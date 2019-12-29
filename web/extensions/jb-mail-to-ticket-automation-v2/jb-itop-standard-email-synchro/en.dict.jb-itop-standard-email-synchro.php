<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2019 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */

Dict::Add('EN US', 'English', 'English', array(
	// Dictionary entries go here
	'Class:MailInboxStandard' => 'Standard Mail Inbox',
	'Class:MailInboxStandard+' => 'Source of incoming e-mails',
	'Class:MailInboxStandard/Attribute:behavior' => 'Behavior',
	'Class:MailInboxStandard/Attribute:behavior/Value:create_only' => 'Create new Tickets',
	'Class:MailInboxStandard/Attribute:behavior/Value:update_only' => 'Update existing Tickets',
	'Class:MailInboxStandard/Attribute:behavior/Value:both' => 'Create or Update Tickets',

	'Class:MailInboxStandard/Attribute:email_storage' => 'After processing the e-mail',
	'Class:MailInboxStandard/Attribute:email_storage/Value:keep' => 'Keep it on the mail server',
	'Class:MailInboxStandard/Attribute:email_storage/Value:delete' => 'Delete it immediately',

	'Class:MailInboxStandard/Attribute:target_class' => 'Ticket Class',
	'Class:MailInboxStandard/Attribute:target_class/Value:Incident' => 'Incident',
	'Class:MailInboxStandard/Attribute:target_class/Value:UserRequest' => 'User Request',
	'Class:MailInboxStandard/Attribute:target_class/Value:Change' => 'Change',
	'Class:MailInboxStandard/Attribute:target_class/Value:Change+' => '',
	'Class:MailInboxStandard/Attribute:target_class/Value:RoutineChange' => 'RoutineChange',
	'Class:MailInboxStandard/Attribute:target_class/Value:RoutineChange+' => '',
	'Class:MailInboxStandard/Attribute:target_class/Value:NormalChange' => 'NormalChange',
	'Class:MailInboxStandard/Attribute:target_class/Value:NormalChange+' => '',
	'Class:MailInboxStandard/Attribute:target_class/Value:EmergencyChange' => 'EmergencyChange',
	'Class:MailInboxStandard/Attribute:target_class/Value:EmergencyChange+' => '',
	'Class:MailInboxStandard/Attribute:target_class/Value:Problem' => 'Problem',
	'Class:MailInboxStandard/Attribute:target_class/Value:Problem+' => '',
	'Class:MailInboxStandard/Attribute:debug_trace' => 'Debug trace',
	'Class:MailInboxStandard/Attribute:debug_trace+' => '',

	'Class:MailInboxStandard/Attribute:ticket_default_values' => 'Ticket Default Values',
	'Class:MailInboxStandard/Attribute:ticket_default_title' => 'Default Title (if subject is empty)',
	'Class:MailInboxStandard/Attribute:title_pattern+' => 'Pattern to match in the subject',
	'Class:MailInboxStandard/Attribute:title_pattern' => 'Title Pattern',
	'Class:MailInboxStandard/Attribute:title_pattern?' => 'Use PCRE syntax, including starting and ending delimiters',

	'Class:MailInboxStandard/Attribute:title_pattern_ignore_patterns' => 'Ignore patterns in subject (regex patterns, one per line)', 
	
	'Class:MailInboxStandard/Attribute:stimuli' => 'Stimuli to apply',
	'Class:MailInboxStandard/Attribute:stimuli+' => 'Apply a stimulus when the ticket is in a given state',
	'Class:MailInboxStandard/Attribute:stimuli?' => 'Use the format <state_code>:<stimulus_code>',


	'Class:MailInboxStandard/Attribute:trace' => 'Debug trace',
	'Class:MailInboxStandard/Attribute:trace/Value:yes' => 'Yes',
	'Class:MailInboxStandard/Attribute:trace/Value:no' => 'No',
	  
	   
	'Class:MailInboxStandard/Attribute:debug_log' => 'Debug Log',
	
	'Class:MailInboxStandard/Attribute:error_behavior' => 'Behavior',
	'Class:MailInboxStandard/Attribute:error_behavior/Value:delete' => 'Delete the message from the mailbox',
	'Class:MailInboxStandard/Attribute:error_behavior/Value:mark_as_error' => 'Mark as Error', 
	'Class:MailInboxStandard/Attribute:notify_errors_to' => 'Forward e-mails (in error) To Address',
	'Class:MailInboxStandard/Attribute:notify_from' => 'Mail From Address',
	
	'Class:MailInboxStandard/Attribute:mail_aliases' => 'Mail Aliases (one per line)',
	 
	
	// Policy: mail size too large
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_mail_size_too_big_max_size_MB' => 'Max size (MB)',
	
	// Policy: attachment - forbidden MimeType
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior/Value:fallback_ignore_forbidden_attachments' => 'Fallback: ignore forbidden attachments',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_attachment_forbidden_mimetype_mimetypes' => 'MIME Types (one per line)',
	
	// Policy: no subject
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:fallback_default_subject' => 'Fallback: use default subject',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_no_subject_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_no_subject_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_no_subject_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_no_subject_default_value' => 'Default subject',
	
	// Policy: unknown caller
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:fallback_create_person' => 'Fallback: create person',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_behavior/Value:mark_as_undesired' =>  'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_unknown_caller_default_values' => 'Default values for new person (one per line, example: org_id:1)',
	
	// Policy: other recipients
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:fallback_add_existing_contacts' => 'Fallback: add existing contacts',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:fallback_add_all_contacts' => 'Fallback: add contact / create if necessary',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:fallback_ignore_other_contacts' => 'Fallback: ignore all other contacts',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_notification' => 'Bounce message',
	'Class:MailInboxStandard/Attribute:policy_other_recipients_default_values' => 'Default values for new person (one per line, example: org_id:1)',
	
	// Policy: closed ticket
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:fallback_reopen' => 'Fallback: reopen ticket',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_ticket_closed_notification' => 'Bounce message', 
	
	// Policy: resolved ticket
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior' => 'Behavior on violation',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:fallback_reopen' => 'Fallback: reopen ticket',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_ticket_resolved_notification' => 'Bounce message', 
	 
	// Policy: unknown ticket
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior' => 'Behavior on violation', 
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_ticket_unknown_notification' => 'Bounce message', 
	
	// Policy: undesired title patterns
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior' => 'Behavior on violation', 
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:delete' => 'Delete',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:bounce_delete' => 'Bounce to sender and delete',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:bounce_mark_as_undesired' => 'Bounce to sender and mark as undesired',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_behavior/Value:mark_as_undesired' => 'Mark as Undesired / Temporarily keep the e-mail',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_subject' => 'Bounce subject',
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_notification' => 'Bounce message', 
	'Class:MailInboxStandard/Attribute:policy_undesired_pattern_patterns' => 'Undesired patterns in subject (regex patterns, one per line)', 
	
	
	// Policy: remove parts of title
	'Class:MailInboxStandard/Attribute:policy_remove_pattern_behavior' => 'Remove patterns from subject', 
	'Class:MailInboxStandard/Attribute:policy_remove_pattern_behavior/Value:do_nothing' => 'Do nothing',
	'Class:MailInboxStandard/Attribute:policy_remove_pattern_behavior/Value:fallback_remove' => 'Remove part(s) from subject',
	'Class:MailInboxStandard/Attribute:policy_remove_pattern_patterns' => 'Patterns to remove from subject (regex patterns, one per line)', 
	
	// Policy: Attachments - Image dimensions
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_min_width' => 'Min. width (px)',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_min_width+' => 'Min. width (px). Must be at least 1.',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_max_width' => 'Max. width (px)',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_max_width+' => 'Max. width (px). Set to 0 for unlimited',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_min_height' => 'Min. height (px)',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_min_height+' => 'Min. height (px). Must be at least 1.',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_max_height' => 'Max. height (px)',
	'Class:MailInboxStandard/Attribute:policy_attachment_image_dimensions_max_height+' => 'Max. height (px). Set to 0 for unlimited',
	
	// Headers
	'MailInbox:Server' => 'Mailbox Configuration',
	'MailInbox:Behavior' => 'Behavior on Incoming e-mails',
	'MailInbox:Errors' => 'E-mails in error', 
	
	// Policies
	'MailInbox:PolicyMailSize' => 'Policy: Mail Size',
	'MailInbox:PolicyAttachmentForbiddenMimeType' => 'Policy: Attachment - forbidden MimeType',
	'MailInbox:PolicyAttachmentImageDimensions' => 'Policy: Attachment - image dimensions',
	'MailInbox:PolicyUndesiredPattern' => 'Policy: Undesired title patterns',
	'MailInbox:PolicyRemovePattern' => 'Policy: Remove unwanted title patterns',
	'MailInbox:PolicyIgnorePattern' => 'Policy: Ignore unwanted title patterns',
	'MailInbox:PolicyResolvedTicket' => 'Policy: Resolved tickets',
	'MailInbox:PolicyClosedTicket' => 'Policy: Closed tickets',
	'MailInbox:PolicyUnknownTicket' => 'Policy: Unknown tickets',
	'MailInbox:PolicyNoSubject' => 'Policy: No subject',
	'MailInbox:PolicyUnknownCaller' => 'Policy: Unknown caller',
	'MailInbox:PolicyOtherRecipients' => 'Policy: Other recipients',
	
	'Menu:MailInboxes' => 'Incoming E-mail Inboxes',
	'Menu:MailInboxes+' => 'Configuration of Inboxes to scan for incoming e-mails',
	 
	'MailInboxStandard:DebugTrace' => 'Debug Trace',
	'MailInboxStandard:DebugTraceNotActive' => 'Activate the debug trace on this Inbox to see a detailed log of what happens.',
	
	'MailPolicy:CreateOrUpdateTicket:NoDescriptionProvided' => 'No description provided',
	
));
