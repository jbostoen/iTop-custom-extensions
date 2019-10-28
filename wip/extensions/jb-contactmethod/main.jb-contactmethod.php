<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-10-28 13:59:53
 *
 * PHP Main file
 */

class ApplicationObjectExtension_ContactMethod implements iApplicationObjectExtension {
	 	  
	/**
	 * Invoked to determine whether an object can be written to the database 
	 *	
	 * The GUI calls this verb and reports any issue.
	 * Anyhow, this API can be called in other contexts such as the CSV import tool.
	 * 
	 * @param DBObject $oObject The target object
	 * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
	 */	
	public function OnCheckToWrite( $oObject ) {
				
		// Note: you can not set properties here on the object! (so no way to fix the format)
		// Only blocks invalid input
				
		if( $oObject instanceof ContactMethod ) {
			
			$sContactDetail = $oObject->Get('contact_detail');
			
			switch( $oObject->Get('contact_method') ) {
				
				case 'phone':
				
					$sPhone_digits = \jb_contact_method\iTop_Helper_Phone::ReturnDigits( $sContactDetail );
					
					switch(true) {
						
						// Belgian land line phone number
						case \jb_contact_method\iTop_Helper_Phone::IsValidPhone_BE($sPhone_digits) == true:
						
						// International phone number - hopefully land line
						case \jb_contact_method\iTop_Helper_Phone::OnlyContainsAllowedCharacters($sContactDetail) == true && \jb_contact_method\iTop_Helper_Phone::IsLocal($sContactDetail) == false && strlen($sPhone_digits) > 8:
						
							// No error
							break;
						
						// Belgian mobile phone number
						case \jb_contact_method\iTop_Helper_Phone::IsValidMobilePhone_BE($sPhone_digits) == true:
						
						// Unidentified
						default:
						
							return Array( 
								Dict::S('Errors/ContactMethod/InvalidPhoneNumber')
							);
							
						
					}
				
					break;
				
				case 'mobile_phone':
				
					$sMobilePhone_digits = \jb_contact_method\iTop_Helper_Phone::ReturnDigits( $sContactDetail );
					
					switch(true) {
						
						// Belgian mobile phone number
						case \jb_contact_method\iTop_Helper_Phone::IsValidMobilePhone_BE($sMobilePhone_digits) == true:
						
						// International phone number - hopefully mobile
						case \jb_contact_method\iTop_Helper_Phone::OnlyContainsAllowedCharacters($sContactDetail) == true && \jb_contact_method\iTop_Helper_Phone::IsLocal($sContactDetail) == false && strlen($sMobilePhone_digits) > 9:
						
							// No error
							break;
						
						// Belgian land line phone number
						case \jb_contact_method\iTop_Helper_Phone::IsValidLandLinePhone_BE($sMobilePhone_digits) == true:
						
						// Unidentified
						default: 
							
							// Belgian land line phone number
							return Array( 
								Dict::S('Errors/ContactMethod/InvalidMobilePhoneNumber')
							);
							
					}
					
					break;
				
				
				case 'email':
								
					if( !filter_var($sContactDetail, FILTER_VALIDATE_EMAIL) ) {
					 
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidEmail')
						);
						
					}
				
				default:
					break;
					
			}
			
		}
		
		elseif( $oObject instanceof Person ) {
			
			$aErrors = Array();
			
			// Check phone
			// ---
			$sPhone_original = $oObject->Get('phone');
			$sPhone_digits = \jb_contact_method\iTop_Helper_Phone::ReturnDigits($sPhone_original);
			
			switch(true) {
				
				// Empty (OK for Person, NOT for ContactMethod)
				case strlen($sPhone_original) == 0:
				
				// Belgian land line number
				case \jb_contact_method\iTop_Helper_Phone::IsValidPhone_BE($sPhone_original) == true:
				
				// International phone number - hopefully land line
				case \jb_contact_method\iTop_Helper_Phone::OnlyContainsAllowedCharacters($sPhone_original) == true && \jb_contact_method\iTop_Helper_Phone::IsLocal($sPhone_digits) == false && strlen($sPhone_digits) > 8:
				
					// No error
					break;
				
				// Belgian mobile phone number
				case \jb_contact_method\iTop_Helper_Phone::IsValidMobilePhone_BE($sPhone_digits) == true:
				
				// Unidentified
				default:
				
					$aErrors[] = Dict::S('Errors/ContactMethod/InvalidPhoneNumber');
				
			}
			
			// Check mobile phone
			// ---
			$sMobile_original = $oObject->Get('mobile_phone');
			$sMobile_digits = \jb_contact_method\iTop_Helper_Phone::ReturnDigits($sMobile_original);
			
			switch(true) {
				
				// Empty (OK for Person, NOT for ContactMethod)
				case strlen($sMobile_original) == 0:
				
				// Belgian mobile phone number
				case \jb_contact_method\iTop_Helper_Phone::IsValidMobilePhone_BE($sMobile_original) == true:
				
				// International phone number - hopefully mobile
				case \jb_contact_method\iTop_Helper_Phone::OnlyContainsAllowedCharacters($sMobile_original) == true && \jb_contact_method\iTop_Helper_Phone::IsLocal($sMobile_digits) == false && strlen($sMobile_digits) > 9:
				
					// No error
					break;
				
				// Belgian land line number
				case \jb_contact_method\iTop_Helper_Phone::IsValidPhone_BE($sMobile_original) == true:
				
				// Unidentified
				default:
				
					$aErrors[] = Dict::S('Errors/ContactMethod/InvalidMobilePhoneNumber');
				
			}
			
			// Check email
			// ---
			$sEmail = $oObject->Get('email');
			
			if( $sEmail != '' && !filter_var( $sEmail, FILTER_VALIDATE_EMAIL) ) {
				$aErrors[] = Dict::S('Errors/ContactMethod/InvalidEmail');				
			}
		
			return $aErrors;
			
		}
		
