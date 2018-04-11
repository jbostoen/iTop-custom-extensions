<?php

	/**
	 * Short description for class
	 *
	 * Long description for class (if any)...
	 *
	 * @copyright  2018 jbostoen
	 * @version    Release: @0.1.180411@
	 * @link       https://github.com/jbostoen
	 * @since      Class available since Release 1.2.0
	 */ 
 
	class iTop_Rest {
		
		/* URL of the iTop web services, including version. This is a test environment for us. */
		private $url = "http://10.1.20.22/itop/web/webservices/rest.php?version=1.3";
		
		/* Credentials of an iTop user */
		private $user = "admin";
		private $password = "admin";
		
		
		
		/**
		 * Sends data to the iTop REST services and returns data (decoded JSON)
		 *
		 * @param $params Array containing a key 'serialnumber' (of PhysicalDevice) and contact_id 
		 * 
		 * @return Array containing the data obtained from the iTop REST Services
		 */ 
		function post( $jsonData ) {
			
			//  Initiate curl
			$ch = curl_init();
			 
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");        
			

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $this->user . ":" . $this->password );

			 
			// Set the url
			curl_setopt($ch, CURLOPT_URL, $this->url );
			
			$postString = "".
				"&auth_user=".$this->user.
				"&auth_pwd=".$this->password.
				"&json_data=".json_encode( $jsonData );
			 
				
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);                                                                  
			curl_setopt($ch, CURLOPT_HTTPHEADER, [                                                                                
				"Content-Length: " . strlen($postString)                                                                       
			]);                                          
						
			// Execute
			$result = curl_exec($ch);
			 			
			
			// Closing
			curl_close($ch);
  
  
			return json_decode($result, true);
		
		}
		
		
		/**
		 * Shortcut to getting organizations
		 *
		 * @param $params Array 
		 * 
		 * @return Array containing a key 'error' (0/1)
		 */ 
		function getOrgs( $params = []) {
			
			return $this->post([
				"operation" => "core/get", 
				"class" => "Organization",
				"key" => "SELECT Organization"			
			])["objects"];
			
		}
		
		/**
		 * Shortcut to getting contacts for a certain organization
		 *
		 * @param $params Array 
		 * 
		 * @return Array containing a key 'error' (0/1)
		 */ 
		function getContactsByOrgId( $params = []) {
			
			return $this->post([
				"operation" => "core/get", 
				"class" => "Contact",
				"key" => "SELECT Contact WHERE org_id = '".$params["org_id"]."'"			
			])["objects"];
			
		}
		
		
		 
		
	}
	
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
	

?>




