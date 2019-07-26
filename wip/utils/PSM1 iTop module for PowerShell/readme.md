# PSM1 iTop module for PowerShell

## What?
Custom PowerShell module.
Intended to replace some stand-alone scripts.

Work in progress.

* Set-iTopConfigWritable: makes or keeps (every 5 seconds) configuration file writable
* New-iTopExtension: creates new extension from template
* Remove-iTopLanguages: removes all unnecessary language files
* Rename-iTopExtension: renames an extension. Renames folder, renames default files, replaces extension name in those files...


REST/JSON functions will likely be added:
* Get-iTopObject (core/get)
* New-iTopObject (core/create)
* Remove-iTopObject (core/delete)
* Set-iTopObject (core/update)

Note: this is my very first PowerShell module.

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen