<?php
// Copyright (C) 2016 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Lesser General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
/**
 * @copyright   Copyright (C) 2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * A decoded email message
 */
class EmailMessage {
	public $sUIDL;
	public $sMessageId;
	public $sSubject;
	public $sCallerEmail;
	public $sCallerName;
	public $sRecipient;
	public $aReferences;
	public $sThreadIndex;
	public $sBodyText;
	public $sBodyFormat;
	public $aAttachments;
	public $oRelatedObject;
	public $sDecodeStatus;
	public $aHeaders;
	public $sTrace;
	public $sDate;
	public $oRawEmail; // raw source email
	public $aTos;
	public $aCCs;
	
	const NEW_LINE_MARKER = '__NEWLINE__'; // unlikely to be found in the body of an email message
	
	public function __construct($sUIDL, $sMessageId, $sSubject, $sCallerEmail, $sCallerName, $sRecipient, $aReferences, $sThreadIndex, $sBodyText, $sBodyFormat, $aAttachments, $oRelatedObject, $aHeaders, $sDecodeStatus, $sDate = '', $aTos = array(), $aCCs = array())
	{
		$this->sUIDL = $sUIDL;
		$this->sMessageId = $sMessageId;
		$this->sSubject = $sSubject;
		$this->sCallerEmail = $sCallerEmail;
		$this->sCallerName = $sCallerName;
		$this->sRecipient = $sRecipient;
		$this->aReferences = $aReferences;
		$this->sThreadIndex = $sThreadIndex;
		$this->sBodyText = @iconv("UTF-8", "UTF-8//IGNORE", $sBodyText); // Filter out NON UTF-8 characters
		$this->sBodyFormat = $sBodyFormat;
		$this->aAttachments = $aAttachments;
		$this->oRelatedObject = $oRelatedObject;
		$this->sDecodeStatus = $sDecodeStatus;		
		$this->aHeaders = $aHeaders;
		$this->sTrace = '';
		$this->sDate = $sDate;
		$this->aTos = $aTos;
		$this->aCCs = $aCCs;
		$this->oRawEmail = null;
	}

	/**
	 * Archives the message into a file
	 */
	public function SaveToFile($sFile)
	{
		
	}
	/**
	 * Read the message from an archived file
	 */
	public function ReadFromFile($sFile)
	{
		
	}
	
	public function IsValid()
	{
		$bValid = !empty($this->sUIDL) && !empty($this->sCallerEmail) && !empty($this->sCallerName);

		foreach($this->aAttachments as $aAttachment)
		{
			$bAttachmentValid = !empty($aAttachment['mimeType']) && !empty($aAttachment['filename']) && !empty($aAttachment['content']);
			$bValid = $bValid && $bAttachmentValid;
		}
		
		return $bValid;
	}
	
	 
	/**
	 * Produce a plain-text version of the body of the message by stripping the HTML tags but preserving the visual line breaks
	 * @return string The plain-text version of the text
	 */
	public function StripTags($sText = null)
	{
		if ($sText == null)
		{
			$sText = $this->sBodyText;
		}
		
		// Completely remove the <head>...</head> tags, including their contents
		$sStyleExpr = '|<head>(.*)</head>|iUs';
		$sBodyText = preg_replace($sStyleExpr, '', $sText);
		
		// Completely remove the <style>...</style> tags, including their contents
		$sStyleExpr = '|<style>(.*)</style>|iUs';
		$sBodyText = preg_replace($sStyleExpr, '', $sText);
		
		// Preserve hyperlinks <pre>...</pre> tags
		$sBodyText = preg_replace_callback('|<a([^>]*)>(.*)</a>|isU', array($this, 'AnchorsReplaceCallback'), $sBodyText);
		
		// Preserve new lines inside <pre>...</pre> tags
		$sBodyText = preg_replace_callback('|<pre>(.*)</pre>|isU', array($this, 'PregReplaceCallback'), $sBodyText);

		// Process line breaks: remove carriage returns / line feeds that have no meaning in HTML => replace them by a plain space
		$sBodyText = str_ireplace(array("\n", "\r"), ' ',$sBodyText);
		// Replace <p...>...</p> and <br/> by a carriage return
		$sBodyText = preg_replace('/<p[^>]*>/i', '', $sBodyText);
		$sBodyText = str_ireplace(array('</br>', '<br/>', '<br>', '</p>'), self::NEW_LINE_MARKER, $sBodyText);
		
		// <tr> tags usually start a "new line"
		$sBodyText = str_ireplace('<tr ', self::NEW_LINE_MARKER.'<tr ', $sBodyText);
		
		// <div> tags usually start a "new line" (since by default display-style == block)
		$sBodyText = str_ireplace('<div ', self::NEW_LINE_MARKER.'<div ', $sBodyText);
		// remove tags (opening and ending tags MUST match!)
		$sBodyText = strip_tags($sBodyText);
		// Process some usual entities
		$sBodyText = html_entity_decode($sBodyText, ENT_QUOTES, 'UTF-8');
		
		// Remove consecutive line breaks
		$sBodyText = preg_replace("/".self::NEW_LINE_MARKER."(".self::NEW_LINE_MARKER.")+/", self::NEW_LINE_MARKER, $sBodyText);

		// restore remaining line breaks
		$sBodyText = str_replace(self::NEW_LINE_MARKER, "\n", $sBodyText);
	
		return trim($sBodyText, " \r\n\t".chr(0xC2).chr(0xA0)); // c2a0 is the UTF-8 non-breaking space character
	}
	
