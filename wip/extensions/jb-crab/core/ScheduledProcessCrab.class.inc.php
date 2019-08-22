<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-22 12:49:39
 *
 * Definition of SheduledProcessCrabSync
 */

namespace jb_crab;

use \CoreUnexpectedValue;
use \iScheduledProcess;
use \MetaModel;

/**
 * Class SheduledProcessCrabSync
 */
class SheduledProcessCrabSync implements iScheduledProcess
{
	const MODULE_CODE = 'jb-crab';

	const KEY_MODULE_SETTING_ENABLED = 'enabled';
	const KEY_MODULE_SETTING_DEBUG = 'debug';
	const KEY_MODULE_SETTING_WEEKDAYS = 'week_days';
	const KEY_MODULE_SETTING_TIME = 'time';

	const DEFAULT_MODULE_SETTING_ENABLED = true;
	const DEFAULT_MODULE_SETTING_DEBUG = false;
	const DEFAULT_MODULE_SETTING_WEEKDAYS = 'monday, tuesday, wednesday, thursday, friday, saturday, sunday';
	const DEFAULT_MODULE_SETTING_TIME = '03:00';

	protected $bDebug;

	/**
	 * Constructor.
	 */
	function __construct()
	{
		echo 'Constructed, set bDebug';
		$this->$bDebug = (bool)MetaModel::GetModuleSetting(static::MODULE_CODE, static::KEY_MODULE_SETTING_DEBUG, static::DEFAULT_MODULE_SETTING_DEBUG);
	}

	/**
	 * Gives the exact time at which the process must be run next time
	 *
	 * @return \DateTime
	 * @throws \CoreUnexpectedValue
	 */
	public function GetNextOccurrence()
	{
		$bEnabled = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::KEY_MODULE_SETTING_ENABLED, static::DEFAULT_MODULE_SETTING_ENABLED);
		if (!$bEnabled)
		{
			$oRet = new DateTime('3000-01-01');
		}
		else
		{
			$sRunTime = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::KEY_MODULE_SETTING_TIME, static::DEFAULT_MODULE_SETTING_TIME);
			if (!preg_match('/^([01]?\d|2[0-3]):([0-5]?\d)(?::([0-5]?\d))?$/', $sRunTime, $aMatches))
			{
				throw new \CoreUnexpectedValue(static::MODULE_CODE.": wrong format for setting 'time' (found '$sRunTime')");
			}
			$iHours = (int)$aMatches[1];
			$iMinutes = (int)$aMatches[2];
//			$iSeconds = (array_key_exists(3, $aMatches)) ? $aMatches[3] : 0;
			$iSeconds = 59; // workaround : if below then will run multiple times till the minute is over
			// eg if set to 12:30:12 cron will launch the task at 12:30:12, and on each loop iteration until 12:31:00

			// 1st - Interpret the list of days as ordered numbers (monday = 1)
			//
			$aDays = $this->InterpretWeekDays();

			// 2nd - Find the next active week day
			//
			$oNow = new \DateTime();
			$iNextPos = false;
			for ($iDay = $oNow->format('N'); $iDay <= 7; $iDay++)
			{
				$iNextPos = array_search($iDay, $aDays);
				if ($iNextPos !== false)
				{
					if (($iDay > $oNow->format('N')) || ($oNow->format('H:i') < $sRunTime))
					{
						break;
					}
					$iNextPos = false; // necessary on sundays
				}
			}

			// 3rd - Compute the result
			//
			if ($iNextPos === false)
			{
				// Jump to the first day within the next week
				$iFirstDayOfWeek = $aDays[0];
				$iDayMove = $oNow->format('N') - $iFirstDayOfWeek;
				$oRet = clone $oNow;
				$oRet->modify('-'.$iDayMove.' days');
				$oRet->modify('+1 weeks');
			}
			else
			{
				$iNextDayOfWeek = $aDays[$iNextPos];
				$iMove = $iNextDayOfWeek - $oNow->format('N');
				$oRet = clone $oNow;
				$oRet->modify('+'.$iMove.' days');
			}
			$oRet->setTime($iHours, $iMinutes, $iSeconds);
		}

		return $oRet;
	}

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
			
			
			CrabImportHandler::DownloadShapeFile();
			$sFileName_GeoJSON = CrabImportHandler::ConvertShapeFileToGeoJSON();
			CrabImportHandler::ImportFromGeoJSON($sFileName_GeoJSON);			
			
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

	/**
	 * Interpret current setting for the week days
	 *
	 * Note: This comes from itop-backup scheduled task.
	 *
	 * @returns array of int (monday = 1)
	 * @throws \CoreUnexpectedValue
	 */
	public function InterpretWeekDays()
	{
		static $aWEEKDAYTON = array(
			'monday' => 1,
			'tuesday' => 2,
			'wednesday' => 3,
			'thursday' => 4,
			'friday' => 5,
			'saturday' => 6,
			'sunday' => 7,
		);
		$aDays = array();
		$sWeekDays = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::KEY_MODULE_SETTING_WEEKDAYS, static::DEFAULT_MODULE_SETTING_WEEKDAYS);
		if ($sWeekDays != '')
		{
			$aWeekDaysRaw = explode(',', $sWeekDays);
			foreach ($aWeekDaysRaw as $sWeekDay)
			{
				$sWeekDay = strtolower(trim($sWeekDay));
				if (array_key_exists($sWeekDay, $aWEEKDAYTON))
				{
					$aDays[] = $aWEEKDAYTON[$sWeekDay];
				}
				else
				{
					throw new CoreUnexpectedValue(static::MODULE_CODE.": wrong format for setting 'week_days' (found '$sWeekDay')");
				}
			}
		}
		if (count($aDays) == 0)
		{
			throw new CoreUnexpectedValue(static::MODULE_CODE.": missing setting 'week_days'");
		}
		$aDays = array_unique($aDays);
		sort($aDays);

		return $aDays;
	}

	/**
	 * Prints a $sMessage in the CRON output.
	 *
	 * @param string $sMessage
	 */
	protected function Trace($sMessage)
	{
		// In the CRON output
		if ($this->bDebug)
		{
			echo $sMessage.'\n';
		}
	}
}
