<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-08-05 19:34:49
 *
 * Definition of CMDBChangeHelper
 */

namespace jb_itop_extensions\components;

/**
 * Class CMDBChangeHelper
 */
abstract class CMDBChangeHelper {

	/**
	 * Deletes change operations for a CMDBChange.
	 *
	 * @param \CMDBChange $oChange CMDBChange for which CMDBChangeOp objects (change operation details) will be deleted.
	 *
	 * @return void
	 */
	public static function DeleteChangeOperations(\CMDBChange $oChange) {
		
		$aCachedChangeOpClasses = \MetaModel::EnumChildClasses('CMDBChangeOp');
		
		// Re-query to delete final classes (so all object details, also the data stored in parent class CMDBChangeOp)
		foreach($aCachedChangeOpClasses as $sFinalClass) {
			
			// Not change_id
			$oFilter_ChangeOps = \DBSearch::FromOQL('SELECT '.$sFinalClass.' WHERE change = '.$oChange->GetKey());
			$oSet_ChangeOps = new \CMDBObjectSet($oFilter_ChangeOps);
			
			while($oChangeOp = $oSet_ChangeOps->Fetch()) {
				$oChangeOp->DBDelete();
			}
		}
					
	}


	/**
	 * Deletes CMDBChange.
	 *
	 * @param \CMDBChange $oChange CMDBChange which will be deleted.
	 *
	 * @return void
	 */
	public static function DeleteChange(\CMDBChange $oChange) {
		
		// 1) Delete all CMDBChangeOp (Change Operations)
		DeleteChangeOperations($oChange);
		
		// 2) Delete actual Change
		$oChange->DBDelete();
					
	}
	
}
