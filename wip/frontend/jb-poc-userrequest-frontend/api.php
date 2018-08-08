<?php
 
	require_once("../itop-connector/connector.php");
	
	class iTop_Report_Issue_Public_Infrastructure extends iTop_Rest {
		
		function report( $data ) {
			
			$ret["error"] = 0;
		
			// To-do: Validate if all fields are set and completed in a proper way
			// <implement>
		 
			// Create new ticket. You could specify defaults here
			$aTicket = [
				"operation" => "core/create",
				"comment" => "Request from website",
				"class" => "UserRequest",
				"fields" => [
					"org_id" => 1,
					"title" => "New maintenance request from citizen",
					"description" => "<p>This is a long description about a problem. HTML allowed.</p>", 
					"start_date" => date("Y-m-d H:i:s"),
					"end_date" => null,
					"last_update" => date("Y-m-d H:i:s")
				]
			];
			 
			
			// echo json_encode($res, JSON_PRETTY_PRINT );
			$ret["ticket"] = $this->post( array_replace_recursive($aTicket, ["fields" => $data["ticket"]] ));
						
			
			if( $ret["ticket"]["code"] != 0 ) {
				return [
					"ticket" => [
						"error" => $ret["ticket"]["code"],
						"msg" => $ret["ticket"]["message"]
					]
				];
			}
			else {				
				// We should only receive 1 key (get ID for created UserRequest)
				$id = explode("::", array_keys($ret["ticket"]["objects"])[0] )[1];
			}
						
			
			
			if( isset( $data["attachment"] ) == TRUE ) {
							
				// Attach uploaded file to ticket
				$aAttachment = [
					"operation" => "core/create", 
					"class" => "Attachment",
					"comment" => "New maintenance request from citizen (attachment)",
					"fields" => [
						"expire" => NULL,
						"temp_id" => NULL,
						"item_class" => "UserRequest",
						"item_id" => $id,
						"item_org_id" => 1, 
						"contents" => $this->prepareFile($data["attachment"])
					]
				];
				
				// echo json_encode($res, JSON_PRETTY_PRINT );
				$ret["attachment"] = $this->post($aAttachment);								

				if( $ret["attachment"]["code"] != 0 ) {
					return [
						"attachment" => [
							"error" => $ret["attachment"]["code"],
							"msg" => $ret["attachment"]["message"]
						]
					];
				}
			}
			
			
			
			return $ret;
			
		} 
		
	}
 	
	$i = new iTop_Report_Issue_Public_Infrastructure(); 
		
	/*$res = $i->report([
		"ticket" => [
			"title" => "Suggestie voor Izegemse beeldkwaliteit",
			"description" => "Ergens anders kunnen ze dat <b>wel</b> mooi oplossen!"
		],
		"attachment" => "35071125_10214803692582541_1640894613373845504_n.jpg"
	]);*/
	 
	switch( @$_REQUEST["action"] ) {

		case "createTicket" :
			// Should have 'ticket' => [array of fields], 'attachment' => filename
			// Process file first
			echo json_encode($i->report($_REQUEST));
			break;

		default: 
			echo json_encode([
				"error" => "1", 
				"msg" => "Onbekende actie"
			]);
			break;
	}
 

?>