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
	'jb-ipdevices-teemip/2.6.190807',
	array(
		// Identification
		//
		'label' => 'Datamodel: IP Devices - TeemIP enhancements',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.6.0',
			'itop-endusers-devices/2.6.0',
			'teemip-endusers-devices-adaptor/2.2.0', // TeemIP module version is 2.4.0; but this particular XML is 2.2.0
			'jb-ipdevices/2.6.0'
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.jb-ipdevices-teemip.php'
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
