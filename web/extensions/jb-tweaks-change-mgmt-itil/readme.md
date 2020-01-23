# jb-tweaks-change-mgmt-itil

## What?

Modifies Change:
- prevents automatic computation of impacted items (computed). Often undesired: if you manually add a core switch, this could add a lot of 'computed' items which are also impacted - which makes it less obvious to see the ones that really matter (manually added). The tab 'impact analysis' still works the same.

Modified NormalChange:
- allows to not specify an acceptance_comment

Modifies NormalChange, RoutineChange:
- always allow 'reject', even further in the process

Modifies EmergencyChange, NormalChange and RoutineChange:
- prevent 'title' from becoming read-only when state changes to 'implemented'
- prevent 'private_log' from becoming read-only when state changes to 'closed'
- prevent 'impact' from being mandatory

Modifies ApprovedChange, EmergencyChange, NormalChange and RoutineChange:
- prevent 'fallback' from becoming mandatory

## Cookbook

XML
- override a method
- override a property

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019-2020 Jeffrey Bostoen

