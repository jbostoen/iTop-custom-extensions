<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @author 		jbostoen
 */

Dict::Add('EN US', 'English', 'English', array(

	//	'Class:SomeClass' => 'Class name',
	//	'Class:SomeClass+' => 'More info on class name',
	//	'Class:SomeClass/Attribute:some_attribute' => 'your translation for the label',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value' => 'your translation for a value',
    //	'Class:SomeClass/Attribute:some_attribute/Value:some_value+' => 'your translation for more info on the value',

	'Class:SSID' => 'SSID',
	'Class:SSID+' => 'Wireless network',
	'Class:SSID/Attribute:org_id' => 'Organization',
	'Class:SSID/Attribute:name' => 'Name',
	'Class:SSID/Attribute:password' => 'Password',
	'Class:SSID/Attribute:vlan_tag_id' => 'VLAN tag',
	'Class:SSID/Attribute:security_protocol' => 'Protocol',
	'Class:SSID/Attribute:security_protocol+' => 'Which security protocol is applied? From best to worst: WPA3 / WPA2 / WPA / WEP / Open',
	'Class:SSID/Attribute:security_protocol/Value:wep' => 'WEP',
	'Class:SSID/Attribute:security_protocol/Value:wpa' => 'WPA',
	'Class:SSID/Attribute:security_protocol/Value:wpa2' => 'WPA2',
	'Class:SSID/Attribute:security_protocol/Value:wpa3' => 'WPA3',
	'Class:SSID/Attribute:security_protocol/Value:open' => 'Open',
	'Class:SSID/Attribute:security_target' => 'Target',
	'Class:SSID/Attribute:security_target/Value:personal' => 'Personal (Pre Shared Key)',
	'Class:SSID/Attribute:security_target/Value:enterprise' => 'Enterprise (802.1 verification)',
	'Class:SSID/Attribute:security_target/Value:not_applicable' => 'Not applicable',
	'Class:SSID/Attribute:security_tkip' => 'TKIP',
	'Class:SSID/Attribute:security_tkip/Value:yes' => 'Yes',
	'Class:SSID/Attribute:security_tkip/Value:no' => 'No',
	'Class:SSID/Attribute:security_aes' => 'AES',
	'Class:SSID/Attribute:security_aes/Value:yes' => 'Yes',
	'Class:SSID/Attribute:security_aes/Value:no' => 'No',
	'Class:SSID/Attribute:band_2_4_GHz' => '2.4 GHz Band',
	'Class:SSID/Attribute:band_2_4_GHz/Value:yes' => 'Yes',
	'Class:SSID/Attribute:band_2_4_GHz/Value:no' => 'No',
	'Class:SSID/Attribute:band_5_GHz' => '5 GHz Band',
	'Class:SSID/Attribute:band_5_GHz/Value:yes' => 'Yes',
	'Class:SSID/Attribute:band_5_GHz/Value:no' => 'No',
	'Class:SSID/Attribute:channel_choice' => 'Channel choice',
	'Class:SSID/Attribute:channel_choice/Value:automatic' => 'Automatic',
	'Class:SSID/Attribute:channel_choice/Value:manual' => 'Manual',
	'Class:SSID/Attribute:channel_number' => 'Channel number',
	'Class:SSID/Attribute:networkdevices_list' => 'Wireless Access Points',
	
	'SSID:General' => 'General',
	'SSID:Security' => 'Security',
	'SSID:Radio' => 'Radio',
	
));
