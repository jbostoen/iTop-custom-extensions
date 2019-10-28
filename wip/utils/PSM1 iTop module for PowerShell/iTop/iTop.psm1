# copyright   Copyright (C) 2019 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2019-10-28 13:59:53

# Variables

# Read configuration from JSON-file.
# To do: make settings configurable from command line?
# For now, let's make it $global ($global later?) so it can easily be altered
$global:iTopConfig = ConvertFrom-JSON (Get-Content -Path "$($PSScriptRoot)\config.json" -Raw)

#region iTop (un)install related functions

	<#
	 .Synopsis
	 Installs iTop unattended.

	 .Description
	 Installs iTop unattended.
	 
	 .Example
	 Install-iTopUnattended

	 .Notes
	 Since 2019-08-18 
	#>
	function Install-iTopUnattended() { 

		param(
		)
		
		$installScript = $global:iTopConfig.iTop.UnattendedInstallScript;
		$installXML = $global:iTopConfig.iTop.UnattendedInstallXML;
		$phpExe = $global:iTopConfig.php.Path;
		
		If((Test-Path -Path $installScript) -eq $false) {
			throw "Unattended install script not found: $($installScript). Download from iTop Wiki or specify correct location in config.json"
		}
		If((Test-Path -Path $installXML) -eq $false) {
			throw "Unattended install XML not found: $($installXML). Specify correct location in config.json"
		}
		If((Test-Path -Path $phpExe) -eq $false) {
			throw "PHP.exe not found: $($phpExe). Specify correct location in config.json"
		}
		
		# Make config writable
		Set-iTopConfigWritable
		
		# PHP.exe: require statements etc relate to current working directory. 
		# Need to temporarily change this!
		$originalDir = (Get-Item -Path ".\").FullName;
		$scriptDir = (Get-Item -Path $installScript).Directory.FullName;
		
		cd $scriptDir
		
		$cmd = "$($phpExe) $($installScript) --response_file=$($installXML)";
		
		Write-Host "Running PHP script for unattended installation..."
		Write-Host "Unattended installation script: $($installScript)"
		Write-Host "Unattended installation XML: $($installXML)"
		Write-Host "Command: $($cmd)"
		Write-Host "$('*' * 25)"
		
		PowerShell.exe -Command $cmd
		
		cd $originalDir
		
		Write-Host ""
		Write-Host "$('*' * 25)"
		Write-Host "Ran unattended installation. See above for details."
		

	}


	function Set-iTopConfigWritable {
	<#
	.Synopsis
			Makes iTop configuration file writable

	.Description
			Makes iTop configuration file writable

	.Parameter Loop
			Keep looping

	.Example
			Set-iTopConfigWritable
	 
	.Example
			Set-iTopConfigWritable -loop $true

	#>   
		param(
			[Boolean] $loop = $false
		)

		$count = 0;
		while($loop -eq $true -or $count -eq 0) {
		
			$count = $count + 1;

			Get-Item -Path $global:iTopConfig.iTop.ConfigFile | Set-ItemProperty -Name IsReadOnly -Value $false
			Write-Host "Set write permissions on iTop configuration file ($($global:iTopConfig.iTop.ConfigFile)) (#$($count))"
			
			If($loop -eq $true) {
				Start-Sleep -Seconds 15
			}		
		}
	}
	
	

	function Remove-iTopLanguages {
	<#
	 .Synopsis
	 Removes languages (improves performance of existing iTop installations)

	 .Description
	 Removes languages (improves performance of existing iTop installations)

	 .Parameter confirm
	 Confirm. Defaults to false and does NOT remove language files!
	 
	 .Parameter path
	 Path where extension is located
	 
	 .Example
	 Remove-iTopLanguages
	 
	 .Example
	 Remove-iTopLanguages -languages @("en", "nl") -confirm $true

	#>
		param(
			[Parameter(Mandatory=$false)][String] $path = $global:iTopConfig.iTop.Path,
			[String[]] $languages = @("cs", "da", "de", "es_cr", "fr", "hu", "it", "ja", "pt_br", "ru", "tr", "zh"),
			[Boolean] $confirm = $false 
		)

		$languageFiles = Get-ChildItem -Path $global:iTopConfig.iTop.Path -Recurse -Include @("*.dict.*.php", "*.dictionary.*.php")

		# Exclude languages we want to keep
		$regex = "^(?!" + ($global:iTopConfig.iTop.Languages -Join "|") + ").*\.(dict|dictionary)\.(.*?)\.php$"
		
		$languageFiles_Remove = $languageFiles | Where-Object { 
			($_.Name -match $regex -eq $true)
		}
		
		if($confirm -eq $false) {
			# Just list
			$languageFiles_Remove
			Write-Host "Warning: performed simulation. Did NOT remove languages. Use -confirm`$true"
		}
		else {
			# Delete
			$languageFiles_Remove | Remove-Item		
			Write-Host "Removed all languages except: " + ($global:iTopConfig.iTop.Languages -join ", ")
		}

	}
	
#endregion

#region Extension-related functions


	function New-iTopExtension {
	<#
	 .Synopsis
	 Creates new extension from a template

	 .Description
	 Creates new extension from a template

	 .Parameter Name
	 Specify a name. Only alphanumerical characters and hyphen (-) are accepted.

	 .Parameter Description
	 Specify a description. Briefly explains what the extension does.

	 .Parameter Label
	 Specify a label. This is the title of the extension.

	 .Example
	 New-iTopExtension 

	#>
		param(
			[Parameter(Mandatory=$true)][String] $name = 'new-name',
			[Parameter(Mandatory=$false)][String] $description = '',
			[Parameter(Mandatory=$false)][String] $label = 'Group name: something'
		)

		# Prevent issues with filename
		If( $name -notmatch "^[A-z][A-z0-9\-]{1,}$" ) {
			throw "The extension's name preferably starts with alphabetical character. Furthermore, it consists of alphanumerical characters or hyphens (-) only."
		}

		$extension_Source = "$($env:USERPROFILE)\Documents\WindowsPowerShell\Modules\iTop\data\template"
		$extension_Destination = "$($global:iTopConfig.Extensions.Path)\$($name)"

		# Prevent issues with copy-item, running second time
		If( (Test-Path -Path $extension_Destination) -eq $true ) {
			throw "The destination folder $($extension_Destination) already exists!"
		}

		# Copy directory 
		Copy-Item -Path $extension_Source -Destination $extension_Destination -Recurse -Container 

		# Rename some files
		$aFiles = Get-ChildItem -Path $extension_Destination
		$aFiles | Foreach-Object {
			Move-Item -Path "$($extension_Destination)\$($_.Name)" -Destination "$($extension_Destination)\$( $_.Name -replace "template", $name )"
		}

		# Replace variables in template files
		$files = Get-ChildItem -Path "$($extension_Destination)"

		$files | ForEach-Object {
		
			[String]$c = (Get-Content "$($extension_Destination)\$($_.Name)" -Raw);
		
			# Parameters
			$c = $c.replace('{{ ext_Name }}', $name);
			$c = $c.replace('{{ ext_Description }}', $description);
			$c = $c.replace('{{ ext_Label }}', $label);

			# Defaults from variables
			$c = $c.replace('{{ ext_Url }}', $global:iTopConfig.extensions.Url);
			$c = $c.replace('{{ ext_VersionDescription }}', $global:iTopConfig.extensions.VersionDescription );	
			$c = $c.replace('{{ ext_Author }}', $global:iTopConfig.extensions.Author);
			$c = $c.replace('{{ ext_Company }}', $global:iTopConfig.extensions.Company);
			$c = $c.replace('{{ ext_VersionMin }}', $global:iTopConfig.extensions.VersionMin);
			$c = $c.replace('{{ ext_Version }}', $global:iTopConfig.extensions.Version -replace "\.[0-9]+$","") + "." + $(Get-Date -Format "yyMMdd");
			$c = $c.replace('{{ ext_ReleaseDate }}', $global:iTopConfig.extensions.ReleaseDate);
			$c = $c.replace('{{ ext_Year }}', $(Get-Date -Format "yyyy") );
		
			$c | Set-Content "$($extension_Destination)\$($_.Name)"
		}

		Write-Host "Created extension $($name) from template"

	}

	function Rename-iTopExtension {
	<#
	 .Synopsis
	 Renames existing extension with minimal effort (standard file names only)

	 .Description
	 Renames existing extension with minimal effort (standard file names only)

	 .Parameter path
	 Path where extension is located

	 .Parameter from
	 Original extension name

	 .Parameter to
	 New extension name

	 .Example
	 Rename-iTopExtension -From "some-name" -To "New-name"

	#>
		param(
			[Parameter(Mandatory=$false)][String] $path = $global:iTopConfig.extensions.Path,
			[Parameter(Mandatory=$true)][String] $from = '',
			[Parameter(Mandatory=$true)][String] $to = ''
		
		)
	  
		# Rename directory 
		Move-Item -Path "$($path)\$($from)" -Destination "$($path)\$($to)" 

		# Rename all files containing the string
		# This searches for default patterns only.
		$files = Get-ChildItem -path "$($path)\$($to)\*" -include "*.$($from).php","*.$($from).xml","extension.xml","readme.md"

		$files | ForEach-Object {
		
			# Replace content within those files found above
			[String]$c = (Get-Content "$($path)\$($to)\$($_.Name)" -Raw);	
			$c = $c.replace( $from , $to ); 	
			$c | Set-Content "$($path)\$($to)\$($_.Name)"
		
			# Rename 
			Move-Item -Path "$($path)\$($to)\$($_.Name)" -Destination "$($path)\$($to)\$($_.Name -replace $($from),$($to) )"
		
		}

		Write-Host "Renamed extension from $($from) to $($to)"

	}
	
	function Set-iTopExtensionReleaseInfo {
	<#
	 .Synopsis
	 Sets iTop extension release info.

	 .Description
	 Sets iTop extension release info. Goes over every PHP file, every datamodel XML and every script file (.bat, .ps1, .psm1, .sh) in the specified iTop's extension folder.
	 
	 .Example
	 Set-iTopExtensionReleaseInfo

	#>
		param(
		)
		
		$sVersionTimeStamp = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
		$sVersionExtensions = $($global:iTopConfig.Extensions.VersionMin -replace "\.[0-9]$", "") + '.' + (Get-Date -Format "yyMMdd")
		
		# Either add code to do more proper filtering or just make sure it's only applied to a subset of extenions.
		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include datamodel.*.xml

		$aFiles | Foreach-Object {
			$content = Get-Content "$($_.Directory)\$($_.Name)"
			$content = $content -replace '<itop_design xmlns:xsi="http:\/\/www\.w3\.org\/2001\/XMLSchema-instance" version="1.[0-9]"', "<itop_design xmlns:xsi=`"http://www.w3.org/2001/XMLSchema-instance`" version=`"$($global:iTopConfig.Extensions.VersionDataModel)`"" 
			$content | Set-Content "$($_.Directory)\$($_.Name)"
		}

		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include extension.xml

		$aFiles | Foreach-Object {
			$content = Get-Content "$($_.Directory)\$($_.Name)"
			
			# General iTop extension release info
			$content = $content -replace "<version>.*<\/version>", "<version>$($sVersionExtensions)</version>" 
			$content = $content -replace "<company>.*<\/company>", "<company>$($sCompany)</company>" 
			$content = $content -replace "<release_date>.*<\/release_date>", "<release_date>$(Get-Date -Format 'yyyy-MM-dd')</release_date>" 
			$content = $content -replace "<itop_version_min>.*<\/itop_version_min>", "<itop_version_min>$($global:iTopConfig.Extensions.VersionMin)</itop_version_min>"
			
			$content | Set-Content "$($_.Directory)\$($_.Name)"
			
		}

		# Update module files
		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include module.*.php

		$aFiles | Foreach-Object {
			$unused_but_surpress_output = $_.Name -match "^(.*)\.(.*)\.(.*)$"
			$sModuleShortName = $Matches[2]; # magic
			$content = Get-Content "$($_.Directory)\$($_.Name)"
			$content = $content -replace "'$($sModuleShortName)\/(.*)',", "'$($sModuleShortName)/$($sVersionExtensions)',"
			$content | Set-Content "$($_.Directory)\$($_.Name)"
		}


		# Update any PHP file
		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include *.php

		$aFiles | Foreach-Object {

			$content = Get-Content "$($_.Directory)\$($_.Name)"
			
			$content = $content -replace "^ \* @version     .*", " * @version     $($sVersionTimeStamp)"
			
			$content | Set-Content "$($_.Directory)\$($_.Name)"
		}
		
		
		# Script files

		# Update any BAT file
		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include *.bat

		$aFiles | Foreach-Object {

			$content = Get-Content "$($_.Directory)\$($_.Name)"
			
			$content = $content -replace "^REM version     .*", "REM version     $($sVersionTimeStamp)"
			
			$content | Set-Content "$($_.Directory)\$($_.Name)"
		}
		
		# Update any PS1/PSM1 file
		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include *.ps1, *.psm1

		$aFiles | Foreach-Object {

			$content = Get-Content "$($_.Directory)\$($_.Name)"
			
			$content = $content -replace "^# version     .*", "# version     $($sVersionTimeStamp)"
			
			$content | Set-Content "$($_.Directory)\$($_.Name)"
		}

		# Update any SH file
		$aFiles = Get-ChildItem -path $global:iTopConfig.extensions.Path -File -Recurse -Include *.sh

		$aFiles | Foreach-Object {

			$content = Get-Content "$($_.Directory)\$($_.Name)"
			
			$content = $content -replace "^# version     .*", "# version     $($sVersionTimeStamp)"
			
			$content | Set-Content "$($_.Directory)\$($_.Name)"
		}


	}
	
#endregion
  
#region iTop features

	function Start-iTopCron {
	<#
	 .Synopsis
	 Starts iTop Cron jobs

	 .Description
	 Starts iTop Cron jobs
	 
	 .Example
	 Start-iTopCron

	#>
		param(
		)

		# c:\xampp\php\php.exe c:\xampp\htdocs\itop\web\webservices\cron.php --auth_user=admin --auth_pwd=admin --verbose=1
		$expression = "$($global:iTopConfig.php.Path) $($global:iTopConfig.iTop.Path)\webservices\cron.php --auth_user=$($global:iTopConfig.iTopCron.User) --auth_pwd=$($global:iTopConfig.iTopCron.Password) --verbose=1"
		Invoke-Expression $expression
		
	}
	
#endregion

#region iTop REST/JSON API


	function Get-iTopObject {
	<#
	 .Synopsis
	 Uses iTop REST/JSON API to get object (core/get)

	 .Description
	 Uses iTop REST/JSON API to get object (core/get)
	 
	 .Parameter key
	 ID of iTop object or OQL-query
	 
	 .Parameter class
	 Name of class. Can be ommitted if parameter 'key' is a valid OQL-query.
	 
	 .Parameter outputFields
	 Comma separated list of attributes; or * (return all attributes for specified class); or *+ (all attributes - might be more for subclasses)
	 
	 .Example
	 Get-iTopObject -key 123 -class "UserRequest"
	 
	 .Example
	 Get-iTopObject -key "SELECT UserRequest" -OutputFields "id,ref,title"

	#>
		param(
			[Parameter(Mandatory=$true)][String] $key,
			[Parameter(Mandatory=$false)][String] $class = "",
			[Parameter(Mandatory=$false)][String] $outputFields = ""
		)
		
		# Shortcut, if possible.
		if($class -eq "") {
		
			$matched = ($key -match 'SELECT (.*?)( |$)');
			
			if($matched -eq $true) {
				$class = $matches[1]
			}
			else {
				throw "Specify parameter 'class' if parameter 'key' is not a valid OQL-query"
			}
		
		}
		
		# Output fields
		if($outputFields -eq "") {
			$outputFields = $global:iTopConfig.iTopAPI.Output_Fields;
		}
			
			
		$jsonData = @{
			'operation'='core/get';
			'key'=$key;
			'class'=$class;
			'output_fields'=$outputFields
		};
		
		$argData = @{
			'version'=$global:iTopConfig.iTopAPI.Version;
			'auth_user'=$global:iTopConfig.iTopAPI.User;
			'auth_pwd'=$global:iTopConfig.iTopAPI.Password;
			'json_data'=(ConvertTo-JSON $jsonData)
		}
		
		$request = Invoke-WebRequest $global:iTopConfig.iTopAPI.Url -Method "POST" -Body $argData -Headers @{"Cache-Control"="no-cache"}

		# Valid HTTP response?
		If($request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$content = (ConvertFrom-JSON $request.content)
			
			# iTop API did not return an error
			If($content.code -eq 0) {
			
				[Array]$objects = @()
				
				if($content.objects -ne $null) {
					$content.objects | Get-Member -MemberType NoteProperty | ForEach-Object {
					
						# Gets the properties for each object
						$object = ($content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$recastedFields = [System.Collections.Hashtable]@{};
						
						$object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$recastedFields."$($_.Name)" = $object.fields."$($_.Name)"
						}
						
						$object.fields = $recastedFields
						
						$objects += $object						
					}
				}

				return ,$objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($content.code) - $($content.message)"
			}
			
		}
		else {
			# Invalid HTTP response
			throw "Failure to retrieve data from iTop API (HTTP error). Check URL. Other parameters would be validated by iTop REST/JSON API"
		}
		
	}

	function New-iTopObject {
	<#
	 .Synopsis
	 Uses iTop REST/JSON API to create object (core/create)

	 .Description
	 Uses iTop REST/JSON API to create object (core/create)
	 
	 .Parameter class
	 Name of class.
	 
	 .Parameter fields
	 HashTable of fields
	 
	 .Parameter outputFIelds
	 Comma separated list of attributes; or * (return all attributes for specified class); or *+ (all attributes - might be more for subclasses)
	 	 
	 .Example
	 New-iTopObject -class "UserRequest" -Fields @{'title'='something', 'description'='some description', 'caller_id'="SELECT Organization WHERE name = 'demo'", 'org_id'=1} -OutputFields "*"

	#>
		param(
			[Parameter(Mandatory=$true)][String] $class = "",
			[Parameter(Mandatory=$true)][HashTable] $fields = $null,
			[Parameter(Mandatory=$false)][String] $outputFields = "",
			[Parameter(Mandatory=$false)][String] $comment = ""
		)
		
		# Fields
		if($fields.keys.count -lt 1) {
			throw "Specify fields for object"
		}
		
		# Output fields
		if($outputFields -eq "") {
			$outputFields = $global:iTopConfig.iTopAPI.Output_Fields
		}
		
		# Comment
		if($comment -eq "") {
			$comment = $global:iTopConfig.iTopAPI.Comment
		}
			
		$jsonData = @{
			'operation'='core/create';
			'class'=$class;
			'fields'=$fields;
			'output_fields'=$outputFields;
			'comment'=$comment
		};
		
		$argData = @{
			'version'=$global:iTopConfig.iTopAPI.Version;
			'auth_user'=$global:iTopConfig.iTopAPI.User;
			'auth_pwd'=$global:iTopConfig.iTopAPI.Password;
			'json_data'=(ConvertTo-JSON $jsonData)
		}
		
		$request = Invoke-WebRequest $global:iTopConfig.iTopAPI.Url -Method "POST" -Body $argData -Headers @{"Cache-Control"="no-cache"}

		# Valid HTTP response?
		If($request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$content = (ConvertFrom-JSON $request.content)
			
			# iTop API did not return an error
			If($content.code -eq 0) {
			
				[Array]$objects = @()
				
				if($content.objects -ne $null) {
					$content.objects | Get-Member -MemberType NoteProperty | ForEach-Object { 

						# Gets the properties for each object
						$object = ($content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$recastedFields = [System.Collections.Hashtable]@{};
						
						$object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$recastedFields."$($_.Name)" = $object.fields."$($_.Name)"
						}
						
						$object.fields = $recastedFields
						
						$objects += $object
					}
				}

				return ,$objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($content.code) - $($content.message)"
			}
			
		}
		else {
			# Invalid HTTP response
			throw "Failure to retrieve data from iTop API (HTTP error). Check URL. Other parameters would be validated by iTop REST/JSON API"
		}
		
	}
	
	function Set-iTopObject {
	<#
	 .Synopsis
	 Uses iTop REST/JSON API to update object (core/update)

	 .Description
	 Uses iTop REST/JSON API to update object (core/update)
	 
	 .Parameter key
	 ID of iTop object or OQL-query
	 
	 .Parameter class
	 Name of class. Can be ommitted if parameter 'key' is a valid OQL-query.
	 
	 .Parameter fields
	 HashTable of fields
	 	 
	 .Parameter outputFields
	 Comma separated list of attributes; or * (return all attributes for specified class); or *+ (all attributes - might be more for subclasses)
	 	 
	 .Example
	 Set-iTopObject -id 1 -class "UserRequest" -Fields @{'title'='something', 'description'='some description', 'caller_id'="SELECT Organization WHERE name = 'demo'", 'org_id'=1} -OutputFields "*"

	#>
		param(
			[Parameter(Mandatory=$true)][String] $key,
			[Parameter(Mandatory=$false)][String] $class = "",
			[Parameter(Mandatory=$true)][HashTable] $fields = $null,
			[Parameter(Mandatory=$false)][String] $outputFields = "",
			[Parameter(Mandatory=$false)][String] $comment = ""
		)
		
		# Shortcut, if possible.
		if($class -eq "") {
		
			$matched = ($key -match 'SELECT (.*?)( |$)');
			
			if($matched -eq $true) {
				$class = $matches[1]
			}
			else {
				throw "Specify parameter 'class' if parameter 'key' is not a valid OQL-query"
			}
		
		}
		
		# Fields
		if($fields.keys.count -lt 1) {
			throw "Specify fields for object"
		}
		
		# Output fields
		if($outputFields -eq "") {
			$outputFields = $global:iTopConfig.iTopAPI.Output_Fields
		}
		
		# Comment
		if($comment -eq "") {
			$comment = $global:iTopConfig.iTopAPI.Comment
		}	
			
		$jsonData = @{
			'operation'='core/update';
			'key'=$key;
			'class'=$class;
			'fields'=$fields;
			'output_fields'=$outputFields;
			'comment'=$comment
		};
		
		$argData = @{
			'version'=$global:iTopConfig.iTopAPI.Version;
			'auth_user'=$global:iTopConfig.iTopAPI.User;
			'auth_pwd'=$global:iTopConfig.iTopAPI.Password;
			'json_data'=(ConvertTo-JSON $jsonData)
		}
		
		$request = Invoke-WebRequest $global:iTopConfig.iTopAPI.Url -Method "POST" -Body $argData -Headers @{"Cache-Control"="no-cache"}

		# Valid HTTP response?
		If($request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$content = (ConvertFrom-JSON $request.content)
			
			# iTop API did not return an error
			If($content.code -eq 0) {
			
				[Array]$objects = @()
				
				if($content.objects -ne $null) {
					$content.objects | Get-Member -MemberType NoteProperty | ForEach-Object { 

						# Gets the properties for each object
						$object = ($content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$recastedFields = [System.Collections.Hashtable]@{};
						
						$object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$recastedFields."$($_.Name)" = $object.fields."$($_.Name)"
						}
						
						$object.fields = $recastedFields
						
						$objects += $object
					}
				}

				return ,$objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($content.code) - $($content.message)"
			}
			
		}
		else {
			# Invalid HTTP response
			throw "Failure to retrieve data from iTop API (HTTP error). Check URL. Other parameters would be validated by iTop REST/JSON API"
		}
		
	}
	
	function Remove-iTopObject {
	<#
	 .Synopsis
	 Uses iTop REST/JSON API to delete object (core/delete)

	 .Description
	 Uses iTop REST/JSON API to delete object (core/delete)
	 
	 .Parameter key
	 ID of iTop object or OQL-query
	 
	 .Parameter class
	 Name of class. Can be ommitted if parameter 'key' is a valid OQL-query.
	 	 
	 .Example
	 Remove-iTopObject -id 1 -class "UserRequest"

	#>
		param(
			[Parameter(Mandatory=$true)][String] $key,
			[Parameter(Mandatory=$false)][String] $class = "",
			[Parameter(Mandatory=$false)][String] $comment = ""
		)
		
		# Shortcut, if possible.
		if($class -eq "") {
		
			$matched = ($key -match 'SELECT (.*?)( |$)');
			
			if($matched -eq $true) {
				$class = $matches[1]
			}
			else {
				throw "Specify parameter 'class' if parameter 'key' is not a valid OQL-query"
			}
		
		}
		
		# Comment
		if($comment -eq "") {
			$comment = $global:iTopConfig.iTopAPI.Comment
		}	
			
		$jsonData = @{
			'operation'='core/delete';
			'key'=$key;
			'class'=$class
		};
		
		$argData = @{
			'version'=$global:iTopConfig.iTopAPI.Version;
			'auth_user'=$global:iTopConfig.iTopAPI.User;
			'auth_pwd'=$global:iTopConfig.iTopAPI.Password;
			'json_data'=(ConvertTo-JSON $jsonData)
		}
		
		$request = Invoke-WebRequest $global:iTopConfig.iTopAPI.Url -Method "POST" -Body $argData -Headers @{"Cache-Control"="no-cache"}

		# Valid HTTP response?
		If($request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$content = (ConvertFrom-JSON $request.content)
			
			# iTop API did not return an error
			If($content.code -eq 0) {
			
				[Array]$objects = @()
				
				if($content.objects -ne $null) {
					$content.objects | Get-Member -MemberType NoteProperty | ForEach-Object { 

						# Gets the properties for each object
						$object = ($content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$recastedFields = [System.Collections.Hashtable]@{};
						
						$object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$recastedFields."$($_.Name)" = $object.fields."$($_.Name)"
						}
						
						$object.fields = $recastedFields
						
						$objects += $object
					}
				}

				return ,$objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($content.code) - $($content.message)"
			}
			
		}
		else {
			# Invalid HTTP response
			throw "Failure to retrieve data from iTop API (HTTP error). Check URL. Other parameters would be validated by iTop REST/JSON API"
		}
		
	}
	
#endregion


#region iTop Datamodel

	<#
	 .Synopsis
	 Gets iTop classes from datamodel-production.xml

	 .Description
	 Gets iTop classes from datamodel-production.xml
	 
	 .Parameter class
	 Get specific class
	 
	 .Parameter recurse
	 Process child classes. Defaults to $true.
	 
	 .Example
	 Get-iTopClassesFromNode -recurse $false

	 .Notes
	 Since 2019-08-16 
	#>
	function Get-iTopClass() { 

		param(
			 [Parameter(Mandatory=$false)][Boolean]$recurse = $true,
			 [Parameter(Mandatory=$false)][String]$class = ""
		)

		[Xml]$xmlDoc = Get-Content ($global:iTopConfig.iTop.Path + "\data\datamodel-production.xml")
		return (Get-iTopClassFromNode -recurse $recurse -xmlNode $xmlDoc.itop_design.classes.class -class $class)

	}

	<#
	 .Synopsis
	 Gets iTop classes from XML Node. Avoid using this, it's meant as a sub function.

	 .Description
	 Gets iTop classes from XML Node. Avoid using this, it's meant as a sub function.
	 
	 .Parameter class
	 Get specific class
	 
	 .Parameter recurse
	 Process child classes. Defaults to $true.
	 
	 .Parameter xmlNode
	 XML-node to process
	 
	 .Example
	 Get-iTopClassesFromNode -xmlNode $xmlNode -recurse $false

	 .Notes
	 Since 2019-08-16 
	#>
	function Get-iTopClassFromNode() { 

		param(
			 [Parameter(Mandatory=$true)][System.Array]$xmlNode,
			 [Parameter(Mandatory=$false)][Boolean]$recurse = $true,
			 [Parameter(Mandatory=$false)][String]$class = ""
		)
		
		[System.Collections.ArrayList]$results = @()

		# For each class
		$xmlNode | ForEach-Object {

			if($class -eq "" -or $_.id -eq $class) {
				$results += ($_ | Select-Object -Property id, _created_in, _altered_in, _alteration, parent, properties, fields, presentation)
			}

			if($_.class) {
				$subResults = Get-iTopClassFromNode -xmlNode $_.class -recurse $recurse -class $class
				$results = $results + $subResults
			}

		}

		
		return $results | Sort-Object Id

	}

#endregion
