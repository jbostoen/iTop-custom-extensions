<?php

	require_once("../itop-connector/connector.php");
	
	class iTop_Report_Issue_Public_Infrastructure extends iTop_Rest {
		
		function report( $data ) {
			
			// Create new ticket
			
			// Attach uploaded file to ticket
			$aData = [
				"operation" => "core/create", 
				"class" => "Attachment",
				"key" => "SELECT Attachment",
				"comment" => "Input from Proof of concept",
				"fields" => [
					"expire" => NULL,
					"temp_id" => NULL,
					"item_class" => "UserRequest",
					"item_id" => 1,
					"item_org_id" => 1, 
					"contents" => $this->prepareFile($data["attachmentPhoto"])
				]
			];
			
			$res = $this->post($aData, [/*"showRequest" => true, "showResponse" => true */]);	
			
			echo json_encode($res, JSON_PRETTY_PRINT );
			
		} 
		
	}
 
	
	$i = new iTop_Report_Issue_Public_Infrastructure(); 
	
	$json = $i->report([
		"attachmentPhoto" => "35071125_10214803692582541_1640894613373845504_n.jpg"
	]);
	
	
 

?>