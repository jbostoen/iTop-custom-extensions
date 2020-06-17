<?php
/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 16:58:14
 *
 * Localized data
 */

Dict::Add('EN US', 'English', 'English', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
	
	'Class:Certificate' => 'Certificate',
	'Class:Certificate/Attribute:name' => 'Name',
	'Class:Certificate/Attribute:org_id' => 'Organization',
	'Class:Certificate/Attribute:creator_id' => 'Requested by',
	'Class:Certificate/Attribute:issuer_org_id' => 'Issuer',
	'Class:Certificate/Attribute:date_not_before' => 'Not valid before',
	'Class:Certificate/Attribute:date_not_after' => 'Not valid after',
	'Class:Certificate/Attribute:description' => 'Description',
	'Class:Certificate/Attribute:serial_number' => 'Serial number',
	'Class:Certificate/Attribute:certificate' => 'Certificate',
	'Class:Certificate/Attribute:password' => 'Password',
	'Class:Certificate/Attribute:renewal' => 'Renewal',
	'Class:Certificate/Attribute:renewal/Value:automatically' => 'Automatically',
	'Class:Certificate/Attribute:renewal/Value:manually' => 'Manually',
	'Class:Certificate/Attribute:parent_id' => 'Parent certificate',
	'Class:Certificate/Attribute:functionalcis_list' => 'CIs',
	'Class:Certificate/Attribute:hierarchy' => 'Hierarchy',
	'Class:Certificate/Attribute:hierarchy/Value:root' => 'Root',
	'Class:Certificate/Attribute:hierarchy/Value:intermediate' => 'Intermediate',
	'Class:Certificate/Attribute:hierarchy/Value:end_entity' => 'End-entity / leaf',
	'Class:Certificate/Attribute:hierarchy/Value:self_signed' => 'Self-signed',
	
	'Class:Server/Attribute:certificates_list' => 'Certificates',
	'Class:VirtualMachine/Attribute:certificates_list' => 'Certificates',
	'Class:WebServer/Attribute:certificates_list' => 'Certificates',
	
	'Class:lnkCertificateToFunctionalCI' => 'Link Certificate / FunctionalCI',
	'Class:lnkCertificateToFunctionalCI/Attribute:comment' => 'Comment',
	'Class:lnkCertificateToFunctionalCI/Attribute:functionalci_id' => 'Functional CI',
	'Class:lnkCertificateToFunctionalCI/Attribute:certificate_id' => 'Certificate',
	
));


