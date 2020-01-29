<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'jb-email-synchro/2.6.200129',
	array(
		// Identification
		'label' => 'Mail to Tickets Automation (core)',
		'category' => 'business',
		// Setup
		'dependencies' => array(
		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'EmailSynchroInstaller',
		// Components
		'datamodel' => array(
			'classes/autoload.php',
			'model.jb-email-synchro.php',
		),
		'dictionary' => array(
		),
		'data.struct' => array(
		),
		'data.sample' => array(
		),
		
		// Documentation
		'doc.manual_setup' => '', // No manual installation required
		'doc.more_information' => '', // None
		
		// Default settings
		'settings' => array(
		
			// Note: some of these settings are still here from the original Combodo version.
			// Some of them no longer have any effect.
			
			'notify_errors_to' => '', // mandatory to track errors not handled by the email processing module
			'notify_errors_from' => '', // mandatory as well (can be set at the same value as notify_errors_to)
			'debug' => false, // Set to true to turn on debugging
			'periodicity' => 30, // interval at which to check for incoming emails (in s)
			'body_parts_order' => 'text/html,text/plain', // Order in which to read the parts of the incoming emails
			'pop3_auth_option' => 'USER',
			'maximum_email_size' => '25M', // Maximum allowed size for incoming emails. Set to 0 for unlimited size.
			'big_files_dir' => '',

			// Some patterns which delimit the previous message in case of a Reply
			// The "new" part of the message is the text before the pattern
			// Add your own multi-line patterns (use \\R for a line break)
			// These patterns depend on the mail client/server used... feel free to add your own discoveries to the list
			'multiline-delimiter-patterns' => array(
				'/\\RFrom: .+\\RSent: .+\\R/m', // Outlook English
				'/\\R_+\\R/m', // A whole line made only of underscore characters
				'/\\RDe : .+\\R\\R?Envoyé : /m', // Outlook French, HTML and rich text
				'/\\RDe : .+\\RDate d\'envoi : .+\\R/m', // Outlook French, plain text
				'/\\R-----Message d\'origine-----\\R/m',
			),
			
			'use_message_id_as_uid' => true, // Do NOT change this unless you known what you are doing! Despite being 'false' in Combodo's Mail to Ticket Automation (3.0.5), it works better if set to true on IMAP connections.
			
			'imap_options' => array (
				'imap',
				'ssl',
				'novalidate-cert',
			),
			
			// These settings existed with a - instead of _ 
			// To make them more consistent:
			
			// Lines to be removed just above the 'new part' in a reply-to message... add your own patterns below
			'introductory-patterns' => array(
				'/^le .+ a écrit :$/i', // Thunderbird French
				'/^on .+ wrote:$/i', // Thunderbird English
				'|^[0-9]{4}/[0-9]{1,2}/[0-9]{1,2} .+:$|', // Gmail style
			),
			
			'undesired_purge_delay' => 7, // Warning: Combodo's version had an inconsistent undesired-purge-delay setting. Renamed. Interval (in days) after which undesired emails are deleted in the mailbox
			
			// Deprecated settings:
			'images_minimum_size' => '100x20', // Images smaller than these dimensions will be ignored (signatures...)
			'images_maximum_size' => '', // Images bigger than these dimensions will be resized before uploading into iTop,
		),
	)
);

if (!class_exists('EmailSynchroInstaller'))
{

	// Module installation handler
	//
	class EmailSynchroInstaller extends ModuleInstallerAPI
	{

		/**
		 * Handler called after the creation/update of the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion) {
			// For each email sources, update email replicas by setting mailbox_path to source.mailbox where mailbox_path is null
			SetupPage::log_info("Updating email replicas to set their mailbox path.");

			// Preparing mailboxes search
			$oSearch = new DBObjectSearch('MailInboxBase');

			// Retrieving definition of attribute to update
			$sTableName = MetaModel::DBGetTable('EmailReplica');

			$UidlAttDef = MetaModel::GetAttributeDef('EmailReplica', 'uidl');
			$sUidlColName = $UidlAttDef->Get('sql');

			$oMailboxAttDef = MetaModel::GetAttributeDef('EmailReplica', 'mailbox_path');
			$sMailboxColName = $oMailboxAttDef->Get('sql');

			// Looping on inboxes to update
			$oSet = new DBObjectSet($oSearch);
			while ($oInbox = $oSet->Fetch())
			{
				$sUpdateQuery = "UPDATE $sTableName SET $sMailboxColName = " . CMDBSource::Quote($oInbox->Get('mailbox')) . " WHERE $sUidlColName LIKE " . CMDBSource::Quote($oInbox->Get('login') . '_%') . " AND $sMailboxColName IS NULL";
				SetupPage::log_info("Executing query: " . $sUpdateQuery);
				$iRet = CMDBSource::Query($sUpdateQuery); // Throws an exception in case of error
				SetupPage::log_info("Updated $iRet rows.");
			}
		}
		
	}

}
