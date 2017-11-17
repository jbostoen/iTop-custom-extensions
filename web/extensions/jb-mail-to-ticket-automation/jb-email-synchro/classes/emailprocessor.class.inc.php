<?php
// Copyright (C) 2016 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Lesser General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
/**
 * @copyright   Copyright (C) 2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Abstract class which serves as a skeleton for implementing your own processor of emails
 *
 */
abstract class EmailProcessor
{
	const NO_ACTION = 0;
	const DELETE_MESSAGE = 1;
	const PROCESS_MESSAGE = 2;
	const PROCESS_ERROR = 3;
	const MARK_MESSAGE_AS_ERROR = 4;
	
	abstract public function ListEmailSources();
	
	abstract public function DispatchMessage(EmailSource $oSource, $index, $sUIDL, $oEmailReplica = null);

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
	abstract public function ProcessMessage(EmailSource $oSource, $index, EmailMessage $oEmail, EmailReplica $oEmailReplica);
		
	/**
	 * Called, before deleting the message from the source when the decoding fails
	 * $oEmail can be null
	 * @return integer Next Action Code
	 */
	public function OnDecodeError(EmailSource $oSource, $sUIDL, $oEmail, RawEmailMessage $oRawEmail)
	{
		$sSubject = "iTop ticket creation or update from mail FAILED";
		$sEMailSubject = '';
		if ($oEmail != null)
		{
			$sEMailSubject = $oEmail->sSubject;
		}
		$sMessage = "The message (".$sUIDL."), subject: '$sEMailSubject', was not decoded properly and therefore was not processed.\n";
		$sMessage .= "The original message is attached to this message.\n";
		$this->Trace($sMessage);
		EmailBackgroundProcess::ReportError($sSubject, $sMessage, $oRawEmail);
		return self::MARK_MESSAGE_AS_ERROR;		
	}
	
	
	/**
	 * Called, before deleting the message from the source when the decoding fails
	 * $oEmail can be null
	 * @return integer Next Action Code
	 */
	public function OnPolicyViolation(EmailSource $oSource, $sUIDL, $oEmail, RawEmailMessage $oRawEmail, $sPolicyName)
	{
		$sSubject = "iTop ticket creation or update from mail FAILED because of a policy violation";
		$sEMailSubject = '';
		if ($oEmail != null)
		{
			$sEMailSubject = $oEmail->sSubject;
		}
		$sMessage = "The message (".$sUIDL."), subject: '$sEMailSubject', was not compliant to a policy (".$sPolicyName.") and therefore was not processed.\n";
		$sMessage .= "The original message is attached to this message.\n";
		$this->Trace($sMessage);
		EmailBackgroundProcess::ReportError($sSubject, $sMessage, $oRawEmail);
		return self::MARK_MESSAGE_AS_ERROR;		
	}
	
	/**
	 * @var string To be set by ProcessMessage in case of error
	 */
	protected $sLastErrorSubject;
	/**
	 * @var string To be set by ProcessMessage in case of error
	 */
	protected $sLastErrorMessage;
	 
	/**
	 * Returns the subject for the last error when process ProcessMessage returns PROCESS_ERROR
	 * @return string The subject for the error message email
	 */
	public function GetLastErrorSubject()
	{
		return $this->sLastErrorSubject;
	}
	/**
	 * Returns the body of the message for the last error when process ProcessMessage returns PROCESS_ERROR
	 * @return string The body for the error message email
	 */
	public function GetLastErrorMessage()
	{
		return $this->sLastErrorMessage;
	}
}