<?php
// Copyright (C) 2014 TeemIp
//
//   This file is part of TeemIp.
//
//   TeemIp is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   TeemIp is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with TeemIp. If not, see <http://www.gnu.org/licenses/>

/**
 * @copyright   Copyright (C) 2014 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
/*****************************************
 * Displays choices related to operation.
 */			
function DisplayOperationForm(WebPage $oP, $oAppContext, $sOperation, $sClass, $oObj, $aDefault = array())
{
	$id = $oObj->GetKey();
	$sClassLabel = MetaModel::GetName($sClass);
	$sUIPath = $oObj->MakeUIPath($sOperation);

	// Make sure action can be performed
	$CheckOperation = $oObj->DoCheckOperation($sOperation);
	if ($CheckOperation != '')
	{
		// Found issues: explain and display block again
		// No search bar (2.5 standard)

		$sIssueDesc = Dict::Format($sUIPath.$CheckOperation);
		cmdbAbstractObject::SetSessionMessage($sClass, $id, $sOperation, $sIssueDesc, 'error', 0, true /* must not exist */);
		$oObj->DisplayDetails($oP);
		
//		$oP->add(Dict::Format($sUIPath.$CheckOperation)."<br><br>");
//		$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"window.history.back()\">&nbsp;&nbsp;");
		return;
	}
	
	// Set page titles
	SetPageTitles($oP, $sUIPath, $oObj, $sClassLabel);
			
	// Set blue modification frame 
	$oP->add("<div class=\"wizContainer\">\n");
			
	// Preparation to allow new values to be posted
	$aFieldsMap = array();
	$sPrefix = '';
	$m_iFormId = $oObj->GetNewFormId($sPrefix);
	$iTransactionId = utils::GetNewTransactionId();
	$oP->SetTransactionId($iTransactionId);
	$sFormAction= utils::GetAbsoluteUrlModulesRoot()."/teemip-ip-mgmt/ui.teemip-ip-mgmt.php";
	$oP->add("<form action=\"$sFormAction\" id=\"form_{$m_iFormId}\" enctype=\"multipart/form-data\" method=\"post\" onSubmit=\"return OnSubmit('form_{$m_iFormId}');\">\n");
	$oP->add_ready_script("$(window).unload(function() { OnUnload('$iTransactionId') } );\n");

	if (($sOperation != 'findspace') && ($sOperation != 'listips') && ($sOperation != 'csvexportips') && ($sOperation != 'calculator') && ($sOperation != 'delegate') && ($sOperation != 'undelegate')  && ($sOperation != 'allocateip') && ($sOperation != 'unallocateip'))
	{
		// Display main tab
		$oP->AddTabContainer(OBJECT_PROPERTIES_TAB);
		$oP->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		
		// Display object attributes
	    $oObj->DisplayMainAttributesForOperation($oP, $sOperation, $m_iFormId, $sPrefix, $aDefault);

		// Display tab for global parameters
		$oObj->DisplayGlobalAttributesForOperation($oP, $aDefault);
		
		$oP->SetCurrentTab('');
	}
	
	// Display action fields
	$oObj->DisplayActionFieldsForOperation($oP, $sOperation, $m_iFormId, $aDefault);
	
	// Load other parameters to post
	$sNextOperation = $oObj->GetNextOperation($sOperation);
	$oP->add($oAppContext->GetForForm());
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");	
	$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
	$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"$iTransactionId\">\n");
	$oP->add("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	
	$oP->add('</form>');
	$oP->add("</div>\n");
	
	$iFieldsCount = count($aFieldsMap);
	$sJsonFieldsMap = json_encode($aFieldsMap);
	$sState = $oObj->GetState();
	$oP->add_script(
<<<EOF
	// Create the object once at the beginning of the page...
	var oWizardHelper$sPrefix = new WizardHelper('$sClass', '$sPrefix', '$sState');
	oWizardHelper$sPrefix.SetFieldsMap($sJsonFieldsMap);
	oWizardHelper$sPrefix.SetFieldsCount($iFieldsCount);
EOF
);

}

/*****************************************************************
 * 
 * Main user interface pages for IP Management module starts here
 *
 * ***************************************************************/
