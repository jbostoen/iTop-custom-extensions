<?php
// Copyright (C) 2012-2016 Combodo SARL
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
 * @copyright   Copyright (C) 2012-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Specific exceptions thrown when a decoding error occurs
 */
class EmailDecodingException extends Exception
{
}

/**
 * Helper class to read/decode Email messages encoded according to RFC 2822
 * 
 * This class is written in plain PHP using the standard functions base64_decode and quoted_printable_decode
 * and depends only on the "iconv" extension for the functions iconv and iconv_mime_decode.
 * The class can be used to process messages retrieved from POP3, IMAP or read from plain text files (.eml)
 * Note that for dealing directly with a POP3 or IMAP server, the PHP extension 'IMAP' is probably much more
 * efficient than this class!
 * 
 * This class is inspired from PlancakeEmailParser by Danyuki Software Limited (http://www.plancake.com)
 * written Daniele Occhipinti (dan@plancake.com)
 * 
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/lgpl-3.0.html LGPL
 */
class RawEmailMessage
{
	/**
	 * @var string Raw content of the email (headers + body altogether)
	 */
	protected $sRawContent;
	/**
	 * @var hash of headers: header_code => value
	 */
	protected $aHeaders;
	/**
	 * @var array of parts for a multiparts message
	 */
	protected $aParts;
	/**
	 * @var boolean Whether or not to ignore character set conversion errors
	 */
	protected $bStopOnIconvError;
	
	/**
	 * @var int For internal numbering of the parts
	 */
	protected $iNextId;
	
	/**
	 * Construct a new message from the full text version of it (equivalent to the content of a .eml file)
	 * @param string $sRawContent The full text version of the message (headers + empty line + body)
	 */
	public function  __construct($sRawContent)
	{
		$this->bStopOnIconvError = false;
		$this->iNextId = 0;
		
		$this->sRawContent = $sRawContent;
		$aLines = preg_split("/(\r?\n|\r)/", $sRawContent);
		$aData = $this->ExtractHeadersAndRawBody($aLines);
		
		$this->aHeaders = $aData['headers'];
		$this->aParts = $this->ExtractParts($aData['headers'], $aData['body']);
	}
	
	/**
	 * Get the raw size of the message (in bytes)
	 * @return int The size of the message
	 */
	public function GetSize()
	{
		return strlen($this->sRawContent);
	}
	
	/**
	 * Get the raw content of the message
	 * @return string The raw content of the message
	 */
	public function GetRawContent()
	{
		return $this->sRawContent;
	}
	
	/**
	 * Retrieves the address(es) from the originator of the message... tries: From, Sender and Reply-To
	 * @return array An array of ('email' => email_address, 'name' => display_name) one per 'sender'
	 */
	public function GetSender()
	{
		$sSender = $this->GetHeader('from');
		if (empty($sSender))
		{
			$sSender = $this->GetHeader('sender');
		}
		if (empty($sSender))
		{
			$sSender = $this->GetHeader('reply-to');
		}
		return self::ParseAddresses($sSender);
	}
	
	/**
	 * Retrieves the address(es) from the recipient of the message... "To". Note that in some cases
	 * there is no 'email', just a description like "undisclosed recipients"
	 * @return array An array of ('email' => email_address, 'name' => display_name) one per recipient
	 */
	public function GetTo()
	{
		return self::ParseAddresses($this->GetHeader('to'));
	}
	
	/**
	 * Retrieves the address(es) from the recipient in copy of the message... "CC".
	 * @return array An array of ('email' => email_address, 'name' => display_name) one per recipient
	 */
	public function GetCc()
	{
		return self::ParseAddresses($this->GetHeader('cc'));
	}
	
	/**
	 * Retrieves the subject of the message... "Subject".
	 * @return string The decoded subject of the message
	 */
	public function GetSubject()
	{
		return $this->GetHeader('subject');
	}
	
	/**
	 * Retrieves identifier of the message, as assigned by the creator/sender of the message
	 * @return string The message-id
	 */
	public function GetMessageId()
	{
		return $this->GetHeader('message-id');
	}
	
