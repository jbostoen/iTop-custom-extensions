<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-08-05 19:34:49
 *
 * Definition of DBObjectHelper
 *
 */

namespace jb_itop_extensions\components;

use \DBObject;
use \MetaModel;

/**
 * Class DBObjectHelper
 *
 * @details Used by geometry extension
 */
abstract class DBObjectHelper {

	/**
	 * Gets an array containing the object values
	 *
	 * @param \DBObject $oObject iTop object
	 *
	 * @return \Array Hash table of object.
	 */
	public function GetValuesAsArray(DBObject $oObject) {
		
		$aAttributeList = Metamodel::GetAttributesList(get_class($oObject));
		$aAttributeList[] = 'id';
		
		$aAttributeValues = [];
		foreach($aAttributeList as $sAttCode) {
			$aAttributeValues[$sAttCode] = $oObject->Get($sAttCode);
		}
		
		return $aAttributeValues;
		
	}
	
}
