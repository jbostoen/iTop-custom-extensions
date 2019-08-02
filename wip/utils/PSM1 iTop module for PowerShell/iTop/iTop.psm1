
# Variables

# About iTop
$iTop_Root = "C:\xampp\htdocs\iTop\web";								# Path to iTop root (/web included)
$iTop_ConfigFile = "$($iTop_Root)\conf\production\config-itop.php";		# Path to iTop config file
$iTop_Extensions = "$($iTop_Root)\extensions";							# Path to iTop Extensions

# Defaults for new extensions
$ext_VersionDescription = ""; 											# Version info, if used.
$ext_Author = "Jeffrey Bostoen";										# Author
$ext_Company = "";														# Company
$ext_VersionMin = "2.6.0";												# Min version of iTop
$ext_Version = "2.6.$(Get-Date -format 'yyMMdd')";						# Version of this extension
$ext_ReleaseDate = $(Get-Date -format 'yyyy-MM-dd');					# A release date 
$ext_Url = "https://github.com/jbostoen/iTop-custom-extensions/" 		# Some info

# About PHP (XAMPP preferred)
$php_Path = "c:\xampp\php\php.exe"										# Path to PHP
$php_iTop_Cron_User = "admin"											# iTop user to run cron
$php_iTop_Cron_Password = "admin"										# iTop user passwords to run cron


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

        Get-Item -Path $iTop_ConfigFIle | Set-ItemProperty -Name IsReadOnly -Value $false
        Write-Host "Made iTop configuration file writable ($($iTop_ConfigFIle)) (#$($count))"
        
		If($loop -eq $true) {
			Start-Sleep -Seconds 15
		}		
    }
}


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
    $extension_Destination = "$($iTop_Extensions)\$($name)"

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
	    $c = $c.replace('{{ ext_Url }}', $ext_Url);
	    $c = $c.replace('{{ ext_VersionDescription }}', $ext_VersionDescription );	
	    $c = $c.replace('{{ ext_Author }}', $ext_Author);
	    $c = $c.replace('{{ ext_Company }}', $ext_Company);
	    $c = $c.replace('{{ ext_VersionMin }}', $ext_VersionMin);
	    $c = $c.replace('{{ ext_Version }}', $ext_Version);
	    $c = $c.replace('{{ ext_ReleaseDate }}', $ext_ReleaseDate);
	    $c = $c.replace('{{ ext_Year }}', $(Get-Date -Format 'yyyy') );
	
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
        [Parameter(Mandatory=$false)][String] $path = $iTop_Extensions,
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

function Remove-iTopLanguages {
<#
 .Synopsis
 Removes languages

 .Description
 Removes languages

 .Example
 Remove-iTopLanguages
 
 .Example
 Remove-iTopLanguages -languages @("en", "nl") -confirm $true

#>
    param(
        [String[]] $languages = @("cs", "da", "de", "es_cr", "fr", "hu", "it", "ja", "pt_br", "ru", "tr", "zh"),
        [Boolean] $confirm = $false 
    )

    # Uncomment Remove-Item after testing and confirming it won't delete too much!
    $languages | ForEach-Object {
	    $lang = $_

        if( $confirm -eq $false ) {
	        Get-ChildItem -path $iTop_Root -Recurse -Include "$($lang).dict.*"
	        Get-ChildItem -path $iTop_Root -Recurse -Include "$($lang).dictionary.*"
        }
        elseif( $confirm -eq $true ) {
	        Get-ChildItem -path $iTop_Root -Recurse -Include "$($lang).dict.*" | Remove-Item
	        Get-ChildItem -path $iTop_Root -Recurse -Include "$($lang).dictionary.*" | Remove-Item
        }
    }

}

function Start-iTopCron {
<#

 .Example
 Start-iTopCron

#>
    param(
    )

    # c:\xampp\php\php.exe c:\xampp\htdocs\itop\web\webservices\cron.php --auth_user=admin --auth_pwd=admin --verbose=1
	$expression = "$($php_Path) $($iTop_Root)\webservices\cron.php --auth_user=$($php_iTop_Cron_User) --auth_pwd=$($php_iTop_Cron_Password) --verbose=1"
	Invoke-Expression $expression
	
}
