<?php

	require_once '../../libext/vendor/autoload.php';

	
	session_start();
	
	$loader = new Twig_Loader_Filesystem( iTop_FrameWork::frameworkDir );
	$twig = new iTop_TwigEnvironment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	// Check if prior token exists
	$oAccountManager = new iTop_AccountManager();
	$aValidToken = $oAccountManager->DoLoginByToken();

	// Default should not happen
	$sRedirectURL = 'https://google.be?q=Why did someone not specify a redirect URL?';
	
	if( isset($_REQUEST['redirect']) == true ) {
		$sRedirectURL = $_REQUEST['redirect'];
	}
	
	// Prior valid token exists
	if( isset($aValidToken['errorMsgs']) == false ) {
		header('Location: ' . $sRedirectURL );
	}
	
	// print_r( $_SESSION );
	// print_r( $_COOKIE );
	
	
	 
	// Render template
	echo $twig->render('templates/login.html', [
		'PageTitle' => 'Aanmelden',
		'RedirectURL' => $sRedirectURL
	]);
	
		
	
