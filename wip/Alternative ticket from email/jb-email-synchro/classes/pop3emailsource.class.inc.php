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
 * Reads messages from a POP3 source
 */
class POP3EmailSource extends EmailSource
{
	protected $oPop3 = null;
	protected $sServer = '';
	protected $sLogin = '';
	
	public function __construct($sServer, $iPort, $sLogin, $sPwd, $authOption = true)
	{
		parent::__construct();
		$this->sLastErrorSubject = '';
		$this->sLastErrorMessage = '';
		
		require_once(dirname(__FILE__).'/net_pop3.class.inc.php'); //Include this file only if needed since PEAR desactivates the error reporting

		if(!class_exists('Net_POP3')) throw new Exception('The class Net_POP3 is missing. Did you forget to install the PEAR prerequisistes on the server?');

		$this->oPop3 = new Net_POP3();
		$this->sServer = $sServer;
		$this->sLogin = $sLogin;
		$bRet = $this->oPop3->connect($sServer, $iPort);
		if ($bRet !== true)
		{
			if (class_exists('EventHealthIssue'))
			{
				EventHealthIssue::LogHealthIssue('jb-email-synchro', "Cannot connect to POP3 server: '$sServer' on port $iPort");
			}
			throw new Exception("Cannot connect to $sServer on port $iPort");
		}
		
		$bRet = $this->oPop3->login($sLogin, $sPwd, $authOption);
		if ($bRet !== true)
		{
			if (class_exists('EventHealthIssue'))
			{
				EventHealthIssue::LogHealthIssue('jb-email-synchro', "Cannot login on server '$sServer' using '$sLogin' with pwd: $sPwd");
			}
			throw new Exception("Cannot login using $sLogin with pwd: $sPwd");
		}
	}	

	/**
	 * Get the number of messages to process
	 * @return integer The number of available messages
	 */
	public function GetMessagesCount()
	{
		return $this->oPop3->numMsg();	
	}
	
	/**
	 * Retrieves the message of the given index [0..Count]
	 * @param $index integer The index between zero and count
	 * @return EmailDecoder
	 */
	public function GetMessage($index)
	{
		$sRawHeaders = $this->oPop3->getRawHeaders(1+$index);
		$sBody = $this->oPop3->getBody(1+$index);
		$aUIDL = $this->oPop3->_cmdUidl(1+$index);
		
		return new MessageFromMailbox($aUIDL['uidl'], $sRawHeaders, $sBody);
	}

	/**
	 * Deletes the message of the given index [0..Count] from the mailbox
	 * @param $index integer The index between zero and count
	 */
	public function DeleteMessage($index)
	{
		$ret = $this->oPop3->deleteMsg(1+$index);
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
	 * Get the list (with their IDs) of all the messages
	 * @return Array An array of hashes: 'msg_id' => index 'uild' => message identifier
	 */
	 public function GetListing()
	 {
		$ret = $this->oPop3->_cmdUidl();
		if ($ret == null)
		{
			$ret = array();
		}
		return $ret;
	 }
	 
	 public function Disconnect()
	 {
	 	$this->oPop3->disconnect();
	 }
}
