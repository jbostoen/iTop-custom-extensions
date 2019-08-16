<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 * @experimental
 *
 * Defines class iTop_Helper_Phone, which offers some methods to validate phone numbers. Warning: specifically for Belgian use.
 *
 */
 
 namespace jb_contact_method;
	
	/**
	 * Class iTop_Helper_Phone. Provides some phone functions.
	 */
	abstract class iTop_Helper_Phone {
		
		/**
		 * @var String $allowedCharsRegex Allowed characters in phone number
		 * @used-by \iTop_Helper_Phone::OnlyContainsAllowedCharacters
		 */
		private static $allowedCharsRegex = '\.\/\+ 0-9]';
		
		/**
		 * Returns whether this is a local phone number
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return Boolean
		 */
		public static function IsLocal($sPhone) {
			$sPhone = self::ReturnDigits($sPhone);
			return (substr($sPhone, 0, 1) == '0' || substr($sPhone, 0, 2) == '32');
		}
		
		/**
		 * Returns whether this is a valid prefix for a Belgian mobile phone number
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return Boolean
		 */
		public static function IsMobilePrefix_BE( $sPhone ) {
			
			// https://www.bipt.be/en/consumers/telephone/numbering/numbering-principles
			// 046, 047, 048, 049
			// 04 = land line too, Liège and Voeren. Less digits!
			// That's why we check for the first 2 digits.
			
			// Strip leading country code, zero
			$sPhone = self::ReturnDigitsWithoutLocalDigits_BE($sPhone);		
			
			switch( substr($sPhone, 0, 2) ) {
				case '46':
				case '47':
				case '48':
				case '49':
					return ( strlen($sPhone) == 9 );
					break;
					
				default:
					break;
					
			}
		
			return false;

		}
		
		/**
		 * Returns whether this is a valid Belgian phone number (land line) (based on length)
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return Boolean
		 */
		public static function IsValidLandLinePhone_BE( $sPhone ) {
		
			// No use if invalid characters are found
			if(self::OnlyContainsAllowedCharacters($sPhone) == false) {
				return false;
			}
			
			$sPhone = self::ReturnDigitsWithoutLocalDigits_BE($sPhone);
			return ( strlen($sPhone) == 8 );

		}
		
		
		/**
		 * Returns whether this is a valid phone number (based on length)
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return Boolean
		 */
		public static function IsValidPhone( $sPhone ) {	
		
			// No use if invalid characters are found
			if(self::OnlyContainsAllowedCharacters($sPhone) == false) {
				return false;
			}
			
			// If (significant digits) = (significant digits of Belgium phone): 
			// Assume a Belgian phone number has been specified. Stricter requirements.
			$sPhone_digits = self::ReturnDigits($sPhone);
						
			if( strlen($sPhone_digits) != strlen(self::ReturnDigitsWithoutLocalDigits_BE($sPhone)) ) {
				// Belgian; more strict requirements
				return self::IsValidPhone_BE($sPhone);
			}
			elseif(strlen($sPhone_digits) > 10) {
				// International number, should have been prefixed
				// Phone number can only contain certain characters
				return true;
			}
			
			return false;
	
		}
	
		/**
		 * Returns whether this is a valid Belgian mobile phone number (based on length)
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return Boolean
		 */
		public static function IsValidMobilePhone_BE( $sMobilePhone ) {
			
			$sMobilePhone_significant_digits = self::ReturnDigitsWithoutLocalDigits_BE($sMobilePhone);
			return ( self::OnlyContainsAllowedCharacters($sMobilePhone) == true && strlen($sMobilePhone_significant_digits) == 9 && self::IsMobilePrefix_BE($sMobilePhone) == true );
					
		}
		
		/**
		 * Returns whether this is a valid Belgian phone number (land line or mobile)
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return Boolean
		 */
		public static function IsValidPhone_BE( $sPhone ) {
			return (self::IsValidLandLinePhone_BE($sPhone) == true || self::IsValidMobilePhone_BE($sPhone) == true);
		}
		
		/**
		 * Checks if only allowed characters are used.
		 *
		 * @param String $sPhone Phone number
		 *
		 * @uses \iTop_Helper_Phone::$allowedCharsRegex
		 *
		 *
		 * @return Boolean
		 */
		public static function OnlyContainsAllowedCharacters( $sPhone ) {
			// Only keep listed characters
			return ($sPhone === preg_replace('/[^'.self::$allowedCharsRegex.']/', '', $sPhone));
		}
	
		/**
 		 * Returns digits only.
 		 *
 		 * @param String $sPhone Phone number
 		 *
 		 * @return String Digits only of provided string (phone number)
 		 */
		public static function ReturnDigits( $sPhone ) {
			// Only keep digits
			return preg_replace('/\D/', '', $sPhone);		
		
		}
		
		/**
		 * Returns significant digits only (meaning: no leading zero and no national number if Belgian phone number)
		 *
		 * @param String $sPhone Phone number
		 *
		 * @return String Digits only of provided string (phone number)
		 *
		 * @details Significant details: the ones which make up the zone number (or mobile prefix), without leading zero and without (Belgian) country code
		 */
		public static function ReturnDigitsWithoutLocalDigits_BE( $sPhone ) {
		
			$sPhone = self::ReturnDigits($sPhone);
			
			// Adapted to Belgian situation
			if( substr($sPhone, 0, 2) == '32' ) {
				
				// 32 47x xx xx xx
				// 32 51 xx xx xx
				return substr($sPhone, 2);
			
			}
			elseif( substr($sPhone, 0, 1) == '0' ) {
					
				// Remove leading zero
				return substr($sPhone, 1);
				
			}
			
		}
		
	}
	
	