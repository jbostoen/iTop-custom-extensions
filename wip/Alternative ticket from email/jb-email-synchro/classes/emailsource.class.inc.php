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
 * A source of messages either POP3, IMAP or File...
 */
abstract class EmailSource
{
	protected $sLastErrorSubject;
	protected $sLastErrorMessage;
	protected $sPartsOrder;
	protected $token;
	
	public function __construct()
	{
		$this->sPartsOrder = 'text/plain,text/html'; // Default value can be changed via SetPartsOrder
		$this->token  =null;
	}
	
	/**
	 * Get the number of messages to process
	 * @return integer The number of available messages
	 */
	abstract public function GetMessagesCount();
	
	/**
	 * Retrieves the message of the given index [0..Count]
	 * @param $index integer The index between zero and count
	 * @return EmailDecoder
	 */
	abstract public function GetMessage($index);

	/**
	 * Deletes the message of the given index [0..Count] from the mailbox
	 * @param $index integer The index between zero and count
	 */
	abstract public function DeleteMessage($index);
	
	/**
	 * Name of the eMail source
	 */
	abstract public function GetName();

	/**
	 * Mailbox path of the eMail source
	 */
	public function GetMailbox()
	{
		return '';
	}

	/**
	 * Disconnect from the server
	 */
	abstract public function Disconnect();

	public function GetLastErrorSubject()
	{
		return $this->sLastErrorSubject;
	}
	
	public function GetLastErrorMessage()
	{
		return $this->sLastErrorMessage;
	}
	
	/**
	 * Preferred order for retrieving the mail "body" when scanning a multiparts emails
	 * @param $sPartsOrder string A comma separated list of MIME types e.g. text/plain,text/html
	 */
	public function SetPartsOrder($sPartsOrder)
	{
		$this->sPartsOrder = $sPartsOrder;
	}
	/**
	 * Preferred order for retrieving the mail "body" when scanning a multiparts emails
	 * @return string A comma separated list of MIME types e.g. text/plain,text/html
	 */
	public function GetPartsOrder()
	{
		return $this->sPartsOrder;
	}
	/**
	 * Set an opaque reference token for use by the caller...
	 * @param mixed $token
	 */
 	public function SetToken($token)
 	{
 		$this->token = $token;
 	}
 	/**
 	 * Get the reference token set earlier....
 	 * @return mixed The token set by SetToken()
 	 */
 	public function GetToken()
 	{
 		return $this->token;
 	}
}
