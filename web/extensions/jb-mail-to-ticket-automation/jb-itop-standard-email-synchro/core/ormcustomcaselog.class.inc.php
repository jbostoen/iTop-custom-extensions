<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Custom version of ormCaseLog. Likely to be included in my fork of Mail to Ticket Automation.
 * extended AddLogEntry() to support on_behalf_of_user_id (rather than just 'on_behalf_of'). Important: NOT done so for AddLogEntryFromJSON(). If needed, do this.
 * originally intended an UpdateLogEntryAt() to support retro-active updating (meant for our own version of 'Mail to Ticket Automation'); but it would have to loop each time anyway.
 * therefore, it's better to build an ormCustomCaseLog from scratch and use AddLogEntry()
 */
 

if( class_exists('ormCustomCaseLog') == false ) {
	

	class ormCustomCaseLog extends ormCaseLog {

		/**
		 * Add a new entry to the log or merge the given text into the currently modified entry 
		 * and updates the internal index
		 * @param string $sText The text of the new entry
		 * @param string $sOnBehalfOf  Custom specified user name (for example: "from:" in the Mail to Ticket Automation extension)
		 * @param integer|null $iOnBehalfOfUserId Custom specified user ID
		 * @param String $sDateTime Time sent
		 */
		public function AddLogEntry($sText, $sOnBehalfOf = '', $iOnBehalfOfUserId = null, $sDateTime = '')
		{
			$sText = HTMLSanitizer::Sanitize($sText);
			$sDate = ($sDateTime == '' ? date(AttributeDateTime::GetInternalFormat()) : date(AttributeDateTime::GetInternalFormat(), strtotime($sDateTime)));
			
			if ($sOnBehalfOf == '')	{
				$sOnBehalfOf = UserRights::GetUserFriendlyName();
				$iUserId = UserRights::GetUserId();
			}
			if( $iOnBehalfOfUserId !== null ) {
				$iUserId = $iOnBehalfOfUserId;
			}
			
			/* 
				No prepending!
				if ($this->m_bModified)
				{
					$aLatestEntry = end($this->m_aIndex);
					if ($aLatestEntry['user_name'] == $sOnBehalfOf && $aLatestEntry['user_id'] == $iUserId)
					{
						// Append the new text to the previous one
						$sPreviousText = substr($this->m_sLog, $aLatestEntry['separator_length'], $aLatestEntry['text_length']);
						$sText = $sPreviousText."\n".$sText;

						// Cleanup the previous entry
						array_pop($this->m_aIndex);
						$this->m_sLog = substr($this->m_sLog, $aLatestEntry['separator_length'] + $aLatestEntry['text_length']);
					}
				}
			*/

			$sSeparator = sprintf(CASELOG_SEPARATOR, $sDate, $sOnBehalfOf, $iUserId);
			$iSepLength = strlen($sSeparator);
			$iTextlength = strlen($sText);
			$this->m_sLog = $sSeparator.$sText.$this->m_sLog; // Latest entry printed first
			$this->m_aIndex[] = array(
				'user_name' => $sOnBehalfOf,
				'user_id' => $iUserId,
				'date' => strtotime($sDateTime),
				'text_length' => $iTextlength,
				'separator_length' => $iSepLength,
				'format' => 'html',
			);
			$this->m_bModified = true;
		}
		
		/**
		 * Returns entries
		 * 
		 * @return Array
		 */
		public function GetEntries() {
			return $this->m_aIndex;
		}
		
		/**
		 * Sorts case log entries by timestamp ('date')
		 *
		 * @param Boolean $bAscending Defaults to true.
		 *
		 * @return void
		 *
		 * @details Warning: if DBUpdate() is called AFTER this, it will be considered a modification and it will be likely be logged as 'new entry added'
		 */
		public function ToSortedCaseLog($bAscending = true) {
			
			$aEntries = $this->GetAsArray();
			
			usort($aEntries, function ($item1, $item2) use ($bAscending) {
				
				$dtCompare1 = ($bAscending == true ? $item2['date'] : $item1['date']);
				$dtCompare2 = ($bAscending == true ? $item1['date'] : $item2['date']);
				
				return $dtCompare1 <=> $dtCompare2;
			});
			
			// m_aIndex AND m_sLog both need to be updated, hence this trick.
			$oCustomCaseLog = new ormCustomCaseLog();
			
			foreach($aEntries as $aEntry) {
				$oCustomCaseLog->AddLogEntry($aEntry['message_html'], $aEntry['user_login'], $aEntry['user_id'], $aEntry['date']);
			}
			
			return $oCustomCaseLog;
			
		}
		
	}

}
