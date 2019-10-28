<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-10-28 13:59:53
 *
 * Definition of PopupMenuExtension_ReportGenerator
 */

/**
 *  Class PopupMenuExtension_ReportGenerator. Adds items to popup menu of to 'Details' view, to generate reports.
 */
class PopupMenuExtension_ReportGenerator implements iPopupMenuExtension
{
	
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
		$sClassReportDir = $sModuleDir.'/templates/'.$sClassName;
		
		// Get template names; search for <title> tag which will be used to generate report.
		// Currently not considering abstract (parent) classes; each class has its own templates.
		$aReportFiles = glob($sClassReportDir.'/'.$sTemplateType.'/*.{'.implode(',', self::$extensions).'}', GLOB_BRACE );
		
		// For each of those classes, check which reports are available 
		foreach($aReportFiles as $sReportFile) {
			
			// Get content of report
			$sReportContent = file_get_contents($sReportFile);
			
			// Get label
			$sLabel = self::GetLabel($sReportFile, $sReportContent);
			
			// UID must simply be unique. Keep alphanumerical version of filename.
			$sUID = $sModuleName.'_' . preg_replace('/[^\dA-z_-]/i', '',  basename($sReportFile)) . '_' . rand(0, 10000);
			
			// Add shortcut (button) or keep menu item?
			self::$shortcut_actions .= ( self::GetAppearance($sReportFile, $sReportContent) == 'button' ? ','.$sUID : '');
			
			// URL should pass location of the report (folder/report) and the OQL query for the object(s)
			$sURL = \utils::GetCurrentModuleUrl() . '/showreport.php?'.
				'&type=' . $sTemplateType .
				'&class=' . $sClassName . 
				'&filter=' . htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8') .
				'&template=' . basename(basename($sReportFile))
			;
				
			self::$menu_items[] = new \URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget); 
			
		}
		
		// Update shortcut_actions
		\MetaModel::GetConfig()->Set('shortcut_actions', ltrim(self::$shortcut_actions, ',' ));
		 
	 }
	 
	/**
	 * Gets appearance. Currently: either there is nothing specified in the template (same as specifying 'menu') or 'button' is specified.
	 *
	 * @details Specify in HTML/Twig file as <html data-report-trigger="menu">.
	 * @todo Implement a method for other file types too
	 *
	 * @var \String $sReportFile Filename of report
	 * @var \String $sReportContent Content of report
	 *
	 * @return String
	 */
	public static function GetAppearance($sReportFile, $sReportContent) {
		
		$sRegex = '/\<html.*(?=(\>|data-report-trigger))data-report-trigger="(menu|button)"(?=\>)/';
		
		preg_match($sRegex, $sReportContent, $aRegexGroups);
		
		if(count($aRegexGroups) == 0) {
			// Default
			return 'menu';
		}
		else {
			// As defined
			return $aRegexGroups[2];
		}
	
	}
	
	/**
	 * Gets label for file
	 *
	 * @var \String $sReportFile Filename of report
	 * @var \String $sReportContent Content of report
	 *
	 * @details For HTML/Twig (and XML), this can be defined in a <title> tag. For other file types, it falls back to the filename (without extension)
	 *
	 * @return String Label
	 */
	public static function GetLabel($sReportFile, $sReportContent) {
		
		// Template may contain a <title> tag. 
		// Of course not useful in prints, but allows for easy translation management.
		preg_match('/<title>(.+?)<\/title>/i' , $sReportContent, $aTagMatches);
		
		if( empty($aTagMatches) == false ) 
		{
			// Theoretically there should only be one match 
			$sLabel = $aTagMatches[1];

			// Replace strings etc ( dict_s )
			$sLabel = self::RenderLabel($sLabel);
			
		}
		else 
		{
			// No localization
			$sLabel = pathinfo($sReportFile, PATHINFO_FILENAME);
		}
		
		return $sLabel;
		
	}
	
	/**
	 * Renders the label with the Twig engine, allowing for iTop translations (dict_s filter)
	 *
	 * @param \String $sLabel Label
	 *
	 * @return String
	 */
	 public static function RenderLabel($sLabel) {
	 
		// Autoloader (Twig, chillerlan\QRCode, ...
		require_once(APPROOT . '/libext/vendor/autoload.php');
		
		// Twig Loader
		$loader = new \Twig\Loader\ArrayLoader([
			'string' => $sLabel
		]);
		
		// Twig environment options
		$oTwigEnv = new \Twig_Environment($loader, [
			'autoescape' => false
		]); 

		// Combodo uses this filter, so let's use it the same way for this report generator
		$oTwigEnv->addFilter(new \Twig_SimpleFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
				return \Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
			})
		);
		
		$sLabel = $oTwigEnv->render('string');

		return $sLabel;
  
	 }
	 
}
