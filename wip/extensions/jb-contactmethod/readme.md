# jb-contactmethod

## What?
Adds a contact methods tab to a Contact. Can easily be extended to support all sorts of data. Dropdown to pick method (e.g. phone, email, social media) and a basic text box (255 chars)

Based on the situation in Belgium (length of phone numbers).

## Cookbook

XML:
- add AttributeLinkedSet to Person
- create new class ContactMethod

PHP:
- make sure ContactMethod follows certain rules. Warning if necessary, strip unnecessary parts where needed

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
