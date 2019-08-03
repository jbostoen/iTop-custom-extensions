<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-03 2019-08-03
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jb-geom/2.6.190803',
	array(
		// Identification
		//
		'label' => 'jb-geom',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.6.0',
			'itop-request-mgmt-itil/2.6.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.jb-geom.php',
			'classes/ApplicationUIExtensionGeometryHandler.class.inc.php',
			'classes/CustomSearchForm.class.inc.php',
			'classes/DashletMapOverview.class.inc.php',
			'classes/PopupMenuExtensionGeometryHandler.class.inc.php',
			// ajax.handler.php and ajax.searchform.php are not needed here.
		),
		'webservice' => array(
			
		),
		'data.struct' => array(
			// add your 'structure' definition XML files here,
		),
		'data.sample' => array(
			// add your sample data XML files here,
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(
			// Module specific settings go here, if any
		),
	)
);


