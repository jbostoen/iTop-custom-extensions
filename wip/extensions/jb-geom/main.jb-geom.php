<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Defines cApplicationUIExtension_GeometryHandler, cPopupMenuExtension_GeometryHandler
 */
 

/**
* Note: for compatibility with 2.5, you need to have some specific definitions ( DBObjectSet $oSet, WebPage $oPage )
**/

/**
* Class cApplicationUIExtension_GeometryHandler. Adds tab with map, makes textbox 'geom' invisible
*/
class cApplicationUIExtension_GeometryHandler implements iApplicationUIExtension
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
		 
		// On state changes (for example going from a UserRequest's 'assigned' to 'resolved', this method is called, but not OnDisplayRelations().
		// So geometry attribute needs to be hidden here.
		$oPage->add_ready_script(
<<<EOF
			
			// Hide or disable ( textarea in edit mode! ). Click event will not work here (to show Geometry tab)
			// Alternatively, you could do this with CSS
			$("div[data-attcode='geom']").hide();
				
EOF
		);
		
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
		
		// Get list of attributes
		$aAttributeList = Metamodel::GetAttributesList(get_class($oObject));
		
		// If attribute exists
		if( in_array('geom', $aAttributeList) == true ) {		
		
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
				
				$aClassSpecificSettings = MetaModel::GetModuleSetting('jb-geom', strtolower(get_class($oObject)), array() );				
				$aGeomSettings = array_replace($aGeomSettings, $aClassSpecificSettings);
				
			}
			
			$sGeomString = ( $aGeomSettings['dataformat'] == 'GeoJSON' ? addcslashes($oObject->Get('geom'), '"') : $oObject->Get('geom') ); 
			
			// Get path to ajax.handler.php
			$sModuleDir = basename(dirname(__FILE__));
			$sAjaxHandlerUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/ajax.handler.php';
			
			// Does a cookie exist with a preferred basemap for this class for this user?
			$sDefaultBaseMap = 'osm';
			$sCookieName = 'itop_geometryHandler_basemap_used_for_'.get_class($oObject);
			if(isset($_COOKIE[$sCookieName]) == true ) {
				// Renew for another 30 days
				setcookie($sCookieName, $_COOKIE[$sCookieName], time()+3600*24*30, '/');
				$sDefaultBaseMap = $_COOKIE[$sCookieName];
			}
			
			$sTabName = Dict::S('Location:Geometry');
			$oPage->SetCurrentTab($sTabName); 
			
			$oPage->add_linked_stylesheet('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css');
			$oPage->add_linked_script('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js');
			
			// osm = Open Street Map
			// grb = Grootschalig Referentiebestand, a Flemish webservice
			$oPage->add('
				<select id="geometryHandlerBaseMap">
					<option value="osm"'.($sDefaultBaseMap == 'osm' ? 'selected' : '').'>OpenStreetMap</option>
					<option value="grb"'.($sDefaultBaseMap == 'grb' ? 'selected' : '').'>GRB</option>
				</select>
			');
				
			if( $bEditMode ) {
				$oPage->add('| 
					<select id="geometryHandlerType">
				');

				foreach( $aGeomSettings['datatypes'] as $sDataType => $sDataValue ){
					$oPage->add('<option value="'.$sDataValue.'">'.Dict::S('UI:Geom:'.$sDataValue).'</option>'); 
				} 
						
				$oPage->add('
					</select>
					<div class="actions_button" id="geometryHandlerClear"><a href="javascript:void(0);">'.Dict::S('UI:Geom:Clear').'</a></div> | 
				');
					
			}
			
			// Add style for fullscreen
			$oPage->add_style(
<<<EOF

				.ol-map {
					/* Fix difference in color between regular size (slightly darker) and full screen (dark) */
					background-color: white;
				}
					
				.map:-moz-full-screen {
					\theight: 100%;
				}
				.map:-webkit-full-screen {
					\theight: 100%;
				}
				.map:-ms-fullscreen {
					\theight: 100%;
				}
				.map:fullscreen {
					\theight: 100%;
				}
				.ol-rotate {
					\ttop: 3em;
				}
EOF
			);
			
			$oPage->add(
<<<EOF
				<hr>
				<div id="ol-map" class="ol-map" style="width: 100%; height: 500px;"></div>
				<textarea id="geometryHandler_GeoJSON" style="height: 1px; max-height: 1px; min-height: 1px; width: 1px; max-width: 1px; min-width: 1px; display: none;"></textarea>
EOF
			);
			
			// Gather feature properties
			$aAttributeValues = Array();
			foreach($aAttributeList as $aAttribute) {
				$aAttributeValues[$aAttribute] = $oObject->Get($aAttribute);
			}
			
			$sClassName = get_class($oObject);
			$sJSON_FeatureProperties = json_encode($aAttributeValues);
			
			// 'add_script' is also a method
			// Be careful what EPSG to select!
			// Detect if Geometry was saved in GeoJSON or WKT
			// for geometryHandler.oFeature, use single quotes on the outside. Inner quotes will have been escaped.
			$oPage->add_ready_script(
<<<EOF
				 
				// Geom for {$sClassName}
				  
				if( typeof geometryHandler === "undefined" ) {
					geometryHandler = {};
				}
				
				geometryHandler.oFormat = {
					WKT: new ol.format.WKT(),
					GeoJSON: new ol.format.GeoJSON()
				};
				geometryHandler.aFeatures = [];
				geometryHandler.oFeature = ( "{$sGeomString}" != "" ? geometryHandler.oFormat.{$aGeomSettings['dataformat']}.readFeature("{$sGeomString}", { 
					dataProjection: "{$aGeomSettings['datacrs']}", 
					featureProjection: "{$aGeomSettings['mapcrs']}" 
				}) : null );
				
				if( geometryHandler.oFeature !== null ) {
					geometryHandler.oFeature.setProperties('{$sJSON_FeatureProperties}');
					geometryHandler.aFeatures.push( geometryHandler.oFeature );
				}
				
				geometryHandler.oVectorSource = new ol.source.Vector({ 
					features: geometryHandler.aFeatures
				});

				geometryHandler.oSharedStyle = new ol.style.Style({
					fill: new ol.style.Fill({
						color: "rgb(6,80,140, 0.25)"
					}),
					stroke: new ol.style.Stroke({
						color: "rgb(6,80,140)",
						width: 2
					}),
					image: new ol.style.Circle({
						radius: 7,
						fill: new ol.style.Fill({
							color: "rgb(139,196,88)"
						}),
						stroke: new ol.style.Stroke({
							color: "rgb(0,0,0)",
							width: 1.1
						})
					})
				});
				
				geometryHandler.aLayers = {
					osm: new ol.layer.Tile({
						source: new ol.source.OSM(),
						opacity: 0.5
					}),
					grb: new ol.layer.Tile({
						source: new ol.source.TileWMS({
							url: "https://geoservices.informatievlaanderen.be/raadpleegdiensten/GRB-basiskaart/wms?",
							params: {
								"LAYERS": "GRB_BSK",
								"LEGEND_OPTIONS": "forceLabels:on"
							}
						}),
						opacity: 0.5
					}),
					vector: new ol.layer.Vector({ 
						source: geometryHandler.oVectorSource, 
						style: geometryHandler.oSharedStyle
					})
					
				}
				
				if( geometryHandler.oFeature === null ) {
					geometryHandler.oCenter = [ {$aGeomSettings['mapcenter'][0]}, {$aGeomSettings['mapcenter'][1]} ];
				}
				else {
					
					geometryHandler.aExtent = geometryHandler.aLayers.vector.getSource().getExtent();
					geometryHandler.oCenter = [ 
						( geometryHandler.aExtent[0] + geometryHandler.aExtent[2] ) / 2,
						( geometryHandler.aExtent[1] + geometryHandler.aExtent[3] ) / 2,
					];
				}
				// Center: EPSG:3857 - [ 358652.11242031807, 6606360.84951076 ]
				geometryHandler.oMap = new ol.Map({
					target: "ol-map",
					layers: [
						// the last layer you add, is on top.
						geometryHandler.aLayers.{$sDefaultBaseMap},
						geometryHandler.aLayers.vector 
					],
					view: new ol.View({
						center: geometryHandler.oCenter,
						zoom: "{$aGeomSettings['mapzoom']}",
						projection: "{$aGeomSettings['mapcrs']}"
					}),
					controls: ol.control.defaults().extend([ new ol.control.FullScreen() ])
				});
				
				// Auto-adjust zoom
				if( geometryHandler.oFeature ) {
					
					// Workaround to keep zoom
					geometryHandler.oResolution = geometryHandler.oMap.getView().getResolution();
					geometryHandler.oMap.getView().fit( geometryHandler.aExtent, geometryHandler.oMap.getSize() );
					geometryHandler.oMap.getView().setResolution(geometryHandler.oResolution);
				}
			
				// For some reason, OpenLayers displays a blank map, until you call updateSize() on the ol.Map object.
				// Could this have to do with iTop tab behavior? Further investigation needed, only seems to work on second click now (might just appear to do so). 
				// Not sure if it is an iTop (or Zend) issue, or OpenLayers. 
				// Work-around seems to be to add a minor delay before executing the ol.Map.updateSize() method
				// The tab container  
					$("ul[role='tablist'] > li > a > span:contains('{$sTabName}')").parent().parent().on("click", function(evt){
					setTimeout( function(){ geometryHandler.oMap.updateSize(); }, 1000);
				});			
					 
				// Hide or disable ( textarea in edit mode! ). Click event will not work here (to show Geometry tab)
				// Alternatively, you could do this with CSS
				$("div[data-attcode='geom']").hide();
								 
				// Fix: if you go to the Geometry tab first; then pick Modify, the map is not displayed properly either. 
				$(document).ready(function(){
					setTimeout( function(){ geometryHandler.oMap.updateSize(); }, 1000 );
				});
				
				// Change background map
				$(document.body).on("change", "#geometryHandlerBaseMap", function(e) {
					
					geometryHandler.oMap.getLayers().clear();
					geometryHandler.oMap.addLayer( geometryHandler.aLayers[$("#geometryHandlerBaseMap").val()] );
					geometryHandler.oMap.addLayer( geometryHandler.aLayers.vector );
					
					if( typeof geometryHandler.ConfigureDrawMode !== "undefined") {
						// Re-add interactions
						geometryHandler.ConfigureDrawMode();
					}
					
					// For user convience, save basemap
					$.post("{$sAjaxHandlerUrl}", { 
						action: "remember_last_used_basemap", 
						data: { 
							basemap: $("#geometryHandlerBaseMap").val(),
							class: "{$sClassName}"
						}
					});
					
				});
				
EOF
			);
			
			// View
			if (!$bEditMode)
			{
				
				$oPage->add_ready_script(
<<<EOF
				
					// OpenLayers - View mode
					
EOF
				);
				
			}
			else {
			 
				$oPage->add_ready_script(
<<<EOF
					
					// OpenLayers - Editing allows extra interactions.
					
					geometryHandler.oDeleteCondition = function(mapBrowserEvent) {
						return ol.events.condition.click(mapBrowserEvent) && ol.events.condition.shiftKeyOnly(mapBrowserEvent)
					};
					
					// Modify has a deleteCondition, but it does not work with Points. 
					// It only works with vertex = where two lines meet.
					geometryHandler.oModify = new ol.interaction.Modify({ 
						source: geometryHandler.aLayers.vector.getSource(),
						deleteCondition: geometryHandler.oDeleteCondition
					});
					
					geometryHandler.oDraw = new ol.interaction.Draw({
						source: geometryHandler.aLayers.vector.getSource(),
						type: $("#geometryHandlerType").val(),
						style: geometryHandler.oSharedStyle
					});
					
					geometryHandler.oSnap = new ol.interaction.Snap({
						source: geometryHandler.aLayers.vector.getSource()
					});
					
					geometryHandler.oSelect = new ol.interaction.Select({
						condition: geometryHandler.oDeleteCondition
					});
					
					// Add interactions 
					geometryHandler.oMap.addInteraction( geometryHandler.oDraw );
					geometryHandler.oMap.addInteraction( geometryHandler.oModify );
					geometryHandler.oMap.addInteraction( geometryHandler.oSnap );
					// geometryHandler.oMap.addInteraction( geometryHandler.oSelect );		
					  
					$("body").on("click", "#geometryHandlerClear", function(e){
					
						// Remove
						geometryHandler.oFeature = null;
						geometryHandler.aLayers.vector.getSource().clear();
														
						// Save in geom field 
						$("textarea[name='attr_geom']").val("");
						
					});
					
					$("#geometryHandlerType").on("change", function(e){
		
						geometryHandler.ConfigureDrawMode();
						
					});
						 
					// Get last drawn geometry type
					if( geometryHandler.oFeature ) {
						$("#geometryHandlerType").val( geometryHandler.oFeature.getGeometry().getType() );
					}
				
					geometryHandler.ConfigureDrawMode = function() {
					
						geometryHandler.oMap.removeInteraction( geometryHandler.oDraw );
					
						geometryHandler.oDraw = new ol.interaction.Draw({
							source: geometryHandler.aLayers.vector.getSource(),
							type: $("#geometryHandlerType").val(),
							style: geometryHandler.oSharedStyle
						});
						
						geometryHandler.oDraw.on("drawstart", function(e){
							
							// Remove modify, will cause issues when user double-clicks to set new point
							geometryHandler.oMap.removeInteraction( geometryHandler.oModify );
							
							// Clear previous features
							geometryHandler.aLayers.vector.getSource().clear();
							
							// Add modify again
							geometryHandler.oMap.addInteraction( geometryHandler.oModify );
														
						});
						
						geometryHandler.oDraw.on("drawend", function(e){
							
							var f = e.feature;
							
							// Save in geom field 
							$("textarea[name='attr_geom']").val( geometryHandler.oFormat.{$aGeomSettings['dataformat']}.writeGeometry(f.getGeometry()) );
														
						}); 
						
						geometryHandler.oMap.addInteraction( geometryHandler.oDraw );
						
					}
			
					// Always configure draw mode
					geometryHandler.ConfigureDrawMode();
					
EOF
				);
			}
		}
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

class cPopupMenuExtension_GeometryHandler implements iPopupMenuExtension
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
                         * Insert an item into the Actions menu of a list
                         *
                         * $param is a DBObjectSet containing the list of objects       
                         */      
                        case iPopupMenuExtension::MENU_OBJLIST_ACTIONS:
							break;
 
                        /**
                         * Insert an item into the Toolkit menu of a list
                         *
                         * $param is a DBObjectSet containing the list of objects
                         */      
                        case iPopupMenuExtension::MENU_OBJLIST_TOOLKIT:
							break;
 
                        /**
                         * Insert an item into the Actions menu on an object's details page
                         *
                         * $param is a DBObject instance: the object currently displayed
                         */      
                        case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
							// For any object, add a menu "Google this..." that opens google search in another window
							// with the name of the object as the text to search
							
							// Only for objects with features (if none specified yet, don't show menu. 'Other actions' is not shown when modifying.)
							
							// Get list of attributes
							$aAttributeList = Metamodel::GetAttributesList(get_class($param));
							
							if( in_array('geom', $aAttributeList) == true ) {
								if( $param->Get('geom') != '' ) {
								
									// Add a separator
									$aResult[] = new SeparatorPopupMenuItem(); // Note: separator does not work in iTop 2.0 due to Trac #698, fixed in 2.0.1
	 
									// Add a new menu item that triggers a custom JS function defined in our own javascript file: js/sample.js
									$sModuleDir = basename(dirname(__FILE__));
									$sJSFileUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/js/geometry-actions.js';
									
									// It doesn't seem to be necessary to self-check if $aIncludeJSFiles should only include the JavaScript file once.
									$aResult[] = new JSPopupMenuItem(/* $sUUID */ 'geometryHandler_Open_OpenStreetMap', /* $sLabel */ Dict::S('UI:Geom:Menu:ShowOpenStreetMap'), /* $sJSCode */ 'geometryHandler.Show_OpenStreetMap()', /* $aIncludeJSFiles */ array($sJSFileUrl));
									$aResult[] = new JSPopupMenuItem(/* $sUUID */ 'geometryHandler_Copy_As_GeoJSON', /* $sLabel */ Dict::S('UI:Geom:Menu:CopyAsGeoJSON'), /* $sJSCode */ 'geometryHandler.Copy_As_GeoJSON()', /* $aIncludeJSFiles */ array($sJSFileUrl));
	 
								}
							}
							break;
 
                        /**
                         * Insert an item into the Dashboard menu
                         *
                         * The dashboad menu is shown on the top right corner of the page when
                         * a dashboard is being displayed.
                         * 
                         * $param is a Dashboard instance: the dashboard currently displayed
                         */      
                        case iPopupMenuExtension::MENU_DASHBOARD_ACTIONS:
							break;
 
                        /**
                         * Insert an item into the User menu (upper right corner of the page)
                         *
                         * $param is null
                         */
                        case iPopupMenuExtension::MENU_USER_ACTIONS:
							break;
 
                }
                return $aResult;
        }
}
