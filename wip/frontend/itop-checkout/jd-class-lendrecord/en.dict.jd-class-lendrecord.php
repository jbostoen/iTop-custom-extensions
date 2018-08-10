<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', array(
	// Dictionary entries go here
	'LendRecord/otherinfo' => 'General information',
	'LendRecord/baseinfo' => 'Other information',
	
	
	'Class:LendRecord' => 'Lend record',
	'Class:LendRecord+' => 'Logs a new item that has been lent.',
	
	
	'Class:LendRecord/Attribute:physicaldevice_id' => 'Name of Physical Device',
	'Class:LendRecord/Attribute:physicaldevice_id+' => 'What is the name of the tool/vehicle you want to lend out',
	
	'Class:LendRecord/Attribute:org_id' => 'Organisation',
	'Class:LendRecord/Attribute:org_id+' => 'Which organisation',
	'Class:LendRecord/Attribute:contact_id' => 'Who/Which team',
	'Class:LendRecord/Attribute:contact_id+' => 'Who is using this tool/vehicle',
	
	'Class:LendRecord/Attribute:date_out' => 'Date out',
	'Class:LendRecord/Attribute:date_out+' => 'When does this item leave the stock',
	'Class:LendRecord/Attribute:date_in' => 'Date in',
	'Class:LendRecord/Attribute:date_in+' => 'When does this item return to the stock',
		
	'Class:LendRecord/Attribute:reason' => 'Reason',
	'Class:LendRecord/Attribute:reason+' => 'Why is this item being lent',
	'Class:LendRecord/Attribute:remarks' => 'Remarks',
	'Class:LendRecord/Attribute:remarks+' => 'Did something get damaged, has something happened,...',
	
	'Menu:SearchLendRecord' => 'Search for lend records',
	'Menu:SearchLendRecord+' => 'Search for items being lent out',
	'Menu:NewLendRecord' => 'New lend record',
	'Menu:NewLendRecord+' => 'Log an item being lent out',
));

 



?>
