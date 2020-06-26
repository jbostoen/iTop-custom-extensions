<?php

/**
 * @copyright   Copyright (C) 2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-05-06 13:36:24
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        'jb-sim/2.6.200506',
        array(
                // Identification
                //
                'label' => 'Datamodel: SIM card',
                'category' => 'business',

                // Setup
                //
                'dependencies' => array(
					'itop-endusers-devices/2.6.0'
                ),
                'mandatory' => false,
                'visible' => true,

                // Components
                //
                'datamodel' => array(
					'model.jb-sim.php'
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

