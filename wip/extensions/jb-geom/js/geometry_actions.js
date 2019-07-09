/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Defines front-end functions
 */
 
	if( typeof geometryHandler === "undefined" ) {
		geometryHandler = {};
	}
	if( typeof geometryHandler["common"] === "undefined" ) {
		geometryHandler["common"] = {};
	}
	
	geometryHandler["common"].Show_OpenStreetMap = function (){
		
		// OpenStreetMap expects EPSG:4326. This would only work for Point features. Adapt for other features.
		// This function is included only in a page where 'geom' has already been set.
		var oView = geometryHandler["common"].oMap.getView();
		var aCoords = ol.proj.transform(geometryHandler["common"].oCenter, oView.getProjection(), 'EPSG:4326');
		var sUrl = 'https://www.openstreetmap.org/#map=' + oView.getZoom() + '/' + aCoords[1] + '/' + aCoords[0];
		console.log(sUrl);
		document.location = sUrl;
	}
	
	geometryHandler["common"].Copy_As_GeoJSON = function (){
		
		// Should be in a selectable element.
		$('#geometryHandler_GeoJSON').html(geometryHandler["common"].oFormat.GeoJSON.writeFeature(geometryHandler["common"].oFeature)).show().select();
		document.execCommand('copy');
		$('#geometryHandler_GeoJSON').hide().html('');
		
	}