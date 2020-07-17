# jb-software-mgmt

## What?
Improved software and software license management.

This aims to replace the Software Catalog and Software instances.
It will NOT remove the data by default; but it will make old info invisible for the users.

The idea is to implement better software management:
* the software name could be something generic ("MS Office") or a bit more specific ("MS Office 2019")
* more detailed version info could be set on the software instance
* licenses could be linked to users, devices and/or software instance
  * this covers downgrade rights, as you can now link for example a "Windows Server 2019 Standard" license to both "Windows Server 2019 Standard" and "Windows Server 2016 Standard"
  * this covers usage rights, such as "Windows Server User CAL"
  
Caution: influences all the classes listed below.
Except for FunctionalCI, they all get ```( original)``` appended to their class name (label) in iTop.

* FunctionalCI
  * replaces Software list (AttributeLinkedSet): SoftwareInstance -> izSoftwareInstallation
  * replaces neighbour "SoftwareInstance" with izSoftwareInstallation

* DatabaseSchema
* Patch
  * OSPatch
  * SoftwarePatch
* SoftwareInstance
  * DBServer
  * Middleware
  * OtherSoftware
  * PCSoftware
  * WebServer
* WebApplication


## Cookbook

XML:
* new classes
* replacing existing fields/relationships

## Todo
* DatabaseSchema => original DBServer
* izsoftwareinstallation => parent 'FunctionalCI'


## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019-2020 Jeffrey Bostoen

