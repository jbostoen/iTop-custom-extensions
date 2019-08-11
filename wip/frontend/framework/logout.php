<?php

	require_once '../../libext/vendor/autoload.php';
	
	$oAccountManager = new iTop_AccountManager();
	
	$oAccountManager->DoLogout();
	
	
	$loader = new Twig_Loader_Filesystem( iTop_FrameWork::frameworkDir );
	$twig = new Framework_TwigEnvironment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	 
	// Render template
	echo $twig->render('templates/login.html', [
		'PageTitle' => 'Aanmelden',
	]);
	
		
	
