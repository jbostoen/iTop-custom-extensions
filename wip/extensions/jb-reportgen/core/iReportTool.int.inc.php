<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 17:01:06
 *
 * Definition of iReportTool
 */

namespace jb_itop_extensions\report_generator\tools;

/**
 * Interface iReportTool.
 * Provides methods to enrich data or perform other actions.
 */
interface iReportTool {
	
	/**
	 * Whether or not this extension is applicable
	 *
	 * @return Boolean
	 *
	 */
	public static function IsApplicable();
	
	/**
	 * Rendering hook
	 *
	 * @var \Array $aReportData Twig data
	 * @var \CMDBObjectSet[] $oSet_Objects CMDBObjectSet of iTop objects which are being processed
	 *
	 */
	public static function EnrichData(&$aReportData, $oSet_Objects);
	
	/**
	 * Action hook
	 *
	 * @var \Array $aReportData Report data
	 * @var \CMDBObjectSet[] $oSet_Objects CMDBObjectSet of iTop objects which are being processed
	 *
	 */
	public static function DoExec($aReportData, $oSet_Objects);

}
