<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Defines ApplicationUIExtensionGeometryHandler
 */
 

/**
* Note: for compatibility with 2.5, you need to have some specific definitions ( DBObjectSet $oSet, WebPage $oPage )
**/

/**
* Class ApplicationUIExtensiongeometryHandler["common"]. Adds tab with map, makes textbox 'geom' invisible
*/
class ApplicationUIExtensionGeometryHandler implements iApplicationUIExtension
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
		$aAttributeList[] = 'id';
		
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
				$aGeomSettings = array_replace_recursive($aGeomSettings, $aClassSpecificSettings);
			}
			
			$sGeomString = ( $aGeomSettings['dataformat'] == 'GeoJSON' ? addcslashes($oObject->Get('geom'), '"') : $oObject->Get('geom') ); 
			
			// Get path to AJAX handler
			$sModuleDir = basename(dirname(__FILE__));
			$sAjaxHandlerUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/ajax/handler.php';
			
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
					<option value="osm"'.($sDefaultBaseMap == 'osm' ? ' selected' : '').'>OpenStreetMap</option>
					<option value="grb"'.($sDefaultBaseMap == 'grb' ? ' selected' : '').'>GRB</option>
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
			// for geometryHandler["common"].oFeature, use single quotes on the outside. Inner quotes will have been escaped.
			$oPage->add_ready_script(
<<<EOF
				 
				// Geom for {$sClassName}
				  
				if( typeof geometryHandler === "undefined" ) {
					geometryHandler = {};
				}
				if( typeof geometryHandler["common"] === "undefined" ) {
					geometryHandler["common"] = {};
				}
				
				geometryHandler["common"].oFormat = {
					WKT: new ol.format.WKT(),
					GeoJSON: new ol.format.GeoJSON()
				};
				geometryHandler["common"].aFeatures = [];
				geometryHandler["common"].oFeature = ( "{$sGeomString}" != "" ? geometryHandler["common"].oFormat.{$aGeomSettings['dataformat']}.readFeature("{$sGeomString}", { 
					dataProjection: "{$aGeomSettings['datacrs']}", 
					featureProjection: "{$aGeomSettings['mapcrs']}" 
				}) : null );
				
				if( geometryHandler["common"].oFeature !== null ) {
					geometryHandler["common"].oFeature.setProperties({$sJSON_FeatureProperties});
					geometryHandler["common"].aFeatures.push( geometryHandler["common"].oFeature );
				}
				
				geometryHandler["common"].oVectorSource = new ol.source.Vector({ 
					features: geometryHandler["common"].aFeatures
				});

				geometryHandler["common"].oSharedStyle = new ol.style.Style({
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
				
				geometryHandler["common"].aLayers = {
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
						source: geometryHandler["common"].oVectorSource, 
						style: geometryHandler["common"].oSharedStyle
					})
					
				}
				
				if( geometryHandler["common"].oFeature === null ) {
					geometryHandler["common"].oCenter = [ {$aGeomSettings['mapcenter'][0]}, {$aGeomSettings['mapcenter'][1]} ];
				}
				else {
					
					geometryHandler["common"].aExtent = geometryHandler["common"].aLayers.vector.getSource().getExtent();
					geometryHandler["common"].oCenter = [ 
						( geometryHandler["common"].aExtent[0] + geometryHandler["common"].aExtent[2] ) / 2,
						( geometryHandler["common"].aExtent[1] + geometryHandler["common"].aExtent[3] ) / 2,
					];
				}
				// Center: EPSG:3857 - [ 358652.11242031807, 6606360.84951076 ]
				geometryHandler["common"].oMap = new ol.Map({
					target: "ol-map",
					layers: [
						// the last layer added appears on top.
						geometryHandler["common"].aLayers.{$sDefaultBaseMap},
						geometryHandler["common"].aLayers.vector 
					],
					view: new ol.View({
						center: geometryHandler["common"].oCenter,
						zoom: "{$aGeomSettings['mapzoom']}",
						projection: "{$aGeomSettings['mapcrs']}"
					}),
					controls: ol.control.defaults().extend([ new ol.control.FullScreen() ])
				});
				
				// Auto-adjust zoom
				if( geometryHandler["common"].oFeature ) {
					
					// Workaround to keep zoom
					geometryHandler["common"].oResolution = geometryHandler["common"].oMap.getView().getResolution();
					geometryHandler["common"].oMap.getView().fit( geometryHandler["common"].aExtent, geometryHandler["common"].oMap.getSize() );
					geometryHandler["common"].oMap.getView().setResolution(geometryHandler["common"].oResolution);
				}
			
				// For some reason, OpenLayers displays a blank map, until you call updateSize() on the ol.Map object.
				// Could this have to do with iTop tab behavior? Further investigation needed, only seems to work on second click now (might just appear to do so). 
				// Not sure if it is an iTop (or Zend) issue, or OpenLayers. 
				// Work-around seems to be to add a minor delay before executing the ol.Map.updateSize() method
				// The tab container  
					$("ul[role='tablist'] > li > a > span:contains('{$sTabName}')").parent().parent().on("click", function(evt){
					setTimeout( function(){ geometryHandler["common"].oMap.updateSize(); }, 1000);
				});			
					 
				// Hide or disable ( textarea in edit mode! ). Click event will not work here (to show Geometry tab)
				// Alternatively, you could do this with CSS
				$("div[data-attcode='geom']").hide();
								 
				// Fix: if you go to the Geometry tab first; then pick Modify, the map is not displayed properly either. 
				$(document).ready(function(){
					setTimeout( function(){ geometryHandler["common"].oMap.updateSize(); }, 1000 );
				});
				
				// Change background map
				$(document.body).on("change", "#geometryHandlerBaseMap", function(e) {
					
					geometryHandler["common"].oMap.getLayers().clear();
					geometryHandler["common"].oMap.addLayer( geometryHandler["common"].aLayers[$("#geometryHandlerBaseMap").val()] );
					geometryHandler["common"].oMap.addLayer( geometryHandler["common"].aLayers.vector );
					
					if( typeof geometryHandler["common"].ConfigureDrawMode !== "undefined") {
						// Re-add interactions
						geometryHandler["common"].ConfigureDrawMode();
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
					
					geometryHandler["common"].oDeleteCondition = function(mapBrowserEvent) {
						return ol.events.condition.click(mapBrowserEvent) && ol.events.condition.shiftKeyOnly(mapBrowserEvent)
					};
					
					// Modify has a deleteCondition, but it does not work with Points. 
					// It only works with vertex = where two lines meet.
					geometryHandler["common"].oModify = new ol.interaction.Modify({ 
						source: geometryHandler["common"].aLayers.vector.getSource(),
						deleteCondition: geometryHandler["common"].oDeleteCondition
					});
					
					geometryHandler["common"].oDraw = new ol.interaction.Draw({
						source: geometryHandler["common"].aLayers.vector.getSource(),
						type: $("#geometryHandlerType").val(),
						style: geometryHandler["common"].oSharedStyle
					});
					
					geometryHandler["common"].oSnap = new ol.interaction.Snap({
						source: geometryHandler["common"].aLayers.vector.getSource()
					});
					
					geometryHandler["common"].oSelect = new ol.interaction.Select({
						condition: geometryHandler["common"].oDeleteCondition
					});
					
					// Add interactions 
					geometryHandler["common"].oMap.addInteraction( geometryHandler["common"].oDraw );
					geometryHandler["common"].oMap.addInteraction( geometryHandler["common"].oModify );
					geometryHandler["common"].oMap.addInteraction( geometryHandler["common"].oSnap );
					// geometryHandler["common"].oMap.addInteraction( geometryHandler["common"].oSelect );		
					  
					$("body").on("click", "#geometryHandlerClear", function(e){
					
						// Remove
						geometryHandler["common"].oFeature = null;
						geometryHandler["common"].aLayers.vector.getSource().clear();
														
						// Save in geom field 
						$("textarea[name='attr_geom']").val("");
						
					});
					
					$("#geometryHandlerType").on("change", function(e){
		
						geometryHandler["common"].ConfigureDrawMode();
						
					});
						 
					// Get last drawn geometry type
					if( geometryHandler["common"].oFeature ) {
						$("#geometryHandlerType").val( geometryHandler["common"].oFeature.getGeometry().getType() );
					}
				
					geometryHandler["common"].ConfigureDrawMode = function() {
					
						geometryHandler["common"].oMap.removeInteraction( geometryHandler["common"].oDraw );
					
						geometryHandler["common"].oDraw = new ol.interaction.Draw({
							source: geometryHandler["common"].aLayers.vector.getSource(),
							type: $("#geometryHandlerType").val(),
							style: geometryHandler["common"].oSharedStyle
						});
						
						geometryHandler["common"].oDraw.on("drawstart", function(e){
							
							// Remove modify, will cause issues when user double-clicks to set new point
							geometryHandler["common"].oMap.removeInteraction( geometryHandler["common"].oModify );
							
							// Clear previous features
							geometryHandler["common"].aLayers.vector.getSource().clear();
							
							// Add modify again
							geometryHandler["common"].oMap.addInteraction( geometryHandler["common"].oModify );
														
						});
						
						geometryHandler["common"].oDraw.on("drawend", function(e){
							
							var f = e.feature;
							
							// Save in geom field 
							$("textarea[name='attr_geom']").val( geometryHandler["common"].oFormat.{$aGeomSettings['dataformat']}.writeGeometry(f.getGeometry()) );
														
						}); 
						
						geometryHandler["common"].oMap.addInteraction( geometryHandler["common"].oDraw );
						
					}
			
					// Always configure draw mode
					geometryHandler["common"].ConfigureDrawMode();
					
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
