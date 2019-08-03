# copyright   Copyright (C) 2019 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2019-06-28 21:21

# Needs more testing.

# Pick versions.
$sParentFolder = "D:\GitHub\iTop-custom-extensions\web\extensions" # change to path with your own extensions
$sVersionDate = (Get-Date).ToString("yyyy-MM-dd")
$sVersionItopMin = "2.6.0" 
$sVersionDataModel = "1.6" # match iTop data model version
$sVersionExtensions = "2.6.$( (Get-Date).ToString('yyMMdd'))" # major/minor match iTop version
$sCompany = "none";

# Either add code to do more proper filtering or just make sure it's only applied to a subset of extenions.
$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include datamodel.*.xml

$aFiles | Foreach-Object {
	$content = Get-Content "$($_.Directory)\$($_.Name)"
	$content = $content -replace '<itop_design xmlns:xsi="http:\/\/www\.w3\.org\/2001\/XMLSchema-instance" version="1.[0-9]"', "<itop_design xmlns:xsi=`"http://www.w3.org/2001/XMLSchema-instance`" version=`"$($sVersionDataModel)`"" 
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}

$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include extension.xml

$aFiles | Foreach-Object {
	$content = Get-Content "$($_.Directory)\$($_.Name)"
	
	# General iTop extension release info
	$content = $content -replace '<version>.*<\/version>', "<version>$($sVersionExtensions)</version>" 
	$content = $content -replace '<company>.*<\/company>', "<company>$($sCompany)</company>" 
	$content = $content -replace '<release_date>.*<\/release_date>', "<release_date>$($sVersionDate)</release_date>" 
	$content = $content -replace '<itop_version_min>.*<\/itop_version_min>', "<itop_version_min>$($sVersionItopMin)</itop_version_min>"
	
	$content | Set-Content "$($_.Directory)\$($_.Name)"
	
}

# Update module files
$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include module.*.php

$aFiles | Foreach-Object {
	$unused_but_surpress_output = $_.Name -match "^(.*)\.(.*)\.(.*)$"
	$sModuleShortName = $Matches[2]; # magic
	$content = Get-Content "$($_.Directory)\$($_.Name)"
	$content = $content -replace "'$($sModuleShortName)\/(.*)',", "'$($sModuleShortName)/$($sVersionExtensions)',"
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}


# Update any PHP file
$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include *.php

$aFiles | Foreach-Object {

	$content = Get-Content "$($_.Directory)\$($_.Name)"
	
	# Info on license on top of file
	$content = $content -replace '\* @version     -', "* @version     $($sVersionDate)"
	
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}

# Update any PS1 file
$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include *.ps1, *.psm1

$aFiles | Foreach-Object {

	$content = Get-Content "$($_.Directory)\$($_.Name)"
	
	# Info on license on top of file
	$content = $content -replace '# version     .*', "# version     $($sVersionDate)"
	
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}


# Update any BAT file
$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include *.bat

$aFiles | Foreach-Object {

	$content = Get-Content "$($_.Directory)\$($_.Name)"
	
	# Info on license on top of file
	$content = $content -replace 'REM version     .*', "REM version     $($sVersionDate)"
	
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}
