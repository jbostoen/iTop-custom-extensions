<?php 

	/*
	* Crab Import. 
	*
	* Place under <iTopDir>/web/cron/crabsync
	* Place iTop Connector under <iTopDir>/itop-connector
	*
	* @version 2019-03-28 15:46
	*
	*/
	error_reporting(E_ALL);

	defined("APPDIR") or define("APPDIR",  dirname(dirname(dirname( str_replace("\\", "/", __FILE__ ) ))));	
	
	require_once( APPDIR . "/itop-connector/connector.php" );	

	// No time limit 
	// First time will take long time.
	set_time_limit(0);

	// Increase memory limit
	ini_set("memory_limit", "256M");		
	
	abstract class iTop_CrabImport_Address_Status {
		
		// Official CRAB status
		const proposed = 1;
		const reserved = 2;
		const in_use = 3;
		const no_longer_in_use = 4;
		const unofficial = 5;
		
		// Unofficial
		const not_found = 99; 
		
	}	
	
	/**
	 *  Class iTop_CrabImport_Address
	 */
	class iTop_CrabImport_Address {
		
		/** 
		 *  @var Float $f_crab_id Float. ID CRAB Adres
		 */
		public $f_crab_id;
		
		/** 
		 *  @var Float $f_crab_idStraatnaam Float. ID CRAB Straatnaam
		 */
		public $f_crab_idStraatnaam;
		
		/**
		 * @var String $s_street_name String. Street name.
		 */
		public $s_street_name;
		
		
		/**
		 * @var String $iHuisnummer String. House number (no sub addresses) ( bis: "Meensesteenweg 376_2" )
		 */
		public $s_house_number;
				
		/**
		 * @var String $s_apartment_number String.  
		 */
		public $s_apartment_number;
				
		/**
		 * @var String $s_sub_number String. Used for mailboxes in same building.
		 */
		public $s_sub_number;
		
		/**
		 * @var iTop_CrabImport_Address_Status $iHuisnummer Integer. Status
	     * 
		 */
		public $i_status;		
		
		/**
		 *  
		 *  Constructor.
		 *  
		 *  @param Object Object. Will be used to construct this object.
		 *  
		 *  @return void 
		 *   
		 */
		function __construct( $oData = [] ) {
			
			$aData = (Array)$oData;
			
			foreach( $aData as $k => $v ) {
					
				switch( $k ) {
					
					case "ID":
						$this->f_crab_id = $v;
					
					case "STRAATNM": 
						$this->s_street_name = $v; 
						break; 
						
					case "STRAATNMID": 
						$this->f_crab_idStraatnaam = $v; 
						break; 
						
					case "HUISNR":
						$this->s_house_number = $v; 
						break;
						
					case "APPTNR": 
						$this->s_apartment_number = $v; 
						break;
						
					case "BUSNR": 
						$this->s_sub_number = $v; 
						break;
						

					case "STATUS": // not in GeoJSON
						$this->i_status = $v;
						break;			
			
					case "GEMEENTE":
					case "HERKOMST": 
					case "HNRLABEL":
					case "NISCODE":
					case "POSTCODE":
						break;
						
					default: 
						echo "missing:" .$k;
						break;
				}
				
			}
		}
	}
	
	/**
	 *  Class iTop_CrabImport. Defines functions to import addresses.
	 */
	class iTop_CrabImport {
		  		
		/**
		 *  
		 *  Constructor
		 *  
		 *   
		 *  @return void
		 */
		function __construct() {
			
		}
		
		/**
		 *  
		 *  Fetches CRAB Address list. By default, you can't get a subset unless you authenticate. 
		 *  For quick implementation, let's skip this. The file is currently (27th of September 2018) around 150 MB when zipped.
		 *  Unpacked we get 1 GB.
		 *   
		 *  @return Array crabStraat
		 *   
		 */
		public function download(   ) {

			$sURL = "https://downloadagiv.blob.core.windows.net/crab-adressenlijst/Shapefile/CRAB_Adressenlijst_Shapefile.zip";
			$sFileName = "CRAB_Adressenlijst_Shapefile.zip";
			
			
 			$ch = curl_init();
			$downloadFile = fopen( $sFileName , "w" );
			curl_setopt($ch, CURLOPT_URL, $sURL);
			 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
			curl_setopt($ch, CURLOPT_NOPROGRESS, false);
			curl_setopt($ch, CURLOPT_FILE, $downloadFile );
			curl_exec($ch);
			curl_close($ch);		

			echo "Downloaded file... ".PHP_EOL;			

			// Recursive delete everything
			recursiveRemoveDirectory(  dirname(__FILE__). "/shapefile");
			
			echo "Unzip file..." .PHP_EOL;

			$zip = new ZipArchive;
			$res = $zip->open( $sFileName );
					
			
			if ($res === true) {
				
			  $zip->extractTo(  dirname(__FILE__). "/shapefile");
			  $zip->close();
			  			  
			} 
			else {
				// Fail
				
			}
			
		}
		 
		/**
		* Imports the shapefile. Runs a query to only get data which we're interested in using OGR. Next, it imports this data into iTop.
		*/
		public function importFromShapeFile() {
			
			// Where were contacts extracted (see Download()) 
			$sDirProcess = str_replace("\\", "/", dirname(__FILE__) ) ."/shapefile/Shapefile";

			if( file_exists( $sDirProcess."/CrabAdr.shp") == false ) {
				die("No shapefile at ".$sDirProcess."/CrabAdr.shp");
			}

			// Convert shapefile to GeoJSON (CSV is more compact but caused issues)
			$sRunOGR2OGR = 'ogr2ogr -f GeoJSON "'.$sDirProcess.'/output.geojson" "'.$sDirProcess.'/CrabAdr.shp" -sql "SELECT * FROM CrabAdr WHERE GEMEENTE = \'Izegem\' ORDER BY STRAATNM" '; 

			echo "Convert using OGR: ". $sRunOGR2OGR . PHP_EOL;			 

			shell_exec( $sRunOGR2OGR ); 

			if( file_exists( $sDirProcess."/output.geojson") == false ) {
				die("Unable to convert shapefile to GeoJSON.");
			}
	
	
			$oRest = new iTop_Rest();

			// For debugging
			$oRest->showRequest = true;
			$oRest->showResponse = true;
			
			$aJsonDecoded = json_decode( file_get_contents($sDirProcess."/output.geojson"), TRUE);
			
			// Save some requests, rather than doing it individually
			
			// Select streets
			$aExistingStreetItems = $oRest->get([
				"key" => "SELECT CrabStreet",
				"onlyValues" => true
			]);
			
			
			// Map street names -> iTop internal ID 
			$aStreets = [];
			foreach( $aExistingStreetItems as $oItem ) {

				$aStreets[ $oItem["fields"]["name"] ] = $oItem;
				
			}
			 
			// For later use, to see if crab_id for street exists, without additional queries
			$aExistingStreetItems = array_map( function( $v ) { 
				return $v["fields"]["crab_id"]; 
			} , $aExistingStreetItems );
				
			// Select addresses
			$aExistingAddressItems = $oRest->get([
				"key" => "SELECT CrabAddress",
				"onlyValues" => true
			]);
			
			// For later use, to see if crab_id for address exists, without additional queries
			$aExistingAddressItemsByCrabId = [];
			
			foreach( $aExistingAddressItems as $aExistingAddressItem ) {
				$aExistingAddressItemsByCrabId[ $aExistingAddressItem["fields"]["crab_id"] ] = $aExistingAddressItem;
			}
			
			
			 
			$iAddress = 1;
			
			foreach ($aJsonDecoded["features"] as $v ) {
				
				  
				// Combine values, create address object

				// in list? Let's assume it's in use 
				$v["properties"]["STATUS"] = iTop_CrabImport_Address_Status::in_use; 
 
				$oAddress = new iTop_CrabImport_Address( $v["properties"] );	
				
				
				echo "Process GeoJSON Feature " . sprintf("%08d" , $iAddress ) . " | " . $oAddress->f_crab_id . " | " .  $oAddress->s_street_name. " | "  . $oAddress->s_house_number . " | " . $oAddress->s_apartment_number . " | " . $oAddress->s_sub_number . PHP_EOL;
				
				
				// ///// Sync streets
				
				// Street exists in array? (col 2 = STRAATNM)
				if( in_array( $oAddress->f_crab_idStraatnaam , $aExistingStreetItems ) == false ) {
					
					echo "-- Create street" . PHP_EOL;
				 
					// Create new street
					$aItems = $oRest->create([
						"comment" => "Crab Sync",
						"class" => "CrabStreet",
						"fields" => [
							"crab_id" => $oAddress->f_crab_idStraatnaam,
							"name" => $oAddress->s_street_name,
							"status" => iTop_CrabImport_Address_Status::in_use // 'in gebruik' / 'in use'. Could be fetched through an API but not through this CSV.
						],
						"onlyValues" => true
							
					]); 
					
					
					
					if( count($aItems) != 1 ) {
						throw new Exception("Unexpected error - could not create street? ". json_encode($oAddress) );
												
					}
					else {
						
						
						// Just created, now cache.
						$aExistingStreetItems[] = $oAddress->f_crab_idStraatnaam;
						$aStreets[$oAddress->s_street_name] = $aItems[0];  
						
					} 
					
				}
				else {
					
					// Update? 
					
				}
				 
				
				// ///// Sync address
					
				// Exists in iTop? 
				if( in_array( $oAddress->f_crab_id, array_keys($aExistingAddressItemsByCrabId) ) == false ) {
					
					// echo "Create ". $oAddress->s_street_name . PHP_EOL ;
					 
					echo "-- Create address" . PHP_EOL;
					
					// Create new street
					$oResult_Create_Address = $oRest->create([
						"comment" => "Crab Sync",
						"class" => "CrabAddress",
						"fields" => [
							"crab_id" => $oAddress->f_crab_id,
							"street_id" => $aStreets[$oAddress->s_street_name]["key"], // This is NOT the Crab id for this street but the iTop ID!
							"house_number" => $oAddress->s_house_number,
							"apartment_number" => $oAddress->s_apartment_number,
							"sub_number" => $oAddress->s_sub_number,
							"status" => $oAddress->i_status // 'in gebruik' / 'in use'. Could be fetched through an API but not through this CSV.
						],
						"onlyValues" => true
							
					]);  
					 
					
				}
				else {
					
					// Update? Only request if necessary.
					$aCrabItem = $aExistingAddressItemsByCrabId[ $oAddress->f_crab_id ]["fields"];
					
					if( 
						$aCrabItem["street_id"] != $aStreets[$oAddress->s_street_name]["key"] || 
						$aCrabItem["house_number"] != $oAddress->s_house_number || 
						$aCrabItem["apartment_number"] != $oAddress->s_apartment_number || 
						$aCrabItem["sub_number"] != $oAddress->s_sub_number || 
						$aCrabItem["status"] != $oAddress->i_status 
					) {
						
						// Update required
						$oResult_Update_Address = $oRest->update([
							"comment" => "Crab Sync",
							"class" => "CrabAddress",
							"key" => $aExistingAddressItemsByCrabId[ $oAddress->f_crab_id ]["key"],
							"fields" => [
								"street_id" => $aStreets[$oAddress->s_street_name]["key"], // This is NOT the Crab id for this street but the iTop ID!
								"house_number" => $oAddress->s_house_number,
								"apartment_number" => $oAddress->s_apartment_number,
								"sub_number" => $oAddress->s_sub_number,
								"status" => $oAddress->i_status // 'in gebruik' / 'in use'. Could be fetched through an API but not through this CSV.
							],
							"onlyValues" => true
								
						]); 
					
					}
					
					// Unset, no longer necessary, crab_id is unique.
					// Reduce memory, speed up.
					unset( $aExistingAddressItemsByCrabId[ $oAddress->f_crab_id ] );
					
					
				}

				
				$iAddress += 1;
				
			} 

			// Above, we have unset all the $aExistingAddressItems we found. 
			// In $aExistingAddressItemsByCrabId, we might have IDs (iTop) of addresses which are no longer valid.
			// They were not processed or did not have a status we care about.
			// We could set them to 'delete'.
			
			foreach( $aExistingAddressItemsByCrabId as $fCrabId => $aExistingAddressItem ) {
					
				// Update required?
				if( $aExistingAddressItem["fields"]["status"] != iTop_CrabImport_Address_Status::not_found ) {
					
					echo "remove";
						
					$oResult_Update_Address = $oRest->update([
						"comment" => "Crab Sync",
						"class" => "CrabAddress",
						"key" => $fCrabId,
						"fields" => [
							"status" => iTop_CrabImport_Address_Status::not_found
						],
						"onlyValues" => true
							
					]);
					
				}
				
			}
			
			
			echo "Done processing GeoJSON" . PHP_EOL;			
			
		}		
		
	}
	
	// Recursive remove dir
	/**
	* Recursively deletes a directory
	*
	* @param String $sDirectory Name of directory
	*/
	function recursiveRemoveDirectory($sDirectory)
	{
		foreach(glob("{$sDirectory}/*") as $sFile)
		{
			if(is_dir($sFile)) { 
				recursiveRemoveDirectory($sFile);
			} else {
				unlink($sFile);
			}
		}
		rmdir($sDirectory);
	}


	$oCrabImport = new iTop_CrabImport();


	header("Content-Type: text/plain");

	echo "Download shapefile ...".PHP_EOL;

	// Download Shapefile	
	// $oCrabImport->download();

	echo "Process shapefile ..." . PHP_EOL;

	// Import Crab from sync
	$oCrabImport->importFromShapeFile();
	
	 
	
	echo "Done"  . PHP_EOL;
	
	