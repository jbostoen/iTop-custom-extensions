<?php
// Copyright (C) 2010-2013 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Lesser General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Dict::Add('EN US', 'English', 'English', array(
	// Dictionary entries go here
	'Class:MailInboxStandard' => 'Standard Mail Inbox',
	'Class:MailInboxStandard+' => 'Source of incoming eMails',
	'Class:MailInboxStandard/Attribute:behavior' => 'Behavior',
	'Class:MailInboxStandard/Attribute:behavior/Value:create_only' => 'Create new Tickets',
	'Class:MailInboxStandard/Attribute:behavior/Value:update_only' => 'Update existing Tickets',
	'Class:MailInboxStandard/Attribute:behavior/Value:both' => 'Create or Update Tickets',

	'Class:MailInboxStandard/Attribute:email_storage' => 'After processing the eMail',
	'Class:MailInboxStandard/Attribute:email_storage/Value:keep' => 'Keep it on the mail server',
	'Class:MailInboxStandard/Attribute:email_storage/Value:delete' => 'Delete it immediately',

	'Class:MailInboxStandard/Attribute:target_class' => 'Ticket Class',
	'Class:MailInboxStandard/Attribute:target_class/Value:Incident' => 'Incident',
	'Class:MailInboxStandard/Attribute:target_class/Value:UserRequest' => 'User Request',

	'Class:MailInboxStandard/Attribute:ticket_default_values' => 'Ticket Default Values',
	'Class:MailInboxStandard/Attribute:ticket_default_title' => 'Default Title (if subject is empty)',
	'Class:MailInboxStandard/Attribute:title_pattern+' => 'Pattern to match in the subject',
	'Class:MailInboxStandard/Attribute:title_pattern' => 'Title Pattern',
	'Class:MailInboxStandard/Attribute:title_pattern?' => 'Use PCRE syntax, including starting and ending delimiters',

	'Class:MailInboxStandard/Attribute:stimuli' => 'Stimuli to apply',
	'Class:MailInboxStandard/Attribute:stimuli+' => 'Apply a stimulus when the ticket is in a given state',
	'Class:MailInboxStandard/Attribute:stimuli?' => 'Use the format <state_code>:<stimulus_code>',

	// 'Class:MailInboxStandard/Attribute:unknown_caller_behavior' => 'Behavior in case of Unknown Caller',
	// 'Class:MailInboxStandard/Attribute:unknown_caller_behavior/Value:create_contact' => 'Create a new Person',
	// 'Class:MailInboxStandard/Attribute:unknown_caller_behavior/Value:reject_email' => 'Reject the eMail',

	'Class:MailInboxStandard/Attribute:trace' => 'Debug trace',
	'Class:MailInboxStandard/Attribute:trace/Value:yes' => 'Yes',
	'Class:MailInboxStandard/Attribute:trace/Value:no' => 'No',
	
	
	// 'Class:MailInboxStandard/Attribute:import_additional_contacts' => 'Add more contacts (To, CC)',
	// 'Class:MailInboxStandard/Attribute:import_additional_contacts/Value:never' => 'Never',
	// 'Class:MailInboxStandard/Attribute:import_additional_contacts/Value:only_on_creation' => 'When creating a new Ticket',
	// 'Class:MailInboxStandard/Attribute:import_additional_contacts/Value:only_on_update' => 'When updating an existing Ticket',
	// 'Class:MailInboxStandard/Attribute:import_additional_contacts/Value:always' => 'Always',
	
	 
	
		
	'Class:MailInboxStandard/Attribute:caller_default_values' => "New Person's Default Values",
	'Class:MailInboxStandard/Attribute:debug_log' => 'Debug Log',
	'Class:MailInboxStandard/Attribute:error_behavior' => 'Behavior',
	'Class:MailInboxStandard/Attribute:error_behavior/Value:delete' => 'Delete the message from the mailbox',
	'Class:MailInboxStandard/Attribute:error_behavior/Value:mark_as_error' => 'Keep the message in the mailbox',
	'Class:MailInboxStandard/Attribute:notify_errors_to' => 'Forward eMails To',
	'Class:MailInboxStandard/Attribute:notify_errors_from' => '(From)',
	
 
	
	// Policy: mail size too large
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_max_size_MB' => 'Max size (MB)',
	
	// Policy: forbidden attachments
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_forbidden_attachments_mimetypes' => 'MIME Types',
	
	// Policy: no subject
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:fallback' => 'Fallback: use default subject',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_no_subject_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_no_subject_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_no_subject_default_value' => 'Default subject',
	
	// Policy: unknown caller
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:fallback' => 'Fallback: create contact',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_default_values' => 'Default values for new contact (one per line, example: org_id:1)',
	
	// Policy: other recipients
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:add_existing_contacts' => 'Fallback: add contact if exists',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:add_all_contacts' => 'Fallback: add contact / create if necessary',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:ignore_all_contacts' => 'Fallback: ignore all other contacts',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_default_values' => 'Default values for new contacts (one per line, example: org_id:1)',
	
	// Policy: closed ticket
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:fallback' => 'Fallback: reopen ticket',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_notification' => 'Bounce message', 
	
	// Policy: resolved ticket
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior' => 'eMail',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:fallback' => 'Fallback: reopen ticket',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_notification' => 'Bounce message', 
	 
	// Policy: unknown ticket
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior' => 'eMail', 
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_notification' => 'Bounce message', 
	
	// Policy: undesired patterns
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior' => 'eMail', 
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:mark_as_error' => 'Keep (mark as error)',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_big_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_notification' => 'Bounce message', 
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_patterns' => 'Undesired patterns in subject (regex, one per line)', 
	
	
	
	
	
	// Headers
	'MailInbox:Server' => 'Mailbox Configuration',
	'MailInbox:Behavior' => 'Behavior on Incoming eMails',
	'MailInbox:Errors' => 'Emails in Error',
	// 'MailInbox:OtherContacts' => 'Behavior for Additional Contacts',
	// 'MailInbox:Caller' => 'Unknown Callers',
	
	// Policies
	'MailInbox:PolicySize' => 'Policy: Mail Size',
	'MailInbox:PolicyForbiddenAttachments' => 'Policy: Forbidden attachments',
	'MailInbox:PolicyUndesiredPatterns' => 'Policy: Undesired patterns',
	'MailInbox:PolicyResolvedTicket' => 'Policy: Resolved tickets',
	'MailInbox:PolicyClosedTicket' => 'Policy: Closed tickets',
	'MailInbox:PolicyUnknownTicket' => 'Policy: Unknown tickets',
	'MailInbox:PolicyNoSubject' => 'Policy: No subject',
	'MailInbox:PolicyUnknownCaller' => 'Policy: Unknown caller',
	'MailInbox:PolicyOtherRecipients' => 'Policy: Other recipients',
	
	'Menu:MailInboxes' => 'Incoming eMail Inboxes',
	'Menu:MailInboxes+' => 'Configuration of Inboxes to scan for Incoming eMails',
	 
	'MailInboxStandard:DebugTrace' => 'Debug Trace',
	'MailInboxStandard:DebugTraceNotActive' => 'Activate the debug on this Inbox to see the debug trace here.',
	// 'MailInbox:NoSubject' => 'No subject',
	
	 
	
));
