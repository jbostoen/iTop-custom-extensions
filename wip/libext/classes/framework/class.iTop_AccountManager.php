<?php

	/*
	** Experimental. iTop Account Management. Requires jb-authenticationmethod (containing class AuthenticationMethod). Adapted to use REST.
	** @version 20190122-1353
	*/
	
	/**
	 * Class iTop_AccountManager. Class to find a person based on 
	 */
	class iTop_AccountManager extends iTop_Rest {
		
		
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
			
			$aAcceptedAuthenticationMethods = ['phone', 'mobile_phone', 'twitter_id', 'facebook_id', 'email'];
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
			
			// Find out if these authentication methods aren't used already for someone
			// If they are, the new authentication methods should be linked to this person ONLY if it's a person with a similar name
			
			
			
			
			
			
		}
		
		
		/**
		* Checks if credentials are valid.
		*
		* @var String $sLogin Login (iTop username, email, ...)
		* @var String $sPassword Password (password, token, ID ...)
		* @var String[] $aContactMethods Array of strings with contact methods ('email', ...)
		*
		* @return [
		*  'errorMgs'            => Array of strings, if errors occurred.
		* ]
		*/
		public function HasValidCredentials( $sLogin, $sPassword, $aContactMethods = [] ) {
			
			// In our public frontend, it's unlikely users have an actual iTop user. Put it at the bottom.
			foreach( $aContactMethods as $sContactMethod ) {
					
				if( $this->IsValidAuthenticationMethodWithPassword( $sLogin, $sPassword, $sContactMethod ) == true ) {
					return true;					
				}
				
			}
			
			if( $this->IsValidLocalUser( $sLogin, $sPassword ) == true ) {
				return true;
			}
			
			
			return false;
			
		}
		
		
		/**
		* Checks if authentication succeeds using native iTop user classes
		*
		* @var String $sLogin User Login (iTop username)
		* @var String $sPassword Password
		*
		* @return Boolean
		*/
		public function IsValidLocalUser( $sLogin, $sPassword ) {
			
			// Attempt authentication within native iTop
			$oRest_iTop = new iTop_Rest();
			
			$aReturnedData = $oRest_iTop->Post([
				'operation' => 'core/check_credentials',
				'user' => $sLogin,
				'password' => $sPassword
			]);
			
			// Invalid: code = 0, message = '', authorized = ''
			if( $aReturnedData['authorized'] == 1 ) {
				
				return true;
				
			}
			
			return false;
			
		}
		
		
		
		
		/**
		* Checks if authentication succeeds using a ContactMethod class, which then retrieves the iTop user and checks against that password. 
		*
		* @var String $sLogin User Login (login)
		* @var String $sPassword Password
		* @var String $sContactMethod Contact method
		*
		* @return Boolean
		*/
		public function IsValidAuthenticationMethodWithPassword( $sLogin, $sPassword, $sContactMethod ) {
			
			// Attempt authentication within native iTop
			$oRest_iTop = new iTop_Rest();
		
					
			$sOQL = 'SELECT AuthenticationMethod WHERE authentication_method = "' . $sContactMethod . '" AND authentication_detail LIKE "' . $sLogin. '"';
			$aSet_AuthenticationMethod = $this->Get([
				'key' => $sOQL,
				'onlyValues' => true
			]);
			
			// Hopefully there's (at least) one contact method.
			// Theoretically this can be expanded to phone number (perhaps shared by some people)
			foreach( $aSet_AuthenticationMethod as $aAuthenticationMethod ) {

				$fPersonId = $aAuthenticationMethod['fields']['user_id'];
									
				// Retrieved the person id. Find iTop UserLocal(s) linked to this account.
				// Password is inaccessible.
				$aSet_UserLocal = $this->Get([
					'key' => 'SELECT UserLocal WHERE id = ' . $fPersonId,
					'onlyValues' => true
				]);
				
				// Probably always limited to one UserLocal
				foreach( $aSet_UserLocal as $aUserLocal ) {					
					
					// Valid credentials?
					if( $this->IsValidLocalUser( $aUserLocal['fields']['login'] , $sPassword ) == true ) {
						return true;
					}
					
				}				
				
			}
			
			return false;
			
		}
		
		
		/**
		* To be called AFTER an external authentication. Checks if the specified login is known (Facebook ID, Twitter ID, ...).
		*
		* @var String $sLogin User Login (login)
		* @var String $sContactMethod Contact method
		*
		* @return Boolean
		*/
		public function IsValidAuthenticatedMethodByThirdParty( $sLogin, $sContactMethod ) {
			
			// Attempt authentication within native iTop
			$oRest_iTop = new iTop_Rest();
					
			$sOQL = 'SELECT AuthenticationMethod WHERE authentication_method = "' . $sContactMethod . '" AND authentication_detail LIKE "' . $sLogin. '"';
			$aSet_AuthenticationMethod = $this->Get([
				'key' => $sOQL,
				'onlyValues' => true
			]);
			
			// Hopefully there's (at least) one contact method.
			// Theoretically this can be expanded to phone number (perhaps shared by some people)
			foreach( $aSet_AuthenticationMethod as $aAuthenticationMethod ) {

				$fPersonId = $aAuthenticationMethod['fields']['user_id'];
				
				// Just check if the user still exists.
				$aSet_UserLocal = $this->Get([
					'key' => 'SELECT UserLocal WHERE contactid = ' . $fPersonId,
					'onlyValues' => true
				]);
				
				// Probably always limited to one UserLocal
				foreach( $aSet_UserLocal as $aUserLocal ) {
					
					return true;
					
				}
				
				
			}
			
			return false;
			
		}
			
		
		
	}
	
