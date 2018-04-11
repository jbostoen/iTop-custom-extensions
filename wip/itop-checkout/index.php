<?php

 
	require_once("api.php");
	
	
	$i = new iTop_Scan();
	
	
	// Get contacts
	$contacts = $i->getContactsByOrgId([
		"org_id" => 2
	]);
	
	// Sort by friendly name. Don't look at case, our users won't understand.
	function cmp($a, $b) {		
		return strcmp( strtolower( $a["fields"]["friendlyname"] ), strtolower( $b["fields"]["friendlyname"] ) );
	} 
	
	uasort($contacts, "cmp");


	 
	// Render
	echo $twig->render("checkout.html", [
		
		/* Params */
		"contacts" => $contacts
		
	]);

	
	

	
?>