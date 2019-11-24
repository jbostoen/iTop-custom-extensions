<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jb-itop-standard-email-synchro/2.6.191101',
	array(
		// Identification
		//
		'label' => 'Feature: Ticket Creation from E-mails',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'jb-email-synchro/2.6.190110',
			// no other dependency is listed, for backward 1.x compatibility... though this module uses implicitely the Ticket's derived classes...
		),
		'installer' => 'StandardEmailSynchroInstaller',
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.jb-itop-standard-email-synchro.php',
			'core/ormcustomcaselog.class.inc.php',
			'core/policy.class.inc.php',
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
			'inline_image_max_width' => 500, // Maximum width (in px) for displaying inline images
			'ticket_log' => array('UserRequest' => 'public_log', 'Incident' => 'public_log'),
		),
	)
);


if (!class_exists('StandardEmailSynchroInstaller')) {

	// Module installation handler
	//
	class StandardEmailSynchroInstaller extends ModuleInstallerAPI {

		/**
		 * Handler called before creating or upgrading the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 *
		 * @since 20191123-2008
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion) {
			
			// 20191123-2011: renamed enum values, indicating they're fallbacks and doing a specific action; even if there's only one fallback.
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_forbidden_attachments_behavior', 'fallback', 'fallback_ignore_forbidden_attachments');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_no_subject_behavior', 'fallback', 'fallback_default_subject');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_ticket_resolved_behavior', 'fallback', 'fallback_reopen');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_ticket_closed_behavior', 'fallback', 'fallback_reopen');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_unknown_caller_behavior', 'fallback', 'fallback_create_person');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_other_recipients_behavior', 'ignore_all_contacts', 'fallback_ignore_other_contacts');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_other_recipients_behavior', 'add_all_contacts', 'fallback_add_other_contacts');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_other_recipients_behavior', 'add_existing_contacts', 'fallback_add_existing_other_contacts');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_remove_pattern_behavior', 'remove', 'fallback_remove');
			self::RenameEnumValueInDB('MailInboxStandard', 'policy_remove_pattern_behavior', 'ignore', 'do_nothing'); // Should actually be translated to policy_ignore_pattern
			
		}
		
	}

}