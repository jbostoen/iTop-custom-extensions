<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 16:58:14
 *
 * Definition of PopupMenuExtension_ReportGenerator
 */

namespace jb_itop_extensions\report_generator;

/**
 * Class PopupMenuExtension_ReportGenerator. 
 * Adds items to popup menu of to 'Details' view, to generate reports.
 */
class PopupMenuExtensionReportGenerator implements \iPopupMenuExtension {
	
	/**
	 * @var \Array $extensions List of extensions to search for when looking for templates.
	 */
	private static $extensions = [
		// Potentially using 'title' tag:
		'html',
		'twig',
		'xml',
		// Not using 'title' tag:
		'csv',
		'json',
		'txt'
	];
	
	/**
	 * @var \URLPopupMenuItem[] Array of \URLPopupMenuItem
	 */
	private static $menu_items = [];
	
	/**
	 * @var \String Comma separated list of shortcut actions
	 */
	private static $shortcut_actions = '';
	
	/**
	 * @var \Integer $iMenuId Menu ID
	 * @var \Object $param Parameter provided by iTop.
	 */
	public static function EnumItems($iMenuId, $param) {
	
		if($iMenuId == self::MENU_OBJDETAILS_ACTIONS) {
		  
			// The actual object of which details are displayed (\DBOBject)
			$oObject = $param;
			
			// Build filter
			$oFilter = new \DBObjectSearch(get_class($oObject));
			$oFilter->AddCondition('id', $oObject->Get('id'), '=');

			// Process templates
			self::ProcessTemplates('details', $oFilter);
			
			return self::$menu_items;
		
		}
		elseif($iMenuId == self::MENU_OBJLIST_ACTIONS) {
			
			// $param in this case is a \DBObjectSet
			$oObjectSet = $param;
			
			// There should be items in the set.
			if( $oObjectSet->Count() > 0 ) {
				
				// Build filter
				$oFilter = $oObjectSet->GetFilter();
				
				// Process templates
				self::ProcessTemplates('list', $oFilter);
				
				return self::$menu_items;
				  
			} 
		} 
		
		// Always expects an array as result.
		return [];
		  
	}
	 
	/**
	 * Gets data from the templates, such as title and whether or not to use a separate button.
	 *
	 * @param \String $sTemplateType The template type, depending on the view ('details', 'list')
	 * @param \DBObjectSearch $oFilter The filter to provide in menu items
	 *
	 * @return void
	 * 
	 * @uses \PopupMenuExtension_ReportGenerator::menu_items
	 * @uses \PopupMenuExtension_ReportGenerator::shortcut_actions
	 */
	public static function ProcessTemplates($sTemplateType, \DBObjectSearch $oFilter) {
		
		// Menu items
		self::$menu_items = [];
		self::$shortcut_actions = \MetaModel::GetConfig()->Get('shortcut_actions');
		
		// Settings
		$sTarget = '_BLANK';
		$sModuleName = \utils::GetCurrentModuleName();
		$sModuleDir = APPROOT . '/env-' . \utils::GetCurrentEnvironment() . '/' . \utils::GetCurrentModuleDir(0);
		
		// Location of the reports
		$sClassName = $oFilter->GetClass();
		
		// Get template names.
		// Currently not considering abstract (parent) classes; each class has its own templates.
		include_once($sModuleDir.'/templates/reports.php');
		
		// For each of those classes, check which reports are available
		if(isset($aReports[$sClassName]) == true && isset($aReports[$sClassName][$sTemplateType]) == true) {
			
			foreach($aReports[$sClassName][$sTemplateType] as $aReport) {
				
				$sReportFile = $aReport['file'];
				
				// UID must simply be unique. Keep alphanumerical version of filename.
				$sUID = $sModuleName.'_' . preg_replace('/[^\dA-z_-]/i', '',  basename($sReportFile)) . '_' . rand(0, 10000);
				
				// Add shortcut (button) or keep menu item?
				self::$shortcut_actions .= ( $aReport['button'] == true ? ','.$sUID : '');
				
				// Parameters
				$aParameters = [];
				if(isset($aReport['parameters']) == true) {
					$aParameters = $aReport['parameters'];
				}
				
				// URL should pass location of the report (folder/report) and the OQL query for the object(s)
				$sURL = \utils::GetAbsoluteUrlExecPage().'?'.
					'&exec_module='.\utils::GetCurrentModuleName().
					'&exec_page=reporting.php'.
					'&exec_env='.\utils::GetCurrentEnvironment().
					'&type=' . $sTemplateType .
					'&class=' . $sClassName . 
					'&filter=' . htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8') .
					'&template=' . basename(basename($sReportFile)) . 
					( count($aParameters) > 0 ? '&'. http_build_query($aParameters) : '' )
				;
					
				self::$menu_items[] = new \URLPopupMenuItem($sUID, $aReport['title'], $sURL, $sTarget);
				
			}
			
		}
		
		// Update shortcut_actions
		\MetaModel::GetConfig()->Set('shortcut_actions', ltrim(self::$shortcut_actions, ',' ));
		 
	}
	 
}
