<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jd-class-lendrecord/2.6.190822',
	array(
		// Identification
		//
		'label' => 'Datamodel: LendRecord, a system to log who used which tools and/or vehicles and when.',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-request-mgmt-itil/2.6.0' 
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.jd-class-lendrecord.php'
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


?>
