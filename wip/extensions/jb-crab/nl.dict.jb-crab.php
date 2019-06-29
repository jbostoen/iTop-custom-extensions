<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(

	// Crab-Street
	'CrabStreet:baseinfo' => 'Algemene info',
	 
	'Class:CrabStreet' => 'Crab-straatnaam',
	'Class:CrabStreet+' => 'Straatnaam in Crab.',
	 
	'Class:CrabStreet/Attribute:crab_id' => 'Crab-ID',
	'Class:CrabStreet/Attribute:crab_id+' => 'Interne ID (Crab-Services)',
	'Class:CrabStreet/Attribute:name' => 'Straatnaam',
	'Class:CrabStreet/Attribute:name+' => 'Straatnaam',
	
	'Class:CrabStreet/Attribute:status' => 'Status',
	'Class:CrabStreet/Attribute:status+' => 'Status',
	
	'Class:CrabStreet/Attribute:status/Value:1' => 'voorgesteld',
	'Class:CrabStreet/Attribute:status/Value:2' => 'gereserveerd',
	'Class:CrabStreet/Attribute:status/Value:3' => 'in gebruik',
	'Class:CrabStreet/Attribute:status/Value:4' => 'niet meer in gebruik', 
	 
	'Class:CrabStreet/Attribute:list_addresses' => 'Adreslijst',
	'Class:CrabStreet/Attribute:list_addresses+' => 'Adreslijst',
	
	'Menu:SearchCrabStreet' => 'Zoek Crab-straten',
	'Menu:SearchCrabStreet+' => 'Zoek Crab-straten',
	'Menu:NewCrabStreet' => 'Nieuwe Crab-straat',
	'Menu:NewCrabStreet+' => 'Nieuwe Crab-straat',
	
	// Crab-Address
	'CrabAddress:baseinfo' => 'Algemene info',
	 
	'Class:CrabAddress' => 'Crab-adres',
	'Class:CrabAddress+' => 'Huisnummer in Crab.',
	 
	'Class:CrabAddress/Attribute:crab_id' => 'Crab-ID',
	'Class:CrabAddress/Attribute:crab_id+' => 'Interne ID (Crab-Services)',
	
	'Class:CrabAddress/Attribute:street_id' => 'Street ID',
	'Class:CrabAddress/Attribute:street_id+' => 'Internal ID within iTop',
	
	'Class:CrabAddress/Attribute:house_number' => 'Huisnummer',
	'Class:CrabAddress/Attribute:house_number+' => 'Huisnummer (gebruik _2 voor bisnummers = apart gebouw)',
	
	'Class:CrabAddress/Attribute:apartment_number' => 'Appartementnummer',
	'Class:CrabAddress/Attribute:apartment_number+' => 'Appartementnummer',
	
	'Class:CrabAddress/Attribute:sub_number' => 'Subnummer',
	'Class:CrabAddress/Attribute:sub_number+' => 'Subnummer (busnummers)',
	
	'Class:CrabAddress/Attribute:status' => 'Status',
	'Class:CrabAddress/Attribute:status+' => 'Status',
	
	'Class:CrabAddress/Attribute:status/Value:1' => 'voorgesteld',
	'Class:CrabAddress/Attribute:status/Value:2' => 'gereserveerd',
	'Class:CrabAddress/Attribute:status/Value:3' => 'in gebruik',
	'Class:CrabAddress/Attribute:status/Value:4' => 'niet meer in gebruik', 
	'Class:CrabAddress/Attribute:status/Value:5' => 'onofficieel', 
	'Class:CrabAddress/Attribute:status/Value:99' => 'niet meer in dataset Vlaanderen', 
	 
	'Class:Location/Attribute:geom' => 'Geometrie', 
	
	'Menu:SearchCrabAddress' => 'Zoek Crab-adressen',
	'Menu:SearchCrabAddress+' => 'Zoek Crab-adressen',
	'Menu:NewCrabAddress' => 'Nieuw Crab-adres',
	'Menu:NewCrabAddress+' => 'Nieuw Crab-adres',
	
	// Geometry
	'UI:Geom:Point' => 'Point', 		
	'Location:Geometry' => 'Geometry',

));
