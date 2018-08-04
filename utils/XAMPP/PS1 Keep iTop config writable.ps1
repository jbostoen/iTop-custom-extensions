$iTopConfFile = "C:\xampp\htdocs\iTop\web\conf\production\config-itop.php";

do { 
	
	# Change to make less intensive.
	Sleep -s 5
	
	
	 
	# Until user presses ESC 
	Set-ItemProperty $($iTopConfFile) -name IsReadOnly -value $false
	
} while ( -not ( $Host.UI.RawUI.KeyAvailable -and ($Host.UI.RawUI.ReadKey("IncludeKeyUp,NoEcho").VirtualKeyCode -eq 27 ) ) )

# Now make read-only
Set-ItemProperty $($iTopConfFile) -name IsReadOnly -value $true

