# PSM1 iTop module for PowerShell

## What?
Custom PowerShell module. 
Intended to replace some stand-alone scripts.

Note`: this is my very first PowerShell module. I realize 'iTop' should be capitalized in the commands, but this wasn't done because of readability.
Written to automate some tasks which are repeated a lot in development.

**iTop API (REST/JSON) functions**
This also inherits the limitations present in the iTop API.
* `Get-iTopObject`: get zero, one or more iTop objects (core/get)
* `New-iTopObject`: create 1 iTop object (core/create)
* `Remove-iTopObject`: delete 1 iTop object (core/delete)
* `Set-iTopObject`: update 1 iTop object (core/update)

**Miscellaneous**
* `Install-iTopUnattended`: performs an unattended (automatic) (re)installation of iTop.
* `Get-iTopClass`: gets overview of each class, including parent and module where it's defined or changed
* `Get-iTopCommand`: shows all iTop commands in this PS1 module.
* `New-iTopExtension`: creates new extension from template
* `Remove-iTopLanguages`: removes all unnecessary language files
* `Rename-iTopExtension`: renames an extension. Renames folder, renames default files, replaces extension name in those files...
* `Set-iTopConfigWritable`: makes or keeps (every 5 seconds) configuration file writable
* `Start-iTopCron`: starts iTop cron jobs

Also exposes variable
* `$global:iTopConfig` - contains all the settings needed for the functions above. 
  * Retrieved from config.json. Can be overwritten during session.
  * Highly advised to import this from an encrypted file rather than from the demo JSON file provided.


## Where to put this module
In PowerShell, enter `$env:PSModulePath` to find out.
Most likely C:\Users\username\Documents\WindowsPowerShell\Modules

## Info on commands
In PowerShell, after doing the above, to see a list of all commands:
`Get-iTopCommand`

To get help, use `Get-Help` and the name of the command
`Get-Help Set-iTopConfigWritable`


## Important security note
The config file is provided as a pointer to what settings are available.

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019-2020 Jeffrey Bostoen
