<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-01-23 11:41:53
 *
 * Localized data
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
	
	'Class:Certificate' => 'Certificaat',
	'Class:Certificate/Attribute:name' => 'Naam',
	'Class:Certificate/Attribute:org_id' => 'Organisatie',
	'Class:Certificate/Attribute:creator_id' => 'Aangemaakt door',
	'Class:Certificate/Attribute:provider_org_id' => 'Leverancier',
	'Class:Certificate/Attribute:date_creation' => 'Gemaakt op',
	'Class:Certificate/Attribute:date_expiration' => 'Vervalt op',
	'Class:Certificate/Attribute:description' => 'Beschrijving',
	'Class:Certificate/Attribute:certificate' => 'Certificaat',
	'Class:Certificate/Attribute:password' => 'Wachtwoord',
	'Class:Certificate/Attribute:functionalcis_list' => 'Functionele CI\'s',
	'Class:Certificate/Attribute:webservers_list' => 'Webservers',
	
	'Class:Server/Attribute:certificates_list' => 'Certificaten',
	'Class:VirtualMachine/Attribute:certificates_list' => 'Certificaten',
	'Class:WebServer/Attribute:certificates_list' => 'Certificaten',
	
	'Class:lnkCertificateToFunctionalCI' => 'Link Certificaat / Functioneel CI',
	'Class:lnkCertificateToWebServer' => 'Link Certificaat / Webserver',
	
));

