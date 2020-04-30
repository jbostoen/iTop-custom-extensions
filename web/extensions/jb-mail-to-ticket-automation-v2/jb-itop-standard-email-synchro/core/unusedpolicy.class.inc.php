<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 16:58:14
 *
 * A demo of a class which could store e-mails automatically.
 */
 
namespace jb_itop_extensions\mail_to_ticket;

/**
 * Class PolicyExampleSaveEmailsToPath Offers a policy to save incoming emails as .EML file to a (hardcoded) directory.
 * Note: this is NOT in use by default.
 */
abstract class PolicyExampleSaveEmailsToPath extends Policy implements iPolicy {
	
	/**
	 * @var \Integer $iPrecedence It's not necessary that this number is unique; but when all policies are listed; they will be sorted ascending (intended to make sure some checks run first; before others).
	 * Depending on the use case, set a low number (always export the email) or high number (export only if certain policies have been processed and email was compliant)
	 */
	public static $iPrecedence = 1;
	
	/**
	 * @var \String $sPolicyId Shortname for policy
	 */
	public static $sPolicyId = 'policy_example_save_emails_to_path';
	
	/**
	 * Checks if mailbox, email, ticket information is compliant with a certain policy.
	 *
	 * @return boolean Whether this is compliant with a specified policy. Returning 'false' blocks further processing.
	 */
	public static function IsCompliant() {
		
		parent::BeforeComplianceCheck();
		
		$oRawEmail = self::$oEmail->oRawEmail;
		
		// Add some logic for file name. Mind time zones!
		$sDateTime = strtotime($oRawEmail->GetHeader('date'));
		$sFolder = 'C:/temp/'.date('Ymd', $sDateTime);
		$sMessageId = $oRawEmail->GetMessageId();
		
		// Forbidden on Windows
		$aForbiddenChars = array_merge(
			array_map('chr', range(0,31)),
			array('<', '>', ':', '\'', '/', '\\', '|', '?', '*')
		);
		
		$sSanitizedMessageId = str_replace($aForbiddenChars, '', $sMessageId);
		$sSanitizedMessageId = str_replace('.', '_', $sSanitizedMessageId);
		
		if(file_exists('C:/temp/') == true) {
			if(file_exists($sFolder) == false) {
				self::Trace('.. Create folder: '.$sFolder);
				mkdir($sFolder);
			}
			$sFilePath = $sFolder.'/'.$sSanitizedMessageId.'.eml';
			self::Trace('.. Save e-mail to '.$sFilePath);
			$oRawEmail->SaveToFile($sFilePath);
		}
		else {
			self::Trace('.. Unable to export: folder C:/temp does not exist');
		}
		
		parent::AfterPassedComplianceCheck();
		
		return true;
		
	}

}


/*
@todo 


abstract class PolicyAttachmentSize implements iPolicy {
	// attachment too big
}

abstract class PolicyAttachmentVirusCheck implements iPolicy {
	// could be an example implementing ClamAv, similar to what's mentioned in MailInboxBase
}

abstract class PolicyStatistics implements iPolicy {
	// could be an example of keeping track of statistics. Number of processed e-mails per inbox etc.
}

*/

