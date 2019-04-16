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
		* @var String $name Name which is used by default in REST comments
		*/
		public $name = 'iTop REST - Account Manager';
		
		
		/**
		* @var Integer $orgId Default organization for new accounts/persons
		*/
		public $orgId = 1;
		
		/**
		* Creates an iTop UserLocal
		*
		* @param Array $aUser Contains user info: first_name, name, phone, mobile_phone, mail, ...
		*
		*
		* @return Array [
		*  'errorMsgs' =>           List of error messages. Empty if no error
		*  'errorFields' =>         List of field names.
		*
		* ]
		*
		* @uses \iTop_PersonManager::CreatePerson();
		*
		* @details First a Person is created, with at least a ContactMethod ('email'). 
		* Next, a UserLocal is created ( citizen_timestamp_sessionid ). 
		* All specified authentication methods will be added for this person. 
		* Last but not least, the token is set.
		*
		*/
		public function CreateAccount( $aUser = [] ) {
			
			if( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
			
			$aAcceptedAuthenticationMethods = ['phone', 'mobile_phone', 'twitter_id', 'facebook_id', 'email'];
			$aAcceptedPersonInfo = ['first_name', 'name', 'password', 'org_id', 'email'];
			$aAcceptedFields = array_merge(['password', 'agree_gdpr'], $aAcceptedPersonInfo, $aAcceptedAuthenticationMethods);
			
			// Override default organization
			$aUser['org_id'] = $this->orgId;
			
			// Are all properties accepted keys?
			$aErrorFields = [];
			$aErrorMsgs = [];
			
			foreach( array_keys($aUser) as $sKey ) {
				if( in_array($sKey, $aAcceptedFields) == false ) {
					$aErrorFields[] = $sKey;
					$aErrorMsgs[] = 'Ongeldig attribuut gevonden: "'.$sKey.'"';
				}			
			}
			
			
			// Required keys present AND valid?
			if( isset($aUser['email']) == false ) {				
				$aErrorMsgs[] = 'Er moet minstens een e-mailadres gekoppeld worden aan een account.';
				$aErrorFields[] = 'email';
			}
			elseif( filter_var($aUser['email'], FILTER_VALIDATE_EMAIL ) == false ) {				
				$aErrorMsgs[] = 'Geef een geldig e-mailadres op.';
				$aErrorFields[] = 'email';
			}
			
			if( isset($aUser['password']) == false ) {
				$aErrorMsgs[] = 'Er moet een wachtwoord opgegeven worden.';
				$aErrorFields[] = 'password';				
			}
			elseif( strlen($aUser['password']) < 8 ) {
				$aErrorMsgs[] = 'Het wachtwoord moet minstens 8 tekens bevatten.';
				$aErrorFields[] = 'password';
			}
			
			if( isset($aUser['agree_gdpr']) == false ) {
				$aErrorMsgs[] = 'Er moet een wachtwoord opgegeven worden.';
				$aErrorFields[] = 'agree_gdpr';				
			}
			elseif( $aUser['agree_gdpr'] != '1' ) {
				$aErrorMsgs[] = '';
				$aErrorFields[] = 'agree_gdpr';	
			}
			
			unset($aUser['agree_gdpr']);
			
			if( count($aErrorMsgs) > 0 ) {

				return [
					'errorMsgs' => $aErrorMsgs,
					'errorFields' => $aErrorFields
				];
				
			}
			
			// Find out if these authentication methods aren't used already for someone
			// If they are, the new authentication methods should be linked to this person ONLY if it's a person with a similar name
			foreach( $aAcceptedAuthenticationMethods as $sAuthenticationMethod ) {
				
				if( isset($aUser[$sAuthenticationMethod]) == true ) {
					
					// This contact method should not exist already for any user!
					$aSet_AuthenticationMethods = $this->Get([
						'key' => 'SELECT AuthenticationMethod WHERE authentication_method = "' . $sAuthenticationMethod . '" AND authentication_detail = "' . $aUser[$sAuthenticationMethod] . '"',
						'no_keys' => true
					]);
					
					if( count($aSet_AuthenticationMethods) > 0 ) {
						$aErrorMsgs[] = iTop_FrameWork::S('Class:AuthenticationMethod/Attribute:authentication_method/Value:' . $sAuthenticationMethod ) . ' is al gelinkt aan een account.';
					}
					
				}
				
			}
						
			if( count($aErrorMsgs) > 0 ) {
				
				echo json_encode([
					'errorMsgs' => $aErrorMsgs,
					'errorFields' => $aErrorFields
				]);
				exit();
				
			}
			
			// Info hasn't been used yet.			
			$oPersonManager = new iTop_PersonManager();
			
			// Try to create person
			$aPersonInfo = [];
			
			// Do not pass everything to 'Person'. ContactMethods are very similar to AuthenticationMethods, 
			// but the user hasn't really approved sharing of these details with our organization!
			foreach( $aAcceptedPersonInfo as $sPersonInfo ) {
				if( isset( $aUser[$sPersonInfo] ) == true ) {
					$aPersonInfo[$sPersonInfo] = $aUser[$sPersonInfo];
				}
			}
			
			// Try to create the user
			$aResult_CreatePerson = $oPersonManager->CreatePerson( $aPersonInfo );
			
			if( isset($aResult_CreatePerson['id']) == false ) {
				return $aResult_CreatePerson;
			}
			
						
			// Create iTop UserLocal
			// The account name is rather random, it's not really used for anything
			$aData_UserLocal = $this->Create([
				'class' => 'UserLocal', // iTop User (account)
				'fields' => [
				
					// Don't store data twice. first_name, last_name should be stored in 'Person' class.
					'contactid' => $aResult_CreatePerson['id'],
					
					'status' => 'enabled',
					'allowed_org_list' => [
						[
							'allowed_org_id' => $this->orgId,
							'reason' => 'Default org'
						]
					],
					'profile_list' => [
						[
							'profileid' => [ 
								// 'finalclass' => 'URP_Profiles',
								'name' => 'Portal user'
							]
						]
					],
					
					// 'burger'
					'login' => 'citizen_'.date('YmdHis').'_'.session_id(),
					'password' => $aUser['password']
				
				],
				'no_keys' => true
			]);
			
			$aUserLocal = current($aData_UserLocal);
			
			// Add each AuthenticationMethod
			foreach( $aAcceptedAuthenticationMethods as $sAuthenticationMethod ) {
				if( isset($aUser[$sAuthenticationMethod]) == true ) {
					$aResult_CreateAuthenticationMethod = $this->CreateAuthenticationMethod([
						'user_id' => $aUserLocal['key'],
						'authentication_method' => $sAuthenticationMethod,
						'authentication_detail' => $aUser[$sAuthenticationMethod]
					]);
				}
			}
			
			// Unable to use \iTop_AccountManager::DoLogin(): we don't know what 'login' will be.
			$this->CreateToken([
				'user_id' => $aUserLocal['key'],
				'set_cookie' => false // Don't create cookie first time. Rely on session.
			]);
			
			
			
			return [
				'login' => $aUserLocal['fields']['login'] 
			];
			
		}
		
		
		
		/**
		* Handles the generation of tokens. Sets $_SESSION and $_COOKIE (if permitted)
		*
		* @var Array $aOptions Array of optional options
		* [
		*  'authentication_detail'            => Required. Authentication detail
		*  'authentication_method'            => Required. Authentication method
		*  'user_id'                          => Required. User ID
		*  
		* ]
		*
		* @return [
		*                                        Failure
		*  'errorMgs'                         => Array of strings, if errors occurred.
		*  'errorOrigin'                      => Method name
		*
		*
		*                                        Success
		* ]
		*
		* @used-by \iTop_AccountManager::CreateAccount()
		*
		*/
		public function CreateAuthenticationMethod( $aOptions = [] ) {
			
			$aErrorMsgs = [];
			
			// Check properties
			foreach(['user_id', 'authentication_detail', 'authentication_method'] as $sProperty) {
				if( isset($aOptions[$sProperty]) == false ) {					
					return [
						'errorMsgs' => ['Geen "' . $sProperty . '" opgegeven.']
					];					
				}
			}
			
			if( count($aErrorMsgs) > 0 ) {
				return [
					'errorMsgs' => $aErrorMsgs,
					'errorOrigin' => __METHOD__
				];
			}
			
			// Try to add authentication method
			$aResult_CreateAuthenticationMethod = $this->Create([
				'class' => 'AuthenticationMethod',
				'fields' => [
					'user_id' => $aOptions['user_id'],
					'authentication_method' => $aOptions['authentication_method'],
					'authentication_detail' => $aOptions['authentication_detail']
				]
			]);	
			
			return [];
			
		}
		
		
		
		
		/**
		* Handles the generation of tokens. Sets $_SESSION and $_COOKIE (if permitted)
		*
		* @var Array $aOptions Array of optional options
		* [
		*  'user_id'                          => Required. User ID
		*  'set_cookie'                       => Optional. Boolean. Defaults to false
		*  
		* ]
		*
		* @return [
		*                                        Failure
		*  'errorMgs'                         => Array of strings, if errors occurred.
		*
		*                                        Success
		*  'token'                            => Selector and token (see $_SESSION, $_COOKIE)
		*
		* ]
		*
		* @used-by \iTop_AccountManager::CreateAccount()
		* @used-by \iTop_AccountManager::DoLogin()
		*
		*/
		public function CreateToken( $aOptions = [] ) {
					
			// Authentication succeeded. Create session token
			// Based on https://stackoverflow.com/questions/3128985/php-login-system-remember-me-persistent-cookie
			$sSelector = base64_encode(random_bytes(9));
			$sAuthenticator = random_bytes(33);
						
			// Save this information
			$aReturnData = $this->Create([
				'class' => 'AuthenticationMethod',
				'fields' => [
					'user_id' => $aOptions['user_id'],
					'authentication_method' => 'token',
					'authentication_detail' => $sSelector.':'.hash('sha256', $sAuthenticator),
					'first_used' => date('Y-m-d H:i:s'),
					'last_used' => date('Y-m-d H:i:s')
				]
			]);
			
			$sCompleteToken = $sSelector . ':' . base64_encode($sAuthenticator);
			
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}

			$_SESSION[ iTop_FrameWork::prefix . '_token'] = $sCompleteToken;
			$_SESSION[ iTop_FrameWork::prefix . '_user_id'] = $aOptions['user_id'];
			
			if( isset($aOptions['set_cookie']) == true ) {
				if( @$aOptions['set_cookie'] == true ) {
					
					setCookie( iTop_FrameWork::prefix . '_token', $sCompleteToken, time() + (30 * 24 * 3600), '/' );
					
				}	
			}	
			
			return [
				'token' => $sCompleteToken
			];
			
		}
		
		
		/**
		* Handles the login of the user: checks if the user has valid credentials and creates a session token.
		*
		* @var Array $aOptions Array of optional options
		* [
		*  'authentication_methods'           => Required. Array of strings. Order determines precedence!
		*  'login'                            => Required. Unique identifier: iTop username, email, ...
		*  'password'                         => Required. Password, token, ...
		*  'set_cookie'                       => Required. Boolean. Defaults to false
		*  
		* ]
		*
		*
		* @return [
		*                                        Failure
		*  'errorMgs'                         => Array of strings, if errors occurred.
		* ]
		*
		* @uses \iTop_AccountManager::createToken();
		*/
		public function DoLogin( $aOptions = [] ) {
			
			$aHasValidCredentials = $this->HasValidCredentials( $aOptions );
			
			// Errors occurred? No further processing
			if( isset( $aHasValidCredentials['errorMsgs'] ) == true ) {
				
				return $aHasValidCredentials;
			}
			else {
					
				// Token generator needs user ID
				$aOptions_Token = [
					'user_id' => $aHasValidCredentials['id'],
					'set_cookie' => $aOptions['set_cookie']
				];
							
				// No errors occurred, proceed.
				$this->createToken( $aOptions_Token );
			
			}
			return [
				'login' => $aHasValidCredentials['login']
			];
			
		}
		
		
		/**
		* Checks if a valid token has been set ( $_SESSION and fallback to $_COOKIE ), creates new token too.
		*
		* @return [
		*                                        Failure
		*  'errorMgs'                         => Array of strings, if errors occurred.
		* ]
		*
		* @uses \iTop_AccountManager::DoLogin();
		*
		*/
		public function DoLoginByToken( ) {
			
			// Checks if $_SESSION has a valid token. 
			// If not, there's a fallback to $_COOKIE
			
			if( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
			
			// Session is most recent.
			if( isset($_SESSION[ iTop_FrameWork::prefix . '_token']) == true ) {
				
				$aDoLogin = $this->DoLogin([
					'authentication_methods' => ['token'],
					'login' => $_SESSION[ iTop_FrameWork::prefix . '_token'],
					'set_cookie' => isset( $_COOKIE[ iTop_FrameWork::prefix . '_token'] )
				]);
				
				// Success
				if( isset( $aDoLogin['errorMsgs'] ) == false ) {
					return $aDoLogin;
				}
				
			}
			
			// Cookie lasts longer.
			if( isset($_COOKIE[ iTop_FrameWork::prefix . '_token']) == true ) {
				
				$aDoLogin = $this->DoLogin([
					'authentication_methods' => ['token'],
					'login' => $_COOKIE[ iTop_FrameWork::prefix . '_token'],
					'set_cookie' => isset( $_COOKIE[ iTop_FrameWork::prefix . '_token'] )
				]);
				
				// Success
				if( isset( $aDoLogin['errorMsgs'] ) == false ) {
					return $aDoLogin;
				}
				
			}
			
			
			return [
				'errorMsgs' => [
					'Authenticatie aan de hand van token is mislukt.'
				]
			];
			
		}
		
		
		/**
		* Logs the user out (ends $_SESSION, ends $_COOKIE)
		*
		* @return Empty array
		*/
		public function DoLogout( $aOptions = [] ) {
			
			setCookie( iTop_FrameWork::prefix . '_token', '', time() - 3600, '/' );	

			if( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
			
			session_destroy();
					
			return [];
		}
		
		
		
		
		/**
		* Checks if credentials are valid.
		*
		* @var Array $aOptions Array of optional options
		* [
		*  'authentication_methods'           => Required. Array of strings. Order determines precedence!
		*  'login'                            => Required. Unique identifier: iTop username, email, ...
		*  'password'                         => Required. Password, token, ...
		*  
		* ]
		*
		* @return Array
		* [
		*                                        Failure:
		*  'errorMsgs'                        => Array of strings.
		*
		*                                        Success:
		*  'id'                               => Integer. iTop User ID.
		*  'login'                            => String. Login name.
		*
		* ]
		*
		* @details Make sure front-end users can't simply use a Facebook ID to authenticate.
		* This means the callback URL needs to remain a secret.
		*
		* @uses \iTop_AccountManager::IsValidLocalUser()
		* @uses \iTop_AccountManager::IsValidAuthenticationMethodByThirdParty()
		* @uses \iTop_AccountManager::IsValidAuthenticationMethodByToken()
		* @uses \iTop_AccountManager::IsValidAuthenticationMethodWithPassword()
		*/
		public function HasValidCredentials( $aOptions = [] ) {
						
			// In our public frontend, it's unlikely users have an actual iTop user. Put it at the bottom.
			if( isset($aOptions['authentication_methods']) == true && isset($aOptions['login']) == true ) {
				
				$sLogin = $aOptions['login'];
					
				foreach( $aOptions['authentication_methods'] as $sAuthenticationMethod ) {
					
					switch( $sAuthenticationMethod ) {
						
						case 'email':
					
							// The following methods are deliberately ordered and may offer fallbacks!										
							// Authenticated by combination of email address and password
							if( isset($aOptions['password']) == true ) {
														
								$sPassword = $aOptions['password'];
						
								$aIsValidAuthenticationMethodWithPassword = $this->IsValidAuthenticationMethodWithPassword( $sLogin, $sPassword, $sAuthenticationMethod );
							
								if( isset($aIsValidAuthenticationMethodWithPassword['id']) == true ) {
									return $aIsValidAuthenticationMethodWithPassword;
								}
							}
							
							break;
							
						case 'facebook_id':
						case 'twitter_id':
						
								
							// Authenticated by third party (Facebook, Twitter, ...)
							$aIsValidAuthenticationMethodByThirdParty = $this->IsValidAuthenticationMethodByThirdParty( $sLogin, $sAuthenticationMethod );
							
							if( isset($aIsValidAuthenticationMethodByThirdParty['id']) == true ) {
								return $aIsValidAuthenticationMethodByThirdParty;
							}
							
							break;
							
							
						case 'token': 
					
									
							// Authenticated by token (in $_SESSION or $_COOKIE)
							$aIsValidAuthenticationMethodByToken = $this->IsValidAuthenticationMethodByToken( $sLogin );
							
							if( isset($aIsValidAuthenticationMethodByToken['id']) == true ) {
								return $aIsValidAuthenticationMethodByToken;
							}
							
							break;
							
						case 'password':
						case 'native': 
						
							// Authenticated by iTop authentication (iTop user)
							$aIsValidAuthenticationMethodLocalUser = $this->IsValidAuthenticationMethodLocalUser( $sLogin, $sPassword );
							
							if( isset($aIsValidAuthenticationMethodLocalUser['id']) == true ) {
								return $aIsValidAuthenticationMethodLocalUser;
							}
							
						default:
						
							break;
						
					}
					
				}
				
			}
			else {
				
				return [
					'errorMsgs' => [
						'Aanmelden lukt niet. Er is geen login opgegeven of authenticatiemethodes ontbreken.'
					],
					'options' => $aOptions
				];
				
			}

			return [
				'errorMsgs' => [
					'Aanmelden lukt niet. Je logingegevens zijn niet (meer) gekend bij ons.'
				]
			];
			
		}
		
		
		/**
		* Checks if authentication succeeds using native iTop user classes
		*
		* @var String $sLogin User Login (iTop username)
		* @var String $sPassword Password
		*
		* @return Array true/false
		*
		* @used-by \iTop_AccountManager::IsValidAuthenticationMethodLocalUser()
		* @used-by \iTop_AccountManager::IsValidAuthenticationMethodWithPassword()
		*
		*/
		public function IsValidLocalUser( $sLogin, $sPassword ) {
			
			// Attempt authentication within native iTop
			
			$aReturnedData = $this->Post([
				'operation' => 'core/check_credentials',
				'user' => $sLogin,
				'password' => $sPassword
			]);
			
			// Invalid: code = 0, message = '', authorized = ''
			return ( $aReturnedData['authorized'] == 1 );
			
		}
		
		
		
		
		
		/**
		* Checks if authentication succeeds using a AuthenticationMethod class, which then retrieves the iTop user and checks against that password. 
		*
		* @var String $sLogin User Login (login)
		* @var String $sPassword Password
		* @var String $sAuthenticationMethod Authentication method
		*
		* @return Array
		* [
		*                                        Failure:
		*  'errorMsgs'                        => Array of strings.
		*
		*                                        Success:
		*  'id'                               => Integer. iTop User ID.
		*  'login'                            => String. Login name.
		*
		* ]
		*
		* @used-by \iTop_AccountManager::HasValidCredentials()
		*
		*/
		private function IsValidAuthenticationMethodWithPassword( $sLogin, $sPassword, $sAuthenticationMethod ) {
			
			// Attempt authentication within native iTop
		
			// We are assuming authentication_method and authentication_detail form something unique
			// LIKE for emails
			$sOQL = 'SELECT AuthenticationMethod WHERE authentication_method = "' . $sAuthenticationMethod . '" AND authentication_detail LIKE "' . $sLogin. '"';
			$aSet_AuthenticationMethod = $this->Get([
				'key' => $sOQL,
				'no_keys' => true
			]);
			
			// Hopefully there's (at least) one authentication method.
			// Theoretically this can be expanded to phone number (perhaps shared by some people)
			foreach( $aSet_AuthenticationMethod as $aAuthenticationMethod ) {

				$fContactId = $aAuthenticationMethod['fields']['user_id'];
				
				// Retrieved the person id. Find iTop UserLocal(s) linked to this account.
				// Password is inaccessible.
				$aSet_UserLocal = $this->Get([
					'key' => 'SELECT UserLocal WHERE id = ' . $fContactId,
					'no_keys' => true
				]);
				
				// Always limited to zero or one UserLocal
				foreach( $aSet_UserLocal as $aUserLocal ) {					
					
					$sLogin = $aUserLocal['fields']['login'];
					$sKey = $aUserLocal['key'];				
					
					// Valid credentials?															
					if( $this->IsValidLocalUser( $sLogin, $sPassword ) == true ) {
						
						
						return [
							'login' => $sLogin,
							'id' => $sKey
						];
					}
					
				}
				
			}
			
			return [
				'errorMsgs' => [
					'Authenticatie van een iTop user is mislukt.'
				]
			];
			
		}
		
		
		/**
		* To be called AFTER an external authentication. Checks if the specified login is known (Facebook ID, Twitter ID, ...). 
		* Warning: make sure this is only called if the login comes from a safe source!
		*
		* @var String $sLogin User Login (login)
		* @var String $sAuthenticationMethod Authentication method
		*
		* @return Array
		* [
		*                                        Failure:
		*  'errorMsgs'                        => Array of strings.
		*
		*                                        Success:
		*  'id'                               => Integer. iTop User ID.
		*  'login'                            => String. Login name.
		*
		* ]
		*
		* @used-by \iTop_AccountManager::HasValidCredentials()
		*
		*/
		private function IsValidAuthenticationMethodByThirdParty( $sLogin, $sAuthenticationMethod ) {
			
			// Attempt authentication within native iTop
					
			$sOQL = 'SELECT AuthenticationMethod WHERE authentication_method = "' . $sAuthenticationMethod . '" AND authentication_detail LIKE "' . $sLogin. '"';
			$aSet_AuthenticationMethod = $this->Get([
				'key' => $sOQL,
				'no_keys' => true
			]);
			
			// Hopefully there's (at least) one AuthenticationMethod.
			// Theoretically this can be expanded to phone number (perhaps shared by some people)
			foreach( $aSet_AuthenticationMethod as $aAuthenticationMethod ) {

				$fContactId = $aAuthenticationMethod['fields']['user_id'];
				
				// Just check if the user still exists.
				$aSet_UserLocal = $this->Get([
					'key' => 'SELECT UserLocal WHERE contactid = ' . $fContactId,
					'no_keys' => true
				]);
				
				// Always limited to zero or one UserLocal
				foreach( $aSet_UserLocal as $aUserLocal ) {
					
					$sLogin = $aUserLocal['fields']['login'];
					$sKey = $aUserLocal['key'];
				
					return [
						'login' => $sLogin,
						'id' => $sKey
					];
					
				}
				
				
			}
			
			return [
				'errorMsgs' => [
					'Authenticatie van een iTop user is mislukt.'
				]
			];
			
			
		}
			
		
		/**
		* Verifies a token.
		*
		* @var String $sToken Token
		*
		* @return Array
		* [
		*                                        Failure:
		*  'errorMsgs'                        => Array of strings.
		*
		*                                        Success:
		*  'id'                               => Integer. iTop User ID.
		*  'login'                            => String. Login name.
		*
		* ]
		*
		* @used-by \iTop_AccountManager::HasValidCredentials()
		*
		*/
		private function IsValidAuthenticationMethodByToken( $sToken ) {
			
			// This should be handled a little differently.
			list( $sSelector, $sAuthenticator ) = explode(':', $sToken);
			
			// Attempt authentication within native iTop
					
			$sOQL = 'SELECT AuthenticationMethod WHERE authentication_method = "token" AND authentication_detail = "' . $sSelector . ':' . hash('sha256', base64_decode($sAuthenticator)). '"';
			$aSet_AuthenticationMethod = $this->Get([
				'key' => $sOQL,
				'no_keys' => true
			]);
									
			// Hopefully there's (at least) one AuthenticationMethod.
			foreach( $aSet_AuthenticationMethod as $aAuthenticationMethod ) {
			
				$fContactId = $aAuthenticationMethod['fields']['user_id'];
				
				// Just check if the user still exists.
				$aSet_UserLocal = $this->Get([
					'key' => 'SELECT UserLocal WHERE id = ' . $fContactId,
					'no_keys' => true
				]);
				
				
				// Always limited to zero or one UserLocal
				foreach( $aSet_UserLocal as $aUserLocal ) {
					
								
					// Cleanup this particular token. $_COOKIE or $_SESSION will be reset anyway.
					$this->Delete([
						'key' => $sOQL
					]);
					
					$sLogin = $aUserLocal['fields']['login'];
					$sKey = $aUserLocal['key'];
				
					return [
						'login' => $sLogin,
						'id' => $sKey
					];
					
				}
				
				
			}
			
			return [
				'errorMsgs' => [
					'Authenticatie van een iTop user is mislukt.'
				]
			];
			
			
		}
		
		
		/**
		* Checks if authentication succeeds using native iTop user classes
		*
		* @var String $sLogin User Login (iTop username)
		* @var String $sPassword Password
		*
		* @return Array true/false
		*
		* @uses \iTop_AccountManager::IsValidLocaluser()
		* @used-by \iTop_AccountManager::HasValidCredentials()
		*
		* @return Array
		* [
		*                                        Failure:
		*  'errorMsgs'                        => Array of strings.
		*
		*                                        Success:
		*  'id'                               => Integer. iTop User ID.
		*  'login'                            => String. Login name.
		*
		* ]
		*
		*/
		private function IsValidAuthenticationMethodLocalUser( $sLogin, $sPassword ) {
			
			// Attempt authentication within native iTop
			if( $this->IsValidLocalUser( $sLogin, $sPassword ) == true ) {
				
								
				// Find the ID
				$aSet_UserLocal = $this->Get([
					'key' => 'SELECT UserLocal WHERE login = "' . $sLogin . '"',
					'no_keys' => true
				]);
				
				// Always limited to zero or one UserLocal
				foreach( $aSet_UserLocal as $aUserLocal ) {		
					
					$sLogin = $aUserLocal['fields']['login'];
					$sKey = $aUserLocal['key'];				
					
					return [
						'login' => $sLogin,
						'id' => $sKey
					];
				}				
				
				
			}
			
			return [
				'errorMsgs' => [
					'Authenticatie van een iTop user is mislukt.'
				]
			];
			
		}
		
	}
	
