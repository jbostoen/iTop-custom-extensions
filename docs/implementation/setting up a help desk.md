# Setting up a help desk

## Steps
* make a contact (example: John Smith from IT Support) part of a team (example: IT Crowd)
* create a delivery model for the organization (provider = yourself) and add the team(s) which will respond to requests (IT Crowd).
* create a service (and service subcategories where at least one is in production and of the type service request)
* create a customer contract for the organization you want to deliver services to (even if it's your own organization); add the services you created.

## Points to consider

### Teams / shared mailboxes
* a user request is coming from a user. However, when using shared mailboxes in your organization, you often see mails coming from the shared mail address. So it's recommended to create a **user** contact instead with last name = '-', first name: '<team>'. Be careful: if you actually have a **team** contact as well with this email address, processing will fail.
* just change the caller after the ticket's processed automatically

### Who's the actual caller?
* Cut the middle persons. A boss or key user shouldn't be creating requests for others. Direct contact makes things much more efficient.

### No (B)CC!
* make it a policy to reject these mails. Reason: if someone sends the initial mail with others in copy and they all start to reply, you'll have several new tickets. Also, it doesn't make sense to communicate with multiple people about a ticket.

### Notifications
* if you create a ticket, skip the public log, the Email Reply extension won't work right away. 
* to avoid the risk of forgetting about this, there's an extension in this repository which makes it impossible to write a public log until the ticket has been created.

