# Place script in same directory as the extension folder.
# Will simply rename files and change content, only based on 'internal' name.


$extNameS = Read-Host "`n`nSource Internal name?`nExample: prefix-class-className-what-changed`n";
$extNameD = Read-Host "`n`Destination Internal name?`nExample: prefix-class-className-what-changed`n";

  
# Copy directory 
Move-Item -Path "$($extNameS)" -Destination "$($extNameD)" 

 
# Rename some files
Move-Item -Path "$($extNameD)\datamodel.$($extNameS).xml" -Destination "$($extNameD)\datamodel.$($extNameD).xml"
Move-Item -Path "$($extNameD)\en.dict.$($extNameS).php" -Destination "$($extNameD)\en.dict.$($extNameD).php"
Move-Item -Path "$($extNameD)\model.$($extNameS).php" -Destination "$($extNameD)\model.$($extNameD).php"
Move-Item -Path "$($extNameD)\module.$($extNameS).php" -Destination "$($extNameD)\module.$($extNameD).php"

$files = Get-ChildItem -Path "$($extNameD)"

$files | ForEach-Object {
	
    [String]$c = (Get-Content "$($extNameD)\$($_.Name)" -Raw);
	
	$c = $c.replace( $extNameS , $extNameD ); 
	
	$c | Set-Content "$($extNameD)\$($_.Name)"
}



Write-Host -NoNewLine 'Press any key to continue...';
$null = $Host.UI.RawUI.ReadKey('NoEcho,IncludeKeyDown');

