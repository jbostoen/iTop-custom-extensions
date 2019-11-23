<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-11-01 17:26:09
 *
 * Policy interface definition and some classes implementing it
 */
 
namespace jb_itop_extensions\mail_to_ticket;

/**
 * Interface iPolicy defines what the classes implementing policies should look like.
 */
interface iPolicy {
	
	/**
	 *
	 * @return boolean Whether this is compliant with a specified policy
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
	public static function AfterComplianceCheck();
	
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
	 * @var MailInboxStandard $oMailBox Mailbox
	 * @var EmailMessage $oEmail Email message
	 * @var Ticket|null $oTicket Ticket found based on ticket reference (or null if not found)
	 *
	 * @return boolean Whether this is compliant with a specified policy
	 */
	public static function Init(MailInboxStandard $oMailBox, EmailMessage $oEmail, $oTicket) {
		
		self::$oMailBox = $oMailBox;
		self::$oEmail = $oEmail;
		self::$oTicket = $oTicket;
	
	}
	
	/**
	 * Checks if mailbox, email, ticket information is compliant with a certain policy.
	 *
	 * @return boolean Whether this is compliant with a specified policy
	 */
	public static function IsCompliant() {
		
		parent::BeforeComplianceCheck();
		parent::AfterComplianceCheck();
		return true;
		
	}
	
	/**
	 * Runs some default functionality BEFORE checking the policy. Use case: logging some information.
	 * Can be cascaded to subclasses.
	 * It exposes the same parameters as the IsCompliant() method.
	 *
	 * @var MailInboxStandard $oMailBox Mailbox
	 * @var EmailMessage $oEmail Email message
	 * @var Ticket|null $oTicket Ticket found based on ticket reference (or null if not found)
	 *
	 * @return void
	 */
	public static function BeforeComplianceCheck() {
	
		self::$oMailBox->Trace('Policy check starting: '.get_called_class());
		
		if(property_exists(self::$sPolicyId) == true) {
			$oMailbox->Trace('. Behavior: '.self::$oMailBox->Get(self::$sPolicyId.'_behavior'));
		}
		
	}
	
	/**
	 * Runs some default functionality AFTER checking the policy. Use case: logging some information.
	 * Can be cascaded to subclasses.
	 *
	 * @return void
	 */
	public static function AfterComplianceCheck() {
	
		self::$oMailBox->Trace('Policy check complete: '.get_called_class());
		
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
		$sFrom = self::$oEmail->Get('notify_from'); 
	
		// Policy violations have a typical way of handling.
		// The behavior is - besides some fallbacks - usually one of the following:
		// - bounce_delete -> bounce and delete the message
		// - bounce_mark_as_undesired -> bounce and marks the message as undesired
		// - delete -> delete the message
		// - mark_as_undesired -> stays in the mailbox for a few days
		// - some sort of fallback -> doesn't matter here
		
		$sBehavior = self::$oMailBox->Get(self::$sPolicyId.'_behavior');
		self::$oMailBox->Trace('. Policy violated. Behavior: '.$sBehavior);
		
		// First check if e-mail notification must be sent to caller (bounce message)
		switch($sBehavior) {
		
			// Generic cases
			case 'bounce_delete':
			case 'bounce_mark_as_undesired':
				
				$sSubject = $oMailBox->Get(self::$sPolicyId.'_subject');
				$sBody = $oMailBox->Get(self::$sPolicyId.'_notification'); 
				
				// Return to sender
				if($sTo == ''){ 
					self::$oMailBox->Trace('.. No "to" defined, skipping bounce message.');
				}
				elseif($sFrom == ''){ 
					self::$oMailBox->Trace('.. No "from" defined, skipping bounce message.');
				}
				else if($oRawEmail){
					
					// Allow some customization in the bounce message
					$sSubject = self::ReplaceMailPlaceholders($sSubject);
					$sBody = self::ReplaceMailPlaceholders($sBody);
		
					self::Trace('Raw Email: '.$sSubject.'\n\n'.strip_tags($sBody));
					$oRawEmail->SendAsAttachment($sTo, $sFrom, $sSubject, $sBody);
				}
				
				break;
				
		}
		
				
		switch($sBehavior) {
				
			case 'bounce_delete':
			case 'delete': 
				self::Trace('Set next action for EmailProcessor to DELETE_MESSAGE');
				self::$setNextAction(EmailProcessor::DELETE_MESSAGE); // Remove the message from the mailbox
				break;
				
			// Mark as error should be irrelevant now. Keeping it just in case.
			case 'mark_as_error': 
				self::Trace('Set next action for EmailProcessor to MARK_MESSAGE_AS_ERROR');
				self::$setNextAction(EmailProcessor::MARK_MESSAGE_AS_ERROR); // Keep the message in the mailbox, but marked as error
				break;
				 
			case 'bounce_mark_as_undesired':
			case 'mark_as_undesired':
				self::Trace('Set next action for EmailProcessor to MARK_MESSAGE_AS_UNDESIRED');
				self::$setNextAction(EmailProcessor::MARK_MESSAGE_AS_UNDESIRED); // Keep the message temporarily in the mailbox, but marked as undesired
				break;
				
			// Any other action
			default:
				self::Trace('Set next action for EmailProcessor to NO_ACTION');
				self::$setNextAction(EmailProcessor::NO_ACTION);
				
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
		
		$sString = \MetaModel::ApplyParams($sString, $aParams);
		
	}
		 
}

/**
 * Class PolicyForbiddenAttachment Offers a policy to enforce some rules on the attachment.
 */
abstract class PolicyForbiddenAttachment extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 */
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_forbidden_attachments';
		
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if attachments are in line with configured policies.
		
