<?php
	
/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Defines cDashletMapOverview
 */
 
 class DashletMapOverview extends Dashlet
{

	/**
	* Dashlet constructor.
	*
	* @param \ModelReflection $oModelReflection
	* @param string $sId
	*/
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);

		$this->aProperties['title'] = Dict::S('UI:DashletMapOverview:Label');
		$this->aProperties['query'] = 'SELECT UserRequest';
		$this->aProperties['height'] = '500';
		// $this->aCSSClasses[] = 'dashlet-inline'; -> won't use 100% width
	}
	
	/**
	* @return array
	*/
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletMapOverview:Label'),
			'icon' => 'env-'.utils::GetCurrentEnvironment().'/jb-geom/images/map.png',
			'description' => Dict::S('UI:DashletMapOverview:Description'),
		); 
	}
	
	/**
	* @param \DesignerForm $oForm
	*/
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		// Add fields to the dashlet properties
		$oField = new DesignerTextField('title', Dict::S('UI:DashletMapOverview:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletMapOverview:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
		
		$oField = new DesignerIntegerField('height', Dict::S('UI:DashletMapOverview:Prop-Height'), $this->aProperties['height']);
		$oForm->AddField($oField);
	}
	
	
	/**
	* @return array
	*/
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{

		if(isset($oPage->bInitiatedOpenLayers) == false) {
			
			// Add reference to OpenLayers JS and CSS
			$oPage->add_linked_stylesheet('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css');
			$oPage->add_linked_script('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js');

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
		}
		
		// Get properties
		$sQuery = $this->aProperties['query'];
		$sTitle = $this->aProperties['title'];
		$iHeight = (int)$this->aProperties['height'];
		
		$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8');

		$sId = utils::GetSafeId('dashlet_map_overview_'.($bEditMode? 'edit_' : '').$this->sId);
		
		// Does a cookie exist with a preferred basemap for this class for this user?
		$sDefaultBaseMap = 'osm';
		$sCookieName = 'itop_geometryHandler_basemap_used_for_'.get_class($oObject);
		if(isset($_COOKIE[$sCookieName]) == true ) {
			// Renew for another 30 days
			setcookie($sCookieName, $_COOKIE[$sCookieName], time()+3600*24*30, '/');
			$sDefaultBaseMap = $_COOKIE[$sCookieName];
		}
			
		// Add header
		if ($sHtmlTitle != '')
		{
			$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
		}
		
		$oPage->add('<div class="dashlet-content">');
	
		// @todo Review if the search block can be manipulated so it works the same way but shows the map instead with filtered features
		// @todo Figure out what these extra parameters are?
		// Add search block
		// if (isset($aExtraParams['query_params']))
		// {
			// $aQueryParams = $aExtraParams['query_params'];
		// }
		// elseif (isset($aExtraParams['this->class']) && isset($aExtraParams['this->id']))
		// {
			// $oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			// $aQueryParams = $oObj->ToArgsForQuery();
		// }
		// else
		// {
			// $aQueryParams = array();
		// }
	
		// $oFilter = DBObjectSearch::FromOQL($sQuery, $aQueryParams);
		// $sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		// $sType = 'search';
		// $oBlock = new DisplayBlock($oFilter, $sType);
		// $oBlock->Display($oPage, $sBlockId, $aExtraParams);
		
		$oObjectSet = new DBObjectSet(DBObjectSearch::FromOQL($sQuery));
		
		// Module settings, defaults.
		$aGeomSettings = MetaModel::GetModuleSetting('jb-geom', 'default', array( 
			'dataformat' => 'WKT',
			'datacrs' => 'EPSG:3857',
			'datatypes' => array('Point', 'LineString', 'Polygon'),
			'mapcrs' => 'EPSG:3857',
			'mapcenter' => array( 358652.11242031807, 6606360.84951076 ),
			'mapzoom' => 17,
		));
		
		$sClassName = $oObjectSet->GetClass();
		
		// Module settings, class specifics. In XML, most nodes seem to start with a non-capital.
		if(MetaModel::GetModuleSetting('jb-geom', strtolower($sClassName), '') != '') {
			
			$aClassSpecificSettings = MetaModel::GetModuleSetting('jb-geom', strtolower($sClassName), array() ); 
			
			foreach( $aClassSpecificSettings as $k => $v ) {
				$aGeomSettings[$k] = $v;
			} 
			
		}
		
		// Get list of attributes
		$aAttributeList = Metamodel::GetAttributesList($sClassName);
		
		// Get path to ajax.handler.php
		$sModuleDir = basename(dirname(__FILE__));
		$sAjaxHandlerUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/ajax.handler.php';
		
		// Does a cookie exist with a preferred basemap for this class for this user?
		// If not, assume OpenStreetMap
		$sDefaultBaseMap = 'osm';
		$sCookieName = 'itop_geometryHandler_basemap_used_for_'.$sId;
		
		if(isset($_COOKIE[$sCookieName]) == true ) {
			// Renew for another 30 days
			setcookie($sCookieName, $_COOKIE[$sCookieName], time()+3600*24*30, '/');
			$sDefaultBaseMap = $_COOKIE[$sCookieName];
		}
		
		// Push all features into array
		$aFeatures = Array();
		while ($oObject = $oObjectSet->Fetch()) {
			$aFeature = [];
			foreach($aAttributeList as $aAttribute) {
				if($aAttribute == 'geom') {
					$aFeature['geometry'] = $oObject->Get('geom');
				}
				else {
					$aFeature['properties'][$aAttribute] = $oObject->Get($aAttribute);
				}
			}
			$aFeatures[] = $aFeature;
		}

		// for geometryHandler_{$sId}.oFeature, use single quotes on the outside. Inner quotes will have been escaped.
		$aJSON_Features = json_encode($aFeatures);
		
		
		// Add content
		$oPage->add('
			<select id="geometryHandlerBaseMap">
				<option value="osm"'.($sDefaultBaseMap == 'osm' ? 'selected' : '').'>OpenStreetMap</option>
				<option value="grb"'.($sDefaultBaseMap == 'grb' ? 'selected' : '').'>GRB</option>
			</select>
		');
		
		$oPage->add(
<<<EOF
			<hr>
			<div id="{$sId}_map" class="ol-map" style="width: 100%; height: {$iHeight}px;"></div>
EOF
		);
		
		$oPage->add_ready_script(
<<<EOF

			geometryHandler_{$sId} = {};
			geometryHandler_{$sId}.oFormat = {
				WKT: new ol.format.WKT(),
				GeoJSON: new ol.format.GeoJSON()
			};
			geometryHandler_{$sId}.aFeatures = [];				
			geometryHandler_{$sId}.aFeatureSet = {$aJSON_Features};
			
			$.each(geometryHandler_{$sId}.aFeatureSet, function(i) {
				// Must contain geometry
				if(geometryHandler_{$sId}.aFeatureSet[i].geometry != "" && geometryHandler_{$sId}.aFeatureSet[i].geometry !== null) {
					var oFeature = geometryHandler_{$sId}.oFormat.{$aGeomSettings['dataformat']}.readFeature(geometryHandler_{$sId}.aFeatureSet[i].geometry, { 
						dataProjection: "{$aGeomSettings['datacrs']}", 
						featureProjection: "{$aGeomSettings['mapcrs']}" });
					oFeature.setProperties(geometryHandler_{$sId}.aFeatureSet[i].properties);
					geometryHandler_{$sId}.aFeatures.push(oFeature);
				}
			});
			
			geometryHandler_{$sId}.oVectorSource = new ol.source.Vector({ 
				features: geometryHandler_{$sId}.aFeatures
			});

			geometryHandler_{$sId}.oSharedStyle = new ol.style.Style({
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
			
			geometryHandler_{$sId}.aLayers = {
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
					source: geometryHandler_{$sId}.oVectorSource, 
					style: geometryHandler_{$sId}.oSharedStyle
				})
				
			}
			
			if( geometryHandler_{$sId}.aLayers.vector.getSource().getFeatures().length == 0 ) {
				geometryHandler_{$sId}.oCenter = [ {$aGeomSettings['mapcenter'][0]}, {$aGeomSettings['mapcenter'][1]} ];
			}
			else {
				
				geometryHandler_{$sId}.aExtent = geometryHandler_{$sId}.aLayers.vector.getSource().getExtent();
				geometryHandler_{$sId}.oCenter = [ 
					( geometryHandler_{$sId}.aExtent[0] + geometryHandler_{$sId}.aExtent[2] ) / 2,
					( geometryHandler_{$sId}.aExtent[1] + geometryHandler_{$sId}.aExtent[3] ) / 2,
				];
			}
			
			geometryHandler_{$sId}.oMap = new ol.Map({
				target: "{$sId}_map",
				layers: [
					// the last layer added appears on top.
					geometryHandler_{$sId}.aLayers.{$sDefaultBaseMap},
					geometryHandler_{$sId}.aLayers.vector 
				],
				view: new ol.View({
					center: geometryHandler_{$sId}.oCenter,
					zoom: "{$aGeomSettings['mapzoom']}",
					projection: "{$aGeomSettings['mapcrs']}"
				}),
				controls: ol.control.defaults().extend([ new ol.control.FullScreen() ])
			});
			
			geometryHandler_{$sId}.oMap.getView().fit( geometryHandler_{$sId}.aExtent, geometryHandler_{$sId}.oMap.getSize() );
											 
			// Fix: if you go to the Geometry tab first; then pick Modify, the map is not displayed properly either. 
			$(document).ready(function(){
			//	setTimeout( function(){ geometryHandler_{$sId}.oMap.updateSize(); }, 1000 );
			});
			
			// Change background map
			$(document.body).on("change", "#geometryHandlerBaseMap", function(e) {
				
				geometryHandler_{$sId}.oMap.getLayers().clear();
				geometryHandler_{$sId}.oMap.addLayer( geometryHandler_{$sId}.aLayers[$("#geometryHandlerBaseMap").val()] );
				geometryHandler_{$sId}.oMap.addLayer( geometryHandler_{$sId}.aLayers.vector );
				
				// For user convience, save basemap
				$.post("{$sAjaxHandlerUrl}", { 
					action: "remember_last_used_basemap", 
					data: { 
						basemap: $("#geometryHandlerBaseMap").val(),
						dashlet: "{$sId}"
					}
				});

			});

EOF
		);


		if($bEditMode)
		{
			$oPage->add('<div class="dashlet-blocker"></div>');
		}
 
		$oPage->add('</div>');
			
		$oPage->bInitiatedOpenLayers = true;
		
	}
 
}
