# PSM1 iTop module for PowerShell

## What?
Custom PowerShell module.
Intended to replace some stand-alone scripts.

Work in progress. Note: this is my very first PowerShell module.

REST/JSON functions 
* Get-iTopObject (core/get)

REST/JSON functions will likely be added:
* New-iTopObject (core/create)
* Remove-iTopObject (core/delete)
* Set-iTopObject (core/update)

Misc
* Set-iTopConfigWritable: makes or keeps (every 5 seconds) configuration file writable
* New-iTopExtension: creates new extension from template
* Remove-iTopLanguages: removes all unnecessary language files
* Rename-iTopExtension: renames an extension. Renames folder, renames default files, replaces extension name in those files...
* Start-iTopCron


## Where to put this module
In PowerShell, run $env:PSModulePath to find out.
Most likely C:\Users\username\Documents\WindowsPowerShell\Modules

## Info on commands
In PowerShell, after doing the above, to see all commands:
Get-Command | Where-Object { $_ -like '*iTop*' }

To get help, use Get-Help and the name of the command
Get-Help Set-iTopConfigWritable

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen