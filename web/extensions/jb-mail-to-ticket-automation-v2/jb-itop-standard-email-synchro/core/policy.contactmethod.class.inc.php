<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-01-29 14:37:35
 *
 * Classes implementing iPolicy. These policies use ContactMethod objects to find Contacts (caller, additional contacts)
 * 
 */
 
namespace jb_itop_extensions\mail_to_ticket;

/**
 Class PolicyHelper_ContactMethod defines some shared functionality.
*/
abstract class PolicyHelper_ContactMethod {
	
	/**
	 * Returns a regex pattern without / at the front or end.
	 * Replaces non A-z characters; gives some room for spaces.
	 *
	 * @param \String $sInput Input
	 *
	 * @return \String
	 */
	public static function GetRegexForName($sInput) {
		
		// Strip out atypical characters, be slightly forgiving (allow up to 3 characters instead of 1)
		$sInput = str_replace('[^A-Za-z]', '.{1,3}', $sInput);
		
		// Be forgiving in number of spaces in between
		$sInput = str_replace(' ', '[ ]{1,3}', $sInput);
		
		// preg_quote should not be necessary since weird characters were stripped.
		// if it's added; it should be BEFORE replacing it with regex patterns.
		
		return $sInput;
		
	}
		
	/**
	 * Searches an email's body (content) for names.
	 *
	 * @return \Integer Value (numbers of consecutive characters matched)
	 *
	 * @details Does not simply look at 'from:' name since this might for instance be the husband's name while the wife's sending an e-mail
	 *
	 * @uses PolicyHelper_ContactMethod::$aPersonDetailsFound
	 */
	public static function GetScoreOfPersonNameInEmailBody(\EmailMessage $oEmail, \Person $oCaller) {
		
		// Do something about accents; only keep A-z and add LIMITED wildcard(s)
		
		// Run on plain text
		$sBodyText = trim(\utils::HtmlToText($oEmail->sBodyText));
		$aLines = preg_split(NEWLINE_REGEX, $sBodyText);
		
		$sFirstName = self::GetRegexForName($oCaller->Get('first_name'));
		$sLastName = self::GetRegexForName($oCaller->Get('name'));
		
		// Put the needles in order of preference.
		// Method will exit on first match!
		$aNeedles = [
			$oCaller->Get('first_name').' '.$oCaller->Get('last_name'),
			$oCaller->Get('name').' '.$oCaller->Get('first_name'),
			
			// Ending with ...
			$oCaller->Get('name'), // last names are usually longer than first names (?)
			$oCaller->Get('first_name'), // Might be too common: 'an' in 'anders', 'ben' in 'ik ben', ... Should almost be the last line; but how to know it's a name and not a sentence?
		];
		
		
		// Name is most likely to be at the end of the e-mail
		// Usually e-mails end with contact details including name (sometimes disclaimer)
		$aLines = array_reverse($aLines);
		
		foreach($aLines as $sLine) {
			
			self::Trace("... TEMP DEV line = /^{$sLine}$/");
				
			foreach($aNeedles as $sNeedle) {
				
				$sNeedle = trim($sNeedle);
				
				self::Trace("... TEMP DEV find = /^{$sNeedle}$/");
					
				// Find as complete line
				if(preg_match('/^'.$sNeedle.'$/', $sLine, $aMatches)) {
					
					// Found something!
					self::Trace("... TEMP DEV found = {$aMatches[0]}");
		
					// Order of $aNeedles matters; so now return the length of what was found (one or multiple times)
					$iLength = strlen($aMatches[0]);
					break 2;
					
				}
			
			}
			
		}		
		
		// Return
		self::Trace("... TEMP DEV length = {$iLength}");
		return $iLength;
		
	}
	
}
 
/**
 * Class PolicyFindCallerByContactMethod Offers a policy to find the caller based on ContactMethod.
 *
 * @details Runs OQL: SELECT ContactMethod WHERE contact_method = 'email' and caller_detail = 'the email address of the caller'
 *
 * Keep in mind: e-mail address might be shared by multiple people.
 * One approach could be to set a caller anyway since the e-mail will arrive properly and people obviously trust each other enough to share.
 * Another approach might be to NOT set a caller if an appropriate match is not found? But what if people don't use their name?
 * It would create another Person (caller) which might not be necessary.
 *
 * If at least one ContactMethod is found, it will check if a combination of both the first_name and name of the Person are in the body text of the e-mail.
 * If no ContactMethod is found, nothing will be done and the next processing (creating a Person -> resulting in creation of ContactMethod for future use) 
 * will happen in PolicyFindCaller::IsCompliant()
 *
 * Also incorporate existing Ticket caller_id!
 * Only on incoming emails where caller is unidentified?
 * What if caler was already linked?
 *
 */
