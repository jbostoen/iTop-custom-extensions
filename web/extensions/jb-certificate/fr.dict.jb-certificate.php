<?php
/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 17:01:06
 *
 * Localized data
 */

Dict::Add('FR FR', 'French', 'Français', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
	
	'Class:Certificate' => 'Certificat',
	'Class:Certificate/Attribute:name' => 'Nom',
	'Class:Certificate/Attribute:org_id' => 'Organisation',
	'Class:Certificate/Attribute:creator_id' => 'Demandé par',
	'Class:Certificate/Attribute:provider_org_id' => 'Fournisseur',
	'Class:Certificate/Attribute:date_creation' => 'Créé le',
	'Class:Certificate/Attribute:date_expiration' => 'Expire le',
	'Class:Certificate/Attribute:description' => 'Description',
	'Class:Certificate/Attribute:certificate' => 'Certificat',
	'Class:Certificate/Attribute:password' => 'Mot de passe',
	'Class:Certificate/Attribute:renewal' => 'Renouvellement',
	'Class:Certificate/Attribute:renewal/Value:automatically' => 'Automatique',
	'Class:Certificate/Attribute:renewal/Value:manually' => 'Manuel',
	'Class:Certificate/Attribute:functionalcis_list' => 'CIs',
	'Class:Certificate/Attribute:webservers_list' => 'Serveurs Web',
	
	'Class:Server/Attribute:certificates_list' => 'Certificats',
	'Class:VirtualMachine/Attribute:certificates_list' => 'Certificats',
	'Class:WebServer/Attribute:certificates_list' => 'Certificats',
	
	'Class:lnkCertificateToFunctionalCI' => 'Lien Certificat / CI',
	'Class:lnkCertificateToWebServer' => 'Lien Certificat / Server Web',
));

