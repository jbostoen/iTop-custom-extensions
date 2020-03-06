<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2019-2020 Jelle Defoort, Jeffrey Bostoen
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', array(
	
	// Dictionary entries go here
	'PrinterSupply:in' => 'In',
	'PrinterSupply:out' => 'Out',

	'Class:PrinterSupplyType' => 'Supply type',
	'Class:PrinterSupplyType+' => 'Supply type',
	'Class:PrinterSupplyType/Attribute:name' => 'Name',
	'Class:PrinterSupplyType/Attribute:name+' => 'Name of the supply. Hint: start with the original name and then the generic name, e.g.: HP A30 Black / IsoTech CLA776 or Brother DR-2100 Drum.',
	'Class:PrinterSupplyType/Attribute:models_list' => 'Models',
	'Class:PrinterSupplyType/Attribute:models_list+' => 'Which printer models use this',
	'Class:PrinterSupplyType/Attribute:printersupplies_list' => 'Printer supplies',
	'Class:PrinterSupplyType/Attribute:printersupplies_list+' => 'List of this type of printer supplies',

	'Class:PrinterSupply' => 'PrinterSupply',
	'Class:PrinterSupply+' => 'PrinterSupply',
	'Class:PrinterSupply/Attribute:printersupplytype_id' => 'Supply',
	'Class:PrinterSupply/Attribute:printersupplytype_id+' => 'The printer supply',
	'Class:PrinterSupply/Attribute:location_id' => 'Location',
	'Class:PrinterSupply/Attribute:location_id+' => 'Location of supply',	
	'Class:PrinterSupply/Attribute:printer_id' => 'Printer',
	'Class:PrinterSupply/Attribute:printer_id+' => 'To which printer did this go',
	'Class:PrinterSupply/Attribute:date_delivery' => 'Date delivery',
	'Class:PrinterSupply/Attribute:date_delivery+' => 'Date delivery',	
	'Class:PrinterSupply/Attribute:date_out' => 'Date out',
	'Class:PrinterSupply/Attribute:date_out+' => 'Date out',	
	
	'Menu:SearchPrinterSupply' => 'Search for printer supplies',
	'Menu:SearchPrinterSupply+' => 'Search for printer supplies',
	'Menu:NewPrinterSupply' => 'New printer supply',
	'Menu:NewPrinterSupply+' => 'Register new printer supply',
	
	'Class:lnkPrinterSupplyTypeToModel' => 'Link PrinterSupply / Model',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:printersupplytype_id' => 'Supply type',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:printersupplytype_id+' => 'Printer supply type',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:model_id' => 'Printer model',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:model_id+' => 'Model of printer for which this supply can be used',	
	
	'Class:Printer/Attribute:printersupplies_dashboard' => 'Supplies',
	'Class:Printer/Attribute:printersupplies_dashboard/supplytypes_list' => 'Supply types',
	'Class:Printer/Attribute:printersupplies_dashboard/supplies_list' => 'Available supplies',
	
));

