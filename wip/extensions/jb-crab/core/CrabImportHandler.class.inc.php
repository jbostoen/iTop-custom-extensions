<?php 

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-10-04 18:08:57
 *
 * Definition of Address
 */
 
namespace jb_crab;

	/**
	 * Class CrabImportHandler. Contains methods to import addresses.
	 */
	abstract class CrabImportHandler {
		
		/**
		 * @var \Array $aCrab_Status Crab Status codes
		 */
		private static $aCrab_Status = [
			// Official Crab Status
			'proposed' => 1,
			'reserved' => 2,
			'in_use' => 3,
			'no_longer_in_use' => 4,
			
			// Unofficial: not found in dataset
			'not_found' => 99
		];
		
		/**
		 *
		 * Constructor
		 *
		 * @return void
		 */
		private function __construct() {
			
		}
		
		/**
		 * Trace method
		 *
		 * @return void
		 */
		public function Trace($sMessage) {
			echo date('Y-m-d H:i:s').' - '.\utils::GetCurrentModuleName().' - '.$sMessage.PHP_EOL;
		}
		
		/**
		 *  
		 * Fetches Crab Address list from Flemish services. By default, the entire dataset is downloaded.
		 * Otherwise it gets complicated with authentication.
		 * The file is currently (27th of September 2018) around 150 MB when zipped.
		 * Unpacked it's currently a 1 GB file.
		 *   
		 * @return void
		 *   
		 */
		public function DownloadShapeFile() {

			// Link last checked 16th of July,2019
			$sURL = 'https://downloadagiv.blob.core.windows.net/crab-adressenlijst/Shapefile/CRAB_Adressenlijst_Shapefile.zip';
			$sDownloadDirectory = str_replace('\\', '/', dirname(__FILE__).'/download');
			$sTargetFileName = $sDownloadDirectory.'/Crab_Adressenlijst_Shapefile.zip';
			
			// Recursive delete everything
			self::RecursiveRemoveDirectory($sDownloadDirectory);
			$oOldMask = umask(0);
			mkdir($sDownloadDirectory, '0755');
			umask($oOldMask);

			self::Trace('Downloading file...');
			self::Trace('. From '.$sURL);
			self::Trace('. To '.$sTargetFileName);
			
			// Actual download
 			$ch = curl_init();
			$sDownloadFile = fopen($sTargetFileName, 'w');
			curl_setopt($ch, CURLOPT_URL, $sURL);
			 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
			curl_setopt($ch, CURLOPT_NOPROGRESS, false);
			curl_setopt($ch, CURLOPT_FILE, $sDownloadFile);
			curl_exec($ch);
			curl_close($ch);
			
			if(class_exists('ZipArchive') == false) {
				self::Trace('Failure: missing php-zip?');
				throw new \Exception('Failure: missing php-zip?');
			}
			
			self::Trace('Unzipping file...');

			$zip = new \ZipArchive;
			$res = $zip->open($sTargetFileName);
			
			if ($res === true) {
				
				$zip->extractTo($sDownloadDirectory);
				$zip->close();
			  			  
			} 
			else {
				// Fail
				self::Trace('Unable to unzip file to directory '.$sDownloadDirectory);
				throw new \Exception('Unable to unzip file to directory '.$sDownloadDirectory);
			}
			
		}
				 
		/**
		 * Converts the shapefile to GeoJSON. Enforces EPSG:3857 (GeoJSON typically uses EPSG:4326).
		 * Applies a specified OGR filter too. Ordering is important for later processing!
		 * 
		 * @param String $sFilter OGR Filter. Single quotes within the query must be escaped.
		 *
		 * @return String GeoJSON filename
		 */
		public function ConvertShapeFileToGeoJSON($sFilter = "SELECT * FROM CrabAdr WHERE GEMEENTE = 'Izegem' ORDER BY STRAATNM") {
			
			// Disabled for security?
			$sDownloadDirectory = str_replace('\\', '/', dirname(__FILE__).'/download');
			$aDisabledFunctions = explode(',', ini_get('disable_functions'));
			
			if(in_array('shell_exec', $aDisabledFunctions) == true) {
				self::Trace('Failure: shell_exec is disabled (PHP disable_functions)');
				throw new \Exception('Failure: shell_exec is disabled (PHP disable_functions)');
			}
			
			// Where were contacts extracted (see Download()) 
			$sFileName_GeoJSON = $sDownloadDirectory.'/output.geojson';
			$sFileName_ShapeFile = $sDownloadDirectory.'/Shapefile/CrabAdr.shp';

			if( file_exists($sFileName_ShapeFile) == false ) {
				self::Trace('Failure: no shapefile found at '.$sFileName_ShapeFile);
				throw new \Exception('Failure: no shapefile found at '.$sFileName_ShapeFile);
			}

			// Convert shapefile to GeoJSON (CSV is more compact, but caused issues)
			// Source data is EPSG:31370 (Belgian Lambert 72), convert to EPSG:3857. 
			// Use CRS definitions here, because sometimes ogr2ogr can't find the shorthand references.
			// GeoJSON is usually EPSG:4326, but not in our case.
			$sRunOGR2OGR = 'ogr2ogr '.
				'-f GeoJSON "'.$sFileName_GeoJSON.'" "'.$sFileName_ShapeFile.'" '.
				'-s_srs "+proj=lcc +lat_1=51.16666723333333 +lat_2=49.8333339 +lat_0=90 +lon_0=4.367486666666666 +x_0=150000.013 +y_0=5400088.438 +ellps=intl +towgs84=-106.8686,52.2978,-103.7239,0.3366,-0.457,1.8422,-1.2747 +units=m +no_defs" '.
				'-t_srs "+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs" '.
				($sFilter != '' ? '-sql "'.$sFilter.'" ' : ''); 
			
			self::Trace('Reprojecting dataset to EPSG:3857 and applying this filter: '. $sRunOGR2OGR);		 

			exec( $sRunOGR2OGR ); 

			// Check if everything went well
			if(file_exists($sFileName_GeoJSON) == false) {
				self::Trace('Failure: unable to convert shapefile to GeoJSON');
				throw new \Exception('Failure: unable to convert shapefile to GeoJSON');
			}
			
			return $sFileName_GeoJSON;
		
		}
		
		/**
		 * Imports the features in the GeoJSON file
		 *
		 * @param String $sFileName_GeoJSON Filename
		 *
		 * @return void
		 */
		public function ImportFromGeoJSON($sFileName_GeoJSON) {
	
			$aJsonDecoded = json_decode(file_get_contents($sFileName_GeoJSON), true);
			
			// Fetch cities
			$oFilter_CrabCities = \DBObjectSearch::FromOQL('SELECT CrabCity');
			$oSet_CrabCities = new \CMDBObjectSet($oFilter_CrabCities);
			self::Trace('# known cities: '.$oSet_CrabCities->Count());
						
			// The cities object set is used to:
			$aCities_name = [];
			while($oObj = $oSet_CrabCities->Fetch()) {
				$aCities_name[$oObj->Get('name')] = $oObj; 
			}
			
			// Fetch streets
			$oFilter_CrabStreets = \DBObjectSearch::FromOQL('SELECT CrabStreet');
			$oSet_CrabStreets = new \CMDBObjectSet($oFilter_CrabStreets);
			self::Trace('# known streets: '.$oSet_CrabStreets->Count());
			
			// The streets object set is used to:
			// 1) check if a street exists
			// 2) get it's ID by supplying street name
			// For convenience, it's turned into an array.
			$aStreets_crab_id = [];
			while($oObj = $oSet_CrabStreets->Fetch()) {
				$aStreets_crab_id['crab_id::'.$oObj->Get('crab_id')] = $oObj;
			}
			
			// Fetch existing addresses
			$oFilter_CrabAddresses = \DBObjectSearch::FromOQL('SELECT CrabAddress');
			$oSet_CrabAddresses = new \CMDBObjectSet($oFilter_CrabAddresses);
			self::Trace('# known addresses: '.$oSet_CrabAddresses->Count());
			
			// To see if it exists based on crab_id and NOT having to run thousands of queries: index the CrabAddress objects.
			// It also seems like CMDBObjectSet does not support removal.
			// @todo Check if variable can be unset after creating the array
			$aAddresses_crab_id = [];
			while($oObj = $oSet_CrabAddresses->Fetch()) {
				$aAddresses_crab_id['crab_id::'.$oObj->Get('crab_id')] = $oObj;
			}
			
			// Let's start processing.		 
			$iAddress = 1;
			
			// Used to map properties to CrabAddress
			$aMapping = [
				'ID' => 'crab_id',
				'STRAATNMID' => 'street_id', // CrabStreet street_id <=> CrabStreet (iTop) id
				'HUISNR' => 'house_number',
				'APPTNR' => 'apartment_number',
				'BUSNR' => 'sub_number',
				'STATUS' => 'status',
				'GEOM' => 'geom'
			];
			
			foreach ($aJsonDecoded['features'] as $v ) {
				
				// Some values are set manually.
				// Geometry comes from the geometry of the GeoJSON, not from properties.
				// In list? Let's assume it's in use 
				$v['properties']['STATUS'] = self::$aCrab_Status['in_use'];
				$v['properties']['GEOM'] = 'POINT('. implode(' ', $v['geometry']['coordinates']) .')';
 
				if($iAddress == 1) {
					// Output available properties before processing first address (with above properties added)
					$aProperties = array_keys($v['properties']);
					self::Trace('Mapped GeoJSON properties: '.implode(', ', array_intersect($aProperties, $aMapping)));
					self::Trace('Unmapped GeoJSON properties: '.implode(', ', array_diff($aProperties, $aMapping)));
				}
				
				// City exists?
				$sCityName = $v['properties']['GEMEENTE'];
				if(in_array($sCityName, array_keys($aCities_name)) == false) {
					self::Trace('Create CrabCity: '. $sCityName);
					$oCity = new \CrabCity();
					$oCity->Set('name', $sCityName);
					$oCity->DBInsert();
					$aCities_name[$oCity->Get('name')] = $oCity;
				}
				
				$oStreet = new \CrabStreet();
				$oStreet->Set('name', $v['properties']['STRAATNM']);
				$oStreet->Set('crab_id', $v['properties']['STRAATNMID']);
				$oStreet->Set('city_id', $aCities_name[$sCityName]->GetKey());
				$oStreet->Set('status', self::$aCrab_Status['in_use']);
				
				$aDebugInfo = [];
				$oAddress = new \CrabAddress();
				foreach($aMapping as $sAtt_GeoJSON => $sAtt_iTop) {
					$sValue = $v['properties'][$sAtt_GeoJSON];
					$oAddress->Set($sAtt_iTop, $sValue);
					$aDebugInfo[] = $sValue;
				}
				
				// Must fix: street_id is currently the crab_id; but it has to be translated into the iTop internal ID
				$oAddress->Set('street_id', $aStreets_crab_id['crab_id::'.$oAddress->Get('street_id')]->GetKey());
			
				self::Trace('Processing GeoJSON Feature '.sprintf('%08d', $iAddress ).' | '.implode(' | ', $aDebugInfo));
				
				// Street exists in array? (crab_id is unique)
				// If not, create. Assume no changes are made to street names.
				if(array_key_exists('crab_id::'.$oStreet->Get('crab_id'), $aStreets_crab_id) == false ) {
					
					print_r(array_keys($aStreets_crab_id));
					
					self::Trace('Create CrabStreet: '.$oStreet->Get('name').' - Crab ID '.$oStreet->Get('crab_id'));
					$oStreet->DBInsert();
					
					// Add. No duplicates.
					$aStreets_crab_id['crab_id::'.$oStreet->Get('crab_id')] = $oStreet;
					
				}
				
				// Crab address exists in array?
					
				// Exists in iTop? 
				if(array_key_exists('crab_id::'.$oAddress->Get('crab_id'), $aAddresses_crab_id) == false) {
					
					self::Trace('Create CrabAddress: ' . $v['properties']['STRAATNM'] . ' ' . $v['properties']['HUISNR'] . $v['properties']['APPTNR'] . $v['properties']['BUSNR']);
					$oAddress->DBInsert();
					
				}
				else {
					
					// Update? Only request if necessary.
					$oExistingAddress = $aAddresses_crab_id['crab_id::'.$oAddress->Get('crab_id')];
					
					if($oAddress->Fingerprint(['id']) != $oExistingAddress->Fingerprint(['id'])) {
						
						self::Trace('Update CrabAddress: ' . $v['properties']['STRAATNM'] . ' ' . $v['properties']['HUISNR'] . $v['properties']['APPTNR'] . $v['properties']['BUSNR']);
						
						// Initial idea was to simply use $oAddress->SetKey() to set existing CrabAddress and run DBUpdate() was the initial thought.
						// It won't work though: "DBUpdate: could not update a newly created object, please call DBInsert instead"
						// The check happens against a property that can not be overwritten (iTop 2.6.1)
						// Perhaps there is or will be a better way?
						foreach(array_values($aMapping) as $sAttCode) {
							$oExistingAddress->Set($sAttCode, $oAddress->Get($sAttCode));
						}
						
						// Update existing CrabAddress
						$oExistingAddress->DBUpdate();
						
					}
					
					// Unet, no longer necessary, crab_id is unique.
					// Reduce memory, speed up.
					unset($aAddresses_crab_id['crab_id::'.$oAddress->Get('crab_id')]);
					
				}
				
				$iAddress += 1;
				
			} 

			// Above, all the CrabAddress objects which were found, have been unset in $aAddresses_crab_id
			// If any objects are still in there, it may be assumed they're no longer valid.
			// They were not processed or did not have a status that seems to matter.
			// Set status to 'not_found' (not an official Crab status)
			
			foreach( $aAddresses_crab_id as $sCrabId => $oAddress ) {
					
				// Update required?
				if($oAddress->Get('status') != self::$aCrab_Status['not_found'] ) {
							
					$oAddress->Set('status', self::$aCrab_Status['not_found']);
					$oAddress->DBUpdate();
					
				}
				
			}
			
			self::Trace('Finished processing GeoJSON.');
			
			// Recursive delete everything
			self::RecursiveRemoveDirectory(dirname(__FILE__). '/shapefile');

			self::Trace('Cleaned up shapefile directory (all geodata including converted GeoJSON).');
			
		}		
		
		/**
		 * Recursively deletes a directory
		 *
		 * @param \String $sDirectory Name of directory
		 */
		private function RecursiveRemoveDirectory($sDirectory)
		{
			
			if(file_exists($sDirectory) == false) {
				return;
			}
			
			foreach(glob( $sDirectory.'/*') as $sFile)
			{
				if(is_dir($sFile)) { 
					self::RecursiveRemoveDirectory($sFile);
				} else {
					unlink($sFile);
				}
			}
			rmdir($sDirectory);
		}
		
	}
	
