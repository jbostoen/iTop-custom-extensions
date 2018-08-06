<?php

	/**
	 * Short description for class
	 *
	 * Long description for class (if any)...
	 *
	 * @copyright  2018 jbostoen
	 * @version    Release: @0.1.180411@
	 * @link       https://github.com/jbostoen
	 * @since      -
	 */ 
 
	class iTop_Rest {
		
		/* URL of the iTop web services, including version. This is a test environment for us. */
		private $url = "http://localhost/itop/web/webservices/rest.php?version=1.3";
		
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
		function post( $jsonData, $params = [] ) {
			 
			
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
			
			// You need to use URL encode here. If you don't, you might end up with issues. A base64 string easily includes plus signs which need to be escaped
			$postString = "".
				"&auth_user=".$this->user.
				"&auth_pwd=".$this->password.
				"&json_data=".urlencode(json_encode( $jsonData ));
			 
				
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);                                                                  
			curl_setopt($ch, CURLOPT_HTTPHEADER, [                                                                                
				"Content-Length: " . strlen($postString)                                                                       
			]);             

			if( isset($params["showRequest"]) == TRUE ) {
				if( $params["showRequest"] == TRUE ) {
					echo "Request:" . PHP_EOL .json_encode($jsonData, JSON_PRETTY_PRINT ); 
				}
			}
			
						
			// Execute
			$result = curl_exec($ch);
			 			
			
			// Closing
			curl_close($ch);
  
			if( isset($params["showResponse"]) == TRUE ) {
				if( $params["showResponse"] == TRUE ) {
					echo "Response:" . PHP_EOL  .$result; 
				}
			}
   
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
		
		
		/**
		 * Shortcut to getting proper encoded base64 for data
		 *
		 * @param $fileName filename  
		 * 
		 * @return Array containing data, mimetype, filename
		 */ 
		function prepareFile( $params = []) {
			
			$sFileName = "35071125_10214803692582541_1640894613373845504_n.jpg";
			$sType = mime_content_type($sFileName);
			$oData = file_get_contents($sFileName);
			//$base64 = "data:".$sType . ";base64," . base64_encode($oData);
			
			return [
				"data" => base64_encode($oData), // Warning: escape url_encode!
				"filename" => basename( $sFileName ),
				"mimetype" => $sType
			];
			
			
		}
		 
		
	}
	
	

?>




