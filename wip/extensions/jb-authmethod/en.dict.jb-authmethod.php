<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Localized data
 */
 
Dict::Add('EN US', 'English', 'English', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
		
	'Class:Person/Attribute:contact_methods' => 'Authentication methods',
	
	'Class:AuthenticationMethod/Attribute:user_id' => 'iTop User',
	'Class:AuthenticationMethod/Attribute:authentication_method' => 'Authentication method',
	'Class:AuthenticationMethod/Attribute:authentication_method/Value:email' => 'Email',
	'Class:AuthenticationMethod/Attribute:authentication_method/Value:facebook_id' => 'Facebook ID',
	'Class:AuthenticationMethod/Attribute:authentication_method/Value:twitter_id' => 'Twitter ID',
	'Class:AuthenticationMethod/Attribute:authentication_method/Value:token' => 'Token',
	'Class:AuthenticationMethod/Attribute:authentication_detail' => 'Authentication detail',
	'Class:AuthenticationMethod/Attribute:first_used' => 'First used',
	'Class:AuthenticationMethod/Attribute:last_used' => 'Last used',
	
	'Errors/AuthenticationMethod/InvalidEmail' => 'Invalid email address.',
	
));
