<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(
	// Dictionary entries go here
	  
	'Class:Location/Attribute:geom' => 'Geometrie', 
	'Location:Geometry' => 'Geometrie',
	
	// Actions
	'UI:Geom:Clear' => 'Geometrie wissen', 
	
	// Geometry types
	'UI:Geom:Point' => 'Punt',
	'UI:Geom:LineString' => 'Lijn',
	'UI:Geom:Polygon' => 'Vlak',
	
	'UI:Geom:Menu:ShowOpenStreetMap' => 'Toon op OpenStreetMap',
	'UI:Geom:Menu:CopyAsGeoJSON' => 'Kopieer object als GeoJSON',
	
	// Dashlet
	'UI:DashletMapOverview:Label' => 'Overzichtkaart',
	'UI:DashletMapOverview:Description' => 'Een kaart die het overzicht toont van een reeks objecten',
	'UI:DashletMapOverview:Prop-Title' => 'Titel',
	'UI:DashletMapOverview:Prop-Query' => 'Query',
	'UI:DashletMapOverview:Prop-Height' => 'Hoogte (px)',
	'UI:DashletMapOverview:Prop-LabelFeature' => 'Label object',
));
