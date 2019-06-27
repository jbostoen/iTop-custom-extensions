<?php

	/**
	 * A REST notification
	 *
	 * @package     iTopORM
	 */
	class ActionRest extends ActionNotification
	{
		public static function Init()
		{
			$aParams = array
			(
				"category" => "grant_by_profile,core/cmdb,application",
				"key_type" => "autoincrement",
				"name_attcode" => "name",
				"state_attcode" => "",
				"reconc_keys" => array('name'),
				"db_table" => "priv_action_rest",
				"db_key_field" => "id",
				"db_finalclass_field" => "",
				"display_template" => "",
			);
			MetaModel::Init_Params($aParams);
			MetaModel::Init_InheritAttributes();

			MetaModel::Init_AddAttribute(new AttributeURL("test_url", array("allowed_values"=>null, "sql"=>"test_url", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array(), "target" => "_blank")));
			MetaModel::Init_AddAttribute(new AttributeURL("production_url", array("allowed_values"=>null, "sql"=>"production_url", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array(), "target" => "_blank")));
			
			MetaModel::Init_AddAttribute(new AttributeEnum("log_result", array("allowed_values"=>new ValueSetEnum("http_code,http_body"), "sql"=>"log_result", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array(), "target" => "_blank")));

			// Display lists
			MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'test_url', 'production_url', 'trigger_list')); // Attributes to be displayed for the complete details
			
			MetaModel::Init_SetZListItems('list', array('name', 'status', 'test_url', 'production_url')); // Attributes to be displayed for a list
			// Search criteria
			MetaModel::Init_SetZListItems('standard_search', array('name','description', 'status')); // Criteria of the std search form
			MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
		}


		
		// Errors management : not that simple because we need that function to be
		// executed in the background, while making sure that any issue would be reported clearly
		// protected $m_aErrors; //array of strings explaining the issue

		

		/**
		 * @param \Trigger $oTrigger
		 * @param array $aContextArgs
		 *
		 * @throws \CoreException
		 * @throws \CoreUnexpectedValue
		 * @throws \CoreWarning
		 */
		public function DoExecute($oTrigger, $aContextArgs)
		{
			if (MetaModel::IsLogEnabledNotification())
			{
				$oLog = new EventNotificationRest();
				if ($this->IsBeingTested())
				{
					$oLog->Set('message', 'Test - REST Url called');
					$oLog->Set('url', $this->Get('test_url'));
				}
				else
				{
					$oLog->Set('message', 'Production - REST Url called');
					$oLog->Set('url', $this->Get('production_url'));
				}
				$oLog->Set('userinfo', UserRights::GetUser());
				$oLog->Set('trigger_id', $oTrigger->GetKey());
				$oLog->Set('action_id', $this->GetKey());
				$oLog->Set('object_id', $aContextArgs['this->object()']->GetKey());
				// Must be inserted now so that it gets a valid id that will make the link
				// between an eventual asynchronous task (queued) and the log
				$oLog->DBInsertNoReload();
			}
			else
			{
				$oLog = null;
			}

			try
			{
				$sRes = $this->_DoExecute($oTrigger, $aContextArgs, $oLog);

				if ($this->IsBeingTested())
				{
					$sPrefix = 'TEST - ';
				}
				else
				{
					$sPrefix = '';
				}

				if ($oLog)
				{
					$oLog->Set('message', $sPrefix . $sRes);
					$oLog->DBUpdate();
				}

			}
			catch (Exception $e)
			{
				if ($oLog)
				{
					$oLog->Set('message', 'Error: '.$e->getMessage());

					try
					{
						$oLog->DBUpdate();
					}
					catch (Exception $eSecondTryUpdate)
					{
						IssueLog::Error('Failed to process REST call ' . $oLog->GetKey() ." - reason: ".$e->getMessage()."\nTrace:\n".$e->getTraceAsString());

						$oLog->Set('message', 'Error: more details in the log for email "'.$oLog->GetKey().'"');
						$oLog->DBUpdate();
					}
				}
			}

		}

		/**
		 * @param \Trigger $oTrigger
		 * @param array $aContextArgs
		 * @param \EventNotification $oLog
		 *
		 * @return string
		 * @throws \CoreException
		 */
		protected function _DoExecute($oTrigger, $aContextArgs, &$oLog)
		{
			
			switch($this->Get('status')) {
				case 'enabled':
					$sUrl = $this->Get('production_url');
					break;
					
				case 'test':
					$sUrl = $this->Get('test_url');			
					break;
					
				case 'disabled':
					return get_class($this). ' ' . $this->GetKey() . ' is disabled';
					break;
			}
			
			// Object does NOT always contain all properties (example: Trigger 'On Create', 'On Update')
			$oObject = $aContextArgs['this->object()'];
			
			// Use iTop's built-in REST utils and re-use it to obtain all data
			// Autoload fails?
			require_once( __DIR__ . '/../../core/restservices.class.inc.php');
			
			$aParams = json_decode(utils::ReadParam('json_data', null, false, 'raw_data'));			
			$aShowFields = RestUtils::GetFieldList(get_class($oObject), $aParams, 'output_fields');
			$bExtendedOutput = (RestUtils::GetOptionalParam($aParams, 'output_fields', '*') == '*+');
			
			$oResult = new RestResultWithObjects();
			$oResult->AddObject( RestResult::OK, '', $oObject, $aShowFields, $bExtendedOutput );
			
			// Add some more details to make it easier for third-party applications to know which trigger was used.
			// This way, it's possible for one ActionRest to be linked to multiple triggers
			$oResult->trigger_id = $oTrigger->GetKey();
			$oResult->trigger_friendly_name = $oTrigger->Get('friendlyname');
		
			// Currently this version simply posts the data.
			// Future version might post JSON data instead.
			$aPostData = (Array)$oResult;
			
			$oCurl = curl_init();
			curl_setopt($oCurl, CURLOPT_URL, $sUrl);
			curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($oCurl, CURLOPT_HEADER, false);
			curl_setopt($oCurl, CURLOPT_POST, count($aPostData));
			curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($aPostData));

			$sOutput = curl_exec($oCurl);
			$sHttpCode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
			
			curl_close($oCurl);

			switch( $this->Get('log_result') ) {
				case 'http_code': 
					return 'HTTP Code: ' . $sHttpCode;
				case 'http_body':
					return $sOutput;
				default:
					// Should not happen:
					return 'OK';
			}
		}
	}

	class EventNotificationRest extends EventNotification
	{
		public static function Init()
		{
			$aParams = array
			(
				"category" => "core/cmdb,view_in_gui",
				"key_type" => "autoincrement",
				"name_attcode" => "",
				"state_attcode" => "",
				"reconc_keys" => array(),
				"db_table" => "priv_event_notification_rest",
				"db_key_field" => "id",
				"db_finalclass_field" => "",
				"display_template" => "",
				"order_by_default" => array('date' => false)
			);
			MetaModel::Init_Params($aParams);
			MetaModel::Init_InheritAttributes();
			MetaModel::Init_AddAttribute(new AttributeUrl("url", array("allowed_values"=>null, "sql"=>"url", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array(), "target" => "_blank")));
			
			// Display lists
			MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'url')); // Attributes to be displayed for the complete details
			MetaModel::Init_SetZListItems('list', array('date', 'url')); // Attributes to be displayed for a list

			// Search criteria. Copied from EventNotificationEmail, but 'name' is not defined.
			// MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
			// MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
		}

	}
