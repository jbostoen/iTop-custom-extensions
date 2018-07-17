<?php

// Version: 20180716-1640

// Report all PHP errors
error_reporting(-1);


// LDAP
$ldapOrg = [
	"host" 		=>  "ip",
	"user" 		=> "user@intranet.domain.com", 
	"pass"		=> "password",
  // Make your own query. To exclude non-person accounts, we just select users with a phone number
	"query"		=> "(&(objectclass=user)(objectcategory=person)(telephoneNumber=+32 *))",
	"baseDN"	=> "DC=intranet,DC=domain,DC=com"
];
 


// MySQL
$db = [
	"host"	=> "ip", 
	"name" 	=> "name", 
	"user"	=> "user",
	"pass"	=> "pass",
	
	"table" => "synchro_data_persons" // name of the table to write to (=name of the iTop data source in your MySQL Database)
];


// Change the path accordingly as well as the iTop user credentials and id of the data source.
// In our example, we created a data source in iTop first (ID: 21) for Persons.
// This is for a Ubuntu implementation.
$syncSource = "php -q /var/www/html/itop_2_4_1/web/synchro/synchro_exec.php --auth_user=syncuser --auth_pwd=password --data_sources=21";
 
// For each organization
ad_2_itop("Your organization name", $ldapOrg, $db); 

// You might adjust this, but it's probably not necessary

function ad_2_itop( $orgName, $ldap, $db ) {



	$ldap_connection = ldap_connect($ldap['host']);

	if (FALSE === $ldap_connection){
    		// Uh-oh, something is wrong...
		die("No connection with LDAP");
	}

	// We have to set this option for the version of Active Directory we are using.
	ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
	ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

	if (TRUE === ldap_bind($ldap_connection, $ldap['user'], $ldap['pass'] )){

		$getAttributes = ["givenname", "mail", "samaccountname", "sn", "telephonenumber", "mobile"];

		$result = ldap_search($ldap_connection, $ldap["baseDN"], $ldap["query"], $attributes);
		$ldap_entries = [];

		if ( $result !== FALSE ) {
			$ldap_entries = ldap_get_entries($ldap_connection, $result);
		}
	}

	ldap_unbind($ldap_connection); // Clean up after ourselves.

	$mysqli = new mysqli( $db["host"], $db["user"], $db["pass"], $db["name"] );

	// check connection
	if ( $mysqli->connect_errno ) {
		die( "Connect failed: %s\n" . $mysqli->connect_error ) ;
	}

	$mysqli->set_charset("utf8");
 

	echo "<h1>".$orgName."</h1>";
	echo "<table>";
	foreach( $ldap_entries as $index => $ldap_entry ) {

        // Uncomment to get an idea of the attributes you can use
        // print_r( $ldap_entry );
 

		// Valid user? Except for indexes, we also get a "Count" as name for a key. 
		// To avoid service accounts etc, we also want a sn and givenname
        if( strtolower($index) != "count" && @$ldap_entry["sn"][0] != "" && @$ldap_entry["givenname"][0] != "" @$ldap_entry["mail"][0] != "" ) {
 
			/*
				Important note: due to how iTop works, it's "forbidden" to use INSERT ... ON DUPLICATE KEY UPDATE ... 
				The same applies for INSERT IGNORE. The before insert trigger will create a new replica whenever it is fired - it won't be when using an UPDATE query, but it will on all INSERT queries. 
				From iTop: "We didn't came up with a solution for this - trigger are really tricky to work with."
			*/
			
			// As reference, we'll use email. 
			// There could easily be two John Smith's in a company, but they won't have the same email address.
			$result = $mysqli->query("
					SELECT * 
					FROM ".$db["table"]." 
					WHERE 
						email = '".$mysqli->real_escape_string(@$ldap_entry["mail"][0])."';
			");	 
				
			// Depending on the count:
			switch( $result->num_rows ) {
				
				case 0: 
				 
					// Initial insert.
					$mysqli->query("
						INSERT INTO ".$db["table"]." ( org_id, name, first_name, phone, mobile_phone, email )
						VALUES (
								'".$mysqli->real_escape_string($orgName)."',
								'".$mysqli->real_escape_string(@$ldap_entry["sn"][0])."',
								'".$mysqli->real_escape_string(@$ldap_entry["givenname"][0])."',
								'".$mysqli->real_escape_string(@$ldap_entry["telephonenumber"][0])."',
								'".$mysqli->real_escape_string(@$ldap_entry["mobile"][0])."',
								'".$mysqli->real_escape_string(@$ldap_entry["mail"][0])."'
						);								
					");
					 
					echo "<tr>";
					foreach( $getAttributes as $a ) {
						echo "<td>".$a." = ".@$ldap_entry[$a][0]."</td>".PHP_EOL;
					}
					echo "<td>Inserted</td>". PHP_EOL ."</tr>"; 
					
					break;
					
				case 1: 
				
					// Update 
					$mysqli->query("
						UPDATE ".$db["table"]." 
						SET 
							org_id = '".$mysqli->real_escape_string($orgName)."', 
							name = '".$mysqli->real_escape_string(@$ldap_entry["sn"][0])."',
							first_name = '".$mysqli->real_escape_string(@$ldap_entry["givenname"][0])."', 
							phone = '".$mysqli->real_escape_string(@$ldap_entry['telephonenumber'][0])."',
							mobile_phone = '".$mysqli->real_escape_string(@$ldap_entry['mobile_phone'][0])."'
						WHERE 
							email = '".$mysqli->real_escape_string(@$ldap_entry['mail'][0])."';
					");
					
					 
					echo "<tr>";
					foreach( $getAttributes as $a ) {
						echo "<td>".$a." = ".@$ldap_entry[$a][0]."</td>";
					}
					echo "<td>Updated</td>". PHP_EOL ."</tr>"; 
					
					break;
					
					
				default: 				
				
					// Error
					foreach( $getAttributes as $a ) {
						echo "<td>".$a." = ".@$ldap_entry[$a][0]."</td>";
					}
					echo "<td>Error: not unique</td>". PHP_EOL ."</tr>"; 
					
					 
					
			}
					
		}

	}
	echo "</table>";

	$mysqli->close();

}



# Sync iTop
exec( $syncSource );

echo "Done";


?>
