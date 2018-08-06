
	function geom_showOSM(sourceCRS){
		
		// Needs EPSG:4326, would only work for Point features. Adapt for other features.
		var coords = geoLoc.oFeature.clone().getGeometry().transform(sourceCRS, 'EPSG:4326');		
		document.location = 'https://www.openstreetmap.org/#map=17/' + coords[1] + '/' + coords[0];		
		
	}
	
	
	