<?php



	// GetEditClass = 'List' = not displayed . Might be easiest option.
	// Alternatively: look at Password?

	// Should be mix of AttributeString and AttributePassword
	// Interesting: AttributePassword has a front-end property 'changed' to keep track of any changes.
	
	class AttributeRestrictedString extends AttributeString {
		
		

		public function IsPartOfFingerprint()
		{
			return false;
		} // Cannot reliably compare two encrypted passwords since the same password will be encrypted in diffferent manners depending on the random 'salt'
	
		// This is the part which matters
		public function GetEditValue($sValue, $oHostObj = null)
		{
			return str_repeat('*', strlen((string)$sValue));
		}
		
		public function GetEditClass()
		{
			// For cmdbabstract.class.inc.php
			return "List";
		}

		
		/**
		 * Override to display the value in the GUI
		 *
		 * @param string $sValue
		 * @param \DBObject $oHostObject
		 * @param bool $bLocalize
		 *
		 * @return string
		 */
		public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
		{
			return Str::pure2html(str_repeat('*', strlen((string)$sValue)));
		}

		/**
		 * Override to export the value in XML
		 *
		 * @param string $sValue
		 * @param \DBObject $oHostObject
		 * @param bool $bLocalize
		 *
		 * @return mixed
		 */
		public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
		{
			return Str::pure2xml(str_repeat('*', strlen((string)$sValue)));
		}

		/**
		 * Override to escape the value when read by DBObject::GetAsCSV()
		 *
		 * @param string $sValue
		 * @param string $sSeparator
		 * @param string $sTextQualifier
		 * @param \DBObject $oHostObject
		 * @param bool $bLocalize
		 * @param bool $bConvertToPlainText
		 *
		 * @return string
		 */
		 
		public function GetAsCSV(
			$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
			$bConvertToPlainText = false
		) {
			$sFrom = array("\r\n", $sTextQualifier);
			$sTo = array("\n", $sTextQualifier.$sTextQualifier);
			$sEscaped = str_replace($sFrom, $sTo, str_repeat('*', strlen((string)$sValue)));

			return $sTextQualifier.$sEscaped.$sTextQualifier;
		}

			
		/**
		 * For fields containing a potential markup, return the value without this markup
		 *
		 * @param string $sValue
		 * @param \DBObject $oHostObj
		 *
		 * @return string
		 */
		public function GetAsPlainText($sValue, $oHostObj = null)
		{
			return (string)$this->GetEditValue($sValue, $oHostObj);
		}
		
		
		public function GetValueLabel($sValue, $oHostObj = null)
		{
			// Don't display anything in "group by" reports
			return '*****';
		}
		

		public function GetFilterDefinitions()
		{
			// Note: due to this, you will get an error if a password is being declared as a search criteria (see ZLists)
			// not allowed to search on passwords!
			return array();
		}
		
		/*
		static public function GetFormFieldClass()
		{
			return '\\Combodo\\iTop\\Form\\Field\\EmailField';
		}

		*/
	}


	class CustomLimitedString implements iOnClassInitialization {
		
		public function OnAfterClassInitialization($sClass) {
			 
			if($sClass == 'Person') {
				
				MetaModel::Init_AddAttribute(new AttributeRestrictedString("restricted_info", array("allowed_values"=>null, "sql"=>"restricted_info", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())), 'Person');
				
				// Display lists
				
				$aZList = MetaModel::GetZListItems('Person', 'details');
				$aZList['col:col1']['fieldset:Person:info'][] = 'restricted_info';
				
				MetaModel::Init_SetZListItems('details', $aZList, 'Person'); // Attributes to be displayed for the complete details
				
				// MetaModel::Init_SetZListItems('list', array('description', )); // Attributes to be displayed for a list
				
				// Search criteria
				// MetaModel::Init_SetZListItems('standard_search', array('description', 'definition_set')); // Criteria of the std search form
				// MetaModel::Init_SetZListItems('default_search', array('name', 'description')); // Criteria of the default search form
				
				
			}
		
			
		}
		
	}
	
