<?php

/**
 * @copyright   Copyright (C) 2020 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2020-01-27 19:00:07
 *
 * Definition of CobraHelper
 */

namespace jb_itop_extensions\cobra;

abstract class CobraHelper {
	
	/**
	 * Adds Cobra details to the details view of an iTop object.
	 * Note: does NOT take care of security!
	 *
	 * @param \WebPage $oPage Web page
	 * @param \Integer $iCobraAgendaItem ID of agenda item in Cobra
	 *
	 * @return void
	 */
	public function AddCobraDetails(\WebPage $oPage, $iCobraAgendaItem) {

		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.\Dict::S('Integration:Cobra:Details').'</legend>');
		$oPage->add('<a href="'.sprintf(\MetaModel::GetModuleSetting('jb-document-cobra', 'url', 'https://forgot-to-configure-your.url/%1$s'), $iCobraAgendaItem).'" _target="_BLANK">'.\Dict::S('Integration:Cobra:ViewAgendaItem').'</a>');
		$oPage->add('<hr>');
		
		if(function_exists('sqlsrv_connect') == true) {

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

				$iIdAgendaItem = $iCobraAgendaItem;

				$sQuery = "
				
					SELECT 
						[DocVersion].Id, [DocVersion].DocumentId, [DocVersion].VersionStr, /*[DocVersion].DocData, */
						[Agendapunt].Volgnummer, 
						[DocumentAgendaPunt].AgendapuntId, [DocumentAgendaPunt].Titel, 
						[Document].Name
						/* [DocumentAgendaPunt].* */

					FROM [CobraStore].[dbo].[DocVersion] 

					/* Needed to get the agenda item's ID to link to other info */
					LEFT JOIN [CobraStore].[dbo].[Document] ON [CobraStore].[dbo].[DocVersion].DocumentId = [CobraStore].[dbo].[Document].Id 

					/* Only request the agenda item with specific ID */
					INNER JOIN [Cobra].[dbo].[Agendapunt] ON [CobraStore].[dbo].[Document].AgendapuntId = [Cobra].[dbo].[Agendapunt].Id AND [CobraStore].[dbo].[Document].AgendapuntId = '".$iIdAgendaItem."'

					/* [DocumentAgendapunt] contains document title, type of attachment, ... */
					INNER JOIN [Cobra].[dbo].[DocumentAgendaPunt] ON [CobraStore].[dbo].[Document].Dossiernummer = [Cobra].[dbo].[DocumentAgendaPunt].Id 
					
					/* Sort by VersionStr. Is text though, so might give weird results... */
					ORDER BY [CobraStore].[dbo].[DocVersion].VersionStr DESC
					
				";
				$stmt = sqlsrv_query($oConnection, $sQuery);

				if( $stmt === false) {
					die(print_r(sqlsrv_errors(), true));
				}
				
				$aResults = [];
				while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					
					$sDocumentId = (String)$row['DocumentId'];
					
					// Query already sorted by VersionStr descending, but to be sure it's not done alphabetically:
					if(isset($aResults[$sDocumentId]) == false) {
						$aResults[$sDocumentId] = $row;
					}
					else {
						// $aResults contains older version?
						if(version_compare($row['VersionStr'], $aResults[$sDocumentId]['VersionStr']) == 1) {
							$aResults[$sDocumentId] = $row;
						}
					}
					
				}
				
				if(count($aResults) > 0) {
					
					$oPage->add('<table>');
					
					foreach($aResults as $sDocumentId => $aDocument) {
						// $aDocument['VersionStr'] -> for debugging
						$oPage->add('
							<tr>
								<td>
									<a href="exec.php?exec_module=jb-document-cobra&exec_page=getdocument.php&exec_env=production&id='.$aDocument['Id'].'&action=view">'.\Dict::S('Integration:Cobra:ViewFile').'</a> | 
									<a href="exec.php?exec_module=jb-document-cobra&exec_page=getdocument.php&exec_env=production&id='.$aDocument['Id'].'&action=download">'.\Dict::S('Integration:Cobra:DownloadFile').'</a> | 
									'.$aDocument['Titel'].'
								</td>								
							</tr>
						');
					}
					
					$oPage->add('</table>');
					
				}
				else {
					$oPage->add('<li> '.\Dict::S('Integration:Cobra:NoFilesForThisItem').'</li>');
				}

				// Free resources.
				sqlsrv_free_stmt($stmt);


			}
			else {
				$oPage->add(json_encode(sqlsrv_errors()));
			}
		}
		else {
			$oPage->add(\Dict::S('Integration:Cobra:MissingPHPMSSQLExtension'));
		}
		
		$oPage->add('</fieldset>');
			
	}
	
}