<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-11 20:40:30
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
			// Attributes must be specified by their attribute code. If all attributes (of the above types) are specified, it's possible to use ['*']
			
			// Attributes which can be merged for this class
			'allowed' => ['contacts_list', 'functionalcis_list', 'private_log', 'public_log'],
			
			// ['*'] can be used to include ALL attributes.
			'checked' => ['*'],
			
			// Attributes which are ALWAYS merged (enforced). Any attribute specified here should also be in the allowed list.
			'required' => ['private_log', 'public_log'],
			
		],
		
		'target_object' => [
			'add_callers_to_related_contacts' => true, // Add callers of the merged tickets to 'related Contacts' (could be used in Notification)
		],
		'target_object_selection_order' => [
			// Multiple attributes can be specified. Processed in order.
			'id' => true, // Set to 'true' for sorting target objects from oldest to newest; set to false for newest to oldest
		],
		
		// Ideas but not implemented
		// --
		
		// OQL Scope to limit objects which can be selected?
		// For instance, non-resolved and non-closed Tickets
		'source_scope' => '', // Similar to Combodo's User Actions Configurator. The OQL to define the source objects. The only parameter available is current_contact_id.
		
		// Limit who can merge objects?
		'allowed_profiles' = '', // Similar to Combodo's User Actions Configurator. CSV list of profiles allowing the shortcut. The user must have at least one profile to have the shortcut available. Wrong profiles names are ignored. Set as an empty string to allow the shortcut to anybody.
		
		'merged_objects' => [
			'apply_stimulus' => 'some_stimulus', // @todo Check for valid stimulus
			'delete' => true, // Notification could be sent first. How can a placeholder be used?
			'set' => [
				'<attribute_name>' => 'could be used for writeback; have $targetObj placeholder'
			],
			'add_entries' => [
				'<CaseLog_attribute_name>' => 'Entry to insert; add targetObj placeholder'
			],
		],
	]); 
	
	// Module settings, class specific. In XML, most nodes seem to start with a non-capital.
	// Required 'class' parameter makes it easier
	$sClass = utils::ReadParam('class', '', false, 'class');
	if( empty($sClass) == false ) {
		
		if( MetaModel::GetParentClass($sClass) != 'Ticket' ) {
			// Shouldn't be possible to get here
			throw new ApplicationException(Dict::S('UI:TicketMerge:OnlyTickets'));
		}
		
		// Obtain more class specific settings
		if( utils::GetCurrentModuleSetting(strtolower($sClass), '') != '') {
			$aModuleSettings_class_specific = utils::GetCurrentModuleSetting($sClass, array());
			$aModuleSettings = array_replace_recursive($$aModuleSettings, $aModuleSettings_class_specific);
		}
		
		
	}
	
	if( $aModuleSettings['allowed_profiles'] != '' && (count(array_intersect(UserRights::ListProfiles(), explode(',', $aModuleSettings['allowed_profiles']))) > 0) ) {
		throw new SecurityException(basename(__FILE__) . ': invalid profile');
	}
	
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
			$oP->add(MetaModel::GetClassIcon($sClass, true) . ' ' . Dict::Format('UI:TicketMerge:Title', $sClass));
			$oP->add("</p>\n");
			
			$oFilter = DBObjectSearch::unserialize($sFilter);
			// CMDBAbstractObject::DisplaySet() probably decides output order, not here.
			$oObjectSet = new CMDBObjectSet($oFilter);
			$iCountObjects = $oObjectSet->Count();
	
			if($iCountObjects < 2) {
				$oP->p(Dict::S('UI:TicketMerge:MultipleObjectsRequired'));
			}
			else {
				
				// Code below based on cmdbAbstractObject::DeleteObjects() (2.6.1)
				$oP->p('<h1>'.Dict::Format('UI:TicketMerge:ConfirmCountObjectsOfClass', $iCountObjects, MetaModel::GetName($sClass)).'</h1>');

				$oP->add("<form method=\"post\">\n");
				
				$oP->add('<div id="0">');
				CMDBAbstractObject::DisplaySet($oP, $oObjectSet, array(
					'display_limit' => false, 
					'menu' => false, 
					'selection_mode' => true,
					'selection_type' => 'multiple'
				));
				$oP->add("</div>\n");
				
				// transaction_id protects against hijacking attempts
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"merge_show_options\">\n");
				$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8')."\">\n");
				$oP->add("<input type=\"hidden\" name=\"class\" value=\"{$sClass}\">\n");
				
				
				$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
				$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:TicketMerge:Button:Merge')."\">\n");
				$oP->add("<br>");
				$oAppContext = new ApplicationContext();
				$oP->add($oAppContext->GetForForm());
				$oP->add("</form>\n");

				
			}
			
			break; // End case displaytree

		case 'merge_show_options':
		
			// Not sure why, but also used in iTop's native modify/deletion procedure
			$oP->DisableBreadCrumb();
			
			// Inconsistency between style of 'transaction_id' and 'selectObject' parameters comes from Combodo (~ object deletion)
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
				throw new ApplicationException(Dict::S('UI:TicketMerge:WarningAtLeastTwoObjectsNeeded'));
			}
			
			if (!utils::IsTransactionValid($sTransaction_id, true)) {
				throw new SecurityException(basename(__FILE__) . ': invalid transaction_id');
			}
			utils::RemoveTransaction($sTransaction_id);
			
			// Temporary, move to next phase/blue form
			// Open blue modification frame 
			$oP->add("<div class=\"wizContainer\">\n");

			$oP->add("<form method=\"post\">\n");			
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
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
			
			// How to order target object dropdown list?
			$aOrder = [];
			foreach($aModuleSettings['target_object_selection_order'] as $sAttCode => $bSortAscending) {
				$aOrder[$oFilter->GetClassAlias().'.'.$sAttCode] = $bSortAscending;
			}
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
			$oP->add('<b>'.Dict::S('UI:TicketMerge:AttributeOverview').':</b><br>');
			
			$oP->add("<ul>"); 
			
			
			// Which attributes? Free select or from predefined config?
			$aAttributes = Metamodel::GetAttributesList($sClass);
			
			// Limit to valid attribute types
			$aAttributes = array_filter($aAttributes, function($sAttribute) use ($sClass) {
				
				// Get attribute definition (to determine type)
				$oAttributeDef = Metamodel::GetAttributeDef($sClass, $sAttribute);
				 
				// AttributeLinkedSetIndirect (update links; set ticket_id to the destination's ticket_id if not duplicate; else delete)
				// AttributeCaselog is also likely to be interesting to merge.
				return in_array(get_class($oAttributeDef), ['AttributeCaseLog', 'AttributeLinkedSet', 'AttributeLinkedSetIndirect']);
	
			});
			
			// Sort alphabetically
			sort($aAttributes);
			
			$aOption_attributes_allowed = $aModuleSettings['attributes']['allowed'];
			$aOption_attributes_checked = $aModuleSettings['attributes']['checked'];
			$aOption_attributes_required = $aModuleSettings['attributes']['required'];
			
			// Limit to allowed attributes for this class as specified in the configuration.
			// Take care of wildcard ['*'] to specify all attributes
			$aOption_attributes_allowed = (count($aOption_attributes_allowed) == 1 && $aOption_attributes_allowed[0] == '*' ? $aAttributes : $aOption_attributes_allowed);
			$aOption_attributes_allowed = array_values(array_intersect($aAttributes, $aOption_attributes_allowed));
				
			// Limit enforced/required attributes for this class
			$aOption_attributes_required = ( count($aOption_attributes_required) == 1 && $aOption_attributes_required[0] == '*' ? $aOption_attributes_allowed : $aOption_attributes_required);
			$aOption_attributes_required = array_values(array_intersect($aOption_attributes_allowed, $aOption_attributes_required));
			
			// Limit automatically checked attributes for this class
			$aOption_attributes_checked = ( count($aOption_attributes_checked) == 1 && $aOption_attributes_checked[0] == '*' ? $aOption_attributes_allowed : $aOption_attributes_checked);
			$aOption_attributes_checked = array_values(array_intersect($aOption_attributes_allowed, $aOption_attributes_checked));
			
			// Change for output
			$aAttributes = array_map(function($sAttribute) use ($sClass, $aOption_attributes_checked, $aOption_attributes_required) {
				// Add checkbox
				$bChecked = (in_array($sAttribute, $aOption_attributes_checked) == true);
				$bDisabled = (in_array($sAttribute, $aOption_attributes_required) == true);
				return '<input type="checkbox" name="attributes_to_merge[]" value="'.$sAttribute.'"'.($bChecked == true ? ' checked' : '').($bDisabled == true ? ' disabled' : '').'> ' . MetaModel::GetLabel($sClass, $sAttribute, /* bShowMandatory */ false) . ' ('. $sAttribute .')';
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
				$oP->add(Dict::S('UI:TicketMerge:NoAttachmentsFound'));
			}
			
			// Add label to warn user that modifications caused by merging may trigger notifications
			$oP->add("<hr>"); 
			$oP->add('<p>'.Dict::S('UI:TicketMerge:WarningModificationsMayTriggerNotifications').'</p>');
			
			$oP->add("<br>");
			
			$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
			$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:TicketMerge:Button:Merge')."\">\n");
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
			
			if (!utils::IsTransactionValid($sTransaction_id, true)) {
				throw new SecurityException(basename(__FILE__) . ': invalid transaction_id');
			}
			
			utils::RemoveTransaction($sTransaction_id);
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
			
			// Which attributes? Free select or from predefined config?
			$aAttributes = Metamodel::GetAttributesList($sClass);
			
			// Limit to valid attribute types
			$aAttributes = array_filter($aAttributes, function($sAttribute) use ($sClass) {
				
				// Get attribute definition (to determine type)
				$oAttributeDef = Metamodel::GetAttributeDef($sClass, $sAttribute);
				 
				// AttributeCaselog (merge logs)
				// AttributeLinkedSet (update links)
				// AttributeLinkedSetIndirect (update links; set ticket_id to the destination's ticket_id if not duplicate; else delete)
				return in_array(get_class($oAttributeDef), ['AttributeCaseLog', 'AttributeExternalKey', 'AttributeLinkedSet', 'AttributeLinkedSetIndirect']);
	
			});
			
			$aOption_attributes_allowed = $aModuleSettings['attributes']['allowed'];
			$aOption_attributes_checked = $aModuleSettings['attributes']['checked'];
			$aOption_attributes_required = $aModuleSettings['attributes']['required'];
			
			// Limit to allowed attributes for this class as specified in the configuration.
			// Take care of wildcard ['*'] to specify all attributes
			$aOption_attributes_allowed = (count($aOption_attributes_allowed) == 1 && $aOption_attributes_allowed[0] == '*' ? $aAttributes : $aOption_attributes_allowed);
			$aOption_attributes_allowed = array_values(array_intersect($aAttributes, $aOption_attributes_allowed));
				
			// Limit enforced/required attributes for this class
			$aOption_attributes_required = ( count($aOption_attributes_required) == 1 && $aOption_attributes_required[0] == '*' ? $aOption_attributes_allowed : $aOption_attributes_required);
			$aOption_attributes_required = array_values(array_intersect($aOption_attributes_allowed, $aOption_attributes_required));
			
			// Limit automatically checked attributes for this class
			$aOption_attributes_checked = ( count($aOption_attributes_checked) == 1 && $aOption_attributes_checked[0] == '*' ? $aOption_attributes_allowed : $aOption_attributes_checked);
			$aOption_attributes_checked = array_values(array_intersect($aOption_attributes_allowed, $aOption_attributes_checked));
			
			// Change for output
			$aAttributes = array_map(function($sAttribute) use ($sClass, $aOption_attributes_checked, $aOption_attributes_required) {
				// Add checkbox
				$bChecked = (in_array($sAttribute, $aOption_attributes_checked) == true);
				$bDisabled = (in_array($sAttribute, $aOption_attributes_required) == true);
				return '<input type="checkbox" name="attributes_to_merge[]" value="'.$sAttribute.'"'.($bChecked == true ? ' checked' : '').($bDisabled == true ? ' disabled' : '').'> ' . MetaModel::GetLabel($sClass, $sAttribute, /* bShowMandatory */ false) . ' ('. $sAttribute .')';
			}, $aAttributes);
		
			// Limit default checked attributes for this class to what is actually allowed
			$aAttributes_to_merge = array_intersect($aOption_attributes_allowed, $aAttributes_to_merge);
			
			// Special cases. Cheating a bit.
			$aAttributes_to_merge[] = 'caller_id'; // To add caller of Ticket as related contact (lnkContactToTicket)
			
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
								
								$aRelatedContacts_IDs = []; // Storing Contact IDs of all existing and newly created lnkContactToTicket
								$aRelatedContacts_added = []; // Meant for possible output to the user at the end
								
								// Get existing related contacts of target Ticket object (lnkContactToTicket)
								$oFilter_related_contacts = new DBObjectSearch('lnkContactToTicket');
								$oFilter_related_contacts->AddCondition('ticket_id', $oTargetObj->GetKey(), '=');
								$oObjectSet_related_contacts = new CMDBObjectSet($oFilter_related_contacts);
								
								// Cache contact_id of people already linked to this Ticket
								while($oObj = $oObjectSet_related_contacts->Fetch()) {
									$aRelatedContacts_IDs[] = $oObj->Get('contact_id');
								}
								
								// Prepare to go over the object set another time
								$oObjectSet->Rewind();
								
								// Loop over the object set
								while($oObj = $oObjectSet->Fetch()) {
								
									// No existing lnkContactToTicket yet?
									// Also, shouldn't be the caller_id of the target object
									if(in_array($oObj->Get('caller_id'), $aRelatedContacts_IDs) == false && $oObj->Get('caller_id') != $oTargetObj->Get('caller_id')) {
										
										// Create new link between Ticket and Contact (lnkContactToTicket)
										// Downside: it has a combobox for roles: 'computed', 'manual', 'do_not_notify'.
										// 'computed' seems to be the better choice.
										$oLinkContactToTicket = new lnkContactToTicket();
										$oLinkContactToTicket->Set('role_code', 'computed');
										$oLinkContactToTicket->Set('contact_id', $oObj->Get('caller_id'));
										$oLinkContactToTicket->Set('ticket_id', $oTargetObj->GetKey());
										$oLinkContactToTicket->DBInsert();
										
										$aRelatedContacts_added[] = ['caller_id' => $oObj->Get('caller_id'), 'contact_id_friendlyname' => $oObj->Get('caller_id_friendlyname')];
										$aRelatedContacts_IDs[] = $oObj->Get('caller_id');
									}
									
								}
								
							}
							break;
							
						case 'AttributeLinkedSet':
						
							// A set of objects pointing to the current object
							// For example: Ticket has a tab WorkOrders. A set of workorders is linked to ticket.
							
							// Fetch all objects of the other class (e.g. WorkOrder)
							// Update the attribute in the other class pointing to the current class
							
							// @todo What about the history? Should update be tracked and how?
							
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
			
			$oP->add(Dict::Format('UI:TicketMerge:SuccessFullyMergedTo', $oTargetObj->Get('friendlyname')));
			
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

