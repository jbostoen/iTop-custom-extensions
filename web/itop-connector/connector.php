<?php
 

	/**
	 * Defines a PHP Class named iTop_Rest which could be an useful parent class to build upon.
	 *
	 * Place iTop Connector under <iTopDir>/itop-connector
	 *
	 * @copyright  © 2018 - 2019 jbostoen
	 * @version    Release: 0.1.190319
	 * @link       https://github.com/jbostoen
	 * @see        https://www.itophub.io/wiki/page?id=latest%3Aadvancedtopics%3Arest_json
	 */  

	defined('JB_APPDIR_ITOP') or define('JB_APPDIR_ITOP', dirname(dirname( __FILE__ )) );


	/**
	 * Class iTop_Rest. A class to communicate with iTop API more efficiently in PHP implementations.
	 */
	class iTop_Rest {
		
		/** 
		 * @var String URL of the iTop web services, including version. Example: 'http://localhost/itop/web/webservices/rest.php'
		 * @details If left blank, an attempt to derive this info will happen in __construct()
		 */
		public $url = '';
		
		/**
		 * @var String describing the REST API version. 
		 */
		 public $version = '1.3'; /* 1.3 starting with iTop 2.2.0, still valid for iTop 2.6.0 */
		
		/**
		 *@var String User in iTop which has the REST User Profile (in iTop)
		 */
		public $user = 'user';
		/**
		 * @var String Password of the iTop user which has the REST User Profile (in iTop)
		 */
		public $password = 'user';
		
		/* For debugging only */
		/**
		 * @var Boolean Output the request sent to iTop REST/JSON
		 */
		public $showRequest = false;
		
		/**
		 * @var Boolean Output the response from iTop REST/JSON
		 */
		public $showResponse = false;
		
		
		public function __construct( ) {

			// If url is unspecified by default and this 'itop-connector' folder is placed within iTop-directory as expected, the url property will automatically be adjusted
			if( $this->url == '' && file_exists( JB_APPDIR_ITOP .'/approot.inc.php') == true ) {
				 
				// Assume we're in iTop directory; get definitions for APPCONF and ITOP_DEFAULT_ENV
				require_once( JB_APPDIR_ITOP . '/approot.inc.php');
				
				// Get iTop config file 
				if( file_exists( APPCONF . ITOP_DEFAULT_ENV . '/config-itop.php') == true ) {
					
					require( APPCONF . ITOP_DEFAULT_ENV . '/config-itop.php' ); // lcoal scope
					$this->url = $MySettings['app_root_url'] . 'webservices/rest.php';

				} 
				
			} 
			
			
		}
		
		
		
		/**
		 * Shortcut to create data
		 *
		 * @param Array $aParameters Array [
		 *  'comment'         => Optional. String. Describes the action and is stored in iTop's history tab.
		 *  'fields'          => Required. Array. The fields and values for the object to create.
		 *  'class'           => Required. String. iTop class name (examples: Organization, Contact, Person ...)
		 *  'output_fields'   => Optional. Array. List of field names you want to retrieve. 
		 *                       If not specified, all fields are returned.
		 * 
		 *  'onlyValues'      => Optional. Boolean. 
		 *                       Not related to iTop. Will return the objects without a key.
		 * ]
		 * 
		 * @return Array - see processResult()
		 *
		 */ 
		public function Create( Array $aParameters = [] ) {
			
			$sClassName = $this->GetClassName( $aParameters );
			
			print_r($aParameters);
			
			$aResult = $this->Post([
				'operation' => 'core/create', // Action
				'class' => $sClassName, // Class of object to create
				'fields' => $aParameters['fields'], // Field data to be saved
				'comment' => ( isset($aParameters['comment']) == true ? $aParameters['comment'] : 'Created by iTop Connector (REST)' ), // Comment in history tab
				'output_fields' => ( isset($aParameters['output_fields']) == true ? $aParameters['output_fields'] :	'*' /* All fields */ )
			]);
			
			return $this->ProcessResult( $aResult, $aParameters ); 
			
		}
		
		 
		
		/**
		 * Shortcut to delete data
		 *
		 * @param Array $aParameters Array [
		 *  'comment'         => Required. String. Describing the action. 
		 *  'key'             => Required.
		 *                       Int (iTop ID) 
		 *                       String (OQL Query) 
		 *                       Array (one or more fields and their values)
		 *  'class'           => Required, if key is not an OQL Query. 
		 *                       String. iTop class name (examples: Organization, Contact, Person ...)
		 *  'output_fields'   => Optional. Array. List of field names you want to retrieve. 
		 *                       If not specified, all fields are returned.
		 *  'simulate'        => Optional. Boolean. Defaults to false. According to iTop documentation, only available for delete operation.
		 * 
		 *  'onlyValues'      => Optional. Boolean. 
		 *                       Not related to iTop. Will return the objects without a key.
		 * 
		 * ]
		 * 
		 * @return Array - see processResult()
		 *
		 */ 
		public function Delete( Array $aParameters = [] ) {
			
			$sClassName = $this->GetClassName( $aParameters );
			
			$aResult = $this->Post([
				'operation' => 'core/delete', // iTop REST/JSON operation
				'class' => $sClassName, // Class of object to delete
				'key' => $aParameters['key'], // OQL query (String), ID (Float) or fields/values (Array)
				'comment' => $aParameters['comment'], // Comment in history tab?
				'output_fields' => ( isset($aParameters['output_fields']) == true ? $aParameters['output_fields'] :	'*' /* All fields */ ),
				'simulate' => ( isset($aParameters['simulate']) == true ? $aParameters['simulate'] : false )
			]);
			
			return $this->ProcessResult( $aResult, $aParameters ); 
			
		}
		
		
		  
		  
		/**
		 * Shortcut to get data
		 *
		 * @param Array $aParameters Array [
		 *  'key'             => Required.
		 *                       Int (iTop ID) 
		 *                       String (OQL Query) 
		 *                        Array (one or more fields and their values)
		 *  'class'           => Required if key is not an OQL Query. 
		 *                       String. iTop class name (examples: Organization, Contact, Person ...)
		 *  'output_fields'   => Optional. Array. List of field names you want to retrieve. 
		 *                       If not specified, all fields are returned.
		 * 
		 *  'onlyValues'      => Optional. Boolean. 
		 *                       Not related to iTop. Will return the objects without a key.
		 *                        
		 * ]
		 * 
		 *
		 * @return Array - see processResult()
		 * 
		 */ 
		public function Get( Array $aParameters = [] ) {
			
			$sClassName = $this->GetClassName( $aParameters );
			 			
			$aResult = $this->Post([
				'operation' => 'core/get', // iTop REST/JSON operation
				'class' => $sClassName, // Class of object(s) to retrieve
				'key' => $aParameters['key'], // OQL query (String), ID (Float) or fields/values (Array)
				'output_fields' => ( isset($aParameters['output_fields']) == true ? $aParameters['output_fields'] :	'*' /* All fields */ )			
			]);
			 
			return $this->ProcessResult( $aResult, $aParameters ); 
			
		} 
	
		
		/**
		 * If an OQL query is specified as a key, this will automatically detect and set the class name if it's missing.
		 * 
		 * @param Array $aInput Expects at least either a key named 'class' or a key named 'key' containing an iTop OQL query.
		 * @return String $sInput Class name.
		 *
		 */
		private function GetClassName( Array $aInput = [] ) {
							
			if( isset( $aInput['class'] ) == true ) {
				
				return $aInput['class'];
			
			}
			else {
				 				
				// Is this an OQL query? 
				// Other possibilities: Integer (ID); Array of one or more fields and their values.
				if( is_string($aInput['key']) == true ) {
					 
					if( preg_match('/^select /i', $aInput['key'] ) ) {
						// Dealing with an OQL query. 
						// Generic: SELECT UserRequest
						// Specific: SELECT UserRequest WHERE ...
						// Class names can't contain space, so:
						return explode(' ', $aInput['key'] )[1]; 
					}					
				} 
				
			}
			
			throw new Exception('Error in ' . __METHOD__ . '(): class was not defined and it could also not be derived from key.');
			
		}
			
		  
		
		
		/**
		 * Sends data to the iTop REST services and returns data (decoded JSON)
		 *
		 * @param $aJSONData [
		 *  'operation'       => Required. String.
		 *		( other fields, depending on the operation. Read iTop Rest/JSON documentation. )
		 * ];
		 * 
		 * @return Array containing the data obtained from the iTop REST Services
		 */ 
		public function Post( Array $aJSONData ) {
			   
			
			//  Initiate curl
			$ch = curl_init();
			 
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');        
			

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $this->user . ':' . $this->password );

			 
			// Set the url
			curl_setopt($ch, CURLOPT_URL, $this->url );
			
			// You need to use URL encode here. If you don't, you might end up with issues. A base64 string easily includes plus signs which need to be escaped
			$sPostString = ''.
				'&version='.$this->version.
				'&auth_user='.$this->user.
				'&auth_pwd='.$this->password.
				'&json_data='.urlencode(json_encode( $aJSONData ));
			 
				
			curl_setopt($ch, CURLOPT_POSTFIELDS, $sPostString);                                                                  
			curl_setopt($ch, CURLOPT_HTTPHEADER, [                                                                                
				'Content-Length: ' . strlen($sPostString)                                                                       
			]);             

			if( $this->showRequest == true ) {				
				echo 'Request:' . PHP_EOL .json_encode($aJSONData, JSON_PRETTY_PRINT ); 				
			}
			
						
			// Execute
			$sResult = curl_exec($ch);
			 			
			
			// Closing
			curl_close($ch);
  
			if( $this->showResponse == true ) { 
				echo 'Response: ' . PHP_EOL  .$sResult;  
			}
			
			$aResult = json_decode($sResult, true);
			
			if(!is_array($aResult)){
				throw new Exception('Invalid response from iTop API/REST. Incorrect configuration or something wrong with network or iTop?');
			}
    
			return $aResult; 
		
		}
		
		
		
		
		/**
		 * Shortcut to properly encode data in base64. Required to send to iTop REST/JSON services. 
		 *
		 * @param String $sFileName Path of the file you want to prepare (already on your server)  
		 * 
		 * @return Array
		 * [
		 *  'data'            => base64 encoded file
		 *  'mimetype'        => MIME-type of the file
		 *  'filename'        => Filename (short)
		 * ];
		 */ 
		public function PrepareFile( String $sFileName ) {
			
			$sFileName = $sFileName;
			$sType = mime_content_type($sFileName);
			$oData = file_get_contents($sFileName);
			//$base64 = "data:".$sType . ";base64," . base64_encode($oData);
			
			return [
				'data' => base64_encode($oData), // Warning: escape url_encode!
				'filename' => basename( $sFileName ),
				'mimetype' => $sType
			];
			
		}
		
		
		/**
		 * Processes JSON data retrieved from the iTop REST/JSON services. 
		 * Handles and simplifies the output of successful API calls. 
		 *  
		 * @param Array $aServiceResponse Service response after REST/JSON call
		 * @param Array $aParameters [
		 *  'onlyValues'      => Optional. Boolean. Defaults to false. Removes array keys if true.
		 * ]
		 * 
		 * @return Array, being similar to:
		 * 
		 * On error:
		 * 
		 * Array [
		 *  'code'            => iTop error code (see iTop REST Documentation)
		 *  'message'         => iTop error message
		 * ] 
		 * 
		 * No error and onlyValues = false (default): 
		 * 
		 * Array [
		 *  iTopclass::<Id1>' => [ iTop object data ], 
		 *  iTopclass::<Id2>' => [ iTop object data ], 
		 * 	  ...
		 * ]
		 * 
		 *
		 * No error and onlyValues = true:
		 *
		 * @return Array
		 * Array [
		 *   [ iTop object data ], 
		 *   [ iTop object data ], 
		 * 	  ...
		 * ]
		 *  
		 *  
		 * @details Simplification happens because we only return an array of objects, either with or without key. 
		 * If you want to check for errors, just check in the array if 'code' still exists.
		 */
		private function ProcessResult( Array $aServiceResponse = [], Array $aParameters = [] ) {
						
			if( $aServiceResponse['code'] == 0 ) {
				
				// Valid call, no results? (usually after 'operation/get'
				if( isset( $aServiceResponse['objects'] ) == false ) {
					return [];
				}
				else {
					return ( isset( $aParameters['onlyValues']) == true ? ( $aParameters['onlyValues'] == true ? array_values($aServiceResponse['objects']) : $aServiceResponse['objects'] ) : $aServiceResponse['objects'] );
				}
			}
			else {
				
				// Service response contained an error.
				return $aServiceResponse;
			} 
			
		}
		
		
		/**
		 * Shortcut to update data
		 *
		 * @param $aParameters Array [
		 *  'comment'          => Optional. String. Describes the action and is stored in iTop's history tab.
		 *  'fields'           => Required. Array. The fields and values for them that need to be updated
		 *  'key'              => Required.
		 *                        Int (iTop ID) 
		 *                        String (OQL Query) 
		 *                        Array (one or more fields and their values)
		 *  'class'            => Required if key is not an OQL Query. 
		 *                        String. iTop class name (examples: Organization, Contact, Person ...)
		 *  'output_fields'    => Optional. Array. List of field names you want to retrieve. 
		 *                        If not specified, it returns all fields.
		 * ]
		 * 
		 * @return Array - see processResult()
		 *
		 */ 
		public function Update( Array $aParameters = [] ) {
			
			$sClassName = $this->GetClassName( $aParameters );
			
			$aResult = $this->Post([
				'operation' => 'core/update', // iTop REST/JSON operation
				'class' => $sClassName, // Class of object to update
				'key' => $aParameters['key'], // OQL query (String), ID (Float) or fields/values (Array)
				'fields' => $aParameters['fields'], // Field data to be updated
				'comment' => ( isset($aParameters['comment']) == true ? $aParameters['comment'] : 'Updated by iTop Connector (REST)' ), // Comment in history tab
				'output_fields' => ( isset($aParameters['output_fields']) == true ? $aParameters['output_fields'] :	'*' /* All fields */ ),
			]);
			
			return $this->ProcessResult( $aResult, $aParameters ); 
			
		}
		
		
 
		
	}
	 
	 
