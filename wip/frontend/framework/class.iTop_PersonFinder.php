<?php

	/*
	** Experimental. iTop Person Finder. Requires jb-contactmethod (containing class ContactMethod). Adapted to use REST.
	** @version 20190122-1353
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
		 * @param Array $aActions Array. Allowed values: 'create' (create contact if it doesn't exist), 'update' (add ContactMethod)
		 *   
		 * @return Float Person ID or -1 if not found
		 *
		 * @details Tries to match user info with an iTop user, based on contact methods.
		 * - matching email? (likely an individual property)
		 * - matching mobile phone?
		 *   
		 */
		public function FindPerson( $aPerson = [], $aActions = [] ) {
 
			
			// First, determine whether 'phone' is not a 'mobile_phone'			
			if( isset($aPerson['phone']) == true ) {
					
				$sPhone = preg_replace('/\D/', '', $aPerson['phone']);
				
				// In Belgium:
				if( 
					( substr( $sPhone, 0, 1 ) == '0' && strlen($sPhone) == 10 ) || 
					( substr( $sPhone, 0, 2 ) == '32' && strlen($sPhone) == 11 ) || 
					( substr( $sPhone, 0, 4 ) == '0032' && strlen($sPhone) == 13 )
				) {
					
					// Length suggest mobile phone
					$aPerson['mobile_phone'] = $aPerson['phone'];
									 
					// Unset this
					unset($aPerson['phone']); 
					
				}
				
			}
			
		
			// Process in this order. From least to most likely to change. Use IF, not ELSE IF. 
			// - Find by national ID number
			// - Find by mobile phone number
			// - Find by CRAB address
			// - Find by phone number (land line)
			// - Don't use names -> not unique enough
			
			
			// By email
			if( isset($aPerson['email']) == true ) {
				
				// Find
				$sOQL_value = $aPerson['email'];
				$fPersonId = $this->FindPersonByContactMethod($aPerson, 'email', $sOQL_value);
				
				if( $fPersonId > 0 ) {
					
					// Found something, conclusive enough.
					return $fPersonId;
					
				}
				
			}
			
			
			
			// By mobile_phone
			if( isset($aPerson['mobile_phone']) == true ) {
				
				// National = Belgium in our case.
				$sMobilePhone = preg_replace('/\D/', '', $aPerson['mobile_phone']);
								
				if( ( substr($sMobilePhone, 0, 2) == '32' && strlen($sMobilePhone) == 11 ) || ( substr($sMobilePhone, 0, 4) == '0032' && strlen($sMobilePhone) == 13 ) ) {
					
					// Local number, national code in front
					// Only last 9 digits matter (get rid of national number)
					$sOQL_value = '%'.substr($sMobilePhone, -9);
					
				}
				elseif( substr($sMobilePhone, 0, 1) == '0' && strlen($sMobilePhone) == 10 ) {
					
					// Local number, no national code in front
					// Only last 9 digits matter (get rid of local 0)
					// Write back to Person. Latest change should be primary.						
					$sOQL_value = '%'.substr($sMobilePhone, -9);
					
				}
				else {
					
					// International number. Should actually be exactly the same in iTop.
					$sOQL_value = $sMobilePhone;
					
				}
				
				// Find
				$fPersonId = $this->FindPersonByContactMethod($aPerson, 'mobile_phone', $sOQL_value);
				
				if( $fPersonId > 0 ) {
					
					// Found something, conclusive enough.
					return $fPersonId;
					
				}
				
			
			}
			
			// By phone
			if( isset($aPerson['phone']) == true ) {
				
				// National = Belgium in our case.
				$sPhone = preg_replace('/\D/', '', $aPerson['phone']);
				
				if( substr($sPhone, 0, 1) == '0' && strlen($sPhone) == 9 ) {
					
					// Local number, no national code in front
					// Only last 8 digits matter (get rid of local 0)
					// Write back to Person. Latest change should be primary.						
					$sOQL_value = '%'.substr($sPhone, -8);
					
				}
				elseif( ( substr($sPhone, 0, 2) == '32' && strlen($sPhone) == 10 ) || ( substr($sPhone, 0, 4) == '0032' && strlen($sPhone) == 12 ) ) {
					
					// Local number, national code in front
					// Only last 8 digits matter (get rid of national number)
					$sOQL_value = '%'.substr($sPhone, -8);
					
				}
				else {
					
					// International number. Should actually be exactly the same in iTop.
					$sOQL_value = $sPhone;
					
				}
				
				// Find
				$fPersonId = $this->FindPersonByContactMethod($aPerson, 'phone', $sOQL_value);
				
				if( $fPersonId > 0 ) {
					
					// Found something, conclusive enough.
					return $fPersonId;
					
				} 
			
			}
				
			// Not found. Create new person?
			if( in_array('create', $aActions) == true ) {
				
				// Which values should be used in the Person object when it's created?
				$aInsertProperties = ['first_name', 'name', 'phone', 'mobile_phone', 'email', 'org_id'];
				
				$aDataInsert = [];
				
				// See what else can be set
				foreach( $aInsertProperties as $sProperty ) {
					
					if( isset($aPerson[$sProperty]) == true ) {
									
						// Improve name
						if( in_array($sProperty, ['first_name', 'name']) ) {
							$aPerson[$sProperty] = $this->ImproveName($aPerson[$sProperty]);
						}
						
						// Post this to iTop after this foreach
						$aDataInsert[$sProperty] = $aPerson[$sProperty];
						
					}
							
					
				}
								
				// Properties were actually set?
				$aDataReturn = $this->Create([
					'comment' => 'Created by iTop Connector. User reported issue with infrastructure problem.',
					'class' => 'Person',
					'fields' => $aDataInsert,
					'onlyValues' => true
				]);
				
				// Return new person ID
				return $aDataReturn[0]['key'];

				
			}
			
			return -1;
			
		}
		
		
		/**
		 * 
		 * Find iTop user based on contact method
		 * 
		 * @param Object $aPerson Person
		 * @param String $sContactMethod Contact method
		 * @param String $sContactDetailLike Value to search for (can contain wildcard %, will add slashes)
		 * 
		 * @return Float (ID of iTop Person or -1 if not found)
		 * 
		 * @details An OQL query is used to find iTop users with the same contact method/details. 
		 * Next, this finder loops through the results and checks if we can spot any user with a very similar name (also considering first_name and name might have accidentally been switched).
		 *
		 * @uses \iTop_PersonFinder::HasVerySimilarName()
		 */
		private function FindPersonByContactMethod( $aPerson, $sContactMethod, $sContactDetailLike ) {
			
			// Generic OQL query. Like = insensitive; wildcards can be specified in $sContactDetailLike
			$sOQL = 'SELECT ContactMethod WHERE contact_method = \''.$sContactMethod.'\' AND contact_detail LIKE \''.addslashes($sContactDetailLike).'\'';
			$aSet_ContactMethods = $this->Get([
				'key' => $sOQL,
				'onlyValues' => true 
			]);
			
			
			// Did we find something?			
			// Phone might be shared - especially land line.
			// Let's assume this mostly happens with couples, who in most cases (but not always) have a different first and last name.
			foreach( $aSet_ContactMethods as $aContactMethod ) {
					
				$sOQL = 'SELECT Person WHERE id = ' . $aContactMethod['fields']['person_id'];
				$aSet_Persons = $this->Get([
					'key' => $sOQL,
					'onlyValues' => true
				]);
				
				
				// Should give one result; otherwise there's an integrity problem ( ContactMethod with invalid Person id )
				$aPerson_Match = $aSet_Persons[0];
				
				// Did we match a first name, last name AND phone number?
				if( $this->HasVerySimilarName( $aPerson, $aPerson_Match['fields']['first_name'], $aPerson_Match['fields']['name'] ) == true ) {
					
					return $aPerson_Match['key'];
					
				}
				
			}
			
			return -1;
			
		}
		
		/**
		 * 
		 * Checks if an iTop Person object has a similar first name and last name. 
		 * Basically checks if lowercase version of only the alphabetical characters (non accents) is the same as of an iTop User (info provided in $aPerson).
		 * Also checks for reversed names (human error).
		 *
		 * @todo Further improve; simply removing characters with accents won't be enough when comparing to a non-accent version. It can be expected that people write their own name in the same style each time though. Right, Jérôme?
		 *   
		 * @param Array $aPerson Person (iTop)
		 * @param String $sFirstName First name
		 * @param String $sLastName Last name
		 *
		 * @return Boolean
		 *   
		 * @used-by \iTop_PersonFinder::FindPersonByContactMethod()
		 */
		private function HasVerySimilarName( $aPerson, $sFirstName, $sLastName ) {
			  
			
			// Similar first name AND last name?
			// Ignore capitals, spaces, other non-alphabetical characters... -> this means we will also remove special characters (accents etc)
			$sFirstName = strtolower(preg_replace('/[^A-Za-z]+/', '', $sFirstName));
			$sLastName = strtolower(preg_replace('/[^A-Za-z]+/', '', $sLastName));
			
			$sPerson_FirstName = strtolower(preg_replace('/[^A-Za-z]+/', '', $aPerson['first_name']));
			$sPerson_LastName = strtolower(preg_replace('/[^A-Za-z]+/', '', $aPerson['name']));
					
			// People seem to mess up between first and last name...
			// Fallback for human error
			if( $sPerson_LastName === $sLastName && $sPerson_FirstName === $sFirstName ) {				
				return true;
			}
			
			// No fallback if it's not close enough
			if( $sPerson_FirstName === $sLastName && $sPerson_LastName === $sLastName ) {
				return true;
			}			 
			
			// Default
			return false;
			
		}	
		
		
		
		/**
		*
		* Tries to improve the way a name is written (capitals)
		*
		* @param String $sName Name
		*
		* @details Tries to add some capitalization where required.
		*
		* @todo Could be extended with a list of real names
		*
		*/
		
		private function ImproveName( $sName ) {
						
			// All names should start with a capital; except some:
			// de ... x
			// da x
			// dos x
			// van x
			$aPartsName = explode(' ', $sName);
			
			
			// 'T x -> will be ignored
			$aPartsReturned = [];
			
			// Capitalize first letter of each part
			foreach( $aPartsName as $sPartName ) {
				
				
				// leave some values alone if they're small parts of a name and are separate parts
				if( in_array($sPartName, ['de', 'da', 'dos', 'van']) == false ) {
						 
					// Not if first part is d' 
					if( substr($sPartName, 0, 2 ) == 'd\'' ) {
						// Exception
						$aPartsReturned[] = $sPartName;
					}
					else {
						
						$sPart = ucfirst($sPartName);
									
						// Capitals after dash.
						// Use case: first name: Jean-Pierre; last name: <name parent 1>-<name parent 2> etc.
						$sPart = preg_replace_callback('/(\w+)/', create_function('$m','return ucfirst($m[1]);'), $sPart);
						
						$aPartsReturned[] = $sPart;
						
					}
				
				}
				else {
					$aPartsReturned[] = $sPartName;
				}
			}
			
					 
			// Glue back together
			return implode(' ', $aPartsReturned);
		}
		
		
		
	}
	
