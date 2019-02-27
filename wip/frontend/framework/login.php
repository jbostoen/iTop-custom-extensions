<?php

	require_once '../../libext/vendor/autoload.php';

	$sDir = dirname(__DIR__);
	
	
	$loader = new Twig_Loader_Filesystem( $sDir );
	$twig = new Twig_Environment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	if( isset($_REQUEST['action']) == true ) {
				
		$aErrors = [];
		$aErrorFields = [];
		
		switch($_REQUEST['action']){
			
			case 'login':
					
				
				// Login
				if( isset($_REQUEST['login']) == false ) {
					$aErrors[] = 'Geef een login op.';
					$aErrorFields[] = 'login';
				}
				elseif( $_REQUEST['login'] == '' ) {
					$aErrors[] = 'Geef een login op.';
					$aErrorFields[] = 'login';
				}
				
				// Password
				if( isset($_REQUEST['password']) == false ) {
					$aErrors[] = 'Geef een wachtwoord op.';
					$aErrorFields[] = 'password';
				}
				elseif( $_REQUEST['password'] == '' ) {
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
					
					// Attempt authentication
					$oRest_iTop = new iTop_Rest();
					
					$aReturnData = $oRest_iTop->Post([
						'operation' => 'core/check_credentials',
						'user' => $_REQUEST['login'],
						'password' => $_REQUEST['password']
					]);
					
					// Invalid: code = 0, message = '', authorized = ''
					if( $aReturnData['authorized'] == 1 ) {
						echo json_encode([
							'error' => 0
						]);
						exit();
					}
					else {
						echo json_encode([
							'errorMsgs' => 'De combinatie van de opgegeven login en wachtwoord is onjuist.',
							'errorFields' => ['login','password']
						]);
						exit();
						
					}
					
					
				}
			
				break;
				
			default:
			
				break;
				
		}
			
		
		
	}
	else {
		
		// Test
		$_REQUEST['redirect'] = 'https://google.com';
	 	 
		// Render template
		echo $twig->render('framework/templates/login.html', []);
		
		
	}