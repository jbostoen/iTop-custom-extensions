# jb-ticket-merge

Not recommended to be used for now. Very early development stage.
Developed in personal time.

If you are interested in a quick development of this extension or a specific feature, consider getting in touch and sponsor it.

## What?
Merges any subclass of Ticket (currently limited, may be enhanced to support other classes too)

Currently you can select at least two Tickets and decide to merge them into one Ticket.

Attribute types to be merged:
* AttributeCaseLog (example: private and public logs)
* AttributeLinkedSet (example: related WorkOrders)
* AttributeLinkedSetIndirect (example: related Contacts through lnkContactToTicket)

For now:
* Only supports merging into one of the chosen Tickets. (might offer to create a new Ticket in the future)
* Case logs are automatically put chronologically. (might offer more options on that in the future)
* Callers can be added as related contacts
* Attachments are cloned and also added to the target Ticket

Options:
* specify who is allowed to merge (Profiles)
* specify which attributes (must be of the type above) can be merged: which ones are allowed to be merged, must be merged (required), checked by default (checked)
* specify if callers of the Tickets should be added as related Contacts (except for the Caller of the target Ticket)
* specify on what attribute the list of possible target Tickets should be sorted (by default: oldest ID = oldest Ticket)

Still a lot on the to-do list, including:
* option to apply stimulus (resolve, close merged Tickets)
* option to delete merged Tickets
* option to set attribute on merged tickets and/or target ticket; also add log entry
* option to merge into new Ticket (needs sponsoring; contact me)

## Cookbook

PHP:
- how to introduce custom iTop pages
- how to add a menu item in the Other Actions menu
- use built-in iTop methods to obtain attribute list for a class
- use built-in iTop methods to display lists
- use built-in iTop methods to check user rights
- use built-in iTop methods to prevent malicious actions
- ...

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen



