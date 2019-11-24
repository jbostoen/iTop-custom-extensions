<?php 

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-11 20:40:30
 *
 * Definition of Address
 */
 
namespace jb_itop_extensions\crab;

	/**
	 * Class CrabImportHandler. Contains methods to import addresses.
	 */
	class CrabImportHandler {
		
		/**
		 * @var \Array $aCrab_Status Crab Status codes
		 */
		private $aCrab_Status = [
			// Official Crab Status
			'proposed' => 1,
			'reserved' => 2,
			'in_use' => 3,
			'no_longer_in_use' => 4,
			
			// Unofficial: not found in dataset
			'not_found' => 99
		];
		
		/**
		 * @var \String $sDownloadDirectory Download directory
		 */
		private $sDownloadDirectory = '';
		
		/**
		 *
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			
			$this->sDownloadDirectory = str_replace('\\', '/', dirname(__FILE__).'/download');
			
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
			$sTargetFileName = $this->sDownloadDirectory.'/Crab_Adressenlijst_Shapefile.zip';
			
			// Recursive delete everything
			$this->RecursiveRemoveDirectory($this->sDownloadDirectory);
			$oOldMask = umask(0);
			mkdir($this->sDownloadDirectory, '0755');
			umask($oOldMask);

			$this->Trace('Downloading file...');
			$this->Trace('. From '.$sURL);
			$this->Trace('. To '.$sTargetFileName);
			
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
				$this->Trace('Failure: missing php-zip?');
				throw new \Exception('Failure: missing php-zip?');
			}
			
			$this->Trace('Unzipping file...');

			$zip = new \ZipArchive;
			$res = $zip->open($sTargetFileName);
			
			if ($res === true) {
				
				$zip->extractTo($this->sDownloadDirectory);
				$zip->close();
			  			  
			} 
			else {
				// Fail
				$this->Trace('Unable to unzip file to directory '.$this->sDownloadDirectory);
				throw new \Exception('Unable to unzip file to directory '.$this->sDownloadDirectory);
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
			$aDisabledFunctions = explode(',', ini_get('disable_functions'));
			
			if(in_array('shell_exec', $aDisabledFunctions) == true) {
				$this->Trace('Failure: shell_exec is disabled (PHP disable_functions)');
				throw new \Exception('Failure: shell_exec is disabled (PHP disable_functions)');
			}
			
			// Where were contacts extracted (see Download()) 
			$sFileName_GeoJSON = $this->sDownloadDirectory.'/output.geojson';
			$sFileName_ShapeFile = $this->sDownloadDirectory.'/Shapefile/CrabAdr.shp';

			if( file_exists($sFileName_ShapeFile) == false ) {
				$this->Trace('Failure: no shapefile found at '.$sFileName_ShapeFile);
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
			
			$this->Trace('Reprojecting dataset to EPSG:3857 and applying this filter: '. $sRunOGR2OGR);		 

			exec( $sRunOGR2OGR ); 

			// Check if everything went well
			if(file_exists($sFileName_GeoJSON) == false) {
				$this->Trace('Failure: unable to convert shapefile to GeoJSON');
				throw new \Exception('Failure: unable to convert shapefile to GeoJSON');
			}
			
			return;
		
		}
		
		/**
		 * Imports the features in the GeoJSON file
		 *		 *
		 * @return void
		 */
		public function ImportFromGeoJSON() {
	
			$sFileName_GeoJSON = $this->sDownloadDirectory.'/output.geojson';
			$aJsonDecoded = json_decode(file_get_contents($sFileName_GeoJSON), true);
			
			$iNewCities = 0;
			$iNewStreets = 0;
			$iNewAddresses = 0;
			
			// Fetch cities
			$oFilter_CrabCities = \DBObjectSearch::FromOQL('SELECT CrabCity');
			$oSet_CrabCities = new \CMDBObjectSet($oFilter_CrabCities);
			$this->Trace('# known cities: '.$oSet_CrabCities->Count());
						
			// The cities object set is used to:
			$aCities_name = [];
			while($oObj = $oSet_CrabCities->Fetch()) {
				$aCities_name[$oObj->Get('name')] = $oObj; 
			}
			
			// Fetch streets
			$oFilter_CrabStreets = \DBObjectSearch::FromOQL('SELECT CrabStreet');
			$oSet_CrabStreets = new \CMDBObjectSet($oFilter_CrabStreets);
			$this->Trace('# known streets: '.$oSet_CrabStreets->Count());
			
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
			$this->Trace('# known addresses: '.$oSet_CrabAddresses->Count());
			
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
				$v['properties']['STATUS'] = $this->aCrab_Status['in_use'];
				$v['properties']['GEOM'] = 'POINT('. implode(' ', $v['geometry']['coordinates']) .')';
 
				if($iAddress == 1) {
					// Output available properties before processing first address (with above properties added)
					$aProperties = array_keys($v['properties']);
					$this->Trace('Mapped GeoJSON properties: '.implode(', ', array_intersect($aProperties, $aMapping)));
					$this->Trace('Unmapped GeoJSON properties: '.implode(', ', array_diff($aProperties, $aMapping)));
				}
				
				// City exists?
				$sCityName = $v['properties']['GEMEENTE'];
				if(in_array($sCityName, array_keys($aCities_name)) == false) {
					$this->Trace('Create CrabCity: '. $sCityName);
					$oCity = new \CrabCity();
					$oCity->Set('name', $sCityName);
					$oCity->DBInsert();
					$aCities_name[$oCity->Get('name')] = $oCity;
					
					$iNewCities += 1;
				}
				
				$oStreet = new \CrabStreet();
				$oStreet->Set('name', $v['properties']['STRAATNM']);
				$oStreet->Set('crab_id', $v['properties']['STRAATNMID']);
				$oStreet->Set('city_id', $aCities_name[$sCityName]->GetKey());
				$oStreet->Set('status', $this->aCrab_Status['in_use']);
				
				$aDebugInfo = [];
				$oAddress = new \CrabAddress();
				foreach($aMapping as $sAtt_GeoJSON => $sAtt_iTop) {
					$sValue = $v['properties'][$sAtt_GeoJSON];
					$oAddress->Set($sAtt_iTop, $sValue);
					$aDebugInfo[] = $sValue;
				}
				
				// Must fix: street_id is currently the crab_id; but it has to be translated into the iTop internal ID
				$oAddress->Set('street_id', $aStreets_crab_id['crab_id::'.$oAddress->Get('street_id')]->GetKey());
			
				$this->Trace('Processing GeoJSON Feature '.sprintf('%08d', $iAddress ).' | '.implode(' | ', $aDebugInfo));
				
				// Street exists in array? (crab_id is unique)
				// If not, create. Assume no changes are made to street names.
				if(array_key_exists('crab_id::'.$oStreet->Get('crab_id'), $aStreets_crab_id) == false ) {
					
					$this->Trace('Create CrabStreet: '.$oStreet->Get('name').' - Crab ID '.$oStreet->Get('crab_id'));
					$oStreet->DBInsert();
					
					// Add. No duplicates.
					$aStreets_crab_id['crab_id::'.$oStreet->Get('crab_id')] = $oStreet;
					
					$iNewStreets += 1;
					
				}
				
				// Crab address exists in array?
					
				// Exists in iTop? 
				if(array_key_exists('crab_id::'.$oAddress->Get('crab_id'), $aAddresses_crab_id) == false) {
					
					$this->Trace('Create CrabAddress: ' . $v['properties']['STRAATNM'] . ' ' . $v['properties']['HUISNR'] . $v['properties']['APPTNR'] . $v['properties']['BUSNR']);
					$oAddress->DBInsert();
					
					$iNewAddresses += 1;
					
				}
				else {
					
					// Update? Only request if necessary.
					$oExistingAddress = $aAddresses_crab_id['crab_id::'.$oAddress->Get('crab_id')];
					
					if($oAddress->Fingerprint(['id']) != $oExistingAddress->Fingerprint(['id'])) {
						
						$this->Trace('Update CrabAddress: ' . $v['properties']['STRAATNM'] . ' ' . $v['properties']['HUISNR'] . $v['properties']['APPTNR'] . $v['properties']['BUSNR']);
						
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
				if($oAddress->Get('status') != $this->aCrab_Status['not_found'] ) {
							
					$oAddress->Set('status', $this->aCrab_Status['not_found']);
					$oAddress->DBUpdate();
					
				}
				
			}
			
			$this->Trace('Finished processing GeoJSON.');
			
			// Recursive delete everything
			$this->RecursiveRemoveDirectory($this->sDownloadDirectory);

			$this->Trace('Cleaned up download directory (all geodata including converted GeoJSON).');
			
			$this->Trace('New items:');
			$this->Trace('- ' . $iNewCities . ' cities');
			$this->Trace('- ' . $iNewStreets. ' streets');
			$this->Trace('- '.  $iNewAddresses . ' addresses');
			
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
					$this->RecursiveRemoveDirectory($sFile);
				} else {
					unlink($sFile);
				}
			}
			rmdir($sDirectory);
		}
		
	}
	
