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
	require("connector.php");
	
 
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
				
				 
				if( isset( $res["objects"]) == FALSE ) {
						
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
						
					if( is_array( $_REQUEST["serialnumber"] ) == TRUE ) {
						
						$res = [];
						foreach( $_REQUEST["serialnumber"] as $sn ) {
								
							$res = array_merge( $res, $i->register([ 
								"serialnumber" => $sn,
								"org_id" => $_REQUEST["org_id"],
								"contact_id" => $_REQUEST["contact_id"]
							]));
					
						}
						
					}
					elseif ( is_string( $_REQUEST["serialnumber"] ) == TRUE ) {
						
						$res = $i->register([ 
								"serialnumber" => $_REQUEST["serialnumber"],
								"org_id" => $_REQUEST["org_id"],
								"contact_id" => $_REQUEST["contact_id"]
						]);
						
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