	/**
	 * Retrieves attachments of the message. Any part of the message that is not a "multipart", or an 'inline' text
	 * is considered as an attachment.
	 * @return array Array of ('filename' => original_file_name, 'mimeType' => MIME_type, 'content' => binary_string), one entry per attachment
	 */
	public function GetAttachments(&$aAttachments = null, $aPart = null, &$index = 1)
	{
		static $iAttachmentCount = 0;
		if ($aAttachments === null) $aAttachments = array();
		if ($aPart === null) $aPart = $this->aParts; //Init for recursion
		
		if ($aPart['type'] == 'simple')
		{
			if ($this->IsAttachment($aPart['headers']))
			{
				$sFileName = '';
				$sContentDisposition = $this->GetHeader('content-disposition', $aPart['headers']);
				if (($sContentDisposition != '') && (preg_match('/filename="([^"]+)"/', $sContentDisposition, $aMatches)))
				{
					$sFileName = $aMatches[1];
				}
				else if (($sContentDisposition != '') && (preg_match('/filename=([^"]+)/', $sContentDisposition, $aMatches))) // same but without quotes
				{
					$sFileName = $aMatches[1];
				}
				
				$bInline = true;
				if (stripos($sContentDisposition, 'attachment;') !== false)
				{
					$bInline = false;
				}
				
				
				$sType = '';
				$sContentId = $this->GetHeader('content-id', $aPart['headers']);
				if (($sContentId != '') && (preg_match('/^<(.+)>$/', $sContentId, $aMatches)))
				{
					$sContentId = $aMatches[1];
				}
				else
				{
					$sContentId = 'itop_'.$iAttachmentCount;
					$iAttachmentCount++;
				}
				$sContentType = $this->GetHeader('content-type', $aPart['headers']);
				if (($sContentType != '') && (preg_match('/^([^;]+)/', $sContentType, $aMatches)))
				{
					$sType = $aMatches[1];
				}
				if (empty($sFileName) && preg_match('/name="([^"]+)"/', $sContentType, $aMatches))
				{
					$sFileName = $aMatches[1];
				}
				if (empty($sFileName))
				{
					// generate a name based on the type of the file...
					$aTypes = explode('/', $sType);
					$sFileExtension = $aTypes[1];
					// map the type to a useful extension if needed
					switch($aTypes[1])
					{
						case 'rfc822':
						// Special case for messages: use the .eml extension
						$sFileExtension = 'eml';
						break;
					}
					$sFileName = sprintf('%s%03d.%s', $aTypes[0], $index, $sFileExtension); // i.e. image001.jpg 
				}
				$aAttachments['part_'.$aPart['part_id']] = array(
					'filename' => $sFileName,
					'mimeType' => $sType,
					'content-id' => $sContentId,
					'content' => $this->DecodePart($aPart['headers'], $aPart['body']),
					'inline' => $bInline,
				);
			}
		}
		else
		{
			foreach($aPart['parts'] as $aSubPart)
			{
				$aAttachments = array_merge($aAttachments, $this->GetAttachments($aAttachments, $aSubPart, $index));
			}
		}
		
		return $aAttachments;		
	}
	
	/**
	 * Create a new RawEmailMessage object by reading the content of the given file
	 * @param string $sFilePath The path to the file to load
	 * @return RawEmailMessage The loaded message
	 */
	static public function FromFile($sFilePath)
	{
		//TODO: improve error handling in case the file does not exist or is corrupted...
		return new RawEmailMessage(file_get_contents($sFilePath));
	}
	
	/**
	 * Saves the raw message to a file (basically creating the equivalent to a .eml file that can be read
	 * by Thunderbird, Lotus Notes or Outlok Express)
	 * @param string $sFilePath The path to the file to write into (the file will be overwritten if it exists)
	 * @return void
	 */
	public Function SaveToFile($sFilePath)
	{
		//TODO: improve error handling in case the file does not exist or cannot be written...
		file_put_contents($sFilePath, $this->sRawContent);
	}
	
	/**
	 * returns the first 'text/plain' part of the message that is not an attachment
	 * or null if no such part exists in the message
	 * @return string The text 'body' of the message or null if no plain text version of the 'body' exists
	 */
	public function GetTextBody()
	{
		$aPart = $this->FindFirstPart('text/plain', '/attachment/i');
		if ($aPart === null)
		{
			return null;
		}
		else
		{
			return $aPart['body'];
		}
	}
	
	/**
	 * returns the first 'text/html' part of the message that is not an attachment
	 * or null if no such part exists in the message
	 * @return string The html 'body' of the message or null if no html version of the 'body' exists
	 */
	public function GetHTMLBody()
	{
		$aPart = $this->FindFirstPart('text/html', '/attachment/i');
		if ($aPart === null)
		{
			return null;
		}
		else
		{
			return $aPart['body'];
		}		
	}
	
