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
		$this->aProperties['attributes'] = 'id,friendlyname';
		$this->aProperties['feature_label'] = '';
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
		
		// @todo Watch Combodo developments to see if there's a better solution.
		$oField = new DesignerLongTextField('attributes', Dict::S('UI:DashletMapOverview:Prop-Attributes'), $this->aProperties['attributes']);
		$oForm->AddField($oField);
		
		$oField = new DesignerTextField('feature_label', Dict::S('UI:DashletMapOverview:Prop-FeatureLabel'), $this->aProperties['feature_label']);
		$oForm->AddField($oField);
	}
	
	/**
	* Function for debugging only
	* @return Array
	*/
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
	
		// Get properties
		$sQuery = $this->aProperties['query'];
		$sTitle = $this->aProperties['title'];
		$iHeight = (int)$this->aProperties['height'];
		$aAttributeList_Specified = explode(',', str_replace(' ', '', $this->aProperties['attributes']));
		$sId = utils::GetSafeId('dashlet_map_overview_'.($bEditMode? 'edit_' : '').$this->sId);
		
		// Always add 'id', 'geom', 'friendlyname' to specified attribute list
		// Not necessarily displayed, but it's important to fetch this data
		$aAttributeList_Specified = array_merge(['id', 'friendlyname', 'geom'], $aAttributeList_Specified);
		$aAttributeList_Specified = array_unique($aAttributeList_Specified);
		
		// Get path to ajax.handler.php
		$sModuleDir = basename(dirname(dirname(__FILE__)));
		
		// @Combodo: after reading your 'todo', this should probably change in the future. (CMDBObjectSet) :)
		$oObjectSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sQuery));
		
		// Get class name
		$sClassName = $oObjectSet->GetClass();
		
		// Get list of attributes
		$aAttributeList_Complete = Metamodel::GetAttributesList($sClassName);
		$aAttributeList_Complete[] = 'id';
		
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
		if(MetaModel::GetModuleSetting('jb-geom', strtolower($sClassName), '') != '') {
			$aClassSpecificSettings = MetaModel::GetModuleSetting('jb-geom', strtolower($sClassName), array() ); 
			$aGeomSettings = array_replace_recursive($aGeomSettings, $aClassSpecificSettings);
		}
		
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
				
				.geom-popup {
					width: 600px;
					padding: 10px;
					background-color: rgb(250, 250, 250);
					margin: auto;
					border-radius: 3px;
				}
				
				.geom-popup th {
					width: 200px;
				}
				
				.geom-popup td {
					width: 400px;
				}
				
				.geom-popup .mfp-close {
					position: relative;
					float: right;
					padding: 10px;
					font-weight: bold;
					width: 0;
					height: 0;
					margin-right: 15px;
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
			
			// Remember not to repeat the HTML and JavaScript above
			$oPage->bInitiatedOpenLayers = true;
			
		}
		
		$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8');

		
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
		
		
		// Change endpoint
		$aExtraParams['endpoint'] = utils::GetAbsoluteUrlModulesRoot().basename(dirname(dirname(__FILE__))).'/ajax/customsearchform.php';
		// Has to be put as a string to be passed to JavaScript, which will evaluate this. 
		// Use double quotes to replace {$sId}
		$aExtraParams['onSubmitSuccess'] = "function(oData) { 
			DashletMapOverview_ProcessFeatures('{$sId}', oData);
			geometryHandler['{$sId}'].oSelect.getFeatures().clear();
			geometryHandler['{$sId}'].oSelectAlt.getFeatures().clear();
		}"; 
		
		// If nothing defined: ask all
		$aExtraParams['attributes'] = (count($aAttributeList_Specified) == 0 ? [] : $aAttributeList_Complete);
		
		$oSearchForm = new CustomSearchForm();
		$sHtml .= $oSearchForm->GetSearchForm($oPage, $oObjectSet, $aExtraParams);
		$sHtml .= "</div>\n";
		$oPage->add($sHtml);
		
		
		// Get required translations
		$aAttributes_Labels = [];
		foreach((count($aAttributeList_Specified) == 0 ? $aAttributeList_Complete : $aAttributeList_Specified) as $sAttributeCode) {
			$aAttributes_Labels[$sAttributeCode] = MetaModel::GetLabel($sClassName, $sAttributeCode, /* bShowMandatory */ false);
		}
		
		$sAttributeLabels = json_encode($aAttributes_Labels);
		
		// Get path to ajax.handler.php
		$sAjaxHandlerUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/ajax/handler.php';
		
		// Does a cookie exist with a preferred basemap for this class for this user?
		$sDefaultBaseMap = 'osm';
		$sCookieName = 'itop_geometryHandler_basemap_used_for_'.$sId;
		
		if(isset($_COOKIE[$sCookieName]) == true ) {
			// Renew for another 30 days
			setcookie($sCookieName, $_COOKIE[$sCookieName], time()+3600*24*30, '/');
			$sDefaultBaseMap = $_COOKIE[$sCookieName];
		}
		
		// Push all features with all properties into array
		$aFeatures = Array();
		while ($oObject = $oObjectSet->Fetch()) {
			$aFeature = [];
			foreach($aAttributeList_Specified as $aAttribute) {
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
		
		// Optional feature label
		$sFeatureLabel = ($this->aProperties['feature_label'] == '' ? '""' : 'oFeature.get("'.$this->aProperties['feature_label'].'")');
		
		// Add content
		$oPage->add('
			<select id="geometryHandlerBaseMap">
				<option value="osm"'.($sDefaultBaseMap == 'osm' ? ' selected' : '').'>OpenStreetMap</option>
				<option value="grb"'.($sDefaultBaseMap == 'grb' ? ' selected' : '').'>GRB</option>
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

			geometryHandler["{$sId}"].oSharedStyle = function(oFeature) {
				var oStyle = new ol.style.Style({
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
					}),
					text: new ol.style.Text({
						text: {$sFeatureLabel},
						textAlign: ( oFeature.getGeometry().getType() == 'Point' ? 'left' : 'center' ),
						offsetX: ( oFeature.getGeometry().getType() == 'Point' ? 10 : 0 )
					})
				});
				
				return [ oStyle ];
			}
			
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
			
			geometryHandler["{$sId}"].aTranslations = {$sAttributeLabels};
			
			// Add single click event (prevents from firing on double-click which is 'zoom' by default)
			geometryHandler["{$sId}"].oMap.on("click", function(e) {
				
				// If no feature found, create new one.
				// @todo Check if reprojection is needed
				var aFeatures = geometryHandler["{$sId}"].oMap.getFeaturesAtPixel(e.pixel);
				if(aFeatures === null) {
					var oNewFeature = new ol.Feature(new ol.geom.Point(e.coordinate));
					var sGeometry = geometryHandler["{$sId}"].oFormat.{$aGeomSettings['dataformat']}.writeFeature(oNewFeature);
					document.location = 'UI.php?operation=new&class={$sClassName}&c[menu]=New{$sClassName}&default[geom]=' + sGeometry;
					return;
				}
				
				geometryHandler["{$sId}"].oMap.forEachFeatureAtPixel(e.pixel, function (oFeature, oLayer) {
					
					if(oLayer === geometryHandler["{$sId}"].aLayers.vector) {
						
						// Alt? 
						if( ol.events.condition.click(e) && ol.events.condition.altKeyOnly(e) ) {
							document.location = 'UI.php?operation=details&class={$sClassName}&id=' + oFeature.get("id");
						}
						else {
							// @todo Implement dropdown list later
								
							// Build rows
							// @todo Images?
							var aRows = [];
							$.each(geometryHandler["{$sId}"].aTranslations, function(k,v) {
								if(k == 'geom') {
									return;
								}
								var val = oFeature.get(k);
								val = (typeof val === "undefined" ? "-" : val);
								val = (val === null ? "-" : val);
								aRows.push("<th>" + v + "</th><td>" + val + "</td>");
							});
							
							// Open Magnific Popup (natively in iTop)
							$.magnificPopup.open({
							  items: {
								src: '<div class="geom-popup">' + 
									'<div class="mfp-close">x</div>' +
									'<h1><a href="UI.php?operation=details&class={$sClassName}&id=' + oFeature.get("id") + '">' + oFeature.get("friendlyname") + '</a></h1>' +
									'<table><tbody><tr>' + aRows.join('</tr><tr>') + '</tr></tbody></table>' + 
									'</div>', 
								type: 'inline'
							  }
							});
							
						}
					}
				});
				
			});
			
			geometryHandler["{$sId}"].oSelect = new ol.interaction.Select();
			geometryHandler["{$sId}"].oSelect.on("select", function(e) {

				console.log('Selection changed: ' + e.selected.length + ' selected, ' + e.deselected.length + ' deselected');
				
			});
			
			geometryHandler["{$sId}"].oMap.addInteraction(geometryHandler["{$sId}"].oSelect);
			
			geometryHandler["{$sId}"].oSelectAlt = new ol.interaction.Select({
				condition: function(mapBrowserEvent) {
					return ol.events.condition.click(mapBrowserEvent) && ol.events.condition.altKeyOnly(mapBrowserEvent);
				}
			});
			
			geometryHandler["{$sId}"].oSelectAlt.on("select", function(e) {
				// Unused
			});
			
			geometryHandler["{$sId}"].oMap.addInteraction(geometryHandler["{$sId}"].oSelectAlt);
			
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
	
	}
	
}
