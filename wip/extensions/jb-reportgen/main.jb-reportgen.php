<?php

/**
 *  Defines new popup menus where applicable.
 *  This file should be under <iTop/web>/extensions/extname
 */
 

/**
 *  Class menuExtensionReportGenerator. Adds items to popup menu of to 'Details' view, to generate reports.
 */
class menuExtensionReportGenerator implements iPopupMenuExtension
{
	
	
   public static function EnumItems($iMenuId, $param)
   {
	   
	  if ($iMenuId == self::MENU_OBJDETAILS_ACTIONS)
	  {
		  
		// The actual object of which details are displayed
		$oObject = $param;
		
		// Where are reports located?		
		$sClassReportDir = APPROOT . '/extensions/jb-reportgen/templates/'. get_class($oObject);
		 
		// Get HTML (Twig) template names; search for <title> tag which will be used to generate report.
		// This also means we won't need to do an 'instanceof'. 
		// Currently not considering abstract (parent) classes.
		$aReportFiles = glob( $sClassReportDir . '/*.{html,twig}' , GLOB_BRACE );
		
		// Array for items to be returend 
		$aMenuItems = array();
		 
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
				
			}
			else 
			{
				// No tag matches; fallback
				$sLabel = Dict::S('UI:Menu:ReportGenerator:ShowReportTitleMissing');
				
			}
			
			// UID must simply be unique. Keep alphanumerical version of filename.
			$sUID = 'jb-reportgen-' . preg_replace('/[^\da-z]/i', '',  basename($sReportFile)) . '-' . rand(0, 10000);
			
			// URL should give our generator the location of the report (folder/report) and the ID of the object
			// type=Object is to allow 'showReport.php' to also include lists in the future.
			$sURL = utils::GetAbsoluteUrlAppRoot() . '/extensions/jb-reportgen/showreport.php?type=Object&class=' . get_class($oObject) . '&key=' . $oObject->Get('id') . '&template=' . basename(basename($sReportFile));
			  
			// New window/popup? 
			$sTarget = "_BLANK";
			  
			$aMenuItems[] = new URLPopupMenuItem($sUID, Dict::S('UI:Menu:ReportGenerator:ShowReport') . ': ' . $sLabel, $sURL, $sTarget); 
			
		 
			
		}
		
		 
		return $aMenuItems;
	 
	
	  }
	  
	  // Always expects an array as result (?)
	  return array();
   }
   
  
}
 
 
	
	