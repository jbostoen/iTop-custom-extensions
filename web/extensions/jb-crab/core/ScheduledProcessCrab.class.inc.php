<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-07-21 19:29:11
 *
 * Definition of ScheduledProcessCrabSync
 */

namespace jb_itop_extensions\crab;

use \CoreUnexpectedValue;
use \iScheduledProcess;
use \MetaModel;

/**
 * Class ScheduledProcessCrabSync
 */
class ScheduledProcessCrabSync extends \jb_itop_extensions\components\ScheduledProcess implements \iScheduledProcess
{
	const MODULE_CODE = 'jb-crab';
	
	/**
	 * @inheritdoc
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function Process($iTimeLimit)
	{
		// Increase limits, temporarily.
		$iTimeLimit_PHP = ini_get('max_execution_time');
		$iMemoryLimit_PHP = ini_get('memory_limit');
		set_time_limit(0);
		ini_set('memory_limit', '512M');
		
		$this->Trace('Processing Crab...');
		$this->Trace('Disabled max_execution_time and set memory_limit to 512M');
		
		// Ignore time limit, it should run nightly and it will take some time.
		try {
			
			$oCrab = new \jb_itop_extensions\crab\CrabImportHandler;
			$oCrab->DownloadShapeFile();
			$oCrab->ConvertShapeFileToGeoJSON();
			$oCrab->ImportFromGeoJSON();			
			
		}
		catch(Exception $e) {
			$this->Trace($e->GetMessage());
		}
		finally {
			
			// Restore limits
			ini_set('max_execution_time', $iTimeLimit_PHP);
			ini_set('memory_limit', $iMemoryLimit_PHP);
			
		}
		
	}

}
