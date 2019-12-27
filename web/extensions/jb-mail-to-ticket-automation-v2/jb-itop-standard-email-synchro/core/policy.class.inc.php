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
 
// @todo Get rid of MailInboxBase::AddAttachments() and MailInboxBase::UpdateAttachments()
// @todo Review MailInboxBase::FitTextIn()
// @todo Revise PolicyNoOtherRecipients: should this one be creating contacts as a fallback?
		
namespace jb_itop_extensions\mail_to_ticket;

const NEWLINE_REGEX = '/\r\n|\r|\n/';

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
	public static $iPrecedence = 20;
	
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
			
			$sAttCode = get_called_class()::$sPolicyId.'_behavior');
			if(\MetaModel::IsValidAttCode(get_class(self::$oMailBox), $sAttCode) == true) {
				self::Trace('.. Behavior: '.self::$oMailBox->Get($sAttCode);
			}
		}
	}
	
	/**
	 * Runs some default functionality AFTER checking the policy IF IsCompliant() returned 'true'. Use case: logging some information.
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
	public static $iPrecedence = 10;
	
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
	public static $iPrecedence = 10;
	
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
	public static $iPrecedence = 10;
	
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
 * Class PolicyNoOtherRecipients Offers a policy to enforce being the sole recipient (no other recipients in To:, CC:). Does NOT change "related contacts"!
 */
