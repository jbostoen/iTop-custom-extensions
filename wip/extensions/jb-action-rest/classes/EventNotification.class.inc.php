<?php

/**
 * Definition of EventNotificationRest
 *
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 */

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
