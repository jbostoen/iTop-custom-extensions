<?php
// Copyright (C) 2012-2016 Combodo SARL
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
 * @copyright   Copyright (C) 2012-2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class MessageFromMailbox extends RawEmailMessage
{
	protected $sUIDL;
	
	public function __construct($sUIDL, $sRawHeaders, $sBody)
	{
		$this->sUIDL = $sUIDL;
		parent::__construct( $sRawHeaders."\r\n".$sBody);
	}
	
	/**
	 * Create a new RawEmailMessage object by reading the content of the given file
	 * @param string $sFilePath The path to the file to load
	 * @return RawEmailMessage The loaded message
	 */
	static public function FromFile($sFilePath)
	{
		//TODO: improve error handling in case the file does not exist or is corrupted...
		return new MessageFromMailbox(basename($sFilePath), file_get_contents($sFilePath), '');
	}
	
	/**
	 * Decodes an email from its parts
	 * @return EmailMessage
	 */
	public function Decode($sPreferredDecodingOrder = 'text/plain,text/html')
	{
		$sMessageId = $this->GetMessageId();
		$aCallers = $this->GetSender();
		if (count($aCallers) > 0)
		{
			$sCallerEmail = $aCallers[0]['email'];
			$sCallerName = $this->GetCallerName($aCallers[0]);
		}
		$sSubject = $this->GetSubject();

		$sBodyText = '';
		$sBodyFormat = '';
		$aDecodingOrder = explode(',', $sPreferredDecodingOrder);
		foreach($aDecodingOrder as $sMimeType)
		{
			$aPart = $this->FindFirstPart($sMimeType, '/attachment/i');
			if ($aPart !== null)
			{
				$sBodyText = $aPart['body'];
				$sBodyFormat = $sMimeType;
				break;
			}
		}	

		$sRecipient = '';
		$sReferences = $this->GetHeader('references');
		$aReferences = explode(' ', $sReferences );
		$sThreadIndex = $this->GetMSThreadIndex();
		$aAttachments = $this->GetAttachments();
		$sDecodeStatus = '';
		$oRelatedObject = $this->GetRelatedObject();
		$iTime = strtotime($this->GetHeader('date'), 0); // Parse the RFC822 date format
 		$sDate = date('Y-m-d H:i:s', $iTime);
		
 		$aTos = $this->GetTo();
 		$aCCs = $this->GetCc();
 		
		$oMessage = new EmailMessage(
			$this->sUIDL,
			$sMessageId,
			$sSubject,
			$sCallerEmail,
			$sCallerName,
			$sRecipient,
			$aReferences,
			$sThreadIndex,
			$sBodyText,
			$sBodyFormat,
			$aAttachments,
			$oRelatedObject,
			$this->GetHeaders(),
			$sDecodeStatus,
			$sDate,
			$aTos,
			$aCCs
		);
		$oMessage->oRawEmail = $this; // Keep the source raw email for reference and further processing if needed
		return $oMessage;
	}
	
	/**
	 * Get MS Thread-index for this message
	 */
	protected function GetMSThreadIndex()
	{
		return $this->GetHeader('thread-index');
	}
	 
	protected function GetCallerName()
	{
		$aSender = $this->GetSender();
		$sName = '';
		
		if (count($aSender) > 0)
		{
			if (!empty($aSender[0]['name']))
			{
				$sName = $aSender[0]['name'];
				if (preg_match("/.+ \(([^\)]+) at [^\)]+\)$/", $sName, $aMatches))
				{
					$sName = $aMatches[1];	
				}			
			}
			else
			{
				if (preg_match("/^([^@]+)@.+$/", $aSender[0]['email'], $aMatches))
				{
					$sName = $aMatches[1]; // Use the first part of the email address before the @
				}
			}
		}
		
		// Try to "pretty format" the names
		if (preg_match("/^([^\.]+)[\._]([^\.]+)$/", $sName, $aMatches))
		{
			// transform "john.doe" or "john_doe" into "john doe"
			$sName = $aMatches[1].' '.$aMatches[2];
		}

		if (preg_match("/^([^,]+), ([^,]+)$/", $sName, $aMatches))
		{
			// transform "doe, john" into "john doe"
			$sName = $aMatches[2].' '.$aMatches[1];
		}
		
		// Warning: the line below generates incorrect utf-8 for the character 'é' when running on Windows/PHP 5.3.6
		//$sName = ucwords(strtolower($sName)); // Even prettier: make each first letter of each word - and only them - upper case
		return $sName;
	}
	
	public function SendAsAttachment($sTo, $sFrom, $sSubject, $sTextMessage)
	{
  		$oEmail = new Email();
  		$oEmail->SetRecipientTO($sTo);
  		$oEmail->SetSubject($sSubject);
  		$oEmail->SetBody($sTextMessage, 'text/html');
  		// Turn the original message into an attachment
  		$sAttachment = 	$this->sRawContent;
  		$oEmail->AddAttachment($sAttachment, 'Original-Message.eml', 'text/plain'); // Using the appropriate MimeType (message/rfc822) causes troubles with Thunderbird

  		$aIssues = array();
  		$oEmail->SetRecipientFrom($sFrom);
  		$oEmail->Send($aIssues, true /* bForceSynchronous */, null /* $oLog */);
	}
	
	protected function ParseMessageId($sMessageId)
	{
		$aMatches = array();
		$ret = false;
		if (preg_match('/^<iTop_(.+)_([0-9]+)_.+@.+openitop\.org>$/', $sMessageId, $aMatches))
		{
			$ret = array('class' => $aMatches[1], 'id' => $aMatches[2]);
		}
		return $ret;
	}
	
	/**
	 * Find-out (by analyzing the headers) if the message is related to an iTop object
	 * @return mixed Either the related object or null if none
	 */
	protected function GetRelatedObject()
	{
		if (!class_exists('MetaModel')) return null;
		// First look if the message is not a direct reply to a message sent by iTop
		if ($this->GetHeader('in-reply-to') != '')
		{
			$ret = $this->ParseMessageId($this->GetHeader('in-reply-to'));
			if ($ret !== false)
			{
				if (MetaModel::IsValidClass($ret['class']))
				{
					$oObject = MetaModel::GetObject($ret['class'], $ret['id'], false /* Caution the object may not exist */);
					if ($oObject != null) return $oObject;
				}
			}
		}

		// Second chance, look if a message sent by iTop is listed in the references
		$sReferences = $this->GetHeader('references');
		$aReferences = explode(' ', $sReferences );
		foreach($aReferences as $sReference)
		{
			$ret = $this->ParseMessageId($sReference);
			if ($ret !== false)
			{
				if (MetaModel::IsValidClass($ret['class']))
				{
					$oObject = MetaModel::GetObject($ret['class'], $ret['id'], false /* Caution the object may not exist */);
					if ($oObject != null) return $oObject;
				}
			}
		}
		
		// Third attempt: check the MS thread-index header, either via a direct pattern match
		// or by finding a similar message already processed
		// return EmailReplica::FindTicketFromMSThreadIndex($sMSThreadIndex);
		return null;
	}
}