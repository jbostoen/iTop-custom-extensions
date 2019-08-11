<?php

	require_once '../../libext/vendor/autoload.php';

	$sDir = dirname(__DIR__);
	
	session_start();
	
	$loader = new Twig_Loader_Filesystem( iTop_FrameWork::frameworkDir );
	$twig = new iTop_TwigEnvironment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	
	// Default should not happen
	$sRedirectURL = 'https://google.be?q=Why did someone not specify a redirect URL?';
	
	if( isset($_REQUEST['redirect']) == true ) {
		$sRedirectURL = $_REQUEST['redirect'];
	}
	
	
	$aPrefill = [];
	
	
	
	// Render template
	echo $twig->render('templates/register.html', [
		'PageTitle' => 'Registreren',
		'RedirectURL' => $sRedirectURL,
		'prefill' => $aPrefill
	]);
		