	/**
	 * Get the value for the specified header
	 * @param string $sHeaderName The name of the header (non case sensitive)
	 * @param hash $aHeaders Optional parameter to read inside the headers of the given part of the message
	 * @return string The value of the header or an empty string if no such header exists in the message
	 */
	public function GetHeader($sHeaderName, $aHeaders = null)
	{
		if ($aHeaders === null)
		{
			$aHeaders = $this->aHeaders;
		}
		
		$sHeaderContent = '';
		if (array_key_exists(strtolower($sHeaderName),$aHeaders))
		{
			$sHeaderContent = $aHeaders[strtolower($sHeaderName)];
		}
		return $sHeaderContent;
	}
	
	/**
	 * Get all the headers of the message as a whole (note: header names are lower case'd)
	 * @return hash The value of the headers as header_name => value
	 */
	public function GetHeaders()
	{
		return $aHeaders = $this->aHeaders;
	}
	
	/**
	 * Gets the full hierarchical structure of the message
	 * @param hash null. Used for recursion
	 * @return hash The wholme structure of the message except the 'body' piece of each part
	 */
	public function GetStructure($aParts = null)
	{
		$aRet = array();
		if ($aParts === null) $aParts = $this->aParts; //Init for recursion
		
		foreach($aParts as $sKey => $aData)
		{
			if ($sKey !== 'body')
			{
				if(is_array($aData))
				{
					$aRet[$sKey] = $this->GetStructure($aData);
				}
				else
				{
					$aRet[$sKey] = $aData;
				}
			}
		}
		return $aRet;
	}
	
	/**
	 * Retrieves a part of the message based on its 'part_id' identifier
	 * @param string $sId The identifier of the part to retrieve
	 * @param hash $aPart Used for recursion
	 * @return hash The 'raw' part found (i.e. the body is still an array of encoded strings), or null 
	 */
	public function GetPartById($sId, $aPart = null)
	{
		if ($aPart === null) $aPart = $this->aParts; //Init for recursion
		
		if (($aPart['type'] == 'simple') && ($aPart['part_id'] == $sId))
		{
			return $aPart;
		}
		else
		{
			foreach($aPart['parts'] as $aSubPart)
			{
				$aPartFound = $this->GetPartById($sId, $aSubPart);
				if ($aPartFound === null) return $aPartFound;
			}
		}
		return null;
	}
	/**
	 * Whether or not to stop (i.e. throw an exception) in case of iconv error
	 * @param bool $bStrict True to stop on error, false otherwise (default is false) ands null to get the value without changing it
	 * @return bool The previous (or current) valur of the parameter
	 */
	public function StrictCharacterSetConversion($bStrict = null)
	{
		$bPreviousValue = $this->bStopOnIconvError;
		if ($bStrict != null)
		{
			$this->bStopOnIconvError = $bStrict;	
		}
		return $bPreviousValue;
		
	}
	/////////////////////////////////////////////////////////////////////////
	//
	// Protected methods
	//
	/////////////////////////////////////////////////////////////////////////
	
	/**
	 * Splits the given content into pieces according to the given boundary marker
	 * @param array $aLines The content to be split as a array of lines
	 * @param string $sBoundary The "boundary" marker (without the leading --)
	 * @return array (1 entry per  part). Each part is an array of lines
	 */
	protected function SplitIntoParts($aLines, $sBoundary)
	{
		$sDelim = '--'.$sBoundary;
		$iLen = strlen($sDelim);
		$aCurPart = array();
		$aParts = array();
		foreach ($aLines as $sLine)
		{
			if (substr($sLine, 0, $iLen) == $sDelim)
			{
				if (count($aCurPart) > 0) $aParts[] = $aCurPart;
				$aCurPart = array();
			}
			else
			{
				$aCurPart[] = $sLine;
			}
		}
		if (count($aCurPart) > 0) $aParts[] = $aCurPart;
		return $aParts;
	}
	
