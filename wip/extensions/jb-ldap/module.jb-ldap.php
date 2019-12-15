<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-10-28 13:58:34
 *
 * iTop module definition file
 */

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jb-ldap/2.6.191028',
	array(
		// Identification
		//
		'label' => 'Feature: LDAP Synchronization (cron)',
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
			'core/LDAPSync.class.inc.php',
			'core/ScheduledProcessLDAPSync.class.inc.php',
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
			'time' => '03:00',
			'week_days' => 'monday, tuesday, wednesday, thursday, friday',
			'enabled' => true,
			'debug' => false,
		
			// Specifies defaults (if any)			
			// One or more sync rules should be placed here.
			// For security, it's highly recommended to only use an account with read-only permissions.
			// Settings are similar to Combodo's authent-ldap and used as default settings for any sync rule (the specific rules can overrule this)
			'default_sync_rule' => array(
			
				'host' => '127.0.0.1',
				'port' => 389,
				'default_user' => 'someuser@intranet.domain.org',
				'default_pwd' => 'someuser',
				'base_dn' => 'DC=intranet,DC=domain,DC=org',
				'start_tls' => false,
				'options' => array(
					17 => 3,
					8 => 0
				),
				
				// Currently only strings and integers are supported; not lists/arrays/...
				// These attributes will be fetched and are then available in the $ldap_user->ldap_att$ placeholder
				'ldap_attributes' => array(
					'sn',
					'givenname',
					'mail',
					'telephonenumber',
					'company',
					'samaccountname',
					'userprincipalname',
					'displayname'
					// 'objectsid', -> sadly this is hexadecimal
				),
				
				'objects' => array(
				
				)
				
			),
			
			'sync_rules' => array(
			
				array(
					// Require mail. 'not set' would be: (!(mail=*)); while (mail=*) means mail MUST be set.
					'user_query' => '(&(objectclass=user)(objectcategory=person)(!(sAMAccountName=admin))(mail=*))',
					
					'objects' => array(
					
						// List iTop classes where the info can be used. Objects will be created or updated (unique match), not deleted.
						// Placeholders (can be used to set new attribute values and in OQL queries)
						// - $ldap_user->ldap_att$ (attributes determined in ldap_attributes setting), 
						// - $first_object->id$ (only available after the first object has been created!)
						//   Use case example: refer to a created Person object to create user accounts
						// - $previous_object->id$
						//   Use case: link between a first and second object
						0 => array(
							'class' => 'Person',
							'attributes' => array(
								'org_id' => 1, // Organization for the user. Required attribute
								'email' => '$ldap_user->mail$',
								'first_name' => '$ldap_user->givenname$',
								'name' => '$ldap_user->sn$',
								'phone' => '$ldap_user->telephonenumber$',
							),
							'reconcile_on' => 'SELECT Person WHERE email LIKE "$ldap_user->mail$"'
						),
						
					)
				),
			)
		
		),
	)
);


