<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * PHP Data Model definition file
 */

Dict::Add('EN US', 'English', 'English', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',
	'Class:ActionRest' => 'REST Action',
	'Class:ActionRest+' => 'A REST action which can be executed after a trigger',
	'Class:ActionRest/Attribute:name' => 'Name',
	'Class:ActionRest/Attribute:description' => 'Description',
	'Class:ActionRest/Attribute:status' => 'Status',
	'Class:ActionRest/Attribute:status/Value:enabled' => 'enabled',
	'Class:ActionRest/Attribute:status/Value:disabled' => 'disabled',
	'Class:ActionRest/Attribute:status/Value:test' => 'being tested',
	'Class:ActionRest/Attribute:trigger_list' => 'Triggers',
	'Class:ActionRest/Attribute:test_url' => 'Test URL',
	'Class:ActionRest/Attribute:production_url' => 'Production URL',
	'Class:ActionRest/Attribute:log_result' => 'Result',
	'Class:ActionRest/Attribute:log_result/Value:http_code' => 'HTTP Code',
	'Class:ActionRest/Attribute:log_result/Value:http_body' => 'HTTP Body',
	
	'Class:EventNotificationRest' => 'REST Event',
	'Class:EventNotificationRest+' => 'Trace of a REST Event',
	'Class:EventNotificationRest/date' => 'Date',
	'Class:EventNotificationRest/message' => 'Message',
	'Class:EventNotificationRest/userinfo' => 'User info',
	'Class:EventNotificationRest/action_id' => 'Action ID',
	'Class:EventNotificationRest/trigger_id' => 'Trigger ID',
	'Class:EventNotificationRest/object_id' => 'Object ID',
	'Class:EventNotificationRest/url' => 'URL',
	
));
