<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-01-23 11:41:53
 *
 * Definition of iReportGeneratorExtension
 */

namespace jb_itop_extensions\report_generator;

/**
 * Interface iReportGeneratorExtension.
 * Provides methods to enrich data or perform other actions.
 */
interface iReportGeneratorExtension {
	
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
	 * @var \Array $aTwigData Twig data
	 * @var \Array $oTwigEnv Twig environment
	 * @var \CMDBObjectSet[] $oSet_Objects CMDBObjectSet of iTop objects which are being processed
	 *
	 */
	public static function EnrichData(&$aTwigData, $oTwigEnv, $oSet_Objects);
	
	/**
	 * Action hook
	 *
	 * @var \Array $aTwigData Twig data
	 * @var \Array $oTwigEnv Twig environment
	 * @var \String $sReportFile Report file
	 *
	 */
	public static function DoExec($aTwigData, $oTwigEnv, $sReportFile);

}
