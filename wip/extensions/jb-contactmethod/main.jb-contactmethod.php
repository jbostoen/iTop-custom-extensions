<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * PHP Main file
 */

class cApplicationObjectExtension_ContactMethod implements iApplicationObjectExtension {
	 	  
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
				
		// Note: you can not set properties here on the object!
		// Only blocks invalid input
				
		if( $oObject instanceof ContactMethod ) {
			
			$sContactDetail = $oObject->Get('contact_detail');
			
			switch( $oObject->Get('contact_method') ) {
				
				case 'phone':
				
					$sPhone = self::ReturnSignificantDigits_BE( $sContactDetail );
										
					// Belgian number or most likely international
					if( self::IsValidMobilePhone_BE($sPhone) == true ) {
						// OK
				
					}
					elseif( self::IsValidPhone_BE($sPhone) == true || strlen( $sPhone ) > 8 ) {
						// OK
					}
					else {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidPhoneNumber')
						);
						
					}
				
					break;
				
				case 'mobile_phone':
				
					$sMobilePhone = self::ReturnSignificantDigits_BE( $sContactDetail );
					
					// Belgian number or most likely international
					if( self::IsValidPhone_BE($sMobilePhone) == true ) {
						// OK
					}
					elseif( self::IsValidMobilePhone_BE($sMobilePhone) == true || strlen( $sMobilePhone ) > 9 ) {
						// OK
					}
					else {
						
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
		
			$sPhone = self::ReturnDigits( $oObject->Get('phone') );
			$sPhone_SignificantDigits = self::ReturnSignificantDigits_BE( $oObject->Get('phone') );
								
			// Belgian number or most likely international
			if( self::IsValidMobilePhone_BE($sPhone) == true ) {
				$aErrors[] = Dict::S('Errors/Person/InvalidPhoneNumber');
			}
			elseif( self::IsValidPhone_BE($sPhone) == true || strlen( $sPhone_SignificantDigits ) > 8 ) {
				// OK
			}
			elseif( strlen( $sPhone ) == 0 ) {
				// Empty. OK for Person, NOT for ContactMethod
			}
			else {	
				$aErrors[] = Dict::S('Errors/Person/InvalidPhoneNumber');
			}
		
		
			// Check mobile_phone
			$sMobilePhone = self::ReturnDigits( $oObject->Get('mobile_phone') );
			$sMobilePhone_SignificantDigits = self::ReturnSignificantDigits_BE( $oObject->Get('mobile_phone') );
			
			// Belgian number or most likely international
			if( self::IsValidPhone_BE($sMobilePhone) == true ) {
				$aErrors[] = Dict::S('Errors/Person/InvalidMobilePhoneNumber');
			}
			elseif( self::IsValidMobilePhone_BE($sMobilePhone) == true ) {
				// OK: it's a Belgian phone number
			}
			elseif( strlen( $sMobilePhone_SignificantDigits ) > 9 ) {
				// International number?
			}
			elseif( strlen( $sPhone ) == 0 ) {
				// Empty. OK for Person, NOT for ContactMethod
			}
			else {				
				$aErrors[] = Dict::S('Errors/Person/InvalidMobilePhoneNumber');
			}
		
			// Check email
			$sEmail = $oObject->Get('email');
			
			if( !filter_var( $sEmail, FILTER_VALIDATE_EMAIL) ) {
				$aErrors[] = Dict::S('Errors/Person/InvalidEmail');				
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
				
		if( $oObject instanceof ContactMethod ) {
			
			$sContactMethod = $oObject->Get('contact_method');
			
			// Improve quality
			
			// Write back to Person
			switch($sContactMethod) {
				
				// These properties are available in the Person class
				case 'phone':
				
					$sPhone = self::ReturnDigits( $oObject->Get('contact_detail') );
					$sPhone_SignificantDigits = self::ReturnSignificantDigits_BE( $oObject->Get('contact_detail') );
					
					// Belgian number or most likely international
					if( self::IsValidMobilePhone_BE($sPhone) == true ) {
						// Autocorrect
						$oObject->Set('contact_method', 'mobile_phone');
					}
					elseif( self::IsValidPhone_BE($sPhone) == true || strlen( $sPhone_SignificantDigits ) > 8 ) {
						// OK, assuming Belgian phone OR international number
					}
					else {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidPhoneNumber')
						);						
						
					}
					
					$oObject->Set('contact_detail', $sPhone );
					$oObject->DBUpdate();
				
					break;
				
				case 'mobile_phone':
					
					$sMobilePhone = self::ReturnDigits( $oObject->Get('contact_detail') );
					$sMobilePhone_SignificantDigits = self::ReturnSignificantDigits_BE( $oObject->Get('contact_detail') );
										
					// Belgian number or most likely international
					if( self::IsValidPhone_BE($sMobilePhone) == true ) {
						// Autocorrect
						$oObject->Set('contact_method', 'phone');				
					}
					elseif( self::IsValidMobilePhone_BE($sMobilePhone) == true || strlen( $sMobilePhone_SignificantDigits ) > 9 ) {
						// OK, assuming Belgian mobile_phone OR international number
					}
					else {
						
						return Array( 
							Dict::S('Errors/ContactMethod/InvalidPhoneNumber')
						);
						
					}
					
					$oObject->Set('contact_detail', $sMobilePhone );
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
							
				// Only 1 person should be retrieved
				$oPerson = $oSet_Person->Fetch();
				
				// Prevent loop: only if the Person property is not equal to this new detail: update().
				if( $oPerson->Get($sContactMethod) != $oObject->Get('contact_detail') ) {
					$oPerson->Set($oObject->Get('contact_method'), $oObject->Get('contact_detail'));
					$oPerson->DBUpdate();					
				}
				
			}			
		}
		
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
					$sOQL = 'SELECT ContactMethod WHERE person_id = ' . $oObject->Get('id') .' AND contact_method = "' . $sContactMethod . '" AND contact_detail = "' . $oObject->Get($sContactMethod). '"';
					$oSet_ContactMethods = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
					
					// No contact method was found with these details
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
			
					// Same person: previous/alternative ContactMethod available?
					// Since this happens before delete: don't include this object. Might be most recent.
					$sOQL = 'SELECT ContactMethod WHERE person_id = ' . $oObject->Get('person_id') . ' AND contact_method = "' . $sContactMethod . '" AND id != "' . $oObject->Get('id'). '"';			
					$oSet_ContactMethod = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), /* Order by */ Array('id' => /* Ascending */ false), /* Arguments */ Array(), /* Extended data spec */ null, /* Amount */ 1);
		
