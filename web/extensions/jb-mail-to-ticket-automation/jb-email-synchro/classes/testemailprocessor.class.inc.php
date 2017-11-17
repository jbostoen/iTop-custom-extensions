<?php
/**
 * Used for the unit test of the EmailMessage class
 * Simulates incoming messages by reading from a directory './log) containing .eml files
 * and processes them to check the decoding of the messages
 *
 */
class TestEmailProcessor extends EmailProcessor
{
	public function ListEmailSources()
	{
//		return array( 0 => new IMAPEmailSource('ssl0.ovh.net', 993, 'tickets@combodo.com', 'c8mb0do', '', array('imap', 'ssl', 'novalidate-cert')));
		return array( 0 => new TestEmailSource(dirname(__FILE__).'/log', 'test'));
	}
	
	public function DispatchMessage(EmailSource $oSource, $index, $sUIDL, $oEmailReplica = null)
	{
		return EmailProcessor::PROCESS_MESSAGE;
	}
	
	/**
	 * Process the email downloaded from the mailbox.
	 * This implementation delegates the processing the MailInbox instances
	 * The caller (identified by its email) must already exists in the database
	 * @param EmailSource $oSource The source from which the email was read
	 * @param integer $index The index of the message in the mailbox
	 * @param EmailMessage $oEmail The downloaded/decoded email message
	 * @param EmailReplica $oEmailReplica The information associating a ticket to the email. This replica is new (i.e. not yet in DB for new messages)
	 * @return integer Next Action Code
	 */
	public function ProcessMessage(EmailSource $oSource, $index, EmailMessage $oEmail, EmailReplica $oEmailReplica)
	{
		$sMessage = "[$index] ".$oEmail->sMessageId.' - From: '.$oEmail->sCallerEmail.' ['.$oEmail->sCallerName.']'.' Subject: '.$oEmail->sSubject.' - '.count($oEmail->aAttachments).' attachment(s)';
		if (empty($oEmail->sSubject))
		{
			$sMessage .= "\n=====================================\nERROR: Empty subject for the message.\n";
		}
		if (empty($oEmail->sBodyText))
		{
			$sMessage .= "\n=====================================\nERROR: Empty body for the message.\n";
		}
		else
		{
			$sNewPart = $oEmail->GetNewPart();
			$sMessage .= "\n=====================================\nFormat:{$oEmail->sBodyFormat} \nNewpart:\n{$sNewPart}\n============================================.\n";
		}
		$index = 0;
		foreach($oEmail->aAttachments as $aAttachment)
		{
			$sMessage .= "\n\tAttachment #$index\n";
			if (empty($aAttachment['mimeType']))
			{
				$sMessage .= "\n=====================================\nERROR: Empty mimeType for attachment #$index of the message.\n";
			}
			else
			{
				$sMessage .= "\t\tType: {$aAttachment['mimeType']}\n";
			}
			if (empty($aAttachment['filename']))
			{
				$sMessage .= "\n=====================================\nERROR: Empty filename for attachment #$index of the message.\n";
			}
			else
			{
				$sMessage .= "\t\tName: {$aAttachment['filename']}\n";
			}
			if (empty($aAttachment['content']))
			{
				$sMessage .= "\n=====================================\nERROR: Empty CONTENT for attachment #$index of the message.\n";
			}
			else
			{
				$sMessage .= "\t\tContent: ".strlen($aAttachment['content'])." bytes\n";
			}
			$index++;
		}
		if (!utils::IsModeCLI())
		{
			$sMessage = '<p>'.htmlentities($sMessage, ENT_QUOTES, 'UTF-8').'</p>';
		}
		echo $sMessage."\n";
		return EmailProcessor::NO_ACTION;	
	}	
}