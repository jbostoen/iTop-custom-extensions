# jb-requestmgmt-tweaks

## What?

Modifies UserRequest:

* prevents automatic computation of impacted items (computed). Often undesired: if a core switch is added manually, this could add a lot of 'computed' items which are also impacted - which makes it less obvious to see the ones that really matter (manually added). The tab 'impact analysis' still works the same.
* adds fields:
  * reminder: timestamp, useful for follow-up* 
  * adds values to 'origin':
    * in person
    * monitoring
  * always lookup related Change, Incident, Problem (if there are only a few, it only shows a meaningless dropdown list with friendly names such as P-123456)
  * third party ref
* changes in fields
  * default impact set to 'person' (more common) rather than 'department'
* changes in states
  * don't make 'title' and 'caller_id' read-only
  * no mandatory solution (follow-up and solution is in private log)
  * the 'Email Reply' extension can send the contents of public log entries to the caller of a UserRequest. However, an agent might immediately write something in the public_log . That email never arrives. The new behavior is to prevent writing anything in the public log before the ticket is created.
  * don't make service, service subcategory read-only for closed UserRequests

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
