<?php

	require_once '../../libext/vendor/autoload.php';

	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	
	$oREST = new iTop_Rest();
	
	// Get certain Services
	$aServices = $oREST->get([
		'key' => 'SELECT Service',
		'no_keyss' => true
	]);
	
	// Get certain ServiceSubcategories
	$aServiceSubCategories = $oREST->get([
		'key' => 'SELECT ServiceSubcategory',
		'no_keys' => true
	]);
	 	 
	// Render template
	echo $twig->render('reportproblems.html', [
		'Services' => $aServices,
		'ServiceSubCategories' => $aServiceSubCategories	
	]);

	