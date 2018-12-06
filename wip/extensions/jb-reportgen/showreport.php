<?php

	/**
	 *  Show report using Twig templates
	 *  
	 *  Last updated: 20181206-1447
	 *  
	 */
	 

	// $_REQUEST should contain: 
	// class:				String. Class name
	// key:                 Integer. Key (ID)
	// template: 			String. Report name
	// type: 				String. 'Object' (reserved for future use, e.g. List/Overview)
	 
	 
	// Assume we're under /extensions/extname. No other references yet.
	defined('JB_APPDIR_ITOP') or define('JB_APPDIR_ITOP', dirname(dirname(dirname( __FILE__ ))) );
	
	// Use itop-connector 
	require_once( JB_APPDIR_ITOP . '/itop-connector/connector.php');
	
	
	// Get iTop's Dict::S('string') so it can be exposed to Twig as well 
	require_once( JB_APPDIR_ITOP . '/approot.inc.php' );
	require_once( JB_APPDIR_ITOP . '/application/utils.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/coreexception.class.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/dict.class.inc.php' );
	
	
	// Now that we have the iTop Connector, use it to fetch info of this object we're looking for. 
	$oREST = new iTop_Rest();
	 
	// Request
	$aReturnData = $oREST->get([
		'key' => $_REQUEST['key'],
		'class' => $_REQUEST['class'],
		'onlyValues' => true
	]);
	
	// Valid object?
	if( count( $aReturnData ) != 1 ) {
		
		// Do some nicer handling in the future 
		die('Invalid object');
		
	}
	
	// Valid template?
	$sTemplateDir = dirname( __FILE__ ) . '/templates/';
	$sTemplateFile = $sTemplateDir . $_REQUEST['class'] . '/' . $_REQUEST['template'];
	
	if( file_exists($sTemplateFile) == false ) {
		
		// Do some nicer handling in the future 
		die('Invalid template: ' . $sTemplateFile );
		
	}
	
	
	
	// Get object 
	$aObjectData = $aReturnData[0];
	 
	
	
	// Pass to Twig 	
	require JB_APPDIR_ITOP . '/libext/vendor/autoload.php';
	
	// Twig Loader
	$loader = new Twig_Loader_Filesystem( dirname( __FILE__ ) . '/templates/' . $_REQUEST['class'] );
	
	// Twig environment options
	$twig = new Twig_Environment($loader, array(
		'autoescape' => false
	));
	
	 
	// Expose strings. Can't be done as 'Dict::S', so just 'S'
	$twigLangFunction = new Twig_Function('S', function ( $sLanguageString ) {
		echo Dict::S( $sLanguageString );
	});
	$twig->addFunction($twigLangFunction);


	
	echo $twig->render( $_REQUEST['template'] , $aObjectData );	
	
	
	
	