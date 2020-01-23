<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-01-23 11:41:53
 *
 * Defines class iTop_Rest_Exception, which offers some more information in an exception.
 * Could have been an extension of iTop_FrameWork_Exception, but kept stand-alone for iTop_Rest.
 */
	
	/**
	 * Class iTop_Rest_Exception. Adds more details, mostly to output to JSON.
	 */
	class iTop_Rest_Exception extends \Exception {
	
		/**
		 * @var Array $aDetails Array with detailed information.
		 * @used-by iTop_Rest_Exception::GetDetails()
		 * @used-by iTop_Rest_Exception::ToJSON()
		 */
		private $aDetails = [];
		
		
		// Redefine the exception so message isn't optional
		/**
		 * Construct method
		 *
		 * @param \String $sMessage Short message describing the error
		 * @param \Integer $iCode Integer indicating an error. Defaults to 0
		 * @param \Exception $oPreviousException Previous exception
		 * @param \Array $aResponseFromAPI Hashtable containing more details. Details are optional, but should provide an iTop API REST-response.
		 */
		public function __construct($sMessage, $iCode = 0, \Exception $oPreviousException = null, $aResponseFromAPI = []) {
			
			// make sure everything is assigned properly
			parent::__construct($sMessage, $iCode, $oPreviousException);
			
			// Extend
			$this->aDetails = $aResponseFromAPI;
		}
		
		
		/**
		 * Returns detailed error information.
		 *
		 * @uses \iTop_Rest_Exception::$aDetails
		 * @return Array
		 */
		public function GetDetails() {
			return $this->aDetails;
		}
		
		/**
		 * Returns JSON-encoded detailed error information.
		 *
		 * @uses \iTop_Rest_Exception::$aDetails
		 * @return String ($aDetails JSON-encoded)
		 */
		public function ToJSON() {
			return json_encode($aDetails);
		}
		
	}
	
