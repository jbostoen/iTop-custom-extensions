<?php

	/* Decided to not work with a menu to display the map. Instead, based on type of Geometry, it could be added to the Geolocation tab
class menuExtensionGeom implements iPopupMenuExtension
{
	
	
   public static function EnumItems($iMenuId, $param)
   {
	  if ($iMenuId == self::MENU_OBJDETAILS_ACTIONS)
	  {
		 $oObject = $param;
		 if ($oObject instanceof Location)
		 {
			 
			 $aMenuItems = array();
			 
			 // $sUID = 'geomOSM'; // Make sure that each menu item has a unique "ID"
			 // $sLabel = 'Open Street Map';
			 // $sURL = 'http://www.openstreetmap.org/'; //?mlat='.$oObject->Get('latitude').'&mlon='.$oObject->Get('longitude').'&zoom=12';
			 // $sTarget = '_blank';
			 // $aMenuItems[] = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget);
			
			 
			// Add a new menu item that triggers a custom JS function defined in our own javascript file: js/actions.js
			$sModuleDir = basename(dirname(__FILE__));
			$sJSFileUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/js/actions.js';
			new JSPopupMenuItem('geom_ol_showOSM', 'OpenStreetMap', "ol_showOSM()", array($sJSFileUrl));
			 
			
			 
			  
			$aMenuItems[] = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget); 
			  
			 
			 return $aMenuItems;
		 }
	  }
	  return array();
   }
   
  
}
 */
 
	
?>

