<?php

	require_once '../../libext/vendor/autoload.php';
	require_once '../../itop-connector/connector.php';

	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	
	$oREST = new iTop_REST();
	
	// Get certain Services
	$aServices = $oREST->get([
		'key' => 'SELECT Service',
		'onlyValues' => true
	]);
	
	// Get certain ServiceSubcategories
	$aServiceSubCategories = $oREST->get([
		'key' => 'SELECT ServiceSubcategory',
		'onlyValues' => true
	]);
	 	 
	// Render template
	echo $twig->render('reportproblems.html', [
		'Services' => $aServices,
		'ServiceSubCategories' => $aServiceSubCategories	
	]);


?>