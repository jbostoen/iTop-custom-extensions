 


********************************************************
* Version 2017.02.11 - for iTop 2.3.3
********************************************************

I'm releasing these modules as I'm using them. 
This means they may or may not be suitable for previous and/or future versions. 
You're free to use, share, customize these modules. 

********************************************************
* Warnings
********************************************************

- Class: IP Devices -

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
* Class: Change (ITIL-based) - don't automatically add impacted CI's
********************************************************
 
While it might be beneficial to see all issues which might have impacted a CI by looking at the CI's tickets, 
it makes the Change's related CI's tab full of irrelevant 'computed' CI's. 

Say you add a firewall rule to open up a new port for one specific application. 
On adding the firewall rule, iTop adds all related applications to the related CI's tab. 
While it's good to have an Impact Analysis tab, this behavior makes the actual change more obscure.



********************************************************
* Class: IP Devices
********************************************************
 
Originated as an adjustment to IP Phones.
However, to make it more fitting for future purposes, I first added a class IP Device. 
It's very similar to a network device, but it's been defined at the same level as PC, Printer and DataCenter Device.
( Network Device is a subclass of DataCenter Device already ).

iTop by default considers IP Phones to be an element similar to regular phones. 
In my humble opinion, it lacks an essential Network Devices tab. 

This module redefines the IP Phone class to be a basic IP Device, 
with some additional properties: internal number and external number. 
This makes sense, because a lot of IP Phones are small switches - put between a PC and a network switch. 

It also adds an IP Camera class.


 
********************************************************
* Class: Monitor
********************************************************

This is mostly a copy of iTop's "Add Sample Class" extension, which is used in a tutorial. 

However, one important issue has been fixed. If you use the original example, you can create a new monitor CI. 
If you want to define a new model, you can do so, but there's no model type "Monitor". 
You could select a different model type such as "Peripheral". 
But, if you add another monitor CI, same brand, you notice the model you just added for the previous CI won't appear for you to select.



********************************************************
* Class: User Request - don't make title read-only on resolve
********************************************************
 
Tickets are quickly marked as resolved. This makes the title attribute read-only. 
If you still want to give the ticket a more meaningful title, you need to reopen the ticket. 
However, this may lead to users and agents receiving notifications (if configured). 

This change in the datamodel makes sure you can modify the title any time without consequences.





