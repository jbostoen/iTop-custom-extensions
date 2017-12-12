<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jb-itop-standard-email-synchro/2.4.171212',
	array(
		// Identification
		//
		'label' => 'Ticket Creation from Emails (Standard)',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'jb-email-synchro/2.4.171212',
			// no other dependency is listed, for backward 1.x compatibility... though this module uses implicitely the Ticket's derived classes...
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'stdemailsynchro.class.inc.php',
			'model.jb-itop-standard-email-synchro.php'
			//'mailinboxstandard.class.inc.php',
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
			'inline_image_max_width' => '500', // Maximum width (in px) for displaying inline images
			'ticket_log' => array('UserRequest' => 'public_log', 'Incident' => 'public_log'),
		),
	)
);

