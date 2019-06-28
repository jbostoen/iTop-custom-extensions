<?php
// Copyright (C) 2019 Jeffrey Bostoen
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Lesser General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

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