abstract class PolicyFindCallerByContactMethod extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * @details Should be run before PolicyFindCaller; therefore $iPrecedence should be lower than that of PolicyFindCaller
	 *
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
		// Further processing will use the default PolicyFindCaller method which will block further processing if truly necessary.
		
		// Don't even bother if jb-contactmethod is not enabled as an extension.
		if(class_exists('ContactMethod') == false) {
			self::Trace(".. Policy not relevant: class ContactMethod does not exist.");
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
							self::Trace("... Possible Person ID ".$oSet_Person->GetKey()." -> ".$oSet->Get('friendlyname'));
							
							// Check if this Person's name comes close enough
							$oCaller = $oSet_Person->Fetch();
							
							if(PolicyHelper_ContactMethod::GetScoreOfPersonNameInEmailBody(self::$oEmail, $oCaller) > 0) {
								self::Trace("... Match is very likely.");
							}
							else {
								self::Trace("... Match is rather unlikely; possibly another Person using this e-mail address. What to do?");
								// Keep the caller? Will get the email anyway.
								// Don't keep the caller? Might unnecessarily create a new Person.
								
								// Current implementation: keeps the Caller.
							}
							
						}
						else {
							self::Trace("... Unidentified caller");
						}
						
						break;
						
					case 0:
		
						// ContactMethod was not found.
						self::Trace("... ContactMethod not found.");
						break;
					
					default:
					
						self::Trace("... Found multiple ContactMethods.");
							
						// Find out if this Ticket was already mapped to a user.
						// Sure: someone else could be replying instead now from the same shared email address.
						// It might make the public_log dirty; but then again it will still refer to the original caller.
						if($oEmail->oRelatedObject !== null) {
							
							$iTicketCallerId = $oEmail->oRelatedObject->Get('caller_id');
							
							$oSet_ContactMethods->Rewind();
							while($oPotentialCaller = $oSet_ContactMethods->Fetch()) {
							
								if($oPotentialCaller->GetKey() == $iTicketCallerId) {
									$oCaller = $oPotentialCaller;
									break;
								}
								
							}
						}
						
						// If not linked yet...
						if($oCaller === null) {
							
							// Find out if there's a best match.
							// Loop over each ContactMethod to find each Person.
							$oSet_ContactMethods->Rewind();
							$aPersonIds = [ -1 ]; // Just to be sure
							
							while($oContactMethod = $oSet_ContactMethods->Fetch()) {
								$aPersonIds[] = $oContactMethod->Get('person_id');
							}
							
							// Just to be sure
							$aPersonIds = array_unique($aPersonIds);
							
							// Query each Person found in ContactMethod objects
							$oFilter_Person = \DBObjectSearch::FromOQL('SELECT Person WHERE id IN ('.implode(', ', $aPersonIds).')');
							$oSet_Persons = new \DBObjectSet($oFilter_Person);
							
							$iScore = -99; // Should be lower than -1 (smallest value returned by PolicyHelper_ContactMethod::GetScoreOfPersonNameInEmailBody())
						
							while($oPotentialCaller = $oSet_Persons->Fetch()) {
							
								$iCurrentScore = PolicyHelper_ContactMethod::GetScoreOfPersonNameInEmailBody(self::$oEmail, $oPotentialCaller);

								// Whenever the score is higher: assume it's more likely this is the actual caller
								if($iCurrentScore > $iScore) {
									$oCaller = $oPotentialCaller;
									$iScore = $iCurrentScore;
								}
								
							}
							
						
						}
						
						// Still null?
						
						// Same dilemma: what to do if caller is not (likely) identified? Person linked to first ContactMethod?
							
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
					$oSet_ContactMethods = new \DBObjectSet($oFilter_ContactMethod);
						
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
