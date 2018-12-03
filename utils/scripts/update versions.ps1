
# Needs more testing.

# Pick versions.
$versionDate = (Get-Date).ToString("yyyy-MM-dd")
$versionItopMin = "2.6.0" 
$versionDataModel = "1.6" # match iTop data model version
$versionExtensionsPrevious = "2.5.180804" # this should be the same for all extensions in the past if this should be updated automatically (dependencies etc)
$versionExtensions = "2.6.$( (Get-Date).ToString('yyMMdd'))" # major/minor match iTop version

# Adapt path 
$parentFolder = "D:\GitHub\iTop-custom-extensions\web\extensions\jb-class-monitor"

# Either add code to do more proper filtering or just make sure it's only applied to a subset of extenions.


$aFiles = Get-ChildItem -path $parentFolder -File -Recurse -Include datamodel.*.xml

$aFiles | Foreach-Object {
	$content = Get-Content "$($_.Directory)\$($_.Name)"  
	$content = $content -replace '<itop_design xmlns:xsi="http:\/\/www\.w3\.org\/2001\/XMLSchema-instance" version="1.[0-9]"', "<itop_design xmlns:xsi=`"http://www.w3.org/2001/XMLSchema-instance`" version=`"$($versionDataModel)`"" 
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}

$aFiles = Get-ChildItem -path $parentFolder -File -Recurse -Include extension.xml

$aFiles | Foreach-Object {
	$content = Get-Content "$($_.Directory)\$($_.Name)"  
	$content = $content -replace '<version>.*<\/version>', "<version>$($versionExtensions)</version>" 
	$content = $content -replace '<release_date>.*<\/release_date>', "<release_date>$($versionDate)</release_date>" 
	$content = $content -replace '<itop_version_min>.*<\/itop_version_min>', "<itop_version_min>$($versionItopMin)</itop_version_min>" 
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}

$aFiles = Get-ChildItem -path $parentFolder -File -Recurse -Include module.*.php

$aFiles | Foreach-Object {
	$content = Get-Content "$($_.Directory)\$($_.Name)"  
	$content = $content -replace "\/$( [System.Text.RegularExpressions.Regex]::Escape($versionExtensionsPrevious) )',", "/$($versionExtensions)',"  
	$content | Set-Content "$($_.Directory)\$($_.Name)"
}
