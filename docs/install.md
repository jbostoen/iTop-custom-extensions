
# How to install / use
**Upgrading?**
- See Upgrade notes at the bottom of this document.

**Installing?**
- Unzip web/extensions to <itop path>/web/extensions
- Re-run the setup wizard of your iTop instance ( e.g. http://itop/web/setup ).

**In case of existing IP Phones (default iTop class):**
Please backup your existing IP Phones **before** installing the _Class: IP Devices_-module. It might be best to:
1. export your existing IP Phones to a CSV-file
2. delete them in iTop
3. install the extension,
3. adjust the CSV to the redefined IP Phone class 
4. import from CSV 

It's unknown to me if you'll keep related documents etc. 
I wasn't satisfied with the default IPPhone class, so I never used it and had no data to lose. 


**Upgrading?**
Always:
- Delete all folders starting with 'jb-' in <itop path>/web/extensions

Previous version number lower than 2.4.180309 ? (before 2018-03-09)?
- make sure mailbox ***policies are NOT set to 'Mark as error'***