	/**
	 * Recursively extracts the sub parts from the given header/body depending on the type of this part (multipart or not)
	 * @param hash $aHeaders Hash table of header => header_text
	 * @param array $aBodyLines Array of text lines
	 * @return array of parts. Each part is itself a hash: array(type => string, headers => hash, body => array, parts => array )
	 */
	protected function ExtractParts($aHeaders, $aBodyLines)
	{
		$aParsedParts = array();
		$sContentType = isset($aHeaders['content-type']) ? $aHeaders['content-type'] : '';

		if (($sContentType != '') && preg_match('/multipart(.*);.*?boundary="?([^"]+)"?/i', $sContentType, $aMatches))
		{
			$sBoundary = $aMatches[2];
			if (empty($sBoundary))
			{
				// hmm, malformed message ??
				throw new EmailDecodingException('No boundary found for the multipart piece of the message.');
			}
			
			if (stripos($sContentType, 'multipart/alternative') !== false)
			{
				$aParsedParts['type'] = 'alternative';
			}
			else
			{
				$aParsedParts['type'] = 'related';
			}
			$aParsedParts['part_id'] = $this->iNextId++;
			$aParsedParts['parts'] = array();
			$aRawParts = $this->SplitIntoParts($aBodyLines, $sBoundary);

			foreach($aRawParts as $aLines)
			{
				$aSubPart = $this->ExtractHeadersAndRawBody($aLines);
				if (count($aSubPart['headers']) > 0)
				{
					$aParsedParts['parts'][] = $this->ExtractParts($aSubPart['headers'], $aSubPart['body']);
				}
			}
		}
		else
		{
			$aParsedParts['part_id'] = $this->iNextId++;
			$aParsedParts['type'] = 'simple';
			$aParsedParts['headers'] = $aHeaders;
			if (!array_key_exists('content-type', $aParsedParts['headers']))
			{
				// The most simple type of part is plain text
				$aParsedParts['headers']['content-type'] = 'text/plain';
			}
			$aParsedParts['body'] = $aBodyLines;
		}
		return $aParsedParts;
	}

	/**
	 * Extracts the headers and the raw body of the given part of the message
	 * The headers are returned as a hash array, with key (in lowercase) => decoded value
	 * The body is returned as an array of strings (one per line)
	 * @param array $aLines
	 * @return hash array('headers' => hash, 'body' => array of strings)
	 */
	protected function ExtractHeadersAndRawBody($aLines)
	{
		$aRawFields = array();
		$sCurrentHeader = '';

		$idx = 0;
		$aRawBody = array();
		foreach ($aLines as $sLine)
		{
			if(self::IsNewLine($sLine))
			{
				// end of headers
				$aRawBody = array_slice($aLines, 1+$idx);
				break;
			}

			if (self::IsLineStartingWithPrintableChar($sLine)) // start of new header
			{
				if (preg_match('/([^:]+): ?(.*)$/', $sLine, $aMatches))
				{
					$sNewHeader = strtolower($aMatches[1]);
					$sValue = $aMatches[2];
					$aRawFields[$sNewHeader] = $sValue;
					$sCurrentHeader = $sNewHeader;
				}
			}
			else // the current header continues on this line
			{
				if (isset($aRawFields[$sCurrentHeader]))
				{
					$aRawFields[$sCurrentHeader] .= substr($sLine, 1);
				}
			}
			$idx++;
		}
		
		// Decode headers
		$aHeaders = array();
		foreach($aRawFields as $sKey => $sValue)
		{
			$aHeaders[$sKey] = self::DecodeHeaderString($sValue);
		}
		return array('headers' => $aHeaders, 'body' => $aRawBody);
	}