	/**
	 * Function used with preg_replace_callback to replace the anchors/hyperlinks tags <a ...>...</a>
	 * @param hash $aMatches
	 * @return string
	 */
	protected function AnchorsReplaceCallback($aMatches)
	{
		$sAttributes = $aMatches[1];
		if(preg_match('/href="([^"]+)"/', $sAttributes, $aHrefMatches))
		{
			// Hyperlinks
			if (substr($aHrefMatches[1], 0, 7) == 'mailto:')
			{
				// "mailto:" hyperlinks: keep only the email address (will not be clickable in iTop anyhow)
				$sText = ' '.substr($aHrefMatches[1], 7).' ';
			}
			else
			{
				// Other type of hyperlink, keep as-is, the display in iTop will turn it back into a clickable hyperlink
				$sText = ' '. $aHrefMatches[1].' ';
			}
		}
		else
		{
			// No hyperlink, just keep the text of the anchor
			$sText = $aMatches[2];
		}
		return $sText;
	}
	
	/**
	 * Function used with preg_replace_callback to replace the newlines inside the <pre>...</pre> tags
	 * @param hash $aMatches
	 * @return string
	 */
	protected function PregReplaceCallback($aMatches)
	{
		$sText = str_replace(array("\n", "\r"), EmailMessage::NEW_LINE_MARKER, $aMatches[1]);
		return EmailMessage::NEW_LINE_MARKER.strip_tags($sText).EmailMessage::NEW_LINE_MARKER; // Each <pre>...<pre> causes a line break before and after
	}
		
	/**
	 * When the message is a reply or forward of another message, this method
	 * (tries to) extract the "new" part of the body in HTML, producing some HTML
	 * as the output. The filtering is based on a list of tags/classes to remove (overrideable by 'html-tags-to-remove' in the config)
	 */
	public function GetNewPartHTML($sBodyText = null)
	{
		if ($sBodyText === null)
		{
			$sBodyText = $this->sBodyText;
		}
		
		if ($sBodyText == '') return ''; // No need for a sophisticated processing, an empty string is an empty string!
		if (strpos($sBodyText, '<html') === false)
		{
			// No enclosing <html></html> tag, let's add it
			$sBodyText = '<html><body>'.$sBodyText.'</body></html>';
		}
		
		// default tags to remove: array of tag_name => array of class names
		$aTagsToRemove = array(
			'blockquote' => array(),
			'div' => array('gmail_quote', 'moz-cite-prefix'),
			'pre' => array('moz-signature'),
		);

		if (class_exists('MetaModel'))
		{
			$aTagsToRemove = MetaModel::GetModuleSetting('jb-email-synchro', 'html-tags-to-remove', $aTagsToRemove);	
		}		
		
		$this->oDoc = new DOMDocument();
		$this->oDoc->preserveWhitespace = true;
		@$this->oDoc->loadHTML('<?xml encoding="UTF-8"?>'.$sBodyText); // For loading HTML chunks where the character set is not specified
		
		$this->CleanNode($this->oDoc, $aTagsToRemove);
		
		$oXPath = new DOMXPath($this->oDoc);
		$sXPath = "//body";
		$oNodesList = $oXPath->query($sXPath);
		
		if ($oNodesList->length == 0)
		{
			// No body, save the whole document
			$sCleanHtml = $this->oDoc->saveHTML();
		}
		else
		{
			// Export only the content of the body tag
			$sCleanHtml = $this->oDoc->saveHTML($oNodesList->item(0));
			// remove the body tag itself
			$sCleanHtml = preg_replace( array('|<body[^>]*>|', '|</body>|'), '', $sCleanHtml);
		}

		return $sCleanHtml;
	}
	
