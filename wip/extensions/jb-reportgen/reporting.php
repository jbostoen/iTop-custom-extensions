<?php
/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 17:01:06
 *
 * Shows report; based on available Twig templates.
 *
 * @todo Translate some errors which should never be seen in the first place
 */
	 

/**
 * $_REQUEST should contain: 
 * class:               String. Class name
 * filter:              String. OQL Query
 * type: 				String. 'details' or 'list'
 *
 * Optional:
 * template: 			String. Report name. For convenience, use detail/<filename>.twig and list/<filename>.twig . Default report (HTML)
 * action:				String. Name of custom action ('show_pdf')
*/

namespace jb_itop_extensions\report_generator;
		
	if (!defined('APPROOT')) require_once(__DIR__.'/../../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/displayblock.class.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/wizardhelper.class.inc.php');
	require_once(APPROOT.'/core/restservices.class.inc.php');
	
	// Autoloader (Twig, chillerlan\QRCode, ...
	require_once(APPROOT . '/libext/vendor/autoload.php');
	
	// Get iTop's Dict::S('string') so it can be exposed to Twig as well 
	// require_once( APPROOT . '/application/utils.inc.php' );
	// require_once( APPROOT . '/core/coreexception.class.inc.php' );
	// require_once( APPROOT . '/core/dict.class.inc.php' );
	
	try {
			
		// Logging in exposed :current_contact_id in OQL
		if (\LoginWebPage::EXIT_CODE_OK != \LoginWebPage::DoLoginEx(null /* any portal */, false, \LoginWebPage::EXIT_RETURN))
		{
			throw new \SecurityException('You must be logged in');
		}
		
		// utils::ReadParam( $sName, $defaultValue = "", $bAllowCLI = false, $sSanitizationFilter = 'parameter' )
		$sClassName = \utils::ReadParam('class', '', false, 'class');
		$sType = \utils::ReadParam('type', '', false, 'string');
		$sFilter = \utils::ReadParam('filter', '', false, 'raw_data');
		
		// Load ReportGeneratorExtensions (implementations of iReportGeneratorExtension)
		$sModuleName = \utils::GetCurrentModuleName();
		$sModuleDir = APPROOT . '/env-' . \utils::GetCurrentEnvironment() . '/' . \utils::GetCurrentModuleDir(0);
		$aCustomInterfaces = glob($sModuleDir.'/clients/*/*.php');
		foreach($aCustomInterfaces as $sFile) {
			require_once($sFile);
		}
		
		// Validation
		// --
		
		// Check if right parameters have been given
		if(empty($sClassName) == true) {
			throw new \ApplicationException(\Dict::Format('UI:Error:1ParametersMissing', 'class'));
		}
		
		if(empty($sFilter) == true) {
			throw new \ApplicationException(\Dict::Format('UI:Error:1ParametersMissing', 'filter'));
		}
		
		if(empty($sType) == true) {
			throw new \ApplicationException(\Dict::Format('UI:Error:1ParametersMissing', 'type'));
		}
		
		// Valid type?
		if(in_array($sType, ['details', 'list']) == false) {
			throw new \ApplicationException('Valid values for type are: details, list');
		}
		
		$oFilter = \DBObjectSearch::unserialize($sFilter);
		// $aAllArgs = \MetaModel::PrepareQueryArguments($oFilter->GetInternalParams());
		// $oFilter->ApplyParameters($aAllArgs); // Thought this was necessary for :current_contact_id. Guess not?
		$oSet_Objects = new \CMDBObjectSet($oFilter);		
		
		// Valid object(s)?
		// 20200115-0849: This check seems pointless if there's more automation and a query returns no results
		/*
			if($oSet_Objects->Count() == 0) {
				throw new \ApplicationException('Invalid OQL filter: no object(s) found');
			}
		*/
		
		$aSet_Objects = \jb_itop_extensions\report_generator\ObjectSetToArray($oSet_Objects);
		
		// Get keys to build one OQL Query
		$aKeys = [];
		foreach( $aSet_Objects as $aObject ) {
			$aKeys[] = $aObject['key'];
		}
		
		$oFilter_Attachments = new \DBObjectSearch('Attachment');
		$oFilter_Attachments->AddCondition('item_id', $aKeys, 'IN');
		$oSet_Attachments = new \CMDBObjectSet($oFilter_Attachments);
		$aSet_Attachments = \jb_itop_extensions\report_generator\ObjectSetToArray($oSet_Attachments);
		
		foreach($aSet_Objects as &$aObject ) {
			
			$aObject['attachments'] = array_filter($aSet_Attachments, function($aAttachment) use ($aObject) {
				return ($aAttachment['fields']['item_id'] = $aObject['key']);
			});
			
			$aObject['attachments'] = array_values($aObject['attachments']);
			
		}
		
		if($sType == 'details') {
			$aReportData['item'] = array_values($aSet_Objects)[0];
		}
		else {
			$aReportData['items'] = $aSet_Objects;
		}
		
		// Expose some variables
		// --
		
		$aReportData['current_contact'] = \jb_itop_extensions\report_generator\ObjectToArray(\UserRights::GetUserObject());
		$aReportData['request'] = $_REQUEST;
		$aReportData['application']['url'] = \utils::GetDefaultUrlAppRoot();
		
		// Get all classes implementing iReportTool
		$aReportGeneratorExtensions = [];
		foreach(get_declared_classes() as $sClassName) {
			if(in_array('jb_itop_extensions\report_generator\tools\iReportTool', class_implements($sClassName))) {
				$aReportGeneratorExtensions[] = $sClassName;
			}
		}
		
		// Enrich
		foreach($aReportGeneratorExtensions as $sClassName) {
			if($sClassName::IsApplicable() == true) {
				$sClassName::EnrichData($aReportData, $oSet_Objects);
			}
		}
		
		// Sort based on 'rank' of each class
		// Use case: block further processing
		usort($aReportGeneratorExtensions, function($a, $b) {
			return $a::$rank <=> $b::$rank;
		});
		
		
		// Execute each ReportExtension
		foreach($aReportGeneratorExtensions as $sClassName) {
			if($sClassName::IsApplicable() == true) {
				$sClassName::DoExec($aReportData, $oSet_Objects);
			}
		}

	}
	catch(\Exception $e) {
		require_once(APPROOT.'/application/nicewebpage.class.inc.php');
		$oP = new \NiceWebPage(\Dict::S('UI:PageTitle:FatalError'));
		$oP->add("<h1>".\Dict::S('UI:FatalErrorMessage')."</h1>\n");	
		$oP->add(\Dict::Format('UI:Error_Details', $e->getMessage()));	
		$oP->output();
	}


	/**
	 * Returns array (similar to REST/JSON) from object set
	 *
	 * @param \CMDBObjectSet $oObjectSet iTop object set
	 *
	 * @return Array
	 */
	function ObjectSetToArray(\CMDBObjectSet $oObjectSet) {
		
		$oResult = new \RestResultWithObjects();
		$aShowFields = [];
		$sClass = $oObjectSet->GetClass();
		foreach (\MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			$aShowFields[$sClass][] = $sAttCode;
		}
		
		while($oObject = $oObjectSet->Fetch()) {
			$oResult->AddObject(0, '', $oObject, $aShowFields);
		}
		
		if(is_null($oResult->objects) == true) {
			return [];
		}
		else {
			
			$sJSON = json_encode($oResult->objects);
			return json_decode($sJSON, true);
		}
	}


	/**
	 * Returns array (similar to REST/JSON) from object
	 *
	 * @param \CMDBObject $oObject iTop object
	 *
	 * @return Array
	 */
	function ObjectToArray(\CMDBObject $oObject) {
		
		$oResult = new \RestResultWithObjects();
		$aShowFields = [];
		$sClass = get_class($oObject);
		
		foreach (\MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef) {
			$aShowFields[$sClass][] = $sAttCode;
		}
		
		$oResult->AddObject(0, '', $oObject, $aShowFields);
		
		if(is_null($oResult->objects) == true) {
			return [];
		}
		else {
			$sJSON = json_encode($oResult->objects);
			return current(json_decode($sJSON, true));
		}
		
	}
	
