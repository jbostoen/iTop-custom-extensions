Our **Mail to Ticket automation** is a **fork** from Combodo's Mail to Ticket Automation (version 3.0.11). 
One thing is important here: we highly recommend to set **use_message_id_as_uid** to 'true' in the config file in a lot of cases to avoid duplicates (Combodo sets it to 'false' by default but this could be very undesired for IMAP connections!). Otherwise, configuration settings are mostly similar to https://www.itophub.io/wiki/page?id=extensions%3Aticket-from-email

What is different? We noted a few situations where Combodo's implementation of Mail to Ticket Automation was not sufficient enough. 
We added some options to bounce (ignore and auto-reply to) the message.
* despite giving our (internal) users very specific guidelines, they kept sending mails with other recipients than just the helpdesk. When other recipients replied to all, this generated new tickets rather than it updated the ticket. We couldn't avoid this behavior with mail rules.
* despite a bold red warning, people still replied to mails after a ticket was resolved or closed. But we never noticed those replies.
* some specific situations where we wanted to inform the user why the mail bounces.


# Basics about policies
There's quite a few policies. 

They all have:
* eMail - behavior: always 'bounce and delete', 'delete', 'mark as error'. Sometimes fallbacks are possible.
* bounce subject - message subject when message is bounced back to sender (caller)
* bounce message - message content when message is bounced back to sender (caller)

In the bounce message, some variables can be used. In fact, most (all?) strings from the EmailMessage class are supported. So in the bounce subject/message, you can use $mail->Subject etc. (list below)

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
* **Mailbox (for IMAP)** - the folder, for example InboxTest 	 	 
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

# Policy: Mail Size
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **Max size (MB)** - 10 	
 	 
# Policy: Forbidden attachments
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **MIME Types** - one per line. Example: application/exe
* Fallback: ignore forbidden attachments?
	 	 
# Policy: No subject
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **Default subject** - example: (no subject)
* Fallback: use specified default subject	 	 
 	 
# Policy: Closed tickets
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* Fallback: reopen
 
# Policy: Resolved tickets
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* Fallback: reopen
	 	 
# Policy: Unknown tickets
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* No fallback
 
# Policy: Unknown caller
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

( creates a contact named 'Unknown Caller', belonging to first organization in iTop)
	 	 
# Policy: Other recipients
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* fallback: ignore
	 	 
# Policy: Undesired patterns in title
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **Undesired patterns in subject** - (regex, one per line)


# Policy: Patterns to ignore/remove from title
* Use case: another ticket system which uses IR-1234567 as format. Our format is R-123456, this would lead to issues when handling emails with a reference from the other ticket.
* Fallback - Ignore: just ignores parts when handling a new e-mail, but keeps the original subject to create the ticket. 
* Fallback - Remove, it's gone completely. 
* **Fallback** ignore/remove 	 
* **Undesired patterns in subject** - (regex, one per line)
