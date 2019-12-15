# jb-ldap

## What?
Imports users from Active Directory and can create several types of iTop objects.

## Features
* Run different LDAP queries to different servers (default settings are possible)
* Use LDAP info in subsequent OQL-queries when creating/updating iTop objects

Placeholders
```
$ldap_user->ldap_attribute$ (replace ldap_attribute with queried LDAP attribute)
$first_object->att_code$ (iTop object. Replace att_code with an attribute of the first found/created object)
$previous->att_code$ (iTop object. Replace att_code with an attribute of the previously found/created object)
```

## Important notes
* Experimental

## Cookbook

PHP:
- how to implement a cron job process in iTop (iScheduledProcess)
- using DBObjectSearch and DBObjectSet to fetch data

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen


