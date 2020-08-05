<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-08-05 19:34:49
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jb-certificate-software-mgmt/2.6.200805',
	array(
		// Identification
		//
		'label' => 'Datamodel: Certificate (extension for Software Management)',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'jb-software-mgmt/2.6.0'
		),
		'mandatory' => false,
		'visible' => true,
		'auto_select' => 'SetupInfo::ModuleIsSelected("jb-certificate") && SetupInfo::ModuleIsSelected("jb-software-mgmt")',

		// Components
		//
		'datamodel' => array(
			'model.jb-certificate-software-mgmt.php'
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



