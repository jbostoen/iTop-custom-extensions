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
	* Function for debugging only
	* @return Array
	*/
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
	
		// Get path to ajax.handler.php
		$sModuleDir = basename(dirname(dirname(__FILE__)));
		
		// Module settings, defaults.
		$aGeomSettings = MetaModel::GetModuleSetting('jb-geom', 'default', array( 
			'dataformat' => 'WKT',
			'datacrs' => 'EPSG:3857',
			'datatypes' => array('Point', 'LineString', 'Polygon'),
			'mapcrs' => 'EPSG:3857',
			'mapcenter' => array( 358652.11242031807, 6606360.84951076 ),
			'mapzoom' => 17,
		));
		
		// Duplicates of linked stuff seems to be handled appropriately, but not for styles.
		// Hence this boolean - defined on the Page object (NOT just the dashlet!)
		if(isset($oPage->bInitiatedOpenLayers) == false) {
			
			// Add reference to OpenLayers JS and CSS
			$oPage->add_linked_stylesheet('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css');
			$oPage->add_linked_script('https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js');
			$oPage->add_linked_script(utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/js/custom_search_form_handler.js');
			
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
			
			// @todo: Find out why DashletMapOverview_ProcessFeatures is undefined at this point if used with add_ready_script. Generated output seems fine?
			$oPage->add_script(
<<<EOF
				if(typeof geometryHandler === "undefined") {
					geometryHandler = {};
				}
				
				function DashletMapOverview_ProcessFeatures(sDashletMapOverViewId, oData) {
					geometryHandler[sDashletMapOverViewId].oVectorSource.clear();
					
					$.each(oData, function(i) {
						if(typeof oData[i].geom !== "undefined" && oData[i].geom != "" ) {
							var oFeature = geometryHandler[sDashletMapOverViewId].oFormat.{$aGeomSettings["dataformat"]}.readFeature(oData[i].geom);
							oFeature.setProperties(oData[i]);
							geometryHandler[sDashletMapOverViewId].oVectorSource.addFeature(oFeature);
						}
					});
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
		
		// At Combodo: after reading your 'todo', this should probably change in the future. :)
		$oObjectSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sQuery));
		
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
		
		
		$sHtml = "<div id=\"ds_$sId\" class=\"search_box\">\n";
		$aExtraParams['currentId'] = $sId;
		$aExtraParams['callback'] = "function(oData) { DashletMapOverview_ProcessFeatures('{$sId}', oData); }";
		$oSearchForm = new CustomSearchForm();
		$sHtml .= $oSearchForm->GetSearchForm($oPage, $oObjectSet, $aExtraParams);
		$sHtml .= "</div>\n";
		$oPage->add($sHtml);
		
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
		$aAttributeList[] = 'id';
		
		// Get path to ajax.handler.php
		$sAjaxHandlerUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/ajax.handler.php';
		
		// Does a cookie exist with a preferred basemap for this class for this user?
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
					// Opted not to pass 'geom' as an extra property
					$aFeature['properties'][$aAttribute] = $oObject->Get($aAttribute);
				}
			}
			$aFeatures[] = $aFeature;
		}

		// for geometryHandler["{$sId}"].oFeature, use single quotes on the outside. Inner quotes will have been escaped.
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

			geometryHandler["{$sId}"] = {};
			geometryHandler["{$sId}"].oFormat = {
				WKT: new ol.format.WKT(),
				GeoJSON: new ol.format.GeoJSON()
			};
			geometryHandler["{$sId}"].aFeatures = [];				
			geometryHandler["{$sId}"].aFeatureSet = {$aJSON_Features};
			
			$.each(geometryHandler["{$sId}"].aFeatureSet, function(i) {
				// Must contain geometry
				if(geometryHandler["{$sId}"].aFeatureSet[i].geometry != "" && geometryHandler["{$sId}"].aFeatureSet[i].geometry !== null) {
					var oFeature = geometryHandler["{$sId}"].oFormat.{$aGeomSettings['dataformat']}.readFeature(geometryHandler["{$sId}"].aFeatureSet[i].geometry, { 
						dataProjection: "{$aGeomSettings['datacrs']}", 
						featureProjection: "{$aGeomSettings['mapcrs']}" });
					oFeature.setProperties(geometryHandler["{$sId}"].aFeatureSet[i].properties);
					geometryHandler["{$sId}"].aFeatures.push(oFeature);
				}
			});
			
			geometryHandler["{$sId}"].oVectorSource = new ol.source.Vector({ 
				features: geometryHandler["{$sId}"].aFeatures
			});

			geometryHandler["{$sId}"].oSharedStyle = new ol.style.Style({
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
			
			geometryHandler["{$sId}"].aLayers = {
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
					source: geometryHandler["{$sId}"].oVectorSource, 
					style: geometryHandler["{$sId}"].oSharedStyle
				})
				
			}
			
			if( geometryHandler["{$sId}"].aLayers.vector.getSource().getFeatures().length == 0 ) {
				geometryHandler["{$sId}"].oCenter = [ {$aGeomSettings['mapcenter'][0]}, {$aGeomSettings['mapcenter'][1]} ];
			}
			else {
				
				geometryHandler["{$sId}"].aExtent = geometryHandler["{$sId}"].aLayers.vector.getSource().getExtent();
				geometryHandler["{$sId}"].oCenter = [ 
					( geometryHandler["{$sId}"].aExtent[0] + geometryHandler["{$sId}"].aExtent[2] ) / 2,
					( geometryHandler["{$sId}"].aExtent[1] + geometryHandler["{$sId}"].aExtent[3] ) / 2,
				];
			}
			
			geometryHandler["{$sId}"].oMap = new ol.Map({
				target: "{$sId}_map",
				layers: [
						// the last layer added appears on top.
						geometryHandler["{$sId}"].aLayers.{$sDefaultBaseMap},
						geometryHandler["{$sId}"].aLayers.vector 
				],
				view: new ol.View({
					center: geometryHandler["{$sId}"].oCenter,
					zoom: "{$aGeomSettings['mapzoom']}",
					projection: "{$aGeomSettings['mapcrs']}"
				}),
				controls: ol.control.defaults().extend([ new ol.control.FullScreen() ])
			});
			
			geometryHandler["{$sId}"].oMap.getView().fit( geometryHandler["{$sId}"].aExtent, geometryHandler["{$sId}"].oMap.getSize() );
			
			// Add single click event (prevents from firing on double-click which is 'zoom' by default)
			geometryHandler["{$sId}"].oSelect = new ol.interaction.Select();
			
			geometryHandler["{$sId}"].oSelect.on("select", function(e) {
				if(e.target.getFeatures().getLength() == 1) {
					// Single feature? Then redirect.
					document.location = 'UI.php?operation=details&class={$sClassName}&id=' + e.selected[0].get('id');
				}
			});
			
			geometryHandler["{$sId}"].oMap.addInteraction(geometryHandler["{$sId}"].oSelect);
			
			// Fix: if you go to the Geometry tab first; then pick Modify, the map is not displayed properly either. 
			$(document).ready(function(){
			//	setTimeout( function(){ geometryHandler["{$sId}"].oMap.updateSize(); }, 1000);
			});
			
			// Change background map
			$(document.body).on("change", "#geometryHandlerBaseMap", function(e) {
				
				geometryHandler["{$sId}"].oMap.getLayers().clear();
				geometryHandler["{$sId}"].oMap.addLayer( geometryHandler["{$sId}"].aLayers[$("#geometryHandlerBaseMap").val()] );
				geometryHandler["{$sId}"].oMap.addLayer( geometryHandler["{$sId}"].aLayers.vector );
				
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
