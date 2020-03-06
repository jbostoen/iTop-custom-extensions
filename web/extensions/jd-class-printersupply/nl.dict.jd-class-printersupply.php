<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2019-2020 Jelle Defoort, Jeffrey Bostoen
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('NL NL', 'Dutch', 'Dutch', array(
	
	// Dictionary entries go here
	'PrinterSupply:in' => 'In',
	'PrinterSupply:out' => 'Uit',

	'Class:PrinterSupplyType' => 'Soort PrinterBenodigheid',
	'Class:PrinterSupplyType+' => 'Soort PrinterBenodigheid',
	'Class:PrinterSupplyType/Attribute:name' => 'Naam',
	'Class:PrinterSupplyType/Attribute:name+' => 'Naam van deze benodigdheid. Tip: start met de oorspronkelijke (merk)naam en voeg ernaast een huismerk toe, bv. HP A30 Black / IsoTech CLA776 of Brother DR-2100 Drum.',
	'Class:PrinterSupplyType/Attribute:models_list' => 'Modellen',
	'Class:PrinterSupplyType/Attribute:models_list+' => 'De printermodellen waarvoor deze benodigdheid geschikt is',
	'Class:PrinterSupplyType/Attribute:printersupplies_list' => 'Benodigdheden',
	'Class:PrinterSupplyType/Attribute:printersupplies_list+' => 'Lijst van dit soort benodigdheden',

	'Class:PrinterSupply' => 'PrinterBenodigheid',
	'Class:PrinterSupply+' => 'PrinterBenodigheid',
	'Class:PrinterSupply/Attribute:printersupplytype_id' => 'Benodigdheid',
	'Class:PrinterSupply/Attribute:printersupplytype_id+' => 'De benodigdheid',
	'Class:PrinterSupply/Attribute:location_id' => 'Locatie',
	'Class:PrinterSupply/Attribute:location_id+' => 'Locatie waar deze benodigdheid ligt',	
	'Class:PrinterSupply/Attribute:printer_id' => 'Printer',
	'Class:PrinterSupply/Attribute:printer_id+' => 'De printer waar deze benodigdheid gebruikt werd',
	'Class:PrinterSupply/Attribute:date_delivery' => 'Datum levering',
	'Class:PrinterSupply/Attribute:date_delivery+' => 'Datum levering',	
	'Class:PrinterSupply/Attribute:date_out' => 'Datum uit',
	'Class:PrinterSupply/Attribute:date_out+' => 'Datum uit',	
	
	'Menu:SearchPrinterSupply' => 'Zoek printerbenodigdheden',
	'Menu:SearchPrinterSupply+' => 'Zoek printerbenodigdheden',
	'Menu:NewPrinterSupply' => 'Nieuwe printerbenodigdheid',
	'Menu:NewPrinterSupply+' => 'Nieuwe printerbenodigdheid',
	
	'Class:lnkPrinterSupplyTypeToModel' => 'Link PrinterBenodigheid / Model',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:printersupplytype_id' => 'Soort printerbenodigheid',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:printersupplytype_id+' => 'Soort printerbenodigdheid',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:model_id' => 'Printermodel',
	'Class:lnkPrinterSupplyTypeToModel/Attribute:model_id+' => 'Printermodel waarvoor deze benodigdheid kan worden gebruikt',	
	
	'Class:Printer/Attribute:printersupplies_dashboard' => 'Printerbenodigdheden',
	'Class:Printer/Attribute:printersupplies_dashboard/supplytypes_list' => 'Soort printerbenodigdheden',
	'Class:Printer/Attribute:printersupplies_dashboard/supplies_list' => 'Beschikbare printerbenodigdheden',
	
));

