# What?
This **Mail to Ticket automation** is a **fork** from Combodo's Mail to Ticket Automation (originally based on their version 3.0.7, but also includes the changes up to 3.1.0 so far).
Some fixes from this version were accepted by Combodo back in August 2018 and are now part of the official version.
What is different? In a few cases, Combodo's implementation of Mail to Ticket Automation was not sufficient enough. This extension offers some additional policies.
One thing is important here: it's actually recommended to set **use_message_id_as_uid** to 'true' in the config file in a lot of cases to avoid duplicates (Combodo sets it to 'false' by default but this could be very undesired for IMAP connections!). Otherwise, configuration settings are mostly similar to https://www.itophub.io/wiki/page?id=extensions%3Aticket-from-email
For IMAP, here's a quick example on the configuration options (config-itop.php).
Also make sure the PHP IMAP extension is enabled.
```
	'imap_options' => array (
	  0 => 'imap',
	  1 => 'ssl',
	  2 => 'novalidate-cert',
	),
```
# Basics about policies
Most of them have:
* eMail - behavior: always 'bounce and delete', 'delete', 'mark as undesired'. Sometimes 'mark as error'. Sometimes fallbacks are possible.
* bounce subject - message subject when message is bounced back to sender (caller)
* bounce message - message content when message is bounced back to sender (caller)
In the bounce message, some variables can be used. In fact, most (all?) strings from the EmailMessage class are supported.
So in the bounce subject/message, it's possible to use $mail->Subject etc. (list below)
```
BodyFormat
BodyText
BodyTextPlain (not a property of Email Message, but gives a version with HTML tags stripped)
CallerEmail
CallerName
Date
MessageId
Recipient
Subject
UIDL
```
# Configuration
## Mailbox Configuration
* **Mail Server** 	
* **Login**
* **Password**
* **Protocol** - POP or IMAP
* **Port (993 for IMAP)** - often 993 for IMAP
* **Mailbox folder (for IMAP)** - the folder, for example InboxTest/SomeSubFolder
* **Mail From Address** - errors/bounce messages are sent 'from'
* **Active** - check mailbox 	 	
* **Debug trace** - debug log
* **Mail Aliases** - one per line. List each email address (minimum 1)
	 	
# Behavior on Incoming eMails
* **Behavior** - create only, update only or both
* **After processing the eMail** - delete it right away or keep it on the mail server
* **Ticket Class** - which ticket class (see iTop data model, usually UserRequest)
* **Ticket Default Values** - default values for tickets (see iTop data model, example below).
* **Title Pattern** - example: /R-([0-9]{6})/
* **Stimuli to apply** - example: reopen ticket?
```
    service_id:1
    impact:3
    agent_id:395
    team_id:2
    status:assigned
```	 	
	 	
# Emails in Error
* **Behavior** - Delete or keep the message in the mailbox 	 	
* **Forward eMails (in error) To Address**
***
# Policies
## Mail Size
* Use case: mail size is too big (often related to PHP, MySQL limits)
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* **Max size (MB)** - 10 
 	
## Forbidden attachments
* Use case: blocking .exe attachments.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* **MIME Types** - one per line. Example: application/exe
* Fallback: ignore forbidden attachments?
	 	
## No subject
* Use case: enforcing people to at least supply a subject.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* **Default subject** - example: (no subject)
* Fallback: use specified default subject	 	
 	
## Closed tickets
* Use case: users replying to closed tickets.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* Fallback: reopen

## Resolved tickets
* Use case: users replying to resolved tickets.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* Fallback: reopen
	 	
## Unknown tickets
* Use case: usually triggered if the extension misinterprets a pattern in the email header and can't find the ticket.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* No fallback

## Unknown caller
* Use case: first time caller, unknown to iTop.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* **Default values for new contact** - see example
* Fallback: create contact, with specified defaults.
```
	org_id:1 
	first_name:Unknown 
	name:Caller
```
( creates a contact named 'Unknown Caller', belonging to the organization with ID 1 in iTop)
	 	
## Other recipients
* Use case: when allowing users to mail to the iTop helpdesk system while others are addressed in To: or CC:, the other recipients may reply to the initial e-mail from the caller. This would lead to multiple new tickets, since there is no ticket reference in the e-mail header or subject.
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* fallback: ignore
	 	
## Undesired patterns in title
* Use case: out-of-office
* **eMail** Bounce to sender and delete 	 	
* **Bounce subject**	 	
* **Bounce message**
* **Undesired patterns in subject** - (regex, one per line)
## Patterns to ignore/remove from title
* Use case: another ticket system which uses IR-1234567 as a ticket reference. If this iTop's ticket reference format is R-123456, this would lead to issues when handling emails with a reference from the other ticket system.
* Fallback - Ignore: just ignores parts when handling a new e-mail, but keeps the original subject to create the ticket.
* Fallback - Remove, it's gone completely in the title.
* **Fallback**: ignore, remove 	
* **Undesired patterns in subject** - (regex, one per line)
# Other improvements
## Minor code tweaks
Some code was simplified.
## Lost IMAP connections
There's an attempt to fix issues with lost IMAP connections (to Office 365).
Contrary to the original extension, EmailReplicas don't immediately disappear when the mail can not be seen anymore.
It's stored for 7 more days after it's last seen.
Benefit: if the email wasn't seen due to a lost IMAP connection, the EmailReplica got deleted with the original Combodo extension.
If in the next run the IMAP connection functions properly, the email would be reprocessed as 'new' - which led to new tickets being created.

