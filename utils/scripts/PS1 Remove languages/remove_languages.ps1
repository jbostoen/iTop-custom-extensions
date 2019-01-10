
$iTopDirectory = Read-Host "Path of iTop directory?"

# Languages to remove. Keep "en", "nl"
$aLanguages = @("cs", "da", "de", "es_cr", "fr", "hu", "it", "ja", "pt_br", "ru", "tr", "zh");

# Uncomment Remove-Item after testing
$aLanguages | ForEach-Object {
	$lang = $_
	Get-ChildItem -path $iTopDirectory -Recurse -Include "$($lang).dict.*" #| Remove-Item
	Get-ChildItem -path $iTopDirectory -Recurse -Include "$($lang).dictionary.*" #| Remove-Item
}
