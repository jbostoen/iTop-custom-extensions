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

/* Unused?
function GetToolkitMenu(WebPage $oPage, $oSet, $aExtraParams)
{
	$aActions = array();
	$sHtml = '<div class="itop_popup toolkit_menu" id="tk_'.$this->iListId.'"><ul><li><img src="../images/toolkit_menu.png"><ul>';
	utils::GetPopupMenuItems($oPage, iPopupMenuExtension::MENU_OBJLIST_TOOLKIT, $oSet, $aActions);
	$sHtml = $oPage->RenderPopupMenuItems($aActions);
	return $sHtml;
}
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
		
		case 'displaytree':	// Display hierarchical tree for domain, blocks or subnets
			$sClass = utils::ReadParam('class', '', false, 'class');
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if (($sClass != 'Domain') && ($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}

			// Display search bar
			$oSearch = new DBObjectSearch($sClass);
			$aParams = array('open' => true, 'table_id' => '1');
			$oBlock = new DisplayBlock($oSearch, 'search', false /* Asynchronous */, $aParams);
			$oBlock->Display($oP, 1);

			// Set titles
			$sClassLabel = MetaModel::GetName($sClass);
			$oP->set_title(Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':PageTitle_Class'));
			$oP->add("<p class=\"page-header\">\n");
			$oP->add(MetaModel::GetClassIcon($sClass, true)." ".Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':Title_Class', $sClassLabel));
			$oP->add("</p>\n");
			
			$oP->add('<div class="display_block">');
			
			// Get number of records
			$iCurrentOrganization = $oAppContext->GetCurrentValue('org_id');
			if ($iCurrentOrganization == '')
			{
				$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass"));
			}
			else
			{
				$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass AS c WHERE c.org_id = $iCurrentOrganization"));
			}
			$sObjectsCount = Dict::Format('UI:Pagination:HeaderNoSelection', $oSet->Count());
			
			// Get actions Menu
			$iListId = $oP->GetUniqueId(); 
			$oMenuBlock = new MenuBlock($oSet->GetFilter(), 'list');
			$sActionsMenu = $oMenuBlock->GetRenderContent($oP, array(), $iListId);
			
			// Get toolkit menu
			// Remove "Add To Dashboard" submenu
			$sHtml = '<div class="itop_popup toolkit_menu" id="tk_'.$iListId.'"><ul><li><img src="../images/toolkit_menu.png"><ul>';
			$aActions = array();	
			utils::GetPopupMenuItems($oP, iPopupMenuExtension::MENU_OBJLIST_TOOLKIT, $oSet, $aActions);
			unset($aActions['UI:Menu:AddToDashboard']);
			unset($aActions['UI:Menu:ShortcutList']);
			$sHtml .= $oP->RenderPopupMenuItems($aActions);
			$sToolkitMenu = $sHtml;
			
			// Display menu line
			$sHtml = "<table style=\"width:100%;\">";
			$sHtml .= "<tr><td class=\"pagination_container\">$sObjectsCount</td><td class=\"menucontainer\">$sToolkitMenu $sActionsMenu</td></tr>";
			$sHtml .= "</table>";
			$oP->Add($sHtml);
			
			// Dump Tree(s)
			$oP->add('<table style="width:100%"><tr><td colspan="2">');
			$oP->add('<div style="vertical-align:top;" id="tree">');
			if ($iCurrentOrganization == '')
			{
				$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT Organization"));
				while($oOrg = $oSet->Fetch())
				{
					$oP->add("<h2>".Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':OrgName', $oOrg->Get('name'))."</h2>\n");				
					DisplayTree ($oP, $oOrg->GetKey(), $sClass);
					$oP->add("<br>");
				}
			}
			else
			{
				$oOrg = MetaModel::GetObject('Organization', $iCurrentOrganization, false /* MustBeFound */);
				$oP->add("<h2>".Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':OrgName', $oOrg->Get('name'))."</h2>\n");								
				DisplayTree ($oP, $iCurrentOrganization, $sClass);
			}
			$oP->add('</td></tr></table>');
			$oP->add('</div></div>');
			$oP->add_ready_script("\$('#tree ul').treeview();\n");
		break; // End case displaytree
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'listspace':	// List occupied and unoccupied space within a block
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block'))
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
				$sClassLabel = MetaModel::GetName($sClass);

				// No search bar (2.5 standard)
				
				// Display action menu
				$oSingletonFilter = new DBObjectSearch($sClass);
				$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
				$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
				$oBlock->Display($oP, -1);
				
				// Set titles
				SetPageTitles($oP, 'UI:IPManagement:Action:ListSpace:'.$sClass.':', $oObj, $sClassLabel);
				
				// Dump space
				$oP->add('<table style="width:100%"><tr><td colspan="2">');
				$oP->add('<div style="vertical-align:top;" id="tree">');
				$oObj->DisplayAllSpace($oP);
				$oP->add('</td></tr></table>');
				$oP->add('</div></div>'); 
				$oP->add_ready_script("\$('#tree ul').treeview();\n");
			}
		break; // End case listspace
		
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
		
		case 'dofindspace':	// Apply find space action
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
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
				// Make sure we don't follow the same path twice in a row.
				$sClassLabel = MetaModel::GetName($sClass);
				if (!utils::IsTransactionValid($sTransactionId, false))
				{
					$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
				}
				else
				{
					$aPostedParam = $oObj->GetPostedParam($operation);
					
					// Make sure find action can be launched
					$sErrorString = $oObj->DoCheckToDisplayAvailableSpace($aPostedParam);
					if ($sErrorString != '')
					{
						// Found issues, explain and give the user another chance
						$sIssueDesc = Dict::Format('UI:IPManagement:Action:DoFindSpace:'.$sClass.':'.$sErrorString);
						$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
						$oP->add($sMessage);

						$sNextOperation = $oObj->GetNextOperation($operation);
						DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
					}
					else
					{
						// No search bar (2.5 standard)

						// Display action menu
						$oSingletonFilter = new DBObjectSearch($sClass);
						$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
						$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
						$oBlock->Display($oP, -1);
						
						// Set titles
						SetPageTitles($oP, 'UI:IPManagement:Action:DoFindSpace:'.$sClass.':', $oObj, $sClassLabel);
						
						// Dump space
						$oP->add('<table style="width:100%"><tr><td colspan="2">');
						$oP->add('<div style="vertical-align:top;" id="tree">');
						$oObj->DoDisplayAvailableSpace($oP, 0, $aPostedParam);
						$oP->add('</div></td></tr></table>');
						$oP->add('</div>');		 // ??
						$oP->add_ready_script("\$('#tree ul').treeview();\n");
						$oP->add("<div id=\"dialog_content\"/>\n");
					}
				}
			}
		break; // End case dofindspace
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'listips':	// List IPs of a subnet or an IP range
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet') && ($sClass != 'IPv4Range') && ($sClass != 'IPv6Range'))
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
				$iSize = $oObj->GetSize();
				if ($iSize >= MAX_NB_OF_IPS_TO_DISPLAY)
				{
					// Display subset of IPs only as size is too big to display all IPs once
					DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
				}
				else
				{
					// Display all IPs once
					$sClassLabel = MetaModel::GetName($sClass);

					// No search bar (2.5 standard)
					
					// Display action menu
					$oSingletonFilter = new DBObjectSearch($sClass);
					$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
					$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
					$oBlock->Display($oP, -1);
					
					// Set titles
					SetPageTitles($oP, 'UI:IPManagement:Action:ListIps:'.$sClass.':', $oObj, $sClassLabel);
					
					// Dump IP Tree
					$sStatusIp = $oObj->GetDefaultValueAttribute('status');
					$sParameter = array ('first_ip' => '', 'last_ip' => '', 'status_ip' => $sStatusIp, 'short_name' => '', 'domain_id' => '', 'usage_id' => '', 'requestor_id' => '');
					$oP->add('<table style="width:100%"><tr><td colspan="2">');
					$oP->add('<div style="vertical-align:top;" id="tree">');
					$oObj->DoListIps($oP, 0, $sParameter);
					$oP->add('</div></td></tr></table>');
					$oP->add('</div>');		 // ??
					$oP->add_ready_script("\$('#tree ul').treeview();\n");
					$oP->add("<div id=\"dialog_content\"/>\n");
				}
			}
		break; // End case Listips
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'dolistips':	// Apply list ips action
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet') && ($sClass != 'IPv4Range') && ($sClass != 'IPv6Range'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			// Check if the object exists
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);

			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);
				
				// Make sure range can be listed
				$sErrorString = $oObj->DoCheckToListIps($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:DoListIps:'.$sClass.':CannotBeListed', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);
					
					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
					}
				else
				{
					// No search bar (2.5 standard)
					
					// Display action menu
					$oSingletonFilter = new DBObjectSearch($sClass);
					$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
					$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
					$oBlock->Display($oP, -1);
					
					// Set titles
					SetPageTitles($oP, 'UI:IPManagement:Action:DoListIps:'.$sClass.':', $oObj, $sClassLabel);
					
					// Dump space
					$oP->add('<table style="width:100%"><tr><td colspan="2">');
					$oP->add('<div style="vertical-align:top;" id="tree">');
					$oObj->DoListIps($oP, 0, $aPostedParam);       
					$oP->add('</div></td></tr></table>');
					$oP->add('</div>');		 // ??
					$oP->add_ready_script("\$('#tree ul').treeview();\n");
					$oP->add("<div id=\"dialog_content\"/>\n");
				}
			}
		break; // End case dolistips
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'shrinkblock':		// Shrink a block
		case 'shrinksubnet':	// Shrink a subnet
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}
				
				// Process request now
				DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
			}
		break; // End case shrink
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'doshrinkblock':	// Apply shrink for a block
		case 'doshrinksubnet':	// Apply shrink for a subnet
			$sClass = utils::ReadPostedParam('class', '', 'class');
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
						
			// Object does exist. It has already been checked in action 'split' but check anyway.
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);
			
			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);
					
				// Make sure object can be shrunk
				$sErrorString = $oObj->DoCheckToShrink($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Shrink:'.$sClass.':CannotBeShrunk', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);
					
					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
				}
				else
				{
					// Set page titles
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Shrink:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					if ($sClass == 'IPv4Subnet')
					{
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Shrink:'.$sClass.':Done', $sClassLabel, $oObj->GetName(), $aPostedParam['scale_id'])."</h1>\n");
					}
					else
					{
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Shrink:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					}
					$oP->add("</div>\n");
					
					// Shrink block and display result
					$oSet = $oObj->DoShrink($aPostedParam);
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
					
					// Close transaction
					utils::RemoveTransaction($sTransactionId);
				}
			}
		break; // End case doshrink
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'splitblock':	// Split a block
		case 'splitsubnet':	// Split a subnet
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}
				
				// Process request now
				DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
			}
		break; // End case split
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'dosplitblock':		// Apply split for a block 
		case 'dosplitsubnet':	// Apply split for a subnet
			$sClass = utils::ReadPostedParam('class', '', 'class');
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			// Object does exist. It has already been checked in action 'split' but check anyway.
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);
			
			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);

 				// Make sure object can be split
				$sErrorString = $oObj->DoCheckToSplit($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Split:'.$sClass.':CannotBeSplit', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);
					
					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
				}
				else
				{
					// Set page titles
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Split:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					if ($sClass == 'IPv4Subnet')
					{
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Split:'.$sClass.':Done', $sClassLabel, $oObj->GetName(), $aPostedParam['scale_id'])."</h1>\n");
					}
					else
					{
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Split:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					}
					$oP->add("</div>\n");
					
					// Split block and display result
					$oSet = $oObj->DoSplit($aPostedParam);
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
					
					// Close transaction
					utils::RemoveTransaction($sTransactionId);
				}
			}
		break; // End case dosplit
				
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'expandblock':		// Expand a block	 
		case 'expandsubnet':	// Expand a subnet 
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}
				
				// Process request now
				DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
			}
		break; // End case expand
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'doexpandblock':	// Apply expand block command
		case 'doexpandsubnet':	// Apply expand a subnet
			$sClass = utils::ReadPostedParam('class', '', 'class');
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block') && ($sClass != 'IPv4Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			// Object does exist. It has already been checked in action 'expand' but check anyway.
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);
			
			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);
				
				// Make sure object can be expanded
				$sErrorString = $oObj->DoCheckToExpand($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Expand:'.$sClass.':CannotBeExpanded', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);
					
					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
				}
				else
				{
					// Set page titles
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Expand:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					if ($sClass == 'IPv4Subnet')
					{
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Expand:'.$sClass.':Done', $sClassLabel, $oObj->GetName(), $aPostedParam['scale_id'])."</h1>\n");
					}
					else
					{
						$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Expand:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					}
					$oP->add("</div>\n");
					
					// Expand block and display result
					$oSet = $oObj->DoExpand($aPostedParam);
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
					
					// Close transaction
					utils::RemoveTransaction($sTransactionId);
				}
			}
		break; // End case doexpand
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'csvexportips':	// Export IPs of a subnet or a range in csv window
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet') && ($sClass != 'IPv4Range') && ($sClass != 'IPv6Range'))
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
				$iSize = $oObj->GetSize();
				if ($iSize >= MAX_NB_OF_IPS_TO_DISPLAY)
				{
					// Export subset of IPs only as size is too big to export all IPs once
					DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
				}
				else
				{
					// Export all IPs once
					$sClassLabel = MetaModel::GetName($sClass);

					// No search bar (2.5 standard)
					
					// Display action menu
					$oSingletonFilter = new DBObjectSearch($sClass);
					$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
					$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
					$oBlock->Display($oP, -1);
					
					// Set titles
					SetPageTitles($oP, 'UI:IPManagement:Action:CsvExportIps:'.$sClass.':', $oObj, $sClassLabel);
					
					// Display text area
					$sParameter = array ('first_ip' => '', 'last_ip' => '');
					$oP->add("<div id=\"3\" class=\"display_block\">\n"); 
					$oP->add("<textarea>\n"); 
					$sHtml = $oObj->GetIPsAsCSV($sParameter);
					$oP->add($sHtml);
					$oP->add("</textarea>\n");
					$oP->add("</div>\n");
					
					// Adjust the size of the block
					$oP->add_ready_script(" $('#3>textarea').height($('#3').parent().height() - 220).width( $('#3').parent().width() - 30);");
				}
			}
		break; // End case csvexportips
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'docsvexportips':	// Apply csv export ips action
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet') && ($sClass != 'IPv4Range') && ($sClass != 'IPv6Range'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			// Check if the object exists
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);

			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);
				
				// Make sure range can be exported as csv
				$sErrorString = $oObj->DoCheckToCsvExportIps($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:DoCsvExportIps:'.$sClass.':CannotBeListed', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);
					
					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
				}
				else
				{
					// No search bar (2.5 standard)
					
					// Display action menu
					$oSingletonFilter = new DBObjectSearch($sClass);
					$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
					$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
					$oBlock->Display($oP, -1);
					
					// Set titles
					SetPageTitles($oP, 'UI:IPManagement:Action:DoCsvExportIps:'.$sClass.':', $oObj, $sClassLabel);
					
					// Display text area
					$oP->add("<div id=\"3\" class=\"display_block\">\n"); 
					$oP->add("<textarea>\n"); 
					$sHtml = $oObj->GetIPsAsCSV($aPostedParam);
					$oP->add($sHtml);
					$oP->add("</textarea>\n");
					$oP->add("</div>\n");
					
					// Adjust the size of the block
					$oP->add_ready_script(" $('#3>textarea').height($('#3').parent().height() - 220).width( $('#3').parent().width() - 30);");
				}
			}
		break; // End case docsvexportips
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'calculator':	// Provides IP related calculations
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if (($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			// Id may be null. In that case a temporary object is created.
			if ( empty($id))
			{
				$oObj = MetaModel::NewObject($sClass);
				$id = $oObj->GetKey();
			}
			else
			{
				// Check if the object exists
				$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
				if (is_null($oObj))
				{
					$oObj = MetaModel::NewObject($sClass);
					$id = $oObj->GetKey();
				}
			}
			
			// Display calculation page
			DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
		break; // End case calculator
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'docalculator':	// Calculates subnet parameters
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');

			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Subnet') && ($sClass != 'IPv6Subnet'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			if ($id > 0)
			{
				// Check if the object exists
				$oObj = MetaModel::GetObject($sClass, $id, false /* MustBeFound */);
				if (is_null($oObj))
				{
					$oObj = MetaModel::NewObject($sClass);
					$id = $oObj->GetKey();
				}
			}
			else
			{
				$oObj = MetaModel::NewObject($sClass);
				$id = $oObj->GetKey();
			}

			// Display calculator output
			$sClassLabel = MetaModel::GetName($sClass);
			$aPostedParam = $oObj->GetPostedParam($operation);
			
			// Check calculator inputs
			$sErrorString = $oObj->DoCheckCalculatorInputs($aPostedParam);
			if ($sErrorString != '')
			{
				// Found issues, explain and give the user another chance
				$sIssueDesc = Dict::Format('UI:IPManagement:Action:DoCalculator:'.$sClass.':CannotRun', $sErrorString);
				$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
				$oP->add($sMessage);
				
				$sNextOperation = $oObj->GetNextOperation($operation);
				DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
			}
			else
			{	
				if ($id > 0)
				{
					// No search bar (2.5 standard)
						
					// Display action menu
					$oSingletonFilter = new DBObjectSearch($sClass);
					$oSingletonFilter->AddCondition('id', $oObj->GetKey(), '=');
					$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
					$oBlock->Display($oP, -1);
				}
				
				// Set titles
				SetPageTitles($oP, 'UI:IPManagement:Action:DoCalculator:'.$sClass.':', $oObj, $sClassLabel);
	
				if ($id <= 0)
				{
					$iCurrentOrganization = $oAppContext->GetCurrentValue('org_id');

		 			// Get number of records
					if ($iCurrentOrganization == '')
					{
						$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass"));
					}
					else
					{
						$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass AS c WHERE c.org_id = $iCurrentOrganization"));
					}
					
					// Get actions Menu
					$iListId = $oP->GetUniqueId(); 
					$oMenuBlock = new MenuBlock($oSet->GetFilter(), 'list');
					$sActionsMenu = $oMenuBlock->GetRenderContent($oP, array(), $iListId);
					
					// Get toolkit menu
					// Remove "Add To Dashboard" submenu
					$sHtml = '<div class="itop_popup toolkit_menu" id="tk_'.$iListId.'"><ul><li><img src="../images/toolkit_menu.png"><ul>';
					$aActions = array();	
					utils::GetPopupMenuItems($oP, iPopupMenuExtension::MENU_OBJLIST_TOOLKIT, $oSet, $aActions);
					unset($aActions['UI:Menu:AddToDashboard']);
					unset($aActions['UI:Menu:ShortcutList']);
					$sHtml .= $oP->RenderPopupMenuItems($aActions);
					$sToolkitMenu = $sHtml;
					
					// Display menu line
					$sHtml = "<table style=\"width:100%;\">";
					$sHtml .= "<tr><td class=\"pagination_container\"></td><td class=\"menucontainer\">$sToolkitMenu $sActionsMenu</td></tr>";
					$sHtml .= "</table>";
					$oP->Add($sHtml);
				}

				// Display result
				$oObj->DisplayCalculatorOutput($oP, $aPostedParam);
				
			}
		break; // End case docalculator
		
		///////////////////////////////////////////////////////////////////////////////////////////
				
		case 'delegate':	// Delegates block to child organization
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}
				
				// Process request now
				DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
			}
		break; // End case delegate
		
		///////////////////////////////////////////////////////////////////////////////////////////

		case 'dodelegate':	// Apply delegate a block
			$sClass = utils::ReadPostedParam('class', '', 'class');
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
			
			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}
			
			// Object does exist. It has already been checked in action delegate but check anyway.
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);
			
			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);
				
				// Make sure object can be delegated
				$sErrorString = $oObj->DoCheckToDelegate($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Delegate:'.$sClass.':CannotBeDelegated', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);

					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
				}
				else
				{
					// Set page titles
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Delegate:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Delegate:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					$oP->add("</div>\n");
					
					// Delegate block and display result in details form
					$oSet = $oObj->DoDelegate($aPostedParam);
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
					
					// Close transaction
					utils::RemoveTransaction($sTransactionId);
				}
			}
		break; // End case dodelegate
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'undelegate':	// Delegates block to child organization
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if (($sClass != 'IPv4Block') && ($sClass != 'IPv6Block'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}
		
				// Make sure object can be undelegated
				$sErrorString = $oObj->DoCheckToUndelegate(array());
				if ($sErrorString != '')
				{
					// Found issues: explain and display block again					
					// No search bar (2.5 standard)

					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Undelegate:IPBlock:CannotBeUndelegated', $sErrorString);
					cmdbAbstractObject::SetSessionMessage($sClass, $id, 'undelegate', $sIssueDesc, 'error', 0, true /* must not exist */);
					$oObj->DisplayDetails($oP);
				}
				else
				{
					// Set page titles
					$sClassLabel = MetaModel::GetName($sClass);
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Undelegate:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Undelegate:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					$oP->add("</div>\n");
					
					// Undelegate block and display result in details form
					$oSet = $oObj->DoUndelegate(array());
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
				}
			}
		break; // End case undelegate

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'allocateip':	// Allocate existing IP (not already allocated) to an existing CI
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if (($sClass != 'IPv4Address') && ($sClass != 'IPv6Address'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}

				// Process request now
				DisplayOperationForm($oP, $oAppContext, $operation, $sClass, $oObj);
			}
			break; // End case allocateip

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'doallocateip':	// Apply allocate IP
			$sClass = utils::ReadPostedParam('class', '', 'class');
			$id = utils::ReadPostedParam('id', '');
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');

			// Check if right parameters have been given
			if ( empty($sClass) || empty($id))
			{
				throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
			}
			if (($sClass != 'IPv4Address') && ($sClass != 'IPv6Address'))
			{
				throw new ApplicationException(Dict::Format('UI:Error:WrongActionForClass', $operation, $sClass));
			}

			// Object does exist. It has already been checked in action allocate but check anyway.
			$oObj = MetaModel::GetObject($sClass, $id, true /* MustBeFound */);

			// Make sure we don't follow the same path twice in a row.
			$sClassLabel = MetaModel::GetName($sClass);
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				$oP->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
				$oP->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			else
			{
				$aPostedParam = $oObj->GetPostedParam($operation);

				// Make sure object can be delegated
				$sErrorString = $oObj->DoCheckToAllocate($aPostedParam);
				if ($sErrorString != '')
				{
					// Found issues, explain and give the user another chance
					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Allocate:'.$sClass.':CannotAllocateCI', $sErrorString);
					$sMessage = "<div class=\"header_message message_error teemip_message_error\">".$sIssueDesc."</div>";
					$oP->add($sMessage);

					$sNextOperation = $oObj->GetNextOperation($operation);
					DisplayOperationForm($oP, $oAppContext, $sNextOperation, $sClass, $oObj, $aPostedParam);
				}
				else
				{
					// Set page titles
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Allocate:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Allocate:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					$oP->add("</div>\n");

					// Delegate block and display result in details form
					$oSet = $oObj->DoAllocate($aPostedParam);
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));

					// Close transaction
					utils::RemoveTransaction($sTransactionId);
				}
			}
			break; // End case doallocateip

		///////////////////////////////////////////////////////////////////////////////////////////

		case 'unallocateip':	// Unallocate existing allocated IP from a CI
			$sClass = utils::ReadParam('class', '', false, 'class');
			$id = utils::ReadParam('id', '');
			// Check if right parameters have been given
			if ( empty($sClass))
			{
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if (($sClass != 'IPv4Address') && ($sClass != 'IPv6Address'))
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
				// The object can be read - Check now that user is allowed to modify it
				$oSet = CMDBObjectSet::FromObject($oObj);
				if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO)
				{
					throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $id));
				}

				// A revoir ->

				// Make sure object can be delegated
				$sErrorString = $oObj->DoCheckToUnallocate(array());
				if ($sErrorString != '')
				{
					// Found issues: explain and display block again
					// No search bar (2.5 standard)

					$sIssueDesc = Dict::Format('UI:IPManagement:Action:Unallocate:IPAddress:CannotBeUnallocated', $sErrorString);
					cmdbAbstractObject::SetSessionMessage($sClass, $id, 'unallocate', $sIssueDesc, 'error', 0, true /* must not exist */);
					$oObj->DisplayDetails($oP);
				}
				else
				{
					// Set page titles
					$sClassLabel = MetaModel::GetName($sClass);
					$oP->set_title(Dict::Format('UI:IPManagement:Action:Unallocate:'.$sClass.':PageTitle_Object_Class', $oObj->GetName(), $sClassLabel));
					$oP->add("<div class=\"page_header teemip_page_header\">\n");
					$oP->add("<h1>".$oObj->GetIcon()."&nbsp;".Dict::Format('UI:IPManagement:Action:Unallocate:'.$sClass.':Done', $sClassLabel, $oObj->GetName())."</h1>\n");
					$oP->add("</div>\n");

					// Undelegate block and display result in details form
					$oSet = $oObj->DoUnallocate(array());
					CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
				}
			}
			break; // End case unallocateip

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
