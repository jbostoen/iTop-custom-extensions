<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-12-25 20:06:30
 *
 * Classes implementing iPolicy. These policies use ContactMethods to find Contacts (caller, additional contacts)
 * 
 */
 
namespace jb_itop_extensions\mail_to_ticket;
 
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
		
		// @todo Finish this and add some comparison to first_name, name of Person and e-mail body or "from" name?
		return true;
		
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
						self::Trace("... Found multiple ContactMethods.");
						
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
		
		// @todo Finish this policy and make it scan the email's content for first_name, name and compare it with the person's first_name, name?
		return true;
		
		if(class_exists('ContactMethod') == false) {
			return true;
		}
		
		// Generic 'before' actions
		parent::BeforeComplianceCheck();
		
		$oEmail = self::$oEmail;
		$oMailBox = self::$oMailBox;
		$oTicket = self::$oTicket;
		
		$sBehavior = $oMailBox->Get('policy_other_recipients_behavior');
		
		if(count($oEmail->aInternal_Additional_Contacts) > 0) {
			self::Trace(".. Skipping, another policy already determined additional contacts.");
		}
		elseif($sBehavior == 'fallback_ignore_other_contacts') {
			self::Trace(".. Skipping, policy_other_recipients_behavior is set to fallback_ignore_other_contacts");
		}
		else {
			
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
					
					self::Trace("... Looking for '{$aInfo['email']}'");
				
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
						self::Trace("... Did not find an additional Contact for '{$aInfo['email']}'. This policy won't create one.");
						
						// Check setting if new Contact should be created
						
					}
				
				}
				else {
					self::Trace("... Ignoring '{$aInfo['email']}' = e-mail address for this mailbox");	
				}
				
			}
		
		}
			
		// Generic 'after' actions
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}
}
