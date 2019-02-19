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

/**
 * Read messages from an IMAP mailbox using PHP's IMAP extension
 * Note: in theory PHP IMAP methods can also be used to connect to
 *       a POP3 mailbox, but in practice the missing emulation of
 *       actual unique identifiers (UIDLs) for the messages makes
 *       this unusable for our particular purpose
 */
class IMAPEmailSource extends EmailSource
{
	protected $rImapConn = null;
	protected $sLogin = '';
	protected $sMailbox = '';

	public function __construct($sServer, $iPort, $sLogin, $sPwd, $sMailbox, $aOptions)
	{
		parent::__construct();
		$this->sLastErrorSubject = '';
		$this->sLastErrorMessage = '';
		$this->sLogin = $sLogin;
		$this->sMailbox = $sMailbox;

		$sOptions = '';
		if (count($aOptions) > 0)
		{
			$sOptions = '/'.implode('/',$aOptions);
		}
		
		if (!function_exists('imap_open')) throw new Exception('The imap_open function is missing. Did you forget to install the PHP module "IMAP" on the server?');

		$sIMAPConnStr = "{{$sServer}:{$iPort}$sOptions}$sMailbox";
		$this->rImapConn = imap_open($sIMAPConnStr, $sLogin, $sPwd );
		if ($this->rImapConn === false)
		{
			if (class_exists('EventHealthIssue'))
			{
				EventHealthIssue::LogHealthIssue('jb-email-synchro', "Cannot connect to IMAP server: '$sIMAPConnStr', with credentials: '$sLogin'/'$sPwd'");
			}
			print_r(imap_errors());
			throw new Exception("Cannot connect to IMAP server: '$sIMAPConnStr', with credentials: '$sLogin'/'$sPwd'");
		}
	}	

	/**
	 * Get the number of messages to process
	 * @return integer The number of available messages
	 */
	public function GetMessagesCount()
	{
		$oInfo = imap_check($this->rImapConn);
		if ($oInfo !== false) return $oInfo->Nmsgs;
		
		return 0;	
	}
	
	/**
	 * Retrieves the message of the given index [0..Count]
	 * @param $index integer The index between zero and count
	 * @return MessageFromMailbox|null
	 */
	public function GetMessage($index)
	{
		
		// Experiment: catch error notices.
		// PHP Notice:  Unknown: [CLOSED] IMAP connection broken (server response) (errflg=2) in Unknown on line 0
		// PHP Notice:  Unknown: [CLOSED] IMAP connection lost (errflg=2) in Unknown on line 0
		// PHP Notice:  Unknown: Server Unavailable. 15 (errflg=2) in Unknown on line 0
		// Return NULL if anything goes wrong. This reset may need tweaking; perhaps adjust this later to look for specific errors.
		error_clear_last();
		
		$sRawHeaders = imap_fetchheader($this->rImapConn, 1+$index);
		$sBody = imap_body($this->rImapConn, 1+$index, FT_PEEK);
		$aOverviews = imap_fetch_overview($this->rImapConn, 1+$index);
		
		// No errors should have occurred
		if(!is_null(error_get_last())) {
			// Error occurred
			$this->Trace("Error(s) occurred in " . __METHOD__ . "() -> " . json_encode(error_get_last()));
			return null;
		}
		
		$oOverview = array_pop($aOverviews);

		$bUseMessageId = (bool) MetaModel::GetModuleSetting('jb-email-synchro', 'use_message_id_as_uid', false);
		if ($bUseMessageId)
		{
			$oOverview->uid = $oOverview->message_id;
		}

		
		
		return new MessageFromMailbox($oOverview->uid, $sRawHeaders, $sBody);
	}

	/**
	 * Deletes the message of the given index [0..Count] from the mailbox
	 * @param $index integer The index between zero and count
	 */
	public function DeleteMessage($index)
	{
		$ret = imap_delete($this->rImapConn, (1+$index).':'.(1+$index));
		return $ret;
	}
	
	/**
	 * Name of the eMail source
	 */
	 public function GetName()
	 {
	 	return $this->sLogin;
	 }

	/**
	 * Mailbox path of the eMail source
	 */
	public function GetMailbox()
	{
		return $this->sMailbox;
	}

	/**
	 * Get the list (with their IDs) of all the messages
	 * @return Array An array of hashes: 'msg_id' => index 'uild' => message identifier
	 */
	 public function GetListing()
	 {
	 	$ret = null;
	 	
	 	$oInfo = imap_check($this->rImapConn);
        if (($oInfo !== false) && ($oInfo->Nmsgs > 0))
		{
        	$sRange = "1:".$oInfo->Nmsgs;
		// Workaround for some email servers (like gMail!) where the UID may change between two sessions, so let's use the
		// MessageID as a replacement for the UID.
		// Note that it is possible to receive two times a message with the same MessageID, but since the content of the message
		// will be the same, it's safe to process such messages only once...
		// BEWARE: Make sure that you empty the mailbox before toggling this setting in the config file, since all the messages
		// present in the mailbox at the time of the toggle will be considered as "new" and thus processed again.
        	$bUseMessageId = (bool)MetaModel::GetModuleSetting('jb-email-synchro', 'use_message_id_as_uid', false);

        	$ret = array();
			$aResponse = imap_fetch_overview($this->rImapConn,$sRange);
			
			foreach ($aResponse as $aMessage)
			{
				if ($bUseMessageId)
				{
					$ret[] = array('msg_id' => $aMessage->msgno, 'uidl' => $aMessage->message_id);
				}
				else
				{
					$ret[] = array('msg_id' => $aMessage->msgno, 'uidl' => $aMessage->uid);
				}
			}
        }
        
		return $ret;
	 }
	 
	 public function Disconnect()
	 {
	 	imap_close($this->rImapConn, CL_EXPUNGE);
	 	$this->rImapConn = null; // Just to be sure
	 }
}