	/**
	 * Clean the given node according to the list of tags / classes to remove
	 * @param DOMNode $oElement
	 * @param unknown $aTagsToRemove
	 */
	protected function CleanNode(DOMNode $oElement, $aTagsToRemove)
	{
		$aAttrToRemove = array();
		
		if ($oElement->hasChildNodes())
		{
			$aChildElementsToRemove = array();
			// Gather the child nodes to remove
			foreach($oElement->childNodes as $oNode)
			{
				if (($oNode instanceof DOMElement) && (array_key_exists(strtolower($oNode->tagName), $aTagsToRemove)))
				{
					$aCSSClasses = $aTagsToRemove[strtolower($oNode->tagName)];
					if (count($aCSSClasses) == 0)
					{
						// No special class to check, remove the node straight away
						$aChildElementsToRemove[] = $oNode;
					}
					else
					{
						// Check if the class of this node is a special one
						if (in_array($oNode->getAttribute('class'), $aCSSClasses))
						{
							// The 'class' indicates that this node has to be removed
							$aChildElementsToRemove[] = $oNode;
						}
						else
						{
							// Recurse
							$this->CleanNode($oNode, $aTagsToRemove);
						}
					}
				}
				else if ($oNode instanceof DOMComment)
				{
					// Remove comments
					$aChildElementsToRemove[] = $oNode;
				}
				else
				{
					// Recurse
					$this->CleanNode($oNode, $aTagsToRemove);
				}
			}
						
			// Now remove all marked nodes
			foreach($aChildElementsToRemove as $oDomElement)
			{
				$oElement->removeChild($oDomElement);
			}
		}		
	}
	