					// Only 1 person should be retrieved
					$oPerson = $oSet_Person->Fetch();

					// Set to empty
					$oPerson->Set($sContactMethod, '');
						
					// But hey, maybe there's another last known ContactMethod.
					// For this, simply look at 'id', not date of last change (yet)
					// Todo: look if we can do something with the DBOBjectSet::seek() method
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
	
	
	/**
	* Returns whether this is a valid prefix for a Belgian mobile phone number
	*
	* @param String $sPhone Phone number
	*
	* @return Boolean
	*/
	public static function IsMobilePrefix_BE( $sPhone ) {
		
		// https://www.bipt.be/en/consumers/telephone/numbering/numbering-principles
		// 046, 047, 048, 049
		// 04 = land line too, Liège and Voeren. Less digits!
		// That's why we check for the first 2 digits.
		
		// Length is important because:
		// +46 = Sweden
		// +47 = Norway
		// +48 = Poland
		// +49 = Sweden
		
		$sPhone = self::ReturnSignificantDigits_BE($sPhone);
		
		// Amount of digits does not match
		if( strlen($sPhone) != 9 ) {
			return false;
		}
		
		switch( substr($sPhone, 0, 2) ) {
			case '46':
			case '47':
			case '48':
			case '49':
				return true;
				break;
				
			default:
				break;
				
		}
	
		return false;

	}

	/**
	* Returns whether this is a valid Belgian phone number (based on length)
	*
	* @param String $sPhone Phone number
	*
	* @return Boolean
	*/
	public static function IsValidPhone_BE( $sPhone ) {
	
		$sPhone = self::ReturnSignificantDigits_BE($sPhone);
		return ( strlen($sPhone) == 8 );

	}		
	
	/**
	* Returns whether this is a valid Belgian mobile phone number (basically just an alias for now)
	*
	* @param String $sPhone Phone number
	*
	* @return Boolean
	*/
	public static function IsValidMobilePhone_BE( $sPhone ) {
	
		return self::IsMobilePrefix_BE($sPhone);			
				
	}
	
	/**
	* Returns digits only.
	*
	* @param String $sPhone Phone number
	*
	* @return String Digits only of provided string (phone number)
	*/
	public static function ReturnDigits( $sPhone ) {
	
		return preg_replace('/\D/', '', $sPhone);
	
	}
	
	/**
	* Returns significant digits only (meaning: no leading zero and no national number if Belgian phone number)
	*
	* @param String $sPhone Phone number
	*
	* @return String Digits only of provided string (phone number)
	*
	* @details Significant details: the ones which make up the zone number (or mobile prefix), without leading zero and without (Belgian) country code
	*/
	public static function ReturnSignificantDigits_BE( $sPhone ) {
	
		$sPhone = self::ReturnDigits($sPhone);
		
		// Remove leading zero
		$sPhone = ltrim($sPhone, '0');		
		
		// Adapted to Belgian situation
		if( substr($sPhone, 0, 2) == '32' ) {
			
			// 32 47x xx xx xx
			// 32 51 xx xx xx
			// Drop country code
			return substr( $sPhone, 2);
		
		}
		else {
			return $sPhone;
		}
		
	}	
	
}


