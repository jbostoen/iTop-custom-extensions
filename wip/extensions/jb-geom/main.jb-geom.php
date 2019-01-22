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
	* Not yet called by framework, but reserved. Must be implmented.
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
	public function OnDisplayProperties($oObject, $oPage, $bEditMode = false) : void {
		
		 
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
		
		if( in_array( get_class($oObject), ['Location', 'UserRequest'] ) == true ) {		
		
			// Module settings, defaults.
			$aGeomSettings = MetaModel::GetModuleSetting('jb-geom', 'default', array( 
				'datacrs' => 'EPSG:3857',
				'datatypes' => Array('Point','LineString','Polygon'),
				'mapcrs' => 'EPSG:3857',
				'mapcenter' =>  [ 358652.11242031807, 6606360.84951076 ],
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
			
			$oPage->add_linked_stylesheet('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.1.3/css/ol.css');
			$oPage->add_linked_script('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.1.3/build/ol.js');
			
			if( $bEditMode ) {
				$oPage->add('<button type="button" id="geomClear">'.Dict::S('UI:Geom:Clear').'</button> | 
					<select id="geomType">
				');

				foreach( $aGeomSettings['datatypes'] as $k => $v ){
					$oPage->add('<option value="'.$v.'">'.Dict::S('UI:Geom:'.$v).'</option>'); 
				} 
						
				$oPage->add('
					</select><br>
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
				geom.oFeature = ( \''.$sGeomString.'\' != \'\' ? geom.oFormat.'.( preg_match('/^{"type":"Feature",.*/', $sGeomString) == true ? 'GeoJSON' : 'WKT' ).'.readFeature(\''.$sGeomString.'\', { dataProjection: "'.$aGeomSettings['datacrs'].'", featureProjection: "'.$aGeomSettings['mapcrs'].'" }) : null );
				
				if( geom.oFeature !== null ) {
					geom.oFeatures.push( geom.oFeature );
				}
				
				geom.oVectorSource = new ol.source.Vector({ 
					features: geom.oFeatures
				});
				
				geom.oVectorLayer = new ol.layer.Vector({ 
					source: geom.oVectorSource, 
					style: ol_style
				});					

				if( geom.oFeature === null ) {
					geom.oCenter = [ '.$aGeomSettings['mapcenter'][0].', '.$aGeomSettings['mapcenter'][1].' ];
				}
				else {
					geom.oCenter = [ 
						( geom.oFeature.getGeometry().getExtent()[0] + geom.oFeature.getGeometry().getExtent()[2] ) / 2,
						( geom.oFeature.getGeometry().getExtent()[1] + geom.oFeature.getGeometry().getExtent()[3] ) / 2,
					];
				}
			 
				// Center: EPSG:3857 - [ 358652.11242031807, 6606360.84951076 ]
				geom.oMap = new ol.Map({
					target: "ol-map",
					layers: [
						// OpenLayers: the last layer you add, is on top.
						new ol.layer.Tile({
							source: new ol.source.OSM()
						}), 
						geom.oVectorLayer 
					],
					view: new ol.View({
						center: geom.oCenter,
						zoom: "'.$aGeomSettings['mapzoom'].'",
						projection: "'.$aGeomSettings['mapcrs'].'"
					})
				});
				
			
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
				
				// Style function.
				/* 
					// If you want to do something with vertices, you could use:
					geometry = feature.getGeometry();
					geometry.forEachSegment(function(start,end){ 
						styles.push(
							new ol.style.Style({ 
								geometry: new Point(end), 
								image: new ol.style.Icon({ ... }) 
							}) 
						) 
					});
				*/
				
				function ol_style(feature) {
					switch( feature.getGeometry().getType() ) {
					
						case "Point": 
						
							return [
								new ol.style.Style({ 
									image: new ol.style.Circle({	
										radius: 10,
										fill: new ol.style.Fill({
										  color: "rgba(255, 0, 0, 0.6)"
										}),
										stroke: new ol.style.Stroke({
										  color: "rgba(255, 0, 0, 1)",
										  width: 1
										})
									})
								})
							];
							
						case "Polygon": 
						
							return [
								new ol.style.Style({ 
									fill: new ol.style.Fill({
									  color: "rgba(255, 0, 0, 0.6)"
									}),
									stroke: new ol.style.Stroke({
									  color: "rgba(255, 0, 0, 1)",
									  width: 1
									})										 
								})
							];
							
						case "LineString": 								
						
							return [
								new ol.style.Style({ 										
									fill: new ol.style.Fill({
									  color: "rgba(255, 0, 0, 0.6)"
									}),
									stroke: new ol.style.Stroke({
									  color: "rgba(255, 0, 0, 1)",
									  width: 1
									}) 
								})
							];
							
						default: 
							// Should not happen
							break;
					}
				}	
					 
					 
				// Fix: if you go to the Geometry tab first; then pick Modify, the map is not displayed properly either. 
				$(document).ready(function(){
					setTimeout( function(){ geom.oMap.updateSize(); }, 1000 );
				});

			');
				
			// View
			if (!$bEditMode)
			{
				
				$oPage->add_ready_script('
				
					// Nothing else needed for OpenLayers - view mode
					
				');
				
			}
			else {
			 
				$oPage->add_ready_script('
					
					// Editing requires extra interactions.
					
					geom.oDeleteCondition = function(mapBrowserEvent) {
						return ol.events.condition.click(mapBrowserEvent) && ol.events.condition.shiftKeyOnly(mapBrowserEvent)
					};
					
					// Modify has a deleteCondition, but it does not work with Points. 
					// It only works with vertex = where two lines meet.
					geom.oModify = new ol.interaction.Modify({ 
						source: geom.oVectorSource,
						deleteCondition: geom.oDeleteCondition
					});
					
					geom.oDraw = new ol.interaction.Draw({
						source: geom.oVectorSource,
						type: $("#geomType").val()
					});
					
					geom.oSnap = new ol.interaction.Snap({
						source: geom.oVectorSource
					});
					
					geom.oSelect = new ol.interaction.Select({
						condition: geom.oDeleteCondition
					});
					
					// Add interactions 
					geom.oMap.addInteraction( geom.oDraw );
					geom.oMap.addInteraction( geom.oModify );
					geom.oMap.addInteraction( geom.oSnap );
					// geom.oMap.addInteraction( geom.oSelect );
					
					// Remove feature (Point) on select. Both these implementations gave an error 
					// geom.oSelect.getFeatures().on("add", function(e){ ... });
					// geom.oSelect.on("select", function(e) { var features = e.target.features;  ... });
					
					  
					$("body").on("click", "#geomClear", function(e){
					
						// Remove
						geom.oFeature = null;
						geom.oVectorSource.clear();
														
						// Save in geom field 
						$("textarea[name=\'attr_geom\']").val("");
						
					});
					
					$("#geomType").on("change", function(e){
		
						ol_changeDrawMode();
						
					});
						 
					// Get last drawn geometry type
					if( geom.oFeature !== null ) {
						$("#geomType").val( geom.oFeature.getGeometry().getType() );
						ol_changeDrawMode();
					}

				
					function ol_changeDrawMode() {
					
						geom.oMap.removeInteraction( geom.oDraw );
					
						geom.oDraw = new ol.interaction.Draw({
							source: geom.oVectorSource,
							type: $("#geomType").val()
						});
						
						geom.oDraw.on("drawstart", function(e){
							
							// Clear previous features
							geom.oVectorSource.clear();
														
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


