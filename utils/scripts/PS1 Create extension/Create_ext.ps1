# Will create a copy of the files in the template, replacing some stuff.

$extName = Read-Host "`n`nInternal name?`nExample: prefix-class-className-what-changed`n";
$extDescription = Read-Host "`n`nDescription?`n";
$extLabel = Read-Host "`n`nLabel?`nExamples:`n- Class: UserRequest - add origin: in person`n- Feature: Mail to Ticket Automation`n`n"

$extVersionDescription = ""; 									# Version info, if used.

$extAuthor = "jbostoen";										# Author
$extCompany = "";												# Company
$extVersionMin = "2.4.0";										# Min version of iTop
$extVersion = "2.4.$(Get-Date -format 'yyMMdd')";				# Version of this extension
$extReleaseDate = $(Get-Date -format 'yyyy-MM-dd');				# A release date 
$extUrl = "https://github.com/jbostoen/iTop-custom-extensions/" # Some info

# Prevent issues with copy-item, running second time
If( Test-Path -Path "$($extName)" ) {
    Remove-Item -Path "$($extName)" -Recurse -Force
}

# Copy directory 
Copy-Item -Path "template" -Destination "$($extName)" -Recurse  -Container 



# Rename some files
Move-Item -Path "$($extName)\datamodel.template.xml" -Destination "$($extName)\datamodel.$($extName).xml"
Move-Item -Path "$($extName)\en.dict.template.php" -Destination "$($extName)\en.dict.$($extName).php"
Move-Item -Path "$($extName)\model.template.php" -Destination "$($extName)\model.$($extName).php"
Move-Item -Path "$($extName)\module.template.php" -Destination "$($extName)\module.$($extName).php"

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
	
	$c | Set-Content "$($extName)\$($_.Name)"
}



Write-Host -NoNewLine 'Press any key to continue...';
$null = $Host.UI.RawUI.ReadKey('NoEcho,IncludeKeyDown');

