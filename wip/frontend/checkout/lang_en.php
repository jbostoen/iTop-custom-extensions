<?php
	
	/**
	 *  Language file
	 */
	 
	// @todo Perhaps rewrite to Combodo standards
	 
	$lang = [
				
		// Columns
		"serial_number" => "Serial number",
		"description" => "Description",
		
		"lend_in_out" => "In / out",
		"lend_take_back" => "Take back",
		"lend_out" => "Lend out",
		"lend_to" => "Lend to",
		"lend_reason" => "Reason",
		"lend_remark" => "Remark (e.g. 'broken on return')",
		
		"add_physical_device" => "Add physical device (by serial number",
		
		// Actions
		"clear" => "Clear",
		"look_up" => "Look up",
		"register" => "Register",
		
		// Errors
		"err_serial_number_not_found" => "Serial number not found",
		"err_serial_number_duplicate" => "Serial number is not unique",
		"err_no_contact" => "No contact was selected",
		"err_create_lend_record_failed" => "Could not create a lend record (one cause is that somehow there are two records with no return date_in)",
		"err_device_already_in_list" => "Device was already added to the list"
	
	];
	
?>