# Place script in same directory as the extension folder.
# Will simply rename files and change content, only based on 'internal' name.


$extNameS = Read-Host "`n`nSource Internal name?`nExample: prefix-class-className-what-changed`n";
$extNameD = Read-Host "`n`Destination Internal name?`nExample: prefix-class-className-what-changed`n";

  
# Rename directory 
Move-Item -Path "$($extNameS)" -Destination "$($extNameD)" 

# Rename all files containing the string
$files = Get-ChildItem -path "$($extNameD)\*" -include "*.$($extNameS).php","*.$($extNameS).xml","extension.xml"


$files | ForEach-Object {
	
	# Replace content
    [String]$c = (Get-Content "$($extNameD)\$($_.Name)" -Raw);	
	$c = $c.replace( $extNameS , $extNameD ); 	
	$c | Set-Content "$($extNameD)\$($_.Name)"
	
	# Rename 
	Move-Item -Path "$($extNameD)\$($_.Name)" -Destination "$($extNameD)\$($_.Name -replace $($extNameS),$($extNameD) )"
	
}




Write-Host -NoNewLine 'Press any key to continue...';
$null = $Host.UI.RawUI.ReadKey('NoEcho,IncludeKeyDown');

