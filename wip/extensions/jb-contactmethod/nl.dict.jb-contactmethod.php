<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
		
	'Class:Person/Attribute:contact_methods' => 'Contact methods',
	
	'Class:ContactMethod/Attribute:person_id' => 'Person',
	'Class:ContactMethod/Attribute:contact_method' => 'Contact method',
	'Class:ContactMethod/Attribute:contact_method/Value:phone' => 'Phone',
	'Class:ContactMethod/Attribute:contact_method/Value:mobile_phone' => 'Mobile phone',
	'Class:ContactMethod/Attribute:contact_method/Value:email' => 'Email',
	'Class:ContactMethod/Attribute:contact_detail' => 'Contact detail',
	
	'Errors/ContactMethod/InvalidPhoneNumber' => 'Invalid phone number. National numbers should consist of 9 digits. If country prefix is used, it should be 10 digits.',
	'Errors/ContactMethod/InvalidMobilePhoneNumber' => 'Invalid mobile phone number. National numbers should consist of 10 digits. If country prefix is used, it should be 11 digits.',
	'Errors/ContactMethod/InvalidEmail' => 'Invalid email address.',
	
));



?>