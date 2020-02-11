<?php

/**
 * @copyright   Copyright (C) 2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-18 18:59:12
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        'jb-document-cobra/2.6.200204',
        array(
                // Identification
                //
                'label' => 'Official approval',
                'category' => 'business',

                // Setup
                //
                'dependencies' => array( 
                ),
                'mandatory' => false,
                'visible' => true,

                // Components
                //
                'datamodel' => array(
					'model.jb-document-cobra.php',
					'core/CobraHelper.class.inc.php',
					'core/ApplicationUIExtensionCobra.class.inc.php',
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
					'url' => 'https://some.url/folder/%1$s',
					'server_instance' => 'ip\INSTANCENAME',
					'sql_user' => 'user',
					'sql_password' => 'pwd',
                ),
				
        )
);

