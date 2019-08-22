<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-22 12:49:39
 *
 * Definition of PopupMenuExtension_ReportGenerator
 */

/**
 *  Class PopupMenuExtension_ReportGenerator. Adds items to popup menu of to 'Details' view, to generate reports.
 */
class PopupMenuExtension_ReportGenerator implements iPopupMenuExtension
{
	
	public static function EnumItems($iMenuId, $param)
	{
	
		// Array for items to be returend 
		$aMenuItems = array();
		
		// New window/popup? 
		$sTarget = '_BLANK';
		$sModuleName = utils::GetCurrentModuleName();
		$sModuleDir = dirname(__DIR__);
		
		$aMenuItems = [];
		
		if ($iMenuId == self::MENU_OBJDETAILS_ACTIONS)
		{
		  
			$sActions = MetaModel::GetConfig()->Get('shortcut_actions');
		
			// The actual object of which details are displayed
			$oObject = $param;
			
			// Derive class name 
			$sClassName = get_class($oObject);
			
			// Where are reports located?		
			$sClassReportDir = $sModuleDir.'/templates/'. $sClassName;
			
			// Get HTML (Twig) template names; search for <title> tag which will be used to generate report.
			// This also means we won't need to do an 'instanceof'. 
			// Currently not considering abstract (parent) classes.
			$aReportFiles = glob( $sClassReportDir . '/details/*.{html,twig}' , GLOB_BRACE );
			
			// For each of those classes, check which reports are available 
			foreach( $aReportFiles as $sReportFile ) 
			{
				
				$sReportContent = file_get_contents( $sReportFile );
				
				// Should contain a <title> tag. Of course not useful in prints, but allows for easy translation management.
				preg_match( '/<title>(.+?)<\/title>/i' , $sReportContent, $aTagMatches );
				
				if( empty($aTagMatches) == false ) 
				{
					// Theoretically there should only be one match 
					$sLabel = $aTagMatches[1];

					$sLabel = self::RenderLabel($sLabel);
					
				}
				else 
				{
					// No tag matches; fallback
					$sLabel = Dict::S('UI:Menu:ReportGenerator:ShowReportTitleMissing');
				}
				
				// Prefix
				$sLabel = Dict::S('UI:Menu:ReportGenerator:ShowReport') . ': ' . $sLabel;
				
				// UID must simply be unique. Keep alphanumerical version of filename.
				$sUID = $sModuleName.'_' . preg_replace('/[^\da-z]/i', '',  basename($sReportFile)) . '_' . rand(0, 10000);
				
				// Button or menu-item?
				$sActions .= ( self::GetAppearance($sReportContent) == 'button' ? ','.$sUID : '');
				
				// URL should give our generator the location of the report (folder/report) and the ID of the object
				// type=Object is to allow 'showReport.php' to also include lists in the future.
				$oFilter = new DBObjectSearch($sClassName);
				$oFilter->AddCondition('id', $oObject->Get('id'), '=');

				// URL should pass location of the report (folder/report) and the OQL query for the object(s)
				$sURL = utils::GetCurrentModuleUrl() . '/showreport.php?'.
					'&type=details'.
					'&class=' . $sClassName . 
					'&filter='.htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8').
					'&template=' . basename(basename($sReportFile));
					
				$aMenuItems[] = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget);
				
			}
		 
			MetaModel::GetConfig()->Set('shortcut_actions', ltrim($sActions, ',' ));
		
		}
		elseif($iMenuId == self::MENU_OBJLIST_ACTIONS)
		{
			
			$sActions = MetaModel::GetConfig()->Get('shortcut_actions');
		
			// $param in this case is a DBObjectSet
			$oObjectSet = $param;
			
			if( $oObjectSet->Count() > 0 ) {
				
				// Derive class name 
				$sClassName = $oObjectSet->GetClass();
						
				// Where are reports located?		
				$sClassReportDir = $sModuleDir . '/templates/' . $sClassName; 
				 
				// Get HTML (Twig) template names; search for <title> tag which will be used to generate report.
				// This also means we won't need to do an 'instanceof'. 
				// Currently not considering abstract (parent) classes.
				$aReportFiles = glob( $sClassReportDir . '/list/*.{html,twig}' , GLOB_BRACE );
				
				// For each of those classes, check which reports are available 
				foreach( $aReportFiles as $sReportFile ) 
				{
					
					$sReportContent = file_get_contents( $sReportFile );
					
					// Report (.twig) should contain a <title> tag. Of course not useful in prints, but allows for easy translation management.
					preg_match( '/<title>(.+?)<\/title>/i' , $sReportContent, $aTagMatches );
					
					if( empty($aTagMatches) == false ) {
						// There should only be one match for a <title>-tag
						$sLabel = $aTagMatches[1];
						
						$sLabel = self::RenderLabel($sLabel);
						
					}
					else 
					{
						// No 'title' tag found. Fallback
						$sLabel = Dict::S('UI:Menu:ReportGenerator:ShowReportTitleMissing');
						
					}
					
					// Prefix
					$sLabel = Dict::S('UI:Menu:ReportGenerator:ShowReport'). ': ' . $sLabel;
					$sLabel = 'test';
					
					// UID must simply be unique. Keep alphanumerical version of filename.
					$sUID = $sModuleName.'_' . preg_replace('/[^\da-z]/i', '',  basename($sReportFile)) . '_' . rand(0, 10000);
						
					// Button or menu-item?
					$sActions .= ( self::GetAppearance($sReportContent) == 'button' ? ','.$sUID : '');
						
					$oFilter = $oObjectSet->GetFilter();
					
					// URL should pass location of the report (folder/report) and the OQL query for the object(s)
					$sURL = utils::GetCurrentModuleUrl() . '/showreport.php?'.
						'&type=list'.
						'&class=' . $sClassName . 
						'&filter='.htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8').
						'&template=' . basename(basename($sReportFile));
					
					$aMenuItems[] = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget); 
									
					 
				}
				
				MetaModel::GetConfig()->Set('shortcut_actions', ltrim($sActions, ',' ));
				
			} 
		}		
		
		// Always expects an array as result (?)
		return $aMenuItems;
		  
	}
	
	/**
	 * Renders the label with the Twig engine, allowing for iTop translations
	 *
	 * @param String $sLabel Label
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
		$oTwigEnv = new Twig_Environment($loader, [
			'autoescape' => false
		]);

		// Combodo uses this filter, so let's use it the same way for our report generator
		$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
				return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
			})
		);
		
		$sLabel = $oTwigEnv->render('string');

		return $sLabel;
  
	}
	
	/**
	 * Gets appearance. Currently: not specified (same as 'menu') or 'button'.
	 *
	 * @details Specify in Twig file as <html data-report-trigger="menu">
	 *
	 * @return String
	 */
	public static function GetAppearance($sReportContent) {
		
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
	 
}
