<?php


// Report all PHP errors
error_reporting(-1);


// LDAP
$ldapOrg = [
	'host' 		=>  'ip',
	'user' 		=> 'user@intranet.domain.com', 
	'pass'		=> 'password',
  // Make your own query. To exclude non-person accounts, we just select users with a phone number
	'query'		=> '(&(objectclass=user)(objectcategory=person)(telephoneNumber=+32 51*))',
	'baseDN'	=> 'DC=intranet,DC=domain,DC=com'
];
 


// MySQL
$db = [
	'host'	=> 'ip', 
	'name' 	=> 'name', 
	'user'	=> 'user',
	'pass'	=> 'pass'
];


// Change the path accordingly as well as the iTop user credentials.
// In our example, we created a data source in iTop first (ID: 21) for Persons.
// This is for a Ubuntu implementation.
$syncSource = "php -q /var/www/html/itop_2_4_1/web/synchro/synchro_exec.php --auth_user=admin --auth_pwd=password --data_sources=21";

// Name of the table to write to (=name of the data-source in your MYSQL database)
$iTopDBTableSource = 'synchro_data_persons';

// For each organization
ad_2_itop('Organization name', $ldapOrg1, $db); 


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

	    	$attributes = ['givenname', 'mail', 'samaccountname', 'sn', 'telephonenumber', 'mobile'];

		$result = ldap_search($ldap_connection, $ldap['baseDN'], $ldap['query'], $attributes);
		$ldap_entries = [];

    		if (FALSE !== $result){
 
	       		$ldap_entries = ldap_get_entries($ldap_connection, $result);
		
		}
	}

    	ldap_unbind($ldap_connection); // Clean up after ourselves.



	$mysqli = new mysqli( $db['host'], $db['user'], $db['pass'], $db['name'] );

	# check connection
	if ( $mysqli->connect_errno ) {
        	die( "Connect failed: %s\n" . $mysqli->connect_error ) ;
	}

	$mysqli->set_charset('utf8');



	echo '<h1>'.$orgName.'</h1>';
	echo '<table>';
	foreach( $ldap_entries as $key => $ldap_entry ) {

        # Uncomment to get an idea of the attributes you can use
        # print_r( $ldap_entry );


	        echo '<tr>';

		foreach( $attributes as $a ) {
			echo '<td>'.$ldap_entry[$a][0].'</td>';
		}
       		echo '</tr>';



		# Insert / update in iTop data source
        	if( strtolower($key) != "count" && @$ldap_entry['sn'][0] != "" && @$ldap_entry['givenname'][0] != "" ) {
			$mysqli->query("
                		INSERT INTO ".$iTopDBTableSource." ( org_id, name, first_name, phone, mobile_phone, email )
                		VALUES (
                        		'".$orgName."',
                        		'".@$ldap_entry['sn'][0]."',
                        		'".@$ldap_entry['givenname'][0]."',
                        		'".@$ldap_entry['telephonenumber'][0]."',
                       	 		'".@$ldap_entry['mobile'][0]."',
                        		'".@$ldap_entry['mail'][0]."'
                		)

                		ON DUPLICATE KEY UPDATE
                        		phone = '".@$ldap_entry['telephonenumber'][0]."',
        	                	mobile_phone = '".@$ldap_entry['mobile_phone'][0]."'
	        	");
		}

	}
	echo '</table>';

	mysqli_close($link);

}



# Sync iTop
exec( $syncSource );

echo "Done";


?>
