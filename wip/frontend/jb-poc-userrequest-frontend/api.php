<?php
 
 
	/**
	 *  Basic specific API for UserRequest through an open form 
	 */
 
 
	require_once("../itop-connector/connector.php");
	
	
	/**
	 *  Class iTop_Report_Issue_Public_Infrastructure . Contains specific methods to handle citizen requests.
	 */
	class iTop_Report_Issue_Public_Infrastructure extends iTop_Rest {
		
		/**
		 * Posts data to iTop instance using the iTop. Creates a UserRequest based on 
		 *   		 *  
		 *  @param Array $aData Associative array containing information which needs to be send to iTop. 
		 *  [
		 *    'item'         => [
		 *     
		 *    ]
		 *  ]
		 *   
		 *  @return Array [
		 *    'error'        => Error code
		 *                         0 : no error
		 *  
		 *    'request'      => Original request data
		 *     
		 *  ]
		 *   
		 */
		public function report( Array $aData = [] ) {
			
			$aReturn["error"] = 0;
		
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
			
			// Post this information to iTop.
			// Attachment info will be done in a separate POST. 
			$aReturn["ticket"] = $this->post( array_replace_recursive($aTicket, ["fields" => $data["ticket"]] ));
						
			// Code should be 0. = no error 
			if( $aReturn["ticket"]["code"] != 0 ) {
				return [
					"ticket" => [
						"error" => $aReturn["ticket"]["code"],
						"msg" => $aReturn["ticket"]["message"]
					]
				];
			}
			else {				
				// We should only receive 1 key (get ID for created UserRequest)
				$iTicketId = explode("::", array_keys($aReturn["ticket"]["objects"])[0] )[1];
			}
						
			
			// Is a file attached? (careful! include security implementation)
			if( isset( $data["attachment"] ) == true ) {
							
				// Attach uploaded file to ticket
				$aAttachment = [
					"operation" => "core/create", 
					"class" => "Attachment",
					"comment" => "New maintenance request from citizen (attachment)",
					"fields" => [
						"expire" => NULL,
						"temp_id" => NULL,
						"item_class" => "UserRequest",
						"item_id" => $iTicketId,
						"item_org_id" => 1, 
						"contents" => $this->prepareFile($data["attachment"]) // prepares (encodes) file
					]
				];
				
				// echo json_encode($res, JSON_PRETTY_PRINT );
				$aReturn["attachment"] = $this->post($aAttachment);								

				if( $aReturn["attachment"]["code"] != 0 ) {
					return [
						"attachment" => [
							"error" => $aReturn["attachment"]["code"],
							"msg" => $aReturn["attachment"]["message"]
						]
					];
				}
			}
			
			
			
			return $aReturn;
			
		} 
		
	}
	
	
	
	
	
	// Actually try/do something 
 	
	$i = new iTop_Report_Issue_Public_Infrastructure(); 
		
	$res = $i->report([
		"ticket" => [
			"title" => "Suggestie voor Izegemse beeldkwaliteit", // ticket title 
			"description" => "Ergens anders kunnen ze dat <b>wel</b> mooi oplossen!" // ticket description 
		],
		"attachment" => "35071125_10214803692582541_1640894613373845504_n.jpg" // filename 
	]);
	 
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