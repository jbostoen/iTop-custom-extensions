<?php

 
	require_once("api.php");
	
	$i = new iTop_Scan();
	
	// Settings
	$orgId_checkout = 1;
	
	// Get contacts
	$contacts = $i->get([ 
		"key" => "SELECT Contact WHERE org_id = '".$orgId_checkout."'"						
	]);   
	
	
	// Sort by friendly name. Don't look at case, our users won't understand.
	function cmp($a, $b) {		
		return strcmp( strtolower( $a["fields"]["friendlyname"] ), strtolower( $b["fields"]["friendlyname"] ) );
	} 
	
	uasort($contacts, "cmp");
 
	// Render
	echo $twig->render("checkout.html", [
		
		// Params
		"contacts" => $contacts,
		"org_id" => $orgId_checkout,
		"lang" => $lang
		
		
	]);

	
	

	
?>