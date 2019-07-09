<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * iTop front-end ajax handler for search form. Based on pages/ajax.searchform.php
 *
 */


use Combodo\iTop\Application\Search\AjaxSearchException;
use Combodo\iTop\Application\Search\CriterionParser;

require_once('../../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/user.preferences.class.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/sources/application/search/ajaxsearchexception.class.inc.php');
require_once(APPROOT.'/sources/application/search/criterionparser.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');

try
{
	if (LoginWebPage::EXIT_CODE_OK != LoginWebPage::DoLoginEx(null /* any portal */, false, LoginWebPage::EXIT_RETURN))
	{
		throw new SecurityException('You must be logged in');
	}

	$sParams = utils::ReadParam('params', '', false, 'raw_data');
	if (!$sParams)
	{
		throw new AjaxSearchException("Invalid query (empty filter)", 400);
	}

	$oPage = new ajax_page('');
	$oPage->no_cache();
	$oPage->SetContentType('application/json');

	$aParams = json_decode($sParams, true);
	
	$sHiddenCriteria = '';
	$oFilter = CriterionParser::Parse($aParams['base_oql'], $aParams['criterion'], $sHiddenCriteria);
	$sQuery = $oFilter->ToOQL();
	
	//IssueLog::Info('Search OQL: "'.$oFilter->ToOQL().'"');
	// Thanks to Guillaume who informed me it's okay to use this and that permission matrix of logged-on iTop user is respected
	$oObjectSet = new DBObjectSet(DBObjectSearch::FromOQL($sQuery));

	// Convert
	$aResults = $oObjectSet->ToArrayOfValues();
	
	// Change and drop 'class.' prefix for fields (limits length of JSON output significantly)
	// @todo Implement 'output_fields' or something similar in name to iTop REST/JSON
	foreach($aResults as &$aResult) {
		foreach($aResult as $sAttribute => $sAttributeValue) {
			$aResult[preg_replace('/^'.$aParams['class'].'\./', '', $sAttribute)] = $sAttributeValue;
			unset($aResult[$sAttribute]);
		}
	}
	
	// Output
	$oPage->add(json_encode($aResults));
	$oPage->output();

} catch (AjaxSearchException $e)
{
	http_response_code($e->getCode());
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (SecurityException $e)
{
	http_response_code(403);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (MySQLException $e)
{
	http_response_code(500);
	// Sanytize error:
	$sMsg = $e->GetMessage();
	$sMsg = preg_replace("@^.* mysql_error = @", '', $sMsg);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>'.htmlentities($sMsg, ENT_QUOTES, 'utf-8').'</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (Exception $e)
{
	http_response_code(500);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
}