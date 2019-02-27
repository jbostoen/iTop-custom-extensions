<?php

/**
* Generic Geometry extension
*/

/**
* Class cApplicationUIExtension_geom. Adds tab with map, makes textbox 'geom' invisible
*/
class cApplicationUIExtension_geom implements iApplicationUIExtension
{
	/**
	* Called when building the Actions menu for a single object or a list of objects
	* 
	* @param DBObjectSet $oSet DB Object set
	* 
	* @return Array
	*/
	public function EnumAllowedActions($oSet) {
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
		
		$aAttributeList = Metamodel::GetAttributesList(get_class($oObject));
		
		if( in_array('geom', $aAttributeList) == true ) {		
		
			// Module settings, defaults.
			$aGeomSettings = MetaModel::GetModuleSetting('jb-geom', 'default', array( 
				'datacrs' => 'EPSG:3857',
				'datatypes' => array('Point','LineString','Polygon'),
				'mapcrs' => 'EPSG:3857',
				'mapcenter' => array( 358652.11242031807, 6606360.84951076 ),
				'mapzoom' => 17,
				'format' => 'WKT'
			)); 
			
			// Module settings, specifics. In XML, most nodes seem to start with a non-capital.
			if( MetaModel::GetModuleSetting('jb-geom', strtolower(get_class($oObject)), '') != '') {
				
				$aClassSpecificSettings = MetaModel::GetModuleSetting('jb-geom', strtolower(get_class($oObject)), array() ); 
				
				foreach( $aClassSpecificSettings as $k => $v ) {
					$aGeomSettings[$k] = $v;
				} 
				
			}
			
			$sGeomString = ( $aGeomSettings['dataformat'] == 'GeoJSON' ? addcslashes($oObject->Get('geom'), '"') : $oObject->Get('geom') ); 
			
			$sTabName = Dict::S('Location:Geometry');
			$oPage->SetCurrentTab($sTabName); 
			
			// It seems iTop just outputs a script as text if you set it in $oPage->add()? 
			
			$oPage->add_linked_stylesheet('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css');
			$oPage->add_linked_script('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js');
			
			if( $bEditMode ) {
				$oPage->add('<button type="button" id="geomClear">'.Dict::S('UI:Geom:Clear').'</button> | 
					<select id="geomType">
				');

				foreach( $aGeomSettings['datatypes'] as $sDataType => $sDataValue ){
					$oPage->add('<option value="'.$sDataValue.'">'.Dict::S('UI:Geom:'.$sDataValue).'</option>'); 
				} 
						
				$oPage->add('
					</select> | 
					
					<select id="geomMap">
						<option value="grb">GRB</option>
						<option value="osm">OpenStreetMap</option>
					</select>
					
					<hr>
				');
			}
			
			$oPage->add('<div id="ol-map" class="ol-map" style="width: 100%; height: 500px;"></div>');

			
			// 'add_script' is also a method
			// be careful what EPSG to select!
			// for testing purposes, we'll try to detect if Geometry was saved in GeoJSON or WKT and read it anyway
			// for geom.oFeature, use single quotes on the outside. Inner quotes will have been escaped.
			$oPage->add_ready_script('
				  
				geom = {};
				geom.oFormat = {
					WKT: new ol.format.WKT(),
					GeoJSON: new ol.format.GeoJSON()
				};
				geom.oFeatures = [];
				geom.oFeature = ( "'.$sGeomString.'" != "" ? geom.oFormat.'.( preg_match('/^{"type":"Feature",.*/', $sGeomString) == true ? 'GeoJSON' : 'WKT' ).'.readFeature("'.$sGeomString.'", { dataProjection: "'.$aGeomSettings['datacrs'].'", featureProjection: "'.$aGeomSettings['mapcrs'].'" }) : null );
				
				if( geom.oFeature !== null ) {
					geom.oFeatures.push( geom.oFeature );
				}
				
				geom.oVectorSource = new ol.source.Vector({ 
					features: geom.oFeatures
				});
				

				geom.oSharedStyle = new ol.style.Style({
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
				
				
				geom.aLayers = {
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
						source: geom.oVectorSource, 
						style: geom.oSharedStyle
					})
					
				}
				
				
				if( geom.oFeature === null ) {
					geom.oCenter = [ '.$aGeomSettings['mapcenter'][0].', '.$aGeomSettings['mapcenter'][1].' ];
				}
				else {
					
					aExtent = geom.aLayers.vector.getSource().getExtent();
					geom.oCenter = [ 
						( aExtent[0] + aExtent[2] ) / 2,
						( aExtent[1] + aExtent[3] ) / 2,
					];
				}
				
			 
				// Center: EPSG:3857 - [ 358652.11242031807, 6606360.84951076 ]
				geom.oMap = new ol.Map({
					target: "ol-map",
					layers: [
						// the last layer you add, is on top.
						geom.aLayers.grb,
						geom.aLayers.vector 
					],
					view: new ol.View({
						center: geom.oCenter,
						zoom: "'.$aGeomSettings['mapzoom'].'",
						projection: "'.$aGeomSettings['mapcrs'].'"
					})
				});
				
				// Auto-adjust zoom
				if( geom.oFeature ) {
					
					// Workaround to keep zoom
					oResolution = geom.oMap.getView().getResolution();
					geom.oMap.getView().fit( aExtent, geom.oMap.getSize() );
					geom.oMap.getView().setResolution(oResolution);
				}
				
			
				// For some reason, OpenLayers displays a blank map, until you call updateSize() on the ol.Map object.
				// Could this have to do with iTop tab behavior? Further investigation needed, only seems to work on second click now (might just appear to do so). 
				// Not sure if it is an iTop (or Zend) issue, or OpenLayers. 
				// Work-around seems to be to add a minor delay before executing the ol.Map.updateSize() method
				// The tab container  
				$("ul[role=\'tablist\'] > li > a > span:contains(\''.$sTabName.'\')").parent().parent().on("click", function(evt){
					setTimeout( function(){ geom.oMap.updateSize(); }, 1000);
				});
								
					 
				// Hide or disable ( textarea in edit mode! ). Click event will not work here (to show Geometry tab)
				// Alternatively, you could do this with CSS
				$("div[data-attcode=\'geom\']").hide();
								 
				// Fix: if you go to the Geometry tab first; then pick Modify, the map is not displayed properly either. 
				$(document).ready(function(){
					setTimeout( function(){ geom.oMap.updateSize(); }, 1000 );
				});
				
				// Change background map
				$(document.body).on("change", "#geomMap", function(e) {
					
					geom.oMap.getLayers().clear();
					geom.oMap.addLayer( geom.aLayers[$("#geomMap").val()] );
					geom.oMap.addLayer( geom.aLayers.vector );
					
					if( typeof ol_changeDrawMode !== "undefined") {
						// Re-add interactions
						ol_changeDrawMode();
					}
					
					
				});
				
				
				
				

			');
				
			// View
			if (!$bEditMode)
			{
				
				$oPage->add_ready_script('
				
					// OpenLayers - View mode
					
				');
				
			}
			else {
			 
				$oPage->add_ready_script('
					
					// OpenLayers - Editing requires extra interactions.
					
					geom.oDeleteCondition = function(mapBrowserEvent) {
						return ol.events.condition.click(mapBrowserEvent) && ol.events.condition.shiftKeyOnly(mapBrowserEvent)
					};
					
					// Modify has a deleteCondition, but it does not work with Points. 
					// It only works with vertex = where two lines meet.
					geom.oModify = new ol.interaction.Modify({ 
						source: geom.aLayers.vector.getSource(),
						deleteCondition: geom.oDeleteCondition
					});
					
					geom.oDraw = new ol.interaction.Draw({
						source: geom.aLayers.vector.getSource(),
						type: $("#geomType").val(),
						style: geom.oSharedStyle
					});
					
					geom.oSnap = new ol.interaction.Snap({
						source: geom.aLayers.vector.getSource()
					});
					
					geom.oSelect = new ol.interaction.Select({
						condition: geom.oDeleteCondition
					});
					
					// Add interactions 
					geom.oMap.addInteraction( geom.oDraw );
					geom.oMap.addInteraction( geom.oModify );
					geom.oMap.addInteraction( geom.oSnap );
					// geom.oMap.addInteraction( geom.oSelect );		
					  
					$("body").on("click", "#geomClear", function(e){
					
						// Remove
						geom.oFeature = null;
						geom.aLayers.vector.getSource().clear();
														
						// Save in geom field 
						$("textarea[name=\'attr_geom\']").val("");
						
					});
					
					$("#geomType").on("change", function(e){
		
						ol_changeDrawMode();
						
					});
						 
					// Get last drawn geometry type
					if( geom.oFeature ) {
						$("#geomType").val( geom.oFeature.getGeometry().getType() );
						ol_changeDrawMode();
					}

				
					function ol_changeDrawMode() {
					
						geom.oMap.removeInteraction( geom.oDraw );
					
						geom.oDraw = new ol.interaction.Draw({
							source: geom.aLayers.vector.getSource(),
							type: $("#geomType").val(),
							style: geom.oSharedStyle
						});
						
						geom.oDraw.on("drawstart", function(e){
							
							// Remove modify, will cause issues when user double-clicks to set new point
							geom.oMap.removeInteraction( geom.oModify );
							
							// Clear previous features
							geom.aLayers.vector.getSource().clear();
							
							// Add modify again
							geom.oMap.addInteraction( geom.oModify );
														
						});
						
						geom.oDraw.on("drawend", function(e){
							
							var f = e.feature;
							
							// Save in geom field 
							$("textarea[name=\'attr_geom\']").val( geom.oFormat.'.$aGeomSettings['dataformat'].'.writeFeature(f) );
														
						}); 
						
						geom.oMap.addInteraction( geom.oDraw );
						
					}
			
				');
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


