<?php

	/**
	 *  Show report using Twig templates
	 *  
	 *  Last updated: 20181206-1447
	 *  
	 */
	 
	// $_REQUEST should contain: 
	// class:               String. Class name
	// key:                 Integer. Key ID(s). Single ID. In combination with 'type=detail'.
	// keys:                Integer(s). Key ID(s). Comma separated. In combination with 'type=list'.
	// template: 			String. Report name. For convenience, use detail/<filename>.twig and list/<filename>.twig
	// type: 				String. 'details' for single object, 'list' for multiple IDs. (reserved for future use, e.g. List/Overview)
	 
	// Assume we're under /extensions/extname. No other references yet.
	// This file will be copied to /env-production, but the link will still point to /extensions
	defined('JB_APPDIR_ITOP') or define('JB_APPDIR_ITOP', dirname(dirname(dirname( __FILE__ ))) );
	
	// Use itop-connector 
	require_once( JB_APPDIR_ITOP . '/itop-connector/connector.php');
		
	// Get iTop's Dict::S('string') so it can be exposed to Twig as well 
	require_once( JB_APPDIR_ITOP . '/approot.inc.php' );
	require_once( JB_APPDIR_ITOP . '/application/utils.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/coreexception.class.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/dict.class.inc.php' );
	
	
	// Init array to be passed to Twig
	$aObjectData = []; 
	
	// Now that we have the iTop Connector, use it to fetch info of this object we're looking for. 
	// It's easier to pass to Twig with our implementation.
	$oREST = new iTop_Rest();
	
	if( $_REQUEST['type'] == 'detail' ) {
						  
		// Request
		$aReturnData = $oREST->get([
			'key' => $_REQUEST['key'],
			'class' => $_REQUEST['class'],
			'onlyValues' => true
		]);
		
	}
	elseif( $_REQUEST['type'] == 'list' ) {
				  
		// Request
		$aReturnData = $oREST->get([
			'key' => 'SELECT '.$_REQUEST['class'].' WHERE id IN ('.$_REQUEST['keys'].')',
			'class' => $_REQUEST['class'],
			'onlyValues' => true
		]);		
		
	}
		
	// Valid object?
	// 'list' and 'detail' should at least return 1 item.
	if( count( $aReturnData ) < 1 ) {
		
		// Do some nicer handling in the future 
		die('Invalid object');
		
	}
		

	// Valid template?
	$sTemplateDir = dirname( __FILE__ ) . '/templates/';
	$sTemplateFile = $sTemplateDir . $_REQUEST['class'] . '/' . $_REQUEST['template'];


	if( file_exists($sTemplateFile) == false ) {			
		// Do some nicer handling in the future; but this simply shouldn't happen unless something just got deleted.
		die('Invalid template: ' . $sTemplateFile );
		
	}		
		 
	
	$aTwigData['items'] = [];
	
	// For single and multiple items
	foreach( $aReturnData as $aItemData ) {
		// Only keep data from 'fields' 
		$aTwigData['items'][] = $aItemData;
	}
	
	
	// For single item
	if( count($aReturnData) == 1 ) {
		$aTwigData['item'] = $aTwigData['items'][0];			
	}
	 
		

	
	// Post both parameters to Twig.
	// Either a single Object was requested, or multiple. 
	
	
	// Pass to Twig 	
	require JB_APPDIR_ITOP . '/libext/vendor/autoload.php';
	
	// Twig Loader
	$loader = new Twig_Loader_Filesystem( dirname( __FILE__ ) . '/templates/' . $_REQUEST['class'] );
	
	// Twig environment options
	$oTwigEnv = new Twig_Environment($loader, array(
		'autoescape' => false
	)); 

	// Combodo uses this filter, so let's use it the same way for our report generator
	$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
			return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
		})
	); 
	
	echo $oTwigEnv->render( $_REQUEST['template'] , $aTwigData );	 


	