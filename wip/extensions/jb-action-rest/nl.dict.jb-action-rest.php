<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @author		jbostoen
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
	
	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
	'Class:ActionRest' => 'REST-actie',
	'Class:ActionRest+' => 'De REST-actie zal uitgevoerd worden na een Trigger.',
	'Class:ActionRest/Attribute:name' => 'Naam',
	'Class:ActionRest/Attribute:description' => 'Beschrijving',
	'Class:ActionRest/Attribute:status' => 'Status',
	'Class:ActionRest/Attribute:status/Value:enabled' => 'In productie',
	'Class:ActionRest/Attribute:status/Value:disabled' => 'Inactief',
	'Class:ActionRest/Attribute:status/Value:test' => 'Wordt getest',
	'Class:ActionRest/Attribute:trigger_list' => 'Triggers',
	'Class:ActionRest/Attribute:test_url' => 'URL Test',
	'Class:ActionRest/Attribute:production_url' => 'URL Productie',
	'Class:ActionRest/Attribute:log_result' => 'Resultaat',
	'Class:ActionRest/Attribute:log_result/Value:http_code' => 'HTTP-code',
	'Class:ActionRest/Attribute:log_result/Value:http_body' => 'HTTP-antwoord',
	
	'Class:EventNotificationRest' => 'REST Event',
	'Class:EventNotificationRest+' => 'Trace van een REST Event',
	'Class:EventNotificationRest/date' => 'Datum',
	'Class:Event/EventNotificationRest:date+' => 'Datum en tijdstip waarop de veranderingen zijn vastgelegd',
	'Class:EventNotificationRest/message' => 'Bericht',
	'Class:EventNotificationRest/userinfo' => 'Gebruikersinfo',
	'Class:EventNotificationRest/action_id' => 'ID Actie',
	'Class:EventNotificationRest/trigger_id' => 'ID Trigger',
	'Class:EventNotificationRest/object_id' => 'ID Object',
	'Class:EventNotificationRest/url' => 'URL',
	
));
