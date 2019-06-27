
	function geometryHandler_Show_OpenStreetMap(){
		
		// OpenStreetMap expects EPSG:4326. This would only work for Point features. Adapt for other features.
		// This function is included only in a page where 'geom' has already been set.
		var oView = geomHandler.oMap.getView();
		var aCoords = geometryHandler.oFeature.clone().getGeometry().transform(oView.getProjection(), 'EPSG:4326');
		document.location = 'https://www.openstreetmap.org/#map=' + oView.getZoom() + '/' + aCoords[1] + '/' + aCoords[0];		
		
	}
	
	function geometryHandler_Copy_GeoJSON(){
		
		// Implement
		
	}