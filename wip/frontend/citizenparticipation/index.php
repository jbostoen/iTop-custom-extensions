<?php

	session_start();
	
	require_once('../../libext/vendor/autoload.php');
	

	$sDir = dirname(__DIR__);
	
	
	$loader = new Twig_Loader_Filesystem( iTop_FrameWork::frameworkDir );
	$twig = new Twig_Environment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	
	$oREST = new iTop_Rest();
	
	// Get certain Services
	$aIdeas = $oREST->get([
		'key' => 'SELECT UserRequest',
		'no_keys' => true
	]);
	 
	 	
	if( isset($_REQUEST['p']) == true ) {
		
			
		switch($_REQUEST['p']) {
			
			case 'login':
			
				echo $twig->render('framework/templates/login.html', [
					'Session' => $_SESSION,
					'RedirectURL' => 'citizenparticipation/?p=overview'
				]);
				break;
			
			default:
			
				// Render template
				echo $twig->render('citizenparticipation/templates/citizenparticipation_overview.html', [
					'Session' => $_SESSION,
					'Ideas' => $aIdeas
				]);
				break;
				
		}	
		
	}
	else {
		
		// Render template
		echo $twig->render('templates/citizenparticipation/overview.html', [
			'PageTitle' => 'Ideeënplatform',
			'PageIntro' => 'Heb je een leuk idee voor onze stad? Deel het met ons.',
			'Session' => $_SESSION,
			'Ideas' => $aIdeas,
			'izIFW_token' => ''
		]);
		
		
	}
	