<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


//
// Class: IPPhone
//

Dict::Add('EN US', 'English', 'English', array(

	//
	// Class: IP Device (abstract)
	//
	'Class:IPDevice' => 'IP Device CI',
	'Class:IPDevice+' => '', 
	'Class:IPDevice/Attribute:management_ip' => 'Management IP',
	'Class:IPDevice/Attribute:management_ip+' => '', 
	'Class:IPDevice/Attribute:connectablecis_list' => 'Devices',
	'Class:IPDevice/Attribute:connectablecis_list+' => 'All the devices connected to this network device',
	
	//
	// Class: IP Camera 
	//
	'Class:IPCamera' => 'IP Camera',
	'Class:IPCamera+' => '',   
	 
	//
	// Class: IP Device Generic
	'Class:IPDeviceGeneric' => 'IP Device',
	'Class:IPDeviceGeneric+' => '',   
	
	// Class: IP Phone
	//
	'Class:IPPhone' => 'IP Phone',
	'Class:IPPhone+' => '',
	'Class:IPPhone/Attribute:number_internal' => 'Internal number',
	'Class:IPPhone/Attribute:number_internal+' => '', 
	'Class:IPPhone/Attribute:number_external' => 'External number',
	
	//
	// Class: Model
	//
	'Class:Model/Attribute:type/Value:IPDeviceGeneric' => 'IP Device',
	'Class:Model/Attribute:type/Value:IPDeviceGeneric+' => 'IP Device',
	'Class:Model/Attribute:type/Value:IPCamera' => 'IP Camera',
	'Class:Model/Attribute:type/Value:IPCamera+' => 'IP Camera',
	
	
	//
	// Class: lnkConnectableCIToConnectableCI 
	//
	'Class:lnkConnectableCIToConnectableCI' => 'Link ConnectableCI / ConnectableCI',
	'Class:lnkConnectableCIToConnectableCI+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_id' => 'Network device',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_id+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_name' => 'Network device name',
	'Class:lnkConnectableCIToConnectableCI/Attribute:ConnectableCI_name+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_id' => 'Connected device',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_name' => 'Connected device name',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:network_port' => 'Network port',
	'Class:lnkConnectableCIToConnectableCI/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:device_port' => 'Device port',
	'Class:lnkConnectableCIToConnectableCI/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type' => 'Connection type',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:downlink' => 'down link',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:downlink+' => 'down link',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:uplink' => 'up link',
	'Class:lnkConnectableCIToConnectableCI/Attribute:connection_type/Value:uplink+' => 'up link',
 

));


?>
