# jb-object-merge

Can't be used for now. Very early development stage.

## What?
Merges objects (primarily meant for any subclass of Ticket)

## Ideas
* generic approach, but primarily aimed at subclasses of Ticket.
* not seen before in this repo: customizing iTop UI (similar to TeemIP)
* one Ticket is chosen as "destination" ticket. User decides which ticket will be kept most intact, and where other info will come from.
* merging of case log entries (sort!) + possible append/prepend
* merging of related functional CIs, functional Contacts
* perhaps merging of WorkOrders. Can something generic be derived from the datamodel? Update IDs in 'lnkX' classes AND keep them unique?
* auto-close or auto-delete original tickets

## Cookbook

XML:
- something

PHP:
- something

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen


