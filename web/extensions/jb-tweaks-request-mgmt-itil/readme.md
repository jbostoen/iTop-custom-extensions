# jb-requestmgmt-tweaks

## What?

Modifies UserRequest:
- prevents automatic computation of impacted items (computed). Often undesired: if you manually add a core switch, this could add a lot of 'computed' items which are also impacted - which makes it less obvious to see the ones that really matter (manually added). The tab 'impact analysis' still works the same.
- adds an 'in person', 'monitoring' origin to UserRequest. 
- changes default impact to 'person' (more common) rather than 'department'
- don't make 'title' and 'caller_id' read-only
- no mandatory solution (usually we just comment in private log)
- always lookup related Change, Incident, Problem (if you only have a couple, it only shows a meaningless dropdown list with friendly names such as P-123456)
- at our organization, we use the 'Email Reply' extension, which allows you to send the contents of public log entries you write to the caller of a UserRequest. However, if you create a new ticket, I often find myself writing something to the user right away in the public log. That email never arrives. So this extension prevents me from writing anything in the public log before the ticket is created.

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
