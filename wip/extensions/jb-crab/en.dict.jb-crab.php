<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('EN US', 'English', 'English', array(

	// Crab City
	'Class:CrabCity' => 'Name',
	'Class:CrabCity+' => 'Official name',
	'CrabCity:baseinfo' => 'General information',
	
	'Class:CrabCity/Attribute:streets_list' => 'Streets list',
	'Class:CrabCity/Attribute:streets_list+' => 'Streets list',
	
	// Crab Street
	'CrabStreet:baseinfo' => 'General information',
	 
	'Class:CrabStreet' => 'Crab street',
	'Class:CrabStreet+' => 'Crab street.',
	 
	'Class:CrabStreet/Attribute:crab_id' => 'Crab ID',
	'Class:CrabStreet/Attribute:crab_id+' => 'Internal ID within Crab Services',
	'Class:CrabStreet/Attribute:name' => 'Street name',
	'Class:CrabStreet/Attribute:name+' => 'Street name',
	
	'Class:CrabStreet/Attribute:status' => 'Status',
	'Class:CrabStreet/Attribute:status+' => 'Status',
	
	'Class:CrabStreet/Attribute:status/Value:1' => 'proposed',
	'Class:CrabStreet/Attribute:status/Value:2' => 'reserved',
	'Class:CrabStreet/Attribute:status/Value:3' => 'in use',
	'Class:CrabStreet/Attribute:status/Value:4' => 'no longer in use', 
	 
	'Class:CrabStreet/Attribute:addresses_list' => 'Address list',
	'Class:CrabStreet/Attribute:addresses_list+' => 'Address list',
	
	'Menu:SearchCrabStreet' => 'Search for Crab streets',
	'Menu:SearchCrabStreet+' => 'Search for Crab streets',
	'Menu:NewCrabStreet' => 'New Crab street',
	'Menu:NewCrabStreet+' => 'Create a new Crab Street',
	
	// Crab Address
	'CrabAddress:baseinfo' => 'General information',
	 
	'Class:CrabAddress' => 'Crab address',
	'Class:CrabAddress+' => 'Crab address.',
	 
	'Class:CrabAddress/Attribute:crab_id' => 'Crab ID',
	'Class:CrabAddress/Attribute:crab_id+' => 'Internal ID within Crab Services',
	
	'Class:CrabAddress/Attribute:street_id' => 'Street ID',
	'Class:CrabAddress/Attribute:street_id+' => 'Internal ID within iTop',
	
	'Class:CrabAddress/Attribute:house_number' => 'House number',
	'Class:CrabAddress/Attribute:house_number+' => 'House number',
	
	'Class:CrabAddress/Attribute:apartment_number' => 'Appartement number',
	'Class:CrabAddress/Attribute:apartment_number+' => 'Appartement number',
	
	'Class:CrabAddress/Attribute:sub_number' => 'Sub number',
	'Class:CrabAddress/Attribute:sub_number+' => 'Sub number (usually for multiple mailboxes)',
	 
	'Class:CrabAddress/Attribute:status' => 'Status',
	'Class:CrabAddress/Attribute:status+' => 'Status',
	
	'Class:CrabAddress/Attribute:status/Value:1' => 'proposed',
	'Class:CrabAddress/Attribute:status/Value:2' => 'reserved',
	'Class:CrabAddress/Attribute:status/Value:3' => 'in use',
	'Class:CrabAddress/Attribute:status/Value:4' => 'no longer in use', 
	'Class:CrabAddress/Attribute:status/Value:5' => 'unofficially',   
	'Class:CrabAddress/Attribute:status/Value:99' => 'no longer in data source (Flanders)',   
	 
	'Class:Location/Attribute:geom' => 'Geometry', 
	
	'Menu:SearchCrabAddress' => 'Search for Crab addresses',
	'Menu:SearchCrabAddress+' => 'Search for Crab addresses',
	'Menu:NewCrabAddress' => 'New Crab address',
	'Menu:NewCrabAddress+' => 'Create a new Crab Address',
		  
	// Geometry
	'UI:Geom:Point' => 'Point', 		
	'Location:Geometry' => 'Geometry',
	
));
