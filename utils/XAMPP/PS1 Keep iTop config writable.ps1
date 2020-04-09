# copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
# license     https://www.gnu.org/licenses/gpl-3.0.en.html
# version     2020-04-09 17:01:06

# Keep iTop config file writable. Checks every X seconds.

$iTopConfigFile = "C:\xampp\htdocs\iTop\web\conf\production\config-itop.php";

do { 
	
	# Change to make less intensive.
	Sleep -s 5
	
	
	 
	# Until user presses ESC 
	Set-ItemProperty $($iTopConfigFile) -name IsReadOnly -value $false
	
} while ( -not ( $Host.UI.RawUI.KeyAvailable -and ($Host.UI.RawUI.ReadKey("IncludeKeyUp,NoEcho").VirtualKeyCode -eq 27 ) ) )

# Now make read-only
Set-ItemProperty $($iTopConfigFile) -name IsReadOnly -value $true

