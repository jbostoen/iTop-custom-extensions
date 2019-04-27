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

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

/**
 * @param \iTopWebPage $oPage
 * @param $sUIDL
 *
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 */
function GetMessageDetails($oPage, $sUIDL)
{
	$oReplicaSearch = new DBObjectSearch('EmailReplica');
	$oReplicaSearch->AddCondition('uidl', $sUIDL);
	$oReplicaSet = new DBObjectSet($oReplicaSearch);
	$oReplica = $oReplicaSet->Fetch();
	if (empty($oReplica))
	{
		return;
	}

	$oPage->set_title(Dict::S('MailInbox:MessageDetails'));
	$oPage->add('<h2>'.Dict::S('MailInbox:MessageDetails').'</h2>');

		// Display the eml link
	$iDocId = $oReplica->GetKey();
	/** @var \ormDocument $oDoc */
	$oDoc = $oReplica->Get('contents');
	if (!$oDoc->IsEmpty())
	{
		$sDownloadURL = $oDoc->GetDownloadURL('EmailReplica', $iDocId, 'contents');
		$oPage->add('<h3><div class="attachment" id="display_attachment_'.$iDocId.'"><a href="'.$sDownloadURL.'">'.Dict::S('MailInbox:DownloadEml').'</a></div></h3>');
	}

	$aList = array('message_date', 'status', 'error_message', 'error_trace');
	$aValues = array();
	foreach($aList as $sAttCode)
	{
		$aValues[$sAttCode] = array('label' => MetaModel::GetLabel(get_class($oReplica), $sAttCode), 'value' => $oReplica->GetAsHTML($sAttCode));
	}
	$oPage->details($aValues);

}

try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(true /* bMustBeAdmin */, false /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed

	$oPage = new iTopWebPage("");

	$sOperation = utils::ReadParam('operation', '');
	switch($sOperation)
	{
		case 'message_details':
			$sUIDL = utils::ReadParam('sUIDL', 0, false, 'raw_data');
			GetMessageDetails($oPage, $sUIDL);
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