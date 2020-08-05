<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-08-05 19:34:49
 *
 * Classes implementing iPolicy. These policies use ContactMethod objects to find Contacts (caller, additional contacts)
 * 
 */
 
namespace jb_itop_extensions\mail_to_ticket;

/**
 Class PolicyHelper_ContactMethod defines some shared functionality.
*/
abstract class PolicyHelper_ContactMethod {
	
	// @todo Move this to configuration
	/**
	 * @var \String[] Array of patterns to search which indicates a closing line
	 */
	public $aEndSearchPatterns = [
		
		// Dutch phrases pointing to end of message
		'/(groeten|groetjes)/i',
		'/^hoogachtend/i',
		'/^alvast/i',
		'/dank bij voorbaat/i',
		'/^m(|\.)v(|\.)g/i'
	];
	
	
	/**
	 * Additional tracing during development
	 */
	public static function ExtraTrace($sInput) {
		
		// For extra debugging
		// echo '... Extra trace: '.$sInput;
		
		$fp = fopen('C:/temp/policy_ContactMethod.txt', 'a');//opens file in append mode.
		fwrite($fp, $sInput);
		fclose($fp);
		
	}
	
	/**
	 * Returns a regex pattern (without slashes at the front or end).
	 * Replaces non A-z characters; gives some room for spaces.
	 *
	 * @param \String $sInput Input
	 *
	 * @return \String
	 */
	public static function GetRegexForName($sInput) {
		
		$sInput = trim($sInput);
		
		// Strip out atypical characters, be slightly forgiving (allow up to 3 characters instead of 1)
		// Potential issue: 'José' => 'Joske' would also match
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
	 * @details Does not simply look at 'from:' name since this might for instance be the husband's name while the wife is sending an e-mail
	 *
	 */
	public static function GetScoreOfPersonNameInEmailBody(\EmailMessage $oEmail, \Person $oCaller) {
		
		$iLength = -1; // Nothing found
		
		// Do something about accents; only keep A-z and add LIMITED wildcard(s)
		
		// Run on plain text
		$sBodyText = \utils::HtmlToText($oEmail->sBodyText);
		$aLines = preg_split(NEWLINE_REGEX, $sBodyText);
		
		$sPattern_FirstName = self::GetRegexForName($oCaller->Get('first_name'));
		$sPattern_LastName = self::GetRegexForName($oCaller->Get('name'));
		
		// Put the needles in order of preference.
		// Method will exit on first match!
		$aSearchPatterns = [
			'/^('.$sPattern_FirstName.' '.$sPattern_LastName.')$/',
			'/^('.$sPattern_LastName.' '.$sPattern_FirstName.')$/',
			
			// Ending with ...
			'/^('.$sPattern_LastName.')$/', // last names are usually longer than first names (?). Less likely to be the only content of a line though
			'/^('.$sPattern_Firstname.')(?![A-z]+).*$/', // Might be too common: 'an' in 'anders', 'ben' in 'ik ben', ... Should almost be the last line; but how to know it's a name and not a sentence?
		];
		
		// Name is most likely to be at the end of the e-mail
		// Usually e-mails end with contact details including name (sometimes disclaimer)
		$aLines = array_reverse($aLines);
		
		foreach($aLines as $sLine) {
			
			$sLine = trim($sLine);
			$sLine = preg_replace('/\n\s+/', '\n', rtrim(html_entity_decode(strip_tags($sLine)))); // Plain text
			
			self::ExtraTrace("Line = {$sLine}");
				
			foreach($aSearchPatterns as $sSearchPattern) {
				
				self::ExtraTrace("Search pattern = {$sSearchPattern}");
					
				// Find as complete line
				if(preg_match_all($sSearchPattern, $sLine, $aMatches)) {
					
					// Found something!
					// In a preg_match_all, the first group should be found in [0][0]
					self::ExtraTrace("Found = {$aMatches[0][0]}");
		
					// Order of $aSearchPatterns matters; so now return the length of what was found (one or multiple times)
					$iLength = strlen($aMatches[0][0]);
					break 2;
					
				}
			
			}
			
			foreach(self::$aEndSearchPatterns as $sEndSearchPattern) {
			
				if(preg_match($sEndSearchPattern, $sLine)) {
					break 2; // Stop searching; break out of foreach's
				}
			
			}
			
		}		
		
		// Return
		self::ExtraTrace("Length = {$iLength}");
		return $iLength;
		
	}
	
}
 
/**
 * Class PolicyFindCallerByContactMethod Offers a policy to find the caller based on ContactMethod.
 *
 * @details Runs OQL: SELECT ContactMethod WHERE contact_method = 'email' and caller_detail = 'the email address of the caller'
 *
 * Keep in mind: e-mail address might be shared by multiple people.
 * One approach could be to set a caller anyway since the e-mail will arrive properly and people obviously trust each other enough to share the e-mail address.
 * Another approach might be to NOT set a caller if an appropriate match is not found? But what if people don't use their name?
 * It would create another Person (caller) which might not be necessary.
 *
 * If at least one ContactMethod is found, it will check if a combination of both the first_name and name of the Person are in the body text of the e-mail.
 * If no ContactMethod is found, nothing will be done and further processing will happen in PolicyFindCaller::IsCompliant():
 * creating a Person -> resulting in creation of ContactMethod for future use
 * 
 *
 * Also incorporate existing Ticket caller_id!
 * Only on incoming emails where caller is unidentified?
 * What if caller was already linked to the ticket?
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
				$oSet_ContactMethod = new \DBObjectSet(\DBObjectSearch::FromOQL($sContactMethodQuery), [], ['email' => $sCallerEmail]);
				
				switch($oSet_ContactMethod->Count()) {
					
					case 1:
						
						// Ok, the ContactMethod was found in iTop
						$oContactMethod = $oSet->Fetch();
						self::Trace("... Found ContactMethod: ID ".$oContactMethod->GetKey());
						
						$sContactMethodQuery = 'SELECT Person WHERE id = ":id"';
						$oSet_Person = new \DBObjectSet(\DBObjectSearch::FromOQL($sContactQuery, [], ['id' => $oContactMethod->Get('person_id')]));
						
						// Should be 1 Person
						if($oSet_Person->Count() == 1) {
							
							self::Trace("... Possible Person ID ".$oSet_Person->GetKey()." -> ".$oSet->Get('friendlyname'));
							
							// Check if this Person's name comes close enough
							$oCaller = $oSet_Person->Fetch();
							
							// What score is acceptable?
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
							// Person deleted? ContactMethod should not exist.
							self::Trace("... ContactMethod found, but caller has not been identified");
						}
						
						break;
						
					case 0:
		
						// ContactMethod was not found.
						self::Trace("... ContactMethod not found.");
						break;
					
					default:
					
						self::Trace("... Found multiple ContactMethod objects.");
							
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
						// Same dilemma as the default iPolicy "PolicyFindCaller": 
						// What to do if caller is not (likely) identified? Return Person linked to first ContactMethod?
						// Current design choice: leave caller for now and let another policy handle this.
						
				}
		
				if($oCaller === null) {
					self::Trace("... Caller has not been identified (or without enough certainty).");
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

// Originally there was an idea to add PolicyFindAdditionalContactsByContactMethod
// However: email addresses can be easily linked, but it will be much more difficult (especially if they're not mentioned in the email) to know 
// if the right Person is found.

