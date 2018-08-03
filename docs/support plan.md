# iTop version
Currently maintaining this repository with iTop 2.4.x in mind and specifically the **ITIL** version.

# Updates

## Branches, master and legacy
The current plan is to create a new branch for each iTop version which is not just a maintenance release.

This GitHub was created to host extensions (tweaks, extra features) which were originally developed for iTop 2.3.x-releases. 

With the release of iTop 2.5.x:
- **master branch** is now being used to maintain a version which is fully compatible with 2.5.x-releases. 
- **legacy branch** contains the last version of this repository (iTop 2.4.x series). Older branches are deleted because we don't want to encourage staying on older versions (less features, also maybe security issues) 


Future:
- currently planned version for iTop seems to be 2.6.x and 2.7.x, so not expecting major changes when it comes to compatibility. 
*IF* I move to iTop 2.6.x, the same scenario as above will be used. 
Also, the legacy branch for 2.4.x will then be completely removed.

## Goal
The goal is to keep supporting all extensions as long as they make sense. 
If iTop adds or removes a certain feature or changes datamodels, some extensions may become obsolete.

# Compatibility 
* development focus on the vanilla iTop installation.
* these customizations **might** not be compatible with other extensions (including official ones). 
* don't install both the Combodo Mail to Ticket and the version in this repository at the same time.
* no known issues with other official extensions 

# User support
These extensions are developed for internal use at the organization where I work. 
I'll be testing them, but support is not guaranteed.

Most things will be small changes, such as making fields not read-only or hiding things to make workflows more efficient. 
New classes and features may be added - but evaluated regularly. Use these type of extensions at your own risk.
 

