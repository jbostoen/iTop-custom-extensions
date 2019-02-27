<?php

	/**
	* Definition of iTop_Rest_ReportIssue_PublicInfrastructure
	*/
	
	
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
	
	