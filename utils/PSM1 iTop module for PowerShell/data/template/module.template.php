<?php

/**
 * @copyright   Copyright (C) {{ ext_Year }} {{ ext_Author }}
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     {{ ext_TimeStamp }}
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        '{{ ext_Name }}/{{ ext_Version }}',
        array(
                // Identification
                //
                'label' => '{{ ext_Label }}',
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
					'model.{{ ext_Name }}.php'
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
