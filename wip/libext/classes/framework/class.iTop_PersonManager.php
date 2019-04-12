<?php

	/*
	** Experimental. iTop Account Management. Requires jb-contactmethod (containing class ContactMethod). Adapted to use REST.
	** @version 20190122-1353
	*/
	
	/**
	 * Class iTop_PersonManager. Class to find a person based on 
	 */
	class iTop_PersonManager extends iTop_Rest {
		
		
		/**
		* @param Array $aPerson Contains user info: first_name, name, phone, mobile_phone, mail, ...
		*
		* @details Creates an iTop User Account.
		*
		* @return Array [
		*  'errorMsgs' =>           List of error messages. Empty if no error
		*  'errorFields' =>         List of field names.
		*
		* ]
		*
		* @todo Finish this!
		*
		*
		**/
		public function CreateAccount( $aPerson = [] ) {
			
			if( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
			
			$aAcceptedContactMethods = ['phone', 'mobile_phone', 'email'];
			$aAcceptedFields = array_merge(['first_name', 'name', 'password', 'org_id'], $aAcceptedContactMethods);
			
			// Are all properties accepted keys?
			$aErrorFields = [];
			$aErrorMsgs = [];
			
			foreach( array_keys($aPerson) as $sKey ) {
				if( in_array($sKey, $aAcceptedFields) == false ) {
					$aErrorFields[] = $sKey;
					$aErrorMsgs[] = 'Ongeldig attribuut gevonden: "'.$sKey.'"';
				}			
			}
			
			if( count($aErrorMsgs) > 0 ) {

				// Invalid keys detected.
				return [
					'errorMsgs' => $aErrorMsgs,
					'errorFields' => $aErrorFields
				];
				
			}
			
			// Required keys present AND valid?
			if( isset($aPerson['email']) == false ) {
				
				echo json_encode([
					'errorMsgs' => ['Er moet minstens een e-mailadres gekoppeld worden aan een account.'],
					'errorFields' => ['email']
				]);
				exit();
				
			}
			
			
			// Find out if  there's already a user with a very similar name and ContactMethod
			// Don't force function getPersonId() to create a Person object in iTop of none exists
			$fPersonId = $this->GetPersonId( $aPerson);
			
			if( $fPersonId > 0 ) {
				
				if( isset($aPerson['email']) == true ) {
					
					// @todo Implement password reset
					
					return [
						'errorMsgs' => [
							'Dit e-mailadres is al gekoppeld aan een account. <br>'.
							'Daarom hebben we je een loginlink gestuurd. <br>'.
							'Die vind je over enkele minuten in je mailbox<br>(kijk ook bij "Ongewenste mail"/SPAM).'
						]
					];
					
				}
				else {
					
					return [
						'errorMsgs' => ['Er bestaat al een account met gelijkaardige details.']
					];
					
				}
				
			}
			
			// Try again but create user.
			// Now force function getPersonId() to create a Person object in iTop of none exists
			$fPersonId = $this->GetPersonId( $aPerson, ['create']);
								
					
			$oItop_Rest = new iTop_Rest();
						
			// Create iTop UserLocal
			// The account name is rather random, it's not really used for anything
			$aData_UserLocal = $oItop_Rest->Create([
				'class' => 'UserLocal', // iTop User (account)
				'comment' => 'Created by '.get_class($this),
				'fields' => [
				
					// Don't store data twice. first_name, last_name should be stored in 'Person' class.
					'contactid' => $fPersonId,
					
					'status' => 'enabled',
					'allowed_org_list' => [],
					'profile_list' => [
						[
							'profileid' => [ 
								// 'finalclass' => 'URP_Profiles',
								'name' => 'Portal user'
							]
						]
					],
					
					'login' => 'citizen_'.date('YmdHis').'_'.session_id()
				
				],
				'onlyValues' => true
			]);
			
						
			return $aData_UserLocal;			
			
			
		}
		
		
		/**
		 *  
		 * Tries to find an iTop Person, based on a combination of name and contact method(s). Expects proper input.
		 * If not found, this method can create a Person.
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
		 *  ]
		 *
		 * @param Array $aActions Array. Allowed values: 'create' (create contact if it doesn't exist), 'update' (add ContactMethod)
		 *   
		 * @return Float Person ID or -1 if not found
		 *
		 * @details Tries to match user info with an iTop user, based on contact methods. Currently in this order:
		 *
		 * - national number
		 * - e-mail (likely an individual property)
		 * - mobile phone
		 * - phone
		 *
		 * @uses \iTop_AccountManager::GetPersonIdByContactMethod()
		 *   
		 */
		public function GetPersonId( $aPerson = [], $aActions = [] ) {
 		
			// Process in this order. From least to most likely to change. 
			// Unique: should be 100% unique
			// Mostly unique: people share devices, accounts. They probably aren't bothered using the contact info/name of their partner.
			// Often shared: email accounts (partners), land line phones, ...
			
			$aContactMethods = [
				'national_number',		// Unique, one per user
				'email',				// Often shared, often multiple per user
				'mobile_phone',			// Mostly unique, usually one per user
				'phone'					// Often shared, one per user
			];
			
			
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
			
			// By mobile_phone (chance of being shared: small)
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
					$aPerson['mobile_phone'] = '%'.substr($sMobilePhone, -9);
					
				}
				else {
					
					// International number. Should actually be exactly the same in iTop.
					$aPerson['mobile_phone'] = $sMobilePhone;
					
				}
			
			}
			
			// By phone (chance of being shared: big)
			if( isset($aPerson['phone']) == true ) {
				
				// National = Belgium in our case.
				$sPhone = preg_replace('/\D/', '', $aPerson['phone']);
				
				if( substr($sPhone, 0, 1) == '0' && strlen($sPhone) == 9 ) {
					
					// Local number, no national code in front
					// Only last 8 digits matter (get rid of local 0)
					// Write back to Person. Latest change should be primary.						
					$aPerson['phone'] = '%'.substr($sPhone, -8);
					
				}
				elseif( ( substr($sPhone, 0, 2) == '32' && strlen($sPhone) == 10 ) || ( substr($sPhone, 0, 4) == '0032' && strlen($sPhone) == 12 ) ) {
					
					// Local number, national code in front
					// Only last 8 digits matter (get rid of national number)
					$aPerson['phone'] = '%'.substr($sPhone, -8);
					
				}
				else {
					
					// International number. Should actually be exactly the same in iTop.
					$aPerson['phone'] = $sPhone;
					
				}
				
			
			}
				
			// Try to find this person
			foreach( $aContactMethods as $sContactMethod ) {
					
				if( isset($aPerson[$sContactMethod]) == true ) {
					
					// Find
					$fPersonId = $this->GetPersonIdByContactMethod($aPerson, $sContactMethod, $aPerson[$sContactMethod]);
					
					if( $fPersonId > 0 ) {
						
						// Found something, conclusive enough.
						return $fPersonId;
						
					}
					
				}
			
			}
				
				
			// Not found or not conclusive. Create new user?
			if( in_array('create', $aActions) == true ) {
				
				// Which values should be used in the Person object when it's created?
				$aPropertiesPerson = ['first_name', 'name', 'org_id', 'email', 'phone', 'mobile_phone'];
				
				$aDataInsert = [];
				
				// See what else can be set
				foreach( $aPropertiesPerson as $sProperty ) {
					
					if( isset($aPerson[$sProperty]) == true ) {
									
						// Improve name
						if( in_array($sProperty, ['first_name', 'name']) ) {
							$aPerson[$sProperty] = $this->ImproveNameCapitalization($aPerson[$sProperty]);
						}
						
						// Post this to iTop after this foreach
						$aDataInsert[$sProperty] = $aPerson[$sProperty];
												
					}
					
				}
							
				$aDataInsert['status'] = 'active';
								
				// Properties were actually set?
				$aDataReturned = $this->Create([
					'comment' => 'Created by '.get_class($this),
					'class' => 'Person',
					'fields' => $aDataInsert,
					'onlyValues' => true
				]);
				
				if( empty($aDataReturned["code"]) == false ) {
							
					print_r( $aDataReturned );
					die();
						

				}
				else {
					
					// Return new person ID
					return $aDataReturned[0]['key'];
				}
				
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
		 * @uses \iTop_AccountManager::HasVerySimilarName()
		 */
		private function GetPersonIdByContactMethod( $aPerson, $sContactMethod, $sContactDetailLike ) {
			
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
		 * @param Array $aPerson Person (retrieved from iTop)
		 * @param String $sFirstName First name
		 * @param String $sLastName Last name
		 *
		 * @return Boolean
		 *   
		 * @used-by \iTop_AccountManager::GetPersonIdByContactMethod()
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
		
		private function ImproveNameCapitalization( $sName ) {
						
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
	
