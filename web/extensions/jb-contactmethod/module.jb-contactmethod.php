<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-22 12:47:48
 *
 * PHP Main file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        'jb-contactmethod/2.6.190822',
        array(
                // Identification
                //
                'label' => 'Datamodel: Contact Method',
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
					'model.jb-contactmethod.php',
					'app/application/applicationextension.inc.php',
					'app/common/phonehelper.class.inc.php'
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
