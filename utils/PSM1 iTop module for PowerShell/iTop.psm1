# copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2020-04-02 12:17:00

# Variables

# Read configuration from JSON-file.
# Let's make it $global so it can easily be altered
$global:iTopEnvironments = @{}
$Environments = Get-ChildItem -Path "$($PSScriptRoot)\environments" -Include "*.json" -Recurse

$Environments | ForEach-Object {
	$EnvName = $_.Name -Replace ".json", ""
	$global:iTopEnvironments."$EnvName" = ConvertFrom-JSON (Get-Content -Path $_.FullName -Raw)
	
	# Write-Host "Loaded environment $EnvName"
}

# region Common

	function Get-iTopCommand {
	<#
	 .Synopsis
	 Lists commands for iTop

	 .Description
	 Lists commands for iTop

	 .Parameter Credentials
	 Credentials

	 .Example
	 Get-iTopCommand
	 

	#>   
		param(
			
		)
		
		Write-Host "Getting help: Get-Help <name function>"
		Get-Command | Where { $_.Source -eq 'iTop' } | Format-Table

	}
	
# endregion

#region iTop (un)install related functions

	<#
	 .Synopsis
	 Installs iTop unattended.

	 .Description
	 Installs iTop unattended.
	 
	 .Parameter Environment
	 Environment name
			
	 .Example
	 Install-iTopUnattended

	 .Notes
	 2019-08-18: added function
	 2020-04-01: added parameter Environment (optional)
	#>
	function Install-iTopUnattended { 

		param(
			[Alias('env')][String] $Environment = "default"
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		$installScript = $EnvSettings.App.UnattendedInstall.Script
		$installXML = $EnvSettings.App.UnattendedInstall.XML
		$phpExe = $EnvSettings.PHP.Path
		
		If((Test-Path -Path $installScript) -eq $False) {
			throw "Unattended install script not found: $($installScript). Download from iTop Wiki or specify correct location in $($PSScriptRoot)\environments"
		}
		If((Test-Path -Path $installXML) -eq $False) {
			throw "Unattended install XML not found: $($installXML). Specify correct location in $($PSScriptRoot)\environments"
		}
		If((Test-Path -Path $phpExe) -eq $False) {
			throw "PHP.exe not found: $($phpExe). Specify correct location in $($PSScriptRoot)\environments"
		}
		
		# Make config writable
		Set-iTopConfigWritable
		
		# PHP.exe: require statements etc relate to current working directory. 
		# Need to temporarily change this!
		$OriginalDir = (Get-Item -Path ".\").FullName;
		$scriptDir = (Get-Item -Path $installScript).Directory.FullName;
		
		cd $scriptDir
		
		$Cmd = "$($phpExe) $($installScript) --response_file=$($installXML)";
		
		Write-Host "Start: $((Get-Date).ToString('yyyy-MM-dd HH:mm:ss'))"
		Write-Host "Running PHP script for unattended installation..."
		Write-Host "Unattended installation script: $($installScript)"
		Write-Host "Unattended installation XML: $($installXML)"
		Write-Host "Command: $($Cmd)"
		Write-Host "$('*' * 25)"
		
		PowerShell.exe -Command $Cmd
		
		cd $OriginalDir
		
		Write-Host ""
		Write-Host "$('*' * 25)"
		Write-Host "Ran unattended installation. See above for details."
		Write-Host "Finish: $((Get-Date).ToString('yyyy-MM-dd HH:mm:ss'))"
		

	}


	function Set-iTopConfigWritable {
	<#
	 .Synopsis
			Makes iTop configuration file writable

	 .Description
			Makes iTop configuration file writable

	 .Parameter Environment
	 Environment name
	 
	 .Parameter Loop
	 Keep looping (resets the file to writable every 15 seconds)

	 .Example
	 Set-iTopConfigWritable
	 
	 .Example
	 Set-iTopConfigWritable -loop $true

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	#>   
		param(
			[Boolean] $Loop = $False,
			[Alias('env')][String] $Environment = "default"
		)

		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		$Count = 0;
		while($Loop -eq $true -or $Count -eq 0) {
		
			$Count = $Count + 1;

			Get-Item -Path $EnvSettings.App.ConfigFile | Set-ItemProperty -Name IsReadOnly -Value $False
			Write-Host "Set write permissions on iTop configuration file ($($EnvSettings.App.ConfigFile)) (#$($Count))"
			
			If($Loop -eq $true) {
				Start-Sleep -Seconds 15
			}		
		}
	}
	
	

	function Remove-iTopLanguage {
	<#
	 .Synopsis
	 Removes languages (improves performance of existing iTop installations)

	 .Description
	 Removes languages (improves performance of existing iTop installations)

	 .Parameter Confirm
	 Confirm. Defaults to false and does NOT remove language files!
	 
	 .Parameter Environment
	 Environment name
	 
	 .Example
	 Remove-iTopLanguages
	 
	 .Example
	 Remove-iTopLanguages -KeepLanguages @("en", "nl") -confirm $true

	 .Notes
	 2020-04-01: renamed function from Remove-iTopLanguages to Remove-iTopLanguage (consistency)
	           : added parameter Environment (optional)
	           : removed unused parameter Languages (required) (listed languages to remove, had already moved to config file)
	#>
		param(
			[Boolean] $Confirm = $False,
			[Alias('env')][String] $Environment = "default"
		)

		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		$LanguageFiles = Get-ChildItem -Path $EnvSettings.App.Path -Recurse -Include @("*.dict.*.php", "*.dictionary.*.php")

		# Exclude languages that must be kept
		$KeepLanguages = $EnvSettings.App.Languages -Join "|"
		$Regex = "^(" + $KeepLanguages + ").*\.(dict|dictionary)\.(.*?)\.php$"
		
		$LanguageFiles_Remove = $LanguageFiles | Where-Object { 
			($_.Name -notmatch $Regex)
		}
		
		Write-Host "Languages to keep: $($KeepLanguages)"
		
		if($Confirm -eq $False) {
			# Just list
			$LanguageFiles_Remove
			Write-Host "Warning: performed simulation. Did NOT remove languages. Use -Confirm `$true"
		}
		else {
			# Delete
			$LanguageFiles_Remove | Remove-Item		
			Write-Host "Removed all other languages"
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

	 .Parameter Environment
	 Environment name
	 
	 .Parameter Label
	 Specify a label. This is the title of the extension.

	 .Example
	 New-iTopExtension 

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	#>
		param(
			[Parameter(Mandatory=$true)][String] $Name = 'new-name',
			[Parameter(Mandatory=$False)][String] $Description = '',
			[Parameter(Mandatory=$False)][String] $Label = 'Group name: something',
			[Alias('env')][String] $Environment = "default"
		)

		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		# Prevent issues with filename
		# This may be more limiting than what Combodo allows
		If( $Name -notmatch "^[A-z][A-z0-9\-]{1,}$" ) {
			throw "The extension's name preferably starts with an alphabetical character. Furthermore, it preferably consists of alphanumerical characters or hyphens (-) only."
		}

		$Extension_Source = "$($Env:USERPROFILE)\Documents\WindowsPowerShell\Modules\iTop\data\template"
		$Extension_Destination = "$($EnvSettings.Extensions.Path)\$($Name)"

		# Prevent issues with copy-item, running second time
		If( (Test-Path -Path $Extension_Source) -eq $false ) {
			throw "The source folder $($Extension_Source) does not exist. So there is no template available."
		}
		
		# Prevent issues with copy-item, running second time
		If( (Test-Path -Path $Extension_Destination) -eq $true ) {
			throw "The destination folder $($Extension_Destination) already exists."
		}

		# Copy directory 
		Copy-Item -Path $Extension_Source -Destination $Extension_Destination -Recurse -Container 

		# Rename some files
		$Files = Get-ChildItem -Path $Extension_Destination
		$Files | Foreach-Object {
			Move-Item -Path "$($Extension_Destination)\$($_.Name)" -Destination "$($Extension_Destination)\$( $_.Name -Replace "template", $Name )"
		}

		# Replace variables in template files
		$Files = Get-ChildItem -Path "$($Extension_Destination)"

		$Files | ForEach-Object {
		
			[String]$C = (Get-Content "$($Extension_Destination)\$($_.Name)" -Raw);
		
			# Parameters
			$C = $C.replace('{{ ext_Name }}', $Name);
			$C = $C.replace('{{ ext_Description }}', $Description);
			$C = $C.replace('{{ ext_Label }}', $Label);

			# Defaults from variables
			$C = $C.replace('{{ ext_Url }}', $EnvSettings.Extensions.Url);
			$C = $C.replace('{{ ext_VersionDescription }}', $EnvSettings.Extensions.VersionDescription);
			$C = $C.replace('{{ ext_VersionDataModel }}', $EnvSettings.Extensions.VersionDataModel);
			$C = $C.replace('{{ ext_Author }}', $EnvSettings.Extensions.Author);
			$C = $C.replace('{{ ext_Company }}', $EnvSettings.Extensions.Company);
			$C = $C.replace('{{ ext_VersionMin }}', $EnvSettings.Extensions.VersionMin);
			$C = $C.replace('{{ ext_Version }}', ($EnvSettings.Extensions.VersionMin -Replace "\.[0-9]+$","") + "." + $(Get-Date -Format "yyMMdd"));
			
			$C = $C.replace('{{ ext_ReleaseDate }}', $(Get-Date -Format "yyyy-MM-dd"));
			$C = $C.replace('{{ ext_Year }}', $(Get-Date -Format "yyyy"));
			$C = $C.replace('{{ ext_TimeStamp }}', $(Get-Date -Format "yyyy-MM-dd HH:mm:ss") );
		
			$C | Set-Content "$($Extension_Destination)\$($_.Name)"
		}

		Write-Host "Created extension $($Name) from template in $Extension_Source"

	}

	function Rename-iTopExtension {
	<#
	 .Synopsis
	 Renames existing extension with minimal effort (standard file names only). Careful: simple search and replace operation.

	 .Description
	 Renames existing extension with minimal effort (standard file names only) Careful: simple search and replace operation.
	 Always give your extensions a proper name, preferably starting with something like 'yourprefix-'.

	 .Parameter Environment
	 Environment name

	 .Parameter From
	 Original extension name

	 .Parameter To
	 New extension name

	 .Example
	 Rename-iTopExtension -From "some-name" -To "New-name"

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	           : removed parameter Path (optional)
	#>
		param(
			[Parameter(Mandatory=$true)][String] $From = '',
			[Parameter(Mandatory=$true)][String] $To = '',
			[Alias('env')][String] $Environment = "default"
		
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		$Path = $EnvSettings.Extensions.Path
		
		# Rename directory 
		Move-Item -Path "$($Path)\$($From)" -Destination "$($Path)\$($To)" 

		# Rename all files containing the string
		# This searches for default patterns only.
		$Files = Get-ChildItem -Path "$($Path)\$($To)\*" -Include "*.$($From).php", "*.$($From).xml", "extension.xml", "readme.md"

		$Files | ForEach-Object {
		
			# Replace content within those files found above
			[String]$C = (Get-Content "$($Path)\$($To)\$($_.Name)" -Raw);	
			$C = $C.replace($From , $To ); 	
			$C | Set-Content "$($Path)\$($To)\$($_.Name)"
		
			# Rename 
			Move-Item -Path "$($Path)\$($To)\$($_.Name)" -Destination "$($Path)\$($To)\$($_.Name -Replace $($From),$($To) )"
		
		}

		Write-Host "Renamed extension from $($From) to $($To)"

	}
	
	function Set-iTopExtensionReleaseInfo {
	<#
	 .Synopsis
	 Sets iTop extension release info.

	 .Description
	 Sets iTop extension release info. Goes over every PHP file, every datamodel XML and every script file (.bat, .ps1, .psm1, .sh) in the specified iTop's extension folder.
	 Warning: ignores any files in "template" folder.
	 
	 .Parameter Environment
	 Environment name
	 
	 .Example
	 Set-iTopExtensionReleaseInfo

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	#>
		param(
			[Alias('env')][String] $Environment = "default"
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		$sVersionTimeStamp = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
		$sVersionExtensions = $($EnvSettings.Extensions.VersionMin -Replace "\.[0-9]$", "") + '.' + (Get-Date -Format "yyMMdd")
		
		# Either add code to do more proper filtering or just make sure it's only applied to a subset of extenions.
		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "datamodel.*.xml"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {
			$Content = Get-Content "$($_.Directory)\$($_.Name)"
			$Content = $Content -Replace '<itop_design xmlns:xsi="http:\/\/www\.w3\.org\/2001\/XMLSchema-instance" version="1.[0-9]"', "<itop_design xmlns:xsi=`"http://www.w3.org/2001/XMLSchema-instance`" version=`"$($EnvSettings.Extensions.VersionDataModel)`"" 
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
		}

		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "extension.xml"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {
			$Content = Get-Content "$($_.Directory)\$($_.Name)"
			
			# General iTop extension release info
			$Content = $Content -Replace "<version>.*<\/version>", "<version>$($sVersionExtensions)</version>" 
			$Content = $Content -Replace "<company>.*<\/company>", "<company>$($sCompany)</company>" 
			$Content = $Content -Replace "<release_date>.*<\/release_date>", "<release_date>$(Get-Date -Format 'yyyy-MM-dd')</release_date>" 
			$Content = $Content -Replace "<itop_version_min>.*<\/itop_version_min>", "<itop_version_min>$($EnvSettings.Extensions.VersionMin)</itop_version_min>"
			
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
			
		}

		# Update module files
		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "module.*.php"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {
			$unused_but_surpress_output = $_.Name -match "^(.*)\.(.*)\.(.*)$"
			$sModuleShortName = $Matches[2]; # magic
			$Content = Get-Content "$($_.Directory)\$($_.Name)"
			$Content = $Content -Replace "'$($sModuleShortName)\/(.*)',", "'$($sModuleShortName)/$($sVersionExtensions)',"
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
		}


		# Update any PHP file
		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "*.php"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {

			$Content = Get-Content "$($_.Directory)\$($_.Name)"			
			$Content = $Content -Replace "^ \* @version     .*", " * @version     $($sVersionTimeStamp)"			
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
		}
		
		
		# Script files

		# Update any BAT file
		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "*.bat"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {

			$Content = Get-Content "$($_.Directory)\$($_.Name)"			
			$Content = $Content -Replace "^REM version     .*", "REM version     $($sVersionTimeStamp)"			
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
		}
		
		# Update any PS1/PSM1 file
		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "*.ps1", "*.psm1"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {

			$Content = Get-Content "$($_.Directory)\$($_.Name)"			
			$Content = $Content -Replace "^# version     .*", "# version     $($sVersionTimeStamp)"			
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
		}

		# Update any SH file
		$Files = Get-ChildItem -path $EnvSettings.Extensions.Path -File -Recurse -Include "*.sh"

		$Files | Where-Object { $_.DirectoryName -notmatch '\\template$' } | Foreach-Object {

			$Content = Get-Content "$($_.Directory)\$($_.Name)"			
			$Content = $Content -Replace "^# version     .*", "# version     $($sVersionTimeStamp)"			
			$Content | Set-Content "$($_.Directory)\$($_.Name)"
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
	 
	 .Parameter Environment
	 Environment name
	 
	 .Example
	 Start-iTopCron

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	#>
		param(
			[Alias('env')][String] $Environment = "default"
		)

		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		# c:\xampp\php\php.exe c:\xampp\htdocs\itop\web\webservices\cron.php --auth_user=admin --auth_pwd=admin --verbose=1
		$Expression = "$($EnvSettings.PHP.Path) $($EnvSettings.App.Path)\webservices\cron.php" +
			" --auth_user=$($EnvSettings.Cron.User)" +
			" --auth_pwd=$($EnvSettings.Cron.Password)" +
			" --verbose=1"
		Invoke-Expression $Expression
		
	}
	
#endregion

#region iTop REST/JSON API


	function Get-iTopObject {
	<#
	 .Synopsis
	 Uses iTop REST/JSON API to get object (core/get)

	 .Description
	 Uses iTop REST/JSON API to get object (core/get)
	 
	 .Parameter Class
	 Name of class. Can be ommitted if parameter 'key' is a valid OQL-query.
	 
	 .Parameter Environment
	 Environment name
	 
	 .Parameter Key
	 ID of iTop object or OQL-query
	 
	 .Parameter Limit
	 Maximum umber of objects to return. Defaults to 0 (unlimited). From iTop 2.6.1 onwards.
	 
	 .Parameter Page
	 Number of pages to return. Defaults to 1. From iTop 2.6.1 onwards.
	 
	 .Parameter OutputFields
	 Comma separated list of attributes; or * (return all attributes for specified class); or *+ (all attributes - might be more for subclasses)
	 
	 .Example
	 Get-iTopObject -key 123 -class "UserRequest"
	 
	 .Example
	 Get-iTopObject -key "SELECT UserRequest" -OutputFields "id,ref,title"

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	#>
		param(
			[Parameter(Mandatory=$true)][String] $Key,
			[Parameter(Mandatory=$False)][String] $Class = "",
			[Parameter(Mandatory=$False)][String] $OutputFields = "",
			[Parameter(Mandatory=$False)][Int64] $Limit = 0,
			[Parameter(Mandatory=$False)][Int64] $Page = 1,
			[Alias('env')][String] $Environment = "default"
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		# Shortcut, if possible.
		if($Class -eq "") {
		
			$matched = ($Key -match 'SELECT (.*?)( |$)');
			
			if($matched -eq $true) {
				$Class = $matches[1]
			}
			else {
				throw "Specify parameter 'class' if parameter 'key' is not a valid OQL-query"
			}
		
		}
		
		# Output fields
		if($OutputFields -eq "") {
			$OutputFields = $EnvSettings.API.Output_Fields;
		}
			
			
		$JsonData = @{
			'operation'='core/get';
			'key'=$Key;
			'class'=$Class;
			'output_fields'=$OutputFields;
			'limit'=$Limit;
			'page'=$Page
		};
		
		$ArgData = @{
			'version'=$EnvSettings.API.Version;
			'auth_user'=$EnvSettings.API.User;
			'auth_pwd'=$EnvSettings.API.Password;
			'json_data'=(ConvertTo-JSON $JsonData)
		}
		
		$SecurePassword = ConvertTo-SecureString $EnvSettings.API.Password -AsPlainText -Force
		$Credential = New-Object System.Management.Automation.PSCredential($EnvSettings.API.User, $SecurePassword)
		$Request = Invoke-WebRequest $EnvSettings.API.Url -Method "POST" -Body $ArgData -Headers @{"Cache-Control"="no-cache"} -Credential $Credential

		# Valid HTTP response?
		If($Request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$Content = (ConvertFrom-JSON $Request.content)
			
			# iTop API did not return an error
			If($Content.code -eq 0) {
			
				[Array]$Objects = @()
				
				if($Content.objects -ne $Null) {
					$Content.objects | Get-Member -MemberType NoteProperty | ForEach-Object {
						# Gets the properties for each object
						$Object = ($Content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$RecastedFields = [System.Collections.Hashtable]@{};
						
						$Object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$RecastedFields."$($_.Name)" = $Object.fields."$($_.Name)"
						}
						
						$Object.fields = $RecastedFields
						
						$Objects += $Object
					}
				}

				return ,$Objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($Content.code) - $($Content.message)"
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
	 
	 .Parameter Class
	 Name of class.
	 
	 .Parameter Environment
	 Environment name
	 
	 .Parameter Fields
	 HashTable of fields
	 
	 .Parameter OutputFields
	 Comma separated list of attributes; or * (return all attributes for specified class); or *+ (all attributes - might be more for subclasses)
	 	 
	 .Example
	 New-iTopObject -class "UserRequest" -Fields @{'title'='something', 'description'='some description', 'caller_id'="SELECT Organization WHERE name = 'demo'", 'org_id'=1} -OutputFields "*"

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	#>
		param(
			[Parameter(Mandatory=$true)][String] $Class = "",
			[Parameter(Mandatory=$true)][HashTable] $Fields = $Null,
			[Parameter(Mandatory=$False)][String] $OutputFields = "",
			[Parameter(Mandatory=$False)][String] $Comment = "",
			[Alias('env')][String] $Environment = "default"
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		# Fields
		if($Fields.keys.count -lt 1) {
			throw "Specify fields for object"
		}
		
		# Output fields
		if($OutputFields -eq "") {
			$OutputFields = $EnvSettings.API.Output_Fields
		}
		
		# Comment
		if($Comment -eq "") {
			$Comment = $EnvSettings.API.Comment
		}
			
		$JsonData = @{
			'operation'='core/create';
			'class'=$Class;
			'fields'=$Fields;
			'output_fields'=$OutputFields;
			'comment'=$Comment
		};
		
		$ArgData = @{
			'version'=$EnvSettings.API.Version;
			'auth_user'=$EnvSettings.API.User;
			'auth_pwd'=$EnvSettings.API.Password;
			'json_data'=(ConvertTo-JSON $JsonData)
		}
		
		$SecurePassword = ConvertTo-SecureString $EnvSettings.API.Password -AsPlainText -Force
		$Credential = New-Object System.Management.Automation.PSCredential($EnvSettings.API.User, $SecurePassword)
		$Request = Invoke-WebRequest $EnvSettings.API.Url -Method "POST" -Body $ArgData -Headers @{"Cache-Control"="no-cache"} -Credential $Credential

		# Valid HTTP response?
		If($Request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$Content = (ConvertFrom-JSON $Request.content)
			
			# iTop API did not return an error
			If($Content.code -eq 0) {
			
				[Array]$Objects = @()
				
				if($Content.objects -ne $Null) {
					$Content.objects | Get-Member -MemberType NoteProperty | ForEach-Object {
						# Gets the properties for each object
						$Object = ($Content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$RecastedFields = [System.Collections.Hashtable]@{};
						
						$Object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$RecastedFields."$($_.Name)" = $Object.fields."$($_.Name)"
						}
						
						$Object.fields = $RecastedFields
						
						$Objects += $Object
					}
				}

				return ,$Objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($Content.code) - $($Content.message)"
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
	
	 .Parameter Batch
	 Boolean, defaults to 0. If $true: allows to update multiple objects at once.
	 Note: this launches multiple HTTP requests, since iTop only supports updating one iTop object at a time.
	 If an error occurs, any further updating is halted.
	 
	 .Parameter Class
	 Name of class. Can be ommitted if parameter 'key' is a valid OQL-query.
	 
	 .Parameter Environment
	 Environment name
	 
	 .Parameter Fields
	 HashTable of fields
	 
	 .Parameter Key
	 ID of iTop object or OQL-query
	 	 
	 .Parameter OutputFields
	 Comma separated list of attributes; or * (return all attributes for specified class); or *+ (all attributes - might be more for subclasses)
	 	 
	 .Example
	 Set-iTopObject -Key 1 -Class "UserRequest" -Fields @{'title'='something', 'description'='some description', 'caller_id'="SELECT Organization WHERE name = 'demo'", 'org_id'=1} -OutputFields "*"

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	 2020-05-14: added parameter Batch (optional) - boolean to allow batch updates through PS1.
	#>
		param(
			[Parameter(Mandatory=$true)][String] $Key,
			[Parameter(Mandatory=$False)][String] $Class = "",
			[Parameter(Mandatory=$true)][HashTable] $Fields = $Null,
			[Parameter(Mandatory=$False)][String] $OutputFields = "",
			[Parameter(Mandatory=$False)][String] $Comment = "",
			[Parameter(Mandatory=$False)][Boolean] $Batch = $False,
			[Alias('env')][String] $Environment = "default"
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		# Shortcut, if possible.
		if($Class -eq "") {
		
			$matched = ($Key -match 'SELECT (.*?)( |$)');
			
			if($matched -eq $true) {
				$Class = $matches[1]
			}
			else {
				throw "Specify parameter 'class' if parameter 'key' is not a valid OQL-query"
			}
		
		}
		
		# Fields
		if($Fields.keys.count -lt 1) {
			throw "Specify fields for object"
		}
		
		# Output fields
		if($OutputFields -eq "") {
			$OutputFields = $EnvSettings.API.Output_Fields
		}
		
		# Comment
		if($Comment -eq "") {
			$Comment = $EnvSettings.API.Comment
		}	
		
		# Batch (must be after 'key'/'class' check)
		if($Batch -eq $True) {
			
			$Objects = Get-iTopObject -environment $Environment -key $Key -class $Class
			$Objects | ForEach-Object {
				Set-iTopObject -environment $Environment -key "SELECT $($_.Class) WHERE id = $($_.Key)" -fields $Fields -outputFields $OutputFields -comment $Comment
			}
			
			Return
			
		}
		
		$JsonData = @{
			'operation'='core/update';
			'key'=$Key;
			'class'=$Class;
			'fields'=$Fields;
			'output_fields'=$OutputFields;
			'comment'=$Comment
		};
		
		$ArgData = @{
			'version'=$EnvSettings.API.Version;
			'auth_user'=$EnvSettings.API.User;
			'auth_pwd'=$EnvSettings.API.Password;
			'json_data'=(ConvertTo-JSON $JsonData)
		}
		
		$SecurePassword = ConvertTo-SecureString $EnvSettings.API.Password -AsPlainText -Force
		$Credential = New-Object System.Management.Automation.PSCredential($EnvSettings.API.User, $SecurePassword)
		$Request = Invoke-WebRequest $EnvSettings.API.Url -Method "POST" -Body $ArgData -Headers @{"Cache-Control"="no-cache"} -Credential $Credential

		# Valid HTTP response?
		If($Request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$Content = (ConvertFrom-JSON $Request.content)
			
			# iTop API did not return an error
			If($Content.code -eq 0) {
			
				[Array]$Objects = @()
				
				if($Content.objects -ne $Null) {
					$Content.objects | Get-Member -MemberType NoteProperty | ForEach-Object {
						# Gets the properties for each object
						$Object = ($Content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$RecastedFields = [System.Collections.Hashtable]@{};
						
						$Object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$RecastedFields."$($_.Name)" = $Object.fields."$($_.Name)"
						}
						
						$Object.fields = $RecastedFields
						
						$Objects += $Object
					}
				}

				return ,$Objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($Content.code) - $($Content.message)"
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
	 Warning: might delete related objects automatically (just as a normal iTop delete operation would do).
	 
	 .Parameter Batch
	 Boolean, defaults to 0. If $true: allows to update multiple objects at once.
	 Note: this launches multiple HTTP requests, since iTop only supports updating one iTop object at a time.
	 If an error occurs, any further updating is halted.
	 
	 .Parameter Class
	 Name of class. Can be ommitted if parameter 'key' is a valid OQL-query.
	 	 
	 .Parameter Environment
	 Environment name
	 
	 .Parameter Key
	 ID of iTop object or OQL-query
	 
	 .Example
	 Remove-iTopObject -key 1 -class "UserRequest"

	 .Notes
	 2020-04-01: added parameter Environment (optional)
	 2020-05-14: added parameter Batch (optional) - boolean to allow batch deleting through PS1.
	#>
		param(
			[Parameter(Mandatory=$true)][String] $Key,
			[Parameter(Mandatory=$False)][String] $Class = "",
			[Parameter(Mandatory=$False)][String] $Comment = "",
			[Parameter(Mandatory=$False)][Boolean] $Batch = $False,
			[Alias('env')][String] $Environment = "default"
		)
		
		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		# Shortcut, if possible.
		if($Class -eq "") {
		
			$matched = ($Key -match 'SELECT (.*?)( |$)');
			
			if($matched -eq $true) {
				$Class = $matches[1]
			}
			else {
				throw "Specify parameter 'class' if parameter 'key' is not a valid OQL-query"
			}
		
		}
		
		# Comment
		if($Comment -eq "") {
			$Comment = $EnvSettings.API.Comment
		}	
		
		
		# Batch
		if($Batch -eq $True) {
			
			$Objects = Get-iTopObject -environment $Environment -key $Key -class $Class
			$Objects | ForEach-Object {
				Remove-iTopObject -environment $Environment -key "SELECT $($_.Class) WHERE id = $($_.Key)" -comment $Comment
			}
			
			Return
			
		}
		
		$JsonData = @{
			'operation'='core/delete';
			'key'=$Key;
			'class'=$Class;
			'comment'=$Comment
		};
		
		$ArgData = @{
			'version'=$EnvSettings.API.Version;
			'auth_user'=$EnvSettings.API.User;
			'auth_pwd'=$EnvSettings.API.Password;
			'json_data'=(ConvertTo-JSON $JsonData)
		}
		
		$SecurePassword = ConvertTo-SecureString $EnvSettings.API.Password -AsPlainText -Force
		$Credential = New-Object System.Management.Automation.PSCredential($EnvSettings.API.User, $SecurePassword)
		$Request = Invoke-WebRequest $EnvSettings.API.Url -Method "POST" -Body $ArgData -Headers @{"Cache-Control"="no-cache"} -Credential $Credential

		# Valid HTTP response?
		If($Request.StatusCode -eq 200) {
		
			# Valid HTTP response
			$Content = (ConvertFrom-JSON $Request.content)
			
			# iTop API did not return an error
			If($Content.code -eq 0) {
			
				[Array]$Objects = @()
				
				if($Content.objects -ne $Null) {
					$Content.objects | Get-Member -MemberType NoteProperty | ForEach-Object {
						# Gets the properties for each object
						$Object = ($Content.objects | Select-Object -ExpandProperty $_.Name)
						
						# Cast 'fields' to System.Collections.Hashtable 
						$RecastedFields = [System.Collections.Hashtable]@{};
						
						$Object.fields | Get-Member -MemberType NoteProperty | ForEach-Object {
							$RecastedFields."$($_.Name)" = $Object.fields."$($_.Name)"
						}
						
						$Object.fields = $RecastedFields
						
						$Objects += $Object
					}
				}

				return ,$Objects
				
			}
			# iTop API did return an error
			else {
				throw "iTop API returned an error: $($Content.code) - $($Content.message)"
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
	 
	 .Parameter Class
	 Get specific class
	 
	 .Parameter Environment
	 Environment name
	 
	 .Parameter Recurse
	 Process child classes. Defaults to $true.
	 
	 .Example
	 Get-iTopClassesFromNode -recurse $False

	 .Notes
	 2019-08-16: added function
	 2020-04-01: added parameter Environment (optional)
	#>
	function Get-iTopClass() { 

		param(
			 [Parameter(Mandatory=$False)][Boolean]$Recurse = $true,
			 [Parameter(Mandatory=$False)][String]$Class = "",
			[Alias('env')][String] $Environment = "default"
		)

		if($global:iTopEnvironments.Keys -notcontains $Environment) {
			throw "iTop module: no configuration for environment '$($Environment)' in $($PSScriptRoot)\environments"
		}
		
		$EnvSettings = $global:iTopEnvironments."$Environment"
		
		[Xml]$xmlDoc = Get-Content ($EnvSettings.App.Path + "\data\datamodel-production.xml")
		return (Get-iTopClassFromNode -Recurse $Recurse -XmlNode $xmlDoc.itop_design.classes.class -class $Class)

	}

	<#
	 .Synopsis
	 Gets iTop classes from XML Node. Avoid using this, it's meant as a sub function.

	 .Description
	 Gets iTop classes from XML Node. Avoid using this, it's meant as a sub function.
	 
	 .Parameter Class
	 Get specific class
	 
	 .Parameter Recurse
	 Process child classes. Defaults to $true.
	 
	 .Parameter XmlNode
	 XML-node to process
	 
	 .Example
	 Get-iTopClassesFromNode -xmlNode $xmlNode -recurse $False

	 .Notes
	 2019-08-16: added function
	 2020-04-01: added parameter Environment (optional)
	#>
	function Get-iTopClassFromNode() { 

		param(
			 [Parameter(Mandatory=$true)][System.Array]$xmlNode,
			 [Parameter(Mandatory=$False)][Boolean]$Recurse = $true,
			 [Parameter(Mandatory=$False)][String]$Class = ""
		)
		
		[System.Collections.ArrayList]$Results = @()

		# For each class
		$xmlNode | ForEach-Object {

			if($Class -eq "" -or $_.id -eq $Class) {
				$Results += ($_ | Select-Object -Property id, _created_in, _altered_in, _alteration, parent, properties, fields, presentation)
			}

			if($_.class) {
				$SubResults = Get-iTopClassFromNode -xmlNode $_.class -recurse $Recurse -class $Class
				$Results = $Results + $SubResults
			}

		}
		
		return $Results | Sort-Object Id

	}

#endregion
