<?php

 
	require_once("api.php");
	
	
	class iTop_Scan extends iTop_Rest {
		
		
		/**
		 * Registers a lending record. Detects automatically if it's in or out.
		 *
		 * @param $params Array containing a key 'serialnumber' (of PhysicalDevice), org_id, contact_id 
		 * 
		 * @return Array containing a key 'error' (0/1) and details of the created/updated item
		 */ 
		function register( $params = [] ) {
					
			// Obtain PhysicalDevice id 
			$res = $this->post([
				"operation" => "core/get", 
				"class" => "PhysicalDevice", 
				"key" => "SELECT PhysicalDevice WHERE serialnumber = '".@$params["serialnumber"]."'"
			]);
			 
			// Convert stdClass to array
			$res = $res;
			 
			
			if( $res["code"] == 0 ) {
					
				if( count( $res["objects"] ) < 1 ) {
					return [
						"error" => "scan_001", 
						"msg" => "This serial number is not linked to a PhysicalDevice."
					];
				} 
				elseif( count( $res["objects"] ) > 1 ) {
					return [
						"error" => "scan_002", 
						"msg" => "This serial number is not uniquely linked to 1 PhysicalDevice."
					];
				} 
				else {
					
					// Uniquely identified PhysicalDevice 
					$physicaldevice_id = $res["objects"][ array_keys($res["objects"])[0] ]["key"];
					
					// Is there still an active record? (LendRecord with date_out is null)
					
					$res = $this->post([
						"operation" => "core/get", 
						"class" => "LendRecord", 
						"key" => "SELECT LendRecord WHERE physicaldevice_id = '".$physicaldevice_id."' AND ISNULL( date_in )"
					]); 
					 
					 
					$fields = [	
						"physicaldevice_id" => $physicaldevice_id,
						"org_id" => $params["org_id"],
						"contact_id" => $params["contact_id"],  
						"date_out" => date("Y-m-d H:i:s")
					];
					
					if( isset($params["reason"]) == TRUE ) {
						$fields["reason"] = $params["reason"];
					}
					
					if( isset($params["remarks"]) == TRUE ) {
						$fields["remarks"] = $params["remarks"];
					}
					
					
					if( count($res["objects"]) == 0 ) {
						
						// Not lended out. Lend out now.
						
						$res = $this->post([
							"operation" => "core/create", 
							"comment" => "Create from iTop_Scan",
							"class" => "LendRecord",  
							// "output_fields" => [],
							"fields" => $fields 
						]);
						
						return $res;
						
						
					} 
					elseif( count($res["objects"]) == 1 ) {
						
						// lended out. take back.
												
						$lendrecord_id = $res["objects"][ array_keys($res["objects"])[0] ]["key"];
						
						$fields = [
							"date_in" => date("Y-m-d H:i:s")
						];
						
						if( isset($params["reason"]) == TRUE ) {
							$fields["reason"] = $params["reason"];
						}
						
						if( isset($params["remarks"]) == TRUE ) {
							$fields["remarks"] = $params["remarks"];
						}
						
						$res = $this->post([
							"operation" => "core/update", 
							"comment" => "Update from iTop_Scan",
							"class" => "LendRecord", 
							"key" => $lendrecord_id, 
							// "output_fields" => [],
							"fields" => $fields 
						]);
						
						return $res;
						
					}
					else {
						return [
							"error" => "scan_101", 
							"msg" => "Inconsistent lend record(s) for PhysicalDevice ID = ".$physicaldevice_id,
							"physicaldevice_id" => $physicaldevice_id
						];
					}
				}
				
			}
			else {
				
				// Exception. iTop REST error? 
				return $res;
				
			} 
			
		} 
		
		
		
			  
		/**
		 * Shortcut to getting PhysicalDevice 
		 *
		 * @param $params Array 
		 * 
		 * @return Array containing a key 'error' (0/1)
		 */ 
		function getPhysicalDeviceBySerialNumber( $params = []) {
			
			return $this->post([
				"operation" => "core/get", 
				"class" => "PhysicalDevice",
				"key" => "SELECT PhysicalDevice WHERE serialnumber = '".$params["serialnumber"]."'"			
			])["objects"];
			
		}
		 
		 
	}
	
	/* Examples 
 
	$i = new iTop_Scan();
		$res = $i->register([
			"serialnumber" => "test"
		]);
	 
	$res = $i->post([
		"operation" => "core/get", 
		"class" => "Organization",
		"key" => "SELECT Organization"
	]);
	
	
	$res = $i->post([
		"operation" => "core/get", 
		"class" => "Contact",
		"key" => "SELECT Contact WHERE org_id = 2"
	]);
	
	echo json_encode($res, JSON_PRETTY_PRINT );
	 
	*/

	$i = new iTop_Scan();
	
	
	// Get contacts
	$contacts = $i->getContactsByOrgId([
		"org_id" => 2
	]);
	
	// Sort by friendly name. Don't look at case, our users won't understand.
	function cmp($a, $b) {		
		return strcmp( strtolower( $a["fields"]["friendlyname"] ), strtolower( $b["fields"]["friendlyname"] ) );
	} 
	
	uasort($contacts, "cmp");


	 
	// Render
	echo $twig->render("checkout.html", [
		
		/* Params */
		"contacts" => $contacts
		
	]);

	
	

	
?>