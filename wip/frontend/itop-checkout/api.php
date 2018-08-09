<?php

	/**
	 * Translates JSON requests to PHP stuff
	 *
	 * @copyright  2018 jbostoen
	 * @version    Release: @0.1.180411@
	 * @link       https://github.com/jbostoen
	 * @since      Class available since Release 1.2.0
	 */ 
 
 
 
	require_once("../Twig/vendor/autoload.php" ); 
	
	$loader = new Twig_Loader_Filesystem("templates");
	$twig = new Twig_Environment($loader, array(
		"autoescape" => false
		// 'cache' => '/path/to/compilation_cache',
	));
	
	$twig->addExtension(new Twig_Extensions_Extension_Intl());
	

	
	// Contains actual classes etc;
	require("../itop-connector/connector.php");
	
	
	class iTop_Scan extends iTop_Rest {
		
		
		/**
		 * Shortcut to getting a list of Organizations
		 *
		 * @param $params Array Reserved for future use
		 * 
		 * @return Array [
		 *		[
		 *			"Organization::<Id1>" => 	
		 * 				[ Organization object data from iTop REST/JSON services ]
		 * 		],
		 *		[
		 *			"Organization::<Id2>" => 	
		 * 				[ Organization object data from iTop REST/JSON services ]
		 * 		],
		 *		...
		 * ]
		 */ 
		function getOrgs( Array $params = []) {
			
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
		 * @return Array [
		 *		[
		 *			"Contact::<Id1>" => 	
		 * 				[ Contact object data from iTop REST/JSON services ]
		 * 		],
		 *		[
		 *			"Contact::<Id2>" => 	
		 * 				[ Contact object data from iTop REST/JSON services ]
		 * 		],
		 *		...
		 * ]
		 */ 
		function getContactsByOrgId( Array $params = []) {
			
			return $this->post([
				"operation" => "core/get", 
				"class" => "Contact",
				"key" => "SELECT Contact WHERE org_id = '".$params["org_id"]."'"			
			])["objects"];
			
		}
		
		
		
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
	
	if( isset($_REQUEST["action"]) == TRUE ) {
			
		switch( $_REQUEST["action"] ) {
			
			case "GetOrganizations":
			
				/* Requires nothing */ 
				$res = $i->GetOrgs( );  
				break;
				
				
			case "GetContactsByOrgId": 
			
				/* Requires 'org_id' */			
				$res = $i->getContactsByOrg(["org_id" => $_REQUEST["org_id"] ]); 
				break;
				
				
			case "GetPhysicalDeviceBySerial":
			
				/* Requires 'serialnumber' */
				$res = $i->getPhysicalDeviceBySerialNumber(["serialnumber" => $_REQUEST["serialnumber"] ]);
				
				 
				if( isset( $res["objects"]) == TRUE ) {
						
					/* Let's find out if it's already lend out? */
					$res[ array_keys($res)[0] ]["takeback"] = ( count($i->post([
						"operation" => "core/get", 
						"class" => "LendRecord", 
						"key" => "SELECT LendRecord WHERE physicaldevice_id = '" . $res[ array_keys($res)[0] ]["key"]."' AND ISNULL( date_in ) "
					])["objects"])  == 1 ? 1 : 0 );
					
				}
				
				break;				
				
				
			case "Register":
			
				/* Requires 'serialnumber' */
				if( isset($_REQUEST["serialnumber"]) == TRUE ) {
					
					if( is_string( $_REQUEST["serialnumber"] ) == TRUE  ) {
						
						$_REQUEST["serialnumber"] = [ $_REQUEST["serialnumber"] ];
						
					}
					
					// Now, same procedure.
					if( is_array( $_REQUEST["serialnumber"] ) == TRUE ) {
						
						$res = [];
						foreach( $_REQUEST["serialnumber"] as $sn ) {
							
							$fields = [
								"serialnumber" => $sn,
								"org_id" => $_REQUEST["org_id"],
								"contact_id" => $_REQUEST["contact_id"]
							];
							
							if( isset( $_REQUEST["reason"] ) == TRUE ) {								
								$fields["reason"] = $_REQUEST["reason"];
							}
							if( isset( $_REQUEST["remarks"] ) == TRUE ) {								
								$fields["remarks"] = $_REQUEST["remarks"];
							}							 
							
							$res = array_merge( $res, $i->register( $fields ) );
					
						}
						
					}
					
					
				}
				else {
					
					$res = [
						"error" => "scan_000", 
						"msg" => "No serial number(s) specified"
					];
					
				}
				break;
				
				
			default:
			
				$res = [
					"error" => "scan_000", 
					"msg" => "Unknown action",
					"action" => $_REQUEST["action"]
				];
				
			
		}
		

		echo json_encode($res, JSON_PRETTY_PRINT );
		
	}
	else {
		// echo json_encode(["error" => "scan_000", "msg" => "No action specified."]);
	}
	

	
	 
	

?>




