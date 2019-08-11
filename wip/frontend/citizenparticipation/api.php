<?php
 
 
	/**
	 *  Basic specific API for UserRequest through an open form 
	 */
 
 
	require_once('../../libext/vendor/autoload.php');
	
		
	header('Content-Type: application/json');
	session_start();

	// Save idea in this organization (permission silo)
	$iOrgId = 1;
	
	$oRest = new iTop_Rest();
	
	// Name for comments in REST Actions
	$oRest->name = $oRest->name . ' - CitizenParticipation';
	
	$aReturnData = [];
	
	switch( @$_REQUEST['action'] ) {

	
			
		case 'vote':

			
			$oAccountManager = new iTop_AccountManager();
			$aResult_DoLoginByToken = $oAccountManager->DoLoginByToken();
			
			if( isset($aResult_DoLoginByToken['errorMsgs']) == true ) {
								
				// Unauthenticated, redirect would be interesting.
				// Not done in PHP because this is a JSON-result.
				$aReturnData = [
					'errorMsgs' => 'De sessie is verlopen - je moet eerst <a href="../framework/register.php?redirect=../citizenparticipation/">opnieuw aanmelden</a>.',
					'session' => $_SESSION,
					'details' => $aResult_DoLoginByToken
				];
					
			}
			
			else {
				
				// Authenticated, save vote.
				// Check if vote didn't exist already (could be done with iTop constraints too).
				
				$fVoterId = $_SESSION[ iTop_FrameWork::prefix . '_user_id' ];
				
				$aSet_Votes = $oRest->Get([
					'key' => 'SELECT ParticipationVote WHERE voter_id = "' . $fVoterId . '" AND participationidea_id = "' . $_REQUEST['participationidea_id'] . "'",
					'no_keys' => true
				]);
								
				
				if( isset($aSet_Votes['code']) == true ) {
					
					$aReturnData = [
						'errorMsgs' => 'Er is een probleem met het stemmen.'
					];
					
				}
				elseif( count($aSet_Votes) > 0 ) {
					
					$aReturnData = [
						'errorMsgs' => 'Je hebt al gestemd op dit idee.'
					];					
					
				}
				else {
					
					
					$aResult_Vote = $oRest->Create([
						'class' => 'ParticipationVote',
						'fields' => [
							'org_id' => $iOrgId,
							'voter_id' => $_REQUEST['voter_id'],
							'participationidea_id' => $_REQUEST['participationidea_id'],
							'created' => date('Y-m-d H:i:s'),
							'score' => 1
						]
					]);
					
					if( $aResult_Vote['code'] == 0) {
						// No error
						$aReturnData = [];
					}	
					else {
						$aReturnData = [
							'errorMsgs' => 'Er was een onverwacht probleem bij het stemmen.'
						];
					}
				
				}
				
			}
			
			
			
			break;

		default: 
		
			$aReturnData = [
				'errorMsgs' => [
					'"' . @$_REQUEST['action'] . '" is een onbekende actie.'
				]
			];
			break;
	}
 
 
	echo json_encode($aReturnData);
 
