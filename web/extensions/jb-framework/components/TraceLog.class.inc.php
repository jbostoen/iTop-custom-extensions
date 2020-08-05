<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-08-05 19:34:49
 *
 * Definition of TraceLog
 */

namespace jb_itop_extensions\components;

/**
 * Class TraceLog
 */
abstract class TraceLog {
	
	/**
	 * Prints a $sMessage in the CRON output.
	 *
	 * @param \String $sMessage Message to put in the trace log (CRON output)
	 * @param \String $sType Type of message. Possible values: info, error
	 * @param \String $sWantedTraceLevel Wanted trace level. Possible values: none, info, error
	 */
	public static function Trace($sMessage, $sType = 'info', $sWantedTraceLevel = 'info') {
		
		switch($sWantedTraceLevel) {
			
			case 'info':
				if(in_array($sType, ['info', 'error']) == true) {
					echo $sMessage. PHP_EOL;
				}
				break;
				
			case 'error':
				if($sType == 'error') {
					echo $sMessage. PHP_EOL;
				}
				break;
			
			case 'none':
				break;
				
			default:
				echo 'Unexpected trace level: '.$this->sDebugLevel. PHP_EOL;
			
		}
	}
	
}
