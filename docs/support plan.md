# Goal
The goal is to support all extensions as long as they make sense. 
If iTop adds, changes or removes certain features or parts of the datamodel, some extensions may become obsolete.

# Compatibility 
* development focus on the iTop Community Edition. ITIL-version of modules. No additional extensions.
* these customizations **might** not be compatible with other extensions (including official ones). 
* if there are known incompatibility issues (only with **official** extensions), it will be clearly mentioned.

# Updates and branches
The current plan is to create a new branch for each iTop version which is at least a minor release (second number in version).
There won't be a separate branch for maintenance releases (third number in version).

This GitHub was created to host extensions (tweaks, extra features) which were originally developed for iTop 2.3.x-releases. 

With the official release of iTop 2.7.x, these are the remaining branches:
* **master** used for development. It may also contain changes based on a beta version of an upcoming iTop release.
* **itop-2.7** used for more stable releases of this repository. It should be fully compatible with the latest iTop version.
  * Older branches are deleted since it's never encouraged to stay on older versions (less features, increased risk of known vulnerabilities)

Future:
* planned versions for iTop seem to be 2.8.x up to 2.11.x.
* iTop 2.7.x seems to be a LTS release
* Major changes are not expected when it comes to compatibility in 2.8 when it comes to features; but there will be a GUI overhaul of the console which might impact the extensions in this repo.

*IF* I move to newer iTop versions, the same scenario as above will be used for branches.
There will be maximum 3 branches:
* 'master' (current iTop release or work focused on compatibility with an iTop beta)
* 'itop-last_main_version'
* 'itop-last_long_term_support_version'


# Support
These extensions are developed for internal use at the organization I work for.
I'll be testing them personally, but support is not guaranteed.

Some extensions simply alter the default datamodel and adapt workflows to our situation.
New classes and features have been added as well - but they are evaluated regularly. Use these type of extensions at your own risk.
