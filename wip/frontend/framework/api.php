<?php

	// Default is to return JSON
	header('Content-Type: application/json');
	
	// Loader
	require('../../libext/vendor/autoload.php');
	
	// Don't cache
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	
	if( isset($_REQUEST['action']) == true ) {
				
		$aErrors = [];
		$aErrorFields = [];
		
		switch($_REQUEST['action']){
			
			case 'login':
					
				
				// Login
				if( empty($_REQUEST['login']) == true ) {
					$aErrors[] = 'Geef een login op.';
					$aErrorFields[] = 'login';
				}
				
				// Password
				if( empty($_REQUEST['password']) == true ) {
					$aErrors[] = 'Geef een wachtwoord op.';
					$aErrorFields[] = 'password';
				}
				
							
				if( count($aErrors) > 0 ) {
					echo json_encode([
						'errorMsgs' => $aErrors,
						'errorFields' => $aErrorFields
					]);
					exit();
				}
				else {
					
					$oAccountManager = new iTop_AccountManager();
										
					// Attempt authentication
					$aResult_Authentication = $oAccountManager->DoLogin([
						'authentication_methods' => ['email', 'native'], 
						'login' => $_REQUEST['login'],
						'password' => $_REQUEST['password'],
						'set_cookie' => ( @$_REQUEST['rememberMe'] == 1 ? true : false )
					]);
					
					if( isset($aResult_Authentication['errorMgs']) == false ) {
					
						// No error
						echo json_encode($aResult_Authentication);
						exit();
					
					}
					else {
						
						// Not a regular iTop login.
						// Can we find someone where login = email; and this password?						
						
						echo json_encode([
							'errorMsgs' => ['De combinatie van login en wachtwoord klopt niet.'],
							'errorFields' => ['login', 'password']
						]);
						exit();
						
					}
					
					
				}
			
				break;				
				
			case 'register':			
			
				// Privacy
				if( empty( @$_REQUEST['person']['agree_gdpr']) == true ) {
					$aErrors[] = 'Je kan slechts een account aanmaken als je akkoord gaat met onze privacyverklaring.';
					$aErrorFields[] = 'agree_gdpr';
				}
				
				
				if( count($aErrors) > 0 ) {
					echo json_encode([
						'errorMsgs' => $aErrors,
						'errorFields' => $aErrorFields
					]);
					exit();
				}
				elseif( isset($_REQUEST['person']) == true ) {
					
						
					// @todo Add more validation: password complexity; valid email etc
					$oAccountManager = new iTop_AccountManager();
					
					// Try to create an account.
					// If it conflicts/possibly exists, it will be handled in iTop_AccountManager::createAccount()				
					$aPerson = $_REQUEST['person'];
														
					// Create account
					$aData = $oAccountManager->CreateAccount( $aPerson );				
				
					echo json_encode($aData);
					exit();
			
				}
			
			
				echo json_encode([
					'errorMsgs' => ['Er is een probleem met de registratieprocedure.']
				]);
				exit();
						
						
				break;
				
			default:
			
				break;
				
		}
		
	}
	else {
		
		echo json_encode([
			'error' => 1,
			'msg' => 'Onbekende actie', 
			'request' => $_REQUEST
		]);
		
	}
	