<?php

	/**
	 * Add  
	 */
	 
	// Assume we're under /itopDir/web/frontend/framework. No other references yet.
	// This file will be copied to /env-production, but the link will still point to /extensions
	defined('JB_APPDIR_ITOP') or define('JB_APPDIR_ITOP', dirname(dirname( __FILE__ )) );
			
	// Get iTop's Dict::S('string') so it can be exposed to Twig as well 
	require_once( JB_APPDIR_ITOP . '/approot.inc.php' );
	require_once( JB_APPDIR_ITOP . '/application/utils.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/coreexception.class.inc.php' );
	require_once( JB_APPDIR_ITOP . '/core/dict.class.inc.php' );
	
	