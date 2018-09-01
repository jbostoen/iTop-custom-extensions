The master branch works with iTop 2.5.x .
Everything under the **extensions** folder is considered stable.
 
# Folders
- **docs**: contains info/hints on using the extensions in this repository, but also on implementing/using iTop
- **utils**: some utilities I use for debugging and development
- **web/extensions**: extensions which are actively maintained. You can drop and merge this under <iTop folder>/web .

- **wip**: work in progress. Unfinished. Also contains some concepts.


# What is iTop?
iTop is an open source product by Combodo. "iTop stands for IT Operational Portal. iTop is an Open Source web application for the day to day operations of an IT environment. At the heart of iTop is the CMDB (Configuration Management Data Base), this is originally the first part of iTop that was developed." 

For any general support questions (issues/questions NOT caused by my extensions):
- https://github.com/Combodo/iTop (as of end of August, 2018)
- https://sourceforge.net/p/itop/discussion/



# Compatibility / history
I (jbostoen) currently maintain a few **unofficial** extensions (tweaks, extra features) for iTop 2.5.0
Extensions most likely work with any 2.4.x-version. 
It's unknown if extensions work on older versions of iTop. 

I am developing with PHP 7.1

iTop 2.4.x-branch (no longer maintained, most of my work in this master branch should still be compatible if you replace the datamodel XML version "1.5" to "1.4" in the .xml files): 
https://github.com/jbostoen/iTop-custom-extensions/tree/itop-2.4 


The repository originally started with support for iTop 2.3.x-versions, but that branch has been deleted now. I encourage people to migrate to a more recent version.
