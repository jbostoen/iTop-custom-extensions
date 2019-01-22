<?php
 
 
	/**
	 *  Basic specific API for UserRequest through an open form 
	 */
 
 
	require_once("../../itop-connector/connector.php");
	require_once("../framework/personfinder.php");
	
	
	/**
	 *  Class iTop_Rest_ReportIssue_PublicInfrastructure . Contains specific methods to handle citizen requests.
	 */
	class iTop_Rest_ReportIssue_PublicInfrastructure extends iTop_Rest {
		
		/**
		 * Posts data to iTop instance using the iTop. Creates a UserRequest based on 
		 *   		 *  
		 *  @param Array $aData Associative array containing information which needs to be send to iTop. 
		 *  [
		 *    'item'         => [
		 *     
		 *    ]
		 *  ]
		 *   
		 *  @return Array [
		 *    'error'        => Error code
		 *                         0 : no error
		 *  
		 *    'request'      => Original request data
		 *     
		 *  ]
		 *   
		 */
		public function ReportIssue( Array $aData = [] ) {
			
			$aReturn['error'] = 0;
		
			// To-do: Validate if all fields are set and completed in a proper way
			// <implement>
		 
			// Create new ticket. You could specify defaults here
			$aTicket = [
				'operation' => 'core/create',
				'comment' => 'Created by ' . __METHOD__,
				'class' => 'UserRequest',
				'fields' => [
					'org_id' => 1,
					'start_date' => date('Y-m-d H:i:s'),
					'end_date' => null,
					'last_update' => date('Y-m-d H:i:s')
				]
			];
			  
			
			// Post this information to iTop.
			// Attachment info will be done in a separate POST. 
			$aReturn['ticket'] = $this->Post( array_replace_recursive($aTicket, ['fields' => $aData['fields']] ));
						
			// Code should be 0. = no error 
			if( $aReturn['ticket']['code'] != 0 ) {
				return [
					'ticket' => [
						'error' => $aReturn['ticket']['code'],
						'msg' => $aReturn['ticket']['message']
					]
				];
			}
			else {				
				// We should only receive 1 key (get ID for created UserRequest)
				$iTicketId = explode('::', array_keys($aReturn['ticket']['objects'])[0] )[1];
			}
						
			
			// Is a file attached? (careful! include security implementation)
			if( isset( $aData['attachments'] ) == true ) {
				
				if( is_array($aData['attachments']) == true ) {
					
					foreach( $aData['attachments'] as $aAttachment ) {
							
						// Attach uploaded file to ticket
						$aPost_Attachment = [
							'operation' => 'core/create', 
							'class' => 'Attachment',
							'comment' => 'New maintenance request from citizen (attachment)',
							'fields' => [
								'expire' => null,
								'temp_id' => null,
								'item_class' => 'UserRequest',
								'item_id' => $iTicketId,
								'item_org_id' => 1, 
								'contents' => $this->PrepareFile('files/thumbnail/'.$aAttachment['fileName']) // prepares (encodes) file
							]
						];
						
						// echo json_encode($res, JSON_PRETTY_PRINT );
						$aReturn['attachment'] = $this->Post($aPost_Attachment);								

						if( $aReturn['attachment']['code'] != 0 ) {
							return [
								'attachment' => [
									'error' => $aReturn['attachment']['code'],
									'msg' => $aReturn['attachment']['message']
								]
							];
						}
				
					}
					
				}
					
			}
			
			
			
			return $aReturn;
			
		} 
		
	}
	
	
	
	
	
	// Actually try/do something 
 	
	$oRest_Report_Issue = new iTop_Rest_ReportIssue_PublicInfrastructure(); 
		
	header('Content-Type: application/json');
	session_start();

	 
	switch( @$_REQUEST["action"] ) {

		case "createTicket":
		
			// Should have:
			// 'fields' => [array of fields], 
			// 'attachments' => [array of attachments with fileName property]
			   
			$aFields = $_REQUEST["fields"];
			$aAttachments = ( isset($_REQUEST['attachments']) == true ? $_REQUEST['attachments'] : [] );
			
			
			// /// Validation
			$aErrors = [];
			
			if( isset($aFields['name']) == false ) {
				$aErrors[] = 'Vul een achternaam in (minstens 2 tekens).';				
			}
			elseif( strlen($aFields['name']) < 2 ) {
				$aErrors[] = 'Vul een achternaam in (minstens 2 tekens).';
			}
			
			if( isset($aFields['first_name']) == false ) {
				$aErrors[] = 'Vul een voornaam in (minstens 2 tekens).';				
			}
			elseif( strlen($aFields['first_name']) < 2 ) {
				$aErrors[] = 'Vul een voornaam in (minstens 2 tekens).';
			}
			
			if( isset($aFields['email']) == false ) {
				$aErrors[] = 'Vul een geldig mailadres in.';				
			}
			elseif( !filter_var($aFields['email'], FILTER_VALIDATE_EMAIL)) {
				$aErrors[] = 'Vul een geldig mailadres in.';
			}
			
			if( isset($aFields['name']) == false ) {
				$aErrors[] = 'Vul een geldig telefoonnummer in.';				
			}
			elseif( strlen($aFields['phone']) < 9 ) {
				$aErrors[] = 'Vul een geldig telefoonnummer in.';
			} 
			
			if( isset($aFields['description']) == false ) {
				$aErrors[] = 'Vul een korte beschrijving in (minstens 10 tekens).';
			}
			elseif( strlen($aFields['description']) < 10 ) {
				$aErrors[] = 'Vul een korte beschrijving in (minstens 10 tekens).';
			} 
			
			// @todo Add extent check (actually an intersect with a polygon would be better)
			unset($aFields['geomExtent']);
			
			// @todo Better check if values are real 
			
			if( isset($aFields['service_id']) == false ) {
				$aErrors[] = 'Kies een geldige service.';
			}
			
			if( isset($aFields['servicesubcategory_id']) == false ) {
				$aErrors[] = 'Kies een geldige subservice.';
			}
			
			if( isset($aFields['phrase']) == false ) {
				$aErrors[] = 'Ongeldige Anti-SPAM-code';
			}
			elseif( $_SESSION['phrase'] != $aFields['phrase'] ) {
				$aErrors[] = 'Ongeldige Anti-SPAM-code';
			}
			
			
			if( count($aErrors) > 0 ) {
				echo json_encode([
					'error' => 1,
					'msg' => $aErrors
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
			$oFinder = new iTop_PersonFinder();				
			$iPersonId = $oFinder->FindPerson($aFields, ['create']);
				
				
			
			// /// Ticket creation
			
			// Ready to create ticket
			$aResult = $oRest_Report_Issue->ReportIssue([
				'fields' => [
					'title' => $aFields['title'],
					'geom' => $aFields['geom'],
					'caller_id' => $iPersonId,
					'service_id' => (Int)$aFields['service_id'],
					'servicesubcategory_id' => (Int)$aFields['servicesubcategory_id'],
					'description' => ''.
						'<pre>'.trim(strip_tags( $aFields['description'] )).'</pre>'						
				],
				'attachments' => $aAttachments 
			]);
			
			
			// @todo: less to no output			
			echo json_encode( $aResult );
			 
			
			break;
			
		case 'findAddress':
  
			$aCrabAddresses = $oRest_Report_Issue->Get([
				'key' => 'SELECT CrabAddress WHERE friendlyname LIKE \'%' . addslashes($_REQUEST['term']) . '%\'',
				'onlyValues' => true
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