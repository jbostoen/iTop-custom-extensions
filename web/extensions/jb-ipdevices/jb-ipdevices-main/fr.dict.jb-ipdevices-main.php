<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('FR FR', 'French', 'Français', array(

	//
	// Class: IP Device (abstract)
	//
	'Class:IPDevice' => 'CI Matériel IP',
	'Class:IPDevice+' => '', 
	'Class:IPDevice/Attribute:management_ip' => 'IP de gestion',
	'Class:IPDevice/Attribute:management_ip+' => '', 
	'Class:IPDevice/Attribute:connectablecis_list' => 'Matériels',
	'Class:IPDevice/Attribute:connectablecis_list+' => 'Tous les matériels connectés à cet équipement réseau',
	
	//
	// Class: IP Camera 
	//
	'Class:IPCamera' => 'Caméra IP',
	'Class:IPCamera+' => '',   
	 
	//
	// Class: IP Device Generic
	'Class:IPDeviceGeneric' => 'Matériel IP',
	'Class:IPDeviceGeneric+' => '',   
	
	// Class: IP Phone
	//
	'Class:IPPhone' => 'Téléphone IP',
	'Class:IPPhone+' => '',
	'Class:IPPhone/Attribute:number_internal' => 'Numéro interne',
	'Class:IPPhone/Attribute:number_internal+' => '', 
	'Class:IPPhone/Attribute:number_external' => 'Numéro externe',
	
	//
	// Class: Model
	//
	'Class:Model/Attribute:type/Value:IPDeviceGeneric' => 'Matériel IP',
	'Class:Model/Attribute:type/Value:IPDeviceGeneric+' => '',
	'Class:Model/Attribute:type/Value:IPCamera' => 'Caméra IP',
	'Class:Model/Attribute:type/Value:IPCamera+' => '',
	
	//
	// Class: lnkConnectableCIToConnectableCI 
	//
	'Class:lnkConnectableCIToConnectableCI' => 'Lien CI connecté / CI connecté',
	'Class:lnkConnectableCIToConnectableCI+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_id' => 'Equipement réseaux',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_id+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_name' => 'Nom de l\'équipement réseaux',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_name+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_id' => 'Matériel connecté',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_name' => 'Nom du matériel connecté',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:network_port' => 'Port réseau',
	'Class:lnkConnectableCIToConnectableCI/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:device_port' => 'Port du matériel',
	'Class:lnkConnectableCIToConnectableCI/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type' => 'Type de connexion',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:downlink' => 'Lien descendant',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:downlink+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:uplink' => 'Lien montant',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:uplink+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:patch_label' => 'Libellé du raccord',
	'Class:lnkConnectableCIToConnectableCI/Attribute:patch_label+' => 'Libellé du raccord (port/cable)',
	'Class:lnkConnectableCIToConnectableCI/Attribute:cable_type' => 'Type de cable',
	'Class:lnkConnectableCIToConnectableCI/Attribute:cable_type/Value:rj45' => 'RJ45',
	'Class:lnkConnectableCIToConnectableCI/Attribute:cable_type/Value:optical_fiber_single_mode' => 'Fibre optique (mode simple)',
	'Class:lnkConnectableCIToConnectableCI/Attribute:cable_type/Value:optical_fiber_multi_mode' => 'Fibre optique (mode multiple)',
));
