/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Extends iTop with a cPopupMenuExtension_ImageV
 */
	function open_ImageV() {
		
		if( geometryHandler_ImageV.oFeature !== null ) {
			
			var oFeatureGeometry = geometryHandler_ImageV.oFeature.getGeometry();
			
			switch(oFeatureGeometry.getType()) {
				
				case 'Polygon':
				case 'LineString':
			
					var aExtent = oFeatureGeometry.getExtent();
					var aCoordinates = [ 
						( aExtent[0] + aExtent[2] ) / 2,
						( aExtent[1] + aExtent[3] ) / 2,
					];
					
					break;
					
				case 'Point':
					var aCoordinates = oFeatureGeometry.getCoordinates();
					break;
					
				default:
					console.log('Unexpected geometry type: ' + oFeatureGeometry.getType());
					
			}
					
			// Convert to EPSG:31370
			// Example link: https://viewer.image-v.be?x=68823.043&y=179512.324
			
			var sUrl = 'https://viewer.image-v.be?x=' + aCoordinates[0] + '&y=' + aCoordinates[1];
			window.open(sUrl, '_blank');
			
		}
		
	}