# copyright   Copyright (C) 2019 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2019-06-28 21:17

$iTopDirectory = Read-Host "Path of iTop directory?"

# Languages to remove. Keep "en", "nl"
$aLanguages = @("cs", "da", "de", "es_cr", "fr", "hu", "it", "ja", "pt_br", "ru", "tr", "zh");

# Uncomment Remove-Item after testing and confirming it won't delete too much!
$aLanguages | ForEach-Object {
	$lang = $_
	Get-ChildItem -path $iTopDirectory -Recurse -Include "$($lang).dict.*" #| Remove-Item
	Get-ChildItem -path $iTopDirectory -Recurse -Include "$($lang).dictionary.*" #| Remove-Item
}
