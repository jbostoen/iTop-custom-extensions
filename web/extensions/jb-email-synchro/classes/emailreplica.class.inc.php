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

class EmailReplica extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "requestmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "uidl",
			"state_attcode" => "",
			"reconc_keys" => array("message_id"),
			"db_table" => "email_replica",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			'indexes' => array(
					array('uidl'), // Index on UIDLs for faster search
			),		
		);
		MetaModel::Init_Params($aParams);

		MetaModel::Init_AddAttribute(new AttributeInteger("ticket_id", array("allowed_values" => null, "sql" => "ticket_id", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("uidl", array("allowed_values" => null, "sql" => "uidl", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("mailbox_path", array("allowed_values" => null, "sql" => "mailbox_path", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("message_id", array("allowed_values"=>null, "sql"=>"message_id", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("message_text", array("allowed_values"=>null, "sql"=>"message_text", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("references", array("allowed_values"=>null, "sql"=>"references", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("thread_index", array("allowed_values"=>null, "sql"=>"thread_index", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("message_date", array("allowed_values"=>null, "sql"=>"message_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('ok,error'), "sql"=>"status", "default_value"=>'ok', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("error_message", array("allowed_values"=>null, "sql"=>"error_message", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		
	}
	
	/**
	 * Generate an initial 'thread-index' header, compatible with MS Outlook
	 * and containing a reference to the iTop ticket it is related to
	 * @param $iTicketId integer The iD of the ticket
	 * @param $sTicketClass string The class of the ticket
	 * @return string The content of the thread-index header
	 */
	protected static function MakeMSThreadIndex($oObject)
	{
		// 'Thread-index' is a Microsoft specific heqder used by some versions (2003 / XP) of Outlook
		// instead of relying on the 'References' header. It is made of 27 bytes (random ??) which look
		// like a BASE64 string, and then for each new message in the thread 5 more 'base64-like' chars
		// are added at the end

		// Let's generate something that looks like a valid thread-index and can be decoded into a reference
		// to an iTop object. Since all thread-index I've seen seem to start with Ac... let's do it. Then
		// put iTop to sign it, then put the id of the ticket on 5 hex characters (zero padded) which allows up
		// to 1048575 tickets, then the name of the class, right-padded with zeroes to 16 characters !!
		// For example: AciTop000f100000UserRequest means UserRequest ticket id = 0xf1 = 241
		return sprintf("AciTop%05x%'0-16s", $oObject->GetKey(), get_class($oObject));
	}
	
	/**
	 * Get a valid Thread-index header for the ticket
	 * @param $iTicketId integer The identifier of the ticket
	 * @param $sTicketClass string The class of the ticket
	 * @return string The content of the thread-index header
	 */
	public static function GetNextMSThreadIndex($oTicket)
	{
		$oSearch = new DBObjectSearch('EmailReplica');
		$oSearch->AddCondition('ticket_id', $oTicket->GetKey());
		$oSet = new DBObjectSet($oSearch, array('message_date' => false));
		if ($oSet->Count() == 0)
		{
			$sThreadIndex = self::MakeMSThreadIndex($oTicket);
		}
		else
		{
			$oLatestReplica = $oSet->Fetch();
			$sLatestThreadIndex = $oLatestReplica->Get('thread_index');
			if ($sLatestThreadIndex == '')
			{
				$sThreadIndex = self::MakeMSThreadIndex($oTicket);
			}
			else
			{
				// The new index is obtained by appending 5 (random ?) base64 characters
				// at the end of the previous thread index
				$sThreadIndex = $sLatestThreadIndex.substr( base64_encode(sprintf('%010x', time())), 0, 5);
			}
		}
		return $sThreadIndex;
	}
	
	/**
	 * Find the ticket corresponding to the given MSThreadIndex either by decoding it
	 * or by finding an Emailreplica object in the same discussion thread
	 *
	 */
	public static function FindTicketFromMSThreadIndex($sMSThreadIndex)
	{
		$sShortIndex = substr($sMSThreadIndex, 0, 27);
		$oTicket = null;
		if (preg_match('/AciTop(-[0-9a-f]{5})(.{16})$/', $sShortIndex, $aMatches))
		{
			// Found a thread-index that seems generated by iTop
			$sClass = $aMatches[2];
			$iTicketId = $aMatches[1];
			if (MetaModel::IsValidClass($sClass))
			{
				$oTicket = MetaModel::GetObject($sClass, $iTicketId, false /* Caution the ticket may not exist */);
			}
		}
		if ($oTicket == null)
		{
			$oSearch = new DBObjectSearch('EmailReplica');
			$oSearch->AddCondition('thread_index', $sMSThreadIndex.'%', 'Like');
			$oSet = new DBObjectSet($oSearch, array('message_date' => false));
			if ($oSet->Count() != 0)
			{
				$oReplica = $oSet->Fetch();
				$iTicketId = $oReplica->Get('ticket_id');
				$oTicket = MetaModel::GetObject('Ticket', $iTicketId, false /* Caution the ticket may not exist */);
			}
		}
		return $oTicket;
	}
	
	public static function MakeReferencesHeader($sInitialMessageId, $oObject)
	{
		$sReferences = '';
		if ($sInitialMessageId != '')
		{
			$sReferences .= $sInitialMessageId.' ';
		}
		$sReferences .= self::MakeMessageId($oObject);
		return $sReferences;
	}
	
	public static function MakeMessageId($oObject)
	{
		$sMessageId = sprintf('<iTop_%s_%d_%f@%s.openitop.org>', get_class($oObject), $oObject->GetKey(), microtime(true /* get as float*/), MetaModel::GetConfig()->Get('session_name'));
		return $sMessageId;
	}
}