try
{
	if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
	if (!defined('APPROOT')) require_once(__DIR__.'/../../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/displayblock.class.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/wizardhelper.class.inc.php');
	
	$sLoginMessage = LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	$oAppContext = new ApplicationContext();
	
	// Start construction of page

	$oP = new iTopWebPage('');
	$oP->set_base(utils::GetAbsoluteUrlAppRoot().'pages/');
	
	// All the following actions use advanced forms that require more javascript to be loaded
	$oP->add_linked_script("../js/json.js");
	$oP->add_linked_script("../js/forms-json-utils.js");
	$oP->add_linked_script("../js/wizardhelper.js");
	$oP->add_linked_script("../js/wizard.utils.js");
	$oP->add_linked_script("../js/linkswidget.js");
	$oP->add_linked_script("../js/extkeywidget.js");
	
	
	$operation = utils::ReadParam('operation', '');
	switch($operation)
	{
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'displaylist':	// Display hierarchical tree for domain, blocks or subnets
			
			// $sName, $defaultValue = "", $bAllowCLI = false, $sSanitizationFilter = 'parameter'
			$sIDs = utils::ReadParam('ids', '');
			$sClass = utils::ReadParam('class', '', false, 'class');
			
			// Check if right parameters have been given
			if ( empty($sIDs))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'ids'));
			}
			
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			
			
			// @Combodo: after reading your 'todo', this should probably change in the future. (CMDBObjectSet) :)
			$sQuery = 'SELECT '.$sClass.' WHERE id IN ('.$sIDs.')';
			$oObjectSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sQuery));

			// Found the objects.
			// List with checkboxes (checked by default)
			// Also show a combobox with all Tickets. Default to first (oldest) one?
			

			// Set page header: 'Merge to one <Class object>'
			$sClassLabel = MetaModel::GetName($sClass);
			$oP->add("<p class=\"page-header\">\n");
			$oP->add(MetaModel::GetClassIcon($sClass, true) . ' ' . Dict::Format('UI:ObjectMerge:Title', $sClass));
			$oP->add("</p>\n");
			
			// Add list
			$oDisplayBlock = DisplayBlock::FromObjectSet(/* DBObjectSet */ $oObjectSet, /* $sStyle */ 'list');
			$oDisplayBlock->Display(/* WebPage */ $oP, /* $sId */ 1, /* $aExtraParams */ ['menu' => false]);

			// As of 2.6.1, iTop doesn't offer check boxes yet (also not when trying to select from the list and chose native 'delete').
			// Add translations
			$oP->add(
<<<EOF
			<form method="post">
				<input type="hidden" name="transaction_id" value="adm6874.tmp">
				<input type="hidden" name="operation" value="bulk_delete_confirmed">
				<input type="hidden" name="filter" value="[&quot;SELECT `IPv4Block` FROM IPv4Block AS `IPv4Block` WHERE (`IPv4Block`.`id` IN ('1'))&quot;,[],[]]">
				<input type="hidden" name="class" value="{$sClass}">
				<input type="hidden" name="selectObject[]" value="1">
				<input type="button" onclick="window.history.back();" value=" << Back ">
				<input type="submit" name="" value=" Delete ! ">
				<input type="hidden" name="c[menu]" value="{$sClass}">
			</form>
EOF
			);

			break; // End case displaytree
	

// Get rid of all this below

		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'findspace':	// Find space within a block or a subnet
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
						
			// Check if the object exists
			$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
			if (is_null($oObj))
			{
				$oP->set_title(Dict::S('UI:ErrorPageTitle'));
				$oP->P(Dict::S('UI:ObjectDoesNotExist'));
			}
			else
			{
				// The object can be read - Process request now
				DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
			}
		
			break; // End case findspace
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		

		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'cancel':	// An action was cancelled
		case 'displaylist':
		default: // Menu node rendering (templates)
			ApplicationMenu::LoadAdditionalMenus();
			$oMenuNode = ApplicationMenu::GetMenuNode(ApplicationMenu::GetMenuIndexById(ApplicationMenu::GetActiveNodeId()));
			if (is_object($oMenuNode))
			{
				$oMenuNode->RenderContent($oP, $oAppContext->GetAsHash());
				$oP->set_title($oMenuNode->GetLabel());
			}
		break;
		

		
	}
	$oP->output(); // Display the whole content now !
}

catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
	if ($e instanceof SecurityException)
	{
		$oP->add("<h1>".Dict::S('UI:SystemIntrusion')."</h1>\n");
	}
	else
	{
		$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");
	}	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	$oP->output();
	
	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			try
			{
				$oLog = new EventIssue();
				
				$oLog->Set('message', $e->getMessage());
				$oLog->Set('userinfo', '');
				$oLog->Set('issue', $e->GetIssue());
				$oLog->Set('impact', 'Page could not be displayed');
				$oLog->Set('callstack', $e->getTrace());
				$oLog->Set('data', $e->getContextData());
				$oLog->DBInsertNoReload();
			}
			catch(Exception $e)
			{
				IssueLog::Error("Failed to log issue into the DB");
			}
		}
		
		IssueLog::Error($e->getMessage());
	}
	
	// For debugging only
	//throw $e;
}

catch(Exception $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));	
	$oP->output();
	
	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			try
			{
				$oLog = new EventIssue();
				
				$oLog->Set('message', $e->getMessage());
				$oLog->Set('userinfo', '');
				$oLog->Set('issue', 'PHP Exception');
				$oLog->Set('impact', 'Page could not be displayed');
				$oLog->Set('callstack', $e->getTrace());
				$oLog->Set('data', array());
				$oLog->DBInsertNoReload();
			}
			catch(Exception $e)
			{
				IssueLog::Error("Failed to log issue into the DB");
			}
		}
		
		IssueLog::Error($e->getMessage());
	}
}
