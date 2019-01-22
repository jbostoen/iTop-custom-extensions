<?php

 
	require_once('api.php');
	
	$oRest_CheckOut = new iTop_Rest_CheckOut();
	
	// Settings
	$orgId_checkout = 1;
	
	// Get contacts
	$contacts = $oRest_CheckOut->get([ 
		'key' => 'SELECT Contact WHERE org_id = \''.$orgId_checkout.'\''						
	]);   
	
	
	// Sort by friendly name. Don't look at case, our users won't understand.
	
	/**
	 * Short helper function to sort by friendlyName
	 *  
	 * @param String $a String A
	 * @param String $b String B
	 * @return Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal. 
	 *  
	 */
	function cmpFriendlyName($a, $b) {		
		return strcmp( strtolower( $a['fields']['friendlyname'] ), strtolower( $b['fields']['friendlyname'] ) );
	} 
	
	uasort($contacts, 'cmpFriendlyName');
 
	// Render
	echo $twig->render('checkout.html', [
		
		// Params
		'contacts' => $contacts,
		'org_id' => $orgId_checkout,
		'lang' => $lang
		
		
	]);

	
	

	
?>