	/**
	 * When the message is a reply or forward of another message, this method
	 * (tries to) extract the "new" part of the body
	 */
	public function GetNewPart($sBodyText = null, $sBodyFormat = null)
	{
		if ($sBodyText === null)
		{
			$sBodyText = $this->sBodyText;
		}
		if ($sBodyFormat === null)
		{
			$sBodyFormat = $this->sBodyFormat;
		}
		$this->sTrace .= "Beginning of GetNewPart:\n";
		$this->sTrace .= "=== eMail body ({$sBodyFormat}): ===\n{$sBodyText}\n=============\n";
		$aIntroductoryPatterns = array(
			'/^De : .+$/', // Outlook French
			'/^le .+ a écrit :$/i', // Thunderbird French
			'/^on .+ wrote:$/i', // Thunderbird English
			'|^[0-9]{4}/[0-9]{1,2}/[0-9]{1,2} .+:$|', // Gmail style
		);
		$aGlobalDelimiterPatterns = array(
			"/\RFrom: .+\RSent: .+\R/m",
			"/\RDe : .+\REnvoyé : .+\R/m",
		);
		$aDelimiterPatterns =  array(
			'/^>.*$/' => false, // Old fashioned mail clients: continue processing the lines, each of them is preceded by >
		);

		if (class_exists('MetaModel'))
		{
			$aIntroductoryPatterns = MetaModel::GetModuleSetting('jb-email-synchro', 'introductory-patterns', $aIntroductoryPatterns);	
			$aGlobalDelimiterPatterns = MetaModel::GetModuleSetting('jb-email-synchro', 'multiline-delimiter-patterns', $aGlobalDelimiterPatterns);
			$aDelimiterPatterns = MetaModel::GetModuleSetting('jb-email-synchro', 'delimiter-patterns', $aDelimiterPatterns);
		}
		
		if ($sBodyFormat == 'text/html')
		{
			// In HTML the "quoted" text is supposed to be inside "<blockquote....>.....</blockquote>"
			$this->sTrace .= 'Processing the HTML body (removing blockquotes)'."\n";
			$sBodyText = preg_replace("|<blockquote.+</blockquote>|iUms", '', $sBodyText);
			$this->sTrace .= 'Converting the HTML body to plain text'."\n";
			$sBodyText = $this->StripTags($sBodyText);
		}
		// Treat everything as if in text/plain
		$sUTF8NonBreakingSpace = pack("H*" , 'c2a0'); // hex2bin does not exist until PHP 5.4.0
		$sBodyText = str_replace($sUTF8NonBreakingSpace, ' ', $sBodyText); // Replace UTF-8 non-breaking spaces by "normal" spaces to ease pattern matching
																		   // since PHP/PCRE does not understand MBCS so 0xc2 0xa0 is seen as two characters whereas it displays as a single (non-breaking) space!!
		$aLines = explode("\n", $sBodyText);
		$sPrevLine = '';
		$bGlobalPattern = false;
		$iStartPos = null; // New part position if global pattern is found
		foreach($aGlobalDelimiterPatterns as $sPattern)
		{
			$this->sTrace .= 'Processing the body; trying the global pattern: "'.$sPattern.'"'."\n";
			$ret = preg_match($sPattern, $sBodyText, $aMatches, PREG_OFFSET_CAPTURE);
			if ($ret === 1)
			{
				if ($bGlobalPattern)
				{
					// Another pattern was already found, keep only the min of the two
					$iStartPos = min($aMatches[0][1], $iStartPos);
				}
				else
				{
					$iStartPos = $aMatches[0][1];
				}
				$this->sTrace .= 'Found a match with the global pattern: "'.$sPattern.'"'."\n";
				$bGlobalPattern = true;
				// continue the loop to find if another global pattern is present BEFORE this one in the message
			}
			else if ($ret === false)
			{
				$iErrCode = preg_last_error();
				$this->sTrace .= 'An error occurred with global pattern: "'.$sPattern.'"'." (errCode = $iErrCode)\n";
			}
		}
		if ($bGlobalPattern)
		{
			$sNewText = substr($sBodyText, 0, $iStartPos);
		}
		else
		{
			$aKeptLines = array();
			foreach($aLines as $index => $sLine)
			{
				$sLine = trim($sLine);
				$bStopNow = $this->IsNewPartLine($sLine, $aDelimiterPatterns); // returns true, false or null (if no match)
				if ($bStopNow !== null)
				{
					$this->sTrace .= 'Processing the text/plain body; line #'.$index.' contains the delimiter pattern, will be removed (stop = '.($bStopNow ? 'true' : 'false').')'."\n";
					// Check if the line above contains one of the introductory pattern
					// like: On 10/09/2010 john.doe@test.com wrote:
					if (($index > 0) && isset($aLines[$index-1]))
					{
						$sPrevLine = trim($aLines[$index-1]);
						foreach($aIntroductoryPatterns as $sPattern)
						{
							if (preg_match($sPattern, trim($sPrevLine)))
							{
								// remove the introductory line
								unset($aKeptLines[$index-1]);
								$this->sTrace .= 'Processing the text/plain body; line #'.($index-1).' contains the introductory pattern, will be removed.'."\n";
								break;
							}
						}
					}
					if ($bStopNow === true)
					{
						break;
					}
				}
				else // null => no match, keep the line
				{
					$aKeptLines[$index] = $sLine;
				}
			}
			$sNewText = trim(implode("\n", $aKeptLines));
		}
		$sNewText = trim($sNewText);
		$this->sTrace .= "=== GetNewPart returns: ===\n$sNewText\n=============\n";
		return $sNewText;
	}
	
	protected function IsNewPartLine($sLine, $aDelimiterPatterns)
	{
		foreach($aDelimiterPatterns as $sPattern => $bStopNow)
		{
			if (preg_match($sPattern, $sLine)) return $bStopNow;
		}
		return null;
	}
}
