<?php


/**
 * Map a text column (size > ?) to an attribute
 * @todo use GEOMETRY instead
 *
 * @todo Finish PHP doc. Copied now from attributedef.class.inc.php but incomplete.
 *
 * @package     iTopORM
 */
class AttributeGeometry extends AttributeString
{
	public function GetEditClass()
	{
		return $this->GetFormat();
	}

	// To be overriden, used in GetSQLColumns
	protected function GetSQLCol($bFullSpec = false)
	{
		// return 'TEXT'.CMDBSource::GetSqlStringColumnDefinition();
		
		// @todo See if we can implement GEOMETRY instead. Without specified SRID. Difficulty: can we specify ST_AsText() somewhere?
		return 'TEXT';
	}

	/**
	 * @param bool $bFullSpec
	 *
	 * @return array column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)
	 * @see \CMDBSource::GetFieldSpec()
	 */
	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->Get('sql')] = $this->GetSQLCol($bFullSpec);
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns[$this->Get('sql').'_format'] = "ENUM('WKT','GeoJSON', 'text')".CMDBSource::GetSqlStringColumnDefinition();
			if ($bFullSpec)
			{
				$aColumns[$this->Get('sql').'_format'] .= " DEFAULT 'WKT'"; // default 'WKT' is for migrating old records
			}
		}

		return $aColumns;
	}

	/**
	 * @param string $sPrefix
	 *
	 * @return array suffix/expression pairs (1 in most of the cases), for READING (Select)
	 */
	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->Get('sql');
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix;
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns['_format'] = $sPrefix.'_format';
		}

		return $aColumns;
	}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535;
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
		$aStyles = array();
		if ($this->GetWidth() != '')
		{
			$aStyles[] = 'width:'.$this->GetWidth();
		}
		if ($this->GetHeight() != '')
		{
			$aStyles[] = 'height:'.$this->GetHeight();
		}
		$sStyle = '';
		if (count($aStyles) > 0)
		{
			$sStyle = 'style="'.implode(';', $aStyles).'"';
		}

	
		$sValue = parent::GetAsHTML($sValue, $oHostObject, $bLocalize);

		return "<div $sStyle>".$sValue.'</div>';

	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return $sValue;
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
		return parent::GetAsPlainText($sValue, $oHostObj);
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $sProposedValue
	 * @param $oHostObj
	 *
	 * @return mixed
	 */
	public function MakeRealValue($sProposedValue, $oHostObj)
	{
		$sValue = $sProposedValue;
		return $sValue;
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
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml($value);
	}

	public function GetWidth()
	{
		// @todo Derive from config (default or class based)
		return $this->GetOptional('width', '');
	}

	public function GetHeight()
	{
		// @todo Derive from config (default or class based)
		return $this->GetOptional('height', '');
	}

	static public function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\GeometryField';
	}

	/**
	 * @param \DBObject $oObject
	 * @param \Combodo\iTop\Form\Field\GeometryField $oFormField
	 *
	 * @return \Combodo\iTop\Form\Field\GeometryField
	 * @throws \CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			/** @var \Combodo\iTop\Form\Field\GeometryField $oFormField */
			$oFormField = new $sFormFieldClass($this->GetCode(), null, $oObject);
			$oFormField->SetFormat($this->GetFormat());
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	/**
	 * The actual formatting of the field: WKT (default), GeoJSON (not implemented), text (not implemented)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'WKT');
	}

	/**
	 * Read the value from the row returned by the SQL query and transforms it to the appropriate
	 * internal format (text)
	 *
	 * @see AttributeDBFieldVoid::FromSQLToValue()
	 *
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return string
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$value = $aCols[$sPrefix.''];

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return array column/value pairs (1 in most of the cases), for WRITING (Insert, Update)
	 */
	public function GetSQLValues($value)
	{
		$aValues = array();
		$aValues[$this->Get('sql')] = $this->ScalarToSQL($value);
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aValues[$this->Get('sql').'_format'] = $this->GetFormat();
		}

		return $aValues;
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
	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,	$bConvertToPlainText = false) {
		
		// Example used a switch for $this->GetFormat(), which would allow different implementations for other formats.
		
		return parent::GetAsCSV($sValue, $sSeparator, $sTextQualifier, $oHostObject, $bLocalize, $bConvertToPlainText);
		
	}
}
