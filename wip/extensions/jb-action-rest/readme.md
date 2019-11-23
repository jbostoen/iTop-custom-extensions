# jb-action-rest

## What?
Adds a REST action, to which you can link a trigger (on object creation, on object update, on object delete, ...).

The data posted contains information about the object, the current user and the trigger.
Rather than implementing a very specific integration, the idea is to post all data to any endpoint (service, script).

## Cookbook

PHP
- how to implement a custom Notification that can be triggered (= the action to be executed)
- how to implement a custom EventNotification

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