	/**
	 * Decodes to UTF-8 the entry of the header that can be either base64 or qencoded
	 * @param string $sInput The string to decode
	 * @return string The decoded string
	 */
	static protected function DecodeHeaderString($sInput)
    {
    	return iconv_mime_decode( $sInput, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8' ); // Don't be too strict, continue on errors
    }

	/**
	 * Finds the first part of the message that matches the given criteria: MIMEType / !Content-Disposition
	 * inside the (optional) part. If no part is given, scans the whole message.
	 * @param string $sMimeType The type to look for, for example text/html (non case sensitive)
	 * @param string $sExcludeDispositionPattern A type (as regexp) of "disposition" to exclude
	 * @param hash $aPart The part to scan, by default (=null) scan the whole message
	 * @return hash The given part as a hash array('headers' => hash, 'body' => binary_string); or null if not found
	 */
    protected function FindFirstPart($sMimeType, $sExcludeDispositionPattern = null, $aPart = null)
	{
		$aRetPart = null;
		
		if ($aPart === null) $aPart = $this->aParts; //Init for recursion

		if ($aPart['type'] == 'simple')
		{
			$sContentType = $this->GetHeader('Content-Type', $aPart['headers']);
			if (preg_match('/^([^;]+)/', $sContentType, $aMatches))
			{
				$sPartMimeType = $aMatches[1];
				if (strcasecmp($sPartMimeType, $sMimeType) == 0)
				{
					if ($sExcludeDispositionPattern != null)
					{
						$sContentDisposition = $this->getHeader('Content-Disposition', $aPart['headers']);
						if (($sContentDisposition == '') || (preg_match($sExcludeDispositionPattern, $sContentDisposition) == 0))
						{
							$aRetPart = array();
							$aRetPart['headers'] = $aPart['headers'];
							$aRetPart['body'] = $this->DecodePart($aPart['headers'], $aPart['body']);
						}
					}
					else
					{
						return $this->DecodePart($aPart['headers'], $aPart['body']);
					}
				}
			}
		}
		else
		{
			foreach($aPart['parts'] as $aSubPart)
			{
				$aRetPart = $this->FindFirstPart($sMimeType, $sExcludeDispositionPattern, $aSubPart);
				if ($aRetPart != null)
				{
					return $aRetPart; // Return once we've found one
				}
			}
		}
		return $aRetPart;
	}

	/**
	 * Decodes the 'lines' of the 'body' of the given part according to its headers (and the message's global headers)
	 * This function decode base64 and qencoded strings and converts the result to UTF-8 if needed
	 * @param hash $aHeaders The part headers, for the part to decode
	 * @param array $aLines The body to decode as an array of text strings (one entry per line)
	 * @return string The decoded 'body' of the part, in UTF-8
	 */
	function DecodePart($aHeaders, $aLines)
	{
		$sContentTransferEncoding = $this->GetHeader('Content-Transfer-Encoding');
		$sCharset = 'UTF-8';
		$sContentTypeHeader = $this->GetHeader('Content-Type');
		
		if (!empty($sContentTypeHeader) && preg_match('/charset=([^;]*)/i', $sContentTypeHeader, $aMatches))
		{
			$sCharset = strtoupper(trim($aMatches[1], '"'));
		}

		$sHeader = $this->GetHeader('Content-Transfer-Encoding', $aHeaders);
		if (!empty($sHeader))
		{
			$sContentTransferEncoding = strtolower($sHeader);
		}
		
		$sHeader = $this->GetHeader('Content-Type', $aHeaders);
		if (!empty($sHeader) && preg_match('/charset=([^;]*)/i', $sHeader, $aMatches))
		{
			$sCharset = strtoupper(trim($aMatches[1], '"'));
		}

		switch($sContentTransferEncoding)
		{
			case 'base64':
			$sBody = base64_decode(implode("\n", $aLines));
			if ($sBody === false)
			{
				// Failed to decode, try as-is
				$sBody = implode("\n", $aLines);
			}
			break;

			case 'quoted-printable':
			$sBody = quoted_printable_decode(implode("\n", $aLines));
			break;

			case '7bit':
			default:
			$sBody = implode("\n", $aLines);
		}
 
		// Convert to UTF-8 only if the part is some kind of text
		if($sCharset != 'UTF-8' && preg_match('/text\//', $aHeaders['content-type']))
		{
			$sOriginalBody = $sBody;
			$sBody = @iconv($sCharset, 'UTF-8//TRANSLIT//IGNORE', $sBody);

			if ($sBody === false)
			{
				if ($this->bStopOnIconvError) // iconv returns false on failure
				{
					throw new Exception("Cannot convert message part from '$sCharset' to UTF-8. iconv returned false");
				}
				else
				{
					$sBody = $sOriginalBody; // Pass it as-is !!!
				}
			}
			if (preg_match('/^([^;]+)/', $sHeader, $aMatches))
			{
				$sPartMimeType = $aMatches[1];
				if (strcasecmp($sPartMimeType, 'text/html') == 0)
				{
					// Right now the part is converted to UTF-8, so let's remove <meta charset=xxx> tags
					// which may fool further attemps at parsing the HTML (for example with DOMXML)
					$sBody = preg_replace('/<meta [^>]*charset=[^>]+>/i', '', $sBody);
				}
			}
		}
		 
		return $sBody;
	}
	
	/**
	 * Checks if the given line contains only a "newline" character
	 * @param string $sLine
	 * @return boolean
	 */
	protected static function IsNewLine($sLine)
	{
		$sLine = str_replace(array("\r", "\n"), '', $sLine);

		return (strlen($sLine) === 0);
	}

	/**
	 * Checks if the given line starts with a printable character
	 * @param string $sLine
	 * @return boolean
	 */
	protected static function IsLineStartingWithPrintableChar($sLine)
	{
		return preg_match('/^[A-Za-z]/', $sLine);
	}
	
	/**
	 * Tells whether a part of the message is an attachment or not based on its headers.
	 * The rules are:
	 *   - multipart/<anything> is not an attachment
	 *   - text/<anything> is an attachment if and only if the 'content-disposition' says so
	 *   - all other types are considered as attachments
	 * @param hash $aPartHeaders the headers of the part
	 * @return boolean
	 */
	protected function IsAttachment($aPartHeaders)
	{
		$sContentType = $this->GetHeader('content-type', $aPartHeaders);
		$bRet = true;
		if ($sContentType != '')
		{
			if (preg_match('|^([^/]+)/|', $sContentType, $aMatches))
			{
				$sPrimaryType = strtolower($aMatches[1]);
				if ($sPrimaryType == 'multipart')
				{
					$bRet = false;
				}
				else if ($sPrimaryType == 'text')
				{
					$sContentDisposition = $this->GetHeader('content-disposition', $aPartHeaders);
					if (($sContentDisposition != '') && (preg_match('/attachment/i', $sContentDisposition) != 0))
					{
						$bRet = true;
					}
					else
					{
						$bRet = false;
					}
					
				}
				else
				{
					$bRet = true;
				}
			}
		}
		return $bRet;
	}
	
	/**
	 * Turn the string of addresses into an array (one entry per address) where the two components of each address
	 * are separated: 'name' (human readable) and 'email'
	 * @param string $sAddresses The whole list of addresses as read from the email header
	 * @return array An array of ('name' => friendly_name, 'email' => email_address)
	 */
	protected static function ParseAddresses($sAddresses)
	{
		$sTextQualifier = '"';
		$sAddressDelimiter = ',';
		
		// Split the addresses on the first comma (,) after an at (@), if not inside a quoted string (")
		$aAddresses = array();
		$bInTextString = false;
		$bAtSignFound = false;
		$sCurrentAddress = '';
		for($idx = 0; $idx < strlen($sAddresses); $idx++)
		{
			$c = $sAddresses[$idx];
			
			if ($c == $sTextQualifier)
			{
				$bInTextString = !$bInTextString;
			}
			
			if ( ($c == '@') && !$bInTextString )
			{
				$bAtSignFound = true;
			}
			
			if ( ($c == $sAddressDelimiter) && !$bInTextString && $bAtSignFound)
			{
				// End of address
				$aAddresses[] = self::ExtractAddressPieces($sCurrentAddress);
				$sCurrentAddress = '';
				$bAtSignFound = false;
			}
			else
			{
				$sCurrentAddress .= $c;
			}
			
			if ( ($idx == (strlen($sAddresses)-1)) && (!empty($sCurrentAddress)) )
			{
				// last address in the string
				$aAddresses[] = self::ExtractAddressPieces($sCurrentAddress);
			}
		}
		
		return $aAddresses;
	}
	
	/**
	 * Tries to (!) separate the two components of each address
	 * 'name' (human readable) and 'email'
	 * @param string $sAddress The address to split
	 * @return array An array of ('name' => friendly_name, 'email' => email_address)
	 */
	protected static function ExtractAddressPieces($sAddress)
	{
		$sAddress = trim($sAddress);
		if (preg_match('/^(.*)<([^ ]+)>$/', $sAddress, $aMatches))
		{
			$sName = trim($aMatches[1], ' "');
			$sEmail = $aMatches[2];
		}
		else if (preg_match('/^([^ ]+) ?\((.*)\)$/', $sAddress, $aMatches))
		{
			$sName = trim($aMatches[2]);
			$sEmail = $aMatches[1];
		}
		else
		{
			if (strpos($sAddress, '@') === false)
			{
				// Hmm, no valid address ??
				$sName = $sAddress;
				$sEmail = '';
			}
			else
			{
				$sName = '';
				$sEmail = $sAddress;
			}
		}
		return array('name' => $sName, 'email' => $sEmail);
	}
}