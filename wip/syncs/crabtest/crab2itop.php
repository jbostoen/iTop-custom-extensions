<?php 


	/*
	* Crab Import 
	*/
 
	error_reporting(E_ALL);
 
			
	// Require iTop Connector		
	require_once("../itop-connector/connector.php");

	// No time limit 
	set_time_limit(0);
	
	
	 
	
	
	
	/**
	 *  Class crabHuisnummer
	 */
	class crabAddress {
		
		/** 
		 *  @var Float $fCrabId Float. ID CRAB Adres
		 */
		public $fCrabId;
		
		
		/** 
		 *  @var Float $fCrabIdStraatnaam Float. ID CRAB Straatnaam
		 */
		public $fCrabIdStraatnaam;
		
		/**
		 * @var String $sStraatnaam String. Street name.
		 */
		public $sStraatnaam;
		
		
		
		/**
		 * @var String $iHuisnummer String. House number (no sub addresses) ( bis: "Meensesteenweg 376_2" )
		 */
		public $sHuisnummer;
		
		
		/**
		 * @var String $sAppartementnummer String.  
		 */
		public $sAppartementnummer;
		
		
		/**
		 * @var String $sBusnummer String. Used for mailboxes in same building.
		 */
		public $sBusnummer;
		
		
		
		/**
		 * @var Integer $iHuisnummer Integer. Status
	     * 
		 * 1 proposed
		 * 2 reserved
		 * 3 in use
		 * 4 no longer in use 
		 * 5 unofficial
		 *
		 */
		public $iStatusHuisnummer;
		
		 
		
		
		
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
							$this->fCrabId = $v;
						
						case "STRAATNM": 
							$this->sStraatnaam = $v; 
							break; 
							
						case "STRAATNMID": 
							$this->fCrabIdStraatnaam = $v; 
							break; 
							
						case "HUISNR":
							$this->sHuisnummer = $v; 
							break;
							
						case "APPTNR": 
							$this->sAppartementnummer = $v; 
							break;
							
						case "BUSNR": 
							$this->sBusnummer = $v; 
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
			
			
 /*			$ch = curl_init();
			$downloadFile = fopen( $sFileName , "w" );
			curl_setopt($ch, CURLOPT_URL, $sURL);
			 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
			curl_setopt($ch, CURLOPT_NOPROGRESS, false);
			curl_setopt( $ch, CURLOPT_FILE, $downloadFile );
			curl_exec($ch);
			curl_close($ch);
	*/		
			
			// Recursive delete everything
			recursiveRemoveDirectory(  dirname(__FILE__). "/shapefile");
			
			$zip = new ZipArchive;
			$res = $zip->open( $sFileName );
			
			
			
			if ($res === TRUE) {
				
			  $zip->extractTo(  dirname(__FILE__). "/shapefile");
			  $zip->close();
			  
			  
			} 
			else {
				// Fail
				
			}


			 

		
			
		}
		 
		
		
		
		
		
		public function importFromShapeFile() {
			
			// Where were contacts extracted (see Download())
			$sDirProcess = dirname(__FILE__)."/shapefile/Shapefile";
			
			// Convert shapefile to GeoJSON (CSV is more compact but caused issues)
			$sRunOGR2OGR = 'ogr2ogr.exe -f GeoJSON "'.$sDirProcess.'/output.geojson" "'.$sDirProcess.'/CrabAdr.shp" -sql "SELECT * FROM CrabAdr WHERE GEMEENTE = \'Izegem\' ORDER BY STRAATNM" '; 
			 
	//		shell_exec( $sRunOGR2OGR ); 
			
			ob_flush();
			flush();
			
			
			$oRest = new iTop_Rest();
			 
			
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
			// $aExistingStreetItems = array_map( function( $v ) { return $v["fields"]["crab_id"]; } , $aExistingStreetItems );
			
			
			// Select addresses
			$aExistingAddressItems = $oRest->get([
				"key" => "SELECT CrabAddress",
				"onlyValues" => true
			]);
			
			// For later use, to see if crab_id for address exists, without additional queries
			$aExistingAddressItems = array_map( function( $v ) { return $v["fields"]["crab_id"]; } , $aExistingAddressItems );
			
			
			
			
		 

			foreach ($aJsonDecoded["features"] as $v ) {
				 
			 
	
				
				// Combine values, create address object 
				$oAddress = new crabAddress( $v["properties"] );	
				
				
				
				// ///// Straatnaam
				
				// Street exists in array? (col 2 = STRAATNM)
				if( in_array( $oAddress->sStraatnaam , $aExistingStreetItems ) == false ) {
				 
					// Create new street
					$aItems = $oRest->create([
						"comment" => "Crab Sync",
						"class" => "CrabStreet",
						"fields" => [
							"crab_id" => $oAddress->fCrabIdStraatnaam,
							"name" => $oAddress->sStraatnaam,
							"status" => 3 // 'in gebruik' / 'in use'. Could be fetched through an API but not through this CSV.
						],
						"onlyValues" => true
							
					]); 
					
					if( count($aItems) != 1 ) {
						throw new Exception("Unexpectd error - could not create street?");
												
					}
					else {
						
						
						// Just created, now cache.
						$aExistingStreetItems[] = $aItems[0]["fields"]["crab_id"];
							
					} 
					
				}
				else {
					
					// Update?
					
				}
				 
				
				// ///// Adres
					
				// Exists in iTop? 
				if( in_array( $oAddress->fCrabId, $aExistingAddressItems ) == false ) {
					
					// echo "Create ". $oAddress->sStraatnaam . PHP_EOL ;
					 
					// Create new street
					$oResult_Create_Address = $oRest->create([
						"comment" => "Crab Sync",
						"class" => "CrabAddress",
						"fields" => [
							"crab_id" => $oAddress->fCrabId,
							"street_id" => $aStreets[$oAddress->sStraatnaam]["key"], // This is NOT the Crab id for this street but the iTop ID!
							"house_number" => $oAddress->sHuisnummer,
							"appartement_number" => $oAddress->sAppartementnummer,
							"sub_number" => $oAddress->sBusnummer,
							"status" => 3 // 'in gebruik' / 'in use'. Could be fetched through an API but not through this CSV.
						],
						"onlyValues" => true
							
					]);  
					 
					
				}
				else {
					
					// Update?
					
					
					
					// Unset, no longer necessary, crab_id is unique.
					unset( $aExistingAddressItems[ $oAddress->fCrabId ] );
					
					
				}

				
			} 

			
			
			echo "Done";
			
		 
	
			
			
		}
		
		
		
		
		
	}
	
	
	
	// Recursive remove dir
	function recursiveRemoveDirectory($directory)
	{
		foreach(glob("{$directory}/*") as $file)
		{
			if(is_dir($file)) { 
				recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($directory);
	}





 



	

	
	$oCrabImport = new iTop_CrabImport();
	
	
//	$oCrabImport->download();
	
	$oCrabImport->importFromShapeFile();
	
	 
	ob_end_flush();
	
	echo "Done";
	
	

?>