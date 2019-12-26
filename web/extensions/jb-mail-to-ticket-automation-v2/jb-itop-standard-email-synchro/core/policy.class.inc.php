<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-12-25 20:06:30
 *
 * Policy interface definition and some classes implementing it.
 * 
 * Additional notes:
 * - do not alter ticket contents here, such as subject. That's done at a later phase. For this particular case: change EmailMessage's subject.
 */
 
namespace jb_itop_extensions\mail_to_ticket;

const NEWLINE_REGEX = '/\\r\\n|\\r|\\n/';

/**
 * Interface iPolicy defines what the classes implementing policies should look like.
 */
interface iPolicy {
	
	/**
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant();
	
	/**
	 * Runs some default functionality BEFORE checking the policy. Use case: logging some information.
	 * Can be cascaded to subclasses.
	 *
	 * @return void
	 */
	public static function BeforeComplianceCheck();
	
	/**
	 * Runs some default functionality AFTER checking the policy. Use case: logging some information.
	 * Can be cascaded to subclasses.
	 *
	 * @return void
	 */
	public static function AfterPassedComplianceCheck();
	
	/**
	 * Actions executed when the message does not comply with a policy.
	 * The default method informs the caller that the e-mail was rejected.
	 *
	 * @return void
	 */
	public static function HandleViolation();
	
}

