<?php

/**
 * @copyright   Copyright (C) 2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-08-05 19:34:49
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        'jb-tweaks-teemip/2.6.200805',
        array(
                // Identification
                //
                'label' => 'Datamodel: TeemIP - tweaks',
                'category' => 'business',

                // Setup
                //
                'dependencies' => array(
					'teemip-config-mgmt-adaptor/2.3.0',
                ),
                'mandatory' => false,
                'visible' => true,

                // Components
                //
                'datamodel' => array(
					'model.jb-tweaks-teemip.php'
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


