<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('EN US', 'English', 'English', array(
	// Dictionary entries go here
	  
	'Class:Location/Attribute:geom' => 'Geometry', 
	'Location:Geometry' => 'Geometry',
	
	// Actions
	'UI:Geom:Clear' => 'Clear geometry', 
	
	// Geometry types
	'UI:Geom:Point' => 'Point',
	'UI:Geom:LineString' => 'LineString',
	'UI:Geom:Polygon' => 'Polygon',
	
	'UI:Geom:Menu:ShowOpenStreetMap' => 'Show on OpenStreetMap',
	'UI:Geom:Menu:CopyAsGeoJSON' => 'Copy object as GeoJSON',
	
	// Dashlet
	'UI:DashletMapOverview:Label' => 'Map Overview',
	'UI:DashletMapOverview:Description' => 'A map showing items of a specified class',
	'UI:DashletMapOverview:Prop-Title' => 'Title',
	'UI:DashletMapOverview:Prop-Query' => 'Query',
	'UI:DashletMapOverview:Prop-Height' => 'Height (px)',
	'UI:DashletMapOverview:Prop-LabelFeature' => 'Label feature',
	
));
