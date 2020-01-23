<?php

//	Copyright (C) 2019-2020 Jeffrey Bostoen
//
//	This is an unofficial extension for Combodo's iTop.
//
//	It is free software; you can redistribute it and/or modify	
//	it under the terms of the GNU Affero General Public License as published by
//	the Free Software Foundation, either version 3 of the License, or
//	(at your option) any later version.
//
//	It is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU Affero General Public License for more details.
//
//	You should have received a copy of the GNU Affero General Public License
//	See <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Form\Field;

use Closure;
use DBObject;

/**
 * Description of GeometryField
 *
 * @author Jeffrey Bostoen
 * @package \Combodo\iTop\Form\Field
 * @since 2.6.0
 */
class GeometryField extends TextField
{
	
	// https://en.wikipedia.org/wiki/Well-known_text_representation_of_geometry
	const ENUM_FORMAT_WKT = 'WKT';
	
	// Reserved but unused for now.
	
	// Actually usually includes feature properties; which doesn't make sense to store. Unless only the feature geometry is stored.
	// https://en.wikipedia.org/wiki/GeoJSON
	const ENUM_FORMAT_GEOJSON = 'GeoJSON'; 
	
	// Plain coordinates. But what separator would be used?
	const ENUM_FORMAT_TEXT = 'text';
	
	const DEFAULT_FORMAT = 'WKT';

	protected $sFormat;
	protected $oObject;
	protected $sTransactionId;

	public function __construct($sId, Closure $onFinalizeCallback = null, DBObject $oObject = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->sFormat = static::DEFAULT_FORMAT;
		$this->oObject = $oObject;
		$this->sTransactionId = null;
	}

	/**
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->sFormat;
	}

	/**
	 *
	 * @param string $sFormat
	 * @return \Combodo\iTop\Form\Field\GeometryField
	 */
	public function SetFormat($sFormat)
	{
		$this->sFormat = $sFormat;
		return $this;
	}

	/**
	 *
	 * @return DBObject
	 */
	public function GetObject()
	{
		return $this->oObject;
	}

	/**
	 *
	 * @param DBObject $oObject
	 * @return \Combodo\iTop\Form\Field\GeometryField
	 */
	public function SetObject(DBObject $oObject)
	{
		$this->oObject = $oObject;
		return $this;
	}

	/**
	 * Returns the transaction id for the field. This is usally used/setted when using a html format that allows upload of files/images
	 *
	 * @return string
	 */
	public function GetTransactionId()
	{
		return $this->sTransactionId;
	}

	/**
	 *
	 * @param string $sTransactionId
	 * @return \Combodo\iTop\Form\Field\GeometryField
	 */
	public function SetTransactionId($sTransactionId)
	{
		$this->sTransactionId = $sTransactionId;
		return $this;
	}
	
	public function GetDisplayValue()
	{
		$sValue = $this->GetCurrentValue();
		// Display value could be different, for example based on 'format'.
		if ($this->GetFormat() == GeometryField::ENUM_FORMAT_WKT)
		{
		    $sValue = \Str::pure2html($this->GetCurrentValue());
			return '<div>'.$sValue.'</div>';
		}
		else
		{
			return '<div>To implement: '.$this->GetFormat().' '.$sValue.'</div>';
		}
	}

}
