# PHP Cron Import contacts from LDAP

## What?
This is a sample PHP script which retrieves info from an LDAP. 

It's a way to import users in your Active Directory as iTop contacts (not users!). 
The script retrieves data from the AD and writes it to a MySQL table (created within iTop).
Afterwards, it runs iTop's Data Synchronization.

As of end September 2018, Combodo also released "Data Collector for LDAP" as an extension. It offers some additional features compared to the script provided here; but this PHP script still makes it possible to import info from multiple active directories and shows how to map organizations based on different criteria.

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
