<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * iTop custom operations to merge objects.
 * Heavily based on Combodo's TeemIP module for iTop.
 *
 */

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
	
	$sCustomOperation = utils::ReadParam('operation', '');
	
	// Module settings, defaults.
	$aModuleSettings = utils::GetCurrentModuleSetting('default', [
	
		// Implemented
		// --
		'attributes' => [
			// Attributes must be one of these types: 'AttributeCaseLog', 'AttributeLinkedSet', 'AttributeLinkedSetIndirect'
			// Attributes must be specified by their attribute code. If all attributes (of the above types) are allowed, set to ['*']
			'allowed' => ['contacts_list', 'functionalcis_list', 'private_log', 'public_log'],
			'default' => ['*']
		],
		
		'target_object' => [
			'add_callers_to_related_contacts' => true, // Add callers of the merged tickets to 'related Contacts' (could be used in Notification)
		],
		'target_object_selection' => [
			'order_by' => 'id',
			'order' => 'ascending', // 'ascending' or 'descending'
			'add_callers_to_related_contacts' => true, // Add callers of the merged tickets to 'related Contacts' (could be used in Notification)
		],
		
		// Ideas but not implemented
		// --
		
		// OQL Scope to limit objects which can be selected?
		'scope' => [
		],
		// Limit who can do this?
		'allowed_users' => [
			'profiles' => [],
			'scope' => '',
		],
		// Can user override default options?
		'allow_override_options' => true,		
		'merged_objects' => [
			'delete' => true, // Notification could be sent first. How can a placeholder be used?
			'set' => [
				'<attribute_name>' => 'could be used for writeback; have $targetObj placeholder'
			],
			'set_state' => 'closed', // Could be used to set a state
			'add_entries' => [
				'<CaseLog_attribute_name>' => 'Entry to insert; add targetObj placeholder'
			],
		],
	]); 
	
	// Module settings, class specific. In XML, most nodes seem to start with a non-capital.
	// Required 'class' parameter makes it easier
	$sClass = utils::ReadParam('class', '', false, 'class');
	if( empty($sClass) == false ) {
		if( utils::GetCurrentModuleSetting(strtolower($sClass), '') != '') {
			$aModuleSettings_class_specific = utils::GetCurrentModuleSetting($sClass, array());
			$aModuleSettings = array_replace_recursive($$aModuleSettings, $aModuleSettings_class_specific);
		}
	}
	
	// @todo Take safe approach like in iTop when deleting objects: user selects from list -> limited list with selection boxes is shown -> limited list without boxes is shown to confirm
	
	switch($sCustomOperation)
	{
		///////////////////////////////////////////////////////////////////////////////////////////
		
		case 'merge_list':	// Display hierarchical tree for domain, blocks or subnets
		
			// utils::ReadParam( $sName, $defaultValue = "", $bAllowCLI = false, $sSanitizationFilter = 'parameter' )
			$sClass = utils::ReadParam('class', '', false, 'class');
			$sFilter = utils::ReadParam('filter', '', false, 'raw_data'); // Updated after 'search', so the action menu should be able to pass it through
			
			// Check if right parameters have been given
			if ( empty($sClass)) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			
			if ( empty($sFilter)) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			
			// Not sure why, but also used in iTop's native modify/deletion procedure
			$oP->DisableBreadCrumb();
			
			// Set page header: 'Merge to one <Class object>'
			$sClassLabel = MetaModel::GetName($sClass);
			$oP->add("<p class=\"page-header\">\n");
			$oP->add(MetaModel::GetClassIcon($sClass, true) . ' ' . Dict::Format('UI:ObjectMerge:Title', $sClass));
			$oP->add("</p>\n");
			
			// @todo Find out if transaction_id is useful? This must be generated somewhere
			$oFilter = DBObjectSearch::unserialize($sFilter);
			// CMDBAbstractObject::DisplaySet() probably decides output order, not here.
			$oObjectSet = new CMDBObjectSet($oFilter);
			$iCountObjects = $oObjectSet->Count();
	
			if($iCountObjects < 2) {
				$oP->p(Dict::S('UI:Objectmerge:MultipleObjectsRequired'));
			}
			else {
				
				// Code below based on cmdbAbstractObject::DeleteObjects() (2.6.1)
				$oP->p(Dict::S('UI:Objectmerge:ConfirmCountObjectsOfClass'));
				$oP->p('<h1>'.Dict::Format('UI:Objectmerge:ConfirmCountObjectsOfClass', $iCountObjects, MetaModel::GetName($sClass)).'</h1>');

				$oP->add("<form method=\"post\">\n");
				
				$oP->add('<div id="0">');
				CMDBAbstractObject::DisplaySet($oP, $oObjectSet, array(
					'display_limit' => false, 
					'menu' => false, 
					'selection_mode' => true,
					'selection_type' => 'multiple'
				));
				$oP->add("</div>\n");
				
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"merge_show_options\">\n");
				$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8')."\">\n");
				$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
				
				
				$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
				$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:ObjectMerge:Button:Merge')."\">\n");
				$oP->add("<br>");
				$oAppContext = new ApplicationContext();
				$oP->add($oAppContext->GetForForm());
				$oP->add("</form>\n");

				
			}
			
			break; // End case displaytree

		case 'merge_show_options':
		
			// Not sure why, but also used in iTop's native modify/deletion procedure
			$oP->DisableBreadCrumb();
			
			// Inconsistency between transaction_id and selectObject comes from Combodo (~ object deletion)
			$sClass = utils::ReadParam('class', '', false, 'class');
			$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
			$sTransaction_id = utils::ReadParam('transaction_id', '', false, 'transaction_id');
			$aSelectedObjects = utils::ReadParam('selectObject', '', false, 'raw_data');
			
			// Check if right parameters have been given
			if( empty($sClass) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if( empty($sFilter) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			if( empty($sTransaction_id) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'transaction_id'));
			}
			if( empty($aSelectedObjects) || count($aSelectedObjects) < 2 ) {
				throw new ApplicationException(Dict::S('UI:ObjectMerge:WarningAtLeastTwoObjectsNeeded'));
			}
			
			// Temporary, move to next phase/blue form
			// Open blue modification frame 
			$oP->add("<div class=\"wizContainer\">\n");

			$oP->add("<form method=\"post\">\n");			
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".$sTransaction_id."\">\n");
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"merge_execute\">\n");
			$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".htmlentities($sFilter, ENT_QUOTES, 'UTF-8')."\">\n");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			
			
			// Don't forget: special classes like Attachments.
			// MetaModel won't known if this class has Attachments.
			// Guess, based on already-saved attachments.
			$oFilter = DBObjectSearch::unserialize($sFilter);
			// $oFilter = new DBObjectSearch($oFilter->GetClass());
			$oFilter->ResetCondition();
			$oFilter->AddCondition('id', $aSelectedObjects, 'IN');
			$aOrder[$oFilter->GetClassAlias().'.'.$aModuleSettings['target_object_selection']['order_by']] = ($aModuleSettings['target_object_selection']['order'] == 'ascending');
			$oObjectSet = new CMDBObjectSet($oFilter, $aOrder);
			$iCountObjects = $oObjectSet->Count();
			
			// Attachments?
			$oFilter_Attachments = new DBObjectSearch('Attachment');
			$oFilter_Attachments->AddCondition('item_class', $sClass, '='); // Not really necessary due to specified ids?
			$oFilter_Attachments->AddCondition('item_id', $aSelectedObjects, 'IN');
			$oObjectSet_Attachments = new CMDBObjectSet($oFilter_Attachments, ['Attachment.item_id' => true, 'Attachment.id' => true]);
			
			
			// Let user pick destination object
			$oP->add("<b>Merge to:</b><br>");
			$oP->add("<select name=\"target_object_id\">");
			
			$oObjectSet->Rewind();
			$aCachedFriendlyNames = [];
			while($oObj = $oObjectSet->Fetch()) {
				$oP->add("<option value=\"{$oObj->GetKey()}\">{$oObj->Get('friendlyname')} (ID {$oObj->GetKey()})</option>");
				$aCachedFriendlyNames[$oObj->GetKey()] = $oObj->Get('friendlyname');
			}
			
			$oP->add("</select>");
			
			$oP->add("<br>");
			$oP->add("<hr>");
			
			// Preliminary clean-up: use $oObjectSet, so only output valid IDs
			// Attributes
			// --
			foreach($aSelectedObjects as $iKey)
			{
				$oP->add("<input type=\"hidden\" name=\"selectObject[]\" value=\"".$iKey."\">\n");
			}
			$oP->add('<b>'.Dict::S('UI:ObjectMerge:AttributeOverview').':</b><br>');
			
			$oP->add("<ul>"); 
			
			
			// Which attributes? Free select or from predefined config?
			// @todo Extend this to offer choices
			$aAttributes = Metamodel::GetAttributesList($sClass);
			$aOption_attributes_allowed = $aModuleSettings['attributes']['allowed'];
			$aOption_attributes_default = $aModuleSettings['attributes']['default'];
			
			// Limit to allowed attributes for this class as specified in the configuration
			$aAttributes_allowed = ( count($aOption_attributes_allowed) == 1 && $aOption_attributes_allowed[0] == '*' ? $aAttributes : array_intersect($aAttributes, $aOption_attributes_allowed));
			$aAttributes_allowed = array_values($aAttributes_allowed);
		
			// Limit default checked attributes for this class
			$aOption_attributes_default = ( count($aOption_attributes_default) == 1 && $aOption_attributes_default[0] == '*' ? $aAttributes_allowed : array_intersect($aAttributes_allowed, $aOption_attributes_default));
			$aOption_attributes_default = array_values($aOption_attributes_default);
			
			// Limit to valid attribute types
			$aAttributes = array_filter($aAttributes_allowed, function($sAttribute) use ($sClass) {
				
				// Get attribute definition (to determine type)
				$oAttributeDef = Metamodel::GetAttributeDef($sClass, $sAttribute);
				 
				// AttributeLinkedSetIndirect (update links; set ticket_id to the destination's ticket_id if not duplicate; else delete)
				// AttributeCaselog is also likely to be interesting to merge.
				return in_array(get_class($oAttributeDef), ['AttributeCaseLog', 'AttributeLinkedSet', 'AttributeLinkedSetIndirect']);
	
			});
			
			// Sort alphabetically
			sort($aAttributes);
			
			// Change for output
			$aAttributes = array_map(function($sAttribute) use ($sClass, $aOption_attributes_default) {
				// Add checkbox
				$bChecked = ( (count($aOption_attributes_default) == 1 && $aOption_attributes_default[0] == '*') || in_array($sAttribute, $aOption_attributes_default) == true );
				return '<input type="checkbox" name="attributes_to_merge[]" value="'.$sAttribute.'"'.($bChecked == true ? ' checked' : '').'> ' . MetaModel::GetLabel($sClass, $sAttribute, /* bShowMandatory */ false) . ' ('. $sAttribute .')';
			}, $aAttributes);
			
			$oP->add(implode('<br>', $aAttributes).'<br><br>');
			
			$oP->add("</ul>"); 
			
			// Attachments
			// --
			if($oObjectSet_Attachments->CountExceeds(0) == true) {
				// Found at least one Attachment for this class
				$oP->add('<b>'.MetaModel::GetName('Attachment').'</b>');
				$oP->add('<ul>');
				
				while($oObj = $oObjectSet_Attachments->Fetch()) {
					// Derive from previously cached names, since it can't be known directly
					$oP->add("<li> {$aCachedFriendlyNames[$oObj->Get('item_id')]} (Attachment ID {$oObj->Get('id')}) </li>");
				}
				
				$oP->add('</ul>');
				
			}
			else {
				// No Attachment objects found
				$oP->add(Dict::S('UI:ObjectMerge:NoAttachmentsFound'));
			}
			
			
			
			// Add label to warn user that modifications caused by merging may trigger notifications
			$oP->add("<hr>"); 
			$oP->add('<p>'.Dict::S('UI:ObjectMerge:WarningModificationsMayTriggerNotifications').'</p>');
			
			$oP->add("<br>");
			
			$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
			$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:ObjectMerge:Button:Merge')."\">\n");
			$oAppContext = new ApplicationContext();
			$oP->add($oAppContext->GetForForm());
			$oP->add("</form>\n");
				
			// Close blue modification
			$oP->add("</div>");

			break;
			
		case 'merge_execute':
		
			// Not sure why, but also used in iTop's native modify/deletion procedure
			$oP->DisableBreadCrumb();
			
			// For now, get all the objects; process; DO NOT save or delete (testing phase)
			$sClass = utils::ReadParam('class', '', false, 'class');
			$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
			$sTransaction_id = utils::ReadParam('transaction_id', '', false, 'transaction_id');
			$aSelectedObjects = utils::ReadParam('selectObject', '', false, 'raw_data');
			$aAttributes_to_merge = utils::ReadParam('attributes_to_merge', '', false, 'raw_data');
			$iTarget_object_id = utils::ReadParam('target_object_id', '', false, 'integer');
			
			// Check if right parameters have been given
			if( empty($sClass) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
			}
			if( empty($sFilter) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'filter'));
			}
			if( empty($sTransaction_id) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'transaction_id'));
			}
			if( empty($aSelectedObjects) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'selectObject'));
			}
			if( empty($aAttributes_to_merge) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'attributes_to_merge'));
			}
			if( empty($iTarget_object_id) ) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'target_object_id'));
			}
			
			$sClassLabel = MetaModel::GetName($sClass);
			
			require_once( __DIR__ .'/core/ormcustomcaselog.class.inc.php' );
			// To consider later: ormCaseLog->FromJSON() starts NEW ormCaseLog (no other easy way to clear or re-arrange); must contain 'items'
			// For 2.6.1 create custom ormCaseLog which manipulates AddLogEntry() to support user_login and user_id. Also of use: GetAsArray()
			// Sort/order, then create new ormCaseLog
			// ormCaseLog
			 
			// $oLog = $oTicket->Get($sAttCode);
			// $oLog->AddLogEntry($sCaseLogEntry, $sCallerName);
			// $oTicket->Set($sAttCode, $oLog);
			
			// Get destination object
			$oTargetSearch = new DBObjectSearch($sClass);
			$oTargetSearch->AddCondition('id', $iTarget_object_id, '=');
			$oMonoObjectSet = new CMDBObjectSet($oTargetSearch);
			
			$oTargetObj = $oMonoObjectSet->Fetch();
			
			$oFilter = DBObjectSearch::unserialize($sFilter);
			$oFilter->AddCondition('id', $aSelectedObjects, 'IN');
			$oObjectSet = new CMDBObjectSet($oFilter);
			
			
			// Attributes allowed?
			// Which attributes? Free select or from predefined config?
			// @todo Extend this to offer choices
			$aAttributes = Metamodel::GetAttributesList($sClass);
			$aOption_attributes_allowed = $aModuleSettings['attributes']['allowed'];
			$aOption_attributes_default = $aModuleSettings['attributes']['default'];
			
			// Limit to allowed attributes for this class as specified in the configuration
			$aAttributes_allowed = ( count($aOption_attributes_allowed) == 1 && $aOption_attributes_allowed[0] == '*' ? $aAttributes : array_intersect($aAttributes, $aOption_attributes_allowed));
			$aAttributes_allowed = array_values($aAttributes_allowed);
		
			// Limit default checked attributes for this class
			$aAttributes_to_merge = array_intersect($aAttributes_allowed, $aAttributes_to_merge);
			
			// Special cases
			$aAttributes_to_merge[] = 'caller_id'; // To add caller of Ticket as related contact (lnkContactToTicket)
			$aAttributes_to_merge[] = 'org_id'; // To link Attachment objects to target ticket
			
			// Limit to valid attribute types
			$aAttributes_to_merge = array_filter($aAttributes_to_merge, function($sAttribute) use ($sClass) {
				
				// Get attribute definition (to determine type)
				$oAttributeDef = Metamodel::GetAttributeDef($sClass, $sAttribute);
				
				// AttributeExternalKey is added here to process 'caller_id'
				// AttributeLinkedSet (update objects)
				// AttributeLinkedSetIndirect (update links; set ticket_id to the destination's ticket_id if not duplicate; else delete)
				// AttributeCaselog is also likely to be interesting to merge.
				return in_array(get_class($oAttributeDef), ['AttributeCaseLog', 'AttributeExternalKey', 'AttributeLinkedSet', 'AttributeLinkedSetIndirect']);
	
			});
			
			// Process specified attributes
			// --
			
				foreach($aAttributes_to_merge as $sAttribute) {
					
					// Get attribute definition (to determine type)
					$oAttributeDef = Metamodel::GetAttributeDef($sClass, $sAttribute);
					
					switch(get_class($oAttributeDef)) {
						
						case 'AttributeCaseLog':
						
							$oAttributeValue = new ormCustomCaseLog();
						
							// Might have gone over the object set
							$oObjectSet->Rewind();
							
							// Loop over the object set
							while($oObj = $oObjectSet->Fetch()) {
							
								// ormCaseLog
								$oCaseLog = $oObj->Get($sAttribute);
								
								// GetForJSON() actually returns an array of each entry
								foreach($oCaseLog->GetAsArray() as $aEntry) {
									
									// CustomCaseLog's AddLogEntry remains flexible; keeps original user information and datetime
									$oAttributeValue->AddLogEntry( $aEntry['message_html'], $aEntry['user_login'], $aEntry['user_id'], $aEntry['date'] );
									
								}
								
							}
							
							// Sort chronologically. NOT ascending (true), but descending (false)!
							$oAttributeValue = $oAttributeValue->ToSortedCaseLog(false);
							
							$oTargetObj->Set($sAttribute, $oAttributeValue);
							break;
							
						case 'AttributeExternalKey':
						
							// Keep in mind this extension was written for subclasses of Ticket
							if($sAttribute == 'caller_id' && $aModuleSettings['target_object']['add_callers_to_related_contacts'] == true) {
								
								$aRelatedContacts_cache = [];
								$aRelatedContacts_added = []; // Meant for possible output at the end
								
								// Get existing related contacts of target Ticket object (lnkContactToTicket)
								$oFilter_related_contacts = new DBObjectSearch('lnkContactToTicket');
								$oFilter_related_contacts->AddCondition('ticket_id', $oTargetObj->GetKey(), '=');
								$oObjectSet_related_contacts = new CMDBObjectSet($oFilter_related_contacts);
								
								while($oObj = $oObjectSet_related_contacts->Fetch()) {
									$aRelatedContacts_cache[] = $oObj->Get('contact_id');
								}
									
								// Might have gone over the object set
								$oObjectSet->Rewind();
								
								// Loop over the object set
								while($oObj = $oObjectSet->Fetch()) {
								
									// No existing lnkContactToTicket yet?
									// Also, shouldn't be the caller_id of the target object
									if(in_array($oObj->Get('caller_id'), $aRelatedContacts_cache) == false && $oObj->Get('caller_id') != $oTargetObj->Get('caller_id')) {
										
										// Create new link between Ticket and Contact (lnkContactToTicket)
										// Downside: it has a combobox for roles: 'computed', 'manual', 'do_not_notify'.
										// 'computed' seems to be the better choice.
										$oLinkContactToTicket = new lnkContactToTicket();
										$oLinkContactToTicket->Set('role_code', 'computed');
										$oLinkContactToTicket->Set('contact_id', $oObj->Get('caller_id'));
										$oLinkContactToTicket->Set('ticket_id', $oTargetObj->GetKey());
										$oLinkContactToTicket->DBInsert();
										
										$aRelatedContacts_added[] = $oObj->Get('caller_id');
										$aRelatedContacts_cache[] = $oObj->Get('caller_id');
									}
									
								}
								
							}
							break;
							
						case 'AttributeLinkedSet':
						
							// A set of objects pointing to the current object
							// For example: Ticket has a tab WorkOrders. A set of workorders is linked to ticket.
							
							// Fetch all objects of the other class (e.g. WorkOrder)
							// Update the attribute in the other class pointing to the current class
							
							// @todo What about the history? Should update be tracked and hwo?
							
							break;
							
						case 'AttributeLinkedSetIndirect':
						
							// AttributeLinkedSetIndirect uses 'link' classes to link objects.
							// Most of the times, this links two objects together without further information (example: CI linked to ticket)
							// Sometimes, other fields are available in linked classes too (example: links between NetworkDevice contains other data like uplink/downlink)
							// @todo Change implementation and move this to the previous step, as a list where IDs to be kept can be selected?
							
							// Check if no duplicates will be created
							// If not: update object ID
							// Else: delete
							
							// Obtain ALL objects of this link class (for example: lnkContactToTicket, lnkFunctionalCIToTicket, ...)
							$oFilter_IndirectLinkedSet = new DBObjectSearch($oAttributeDef->GetLinkedClass());
							$oFilter_IndirectLinkedSet->AddCondition($oAttributeDef->GetExtKeyToMe(), $aSelectedObjects, 'IN');
							$oFilter_IndirectLinkedSet->AddCondition($oAttributeDef->GetExtKeyToMe(), $oTargetObj->GetKey(), '!=');
							$oObjectSet_IndirectLinkedSet = new CMDBObjectSet($oFilter_IndirectLinkedSet);
							
							// Cache target object's linked set first: avoid creating *exact* duplicates.
							// *exact* means identical in every possible way. Linked sets may not just contain IDs, but other information as well.
							// For example, a WorkOrder could contain a start and end date. Multiple WorkOrders for the same Person shouldn't be seen as 'duplicate' if start/end is different.
							$oFilter_IndirectLinkedSet_target = new DBObjectSearch($oAttributeDef->GetLinkedClass());
							$oFilter_IndirectLinkedSet_target->AddCondition($oAttributeDef->GetExtKeyToMe(), $oTargetObj->GetKey(), '=');
							$oObjectSet_IndirectLinkedSet_target = new CMDBObjectSet($oFilter_IndirectLinkedSet_target);
							
							// Cache fingerprints
							$aCachedFingerPrints = [];
							while($oObj_lnk = $oObjectSet_IndirectLinkedSet_target->Fetch()) {
								$aCachedFingerPrints[] = $oObj_lnk->FingerPrint(/* Excluded columns */ ['id']);
							}
							
							// Go over every object of indirect linked class which is not related to target object yet
							while($oObj_lnk = $oObjectSet_IndirectLinkedSet->Fetch()) {
								
								// Update and then get the new fingerprint. Don't save, just yet!
								$oObj_lnk->Set($oAttributeDef->GetExtKeyToMe(), $oTargetObj->GetKey());
								$sFingerPrint = $oObj_lnk->FingerPrint(['id']);
								
								// Avoid duplicates: fingerprint should not already exist in the cached array
								if(in_array($sFingerPrint, $aCachedFingerPrints) == false) {
									
									// Create new link
									$oLnk = clone $oObj_lnk;
									$oLnk->Set($oAttributeDef->GetExtKeyToMe(), $oTargetObj->GetKey());
									$oLnk->DBInsert();
								
									// Add this new fingerprint. Recalculate since it has changed!
									$aCachedFingerPrints[] = $sFingerPrint;
									
								}
								else {
									
									// Do not create new link.
									// Do not remove link either from the Ticket that will be merged.
									// It will be deleted automatically if necessary.
								
								}
									
							}
							
							
							break;
						
						default:
							// Ignore, for now.
							
					}
		
				}
			
			// Merge Attachments
			// --
			
				$oFilter_Attachments = new DBObjectSearch('Attachment');
				$oFilter_Attachments->AddCondition('item_id', $aSelectedObjects, 'IN');
				$oFilter_Attachments->AddCondition('item_id', $oTargetObj->GetKey(), '!=');
				$oObjectSet_Attachments = new CMDBObjectSet($oFilter_Attachments);
				
				$oFilter_Attachments_target = new DBObjectSearch('Attachment');
				$oFilter_Attachments_target->AddCondition('item_id', $oTargetObj->GetKey(), '=');
				$oObjectSet_Attachments_target = new CMDBObjectSet($oFilter_Attachments_target);
				
				// Cache fingerprints
				$aCachedFingerPrints = [];
				while($oObj_Attachment = $oObjectSet_Attachments_target->Fetch()) {
					$aCachedFingerPrints[] = $oObj_Attachment->FingerPrint(/* Excluded columns */ ['id']);
				}
				
				// Go over every object of indirect linked class which is not related to target object yet
				while($oObj_Attachment = $oObjectSet_Attachments->Fetch()) {
					
					// Update and then get the new fingerprint. Don't save, just yet!
					$oObj_Attachment->Set('item_id', $oTargetObj->GetKey());
					$sFingerPrint = $oObj_Attachment->FingerPrint(['id']);
					
					// Avoid duplicates: fingerprint should not already exist in the cached array
					if(in_array($sFingerPrint, $aCachedFingerPrints) == false) {
						
						// Create new Attachment
						$oAttachment = clone $oObj_Attachment;
						$oAttachment->Set('item_id', $oTargetObj->GetKey());
						$oAttachment->DBInsert();
					
						// Add this new fingerprint. Recalculate since it has changed!
						$aCachedFingerPrints[] = $sFingerPrint;
						
					}
					else {
						
						// Do not create new Attachment.
						// Do not remove Attachment either from the Ticket that will be merged.
						// It will be deleted automatically if necessary.
					
					}
						
				}
				
				// @todo No history is added, which might not even be bad. -> create CMDBChangeOpAttachmentAdded? Or implement generic "merge" entry?
			
			
			// Update the target object
			// Warning: this could trigger Notifications! User is informed in previous step.
			// @todo How to prevent update history? ("log entry added" => will show most recent, despite perhaps not even being new)

			$oTargetObj->DBUpdate();
			
			// Clean history
			// --
			
				// Delete CMDBChange (thanks Guillaume!)
				$oChange = $oTargetObj->GetCurrentChange();
				
				// If anyone knows a better/shorter way with native iTop methods, please let me know how this can be improved.
				
				// Get CMDBChangeOps (many to one) pointing to this Change
				// Not change_id
				$oFilter_ChangeOps = DBSearch::FromOQL('SELECT CMDBChangeOp WHERE change = '.$oChange->GetKey());
				$oSet_ChangeOps = new CMDBObjectSet($oFilter_ChangeOps);
				
				// Temporarily cache the final classes and IDs
				$aCachedChangeOpFinalClasses = [];
				
				while($oChangeOp = $oSet_ChangeOps->Fetch()) {
					if(in_array($oChangeOp->Get('finalclass'), $aCachedChangeOpFinalClasses) == false ) {
						$aCachedChangeOpFinalClasses[] = $oChangeOp->Get('finalclass');
					}
				}
				
				// Re-query to delete (final classes)
				foreach($aCachedChangeOpFinalClasses as $sFinalClass) {
					// Not change_id
					$oFilter_ChangeOps = DBSearch::FromOQL('SELECT '.$sFinalClass.' WHERE change = '.$oChange->GetKey());
					$oSet_ChangeOps = new CMDBObjectSet($oFilter_ChangeOps);
					
					while($oChangeOp = $oSet_ChangeOps->Fetch()) {
						$oChangeOp->DBDelete();
					}
				}
				
				echo 'should delete #'.$oChange->GetKey();
				
				
				// Find all related change operations
				
				// Only now the CMDBChange can be deleted...
				// Or we can recycle it to add a change of our own?
				// $o->DBDelete();
				
				
		
			
			// Cleanup
			// --
				// What to do with non-target objects?
				// Change state to 'closed'; inform user?
				// Notify with new ticket number and delete?
				// Implement a "On Tickets/Objects merged" trigger?
				// Simply delete objects?
			
			// History
			// --
				// Log something in history?
			
			
			
			$oP->add(Dict::Format('UI:ObjectMerge:SuccessFullyMergedTo', $oTargetObj->Get('friendlyname')));
			
			break;

		

		///////////////////////////////////////////////////////////////////////////////////////////
		
		// @todo Review this below
		
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
	require_once(APPROOT.'/application/nicewebpage.class.inc.php');
	$oP = new NiceWebPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->add(Dict::Format('UI:Error_Details', $e->getMessage()));	
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