abstract class PolicyNoOtherRecipients extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 20;
	
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
		
		$oEmail = self::$oEmail;
		$oMailBox = self::$oMailBox;
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if subject is not empty.
		
			$sCallerEmail = $oEmail->sCallerEmail;
								
			// Take both the To: and CC:
			$aAllContacts = array_merge($oEmail->aTos, $oEmail->aCCs);
			
			// Ignore sender; helpdesk mailbox; any helpdesk mailbox aliases
			$aExcludeContacts = array_merge([ $oEmail->sCallerEmail, $oMailBox->Get('login') ], preg_split(NEWLINE_REGEX, $oMailBox->Get('mail_aliases')));

			$sPolicyBehavior = $oMailBox->Get(self::$sPolicyId.'_behavior');
			
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
								$oCaller->Set('email', $oEmail->sCallerEmail);
								$sDefaultValues = $oMailBox->Get(self::$sPolicyId.'_default_values');
								$aDefaults = preg_split(NEWLINE_REGEX, $sDefaultValues);
								$aDefaultValues = array();
								foreach($aDefaults as $sLine) {
									if (preg_match('/^([^:]+):(.*)$/', $sLine, $aMatches)) {
										$sAttCode = trim($aMatches[1]);
										$sValue = trim($aMatches[2]);
										$aDefaultValues[$sAttCode] = $sValue;
									}
								}
								
								$oMailBox->InitObjectFromDefaultValues($oCaller, $aDefaultValues);
								try {
									self::Trace("Try to create user with default values");
									$oCaller->DBInsert();					
								}
								catch(Exception $e) {
									// This is an actual error.
									self::Trace("Failed to create a Person for the email address '{$sCallerEmail}'.");
									self::Trace($e->getMessage());
									$oMailBox->HandleError($oEmail, 'failed_to_create_contact', $oEmail->oRawEmail);
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
					$oEmail->aTos = [];
					$oEmail->aCCs = [];
				
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
	 */
	public static $iPrecedence = 10;
	
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
		
		$oMailBox = self::$oMailBox;
		$oEmail = self::$oEmail;
		$oTicket = self::$oTicket;
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// @todo: what is $oEmail->oRelatedObject? A Ticket object?
		// Is the ticket valid in the iTop database or does the number NOT match?
		
		// Checking if ticket reference is invalid
		// Due to an earlier GetRelatedTicket() call in MailInboxStandard, Ticket would NOT have been null if there was a valid reference.
		if($oTicket === null) {
		
			// This could be a new ticket. Then it's logical the Ticket object is null. 
			// So check if there was something (header or pattern in subject) which would have lead the system to believe there was a ticket. 
			
			// Are there patterns which should be ignored/removed from the title? 
			// To find the reference, let's remove it from our temp variable. 
			$sSubject = $oEmail->sSubject;
			
			// Here the removal/ignoring of patterns happens; on a copy of the subject string; only to find related tickets.
			foreach(['policy_remove_pattern_patterns', 'title_pattern_ignore_patterns'] as $sAttCode) {
				$sPatterns = $oMailBox->Get($sAttCode);
				
				if(trim($sPatterns) != '') {
					
					$aPatterns = preg_split(NEWLINE_REGEX, $sPatterns);
					
					self::Trace(".. Removing undesired title patterns: {$sPatterns}");
					
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
			
			$sPattern = $oMailBox->FixPattern($oMailBox->Get('title_pattern'));
			if(($sPattern != '') && (preg_match($sPattern, $sSubject, $aMatches))) {
				self::Trace(".. Undesired: unable to find any prior ticket despite a matching ticket reference pattern in the subject ('{$sPattern}').");
				return false;
			} 
			elseif($oEmail->oRelatedObject != null ) {
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
	public static $iPrecedence = 20;
	
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
	public static $iPrecedence = 20;
	
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
	public static $iPrecedence = 20;
	
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
 * Class PolicyFindCaller Offers a policy to find the caller and create a Person with default values if the caller appears to be unknown.
 */
abstract class PolicyFindCaller extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 110;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 * @details Note: different short name because of (legacy) attribute fields!
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
		
			if(isset(self::$oEmail->oInternal_Contact) == false || self::$oEmail->oInternal_Contact === null) {
				
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
								$aDefaults = preg_split(NEWLINE_REGEX, $sDefaultValues);
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
									return false;
								}
									
								break;
								
						}
					
					default:
						self::Trace("... Found ".$oSet->Count()." callers with the same email address '{$sCallerEmail}', the first one will be used...");
						// Multiple callers with the same email address !!!
						$oCaller = $oSet->Fetch();
						
				}
				
				// Set caller for email
				self::$oEmail->oInternal_Contact = $oCaller;
				
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
 * Class PolicyFindCallerByContactMethod Offers a policy to find the caller based on ContactMethod (where contact_method = 'email' and caller_detail = 'the email address of the caller')
 *
 */
abstract class PolicyFindCallerByContactMethod extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * @details Should be run before PolicyUnknownCaller; so this $iPrecedence should be lower than that of PolicyUnknownCaller
	 */
	public static $iPrecedence = 95;
	
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
		
			if(isset(self::$oEmail->oInternal_Contact) == false || self::$oEmail->oInternal_Contact === null) {
				
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
				self::$oEmail->oInternal_Contact = $oCaller;
		
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
	 * @details Do this as late as possible; but before PolicyUnknownTicketReference
	 */
	public static $iPrecedence = 110;
	
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
		
			$oMailBox = self::$oMailBox;
			$oEmail = self::$oEmail;

			if($oMailBox->Get(self::$sPolicyId.'_patterns') != '' ) { 
			
				// Go over each pattern and check.
				$aPatterns = preg_split(NEWLINE_REGEX, $oMailBox->Get(self::$sPolicyId.'_patterns')); 
				$sMailSubject = $oEmail->sSubject;
				
				foreach($aPatterns as $sPattern) {
					if(trim($sPattern) != '') {
							
						$oPregMatched = @preg_match($sPattern, $sMailSubject);
						
						if($oPregMatched === false) {
							self::Trace("... Invalid pattern: '{$sPattern}'");
						}
						elseif(preg_match($sPattern, $sMailSubject)) {
							
							switch($oMailBox->Get(self::$sPolicyId.'_behavior')) { 
								case 'fallback_remove':
								
									self::Trace("... Found pattern to remove: {$sPattern}. Removing it.");
									$oEmail->sSubject = preg_replace($sPattern, '', $sMailSubject);
									
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

/**
 * Class PolicyCreateOrUpdateTicket Special policy; at this point the Ticket is created or updated.
 * @details Replaces Combodo's MailInboxStandard::UpdateTicketFromEmail() and MailInboxStandard::UpdateTicketFromEmail()
 */
abstract class PolicyCreateOrUpdateTicket extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * 
	 * @details This is a special policy which takes care of only basic Ticket creation or update. 
	 * Any real checks that block Ticket creation or update, should have been run by now. 
	 * Any policies following this one, should not be blocking!
	 */
	public static $iPrecedence = 120;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_create_or_update_ticket';
	
	/*
	 * @var \Array $aAddedAttachments Array containing info on any attachments in the e-mail
	 */
	public static $aAddedAttachments = [];
	
	/*
	 * @var \Array $aIgnoredAttachments Array containing info on any attachments in the e-mail
	 */
	public static $aIgnoredAttachments = [];
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox.
	 * Heavily inspired by Combodo's MailInboxStandard::CreateTicketFromEmail()
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		$sBehavior = self::$oMailBox->Get('behavior');
		$oEmail = self::$oEmail;
		
		switch($sBehavior)
		{
			case 'create_only':
				self::CreateTicketFromEmail();
				break;
			
			case 'update_only':
				if (!is_object($oTicket))
				{
					// No ticket associated with the incoming email, nothing to update, reject the email
					self::$oMailBox->HandleError($oEmail, 'nothing_to_update', $oEmail->oRawEmail);
				}
				else {
					// Update the ticket with the incoming eMail
					self::UpdateTicketFromEmail();
				}
				break;
			
			default: // both: update or create as needed
				if(is_object($oTicket) == false) {
					// Create a new ticket
					self::CreateTicketFromEmail();
				}
				else {
					// Update the ticket with the incoming eMail
					self::UpdateTicketFromEmail();
				}
				break;			
		}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
	
	/**
	 * Function inspired by Combodo's MailInboxStandard::CreateTicketFromEmail()
	 */
	public static function CreateTicketFromEmail() {
		
		$oEmail = self::$oEmail;
		$oMailBox = self::$oMailBox;
		$oTicket = self::$oTicket;
		
		// In case of error (exception...) set the behavior
		if ($oMailBox->Get('error_behavior') == 'delete') {
			 // Remove the message from the mailbox
			$oMailBox->SetNextAction(\EmailProcessor::DELETE_MESSAGE);
		}
		else {
			 // Keep the message in the mailbox, but marked as error
			$oMailBox->SetNextAction(\EmailProcessor::MARK_MESSAGE_AS_ERROR);
		}
		
		self::Trace(".. Creating a new Ticket from eMail {$oEmail->sSubject}");
		$sTargetClass = $oMailBox->Get('target_class');
			
		if(\MetaModel::IsValidClass($sTargetClass) == false) {
			$sErrorMessage = "... Invalid 'ticket_class' configured: {$sTargetClass} is not a valid class. Cannot create such an object.";
			self::Trace($sErrorMessage);
			throw new Exception($sErrorMessage);
		}
		
		if($oEmail->oInternal_Contact === null || get_class($oEmail->oInternal_Contact)) {
			$sErrorMessage = "... Invalid caller specified: Cannot create Ticket without valid Person.";
			self::Trace($sErrorMessage);
			throw new Exception($sErrorMessage);			
		}
		
		$oTicket = \MetaModel::NewObject($sTargetClass);
		$oTicket->Set('org_id', $oEmail->oInternal_Contact->Get('org_id'));
		if(\MetaModel::IsValidAttCode($sTargetClass, 'caller_id')) {
			$oTicket->Set('caller_id', $oCaller->GetKey());
		}
		if(\MetaModel::IsValidAttCode($sTargetClass, 'origin')) {
			$oTicket->Set('origin', 'mail');
		}
		
		// Max length for title
		$oTicketTitleAttDef = \MetaModel::GetAttributeDef($sTargetClass, 'title');
		$iTitleMaxSize = $oTicketTitleAttDef->GetMaxSize();
		$sSubject = $oEmail->sSubject;
		$oTicket->Set('title', substr($sSubject, 0, $iTitleMaxSize));
		
		// Insert the remaining attachments so that their ID is known and the attachments can be referenced in the message's body
		// Cannot insert them for real since the Ticket is not saved yet (so Ticket id is unknown)
		// UpdateAttachments() will be called once the ticket is properly saved
		self::$aAddedAttachments = $oMailBox->AddAttachments($oTicket, $oEmail, true, self::$aIgnoredAttachments);
		
		// Seems to be for backward compatibility / plain text.																							
		$oTicketDescriptionAttDef = \MetaModel::GetAttributeDef($sTargetClass, 'description');
		$bForPlainText = true; // Target format is plain text (by default)
		if ($oTicketDescriptionAttDef instanceof \AttributeHTML) {
			// Target format is HTML
			$bForPlainText = false;
		}
		elseif($oTicketDescriptionAttDef instanceof \AttributeText) {
			$aParams = $oTicketDescriptionAttDef->GetParams();
			if(array_key_exists('format', $aParams) && ($aParams['format'] == 'html')) {
				// Target format is HTML
				$bForPlainText = false;
			}
		}
		
		self::Trace(".. Target format for 'description': ".($bForPlainText ? 'text/plain' : 'text/html'));
		self::Trace(".. Email body format: ".$oEmail->sBodyFormat);
		
		$sTicketDescription = self::BuildDescription($bForPlainText);

		$iDescriptionMaxSize = $oTicketDescriptionAttDef->GetMaxSize();
		if(strlen($sTicketDescription) > $iDescriptionMaxSize) {
			$oEmail->aAttachments[] = [
				'content' => $sTicketDescription, 
				'filename' => ($bForPlainText == true ? 'original message.txt' : 'original message.html'), 
				'mimeType' => ($bForPlainText == true ? 'text/plain' : 'text/html')
			];
		}
		
		// Keep some room just in case... (in case of what?)
		$oTicket->Set('description', $oMailBox->FitTextIn($sTicketDescription, $iDescriptionMaxSize - 1000)); 
		
		// Default values
		$sDefaultValues = $oMailBox->Get('ticket_default_values');
		$aDefaults = preg_split(NEWLINE_REGEX, $sDefaultValues);
		$aDefaultValues = [];
		foreach($aDefaults as $sLine) {
			if (preg_match('/^([^:]+):(.*)$/', $sLine, $aMatches)) {
				$sAttCode = trim($aMatches[1]);
				$sValue = trim($aMatches[2]);
				$aDefaultValues[$sAttCode] = $sValue;
			}
		}
		$oMailBox->InitObjectFromDefaultValues($oTicket, $aDefaultValues);
		
		self::AddAdditionalContacts();
		
		self::BeforeInsertTicket();
		$oTicket->DBInsert();
		self::Trace(".. Ticket ".$oTicket->GetName()." created.");
		self::AfterInsertTicket();
		
	}
	
	/**
	 * Function inspired by Combodo's MailInboxStandard::BeforeInsertTicket().
	 * Called right before a Ticket is created.
	 *
	 * @return void
	 */
	public static function BeforeInsertTicket() {
		 
		// Do nothing
	
	}
	
	/**
	 * Function inspired by Combodo's MailInboxStandard::AfterInsertTicket().
	 * Called right after a Ticket is created.
	 *
	 * @return void
	 */
	public static function AfterInsertTicket() {
		 
		// Process attachments now the ID is known
		self::$oMailBox->UpdateAttachments(self::$aAttachments, self::$oTicket);
		
		// Shall we delete the source email immediately?
		if(self::$oMailBox->Get('email_storage') == 'delete') {
			// Remove the processed message from the mailbox
			self::Trace(".. Ticket created, deleting the source eMail '{$oEmail->sSubject}'");
			self::$oMailBox->SetNextAction(\EmailProcessor::DELETE_MESSAGE);		
		}
		else {
			// Keep the message in the mailbox
			self::$oMailBox->SetNextAction(\EmailProcessor::NO_ACTION);		
		}	
	
	}
	 
	/**
	 * Function inspired by Combodo's MailInboxStandard::BuildDescription().
	 * Returns a description for a new Ticket.
	 *
	 * @param \Boolean $bForPlainText True if the desired output format is plain text, false if HTML
	 * @return \String Ticket description
	 */
	public static function BuildDescription($bForPlainText) {
		
		$sTicketDescription = '';
		
		if(self::$oEmail->sBodyFormat == 'text/html') {
			// Original message is in HTML
			self::Trace("... Managing inline images...");
			$sTicketDescription = self::ManageInlineImages(self::$oEmail->sBodyText, $bForPlainText);
			if($bForPlainText == true) {
				self::Trace("... Converting HTML to text using \utils::HtmlToText...");
				$sTicketDescription = \utils::HtmlToText(self::$oEmail->sBodyText);
			}
		}
		else
		{
			// Original message is in plain text
			$sTicketDescription = \utils::TextToHtml(self::$oEmail->sBodyText);
			if($bForPlainText == false) {
				self::Trace("... Converting text to HTML using utils::TextToHtml...");
				$sTicketDescription = \utils::TextToHtml(self::$oEmail->sBodyText);
			}
		}

		if(empty($sTicketDescription) == true) {
			// Will use language of the user under which the cron job is being executed.
			$sTicketDescription = \Dict::S('MailPolicy:CreateOrUpdateTicket:NoDescriptionProvided');
		}
		
		return $sTicketDescription;
		
	}
	
	/**
	 * Function inspired by Combodo's MailInboxStandard::ManageInlineImages().
	 *
	 * @param \String $sBodyText Body text
	 * @param \Boolean $bForPlainText Plain text (true) or HTML (false)
	 * 
	 * @return \String Body text
	 */
	public static function ManageInlineImages($sBodyText, $bForPlainText) {
		 
		// Search for inline images: i.e. <img tags containing an src="cid:...." or without double quotes e.g. src=cid:xyzxyzx
		// Note: (?: ... ) is used for grouping the alternative without creating a "matching group"
		if(preg_match_all('/<img[^>]+src=(?:"cid:([^"]+)"|cid:([^ >]+))[^>]*>/i', $sBodyText, $aMatches, PREG_OFFSET_CAPTURE)) {
			$aInlineImages = []
			foreach ($aMatches[0] as $idx => $aInfo) {
				$aInlineImages[$idx] = array(
					'position' => $aInfo[1]
				);
			}
			foreach ($aMatches[1] as $idx => $aInfo) {
				$sCID = $aInfo[0];
				if(array_key_exists($sCID, self::$aAddedAttachments) == false && array_key_exists($sCID, self::$aIgnoredAttachments) == false) {
					self::Trace(".... Info: inline image: {$sCID} not found as an attachment. Ignored.");
				}
				elseif(array_key_exists($sCID, self::$aAddedAttachments)) {
					$aInlineImages[$idx]['cid'] = $sCID;
					self::Trace(".... Inline image cid:$sCID stored as ".get_class(self::$aAddedAttachments[$sCID])."::".self::$aAddedAttachments[$sCID]->GetKey());
				}
			}
			if(defined('ATTACHMENT_DOWNLOAD_URL') == false) {
				define('ATTACHMENT_DOWNLOAD_URL', 'pages/ajax.render.php?operation=download_document&class=Attachment&field=contents&id=');
			}
			if($bForPlainText == true) {
				// The target form is text/plain, so the HTML tags will be stripped
				// Insert the URLs to the attachments, just before the <img tag so that the hyperlink remains (as plain text) at the right position
				// when the HTML tags will be stripped
				// Start from the end of the text to preserve the positions of the <img tags AFTER the insertion
				$sWholeText = $sBodyText;
				$idx = count($aInlineImages);
				while ($idx > 0) {
					$idx --;
					if (array_key_exists('cid', $aInlineImages[$idx]))
					{
						$sBefore = substr($sWholeText, 0, $aInlineImages[$idx]['position']);
						$sAfter = substr($sWholeText, $aInlineImages[$idx]['position']);
						$oAttachment = self::$aAddedAttachments[$aInlineImages[$idx]['cid']];
						$sUrl = \utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$oAttachment->GetKey();
						$sWholeText = $sBefore.' '.$sUrl.' '. $sAfter;
					}
				}
			}
			else {
				// The target format is text/html, keep the formatting, but just change the URLs
				$aSearches = [];
				$aReplacements = [];
				foreach(self::$aAddedAttachments as $sCID => $oAttachment)
				{
					$aSearches[] = 'src="cid:'.$sCID.'"';
					if(class_exists('InlineImage') == true && $oAttachment instanceof \InlineImage) {
						// Inline images have a special download URL requiring the 'secret' token
						$aReplacements[] = 'src="'.\utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.$oAttachment->GetKey().'&s='.$oAttachment->Get('secret').'"';
					}
					else {
						$aReplacements[] = 'src="'.\utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$oAttachment->GetKey().'"';
					}
					
					$aSearches[] = 'src=cid:'.$sCID; // Same without quotes
					if (class_exists('InlineImage') == true && ($oAttachment instanceof InlineImage)) {
						// Inline images have a special download URL requiring the 'secret' token
						$aReplacements[] = 'src="'.\utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.$oAttachment->GetKey().'&s='.$oAttachment->Get('secret').'" '; // Beware: add a space at the end
					}
					else {
						$aReplacements[] = 'src="'.\utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$oAttachment->GetKey().'" '; // Beware: add a space at the end
					}
				}
				$sWholeText = str_replace($aSearches, $aReplacements, $sBodyText);
			}
			$sBodyText = $sWholeText;
		}
		else {
			self::Trace("... Inline Images: no inline-image found in the message");
		}
		return $sBodyText;
		
	}
	 
	/**
	 * Function inspired by Combodo's MailInboxStandard::AddAdditionalContacts()
	 * Adds additional contacts in the e-mail as related contacts in the Ticket.
	 *
	 * @return void
	 */
	public static function AddAdditionalContacts() {
		
		$oTicket = self::$oTicket;
		$oEmail = self::$oEmail;
		
		$sTargetClass = get_class($oTicket);
		if\MetaModel::IsValidAttCode($sTargetClass, 'contacts_list') == false) {
			return;
		}
		
		$oContactsSet = $oTicket->Get('contacts_list');
		$aExistingContacts = [];
		
		while($oLnk = $oContactsSet->Fetch()) {
			$aExistingContacts[$oLnk->Get('contact_id')] = true;
		}

		foreach($oEmail->aInternal_Additional_Contacts as $oContact) {
			
			if(\MetaModel::IsValidAttCode($sTargetClass, 'caller_id') == true && $oContact->GetKey() != $oTicket->Get('caller_id')) {
				$oLnk = new \lnkContactToTicket();
				$oLnk->Set('contact_id', $oContact->GetKey());
				$oContactsSet->AddObject($oLnk);
			}
			else {
				$sContactName = $oContact->GetName();
				self::Trace(".... Skipping '{$sContactName}' as additional contact since it is the caller.");
			}
		}
		$oTicket->Set('contacts_list', $oContactsSet);
		
	}
	
}

abstract class PolicyFindAdditionalContactsByContactMethod implements iPolicy {

	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * @details Level should be higher than PolicyFindCallerByContactMethod and lower than the default PolicyFindAdditionalPerson
	 */
	public static $iPrecedence = 96;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_find_additional_contacts_by_contact_method';
	
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		if(class_exists('ContactMethod') == false) {
			return;
		}
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Find by ContactMethod
		$oEmail = self::$oEmail;
		$oMailBox = self::$oMailBox;
		$oTicket = self::$oTicket;
		
		if(count($oEmail->aInternal_Additional_Contacts) > 0) {
			self::Trace(".. Skipping, another policy already determined additional contacts.");
			return;
		}
			
		// Looking into these email properties:
		$aAdditionalContacts = array_merge($oEmail->aTos, $oEmail->aCCs);
		
		// If a ContactMethod is found, it's *Person* should not be in $oEmail->aInternal_Additional_Contacts yet.
		$aExistingContactIds = [];
		
		if(\MetaModel::IsValidAttCode($oMailBox->Get('target_class', 'caller_id') == true) {
			$oTicket->Get('caller_id') 
		}
		
		foreach($oEmail->aInternal_Additional_Contacts as $oContact) {
			$aExistingContactIds[] = $oContact->GetKey();
		}
		
		// List of e-mail addresses to exclude
		$aExcludeMailAddresses = array_map('strtolower', preg_split(NEWLINE_REGEX, $oMailBox->Get('mail_aliases'));
		$aExcludeMailAddresses[] = strtolower($oMailBox->Get('login'));
		
		foreach($aAdditionalContacts as $aInfo) {
			
			$sEmail_LowerCase = strtolower($aInfo['email']);
			
			// If not an excluded e-mail address (login, mail alias for mailbox ...)
			if(in_array($sEmail_LowerCase, $aExcludeMailAddresses) == false) {
				
				// Lookup
				$oFilter_ContactMethod = \DBObjectSearch::FromOQL('SELECT ContactMethod WHERE contact_method = "email" AND contact_detail LIKE "'.$sEmail_LowerCase.'"');
				$oSet_Contact_Methods = new \DBObjectSet($oFilter_ContactMethod);
					
				// If not already added with this e-mail address or an alias:
				if($oSet_ContactMethods->Count() == 1) {
					
					self::Trace("... Found additional Contact for '{$aInfo['email']}'");
					
					$oContactMethod = $oSet_ContactMethods->Fetch();
					
					if($oContactMethod->Get('person_id') != $oEmail->oInternal_Contact->GetKey() && in_array($oContactMethod->Get('person_id'), $aExistingContactIds) == false) {
						
						try {
							
							// Fetch Contact
							$oFilter_Contact = \DBObjectSearch::FromOQL('SELECT Person WHERE id = '.$oContactMethod->Get('person_id'));
							$oSet_Contacts = new \DBObjectSet($oFilter_Contact);
							$oContact = $oSet_Contacts->Fetch();
						
							$oEmail->aInternal_Additional_Contacts[] = $oContact;
							$aExistingContactIds[] = $oContactMethod->Get('person_id');
						}
						catch(\Exception $e) {
							// ContactMethod person_id can not be null. Database integrity issue?
							self::Trace("... Unexpected issue. Problem with integrity of ContactMethod?");
						}
			
					}
				}
				else {
					// This doesn't matter. If there's no Contact found by ContactMethod; there is still the default PolicyFindAdditionalContacts
					self::Trace("... Did not find an additional Contact for '{$aInfo['email']}'");
				}
			
			}
			else {
				self::Trace("... Ignoring '{$aInfo['email']}' = e-mail address for this mailbox");	
			}
			
		}
		
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
}

/*
abstract class PolicyFindAdditionalContacts implements iPolicy {
	// @todo Implement method to add Person objects to related contacts based on Person class
	// Create CC people which were not found?
}
*/

