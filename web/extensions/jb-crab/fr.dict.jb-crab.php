<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('FR FR', 'French', 'Français', array(

	// Crab City
	'Class:CrabCity' => 'Municipalité de Crab',
	'Class:CrabCity+' => 'Municipalité de Crab',
	
	'Class:CrabCity/Attribute:name' => 'Nom',
	'Class:CrabCity/Attribute:name+' => 'Nom officiel',
	'CrabCity:baseinfo' => 'Informations Générales',
	
	'Class:CrabCity/Attribute:streets_list' => 'Rues',
	'Class:CrabCity/Attribute:streets_list+' => 'Listes des rues',
	
	// Crab Street
	'CrabStreet:baseinfo' => 'Informations Générales',
	 
	'Class:CrabStreet' => 'Rue de Crab',
	'Class:CrabStreet+' => '',
	 
	'Class:CrabStreet/Attribute:crab_id' => 'Identifiant Crab',
	'Class:CrabStreet/Attribute:crab_id+' => 'Identifiant interne aux Services de Crab',
	'Class:CrabStreet/Attribute:name' => 'Nom de rue',
	'Class:CrabStreet/Attribute:name+' => '',
	
	'Class:CrabStreet/Attribute:city_id' => 'Ville',
	'Class:CrabStreet/Attribute:city_id+' => '',
	
	'Class:CrabStreet/Attribute:status' => 'Statut',
	'Class:CrabStreet/Attribute:status+' => '',
	
	'Class:CrabStreet/Attribute:status/Value:1' => 'Proposé',
	'Class:CrabStreet/Attribute:status/Value:2' => 'Réservé',
	'Class:CrabStreet/Attribute:status/Value:3' => 'Utilisé',
	'Class:CrabStreet/Attribute:status/Value:4' => 'Plus utilisé',
	 
	'Class:CrabStreet/Attribute:addresses_list' => 'Adresses',
	'Class:CrabStreet/Attribute:addresses_list+' => '',
	
	'Menu:SearchCrabStreet' => 'Recherche des rues de Crab',
	'Menu:SearchCrabStreet+' => 'Recherche des rues de Crab',
	'Menu:NewCrabStreet' => 'Nouvelle rue de Crab',
	'Menu:NewCrabStreet+' => 'Créer une nouvelle rue de Crab',
	
	// Crab Address
	'CrabAddress:baseinfo' => 'Informations Générales',
	 
	'Class:CrabAddress' => 'Adresse de Crab',
	'Class:CrabAddress+' => '',
	 
	'Class:CrabAddress/Attribute:crab_id' => 'Identifiant Crab',
	'Class:CrabAddress/Attribute:crab_id+' => 'Identifiant interne aux Services de Crab',
	
	'Class:CrabAddress/Attribute:street_id' => 'Nom de rue',
	'Class:CrabAddress/Attribute:street_id+' => 'Identifiant interne à iTop',
	
	'Class:CrabAddress/Attribute:house_number' => 'Numéro de maison',
	'Class:CrabAddress/Attribute:house_number+' => '',
	
	'Class:CrabAddress/Attribute:apartment_number' => 'Numéro d\'appartement',
	'Class:CrabAddress/Attribute:apartment_number+' => '',
	
	'Class:CrabAddress/Attribute:sub_number' => 'Sous numéro',
	'Class:CrabAddress/Attribute:sub_number+' => 'Sous numéro (utilisé dans le cas de multiple boîtes aux lettres)',
	 
	'Class:CrabAddress/Attribute:status' => 'Statut',
	'Class:CrabAddress/Attribute:status+' => '',
	
	'Class:CrabAddress/Attribute:geom' => 'Géométrie',
	'Class:CrabAddress/Attribute:geom+' => 'Coordonnées XY',
	
	'Class:CrabAddress/Attribute:status/Value:1' => 'Proposé',
	'Class:CrabAddress/Attribute:status/Value:2' => 'Réservé',
	'Class:CrabAddress/Attribute:status/Value:3' => 'Utilisé',
	'Class:CrabAddress/Attribute:status/Value:4' => 'Plus utilisé',
	'Class:CrabAddress/Attribute:status/Value:5' => 'Non officiel',
	'Class:CrabAddress/Attribute:status/Value:99' => 'Plus dans la source de données (Flandres)',
	 
	'Class:Location/Attribute:geom' => 'Géométrie',
	
	'Menu:SearchCrabAddress' => 'Recherche d\'adresses Crab',
	'Menu:SearchCrabAddress+' => '',
	'Menu:NewCrabAddress' => 'Nouvelle adresse Crab',
	'Menu:NewCrabAddress+' => 'Créer une nouvelle adresse Crab',
		  
	// Geometry
	'UI:Geom:Point' => 'Point', 		
	'Location:Geometry' => 'Géométrie',
));
