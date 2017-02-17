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
	
	 

));


?>
