<?php

/**
 * @copyright   Copyright (C) 2019-2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-04-09 16:58:14
 *
 * Definition of class RTPDF. Report tool which renders a PDF file.
 */

namespace jb_itop_extensions\report_generator\tools;

/**
 * Class ReportToolPDF. Parent class for iReportTool which creates PDF.
 */
abstract class RTPDF extends RTTwig implements iReportTool {
	
	/**
	 * Whether or not this extension is applicable
	 *
	 * @return Boolean
	 *
	 */
	public static function IsApplicable() {
		
		// Generic, so no.
		$sAction = \utils::ReadParam('action', '', false, 'string');
		return (in_array($sAction, ['download_pdf', 'show_pdf']) == true);
		
	}
	
	/**
	 * Action hook on rendering the entire template
	 *
	 * @var \Array $aReportData Report data
	 * @var \CMDBObjectSet[] $oSet_Objects CMDBObjectSet of iTop objects which are being processed
	 *
	 */
	public static function DoExec($aReportData, $oSet_Objects) {
		
		// If class doesn't exist, fail silently
		if(class_exists('\mikehaertl\wkhtmlto\Pdf') == false) {
			throw new \ApplicationException('wkhtml seems not to be configured or installed properly.');
		}
		
		try {
		
			/** @var \mikeheartl\wkhtmlto\Pdf $oPDF PDF Object */
			$oPDF = self::GetPDFObject($aReportData);			
			
			// Simply output
			

			// It will be called downloaded.pdf and offered as a download with this header
			// header("Content-Disposition:attachment;filename=downloaded.pdf");
			/*
				if(!$oPDF->saveAs('test.pdf')) {
					echo $oPDF->getError();
				}
			*/
			
			$sAction = \utils::ReadParam('action', '', false, 'string');
		
			switch($sAction) {
				case 'show_pdf':
					header('Content-type:application/pdf');
					break;
				
				case 'download_pdf':
					header('Content-type:application/pdf');
					header("Content-Disposition:attachment;filename=downloaded.pdf");
					break;
					
				default:
					// Unexpected
			}
			
			if(!$oPDF->send()) {
				echo $oPDF->getError();
			}			
				
		}
		catch(\Exception $e) {
			self::OutputError($e);
		}
		
	}
	
	/**
	 * Get PDF object based on report data.
	 *
	 * @var \Array $aReportData Hashtable
	 *
	 * @return \mikehaertl\wkhtmlto\Pdf PDF Object
	 */
	public static function GetPDFObject($aReportData) {
		
		// If class doesn't exist, fail silently
		if(class_exists('\mikehaertl\wkhtmlto\Pdf') == false) {
			throw new \ApplicationException('wkhtml seems not to be configured or installed properly.');
		}
		
		try {
		
			// Get HTML for this report
			$sHTML = self::GetReportFromTwigTemplate($aReportData);			
			
			// TCPPDF is expected to change in iTop 2.7; wkhtml offers more options.
			// However, wkhtmltopdf (stable = 0.12.5) does NOT support flex (uses older webkit version) 
			// Limited changes required: .row -> display: -webkit-box;
			require_once(APPROOT.'libext/vendor/autoload.php');
			$oPDF = new \mikehaertl\wkhtmlto\Pdf();
			
			// Example
			$aOptions = [
				// On some systems you may have to set the path to the wkhtmltopdf executable
				'binary' => 'C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe',
				'ignoreWarnings' => false,
				'commandOptions' => array(
					'useExec' => true,      // Can help on Windows systems
					'procEnv' => array(
						// Check the output of 'locale -a' on your system to find supported languages
						'LANG' => 'en_US.utf-8',
					),
				),
				
				// 'no-outline', // Make Chrome not complain
				'margin-top'    => 10,
				'margin-right'  => 10,
				'margin-bottom' => 10,
				'margin-left'   => 10,
				
				// HTTP credentials
				// 'username' => 'user',
				// 'password' => 'password',
				
			];
		
			// Config above is ignored. For cross instances, allow settings to be defined in config-itop.php
			$aOptions = \utils::GetCurrentModuleSetting('extra_wkhtmltopdf', []);
			
			// Some options can also be set as: $oPDF->binary = 'C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe';
			
			$oPDF->setOptions($aOptions);
			$oPDF->addPage($sHTML);

			return $oPDF;
				
		}
		catch(\Exception $e) {
			self::OutputError($e);
		}
		
	}
	
}

