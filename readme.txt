Place extensions modules in this directory. They will be taken into account by the setup program and safely copied in case of upgrade.




********************************************************
* Version 2017.02.11 - for iTop 2.3.3
********************************************************

I'm releasing these modules as I'm using them. 
This means they may or may not be suitable for previous and/or future versions. 
You're free to use, share, customize these modules. 

********************************************************
* Warnings
********************************************************

- Fix Class IP Phone -

Please backup your existing IP Phones. It might be best to:
1. export your existing IP Phones to a CSV-file
2. delete them in iTop
3. install the extension,
3. adjust the CSV to the redefined IP Phone class 
4. import from CSV 

It's unknown to me if you'll keep related documents etc. 
I wasn't satisfied with the default IPPhone class, so I never used it and had no data to lose. 





********************************************************
* Install / Use
********************************************************

To install/update extensions, place the folders included in the 'extensions'-folder of your iTop instance.
You need to run http://your-itop-instance/setup . 
Go through the wizard (you'll remember this from your initial iTop installation or upgrade process). 
At one point, you can select the extensions you want to be installed.

If you already use IPPhone objects: see warning above.


********************************************************
* Add Class Monitor 
********************************************************

This is mostly a copy of iTop's "Add Sample Class" extension, which is used in a tutorial. 

However, one important issue has been fixed. If you use the original example, you can create a new monitor CI. 
If you want to define a new model, you can do so, but there's no model type "Monitor". 
You could select a different model type such as "Peripheral". 
But, if you add another monitor CI, same brand, you notice the model you just added for the previous CI won't appear for you to select.


********************************************************
* Fix Class IP Phone
********************************************************

iTop by default considers IP Phones to be an element similar to regular phones. 
In my humble opinion, it lacks an essential Network Devices tab. 

This module redefines the IP Phone object to be a basic network device, 
with three additional properties: internal number, external number, management IP. 
This makes sense, because a lot of IP Phones are small switches - put between a PC and a network switch. 



