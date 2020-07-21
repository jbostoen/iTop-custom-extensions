<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-07-21 19:29:11
 *
 * Definition of ApplicationHelper
 */

namespace jb_itop_extensions\components;

/**
 * Class ApplicationHelper
 */
abstract class ApplicationHelper {

	/**
	 * Checks if this is a specific iTop version.
	 * The more specific the string (needle), the more specific the match is performed.
	 * So it's possible to check for version "2", "2.7", "2.7.0", ...
	 *
	 * @param \String $sWantedVersion Examples: version "2", "2.7", "2.7.0", ...
	 *
	 * @return Boolean
	 */
	public static function IsVersion($sWantedVersion) {
		
		$sAppVersion = self::GetNumericVersion();
		
		if(preg_match('/^'.preg_quote($sWantedVersion).'.*$/', $sAppVersion)) {
			return true;
		}
		
		return false;
					
	}

	/**
	 * Checks if this is minimum a specific iTop version.
	 * The more specific the string (needle), the more specific the match is performed.
	 * So it's possible to check for version "2", "2.7", "2.7.0", ...
	 *
	 * @param \String $sWantedVersion Examples: version "2", "2.7", "2.7.0", ...
	 *
	 * @return Boolean
	 */
	public static function IsMinimumVersion($sWantedVersion) {
		
		$sAppVersion = self::GetNumericVersion();
		
		$aVersionNumbers_Wanted = explode('.', $sWantedVersion);
		$aVersionNumbers_App = explode('.', $sAppVersion);
		
		// Indexes will be the same from left to right.
		// Wanted version might be shorter, but it doesn't matter.
		foreach($aVersionNumbers_Wanted as $iIndex => $sVersionPart) {
			
			// Fails if version is smaller.
			// In case wanted version = 2.6 and app version = 2.6.2: still okay
			if($sVersionPart < $aVersionNumbers_App[$iIndex]) {
				return false;
			}
			
		}
		
		return true;
					
	}
	
	/**
	 * Returns iTop version - without any tag or something
	 *
	 * @return String
	 */	 
	public static function GetNumericVersion() {
		
		// For example, beta might have been labeled 2.7.0-dev
		return preg_replace('[^0-9\.]', '', ITOP_VERSION);
		
	}
		
	
}
