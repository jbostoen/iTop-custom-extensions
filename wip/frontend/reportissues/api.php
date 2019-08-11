<?php	
	
	require_once '../../libext/vendor/autoload.php';
 	
	$oRest_Report_Issue = new iTop_Rest_ReportIssue_PublicInfrastructure(); 
		
	header('Content-Type: application/json');
	session_start();

	 
	switch( @$_REQUEST['action'] ) {

		case 'createTicket':
		
			// Should have:
			// 'fields' => [array of fields], 
			// 'attachments' => [array of attachments with fileName property]
			   
			$aFields = $_REQUEST['fields'];
			$aAttachments = ( isset($_REQUEST['attachments']) == true ? $_REQUEST['attachments'] : [] );
			
			
			// Validation. Respect order of fields compared to front end (user friendly)
			$aErrors = [];
			$aErrorFields = [];
			
			
			if( isset($aFields['geom']) == false ) {
				$aErrors[] = 'Duid een punt, lijn of vlak aan op de kaart.';
				$aErrorFields[] = 'findAddress'; // Not really, but have to mark it
			}
			elseif( $aFields['geom'] == '' ) {
				$aErrors[] = 'Duid een punt, lijn of vlak aan op de kaart.';
				$aErrorFields[] = 'findAddress'; // Not really, but have to mark it
			}
			
			if( isset($aFields['first_name']) == false ) {
				$aErrors[] = 'Vul een voornaam in (minstens 2 tekens).';
				$aErrorFields[] = 'first_name';
			}
			elseif( strlen($aFields['first_name']) < 2 ) {
				$aErrors[] = 'Vul een voornaam in (minstens 2 tekens).';
				$aErrorFields[] = 'first_name';
			}
			
			if( isset($aFields['name']) == false ) {
				$aErrors[] = 'Vul een achternaam in (minstens 2 tekens).';		
				$aErrorFields[] = 'name';
			}
			elseif( strlen($aFields['name']) < 2 ) {
				$aErrors[] = 'Vul een achternaam in (minstens 2 tekens).';
				$aErrorFields[] = 'name';
			}
			
			
			if( isset($aFields['email']) == false ) {
				$aErrors[] = 'Vul een geldig mailadres in.';
				$aErrorFields[] = 'email';	
			}
			elseif( !filter_var($aFields['email'], FILTER_VALIDATE_EMAIL)) {
				$aErrors[] = 'Vul een geldig mailadres in.';
				$aErrorFields[] = 'email';
			}
			
			if( isset($aFields['phone']) == false ) {
				$aErrors[] = 'Vul een geldig telefoonnummer in.';
				$aErrorFields[] = 'phone';				
			}
			elseif( strlen($aFields['phone']) < 9 ) {
				$aErrors[] = 'Vul een geldig telefoonnummer in.';
				$aErrorFields[] = 'phone';
			} 
			
			if( isset($aFields['description']) == false ) {
				$aErrors[] = 'Vul een korte beschrijving in (minstens 10 tekens).';
				$aErrorFields[] = 'description';
			}
			elseif( strlen($aFields['description']) < 10 ) {
				$aErrors[] = 'Vul een korte beschrijving in (minstens 10 tekens).';
				$aErrorFields[] = 'description';
			} 
			
			// @todo Add extent check (actually an intersect with a polygon would be better)
			unset($aFields['geomExtent']);
			
			// @todo Better check if values are real 
			
			if( isset($aFields['service_id']) == false ) {
				$aErrors[] = 'Kies een geldige service.';
				$aErrorFields[] = 'service_id';
			}
			
			if( isset($aFields['servicesubcategory_id']) == false ) {
				$aErrors[] = 'Kies een geldige subservice.';
				$aErrorFields[] = 'servicesubcategory_id';
			}
			
			if( isset($aFields['phrase']) == false ) {
				$aErrors[] = 'Ongeldige Anti-SPAM-code.';
				$aErrorFields[] = 'phrase';
			}
			elseif( $_SESSION['phrase'] != $aFields['phrase'] ) {
				$aErrors[] = 'Ongeldige Anti-SPAM-code.';
				$aErrorFields[] = 'phrase';
			}
			
			
			
			if( count($aErrors) > 0 ) {
				echo json_encode([
					'errorMsgs' => $aErrors,
					'errorFields' => $aErrorFields
				]);
				exit();
			}
			
			// /// Match person
			
			// Defaults for new people
			$aFields['org_id'] = 1; 
			$aFields['status'] = 'active'; 
			$aFields['notify'] = 'no';
				
			// Try to match Person
			// Create if nothing found
			$oPersonManager = new iTop_PersonManager();				
			$iPersonId = $oPersonManager->GetPersonId($aFields, ['create']);
				
			// /// Ticket creation
			
			// Ready to create ticket
			$aResult = $oRest_Report_Issue->ReportIssue([
				'fields' => [
					'title' => $aFields['title'],
					'geom' => $aFields['geom'],
					'caller_id' => $iPersonId,
					'service_id' => (Int)$aFields['service_id'],
					'servicesubcategory_id' => (Int)$aFields['servicesubcategory_id'],
					'description' => trim(strip_tags( $aFields['description'] ))
				],
				'attachments' => $aAttachments 
			]);
			
			
			// @todo: less to no output			
			echo json_encode( $aResult );
			 
			
			break;
			
		case 'findAddress':
  
			$aCrabAddresses = $oRest_Report_Issue->Get([
				'key' => 'SELECT CrabAddress WHERE friendlyname LIKE "%' . addslashes($_REQUEST['term']) . '%"',
				'no_keys' => true
			]);
		
			echo json_encode( $aCrabAddresses );
			
			break;

		default: 
			echo json_encode([
				'error' => 1, 
				'msg' => 'Onbekende actie'
			]);
			break;
	}
 

?>
