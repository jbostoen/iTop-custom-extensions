# copyright   Copyright (C) 2019 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2018-12-05 20:37

# Will create a copy of the files in the template, replacing some stuff.

$extName = Read-Host "`n`nInternal name?`nExample: prefix-class-className-what-changed`n";
$extDescription = Read-Host "`n`nDescription?`n";
$extLabel = Read-Host "`n`nLabel?`nExamples:`n- Class: UserRequest - add origin: in person`n- Feature: Mail to Ticket Automation`n`n"

$extVersionDescription = ""; 									# Version info, if used.

$extAuthor = "Jeffrey Bostoen";									# Author
$extCompany = "";												# Company
$extVersionMin = "2.6.0";										# Min version of iTop
$extVersion = "2.6.$(Get-Date -format 'yyMMdd')";				# Version of this extension
$extReleaseDate = $(Get-Date -format 'yyyy-MM-dd');				# A release date 
$extUrl = "https://github.com/jbostoen/iTop-custom-extensions/" # Some info

# Prevent issues with copy-item, running second time
If( Test-Path -Path "$($extName)" ) {
    Remove-Item -Path "$($extName)" -Recurse -Force
}

# Copy directory 
Copy-Item -Path "template" -Destination "$($extName)" -Recurse  -Container 



# Rename some files
$aFiles = gci -Path "$($extName)"
$aFiles | Foreach-Object {
	Move-Item -Path "$($extName)\$( $_.Name )" -Destination "$($extName)\$( $_.Name -replace "template", $extName )"
}


$files = Get-ChildItem -Path "$($extName)"

$files | ForEach-Object {
	
    [String]$c = (Get-Content "$($extName)\$($_.Name)" -Raw);
	
	$c = $c.replace('{{ extName }}', $extName);
	$c = $c.replace('{{ extDescription }}', $extDescription);
	$c = $c.replace('{{ extLabel }}', $extLabel);
	$c = $c.replace('{{ extUrl }}', $extUrl);
	$c = $c.replace('{{ extVersionDescription }}', $extVersionDescription );
	
	$c = $c.replace('{{ extAuthor }}', $extAuthor);
	$c = $c.replace('{{ extCompany }}', $extCompany);
	$c = $c.replace('{{ extVersionMin }}', $extVersionMin);
	$c = $c.replace('{{ extVersion }}', $extVersion);
	$c = $c.replace('{{ extReleaseDate }}', $extReleaseDate);
	$c = $c.replace('{{ extYear }}', $(Get-Date -Format 'yyyy') );
	
	$c | Set-Content "$($extName)\$($_.Name)"
}



Write-Host -NoNewLine 'Press any key to continue...';
$null = $Host.UI.RawUI.ReadKey('NoEcho,IncludeKeyDown');