abstract class Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_generic';
		
	/**
	 * @var \EmailMessage $oEmail Email message
	 */
	public static $oEmail = null;
	
	/**
	 * @var \MailInboxStandard $oMailBox Mailbox
	 */
	public static $oMailBox = null;
	
	/**
	 * @var \Ticket $oTicket Ticket object (in iTop)
	 */
	public static $oTicket = null;
	
	/**
	 * Constructor. Sets some widely used property values.
	 *
	 * @var \MailInboxStandard $oMailBox Mailbox
	 * @var \EmailMessage $oEmail Email message
	 * @var \Ticket|null $oTicket Ticket found based on ticket reference (or null if not found)
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function Init(\MailInboxStandard $oMailBox, \EmailMessage $oEmail, ?\Ticket $oTicket) {
		
		self::$oMailBox = $oMailBox;
		self::$oEmail = $oEmail;
		self::$oTicket = $oTicket;
	
	}
	
	/**
	 * Checks if mailbox, email, ticket information is compliant with a certain policy.
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		return true;
		
	}
	
	/**
	 * Runs some default functionality BEFORE checking the policy. Use case: logging some information.
	 * Can be cascaded to subclasses.
	 *
	 * @return void
	 */
	public static function BeforeComplianceCheck() {
	
		$sUnqualifiedName = (new \ReflectionClass(get_called_class()))->getShortName();
		if($sUnqualifiedName  != 'Policy') {
			self::Trace('. Check: '.$sUnqualifiedName);
			self::Trace('.. Behavior: '.self::$oMailBox->Get(get_called_class()::$sPolicyId.'_behavior'));
		}
	}
	
	/**
	 * Runs some default functionality AFTER checking the policy. Use case: logging some information.
	 * Can be cascaded to subclasses.
	 *
	 * @return void
	 */
	public static function AfterPassedComplianceCheck() {
	
		$sUnqualifiedName = (new \ReflectionClass(get_called_class()))->getShortName();
		if($sUnqualifiedName  != 'Policy') {
			self::Trace('. Complete: '.$sUnqualifiedName);
		}
	}
	
	/**
	 * Actions executed when the message does not comply with a policy.
	 * The default method informs the caller that the e-mail was rejected.
	 *
	 * @return void
	 */
	public static function HandleViolation() {
		
		$oRawEmail = self::$oEmail->oRawEmail;
	
		// Inform the caller who doesn't follow guidelines.		
		// User education and communicating the guideliens is great; but sometimes policies need to be enforced.
		$sTo = self::$oEmail->sCallerEmail;
		$sFrom = self::$oMailBox->Get('notify_from'); 
	
		// Policy violations have a typical way of handling.
		// The behavior is - besides some fallbacks - usually one of the following:
		// - bounce_delete -> bounce and delete the message
		// - bounce_mark_as_undesired -> bounce and marks the message as undesired
		// - delete -> delete the message
		// - do nothing -> great, lazy. For testing purposes.
		// - mark_as_undesired -> stays in the mailbox for a few days
		// - some sort of fallback -> doesn't matter here
		
		$sPolicyId = get_called_class()::$sPolicyId;
		
		$sBehavior = self::$oMailBox->Get($sPolicyId.'_behavior');
		self::Trace('. Policy violated. Behavior: '.$sBehavior);
		
		// First check if e-mail notification must be sent to caller (bounce message)
		switch($sBehavior) {
		
			// Generic cases
			case 'bounce_delete':
			case 'bounce_mark_as_undesired':
			
				self::Trace('Bounce message: '.$sPolicyId);
				
				$sSubject = self::$oMailBox->Get($sPolicyId.'_subject');
				$sBody = self::$oMailBox->Get($sPolicyId.'_notification'); 
				
				// Return to sender
				if($sTo == ''){ 
					self::Trace('.. No "to" defined, skipping bounce message.');
				}
				elseif($sFrom == ''){ 
					self::Trace('.. No "from" defined, skipping bounce message.');
				}
				else if($oRawEmail){
					
					// Allow some customization in the bounce message
					$sSubject = self::ReplaceMailPlaceholders($sSubject);
					$sBody = self::ReplaceMailPlaceholders($sBody);
					
					if($sSubject == '') {
						$sSubject = 'Message bounced - not compliant with an enforced policy.';
					}

					self::Trace('Sending bounce message "'.$sSubject.'" to "'.$sTo.'"');
					$oRawEmail->SendAsAttachment($sTo, $sFrom, $sSubject, $sBody);
				}
				
				break;
				
		}
		
				
		switch($sBehavior) {
				
			case 'bounce_delete':
			case 'delete': 
				self::Trace('Set next action for EmailProcessor to DELETE_MESSAGE');
				self::$oMailBox->SetNextAction(\EmailProcessor::DELETE_MESSAGE); // Remove the message from the mailbox
				break;
				
			// Mark as error should be irrelevant now. Keeping it just in case.
			case 'mark_as_error': 
				self::Trace('Set next action for EmailProcessor to MARK_MESSAGE_AS_ERROR');
				self::$oMailBox->SetNextAction(\EmailProcessor::MARK_MESSAGE_AS_ERROR); // Keep the message in the mailbox, but marked as error
				break;
				 
			case 'bounce_mark_as_undesired':
			case 'mark_as_undesired':
				self::Trace('Set next action for EmailProcessor to MARK_MESSAGE_AS_UNDESIRED');
				self::$oMailBox->SetNextAction(\EmailProcessor::MARK_MESSAGE_AS_UNDESIRED); // Keep the message temporarily in the mailbox, but marked as undesired
				break;
				
			// Any other action
			case 'do_nothing':
			default:
				self::Trace('Set next action for EmailProcessor to NO_ACTION');
				self::$oMailBox->SetNextAction(\EmailProcessor::NO_ACTION);
				
		}
		
	}
	
	/**
	 * Replace e-mail placeholders in a string.
	 * 
	 * @var \String $sString Input string
	 *
	 * @details Also exposes some properties which are not likely to be useful (body_format) at any time, but who knows?
	 *
	 * @return String String where the placeholders are filled in
	 */
	public static function ReplaceMailPlaceholders($sString) {
		
		$aParams = [
			'mail->uidl' => self::$oEmail->sUIDL,
			'mail->message_id' => self::$oEmail->sMessageId,
			'mail->subject' => self::$oEmail->sSubject,
			'mail->caller_email' => self::$oEmail->sCallerEmail,
			'mail->caller_name' => self::$oEmail->sCallerName,
			'mail->recipient' => self::$oEmail->sRecipient,
			'mail->date' => self::$oEmail->sDate,
			'mail->body_text_plain' => strip_tags(self::$oEmail->sBodyText),
			'mail->body_text'  => self::$oEmail->sBodyText,
			'mail->body_format' => self::$oEmail->sBodyFormat
		];
		
		return \MetaModel::ApplyParams($sString, $aParams);
		
	}
	
	/**
	 * For logging information about the processing of e-mails.
	 *
	 * @var \String $sString Input string
	 *
	 * @return void
	 */
	public static function Trace($sString) {
		self::$oMailBox->Trace($sString);
	}
		 
}

