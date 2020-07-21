<?php
/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-07-21 19:29:11
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
	'Class:Certificate/Attribute:date_not_before' => 'Geldig vanaf',
	'Class:Certificate/Attribute:date_not_after' => 'Geldig tot',
	'Class:Certificate/Attribute:description' => 'Beschrijving',
	'Class:Certificate/Attribute:serial_number' => 'Serienummer',
	'Class:Certificate/Attribute:certificate' => 'Certificaat',
	'Class:Certificate/Attribute:password' => 'Wachtwoord',
	'Class:Certificate/Attribute:renewal' => 'Vernieuwing',
	'Class:Certificate/Attribute:renewal/Value:automatically' => 'Automatisch',
	'Class:Certificate/Attribute:renewal/Value:manually' => 'Manueel',
	'Class:Certificate/Attribute:parent_id' => 'Hoger certificaat',
	'Class:Certificate/Attribute:functionalcis_list' => 'Functionele CI\'s',
	'Class:Certificate/Attribute:hierarchy' => 'Hiërarchie',
	'Class:Certificate/Attribute:hierarchy/Value:root' => 'Root (basis)',
	'Class:Certificate/Attribute:hierarchy/Value:intermediate' => 'Intermediate',
	'Class:Certificate/Attribute:hierarchy/Value:end_entity' => 'End-entity / leaf',
	'Class:Certificate/Attribute:hierarchy/Value:self_signed' => 'Self-signed',
	
	'Class:Server/Attribute:certificates_list' => 'Certificaten',
	'Class:VirtualMachine/Attribute:certificates_list' => 'Certificaten',
	'Class:WebServer/Attribute:certificates_list' => 'Certificaten',
	
	'Class:lnkCertificateToFunctionalCI' => 'Link Certificaat / Functioneel CI',
	'Class:lnkCertificateToFunctionalCI/Attribute:comment' => 'Commentaar',
	'Class:lnkCertificateToFunctionalCI/Attribute:functionalci_id' => 'Functioneel CI',
	'Class:lnkCertificateToFunctionalCI/Attribute:certificate_id' => 'Certificaat',
	
));


