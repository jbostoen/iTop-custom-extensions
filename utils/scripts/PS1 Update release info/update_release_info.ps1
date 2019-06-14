
# Needs more testing.

# Pick versions.
$sParentFolder = "D:\GitHub\iTop-custom-extensions\web\extensions" # change to path with your own extensions
$sVersionDate = (Get-Date).ToString("yyyy-MM-dd")
$sVersionItopMin = "2.6.0" 
$sVersionDataModel = "1.6" # match iTop data model version
$sVersionExtensions = "2.6.$( (Get-Date).ToString('yyMMdd'))" # major/minor match iTop version
 

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
	$content = $content -replace '<version>.*<\/version>', "<version>$($sVersionExtensions)</version>" 
	$content = $content -replace '<release_date>.*<\/release_date>', "<release_date>$($sVersionDate)</release_date>" 
	$content = $content -replace '<itop_version_min>.*<\/itop_version_min>', "<itop_version_min>$($sVersionItopMin)</itop_version_min>" 
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}

# Find module files
$aFiles = Get-ChildItem -path $sParentFolder -File -Recurse -Include module.*.php

$aFiles | Foreach-Object {
	$_.Name -match "^(.*)\.(.*)\.(.*)$"
	$sModuleShortName = $Matches[2]; # magic
	$content = Get-Content "$($_.Directory)\$($_.Name)"
	$content = $content -replace "'$($sModuleShortName)\/(.*)',", "'$($sModuleShortName)/$($sVersionExtensions)',"  
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}
