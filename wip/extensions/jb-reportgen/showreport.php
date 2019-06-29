<?php
/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Shows report; based on available Twig templates.
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
	
	// Autoloader (Twig, iTop_Rest, ...
	require JB_APPDIR_ITOP . '/libext/vendor/autoload.php';
		
	// Get iTop's Dict::S('string') so it can be exposed to Twig as well 
	require_once( JB_APPDIR_ITOP . '/approot.inc.php' );
	require_once( JB_APPDIR_ITOP . '/application/utils.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/coreexception.class.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/dict.class.inc.php' );
	
	// Short validation first 
	switch( $_REQUEST['type'] ) {

		case 'details': 
			if( isset($_REQUEST['key']) == false || isset($_REQUEST['template']) == false ) {
				die('Type details requires <b>key</b> and <b>template</b> to be specified.');
			}
			break;
			
		case 'list':
			if( isset($_REQUEST['keys']) == false || isset($_REQUEST['template']) == false ) {
				die('Type details requires <b>keys</b> and <b>template</b> to be specified.');
			}
			break;
			
		default:
			die('Invalid type. Must be: <b>details</b> , <b>list</b>');
		
	}
	
	// Init array to be passed to Twig
	$aObjectData = []; 
	
	// Now that we have the iTop Connector, use it to fetch info of this object we're looking for. 
	// It's easier to pass to Twig with our implementation.
	$oREST = new iTop_Rest();
	
	if( $_REQUEST['type'] == 'details' ) {
						  
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
	$sTemplateFile = $sTemplateDir . $_REQUEST['class'] . '/' . $_REQUEST['type'] . '/' . $_REQUEST['template'];

	if( file_exists($sTemplateFile) == false ) {			
		// Do some nicer handling in the future; but this simply shouldn't happen unless something just got deleted.
		die('Invalid template: ' . $sTemplateFile );
		
	}		
		 
	$aTwigData['items'] = [];
	
	// For single and multiple items: fetch associated Attachments
	foreach( $aReturnData as $aItemData ) {
		
		// No attachments by default
		$aItemData['attachments'] = [];
		
		// Attachments?		
		$aReturnDataAttachments = $oREST->get([
			'key' => 'SELECT Attachment WHERE item_id = '.$aItemData['key'],
			'onlyValues' => true
		]);
		
		foreach( $aReturnDataAttachments as $aAttachmentData ) {
			// Don't repeat data from the parent. Focus on contents ( data, mimetype, filename )
			$aItemData['attachments'][] = $aAttachmentData['fields']['contents'];			
		}
		
		// This will expose 'key' and 'fields' (as well as some other REST data)
		$aTwigData['items'][] = $aItemData;
		
	}
	
	
	// For single item
	if( count($aReturnData) == 1 ) {
		$aTwigData['item'] = $aTwigData['items'][0];			
	}
	 
	// Post both parameters to Twig.
	// Either a single Object was requested, or multiple. 
	
	// Pass to Twig 	
	
	// Twig Loader
	$loader = new Twig_Loader_Filesystem( dirname( __FILE__ ) . '/templates/' . $_REQUEST['class'] . '/' . $_REQUEST['type'] );
	
	// Twig environment options
	$oTwigEnv = new Twig_Environment($loader, array(
		'autoescape' => false
	)); 

	// Combodo uses this filter, so let's use it the same way for our report generator
	$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
			return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
		})
	);
	
	// Relies on chillerlan/php-qrcode
	if( class_exists('chillerlan\\QRCode') == true ) {
		
		$oTwigEnv->addFilter(new Twig_SimpleFilter('qr', function ($sString) {

				$aOptions = new chillerlan\QRCode\QROptions([
					'version'    => 5,
					'outputType' => chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG,
					'eccLevel'   => chillerlan\QRCode\QRCode::ECC_L,
					'scale'		 => 3
				]);

				// invoke a fresh QRCode instance
				$oQRCode = new chillerlan\QRCode\QRCode($aOptions);

				// and dump the output 
				return $oQRCode->render($sString);		
		
			})
		);
			
	}
	
	echo $oTwigEnv->render( $_REQUEST['template'] , $aTwigData );	 


	