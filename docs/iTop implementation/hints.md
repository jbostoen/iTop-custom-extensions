
# Best practices


## Contracts

Point of view:
* provider contracts = services you get
* customer contracts = services you deliver



## Documents

Revisions (history of versions):
iTop keeps a history! So if you keep configs in iTop, you can simply edit an existing document and upload the latest version rather than creating a new document! 


## Helpdesk

### Teams / shared mailboxes
* a user request is coming from a user. However, when using shared mailboxes in your organization, you often see mails coming from the shared mail address. So it's recommended to create a **user** contact instead with last name = '-', first name: '<team>'. Be careful: if you actually have a **team** contact as well with this email address, processing will fail.
* just change the caller after the ticket's processed automatically

### Who's the actual caller?
* Cut the middle persons. A boss or key user shouldn't be creating contacts for others. Direct contact makes things much more efficient.

### No (B)CC!
* make it a policy not to accept these mails. Reason: if someone sends the initial mail with others in copy and they all start to reply, you'll have several new tickets. Also, it doesn't make sense to communicate with multiple people about a ticket.

### Notifications
* if you create a ticket, skip the public log, the Email Reply extension won't work right away.


## Functional CI

### IP Phone
The Combodo version doesn't allow you to link an IP Phone (often a switch at the same time) to network devices and clients. Either use the Network Device or use a customized data model.

### Monitor
There is (was?) an issue in the official Combodo example, where you'll be unable to create a model in the Monitor class. It's fixed in our version.


# Linking devices

**PC <=> switch:**
* POV CI PC: downlink from switch . Network port = belongs to switch, device port = belongs to device (PC)
* POV CI Network Device: downlink to PC. Network port = belongs to switch, device port = belongs to device (PC)

**switch <=> switch:**
* CI switch (Network Device): uplink. Network port = from regular switch, device port = belongs to other switch



# Triggers
## Notifications, triggers: States
A state is really a phase in the lifecycle. 'on entering/leaving a state' should be seen as: ticket is assigned, reassigned, closed, resolved. There's currently no standard implementation in iTop to send a notification on changing an attribute (e.g. server = inactive).


# Themes
* css -> contains two .scss-files with some variables, but lots of them are still hardcoded...
