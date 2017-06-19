<?php
// Copyright (C) 2013 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * Processing of AJAX calls for the CalendarView
 *
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');

function GetMailboxContent($oPage, $oInbox)
{
	if(is_object($oInbox))
	{
		$iStartIndex = utils::ReadParam('start', 0);
		$iMaxCount = utils::ReadParam('count', 10);
		$iMsgCount = 0;
		try
		{
			$oSource = $oInbox->GetEmailSource();
			$iTotalMsgCount = $oSource->GetMessagesCount();
			$iStart = min($iStartIndex, $iTotalMsgCount);
			$iEnd = min($iStart + $iMaxCount, $iTotalMsgCount);
			$iMsgCount = $iEnd - $iStart;
			$aMessages = $oSource->GetListing();
		}
		catch(Exception $e)
		{
			$oPage->p("Failed to initialize the mailbox: ".$oInbox->GetName().". Reason: ".$e->getMessage());
		}
					
		$iProcessedCount = 0;
		if ($iMsgCount > 0)
		{
			// Get the corresponding EmailReplica object for each message
			$aUIDLs = array();
			for($iMessage = 0; $iMessage < $iTotalMsgCount; $iMessage++)
			{
				// Assume that EmailBackgroundProcess::IsMultiSourceMode() is always set to true
				$aUIDLs[] = $oSource->GetName().'_'.$aMessages[$iMessage]['uidl'];
			}
			$sOQL = 'SELECT EmailReplica WHERE uidl IN ('.implode(',', CMDBSource::Quote($aUIDLs)).') AND mailbox_path = ' . CMDBSource::Quote($oInbox->Get('mailbox'));
			$oReplicaSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
			$oReplicaSet->OptimizeColumnLoad(array('EmailReplica' => array('uidl', 'ticket_id', 'status', 'error_message')));
			$iProcessedCount = $oReplicaSet->Count();
			$aProcessed = array();
			while($oReplica = $oReplicaSet->Fetch())
			{
				$aProcessed[$oReplica->Get('uidl')] = array(
						'status' => $oReplica->Get('status'),
						'ticket_id' => $oReplica->Get('ticket_id'),
						'error_message' => $oReplica->Get('error_message'),
				);
			}
			
			$aTableConfig = array(
				'checkbox' => array('label' => '<input type="checkbox" id="mailbox_checkall"/>', 'description' => ''),
				'status' => array('label' => Dict::S('MailInbox:Status'), 'description' => ''),
				'date' => array('label' => Dict::S('MailInbox:Date'), 'description' => ''),
				'from' => array('label' => Dict::S('MailInbox:From'), 'description' => ''),
				'subject' => array('label' => Dict::S('MailInbox:Subject'), 'description' => ''),
				'ticket' => array('label' =>  Dict::S('MailInbox:RelatedTicket'), 'description' => ''),
				'error' => array('label' =>  Dict::S('MailInbox:ErrorMessage'), 'description' => ''),
			);

			$aData = array();
			for($iMessage = $iStart; $iMessage < $iStart+$iMsgCount; $iMessage++)
			{
				$oRawEmail = $oSource->GetMessage($iMessage);
				$oEmail = $oRawEmail->Decode($oSource->GetPartsOrder());

				// Assume that EmailBackgroundProcess::IsMultiSourceMode() is always set to true
				$sUIDLs = $oSource->GetName().'_'.$aMessages[$iMessage]['uidl'];
				$sStatus = Dict::S('MailInbox:Status/New');
				$sLink = '';
				$sErrorMsg = '';
				if (array_key_exists($sUIDLs, $aProcessed))
				{
					$sStatus = ($aProcessed[$sUIDLs]['status'] == 'ok') ? Dict::S('MailInbox:Status/Processed') : Dict::S('MailInbox:Status/Error');
					$sErrorMsg = $aProcessed[$sUIDLs]['error_message'];
					if ($aProcessed[$sUIDLs]['ticket_id'] != '')
					{
						$sTicketUrl = ApplicationContext::MakeObjectUrl($oInbox->Get('target_class'), $aProcessed[$sUIDLs]['ticket_id']);
						$sLink = '<a href="'.$sTicketUrl.'">'.$oInbox->Get('target_class').'::'.$aProcessed[$sUIDLs]['ticket_id'].'</a>';
					}
				}
				$aData[] = array('checkbox' => '<input type="checkbox" class="mailbox_item" value="'.htmlentities($sUIDLs, ENT_QUOTES, 'UTF-8').'"/>', 'status' => $sStatus, 'date' => $oEmail->sDate, 'from' => $oEmail->sCallerEmail, 'subject' => $oEmail->sSubject, 'ticket' => $sLink, 'error' => $sErrorMsg);
			}
			$oPage->p(Dict::Format('MailInbox:Z_DisplayedThereAre_X_Msg_Y_NewInTheMailbox', $iMsgCount, $iTotalMsgCount, ($iTotalMsgCount - $iProcessedCount)));					
			$oPage->table($aTableConfig, $aData);
			$oPage->add('<div><img src="../images/tv-item-last.gif" style="vertical-align:bottom;margin-left:10px;"/>&nbsp;'.Dict::S('MailInbox:WithSelectedDo').'&nbsp;&nbsp<button class="mailbox_button" id="mailbox_reset_status">'.Dict::S('MailInbox:ResetStatus').'</button>&nbsp;&nbsp;<button class="mailbox_button" id="mailbox_delete_messages">'.Dict::S('MailInbox:DeleteMessage').'</button></div>');
		}
		else
		{
			$oPage->p(Dict::Format('MailInbox:EmptyMailbox'));					
		}
	}
	else
	{
		$oPage->P(Dict::S('UI:ObjectDoesNotExist'));
	}	
}

