<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 16:58:14
 *
 * Definition of class RTTwig. Report tool which renders content using Twig.
 */

namespace jb_itop_extensions\report_generator\tools;

/**
 * Class ReportToolTwig. Renders a report wit hbasic object details using Twig.
 */
abstract class RTTwig extends RTParent implements iReportTool {
	
	/**
	 * Whether or not this extension is applicable
	 *
	 * @return Boolean
	 *
	 */
	public static function IsApplicable() {
		
		// Always applicable when no action is specified.
		$sAction = \utils::ReadParam('action', '', false, 'string');		
		return ($sAction == '');
		
	}
	
	/**
	 * Rendering hook
	 *
	 * @var \Array $aReportData Report data
	 * @var \CMDBObjectSet[] $oSet_Objects CMDBObjectSet of iTop objects which are being processed
	 *
	 */
	public static function EnrichData(&$aReportData, $oSet_Objects) {
		
		// Enrich data with iTop setting (remove trailing /)
		$aReportData['itop']['root_url'] = substr(\utils::GetAbsoluteUrlAppRoot(), 0, -1);
		
	}
	
	/**
	 * Action hook
	 *
	 * @var \Array $aReportData Report data
	 * @var \CMDBObjectSet[] $oSet_Objects CMDBObjectSet of iTop objects which are being processed
	 *
	 */
	public static function DoExec($aReportData, $oSet_Objects) {
		
		try {
		
			$sHTML = self::GetReportFromTwigTemplate($aReportData);
			$sReportFile = self::GetReportFileName();
			
			// Set Content-Type header for these extensions
			$aExtensionsToContentTypes = [
				'csv' => 'text/csv',
				'html' => 'text/html',
				'json' => 'application/json',
				'twig' => 'text/html',
				'txt' => 'text/plain',
				'xml' => 'text/xml'
			];
			
			// Check if known extension, set MIME Type
			$sReportFileExtension = strtolower(pathinfo($sReportFile, PATHINFO_EXTENSION));
			if(isset($aExtensionsToContentTypes[$sReportFileExtension]) == true) {
				header('Content-Type: '.$aExtensionsToContentTypes[$sReportFileExtension]);
			}
			
			echo $sHTML;
		
		
		}
		catch(\Exception $e) {
			self::OutputError($e);
		}
		
	}
	
	/**
	 * Returns default filename of report
	 *
	 * @return \String Filename
	 */
	public static function GetReportFileName() {
		
		$sClassName = \utils::ReadParam('class', '', false, 'class');
		$sType = \utils::ReadParam('type', '', false, 'string');
		$sTemplateName = \utils::ReadParam('template', '', false, 'string');
		
		// 'class' and 'type' were already checked		
		if(empty($sTemplateName) == true) {
			throw new \ApplicationException(\Dict::Format('UI:Error:1ParametersMissing', 'template'));
		}
		
		// @todo - for 2.6.0 compatibility. Remove in future versions
		// @todo - starting 2.7.0: $sCurrentModuleDir = \utils::GetAbsoluteModulePath(\utils::GetCurrentModuleDir(0));
		$sCurrentModuleDir = APPROOT.'env-'.\utils::GetCurrentEnvironment().'/'.\utils::GetCurrentModuleDir(0).'/';
		$sReportDir = $sCurrentModuleDir . '/templates/'.$sClassName.'/'.$sType;
		$sReportFile = $sReportDir.'/'.$sTemplateName;
		
		// Prevent local file inclusion
		if($sCurrentModuleDir != dirname(dirname(dirname(dirname($sReportFile)))).'/') {
			throw new \ApplicationException('Invalid type or template');
		}
		elseif(file_exists($sReportFile) == false) {
			throw new \ApplicationException('Template does not exist: ' .$sReportFile);
		}
		
		return $sReportFile;
		
	}
	
	/**
	 * Returns content (HTML, XML, ...) of report
	 *
	 * @var \Array $aReportData Hashtable
	 *
	 * @return \String Content
	 */
	public static function GetReportFromTwigTemplate($aReportData = []) {
		
		$sReportFile = self::GetReportFileName();
		
		// Twig Loader
		$loader = new \Twig\Loader\FilesystemLoader(dirname($sReportFile));
		
		// Twig environment options
		$oTwigEnv = new \Twig\Environment($loader, [
			'autoescape' => false
		]); 

		// Combodo uses this filter, so let's use it the same way for our report generator
		$oTwigEnv->addFilter(new \Twig\TwigFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
				return \Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
			})
		);
		
		// Relies on chillerlan/php-qrcode
		if(class_exists('chillerlan\QRCode\QRCode') == true) {
			
			$oTwigEnv->addFilter(new \Twig\TwigFilter('qr', function ($sString) {

					$aOptions = new \chillerlan\QRCode\QROptions([
						'version'    => 5,
						// 'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG,
						'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG, // SVG is not rendered with wkhtmltopdf 0.12.5 (with patched qt) 
						'eccLevel'   => \chillerlan\QRCode\QRCode::ECC_L,
						'scale'		 => 3 // Note: scale is for SVG, IMAGE_*. output. Irrelevant for HTML output; use CSS
					]);

					// invoke a fresh QRCode instance
					$oQRCode = new \chillerlan\QRCode\QRCode($aOptions);

					// and dump the output 
					return '<img src="'.$oQRCode->render($sString).'">';		
			
				})
			);
				
		}
		else {
			
			$oTwigEnv->addFilter(new \Twig\TwigFilter('qr', function ($sString) {
				return $sString.' (QR library missing)';
			}));
				
		}
		
		return $oTwigEnv->render(basename($sReportFile), $aReportData);
		
	}
	
}