		// No errors		
		return Array();
				
	}	
	
	/**
	 * Invoked to determine whether an object has been modified in memory
	 *
	 *	The GUI calls this verb to determine the message that will be displayed to the end-user.
	 *	Anyhow, this API can be called in other contexts such as the CSV import tool.
	 *	
	 * If the extension returns false, then the framework will perform the usual evaluation.
	 * Otherwise, the answer is definitively "yes, the object has changed".	 	 	 
	 *	 
	 * @param DBObject $oObject The target object
	 * @return boolean True if something has changed for the target object
	 */	
	public function OnIsModified($oObject) {
		return false;
	}
	
	/**
	 * Invoked to determine whether an object can be deleted from the database
	 *	
	 * The GUI calls this verb and stops the deletion process if any issue is reported.
	 * 	 
	 * Please note that it is not possible to cascade deletion by this mean: only stopper issues can be handled. 	 
	 * 
	 * @param DBObject $oObject The target object
	 * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
	 */	
	public function OnCheckToDelete($oObject) {
		return Array();
		
	}

	/**
	 * Invoked when an object is updated into the database
	 *	
	 * The method is called right <b>after</b> the object has been written to the database.
	 * 
	 * @param DBObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information once for all the changes made within the current page
	 * @return void
	 */	
	public function OnDBUpdate($oObject, $oChange = null) {
		$this->OnContactMethodChanged($oObject);
		return;
	}

	/**
	 * Invoked when an object is created into the database
	 *	
	 * The method is called right <b>after</b> the object has been written to the database.
	 * 
	 * @param DBObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information once for all the changes made within the current page
	 * @return void
	 */	
	public function OnDBInsert($oObject, $oChange = null) {
		$this->OnContactMethodChanged($oObject);
		return;
	}

	/**
	 * Invoked when an object is deleted from the database
	 *	
	 * The method is called right <b>before</b> the object will be deleted from the database.
	 * 
	 * @param DBObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information once for all the changes made within the current page
	 * @return void
	 */	
	public function OnDBDelete($oObject, $oChange = null) {
		$this->OnContactMethodDeleted($oObject);
		return;
	}
	
	
	/**
	 * 
	 * Updates related Person object each time a ContactMethod is updated and the other way around.
	 * Triggered on both insert and update.
	 *
	 */
	public function OnContactMethodChanged($oObject) {
			
			
		// If a ContactMethod changed, validate and port back to Person object
		if( $oObject instanceof ContactMethod ) {
			
			$sContactMethod = $oObject->Get('contact_method');
			
			// Improve quality
			
			// Write back to Person
			switch($sContactMethod) {
				
				// These properties are available in the Person class
				case 'phone':
				
					$sPhone_digits = \jb_contact_method\iTop_Helper_Phone::ReturnDigits( $oObject->Get('contact_detail') );
					
					// Belgian number or most likely international
					if( \jb_contact_method\iTop_Helper_Phone::IsValidMobilePhone_BE($sPhone_digits) == true ) {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidPhoneNumber')
						);	
					}
					elseif( \jb_contact_method\iTop_Helper_Phone::IsValidLandLinePhone_BE($sPhone_digits) == true || (\jb_contact_method\iTop_Helper_Phone::OnlyContainsAllowedCharacters($sPhone) == true && strlen($sPhone_digits) > 8) ) {
						// OK, assuming Belgian phone OR international number
						$oObject->Set('contact_detail', $sPhone_digits);
						$oObject->DBUpdate();
					}
					else {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidPhoneNumber')
						);						
						
					}
				
					break;
				
				case 'mobile_phone':
					
					$sMobilePhone_digits = \jb_contact_method\iTop_Helper_Phone::ReturnDigits( $oObject->Get('contact_detail') );
										
					// Belgian number or most likely international
					if( \jb_contact_method\iTop_Helper_Phone::IsValidLandLinePhone_BE($sMobilePhone_digits) == true ) {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidMobilePhoneNumber')
						);			
					}
					elseif( \jb_contact_method\iTop_Helper_Phone::IsValidMobilePhone_BE($sMobilePhone_digits) == true || (\jb_contact_method\iTop_Helper_Phone::OnlyContainsAllowedCharacters($oObject->Get('contact_detail')) == true && strlen(sMobilePhone_digits) > 9) ) {
						// OK, assuming Belgian mobile_phone OR international number
						$oObject->Set('contact_detail', $sMobilePhone_digits);
						$oObject->DBUpdate();
					}
					else {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidMobilePhoneNumber')
						);
						
					}
					
					$oObject->DBUpdate();
				
					break;
				
				// Other properties aer not available in Person class
				default: 
					break;
				
			}
			
			// Might have been changed above (from phone to mobile_phone , from mobile_phone to phone )
			// This should be updated properly in Person object.
			$sContactMethod = $oObject->Get('contact_method');
			
			// Write back to Person
			if( in_array($sContactMethod, Array('phone', 'mobile_phone', 'email')) == true ) {
				
				// Write back to Person. Latest change should be primary.						
				$sOQL = 'SELECT Person WHERE id = '. $oObject->Get('person_id');
				$oSet_Person = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
							
				// Only 1 person will be retrieved (assuming person_id was valid)
				$oPerson = $oSet_Person->Fetch();
				
				// Prevent loop: only if the Person property is not equal to this new detail: update().
				if( $oPerson->Get($sContactMethod) != $oObject->Get('contact_detail') ) {
					$oPerson->Set($oObject->Get('contact_method'), $oObject->Get('contact_detail'));
					$oPerson->DBUpdate();					
				}
				
			}			
		}
		
		// If contact info on the Person object changed, update ContactMethods if necessary
		elseif( $oObject instanceof Person ) {
			
			// Check if a ContactMethod exists for email, phone, mobile_phone. 
			// If not, create.
			$aContactMethods = Array('email', 'phone', 'mobile_phone');
			
			// ValidateInput() will already have checked whether it's a valid email, phone, mobile_phone
			foreach($aContactMethods as $sContactMethod) {
				
				// Write back to Person
			
				// Ignore if empty
				if( $oObject->Get($sContactMethod) != '' ) {
						
					// Select ContactMethod
					// Use LIKE without wildcards to enforce case insensitivity (email)
					$sOQL = 'SELECT ContactMethod WHERE person_id = ' . $oObject->Get('id') .' AND contact_method LIKE "' . $sContactMethod . '" AND contact_detail = "' . $oObject->Get($sContactMethod). '"';
					$oSet_ContactMethods = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
					
					// There shouldn't be a ContactMethod with the same details if a new one is added
					if( $oSet_ContactMethods->Count() == 0 ) {
						
						// Create ContactMethod
						$oContactMethod = new ContactMethod();
						$oContactMethod->Set('person_id', $oObject->Get('id'));
						$oContactMethod->Set('contact_method', $sContactMethod);
						$oContactMethod->Set('contact_detail', $oObject->Get($sContactMethod));
						$oContactMethod->DBInsert();	
						
					}
				
				}
			}			
		}		
	}
	
	/**
	 * 
	 * Updates related Person object each time a ContactMethod is removed.
	 * It checks if it's one of the default contact details (phone, mobile phone, email) and sets the info to blank or the last other known info.
	 *  	 
	 */
	public function OnContactMethodDeleted($oObject) {
		
		// If a ContactMethod is deleted, the related Person object should be updated to reflect these changes 
		
		if( $oObject instanceof ContactMethod ) {
			
			$sContactMethod = $oObject->Get('contact_method');
			
			switch( $sContactMethod ) {
				
				case 'phone':
				case 'mobile_phone':
				case 'email':
					
					// Write back to Person object. Latest change should be primary.
					$sOQL = 'SELECT Person WHERE id = '. $oObject->Get('person_id');
					$oSet_Person = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
			
					// Only 1 person should be retrieved
					$oPerson = $oSet_Person->Fetch();

					// Set to empty
					$oPerson->Set($sContactMethod, '');
					
					// But what if a fallback is possible, to update the Person object with another most recent ContactMethod of the same contact_method type?
					// Since this query is executed before ContactMethod is really deleted: 
					// Don't include this ContactMethod object (which might have been the most recent one)
					$sOQL = 'SELECT ContactMethod WHERE person_id = ' . $oObject->Get('person_id') . ' AND contact_method = "' . $sContactMethod . '" AND id != "' . $oObject->Get('id'). '"';			
					$oSet_ContactMethod = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), /* Order by */ Array('id' => /* Ascending */ false), /* Arguments */ Array(), /* Extended data spec */ null, /* Amount */ 1);
						
					// But maybe there's another last known ContactMethod.
					// Simply look at 'id' and take the last one, not date of last change (yet)
					// @todo look if something can be done with the DBOBjectSet::seek() method
					while($oContactMethod = $oSet_ContactMethod->Fetch()){
						$oPerson->Set($sContactMethod, $oContactMethod->Get('contact_detail'));	
					}
					
					$oPerson->DBUpdate();
					break;
					
				default:
					break;
		
			}
			
		}
		
		// if Person is deleted, iTop should automatically remove all ContactMethods by default
		
		return;
	}
	
}
