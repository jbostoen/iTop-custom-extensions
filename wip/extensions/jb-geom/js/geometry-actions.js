
	function geometryHandler_Show_OpenStreetMap(){
		
		// OpenStreetMap expects EPSG:4326. This would only work for Point features. Adapt for other features.
		// This function is included only in a page where 'geom' has already been set.
		var oView = geometryHandler.oMap.getView();
		var aCoords = geometryHandler.oFeature.clone().getGeometry().transform(oView.getProjection(), 'EPSG:4326');
		document.location = 'https://www.openstreetmap.org/#map=' + oView.getZoom() + '/' + aCoords[1] + '/' + aCoords[0];		
		
	}
	
	function geometryHandler_Copy_As_GeoJSON(){
		
		// Should be in a selectable element.
		$('#geometryHandler_GeoJSON').html(geometryHandler.oFormat.GeoJSON.writeFeature(geometryHandler.oFeature)).show().select();
		document.execCommand('copy');
		$('#geometryHandler_GeoJSON').hide().html('');
		
	}