			$aForbiddenMimeTypes = preg_split('/\\r\\n|\\r|\\n/', self::$oMailbox->Get(self::$sPolicyId.'_mimetypes') );
		
			if(count($aForbiddenMimeTypes) > 0) {
				
				self::$oMailbox->Trace('.. Checking if there are attachments with one of these MimeTypes: '. implode(' - ', $aForbiddenMimeTypes));
					
				switch(self::$oMailbox->Get(self::$sPolicyId.'_behavior')) {
					
					case 'bounce_delete':
					case 'bounce_mark_as_undesired':
					case 'mark_as_undesired':
					case 'delete':
						
						// Forbidden attachments? 
						foreach($oEmail->aAttachments as $aAttachment) { 
							self::$oMailbox->Trace('.. Attachment MimeType: '.$aAttachment['mimeType']);
							
							if(in_array($aAttachment['mimeType'], $aForbiddenMimeTypes) == true) {
								
								self::$oMailbox->Trace('... Found attachment with forbidden MimeType "'.$aAttachment['mimeType'].'"');
								self::HandleViolation();
								
								// No specific fallback
								
								
								// Stop processing any further!
								return false;
							}
						}
					
						break;
					
					case 'fallback_ignore_forbidden_attachments': 
						// Ticket will be processed. Forbidden attachments will be ignored.
						break;
						
					default:
						self::$oMailbox->Trace('.. Unexpected "behavior"');
						break;
				}
		
			}
			
		// Generic 'after' actions
		parent::AfterComplianceCheck();
		
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
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_mail_size_too_big';
		
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if mail size is not too big
		
			$iMailSize = $oEmail->oRawEmail->GetSize();
			$iLimitMailSize = ($this->Get($this->sPolicyId.'_big_max_size_MB') * 1024 * 1024);
			
			if($iMailSize > $iLimitMailSize) {
				
				// Mail size too big
				self::$oMailbox->Trace('... Mail size too big: mail size = '.$iMailSize.' bytes, while limit is '.$iLimitMailSize.' bytes');
				self::HandleViolation();
				
				// No fallback
				
				// Stop processing any further!
				return false;
				
			}
			
		// Generic 'after' actions
		parent::AfterComplianceCheck();
		
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
	public static $iPrecedence = 50;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_no_subject';
		
	/**
	 * Checks if all information within the e-mail is compliant with the policies defined for this mailbox
	 *
	 * @return boolean Whether this is compliant with a specified policy
	 */
	public static function IsCompliant() {
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		// Checking if subject is not empty.
		
			$sPolicyBehavior = self::$oMailBox->Get(self::$sPolicyId.'_behavior');
			
			switch($sPolicyBehavior) {
				 // Will use default subject.
				 case 'bounce_delete':
				 case 'bounce_mark_as_undesired':
				 case 'delete':
				 case 'mark_as_undesired':
					 
					if( $oEmail->sSubject == '') {

						// Mail size too big
						self::$oMailbox->Trace('... Mail size too big: mail size = '.$iMailSize.' bytes, while limit is '.$iLimitMailSize.' bytes');
						self::HandleViolation();
						
						// No fallback
						
						// Stop processing any further!
						return false;
						
					}
					break;
					
				case 'fallback_default_subject':
				
					self::$oMailbox->Trace('.. Fallback: setting title to '.$sDefaultTitle);
					
					// Set ticket title of e-mail message
					// Setting the ticket title on the ticket object happens later and not in this policy!
					$sDefaultTitle = self::$oMailBox->Get($this->sPolicyId.'_default_subject');
					self::$oEmail->sSubject = $sDefaultTitle;
					break;
				
				default:
					self::$oMailbox->Trace('.. Unexpected "behavior"');
					break;
				
			}
			
		// Generic 'after' actions
		parent::AfterComplianceCheck();
		
		return true;
		
	}
	
}
