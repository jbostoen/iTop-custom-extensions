<?php
	
	/**
	 *  Language file
	 */
	 
	// @todo Perhaps rewrite to Combodo standards
	 
	$lang = [
				
		// Columns
		"serial_number" => "Serienummer",
		"description" => "Beschrijving",
		
		"lend_in_out" => "In / uit",
		"lend_take_back" => "Terugnemen",
		"lend_out" => "Uitlenen",
		"lend_to" => "Uitlenen aan",
		"lend_reason" => "Reden",
		"lend_remark" => "Opmerking (bv. 'kapot bij teruggave')",
		
		"add_physical_device" => "Item toevoegen (via serienummer)",
		
		// Actions
		"clear" => "Wissen",
		"look_up" => "Opzoeken",
		"register" => "Registreer",
		
		// Errors
		"err_serial_number_not_found" => "Serienummer niet gevonden",
		"err_serial_number_duplicate" => "Serienummer niet uniek",
		"err_no_contact" => "Geen contact geselecteerd",
		"err_create_lend_record_failed" => "Kon geen LendRecord aanmaken (mogelijke reden: zijn er meerdere records zonder 'date_in'?)",
		"err_device_already_in_list" => "Item is al toegevoegd"
	
	];
	
?>