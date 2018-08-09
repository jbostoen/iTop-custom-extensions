<?php

	/**
	 * Defines a PHP Class named iTop_Rest which could be an useful parent class to build upon.
	 *
	 * @copyright  © 2018 - jbostoen
	 * @version    Release: @0.1.180809@
	 * @link       https://github.com/jbostoen
	 * @since      -
	 */ 
	class iTop_Rest {
		
		/** 
		 *  @var String URL of the iTop web services, including version. This is a test environment for us. 
		 */
		private $url = "http://localhost/itop/web/webservices/rest.php?version=1.3";
		
		/**
		 *@var String User in iTop which has the REST User Profile (in iTop)
		 */
		private $user = "admin";
		/**
		 *@var String Password of the iTop user which has the REST User Profile (in iTop)
		 */
		private $password = "admin";
		
		/* For debugging only */
		/**
		 *  @var Boolean Output the request sent to iTop REST/JSON
		 */
		private $showRequest = false;
		
		/**
		 *  @var Boolean Output the response from iTop REST/JSON
		 */
		private $showResponse = false;
		 
		
		/**
		 * Sends data to the iTop REST services and returns data (decoded JSON)
		 *
		 * @param $aJSONData [
		 *		'operation'			=> String. Required.
		 *		( other fields, depending on the operation. Read iTop Rest/JSON documentation. )
		 * ];
		 * 
		 * @return Array containing the data obtained from the iTop REST Services
		 */ 
		function post( Array $aJSONData ) {
			   
			
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
				"&json_data=".urlencode(json_encode( $aJSONData ));
			 
				
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);                                                                  
			curl_setopt($ch, CURLOPT_HTTPHEADER, [                                                                                
				"Content-Length: " . strlen($postString)                                                                       
			]);             

			if( $this->showRequest == TRUE ) {				
				echo "Request:" . PHP_EOL .json_encode($aJSONData, JSON_PRETTY_PRINT ); 				
			}
			
						
			// Execute
			$result = curl_exec($ch);
			 			
			
			// Closing
			curl_close($ch);
  
			if( $this->showResponse == TRUE ) { 
				echo "Response:" . PHP_EOL  .$result;  
			}
    
			return json_decode($result, true); 
		
		}
		
		
		/**
		 * Shortcut to getting data
		 *
		 * @param $params Array [
		 *			'key' 			=> 	Required. Int (iTop ID) or String (OQL Query)
		 *			'class' 		=> 	Required if key is Int. String. iTop class name (examples: Organization, Contact, Person ...)
		 *			'fields' 		=> 	Optional. Array. List of field names you want to retrieve. 
		 *								If not specified, it returns all fields.
		 *		]
		 * 
		 * @return Array [
		 *		[
		 *			'iTopclass::<Id1>' => 	
		 * 				[ iTop object data from iTop REST/JSON services ]
		 * 		],
		 *		[
		 *			'iTopclass::<Id1>' => 	
		 * 				[ iTop object data from iTop REST/JSON services ]
		 * 		],
		 *		...
		 * ]
		 */ 
		function get( Array $params = [] ) {
			
			$res = $this->post([
				"operation" => "core/get", 
				"class" => ( isset($params["class"]) == TRUE ? $params["class"] : explode(" ", $params["key"])[1] ),
				"key" => ( $params["key"] ),
				"output_fields" => ( isset($params["fields"]) == TRUE ? implode(", " , $params["fields"]) :	"*" )			
			]);
			
			if( $res["code"] == 0 ) {
				return $res["objects"];
			}
			else {
				return $res;
			} 
			
		} 
		  
		
		/**
		 * Shortcut to getting proper encoded base64 for data
		 *
		 * @param $sFileName String Path of the file you want to prepare (already on your server)  
		 * 
		 * @return Array
		 * [
		 *		'data' 		=> base64 encoded file
		 *		'mimetype'	=> MIME-type of the file
		 *		'filename' 	=> Original filename
		 * ];
		 */ 
		function prepareFile( String $sFileName ) {
			
			$sFileName = $sFileName;
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