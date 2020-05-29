<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(

	// Crab City	 
	'Class:CrabCity' => 'Crab Gemeente',
	'Class:CrabCity+' => 'Crab Gemeente',
	'Class:CrabCity/Attribute:name' => 'Naam',
	'Class:CrabCity/Attribute:name+' => 'Officiële naam',
	'CrabCity:baseinfo' => 'Algemene info',
	
	'Class:CrabCity/Attribute:streets_list' => 'Stratenlijst',
	'Class:CrabCity/Attribute:streets_list+' => 'Stratenlijst',
	
	// Crab-Street
	'CrabStreet:baseinfo' => 'Algemene info',
	 
	'Class:CrabStreet' => 'Crab Straatnaam',
	'Class:CrabStreet+' => 'Crab Straatnaam',
	 
	'Class:CrabStreet/Attribute:crab_id' => 'Crab-ID',
	'Class:CrabStreet/Attribute:crab_id+' => 'Interne ID (Crab-Services)',
	'Class:CrabStreet/Attribute:name' => 'Straatnaam',
	'Class:CrabStreet/Attribute:name+' => 'Straatnaam',
	
	'Class:CrabStreet/Attribute:city_id' => 'Gemeente',
	'Class:CrabStreet/Attribute:city_id+' => 'Gemeente',
	
	'Class:CrabStreet/Attribute:status' => 'Status',
	'Class:CrabStreet/Attribute:status+' => 'Status',
	
	'Class:CrabAddress/Attribute:geom' => 'Geometrie',
	'Class:CrabAddress/Attribute:geom+' => 'XY-coördinaten',
	
	'Class:CrabStreet/Attribute:status/Value:1' => 'Voorgesteld',
	'Class:CrabStreet/Attribute:status/Value:2' => 'Gereserveerd',
	'Class:CrabStreet/Attribute:status/Value:3' => 'In gebruik',
	'Class:CrabStreet/Attribute:status/Value:4' => 'Niet meer in gebruik', 
	 
	'Class:CrabStreet/Attribute:addresses_list' => 'Adreslijst',
	'Class:CrabStreet/Attribute:addresses_list+' => 'Adreslijst',
	
	'Menu:SearchCrabStreet' => 'Zoek Crab-straten',
	'Menu:SearchCrabStreet+' => 'Zoek Crab-straten',
	'Menu:NewCrabStreet' => 'Nieuwe Crab-straat',
	'Menu:NewCrabStreet+' => 'Nieuwe Crab-straat',
	
	// Crab-Address
	'CrabAddress:baseinfo' => 'Algemene info',
	 
	'Class:CrabAddress' => 'Crab Adres',
	'Class:CrabAddress+' => 'Crab Adres',
	 
	'Class:CrabAddress/Attribute:crab_id' => 'Crab-ID',
	'Class:CrabAddress/Attribute:crab_id+' => 'Interne ID (Crab-Services)',
	
	'Class:CrabAddress/Attribute:street_id' => 'Straatnaam',
	'Class:CrabAddress/Attribute:street_id+' => 'Straatnaam ID binnen iTop',
	
	'Class:CrabAddress/Attribute:house_number' => 'Huisnummer',
	'Class:CrabAddress/Attribute:house_number+' => 'Huisnummer (gebruik _2 voor bisnummers = apart gebouw)',
	
	'Class:CrabAddress/Attribute:apartment_number' => 'Appartementnummer',
	'Class:CrabAddress/Attribute:apartment_number+' => 'Appartementnummer',
	
	'Class:CrabAddress/Attribute:sub_number' => 'Subnummer',
	'Class:CrabAddress/Attribute:sub_number+' => 'Subnummer (busnummers)',
	
	'Class:CrabAddress/Attribute:status' => 'Status',
	'Class:CrabAddress/Attribute:status+' => 'Status',
	
	'Class:CrabAddress/Attribute:geom' => 'Geometrie',
	'Class:CrabAddress/Attribute:geom+' => 'XY-coördinaten',
	
	'Class:CrabAddress/Attribute:status/Value:1' => 'Voorgesteld',
	'Class:CrabAddress/Attribute:status/Value:2' => 'Gereserveerd',
	'Class:CrabAddress/Attribute:status/Value:3' => 'In gebruik',
	'Class:CrabAddress/Attribute:status/Value:4' => 'Niet meer in gebruik', 
	'Class:CrabAddress/Attribute:status/Value:5' => 'Onofficieel', 
	'Class:CrabAddress/Attribute:status/Value:99' => 'Niet meer in dataset Vlaanderen', 
	 
	'Class:Location/Attribute:geom' => 'Kaart', 
	
	'Menu:SearchCrabAddress' => 'Zoek Crab-adressen',
	'Menu:SearchCrabAddress+' => 'Zoek Crab-adressen',
	'Menu:NewCrabAddress' => 'Nieuw Crab-adres',
	'Menu:NewCrabAddress+' => 'Nieuw Crab-adres',
	
	// Geometry
	'UI:Geom:Point' => 'Point', 		
	'Location:Geometry' => 'Kaart',

));
