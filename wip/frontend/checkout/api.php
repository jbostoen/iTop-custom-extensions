<?php

	/**
	 *  iTop Check Out
	 */

	/**
	 * Basic API for a check-out system
	 *
	 * @copyright  © 2018 - jbostoen
	 * @version    Release: @0.1.180810@
	 * @link       https://github.com/jbostoen
	 */ 
 
	
	// Contains actual classes etc;
	require_once("../../itop-connector/connector.php");
	
	// Twig 
 	require_once("../../libext/vendor/autoload.php" ); 
	
	// Language
	require_once("lang_nl.php");
	
	
	
	$loader = new Twig_Loader_Filesystem("templates");
	$twig = new Twig_Environment($loader, array(
		"autoescape" => false
		// 'cache' => '/path/to/compilation_cache',
	));
	
	$twig->addExtension(new Twig_Extensions_Extension_Intl());
		
	
	/**
	 *  iTop Scan extends iTop_Rest and contains specific methods written to keep track of an inventory of functionalCIs which are often used by different people.
	 */
	class iTop_Scan extends iTop_Rest {
		  
		 
		/**
		 * Registers a LendingRecord. Detects automatically if it's in or out.
		 *
		 * @param Array $params Array 
		 * $params = [
		 *   'serialnumber'  => Required. String. Serial number of a PhysicalDevice 
		 *   'org_id'        => Required. Integer. ID of an Organization
		 *   'contact_id'    => Required. Integer. ID of a Conact (deliberately chosen because we want both Person and Team).
		 * ]
		 * 
		 * @return Array 
		 * $array = [
		 *   'error'         => Integer or String. 0 if no error. 
		 *   'msg'           => Only if an error occurred.
		 */ 
		function register( Array $params = [] ) {
			
			global $lang;
					
			// Obtain PhysicalDevice id 
			$res = $this->get([
				"key" => "SELECT PhysicalDevice WHERE serialnumber = '".@$params["serialnumber"]."'"
			]);
			  
			
			if( isset($res["code"]) == FALSE ) {
					
				if( count( $res ) < 1 ) {
					return [
						"error" => "scan_001", 
						"msg" => $lang["err_serial_number_not_found"]
					];
				} 
				elseif( count( $res ) > 1 ) {
					return [
						"error" => "scan_002", 
						"msg" => $lang["err_serial_number_duplicate"]
					];
				} 
				else {
					
					// Uniquely identified PhysicalDevice 
					$physicaldevice_id = $res[ array_keys($res)[0] ]["key"];
					 
					
					// Is there still an active record? (LendRecord with date_out is null)					
					$res = $this->get([  
						"key" => "SELECT LendRecord WHERE physicaldevice_id = ".$physicaldevice_id." AND ISNULL( date_in )"
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
					 
					if( count($res) == 0 ) {
						
						// Not lended out. Lend out now.
						
						$res = $this->create([
							"comment" => "Create from iTop_Scan",
							"class" => "LendRecord",   
							"fields" => $fields 
						]);
						
						return $res;
						
						
					} 
					elseif( count($res) == 1 ) {
						
						// lended out. take back.
												
						$lendrecord_id = $res[ array_keys($res)[0] ]["key"];
						
						$fields = [
							"date_in" => date("Y-m-d H:i:s")
						];
						
						if( isset($params["reason"]) == TRUE ) {
							$fields["reason"] = $params["reason"];
						}
						
						if( isset($params["remarks"]) == TRUE ) {
							$fields["remarks"] = $params["remarks"];
						}
						
						$res = $this->update([ 
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
							"error" => "error_reg_101", 
							"msg" => $lang["err_create_lend_record_failed"],
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
		 
	}
	 
	 
 
	$i = new iTop_Scan();
	
	if( isset($_REQUEST["action"]) == TRUE ) {
			
		switch( $_REQUEST["action"] ) {
			
			case "GetOrganizations":
			
				/* Requires nothing */ 
				return $this->get([ 
					"class" => "Organization",
					"key" => "SELECT Organization"			
				]);  
				break;
				
				
			case "GetContactsByOrgId": 
			
				// Requires 'org_id'  
				return $i->get([ 
					"class" => "Contact",
					"key" => "SELECT Contact WHERE org_id = '".$_REQUEST["org_id"]."'"						
				]);   
				break;
				
				
			case "GetPhysicalDeviceBySerial":
			
				// Requires 'serialnumber' 
				
				$res = $i->get([
					"key" => "SELECT PhysicalDevice WHERE serialnumber = '".$_REQUEST["serialnumber"]."'"			
				]);
				 				 
				
				// No error during retrieving
				if( isset( $res["code"] ) == FALSE ) {
						
					// Does it exist? 
					switch( count($res ) ) {
						
						case 0: 
							$res = [
								"error" => "scan_lookup_201",
								"msg" => $lang["serial_number_not_found"] // Serial number not unique
							];
							break;
						
						
						case 1: 
							// Let's find out if it's already lend out? 
							// If it is: takeback = 1 
							$res[ array_keys($res)[0] ]["takeback"] = ( count($i->get([
								"key" => "SELECT LendRecord WHERE physicaldevice_id = '" . $res[ array_keys($res)[0] ]["key"]."' AND ISNULL( date_in ) "
							]))  == 1 ? 1 : 0 );
							
							break;
							
						default:
							$res = [
								"error" => "scan_lookup_202",
								"msg" => $lang["serial_number_duplicate"] // Serial number not unique
							];
							break;
					}
				}
				
				break;				
				
				
			case "Register":
			
				// Requires 'serialnumber'
				if( isset($_REQUEST["serialnumber"]) == FALSE ) {
										
					$res = [
						"error" => "scan_reg_001", 
						"msg" => "No serial number(s) specified"
					];
					
				}
				elseif( @$_REQUEST["contact_id"] == "" ) {
									
					$res = [
						"error" => "scan_reg_002", 
						"msg" => "No contact specified"
					];					
				}
				else {
					
					// Put string in Array so we can handle it the same way
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
 
							$registeredData = $i->register( $fields );
							 
							
							$res = array_merge( $res, $registeredData );
					
						}
						
					}
					
					
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