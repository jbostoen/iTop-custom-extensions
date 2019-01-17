<?php

	/*
	** Experimental. iTop Person Finder. Requires jb-contactmethod (containing class ContactMethod). Adapted to use REST.
	** @version 20190116-1048
	*/
	
	/**
	 * Class iTop_PersonFinder. Class to find a person based on 
	 */
	class iTop_PersonFinder extends iTop_Rest {
			
		
		/**
		 *  
		 * Tries to find an iTop Person. Expects proper input.
		 * If not found, a Person will be created.
		 * 
		 * @param Array $aPerson Contact data
		 *  [
		 * 
		 *   // Related to Person
		 *   'first_name'         => ...,
		 *   'name'               => ...,
		 * 
		 *   // Related to ContactMethod
		 *   'phone'              => ...,
		 *   'mobile'             => ...,
		 *   'email'              => ...,
		 * 
		 *  
		 *  ]
		 * @param Array $aActions Array. Allowed values: 'create' (create contact if it doesn't exist), update (add ContactMethod)
		 *   
		 * @return Float Person ID or -1 if not found
		 *   
		 */
		public function findPerson( $aPerson = [], $aActions = [] ) {

			// For easy reference
			$oPerson = (Object)$aPerson;
			
			// First, determine whether 'phone' is not a 'mobile_phone'			
			if( property_exists($oPerson, 'phone') ) {
					
				$sPhone = preg_replace('/\D/', '', $oPerson->phone);
				
				// In Belgium:
				if( 
					( substr( $sPhone, 0, 1 ) == '0' && strlen($sPhone) == 10 ) || 
					( substr( $sPhone, 0, 2 ) == '32' && strlen($sPhone) == 11 ) 
				) {
					
					// Length suggest mobile phone
					$oPerson->mobile_phone = $oPerson->phone;
									 
					// Unset this
					unset($oPerson->phone);
					
				}
				
			}
			
		
			// Process in this order. use IF, not ELSE IF. 
			// - Find by national ID number
			// - Find by mobile phone number
			// - Find by phone number (land line)
			// - Don't use names -> not unique enough
			
			
			// By email
			if( property_exists($oPerson, 'email') ) {
				
				// Find
				$sOQL_value = $oPerson->email;
				$fPersonId = $this->findPersonByContactMethod($aPerson, 'email', $sOQL_value);
				
				if( $fPersonId > 0 ) {
					
					// Found something, conclusive enough.
					return $fPersonId;
					
				}
				
			}
			
			
			
			// By mobile_phone
			if( property_exists($oPerson, 'mobile_phone') ) {
				
				// National = Belgium in our case.
				$sMobilePhone = preg_replace('/\D/', '', $oPerson->mobile_phone);
				
				if( substr($sMobilePhone, 0, 1) == '0' && strlen($sMobilePhone) == 10 ) {
					
					// Local number, no national code in front
					// Only last 9 digits matter (get rid of local 0)
					// Write back to Person. Latest change should be primary.						
					$sOQL_value = '%'.substr($sMobilePhone, -9);
					
				}
				elseif( substr($sMobilePhone, 0, 2) == '32' && strlen($sMobilePhone) == 11 ) {
					
					// Local number, national code in front
					// Only last 9 digits matter (get rid of national number)
					$sOQL_value = '%'.substr($sMobilePhone, -9);
					
				}
				else {
					
					// International number. Should actually be exactly the same in iTop.
					$sOQL_value = $sMobilePhone;
					
				}
				
				// Find
				$fPersonId = $this->findPersonByContactMethod($aPerson, 'mobile_phone', $sOQL_value);
				
				if( $fPersonId > 0 ) {
					
					// Found something, conclusive enough.
					return $fPersonId;
					
				}
				
				
			
			}
			
			// By phone
			if( property_exists($oPerson, 'phone') ) {
				
				// National = Belgium in our case.
				$sPhone = preg_replace('/\D/', '', $oPerson->phone);
				
				if( substr($sPhone, 0, 1) == '0' && strlen($sPhone) == 9 ) {
					
					// Local number, no national code in front
					// Only last 8 digits matter (get rid of local 0)
					// Write back to Person. Latest change should be primary.						
					$sOQL_value = '%'.substr($sPhone, -8);
					
				}
				elseif( substr($sPhone, 0, 2) == '32' && strlen($sPhone) == 10 ) {
					
					// Local number, national code in front
					// Only last 8 digits matter (get rid of national number)
					$sOQL_value = '%'.substr($sPhone, -8);
					
				}
				else {
					
					// International number. Should actually be exactly the same in iTop.
					$sOQL_value = $sPhone;
					
				}
				
				// Find
				$fPersonId = $this->findPersonByContactMethod($aPerson, 'phone', $sOQL_value);
				
				if( $fPersonId > 0 ) {
					
					// Found something, conclusive enough.
					return $fPersonId;
					
				} 
			
			}
		
		
			
			
			// Not found. Create contact?
		
		
			return -1;
			
		}
		
		
		/**
		 * 
		 * Uses an OQL
		 * 
		 * @param Object $aPerson Person
		 * @param String $sContactMethod Contact method
		 * @param String $sContactDetailLike Value to search for (can contain wildcard %)
		 * 
		 * @return Float (ID of iTop Person)
		 *  
		 */
		private function findPersonByContactMethod( $aPerson, $sContactMethod, $sContactDetailLike ) {
			
			// Generic OQL query. Like = insensitive; wildcards can be specified in $sContactDetailLike
			$sOQL = 'SELECT ContactMethod WHERE contact_method = \''.$sContactMethod.'\' AND contact_detail LIKE \''.$sContactDetailLike.'\'';
						echo $sOQL;
			$aSet_ContactMethods = $this->get([
				'key' => $sOQL,
				'onlyValues' => true 
			]);
			
			
			// Did we find something?			
			// Phone might be shared - especially land line.
			// Let's assume this mostly happens with couples, who in most cases (but not always) have a different first and last name.
			foreach( $aSet_ContactMethods as $aContactMethod ) {
					
				$sOQL = 'SELECT Person WHERE id = ' . $aContactMethod['fields']['person_id'];
				$aSet_Persons = $this->get([
					'key' => $sOQL,
					'onlyValues' => true
				]);
				
				
				// Should give one result; otherwise there's an integrity problem ( ContactMethod with invalid Person id )
				$aPerson_Match = $aSet_Persons[0];
				
				// Did we match a first name, last name AND phone number?
				if( $this->hasVerySimilarName( $aPerson, $aPerson_Match['fields']['first_name'], $aPerson_Match['fields']['name'] ) == true ) {
					
					return $aPerson_Match['key'];
					
				}
				
			}
			
			return -1;
			
		}
		
		/**
		 * 
		 * Function to check of an iTop Person object has a similar first name and last name. 
		 * Also checks for reversed names (human error).
		 *   
		 * @param Array $aPerson Person (iTop)
		 * @param String $sFirstName First name
		 * @param String $sLastName Last name
		 *
		 * @return Boolean
		 *   
		 */
		private function hasVerySimilarName( $aPerson, $sFirstName, $sLastName ) {
			  
			
			// Similar first name AND last name?
			// Ignore capitals, spaces, other non-alphabetical characters... -> this means we will also remove special characters (accents etc)
			$sFirstName = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $sFirstName));
			$sLastName = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $sLastName));
			
			$sPerson_FirstName = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $aPerson['first_name']));
			$sPerson_LastName = strtolower(preg_replace('/[^A-Za-z0-9]+/', '', $aPerson['name']));
					
			// People seem to mess up between first and last name...
			// Fallback for human error
			if( $sPerson_LastName === $sLastName && $sPerson_FirstName === $sFirstName ) {				
				return true;
			}
			
			// No fallback if it's not close enough
			if( $sPerson_FirstName === $sLastName && $sPerson_LastName === $sLastName ) {
				return true;
			}
			
			echo $sPerson_LastName . ' = ' . $sLastName . ' && ' . $sPerson_FirstName . ' = ' . $sFirstName;
			
			// Default
			return false;
			
		}	
		
		
	}
	
