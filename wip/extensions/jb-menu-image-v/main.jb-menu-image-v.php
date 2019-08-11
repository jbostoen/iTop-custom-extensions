<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-11 20:40:30
 *
 * Extends iTop with a cPopupMenuExtension_ImageV
 */
 
class cPopupMenuExtension_ImageV implements iPopupMenuExtension
{
	/**
	 * Get the list of items to be added to a menu.
	 *
	 * This method is called by the framework for each menu.
	 * The items will be inserted in the menu in the order of the returned array.
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx
	 * @param mixed $param Depends on $iMenuId, see the constants defined above
	 * @return object[] An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 */
	public static function EnumItems($iMenuId, $param)
	{
		$aResult = array();
				
		switch($iMenuId) // type of menu in which to add menu items
		{
			/**
			 * Insert an item into the Actions menu on an object's details page
			 *
			 * $param is a DBObject instance: the object currently displayed
			 */      
			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
			
				// Get list of attributes
				$oObject = $param;
				$aAttributeList = Metamodel::GetAttributesList(get_class($oObject));
				$sGeometry = '';
				
				switch(true) {
					
					case in_array('geom', $aAttributeList):
					
						if( $oObject->Get('geom') != '' ) {
							$sGeometry = $oObject->Get('geom');
						}
					
						break;
						
					case in_array('crab_address_id', $aAttributeList):
					
						if( $oObject->Get('crab_address_id') != '' && (int)$oObject->Get('crab_address_id') > 0 ) {
							
							// Obtain coordinates
							$oSet_CrabAddresses = new CMDBObjectSet(DBObjectSearch::FromOQL('SELECT CrabAddress WHERE id = ' . $oObject->Get('crab_address_id')));
							
							// Could be invalid (but shouldn't be)
							if ($oSet_CrabAddresses->Count() == 1 ) {							
								$oCrabAddress = $oSet_CrabAddresses->Fetch();
								
								// Use CRAB geometry
								if( $oCrabAddress->Get('geom') != '' ) {
									$sGeometry = $oCrabAddress->Get('geom');
								}
							}
														
						}
					
						break;
						
					default:
					
				}

				if( $sGeometry !== '' ) {
						
					// add a separator
					$aResult[] = new SeparatorPopupMenuItem(); // Note: separator does not work in iTop 2.0 due to Trac #698, fixed in 2.0.1

					// Add a new menu item that triggers a custom JS function defined in our own javascript file: js/menu-actions.js
					$sModuleDir = basename(dirname(__FILE__));
					$sJSFileUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/js/menu-actions.js';
					$aResult[] = new JSPopupMenuItem('_Open_ImageV_', Dict::S('UI:Menu:OpenImageV'), "open_ImageV()", array($sJSFileUrl));

				}
				
				break;
			
			default:
				
				break;

		}
		
		return $aResult;
		
	}
}

/**
* Class cApplicationUIExtension_GeometryHandler. Adds tab with map, makes textbox 'geom' invisible
*/
class cApplicationUIExtension_ImageV implements iApplicationUIExtension
{
	/**
	* Called when building the Actions menu for a single object or a list of objects
	* 
	* @param DBObjectSet $oSet DB Object set
	* 
	* @return Array
	*/
	public function EnumAllowedActions( DBObjectSet $oSet) {
		return Array();
	}
	
	/**
	* Not yet called by framework, but reserved. Must be implemented.
	*
	* @param DBObject $oObject DB Object
	*
	* @return Type Description
	*/
	public function EnumUsedAttributes($oObject) : void { 
	}
	
	/**
	* Invoked when the object is displayed alone or within a list
	*
	* @param DBObject $oObject
	*
	* @return Integer 
	* 
    * HILIGHT_CLASS_CRITICAL
    * HILIGHT_CLASS_WARNING
    * HILIGHT_CLASS_OK
    * HILIGHT_CLASS_NONE
	*
	*/
	public function GetHilightClass($oObject) {
		return HILIGHT_CLASS_NONE;
	}
	
	/**
	* Not yet called by the framework!
	* Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	*
	* @param DBObject $oObject
	*
	* @return String Path of the icon, relative to the modules directory
	*
	*/
	public function GetIcon($oObject) : void {
		
	}
	