/**
 * Class PolicyForbiddenAttachment Offers a policy to enforce some rules on the attachment.
 */
abstract class PolicyForbiddenAttachment extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 1;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_forbidden_attachments';
		
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if attachments are in line with configured policies.
		
			$aForbiddenMimeTypes = preg_split(NEWLINE_REGEX, self::$oMailBox->Get(self::$sPolicyId.'_mimetypes'));
		
			self::Trace('.. Forbidden MimeTypes: '. implode(' - ', $aForbiddenMimeTypes));
			self::Trace('.. # Attachments: '. count(self::$oEmail->aAttachments));
			
			if(count($aForbiddenMimeTypes) > 0 && count(self::$oEmail->aAttachments) > 0) {
				
				switch(self::$oMailBox->Get(self::$sPolicyId.'_behavior')) {
					
					case 'bounce_delete':
					case 'bounce_mark_as_undesired':
					case 'delete':
					case 'do_nothing':
					case 'mark_as_undesired':
						
						// Forbidden attachments? 
						foreach(self::$oEmail->aAttachments as $aAttachment) { 
							self::Trace('.. Attachment MimeType: '.$aAttachment['mimeType']);
							
							if(in_array($aAttachment['mimeType'], $aForbiddenMimeTypes) == true) {
								
								self::Trace('... Found attachment with forbidden MimeType "'.$aAttachment['mimeType'].'"');
								self::HandleViolation();
								
								// No specific fallback								
								// Stop processing any further!
								return false;
							}
						}
					
						break; // Defensive programming
					
					case 'fallback_ignore_forbidden_attachments':
					
						// Ticket will be processed. Forbidden attachments will be removed here.
						foreach(self::$oEmail->aAttachments as $index => $aAttachment) { 
							if(in_array($aAttachment['mimeType'], $aForbiddenMimeTypes) == true) {
								self::Trace("... Attachment Content-Id ". $aAttachment['content-id'] . " - Mime Type: {$aAttachment['mimeType']} = forbidden.");
								// Removing attachment
								unset(self::$oEmail->aAttachments[$index]);
							}
							else {
								self::Trace("... Attachment Content-Id ". $aAttachment['content-id'] . " - Mime Type: {$aAttachment['mimeType']} = allowed.");
							}
						}
						break;
						
					default:
						self::Trace('.. Unexpected "behavior"');
						break;
				}
		
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyLimitMailSize Offers a policy to prevent big e-mail messages from being processed
 */
abstract class PolicyLimitMailSize extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 1;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_mail_size_too_big';
		
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if mail size is not too big
		
			$iMailSize = self::$oEmail->oRawEmail->GetSize();
			$iLimitMailSize = (self::$oMailBox->Get(self::$sPolicyId.'_max_size_MB') * 1024 * 1024);
			
			if($iMailSize > $iLimitMailSize) {
				
				// Mail size too big
				self::Trace('.. Undesired: mail size too big: mail size = '.$iMailSize.' bytes, while limit is '.$iLimitMailSize.' bytes.');
				self::HandleViolation();
				
				// No fallback
				
				// Stop processing any further!
				return false;
				
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyNoSubject Offers a policy to enforce non-empty subjects
 */
abstract class PolicyNoSubject extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 1;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_no_subject';
		
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if subject is not empty.
		
			$sPolicyBehavior = self::$oMailBox->Get(self::$sPolicyId.'_behavior');
			
			if(self::$oEmail->sSubject == '') {
				
				switch($sPolicyBehavior) {
					 // Will use default subject.
					 case 'bounce_delete':
					 case 'bounce_mark_as_undesired':
					 case 'delete':
					 case 'do_nothing':
					 case 'mark_as_undesired':

						// No subject (and no fallback)
						self::Trace('.. Undesired: Empty subject.');
						self::HandleViolation();
						
						// No fallback
						
						// Stop processing any further!
						return false;
						
						break; // Defensive programming
						
					case 'fallback_default_subject':
					
						// Set ticket title of e-mail message
						// Setting the ticket title on the ticket object happens later and not in this policy!
						$sDefaultTitle = self::$oMailBox->Get(self::$sPolicyId.'_default_value');
						self::Trace('.. Fallback: changing empty subject to "'.$sDefaultTitle.'"');
						self::$oEmail->sSubject = $sDefaultTitle;
						break;
					
					default:
						self::Trace('.. Unexpected "behavior"');
						break;
					
				}
			
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyNoOtherRecipients Offers a policy to enforce being the sole recipient (no other recipients in To:, CC:)
 */
abstract class PolicyNoOtherRecipients extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_other_recipients';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if subject is not empty.
		
			$sCallerEmail = self::$oEmail->sCallerEmail;
								
			// Take both the To: and CC:
			$aAllContacts = array_merge(self::$oEmail->aTos, self::$oEmail->aCCs);
			
			// Ignore sender; helpdesk mailbox; any helpdesk mailbox aliases
			$aExcludeContacts = array_merge([ self::$oEmail->sCallerEmail, self::$oMailBox->Get('login') ], preg_split(NEWLINE_REGEX, self::$oMailBox->Get('mail_aliases')));

			$sPolicyBehavior = self::$oMailBox->Get(self::$sPolicyId.'_behavior');
			
			switch($sPolicyBehavior) {
				 // Will use default subject.
				 case 'bounce_delete':
				 case 'bounce_mark_as_undesired':
				 case 'delete':
				 case 'do_nothing':
				 case 'mark_as_undesired':
				
					foreach($aAllContacts as $aContactInfo) {
						$sCurrentEmail = $aContactInfo['email'];
						if(in_array($sCurrentEmail, $aExcludeContacts) == false) {
							
							// Found other contacts in To: or CC: 
							self::Trace(".. Undesired: at least one other recipient (missing alias or unwanted): {$aContactInfo['email']}");
							self::HandleViolation();
							
							// No fallback
							
							// Stop processing any further!
							return false;
						}
					}

					break; // Defensive programming
					
				case 'fallback_add_existing_other_contacts':
			
					foreach($aAllContacts as $aContactInfo) {
						$sCurrentEmail = $aContactInfo['email'];
						if(in_array($sCurrentEmail, $aExcludeContacts) == false) {
									
							// Check if this contact exists.
							// Non-existing contacts must be created.
							// Actual linking of contacts happens after policies have been processed.
							$sContactQuery = 'SELECT Person WHERE email = :email';
							$oSet = new \DBObjectSet(\DBObjectSearch::FromOQL($sContactQuery), array(), array('email' => $sContactEmail));
							
							if($oSet->Count() == 0) {
								
								// Create
								self::Trace("Creating a new Person with email address {$sCallerEmail}");
								$oCaller = new \Person();
								$oCaller->Set('email', self::$oEmail->sCallerEmail);
								$sDefaultValues = self::$oMailBox->Get(self::$sPolicyId.'_default_values');
								$aDefaults = preg_split('/\r\n|\r|\n/', $sDefaultValues);
								$aDefaultValues = array();
								foreach($aDefaults as $sLine)														   
								{
									if (preg_match('/^([^:]+):(.*)$/', $sLine, $aMatches))
									{
										$sAttCode = trim($aMatches[1]);
										$sValue = trim($aMatches[2]);
										$aDefaultValues[$sAttCode] = $sValue;
									}
								}
								self::$oMailBox->InitObjectFromDefaultValues($oCaller, $aDefaultValues);
								try
								{
									self::Trace("Try to create user with default values");
									$oCaller->DBInsert();					
								}
								catch(Exception $e)
								{
									// This is an actual error.
									self::Trace("Failed to create a Person for the email address '{$sCallerEmail}'.");
									self::Trace($e->getMessage());
									self::$oMailBox->HandleError(self::$oEmail, 'failed_to_create_contact', self::$oEmail->oRawEmail);
									return null;
								}									
								
							}
						}
					}
					break;
					
				case 'fallback_add_existing_other_contacts':
				
					// Will be added automatically later
					break;
					
				case 'fallback_ignore_other_contacts':
				
					// Make sure these contacts are not processed.
					self::$oEmail->aTos = [];
					self::$oEmail->aCCs = [];
				
					self::Trace('.. Ignoring other contacts');
					break;
				
				default:
					self::Trace('.. Unexpected "behavior"');
					break;
				
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyUnknownTicketReference Offers a policy to handle unknown ticket references. Also see MailInboxStandard::GetRelatedTicket()
 * @todo Check if this works properly
 */
abstract class PolicyUnknownTicketReference extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * @details This one must have a higher rank (=later processed) than PolicyRemoveTitlePatterns and PolicyIgnoreTitlePatterns
	 */
	public static $iPrecedence = 200;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_ticket_unknown';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Todo: what is $oEmail->oRelatedObject?
		// Is the ticket valid in the iTop database or does the number NOT match?
		
		// Checking if ticket reference is invalid
			if(self::$oTicket === null) {
			
				// This could be a new ticket. Then it's logical the Ticket object is null. 
				// So check if there was something (header or pattern in subject) which would have lead the system to believe there was a ticket. 
				
				// Are there patterns which should be ignored/removed from the title? 
				// To find the reference, let's remove it from our temp variable. 
				$sSubject = self::$oEmail->sSubject;
				
				// Here the removal/ignoring of patterns happens; on a copy of the subject string; only to find related tickets.
				foreach(['policy_remove_pattern_patterns', 'title_pattern_ignore_patterns'] as $sAttCode) {
					$sPatterns = self::$oMailBox->Get($sAttCode);
					
					if(trim($sPatterns) != '') {
						
						$aPatterns = preg_split('/\r\n|\r|\n/', $sPatterns);
						
						self::Trace(".. GetRelatedTicket() - Removing undesired title patterns: {$sPatterns}");
						
						foreach($aPatterns as $sPattern) {
							if(trim($sPattern) != '') {
								$oPregMatch = @preg_match($sPattern, $sSubject);
								
								if( $oPregMatch === false) {
									self::Trace("... Invalid pattern: '{$sPattern}'");
								}
								elseif(preg_match($sPattern, $sSubject)) {
									self::Trace("... Removing: '{$sPattern}'");
									$sSubject = preg_replace($sPattern, '', $sSubject);
								}
								else {
									// Just not matching
								}
							}
						}
					}
				}
				
				$sPattern = self::$oMailBox->FixPattern(self::$oMailBox->Get('title_pattern'));
				if(($sPattern != '') && (preg_match($sPattern, $sSubject, $aMatches))) {
					self::Trace(".. Undesired: unable to find any prior ticket despite a matching ticket reference pattern in the subject ('{$sPattern}').");
					return false;
				} 
				elseif(self::$oEmail->oRelatedObject != null ) {
					self::Trace(".. Undesired: unable to find any prior ticket despite an email header ({$oEmail->oRelatedObject}).");
					return false;
				}
			
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyTicketResolved Offers a policy to handle replies to resolved tickets.
 */
abstract class PolicyTicketResolved extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_ticket_resolved';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if a previous ticket was found
			if(self::$oTicket !== null) {
				if(self::$oTicket->Get('status') == 'resolved') {
					
					self::Trace(".. Ticket was marked as resolved before.");
							
					switch(self::$oMailBox->Get(self::$sPolicyId.'_behavior')) { 
						case 'bounce_delete': 
						case 'bounce_mark_as_undesired':
						case 'delete':
						case 'do_nothing':
						case 'mark_as_undesired':
						
							self::Trace(".. Undesired: ticket was marked as resolved before.");
							self::HandleViolation();
							
							// No fallback
							
							// Stop processing any further!
							return false;

							break; // Defensive programming
							 
						case 'fallback_reopen': 
							// Reopen ticket
							self::Trace("... Fallback: reopen resolved ticket."); 
							self::$oTicket->ApplyStimulus('ev_reopen');
							break; 
							
						default:
							// Should not happen.
							self::Trace("... Unknown action for resolved tickets.");
							break; 
						
					}
				}
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyTicketClosed Offers a policy to handle replies to closed tickets.
 */
abstract class PolicyTicketClosed extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_ticket_closed';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if a previous ticket was found
			if(self::$oTicket !== null) {
				if(self::$oTicket->Get('status') == 'closed') {
						
					switch(self::$oMailBox->Get(self::$sPolicyId.'_behavior')) { 
						case 'bounce_delete': 
						case 'bounce_mark_as_undesired':
						case 'delete':
						case 'do_nothing':
						case 'mark_as_undesired':
						
							self::Trace(".. Undesired: ticket was marked as closed before.");
							self::HandleViolation();
							
							// No fallback
							
							// Stop processing any further!
							return false;

							break; // Defensive programming
							 
						case 'fallback_reopen': 
							// Reopen ticket
							self::Trace("... Fallback: reopen closed ticket."); 
							self::$oTicket->ApplyStimulus('ev_reopen');
							break; 
							
						default:
							// Should not happen.
							self::Trace("... Unknown action for closed tickets.");
							break; 
						
					}
				}
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyUndesiredTitlePatterns Offers a policy to handle undesired title patterns.
 */
abstract class PolicyUndesiredTitlePatterns extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_undesired_pattern';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if an undesired title pattern is found

			if(self::$oMailBox->Get(self::$sPolicyId.'_patterns') != '' ) { 
			
				// Go over each pattern and check.
				$aPatterns = preg_split(NEWLINE_REGEX, self::$oMailBox->Get(self::$sPolicyId.'_patterns')); 
				$sMailSubject = self::$oEmail->sSubject;
				
				foreach($aPatterns as $sPattern) {
					if(trim($sPattern) != '') {
							
						$oPregMatched = @preg_match($sPattern, $sMailSubject);
						
						if($oPregMatched === false) {
							self::Trace("... Invalid pattern: '{$sPattern}'");
						}
						elseif(preg_match($sPattern, $sMailSubject)) {
							
							switch(self::$oMailBox->Get(self::$sPolicyId.'_behavior')) { 
								case 'bounce_delete': 
								case 'bounce_mark_as_undesired':
								case 'delete':
								case 'do_nothing':
								case 'mark_as_undesired':
								
									self::Trace("... The message '{$sMailSubject}' is considered as undesired, since it matches {$sPattern}.");
									self::HandleViolation();
									
									// No fallback
									
									// Stop processing any further!
									return false;

									break; // Defensive programming
									
								default:
									// Should not happen.
									self::Trace("... Unknown action for closed tickets.");
									break; 
								
							}
							
						}
						else {
							self::Trace("... Pattern '{$sPattern}' not matched");
						}
					}
				}
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyUnknownCaller Offers a policy to find the caller and create a Person with default values if the caller appears to be unknown.
 */
abstract class PolicyUnknownCaller extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 100;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_unknown_caller';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if there's an unknown caller
		
			if(isset(self::$oEmail->oCaller) == false || self::$oEmail->oCaller === null) {
				
				$oCaller = null;
				$sContactQuery = 'SELECT Person WHERE email = :email';
				$sCallerEmail = self::$oEmail->sCallerEmail;
				$oSet = new \DBObjectSet(\DBObjectSearch::FromOQL($sContactQuery), array(), array('email' => $sCallerEmail));
				
				switch($oSet->Count()) {
					
					case 1:
						// Ok, the caller was found in iTop
						$oCaller = $oSet->Fetch();
						break;
						
					case 0:

						// Caller was not found.
						switch(self::$oMailBox->Get(self::$sPolicyId.'_behavior')) {
							
							case 'bounce_delete':
							case 'bounce_mark_as_undesired':
							case 'delete':
							case 'do_nothing':
							case 'mark_as_undesired':
							
								self::Trace("... The message '{$sMailSubject}' is considered as undesired, the caller was not found.");
								self::HandleViolation();
								
								// No fallback
								
								// Stop processing any further!
								self::$oMailBox->oCaller = $oCaller;
								return false;

								break; // Defensive programming

							case 'fallback_create_person':
								
								self::Trace("... Creating a new Person for the email: {$sCallerEmail}");
								$oCaller = new \Person();
								$oCaller->Set('email', self::$oEmail->sCallerEmail);
								$sDefaultValues = self::$oMailBox->Get(self::$sPolicyId.'_default_values');
								$aDefaults = preg_split('/\r\n|\r|\n/', $sDefaultValues);
								$aDefaultValues = array();
								foreach($aDefaults as $sLine) {
									if (preg_match('/^([^:]+):(.*)$/', $sLine, $aMatches)) {
										$sAttCode = trim($aMatches[1]);
										$sValue = trim($aMatches[2]);
										$aDefaultValues[$sAttCode] = $sValue;
									}
								}
								self::Trace('... Default values: '.json_encode($aDefaultValues));
								self::$oMailBox->InitObjectFromDefaultValues($oCaller, $aDefaultValues);
								try
								{
									self::Trace("... Try to create user with default values");
									$oCaller->DBInsert();					
								}
								catch(Exception $e)
								{
									// This is an actual error.
									self::Trace("... Failed to create a Person for the email address '{$sCallerEmail}'.");
									self::Trace($e->getMessage());
									self::$oMailBox->HandleError(self::$oEmail, 'failed_to_create_contact', self::$oEmail->oRawEmail);
									// self::$oMailBox->oCaller = $oCaller;
									return false;
								}
									
								break;
								
						}
					
					default:
						self::Trace("... Found ".$oSet->Count()." callers with the same email address '{$sCallerEmail}', the first one will be used...");
						// Multiple callers with the same email address !!!
						$oCaller = $oSet->Fetch();
						
				}
			}
			else {
				self::Trace("... Caller already determined by previous policy. Skip.");
			}
		
		// Set caller for email
		self::$oEmail->oCaller = $oCaller;
		
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}


/**
 * Class PolicyFindCallerByContactMethod Offers a policy to find the caller based on ContactMethod (where contact_method = 'email' and caller_detail = 'the email address of the caller')
 *
 */
abstract class PolicyFindCallerByContactMethod extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * @details Should be run before PolicyUnknownCaller
	 */
	public static $iPrecedence = 99;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_find_caller_by_contact_method';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Note: even if a caller is NOT found, this method should always return "true".
		// Further processing will use the default PolicyUnknownCaller method which will block further processing if truly necessary.
		
		// Don't even bother if jb-contactmethod is not enabled as an extension.
		if(class_exists('ContactMethod') == false) {
			return true;
		}
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if there's an unknown caller
		
			if(isset(self::$oEmail->oCaller) == false || self::$oEmail->oCaller === null) {
				
				$oCaller = null;
				$sContactMethodQuery = 'SELECT ContactMethod WHERE contact_method = "email" AND contact_detail LIKE ":email"';
				$sCallerEmail = self::$oEmail->sCallerEmail;
				$oSet_ContactMethod = new \DBObjectSet(\DBObjectSearch::FromOQL($sContactMethodQuery), array(), array('email' => $sCallerEmail));
				
				switch($oSet_ContactMethod->Count()) {
					
					case 1:
						
						// Ok, the ContactMethod was found in iTop
						$oContactMethod = $oSet->Fetch();
						self::Trace("... Found ContactMethod: ID ".$oContactMethod->GetKey());
						
						$sContactMethodQuery = 'SELECT Person WHERE id = ":id"';
						$oSet_Person = new \DBObjectSet(\DBObjectSearch::FromOQL($sContactQuery, array(), array('id' => $oContactMethod->Get('person_id'))));
						
						// Should be 1
						if($oSet_Person->Count() == 1) {
							self::Trace("... Identified caller as Person: ID ".$oSet_Person->GetKey()." -> ".$oSet->Get('friendlyname'));
							
							// @todo How easily can it be assumed this is the same caller? Implement check on Person first_name, name in Email's subject or body?
							$oCaller = $oSet_Person->Fetch();
						}
						else {
							self::Trace("... Unidentified caller");
						}
						
						break;
						
					case 0:
		
						// ContactMethod was not found.
						self::Trace("... ContactMethod not found.");
					
					default:
						self::Trace("... Found multiple ContactMthods.");
						
						// @todo How easily can it be assumed this is the same caller? Implement check on Person first_name, name in Email's subject or body?
						
						
				}
				
				// Set caller for email
				self::$oEmail->oCaller = $oCaller;
		
			}
			else {
				self::Trace("... Caller already determined by previous policy. Skip.");
			}
		
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}

/**
 * Class PolicyRemoveTitlePatterns Offers a policy to remove patterns in titles (in message subject and later ticket title)
 * @todo Check if this works properly
 */
abstract class PolicyRemoveTitlePatterns extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * @details Do this as late as possible.
	 */
	public static $iPrecedence = 999;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_remove_pattern';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if an undesired title pattern is found

			if(self::$oMailBox->Get(self::$sPolicyId.'_patterns') != '' ) { 
			
				// Go over each pattern and check.
				$aPatterns = preg_split(NEWLINE_REGEX, self::$oMailBox->Get(self::$sPolicyId.'_patterns')); 
				$sMailSubject = self::$oEmail->sSubject;
				
				foreach($aPatterns as $sPattern) {
					if(trim($sPattern) != '') {
							
						$oPregMatched = @preg_match($sPattern, $sMailSubject);
						
						if($oPregMatched === false) {
							self::Trace("... Invalid pattern: '{$sPattern}'");
						}
						elseif(preg_match($sPattern, $sMailSubject)) {
							
							switch(self::$oMailBox->Get(self::$sPolicyId.'_behavior')) { 
								case 'fallback_remove':
								
									self::Trace("... Found pattern to remove: {$sPattern}. Removing it.");
									self::$oEmail->sSubject = preg_replace($sPattern, '', $sMailSubject);
									
									// No fallback
									
									// Stop processing any further!
									return false;

									break; // Defensive programming
									
								case 'do_nothing':
									// Should not happen.
									self::Trace("... Found pattern to remove: {$sPattern}. Doing nothing.");
									break; 
									
								default:
									// Should not happen.
									self::Trace("... Unknown action for closed tickets.");
									break; 
								
							}
							
						}
						else {
							self::Trace("... Pattern '{$sPattern}' not matched");
						}
					}
				}
			}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
}
