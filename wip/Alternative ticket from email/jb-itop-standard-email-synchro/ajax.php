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

try
{
	require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(false /* bMustBeAdmin */, false /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed
	
	$oPage = new ajax_page("");
	$oPage->no_cache();

	$sOperation = utils::ReadParam('operation', '');
	$iMailInboxId = utils::ReadParam('id', 0, false, 'raw_data');
	
	switch($sOperation)
	{
		case 'debug_trace':
		$oInbox = MetaModel::GetObject('MailInboxBase', $iMailInboxId, false);
		if(is_object($oInbox))
		{
			if ($oInbox->Get('trace') == 'yes')
			{
				$oPage->add('<pre>'.$oInbox->Get('debug_trace').'</pre>');					
			}
			else
			{
				$oPage->p(Dict::Format('MailInboxStandard:DebugTraceNotActive'));					
			}
		}
		else
		{
			$oPage->P(Dict::S('UI:ObjectDoesNotExist'));
		}
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