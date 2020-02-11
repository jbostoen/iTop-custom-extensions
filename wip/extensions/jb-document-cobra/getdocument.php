<?php

/**
 * @copyright   Copyright (C) 2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Retrieve Cobra document
 */
 
	if (!defined('APPROOT')) require_once(__DIR__.'/../../approot.inc.php');
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/displayblock.class.inc.php');
	require_once(APPROOT.'/application/itopwebpage.class.inc.php');
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');

	if (\LoginWebPage::EXIT_CODE_OK != \LoginWebPage::DoLoginEx(null /* any portal */, false, \LoginWebPage::EXIT_RETURN)) {
		throw new \SecurityException('You must be logged in');
	}
	
	if(function_exists('sqlsrv_connect') == true) {

		try {

			// SQL
			$sServerName = \MetaModel::GetModuleSetting('jb-document-cobra', 'server_instance').', '.\MetaModel::GetModuleSetting('jb-document-cobra', 'server_port');
			
			// (non-Windows) SQL user with data_reader rights
			$aConnectionInfo = [
				'Database' => 'CobraStore',
				'UID' => \MetaModel::GetModuleSetting('jb-document-cobra', 'sql_user'),
				'PWD' => \MetaModel::GetModuleSetting('jb-document-cobra', 'sql_password'),
			];

			$oConnection = sqlsrv_connect($sServerName, $aConnectionInfo);

			if($oConnection) {
							
				$iIdDocumentVersionId = (Int)$_REQUEST['id'];

				$sQuery = "
				
					SELECT 
						[DocVersion].*,
						[DocumentAgendaPunt].*
						
					FROM [CobraStore].[dbo].[DocVersion] 

					/* Needed to get the agenda item's ID to link to other info */
					LEFT JOIN [CobraStore].[dbo].[Document] ON [CobraStore].[dbo].[DocVersion].DocumentId = [CobraStore].[dbo].[Document].Id 

					/* Only request the agenda item with specific ID */
					INNER JOIN [Cobra].[dbo].[Agendapunt] ON [CobraStore].[dbo].[Document].AgendapuntId = [Cobra].[dbo].[Agendapunt].Id 

					/* [DocumentAgendapunt] contains document title, type of attachment, ... */
					INNER JOIN [Cobra].[dbo].[DocumentAgendaPunt] ON [CobraStore].[dbo].[Document].Dossiernummer = [Cobra].[dbo].[DocumentAgendaPunt].Id 
					
					WHERE [DocVersion].Id = '".$iIdDocumentVersionId."'
					
				";
				$stmt = sqlsrv_query($oConnection, $sQuery);

				if($stmt === false) {
					throw new \ApplicationException(print_r(sqlsrv_errors(), true));
				}
				
				// Vraagt rij per rij
				$aResults = [];
				while($aRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					
					$aResults[] = $aRow;
					
				}
				
				// Free resources.
				sqlsrv_free_stmt($stmt);
				
				if(count($aResults) != 1) {			
					die('Unexpected error.');			
				}
				
				$aDoc = $aResults[0];
				
				
				//Returns the Mime Type of a file or a string content - from: https://coursesweb.net/
				// $r = the resource: Path to the file; Or the String content
				// $t = type of the resource, needed to be specified as "str" if $r is a string-content
				$oFileInfo = new finfo(FILEINFO_MIME);
				$sMimeType = $oFileInfo->buffer($aDoc['DocData']);
				
				$sAction = \utils::ReadParam('action', '');
				
				switch($sAction) {
					
					case 'download':
					
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="'.$aDoc['Titel'].'.'.$aDoc['Extension'].'"'); 
						header('Content-Transfer-Encoding: binary');
						header('Connection: Keep-Alive');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Content-Length: ' . strlen($aDoc['DocData']));
						echo $aDoc['DocData'];
						break;
						
					case 'view':
						
						header('Content-Type: '.$sMimeType);
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						echo $aDoc['DocData'];
						break;
						
					default:
						throw new \ApplicationException('Unexpected action');
					
				}
				
			}
			else {
				throw new \ApplicationException(\Dict::S('Class:DocumentCobra:MissingPHPMSSQLExtension'));
			}
		
		}
		catch(\Exception $e) {
			require_once(APPROOT.'/application/nicewebpage.class.inc.php');
			$oP = new \NiceWebPage(\Dict::S('UI:PageTitle:FatalError'));
			$oP->add("<h1>".\Dict::S('UI:FatalErrorMessage')."</h1>\n");	
			$oP->add(\Dict::Format('UI:Error_Details', $e->getMessage()));	
			$oP->output();
		}
		
		
	}
