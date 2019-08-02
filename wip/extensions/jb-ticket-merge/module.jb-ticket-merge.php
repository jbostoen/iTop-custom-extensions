<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
        __FILE__, // Path to the current file, all other file names are relative to the directory containing this file
        'jb-ticket-merge/2.6.190713',
        array(
                // Identification
                //
                'label' => 'Feature: Object Merge',
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
					'model.jb-ticket-merge.php',
					'core/PopupMenuExtensionTicketMerge.class.inc.php'
					// 'ui.jb-ticket-merge.php' - do not include here
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
					
					'default' => array(
					
						// Implemented options
						// --
						'attributes' => array(
							// Attributes must be one of these types: 'AttributeCaseLog', 'AttributeLinkedSet', 'AttributeLinkedSetIndirect'
							// Attributes must be specified by their attribute code. If all attributes (of the above types) are specified, it's possible to use ['*']
							
							// Attributes which can be merged for this class
							'allowed' => array('contacts_list', 'functionalcis_list', 'private_log', 'public_log'),
							
							// ['*'] can be used to include ALL attributes.
							'checked' => array('*'),
							
							// Attributes which are ALWAYS merged (enforced). Any attribute specified here should also be in the allowed list.
							'required' => array('private_log', 'public_log'),
							
						),
						
						'target_object' => array(
							'add_callers_to_related_contacts' => true, // Add callers of the merged tickets to 'related Contacts' (could be used in Notification)
						),
						
						'target_object_selection_order' => array(
							// Multiple attributes can be specified. Processed in order.
							'id' => true, // Set to 'true' for sorting target objects from oldest to newest; set to false for newest to oldest
						),
						
						// Limit who can merge objects?
						'allowed_profiles' => '', // Similar to Combodo's User Actions Configurator. CSV list of profiles allowing the shortcut. The user must have at least one profile to have the shortcut available. Wrong profiles names are ignored. Set as an empty string to allow the shortcut to anybody.
						
						/*
						// Ideas but not implemented
						// --
						
						// OQL Scope to limit objects which can be selected?
						// For instance, non-resolved and non-closed Tickets
						'source_scope' => '', // Similar to Combodo's User Actions Configurator. The OQL to define the source objects. The only parameter available is current_contact_id.
						
						'merged_objects' => array(
							'apply_stimulus' => 'some_stimulus', // @todo Check for valid stimulus
							'delete' => true, // Notification could be sent first. How can a placeholder be used?
							'set' => [
								'<attribute_name>' => 'could be used for writeback; have $targetObj placeholder'
							],
							'add_entries' => array(
								'<CaseLog_attribute_name>' => 'Entry to insert; add $targetObj placeholder'
							),
						),
						*/	
					),
					
                ),
        )
);



