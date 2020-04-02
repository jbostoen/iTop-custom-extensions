# PSM1 iTop module for PowerShell

## What?
Custom PowerShell module.
Note: this is my very first PowerShell module.

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
* `$global:iTopEnvironments` - contains all the settings needed for the functions above. Loaded during initialization of PowerShell ISE or Console.
  * Retrieved from JSON files containing configuration (see below). Can be overwritten during session with PowerShell.
  * Highly advised to import this from an encrypted file rather than from the demo JSON file provided.



## Configuration example

"default" is the name of the default environment and should always be included.
You can add more environments by adding a 'environment-name.json' file in %UserProfile%\Documents\WindowsPowerShell\Modules\iTop\environments

**API settings** are useful in all cases.
All other settings are primarily when you have iTop installed on the same machine as where you are running the PowerShell module on.


```
{

		 
	
	"API": {
		"Url":  "http://127.0.0.1/itop/web/webservices/rest.php",
		"Version":  "1.3",
		"Password":  "admin",
		"Output_Fields":  "*",
		"User":  "admin"
	},
	
	"App":  {
		"Path":  "C:\\xampp\\htdocs\\iTop\\web", 
		"ConfigFile":  "C:\\xampp\\htdocs\\iTop\\web\\conf\\production\\config-itop.php", 
		
		
		"UnattendedInstall": {
			"Script":  "C:\\xampp\\htdocs\\iTop\\web\\toolkit\\unattended_install.php", 
			"XML":  "C:\\xampp\\htdocs\\iTop\\web\\toolkit\\unattended_install.xml" 
		},
		 
		"Languages": [
			"en",
			"nl"
		] 
	},
	
	"Extensions": {
	   "Path":  "C:\\xampp\\htdocs\\iTop\\web\\extensions", 
	   "Url":  "https:
	   "VersionMin":  "2.6.0", 
	   "VersionDataModel":  "1.6", 
	   "Author":  "Jeffrey Bostoen", 
	   "Company":  "", 
	   "VersionDescription":  "" 
	},
	
	"Cron": {
		"User": "admin",
		"Password": "admin"
	}
	
}

```


## Upgrade notes

**To version 2020-04-02 and higher:**
You may need to rewrite some scripts. Config.json needs adjustments.
This version was made to better support different iTop environments (for instance test and production).

* Remove-iTopLanguages is now named Remove-iTopLanguage
* config.json: supports multiple environments now. Must be reconfigured, see example. Preferably make sure there's at least an environment named "default"

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019-2020 Jeffrey Bostoen
