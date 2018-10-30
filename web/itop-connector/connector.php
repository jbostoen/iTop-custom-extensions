<?php

	/**
	 * Defines a PHP Class named iTop_Rest which could be an useful parent class to build upon.
	 *
	 * Place iTop Connector under <iTopDir>/itop-connector
	 *
	 * @copyright  © 2018 - jbostoen
	 * @version    Release: @0.1.180809@
	 * @link       https://github.com/jbostoen
	 * @see        https://www.itophub.io/wiki/page?id=2_5_0%3Aadvancedtopics%3Arest_json
	 */ 
	class iTop_Rest {
		
		/** 
		 *  @var String URL of the iTop web services, including version. This is a test environment for us. 
		 */
		public $url = "http://localhost/itop/web/webservices/rest.php";
		
		/**
		 *  @var String describing the REST API version
		 */
		 public $version = "1.3";
		
		/**
		 *@var String User in iTop which has the REST User Profile (in iTop)
		 */
		public $user = "admin";
		/**
		 *@var String Password of the iTop user which has the REST User Profile (in iTop)
		 */
		public $password = "admin";
		
		/* For debugging only */
		/**
		 *  @var Boolean Output the request sent to iTop REST/JSON
		 */
		public $showRequest = false;
		
		/**
		 *  @var Boolean Output the response from iTop REST/JSON
		 */
		public $showResponse = false;
		
		
		public function __construct( ) {

			// If 'itop-connector' folder is placed within iTop-directory, the url property will automatically be adjusted
			if( file_exists("../approot.inc.php") == true ) {
				
				
				
				// Assume we're in iTop directory
				require_once("../approot.inc.php");
				
				// Get iTop config file 
				echo APPCONF . "/" . ITOP_DEFAULT_ENV . PHP_EOL;
				if( file_exists( APPCONF . "/" . ITOP_DEFAULT_ENV . "/config-itop.php") == true ) {
					
					$this->url = $MySettings["app_root_url"] . "/webservices/rest.php";
					echo "URL set to " . $this->url;
				}
				
				
			}  
			
			
		}
		
		 
		
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
				"&version=".$this->version.
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
		 * @param Array $params Array [
		 *   'key'              => Required.
		 *                         Int (iTop ID) 
		 *                         String (OQL Query) 
		 *                         Array (one or more fields and their values)
		 *    'class'           => Required if key is not an OQL Query. 
		 *                         String. iTop class name (examples: Organization, Contact, Person ...)
		 *    'output_fields'   => Optional. Array. List of field names you want to retrieve. 
		 *                         If not specified, it returns all fields.
		 *  
		 *    'onlyValues'      => Optional. Boolean. 
		 *                         Not related to iTop. Will return the objects without a key.
		 *                         
		 *	]
		 * 

		 * @return Array
		 * Array [
		 *    iTopclass::<Id1>' => [ iTop object data ], 
		 *    iTopclass::<Id2>' => [ iTop object data ], 
		 * 	  ...
		 * ]
		 *
		 * or 
		 * 
		 * Array [
		 *        'code'        => iTop error code (see iTop REST Documentation)
		 *        'message'     => iTop error message
		 * ]
		 *
		 *
		 * or if onlyValues = true:
		 *
		 * @return Array
		 * Array [
		 *    [ iTop object data ], 
		 *    [ iTop object data ], 
		 * 	  ...
		 * ]
		 * 
		 */ 
		function get( Array $params = [] ) {
			
			$params = $this->deriveClass( $params );
			 			
			$res = $this->post([
				"operation" => "core/get", 
				"class" => $params["class"],
				"key" => $params["key"],
				"output_fields" => ( isset($params["output_fields"]) == TRUE ? implode(", " , $params["output_fields"]) :	"*" )			
			]);
			  
			
			if( $res["code"] == 0 ) {
				
				// Valid call, no results?
				if( isset( $res["objects"]  ) == false ) {
					return [];
				}
				else {
					return ( isset( $params["onlyValues"]) == true ? ( $params["onlyValues"] == true ? array_values($res["objects"]) : $res["objects"] ) : $res["objects"] );
				}
			}
			else {
				return $res;
			} 
			
		} 
	
	
		/**
		 * Shortcut to creating data
		 *
		 * @param Array $params Array [
		 *    'comment'         => Required. String. Describing the action.
		 *    'fields'          => Required. Array. The fields and values for them that need to be updated
		 *    'class'           => Required. String. iTop class name (examples: Organization, Contact, Person ...)
		 *    'output_fields'   => Optional. Array. List of field names you want to retrieve. 
		 *                         If not specified, it returns all fields.
		 *  
		 *    'onlyValues'      => Optional. Boolean. 
		 *                         Not related to iTop. Will return the objects without a key.
		 *	]
		 * 
		 * @return Array
		 * Array [
		 *    iTopclass::<Id1>' => [ iTop object data ], 
		 *    iTopclass::<Id2>' => [ iTop object data ], 
		 * 	  ...
		 * ]
		 *
		 * or 
		 * 
		 * Array [
		 *        'code'        => iTop error code (see iTop REST Documentation)
		 *        'message'     => iTop error message
		 * ]
		 *
		 *
		 *
		 * or if onlyValues = true:
		 *
		 * @return Array
		 * Array [
		 *    [ iTop object data ], 
		 *    [ iTop object data ], 
		 * 	  ...
		 * ]
		 *
		 */ 
		function create( Array $params = [] ) {
			
			$params = $this->deriveClass( $params );
			
			$res = $this->post([
				"operation" => "core/create", 
				"class" => $params["class"],
				"output_fields" => ( isset($params["output_fields"]) == TRUE ? implode(", " , $params["output_fields"]) :	"*" ),
				"fields" => $params["fields"],
				"comment" => $params["comment"]
			]);
			
			if( $res["code"] == 0 ) {
				return ( isset( $params["onlyValues"]) == true ? ( $params["onlyValues"] == true ? array_values($res["objects"]) : $res["objects"] ) : $res["objects"] );
			}
			else {
				return $res;
			} 
			
		}	
		
		/**
		 * Shortcut to updating data
		 *
		 * @param $params Array [
		 *   'comment'          => Required. String. Describing the action.
		 *   'fields'           => Required. Array. The fields and values for them that need to be updated
		 *   'key'              => Required.
		 *                         Int (iTop ID) 
		 *                         String (OQL Query) 
		 *                         Array (one or more fields and their values)
		 *   'class'            => Required if key is not an OQL Query. 
		 *                         String. iTop class name (examples: Organization, Contact, Person ...)
		 *   'output_fields'    => Optional. Array. List of field names you want to retrieve. 
		 *                         If not specified, it returns all fields.
		 * ]
		 * 
		 * @return Array
		 * Array [
		 *    iTopclass::<Id1>' => [ iTop object data ], 
		 *    iTopclass::<Id2>' => [ iTop object data ], 
		 * 	  ...
		 * ]
		 *
		 * or 
		 * 
		 * Array [
		 *        'code'        => iTop error code (see iTop REST Documentation)
		 *        'message'     => iTop error message
		 * ]
		 *
		 */ 
		function update( Array $params = [] ) {
			
			$params = $this->deriveClass( $params );
			
			$res = $this->post([
				"operation" => "core/update", 
				"class" => $params["class"],
				"key" => $params["key"],
				"output_fields" => ( isset($params["output_fields"]) == TRUE ? implode(", " , $params["output_fields"]) :	"*" ),
				"fields" => $params["fields"],
				"comment" => $params["comment"]
			]);
			
			if( $res["code"] == 0 ) {
				return $res["objects"];
			}
			else {
				return $res;
			} 
			
		}
		
		
		/**
		 * Shortcut to deleting data
		 *
		 * @param Array $params Array [
		 *   'comment'          => Required. String. Describing the action. 
		 *   'key'              => Required.
		 *                         Int (iTop ID) 
		 *                         String (OQL Query) 
		 *                         Array (one or more fields and their values)
		 *   'class'            => Required if key is not an OQL Query. 
		 *                         String. iTop class name (examples: Organization, Contact, Person ...)
		 *   'output_fields'    => Optional. Array. List of field names you want to retrieve. 
		 *                         If not specified, it returns all fields.
		 *   'simulate'         => Optional. Boolean. Defaults to false.
		 *	]
		 * 
		 * @return Array
		 * Array [
		 *    iTopclass::<Id1>' => [ iTop object data ], 
		 *    iTopclass::<Id2>' => [ iTop object data ], 
		 * 	  ...
		 * ]
		 *
		 * or 
		 * 
		 * Array [
		 *        'code'        => iTop error code (see iTop REST Documentation)
		 *        'message'     => iTop error message
		 * ]
		 *
		 */ 
		function delete( Array $params = [] ) {
			
			$params = $this->deriveClass( $params );
			
			$res = $this->post([
				"operation" => "core/delete", 
				"class" => $params["class"],
				"key" => $params["key"],
				"output_fields" => ( isset($params["output_fields"]) == TRUE ? implode(", " , $params["output_fields"]) :	"*" ),
				"comment" => $params["comment"],
				"simulate" => ( isset($params["simulate"]) == TRUE ? $params["simulate"] : FALSE )
			]);
			
			if( $res["code"] == 0 ) {
				return $res["objects"];
			}
			else {
				return $res;
			} 
			
		}
		
		
		/**
		 *  If an OQL query is specified as a 'key', this will automatically detect 'class' if it's missing.
		 *  
		 *  @param Array $oInput 
		 *  @return Array Same as $oInput, but if 'class' was missing, it will now be set if it can be derived from 'key'.
		 */
		private function deriveClass( Array $aInput ) {
							
			if( isset( $aInput["class"] ) == FALSE ) {
				 				
				// Is this an OQL query? Or an Integer as String?
				if( is_string($aInput["key"]) == TRUE ) {
					 
					if( strtolower( substr($aInput["key"], 0, 7) ) == "select ") {
						$aInput["class"] = explode(" ", $aInput["key"] )[1];
					}					
				}
				else {
					// Integer or Array
					throw new Exception("Error: 'class' was not defined, but it could also not be derived from 'key'.");
				}
				
			}
			  
			
			return $aInput;
			
		}
			
		  
		
		/**
		 * Shortcut to getting proper encoded base64 for data
		 *
		 * @param String $sFileName Path of the file you want to prepare (already on your server)  
		 * 
		 * @return Array
		 * [
		 *   'data'            => base64 encoded file
		 *   'mimetype'        => MIME-type of the file
		 *   'filename'        => Original filename
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