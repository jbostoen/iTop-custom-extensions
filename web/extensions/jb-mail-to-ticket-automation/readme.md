Our **Mail to Ticket automation** is a **fork** from Combodo's Mail to Ticket Automation (originally from version 3.0.7, but we also copied the changes Combodo's done up to version 3.0.11 so far). 

One thing is important here: we highly recommend to set **use_message_id_as_uid** to 'true' in the config file in a lot of cases to avoid duplicates (Combodo sets it to 'false' by default but this could be very undesired for IMAP connections!). Otherwise, configuration settings are mostly similar to https://www.itophub.io/wiki/page?id=extensions%3Aticket-from-email

What is different? We noted a few situations where Combodo's implementation of Mail to Ticket Automation was not sufficient enough. We split them up in what I'll refer to as "Policies".

# Basics about policies
Most of them have:
* eMail - behavior: always 'bounce and delete', 'delete', 'mark as undesired'. Sometimes 'mark as error'. Sometimes fallbacks are possible.
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
* Use case: mail size is too big (often related to PHP, MySQL limits)
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **Max size (MB)** - 10 	
 	 
# Policy: Forbidden attachments
* Use case: you might not want .exe attachments
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **MIME Types** - one per line. Example: application/exe
* Fallback: ignore forbidden attachments?
	 	 
# Policy: No subject
* Use case: you want to enforce people to at least supply a subject.
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* **Default subject** - example: (no subject)
* Fallback: use specified default subject	 	 
 	 
# Policy: Closed tickets
* Use case: despite very clear warnings a ticket has been closed, user still replies.
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* Fallback: reopen
 
# Policy: Resolved tickets
* Use case: despite very clear warnings a ticket has been resolved, user still replies.
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* Fallback: reopen
	 	 
# Policy: Unknown tickets
* Use case: usually if the extension misinterprets a pattern in the email header and can't find the ticket.
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* No fallback
 
# Policy: Unknown caller
* Use case: first time caller
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
* Use case: if you allow (B)CC to a helpdesk system, you might see people replying to the initial email from the caller. This would lead to multiple new tickets, since there is no ticket reference in the email header or subject.
* **eMail** Bounce to sender and delete 	 	 
* **Bounce subject**	 	 
* **Bounce message**
* fallback: ignore
	 	 
# Policy: Undesired patterns in title
* Use case: out-of-office
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