	/**
	* Invoked when an object is being displayed (wiew or edit)
	*
	* @param DBObject $oObject iTop object
	* @param Page $oPage Page object
	* @param Boolean $bEditMode User is editing this iTop object
	*
	* @return void
	*/
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false) : void {
		 
		// Output feature data to make it easily accessible to our custom functions.
		// Required if relying on 'crab_address_id'

		$aAttributeList = Metamodel::GetAttributesList(get_class($oObject));
		$sGeometry = '';
		
		switch(true) {
			
			case in_array('geom', $aAttributeList):
			
				// Use specific geometry
				if( $oObject->Get('geom') != '' ) {
					$sGeometry = $oObject->Get('geom');
					$sGeometrySettings_Class = get_class($this);
				}
			
				break;
				
			case in_array('crab_address_id', $aAttributeList):
			
				if( $oObject->Get('crab_address_id') != '' && (int)$oObject->Get('crab_address_id') > 0 ) {
					
					// Obtain coordinates
					$oSet_CrabAddresses = new CMDBObjectSet(DBObjectSearch::FromOQL('SELECT CrabAddress WHERE id = ' . $oObject->Get('crab_address_id')));
					
					// Could be invalid (but shouldn't be)
					if ($oSet_CrabAddresses->Count() == 1 ) {							
						$oCrabAddress = $oSet_CrabAddresses->Fetch();
						
						// Use CRAB geometry
						if( $oCrabAddress->Get('geom') != '' ) {
							$sGeometry = $oCrabAddress->Get('geom');			
							$sGeometrySettings_Class = 'CrabAddress';
						}
					}
					
				}
			
				break;
				
			default:
			
		}

		// Menu will be added in cPopupMenuExtension_ImageV
		$oPage->add_linked_stylesheet('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css');
		$oPage->add_linked_script('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js');
				
		if( $sGeometry !== '' ) {
			
			
			// Module settings, defaults.
			$aGeomSettings = MetaModel::GetModuleSetting('jb-geom', 'default', array( 
				'dataformat' => 'WKT',
				'datacrs' => 'EPSG:3857',
				'datatypes' => array('Point', 'LineString', 'Polygon'),
				'mapcrs' => 'EPSG:3857',
				'mapcenter' => array( 358652.11242031807, 6606360.84951076 ),
				'mapzoom' => 17,
			)); 
					
			// Module settings, class specifics. In XML, most nodes seem to start with a non-capital.
			if( MetaModel::GetModuleSetting('jb-geom', strtolower(get_class($oObject)), '') != '') {
				
				$aClassSpecificSettings = MetaModel::GetModuleSetting('jb-geom', strtolower($sGeometrySettings_Class), array() );				
				$aGeomSettings = array_replace($aGeomSettings, $aClassSpecificSettings);
				
			}
		
					
			// Needed for projections other than EPSG:4326, EPSG:3857
			$oPage->add_linked_script('https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.5.0/proj4.js');	

			// Add definition (expects file in subfolder 'proj' of this extension)
			$sProjDefinitionFile = dirname(__FILE__).'/epsg.io/31370.js';
			$sProj4_definition = file_get_contents($sProjDefinitionFile);
			
			// Add code to make geometry (coordinates) available to the custom JavaScript function
			$oPage->add_ready_script(
<<<EOF

				// Proj4 definition for EPSG:31370
				{$sProj4_definition}
				ol.proj.proj4.register(proj4);

				// Feature data for Image-V
				geometryHandler_ImageV = {}
				
				// Define format readers first
				geometryHandler_ImageV.oFormat = {
					WKT: new ol.format.WKT(),
					GeoJSON: new ol.format.GeoJSON()
				};
				
				// Now read feature
				geometryHandler_ImageV.oFeature = geometryHandler_ImageV.oFormat.{$aGeomSettings['dataformat']}.readFeature("{$sGeometry}", { 
					dataProjection: "{$aGeomSettings['datacrs']}"
				});
				
				// Image-V expects coordinates in EPSG:31370
				geometryHandler_ImageV.oFeature.getGeometry().transform("{$aGeomSettings['datacrs']}", "EPSG:31370");

EOF
			);
			
		}
		else {
			
			$oPage->add_ready_script('
				// No feature data for Image-V
				// -> geometry is empty and no CRAB geometry either
				geometryHandler_ImageV = {}
				geometryHandler_ImageV.oFeature = null;
			
			');
			
		}
		
	}
	
	/**
	 * 
	 * Invoked when an object is being displayed (wiew or edit)
	 * 
	 * @param \DBObject $oObject iTop object
	 * @param \WebPage $oPage Page object
	 * @param Boolean $bEditMode User is editing the iTop object
	 *   
	 * @return void
	 *  
	 */
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false) {
		
	}
	
	/**
	* Invoked when the end-user clicks on Cancel from the object edition form.
	* Implement here any cleanup. This is necessary when you have injected some javascript into the edition form, and if that code requires to store temporary data (this is the case when a file must be uploaded).
	*
	* @param String $sTempId Unique temporary identifier made of session_id and transaction_id. It identifies the object in a unique way.
	*
	* @return void
	*/
	public function OnFormCancel( $sTempId ) : void { 
	}
	
	/**
	* Invoked when the end-user clicks on Modify from the object edition form
	* The method is called after the changes from the standard form have been taken into account, and before saving the changes into the database.
	*
	* @param DBObject $oObject iTop object
	* @param String $sFormPrefix Prefix given to the HTML form inputs
	*
	* @return void
	*/
	public function OnFormSubmit( $oObject, $sFormPrefix = '' ) : void { 
	}
	
  
}