/**
 * Finds the index of the message with the given UIDL identifier
 * @param Hash $aMessages The array returned by $oSource->GetListing()
 * @param string $sUIDL The UIDL to find
 * @param EmailSource $oSource
 * @return integer The index of the message or false if not found
 */
function FindMessageIDFromUIDL($aMessages, $sUIDL, EmailSource $oSource)
{
	$sKey = $sUIDL;
	$sMultiSourceKey = substr($sUIDL, 1+strlen($oSource->GetName())); // in Multisource mode the name of the source plus _ are prepended to the UIDL
	foreach($aMessages as $aData)
	{
		if ((strcmp($sKey, $aData['uidl']) == 0) || (strcmp($sMultiSourceKey, $aData['uidl']) == 0))
		{
			return $aData['msg_id'] - 1; // return a zero based index
		}
	}
	return false;
}

try
{
	require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(true /* bMustBeAdmin */, false /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed
	
	$oPage = new ajax_page("");
	$oPage->no_cache();

	$sOperation = utils::ReadParam('operation', '');
	$iMailInboxId = utils::ReadParam('id', 0, false, 'raw_data');
	$oInbox = MetaModel::GetObject('MailInboxBase', $iMailInboxId, false);
	
	switch($sOperation)
	{
		case 'mailbox_content':
		GetMailboxContent($oPage, $oInbox);
		break;

		case 'mailbox_reset_status':
		case 'mailbox_delete_messages':
		$aUIDLs = utils::ReadParam('aUIDLs', array(), false, 'raw_data');
		if (count($aUIDLs) > 0)
		{
			$sOQL = 'SELECT EmailReplica WHERE uidl IN ('.implode(',', CMDBSource::Quote($aUIDLs)).')';
			$oReplicaSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
			$oReplicaSet->OptimizeColumnLoad(array('EmailReplica' => array('uidl')));
			$aReplicas = array();
			while($oReplica = $oReplicaSet->Fetch())
			{
				$aReplicas[$oReplica->Get('uidl')] = $oReplica;
			}
			if ($sOperation == 'mailbox_delete_messages')
			{
				// Delete the actual email from the mailbox
				$oSource = $oInbox->GetEmailSource();
				$aMessages = $oSource->GetListing();
			}
			foreach($aUIDLs as $sUIDL)
			{
				if(array_key_exists($sUIDL, $aReplicas))
				{
					// A replica exists for the given email, let's remove it
					$aReplicas[$sUIDL]->DBDelete();
				}
				if ($sOperation == 'mailbox_delete_messages')
				{
					$idx = FindMessageIDFromUIDL($aMessages, $sUIDL, $oSource);
					if ($idx !== false)
					{
						// Delete the actual email from the mailbox
						$oSource->DeleteMessage($idx);				
					}
				}			
			}
			if ($sOperation == 'mailbox_delete_messages')
			{
				$oSource->Disconnect();
			}
		}
		GetMailboxContent($oPage, $oInbox);
		break;
	}
	$oPage->output();
}
catch(Exception $e)
{	
	$oPage->SetContentType('text/html');
	$oPage->add($e->getMessage());
	$oPage->output();
}