<?php

	require_once '../../libext/vendor/autoload.php';

	$sDir = dirname(__DIR__);
	
	session_start();
	
	$loader = new Twig_Loader_Filesystem( $sDir );
	$twig = new Framework_TwigEnvironment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	// AttributeLinkedSetIndirect: profile	
	

	
	// Render template
	echo $twig->render('framework/templates/register.html', [
	'PageTitle' => 'Registreren',
		'redirectURL' => 'https://google.com'
	]);
		