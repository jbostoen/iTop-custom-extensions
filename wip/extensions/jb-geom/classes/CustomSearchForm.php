<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Class GeomSearchForm simply overwrites the default GetSearchForm() to allow for an alternate endpoint, which is unfortunately not an option in iTop 2.6.
 * It's based on \Combodo\iTop\Application\Search\SearchForm\GetSearchForm() (iTop 2.6) and should be updated accordingly.
 */
 
 class CustomSearchForm extends \Combodo\iTop\Application\Search\SearchForm {
 
 	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 *
	 * @details Nothing changed (so perhaps overkill) - except:
	 * - the endpoint points to our custom JSON handler
	 * - an $aExtraParams parameter has been added: 'callback' (takes a JavaScript function defined in a string)
	 */
	public function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$sClassName = $oSet->GetFilter()->GetClass();
		$aListParams = array();

		foreach($aExtraParams as $key => $value)
		{
			$aListParams[$key] = $value;
		}

		// Simple search form
		if (isset($aExtraParams['currentId']))
		{
			$sSearchFormId = $aExtraParams['currentId'];
		}
		else
		{
			$iSearchFormId = $oPage->GetUniqueId();
			$sSearchFormId = 'SimpleSearchForm'.$iSearchFormId;
			$sHtml .= "<div id=\"ds_$sSearchFormId\" class=\"mini_tab{$iSearchFormId}\">\n";
			$aListParams['currentId'] = "$iSearchFormId";
		}
		// Check if the current class has some sub-classes
		if (isset($aExtraParams['baseClass']))
		{
			$sRootClass = $aExtraParams['baseClass'];
		}
		else
		{
			$sRootClass = $sClassName;
		}
		//should the search be opened on load?
		if (isset($aExtraParams['open']))
		{
			$bOpen = $aExtraParams['open'];
		}
		else
		{
			$bOpen = true;
		}

		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$aListParams['json'] = json_decode($sJson, true);
		}

		if (!isset($aExtraParams['result_list_outer_selector']))
		{
			if (isset($aExtraParams['table_id']))
			{
				$aExtraParams['result_list_outer_selector'] = $aExtraParams['table_id'];
			}
			else
			{
				$aExtraParams['result_list_outer_selector'] = "search_form_result_{$sSearchFormId}";
			}
		}

		if (isset($aExtraParams['search_header_force_dropdown']))
		{
			$sClassesCombo = $aExtraParams['search_header_force_dropdown'];
		}
		else
		{
			$aSubClasses = MetaModel::GetSubclasses($sRootClass);
			if (count($aSubClasses) > 0)
			{
				$aOptions = array();
				$aOptions[MetaModel::GetName($sRootClass)] = "<option value=\"$sRootClass\">".MetaModel::GetName($sRootClass)."</options>\n";
				foreach($aSubClasses as $sSubclassName)
				{
					$aOptions[MetaModel::GetName($sSubclassName)] = "<option value=\"$sSubclassName\">".MetaModel::GetName($sSubclassName)."</options>\n";
				}
				$aOptions[MetaModel::GetName($sClassName)] = "<option selected value=\"$sClassName\">".MetaModel::GetName($sClassName)."</options>\n";
				ksort($aOptions);
				$sContext = $oAppContext->GetForLink();
				$sJsonExtraParams = htmlentities(json_encode($aListParams), ENT_QUOTES);
				$sOuterSelector = $aExtraParams['result_list_outer_selector'];
				$sClassesCombo = "<select name=\"class\" onChange=\"ReloadSearchForm('$sSearchFormId', this.value, '$sRootClass', '$sContext', '$sOuterSelector', $sJsonExtraParams)\">\n".implode('',
						$aOptions)."</select>\n";
			}
			else
			{
				$sClassesCombo = MetaModel::GetName($sClassName);
			}
		}
		
		// Custom callback on success
		if(isset($aExtraParams['callback']) == true) {
			$sCallback = $aExtraParams['callback'];
		}
		else {
			$sCallback = 
<<<EOF
			function(data) {
				console.log(data);
			}
EOF;
		}

		$bAutoSubmit = true;
		$mSubmitParam = utils::GetConfig()->Get('search_manual_submit');
		if ($mSubmitParam !== false)
		{
			$bAutoSubmit = false;
		}
		else
		{
			$mSubmitParam = utils::GetConfig()->Get('high_cardinality_classes');
			if (is_array($mSubmitParam))
			{
				if (in_array($sClassName, $mSubmitParam))
				{
					$bAutoSubmit = false;
				}
			}
		}

		$sAction = (isset($aExtraParams['action'])) ? $aExtraParams['action'] : utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		$sStyle = ($bOpen == 'true') ? '' : 'closed';
		$sStyle .= ($bAutoSubmit === true) ? '' : ' no_auto_submit';
		$sHtml .= "<form id=\"fs_{$sSearchFormId}\" action=\"{$sAction}\" class=\"{$sStyle}\">\n"; // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
		$sHtml .= "<h2 class=\"sf_title\"><span class=\"sft_long\">" . Dict::Format('UI:SearchFor_Class_Objects', $sClassesCombo) . "</span><span class=\"sft_short\">" . Dict::S('UI:SearchToggle') . "</span>";
		$sHtml .= "<a class=\"sft_toggler fa fa-caret-down pull-right\" href=\"#\" title=\"" . Dict::S('UI:Search:Toggle') . "\"></a>";
		$sHtml .= "<span class=\"sft_hint pull-right\">" . Dict::S('UI:Search:AutoSubmit:DisabledHint') . "</span>";
		$sHtml .= "</h2>\n";
		$sHtml .= "<div id=\"fs_{$sSearchFormId}_message\" class=\"sf_message header_message\"></div>\n";
		$sHtml .= "<div id=\"fs_{$sSearchFormId}_criterion_outer\">\n</div>\n";
		$sHtml .= "</form>\n";

		if (isset($aExtraParams['query_params']))
		{
			$aArgs = $aExtraParams['query_params'];
		}
		else
		{
			$aArgs = array();
		}

		$bIsRemovable = true;
		if (isset($aExtraParams['selection_type']) && ($aExtraParams['selection_type'] == 'single'))
		{
			// Mark all criterion as read-only and non-removable for external keys only
			$bIsRemovable = false;
		}

		$bUseApplicationContext = true;
		if (isset($aExtraParams['selection_mode']) && ($aExtraParams['selection_mode']))
		{
			// Don't use application context for selections
			$bUseApplicationContext = false;
		}

		$aFields = $this->GetFields($oSet);
		$oSearch = $oSet->GetFilter();
		$aCriterion = $this->GetCriterion($oSearch, $aFields, $aArgs, $bIsRemovable, $bUseApplicationContext);
		$aClasses = $oSearch->GetSelectedClasses();
		$sClassAlias = '';
		foreach($aClasses as $sAlias => $sClass)
		{
			$sClassAlias = $sAlias;
		}

		$oBaseSearch = $oSearch->DeepClone();
		if ($oSearch instanceof DBObjectSearch)
		{
			$oBaseSearch->ResetCondition();
		}
		$sBaseOQL = str_replace(' WHERE 1', '', $oBaseSearch->ToOQL());

		if (!isset($aExtraParams['table_inner_id']))
		{
			$aListParams['table_inner_id'] = "table_inner_id_{$sSearchFormId}";
		}

		if (isset($aExtraParams['result_list_outer_selector']))
		{
			$sDataConfigListSelector = $aExtraParams['result_list_outer_selector'];
		}
		else
		{
			$sDataConfigListSelector = $aExtraParams['table_inner_id'];
		}

		$sDebug = utils::ReadParam('debug', 'false', false, 'parameter');
		if ($sDebug == 'true')
		{
			$aListParams['debug'] = 'true';
		}

		$aDaysMin = array(Dict::S('DayOfWeek-Sunday-Min'), Dict::S('DayOfWeek-Monday-Min'), Dict::S('DayOfWeek-Tuesday-Min'), Dict::S('DayOfWeek-Wednesday-Min'),
			Dict::S('DayOfWeek-Thursday-Min'), Dict::S('DayOfWeek-Friday-Min'), Dict::S('DayOfWeek-Saturday-Min'));
		$aMonthsShort = array(Dict::S('Month-01-Short'), Dict::S('Month-02-Short'), Dict::S('Month-03-Short'), Dict::S('Month-04-Short'), Dict::S('Month-05-Short'), Dict::S('Month-06-Short'),
			Dict::S('Month-07-Short'), Dict::S('Month-08-Short'), Dict::S('Month-09-Short'), Dict::S('Month-10-Short'), Dict::S('Month-11-Short'), Dict::S('Month-12-Short'));

		$sDateTimeFormat = \AttributeDateTime::GetFormat()->ToDatePicker();
		$iDateTimeSeparatorPos = strpos($sDateTimeFormat, ' ');
		$sDateFormat = substr($sDateTimeFormat, 0, $iDateTimeSeparatorPos);
		$sTimeFormat = substr($sDateTimeFormat, $iDateTimeSeparatorPos + 1);

		// Changed 'endpoint', added 'onSubmitSuccess'
		$aSearchParams = array(
			'criterion_outer_selector' => "#fs_{$sSearchFormId}_criterion_outer",
			'result_list_outer_selector' => "#{$aExtraParams['result_list_outer_selector']}",
			'data_config_list_selector' => "#{$sDataConfigListSelector}",
			// 'endpoint' => utils::GetAbsoluteUrlAppRoot().'pages/ajax.searchform.php',
			'endpoint' => utils::GetAbsoluteUrlModulesRoot().basename(dirname(dirname(__FILE__))).'/ajax/customsearchform.php',
			'init_opened' => $bOpen,
			'auto_submit' => $bAutoSubmit,
			'list_params' => $aListParams,
			'search' => array(
				'has_hidden_criteria' => (array_key_exists('hidden_criteria', $aListParams) && !empty($aListParams['hidden_criteria'])),
				'fields' => $aFields,
				'criterion' => $aCriterion,
				'class_name' => $sClassName,
				'class_alias' => $sClassAlias,
				'base_oql' => $sBaseOQL,
			),
			'conf_parameters' => array(
				'min_autocomplete_chars' => MetaModel::GetConfig()->Get('min_autocomplete_chars'),
				'datepicker' => array(
					'dayNamesMin' => $aDaysMin,
					'monthNamesShort' => $aMonthsShort,
					'firstDay' => (int) Dict::S('Calendar-FirstDayOfWeek'),
					'dateFormat' => $sDateFormat,
					'timeFormat' => $sTimeFormat,
				),
			),
			'onSubmitSuccess' => $sCallback // Will be encoded as a string. json_encode() doesn't support JavaScript functions. Handled in front-end.
		);

		$oPage->add_ready_script('$("#fs_'.$sSearchFormId.'").custom_search_form_handler('.json_encode($aSearchParams).');');

		return $sHtml;
		
	}
	
}