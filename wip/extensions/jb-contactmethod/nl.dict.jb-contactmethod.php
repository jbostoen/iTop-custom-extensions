<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
		
	'Class:Person/Attribute:contact_methods' => 'Contactmethodes',
	
	'Class:ContactMethod/Attribute:person_id' => 'Persoon',
	'Class:ContactMethod/Attribute:contact_method' => 'Contactmethode',
	'Class:ContactMethod/Attribute:contact_method/Value:phone' => 'Telefoon',
	'Class:ContactMethod/Attribute:contact_method/Value:mobile_phone' => 'Mobiele telefoon',
	'Class:ContactMethod/Attribute:contact_method/Value:email' => 'E-mailadres',
	'Class:ContactMethod/Attribute:contact_detail' => 'Contactgegeven',
	
	'Errors/ContactMethod/InvalidPhoneNumber' => 'Ongeldig telefoonnummer. Nationale nummers bestaan uit 9 tekens (of 10 als de landprefix opgegeven werd).',
	'Errors/ContactMethod/InvalidMobilePhoneNumber' => 'Ongeldig telefoonnummer. Nationale nummers bestaan uit 10 tekens (of 11 als de landprefix opgegeven werd).',
	'Errors/ContactMethod/InvalidEmail' => 'Ongeldig e-mailadres.',
	
));
