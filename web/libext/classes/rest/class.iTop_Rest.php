<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 17:01:06
 * @see         https://www.itophub.io/wiki/page?id=latest%3Aadvancedtopics%3Arest_json
 *
 * Defines class iTop_Rest, which communicates with iTop REST/JSON API
 *
 * 
 */
 
	/**
	 * Class iTop_Rest. A class to communicate with iTop API more efficiently in PHP implementations.
	 */
	class iTop_Rest {
		
		/**
		* @var Name which is used by default in REST comments
		*/
		public $name = 'iTop REST';		
		
		/**
		 * @var String Password of the iTop user which has the REST User Profile (in iTop)
		 */
		public $password = 'pwd';
		
		/* For debugging only */
		/**
		 * @var Boolean Output the request sent to iTop REST/JSON
		 */
		public $showRequest = false;
		
		/**
		 * @var Boolean Output the response from iTop REST/JSON
		 */
		public $showResponse = false;
		
		
		/** 
		 * @var String URL of the iTop web services, including version. Example: 'http://localhost/itop/web/webservices/rest.php'
		 * @details If left blank, an attempt to derive this info will happen in __construct()
		 */
		public $url = '';
		
		/**
		 *@var String User in iTop which has the REST User Profile (in iTop). iTop REST/JSON error messages might be in the native language of the specified user.
		 */
		public $user = 'admin';
		
		/**
		 * @var String describing the REST API version. 1.3 starting with iTop 2.2.0, still valid for iTop 2.6.0
		 */
		public $version = '1.3';
		
		
		public function __construct() {

			// If url is unspecified by default and this file is placed within iTop-directory as expected, the url property will automatically be adjusted
			if($this->url == '') {
				 
				// Assume we're in iTop directory; get definitions for APPCONF and ITOP_DEFAULT_ENV
				$sDirName = __DIR__ ;
				
				while($sDirName != dirname($sDirName)) {
					
					$sFile = $sDirName.'/approot.inc.php';
					if(file_exists($sFile) == true ) {

						// Compatibility with iTop 2.7; NOT loading Twig etc. Defaults!
						defined('APPROOT') || define('APPROOT', dirname($sFile).'/');
						defined('APPCONF') || define('APPCONF', APPROOT.'conf/');
						defined('ITOP_DEFAULT_ENV') || define('ITOP_DEFAULT_ENV', 'production');
						
						// Get iTop config file 
						if( file_exists( APPCONF . ITOP_DEFAULT_ENV . '/config-itop.php') == true ) {
							
							require( APPCONF . ITOP_DEFAULT_ENV . '/config-itop.php' ); // local scope
							$this->url = $MySettings['app_root_url'] . 'webservices/rest.php';

							return;
							
						}
						
					}
						
					// folder up
					$sDirName = dirname($sDirName);
					
				}
				
				// return hasn't happened: this means we have an error here.
				throw new \Exception('Could not automatically derive iTop Rest/JSON url');
				
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
		 *  'no_keys'         => Optional. Boolean. 
		 *                       Not related to iTop. Will return the objects without a key.
		 * ]
		 * 
		 * @return Array - see processResult()
		 *
		 */ 
		public function Create( Array $aParameters = [] ) {
			
			$sClassName = $this->GetClassName( $aParameters );
						
			$aResult = $this->Post([
				'operation' => 'core/create', // Action
				'class' => $sClassName, // Class of object to create
				'fields' => $aParameters['fields'], // Field data to be saved
				'comment' => ( isset($aParameters['comment']) == true ? $aParameters['comment'] : 'Created by ' . $this->name ), // Comment in history tab
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
		 *  'no_keys'         => Optional. Boolean. 
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
				'comment' => ( isset($aParameters['comment']) == true ? $aParameters['comment'] : 'Deleted by ' . $this->name ), // Comment in history tab?
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
		 *  'no_keys'         => Optional. Boolean. 
		 *                       Not related to iTop. Will return the objects without a key.
		 *                        
		 * ]
		 * 
		 *
		 * @return Array - see processResult()
		 * 
		 */ 
		public function Get(Array $aParameters = []) {
			
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
		private function GetClassName(Array $aInput = []) {
							
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
			
			throw new \Exception('Error in ' . __METHOD__ . '(): class was not defined and it could also not be derived from key.');
			
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
		public function Post(Array $aJSONData = []) {
			
			//  Initiate curl
			$ch = curl_init();
			 
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');        
			

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $this->user . ':' . $this->password);

			 
			// Set the url
			curl_setopt($ch, CURLOPT_URL, $this->url);
			
			// You need to use URL encode here. If you don't, you might end up with issues. A base64 string easily includes plus signs which need to be escaped
			$sPostString = ''.
				'&version='.$this->version.
				'&auth_user='.$this->user.
				'&auth_pwd='.$this->password.
				'&json_data='.urlencode(json_encode($aJSONData));
	
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
  
			if($this->showResponse == true) { 
				echo 'Response: ' . PHP_EOL  .$sResult;  
			}
			
			$aResult = json_decode($sResult, true);
			
			if(is_array($aResult) == false || isset($aResult['code']) == false){
				throw new \Exception('Invalid response from iTop API/REST. Incorrect configuration or something wrong with network or iTop?');
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
		public function PrepareFile(String $sFileName) {
			
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
		 *  'no_keys'         => Optional. Boolean. Defaults to false. Removes array keys if true.
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
		 * No error and no_keys = false (default): 
		 * 
		 * Array [
		 *  iTopclass::<Id1>' => [ iTop object data ], 
		 *  iTopclass::<Id2>' => [ iTop object data ], 
		 * 	  ...
		 * ]
		 * 
		 *
		 * No error and no_keys = true:
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
		private function ProcessResult(Array $aServiceResponse = [], Array $aParameters = []) {
			
			// Valid response ('code' = 0)
			if( isset( $aServiceResponse['code'] ) == true && $aServiceResponse['code'] == 0 ) {
								
				// Valid call, no results? (usually after 'operation/get'
				if(isset($aServiceResponse['objects'] ) == false) {
					return [];
				}
				else {
					$aObjects = $aServiceResponse['objects'];
					return (isset( $aParameters['no_keys']) == true ? ( $aParameters['no_keys'] == true ? array_values($aObjects) : $aObjects ) : $aObjects);
				}
			}
			else {
				
				// Service response contained an error.
				// Return all.
				if( isset($aServiceResponse['code']) == true && isset($aServiceResponse['message']) == true ) {
					// Valid response but error
					throw new \iTop_Rest_Exception('Invalid response from iTop REST/JSON Service: '.$aServiceResponse['message'], $aServiceResponse['code'], null, $aServiceResponse);
				}
				else {
					// Invalid response
					// Must still have been an array or exception would have occurred earlier
					throw new \iTop_Rest_Exception('No response from iTop REST/JSON Service. Check connection and credentials.');
				}
				
			} 
			
		}
		
		/**
		 * Shortcut to update data
		 *
		 * @param Array $aParameters Array [
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
		public function Update(Array $aParameters = []) {
			
			$sClassName = $this->GetClassName( $aParameters );
			
			$aResult = $this->Post([
				'operation' => 'core/update', // iTop REST/JSON operation
				'class' => $sClassName, // Class of object to update
				'key' => $aParameters['key'], // OQL query (String), ID (Float) or fields/values (Array)
				'fields' => $aParameters['fields'], // Field data to be updated
				'comment' => ( isset($aParameters['comment']) == true ? $aParameters['comment'] : 'Updated by ' . $this->name ), // Comment in history tab
				'output_fields' => ( isset($aParameters['output_fields']) == true ? $aParameters['output_fields'] :	'*' /* All fields */ ),
			]);
			
			return $this->ProcessResult( $aResult, $aParameters ); 
			
		}
		
		
	}
	
