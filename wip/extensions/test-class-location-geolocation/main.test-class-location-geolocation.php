<?php

/* 
	Overruling won't work. Look for alternative? GetAttributeFlags() or AttributeCustomFields ? 

class Location {
	
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
	   // Force the computed field to be read-only, preventing it to be written
	   if (($sAttCode == 'wktgeom'))
			 return( OPT_ATT_READONLY | parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState) );
		 
	   return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}
	
}
*/

class TestGeolocExtension implements iPopupMenuExtension
{
   public static function EnumItems($iMenuId, $param)
   {
	  if ($iMenuId == self::MENU_OBJDETAILS_ACTIONS)
	  {
		 $oObject = $param;
		 if ($oObject instanceof Location)
		 {
			 $sUID = 'MyPopupExtension-Geoloc'; // Make sure that each menu item has a unique "ID"
			 $sLabel = 'Geolocation';
			 $sURL = 'http://www.openstreetmap.org/?mlat='.$oObject->Get('latitude').'&mlon='.$oObject->Get('longitude').'&zoom=12';
			 $sTarget = '_blank';
			 $oMenuItem = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget);
			 
			 
			 
			 $sUID = 'MyPopupExtension-Geoloc-GoogleMaps'; // Make sure that each menu item has a unique "ID"
			 $sLabel = 'Google Maps';
			 // Also possible: https://www.google.com/maps/?q=<address>
			 $sURL = 'https://www.google.com/maps/@'.$oObject->Get('longitude');
			 $sTarget = '_blank';
			 $oMenuItem = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget); 
			  
			 
			 return array($oMenuItem);
		 }
	  }
	  return array();
   }
}

 
	
?>
