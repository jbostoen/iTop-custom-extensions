<?php

	header('Content-Type: application/json');

	require('../../libext/vendor/autoload.php');
	
	
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
					
					// Accepted contact methods
					$aContactMethods = ['email'];
					
					// Attempt authentication
					if( $oAccountManager->HasValidCredentials( /* sLogin */ $_REQUEST['login'], /* sPassword */ $_REQUEST['password'], /* aContactMethods */ $aContactMethods ) == true ) {
					
						// No error
						echo json_encode([]);
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
			
				if( isset($_REQUEST['person']) == true ) {
					
						
					// @todo Add more validation: password complexity; valid email etc
					$oAccountManager = new iTop_AccountManager();
					
					// Try to create an account.
					// If it conflicts/possibly exists, it will be handled in iTop_AccountManager::createAccount()				
					$aPerson = $_REQUEST['person'];
					
					// Extend
					$aPerson['org_id'] = 1;
					
					// Create account
					$aData = $oAccountManager->CreateAccount( $aPerson );				
				
					echo json_encode($aData);
			
				}
			
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
	