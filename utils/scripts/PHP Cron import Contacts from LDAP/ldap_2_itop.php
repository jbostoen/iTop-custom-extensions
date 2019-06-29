<?php
/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2018-07-16 16:40
 *
 * Simplistic script to import AD user info into iTop - by means of an iTop Data Source.
 **/
 
	// Report all PHP errors
	error_reporting(-1);

	// LDAP settings
	$aSettings_LDAP = [
		'host' 		=> 'ip',
		'user' 		=> 'user@intranet.domain.com', 
		'pass'		=> 'password',
	  // Make your own query. To exclude non-person accounts, we just select users with a phone number
		'query'		=> '(&(objectclass=user)(objectcategory=person)(telephoneNumber=+32 *))',
		'baseDN'	=> 'DC=intranet,DC=domain,DC=com'
	];
	 
	// MySQL settings
	$aSettings_MySQL = [
		'host'		=> 'ip', 
		'name' 		=> 'name', 
		'user'		=> 'user',
		'pass'		=> 'pass',
		
		'table' 	=> 'synchro_data_persons' // name of the table to write to (=name of the iTop data source in your MySQL Database)
	];

	// Change the path accordingly as well as the iTop user credentials and id of the data source.
	// In our example, we created a data source in iTop first (ID: 21) for Persons.
	// This is for a Ubuntu implementation.
	$sSyncSource = 'php -q /var/www/html/itop_2_6_1/web/synchro/synchro_exec.php --auth_user=syncuser --auth_pwd=password --data_sources=21';
	 
	// For each organization
	ad_2_itop('Your organization name', $aSettings_LDAP, $aSettings_MySQL); 

	// You might adjust this, but it's probably not necessary

	/**
	 * Imports contacts from an Active Directory (using LDAP query) into the MySQL database.
	 *
	 * @param String $sOrgName Name of the organization these users should be mapped to. Assuming 1:1 mapping of $aSettings_LDAP to $sOrgName.
	 * @param Array $aSettings_LDAP LDAP settings
	 * @param Array $aSettings_MySQL MySQL settings
	 *
	 * @return void
	 *
	 */
	public function ad_2_itop( $sOrgName, $aSettings_LDAP, $aSettings_MySQL ) {

		$aSettings_LDAP_connection = ldap_connect($aSettings_LDAP['host']);

		if($aSettings_LDAP_connection === false) {
			die('No connection with LDAP');
		}

		// We have to set this option for the version of Active Directory we are using.
		ldap_set_option($aSettings_LDAP_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
		ldap_set_option($aSettings_LDAP_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

		if(ldap_bind($aSettings_LDAP_connection, $aSettings_LDAP['user'], $aSettings_LDAP['pass']) === true){

			$$aFindAttributes = ['givenname', 'mail', 'samaccountname', 'sn', 'telephonenumber', 'mobile'];

			$result = ldap_search($aSettings_LDAP_connection, $aSettings_LDAP['baseDN'], $aSettings_LDAP['query'], $attributes);
			$aSettings_LDAP_entries = [];

			if ( $result !== false ) {
				$aSettings_LDAP_entries = ldap_get_entries($aSettings_LDAP_connection, $result);
			}
		}

		ldap_unbind($aSettings_LDAP_connection); // Clean up after ourselves.

		$mysqli = new mysqli( $aSettings_MySQL['host'], $aSettings_MySQL['user'], $aSettings_MySQL['pass'], $aSettings_MySQL['name'] );

		// check connection
		if ( $mysqli->connect_errno ) {
			die( 'Connect failed: %s\n' . $mysqli->connect_error ) ;
		}

		$mysqli->set_charset('utf8');
	 
		echo '<h1>'.$sOrgName.'</h1>';
		echo '<table>';
		foreach( $aSettings_LDAP_entries as $index => $aSettings_LDAP_entry ) {

			// Uncomment to get an idea of the attributes you can use
			// print_r( $aSettings_LDAP_entry );

			// Valid user? Except for indexes, we also get a 'Count' as name for a key. 
			// To avoid service accounts etc, we also want a sn and givenname and a non-empty mail address
			if( strtolower($index) != 'count' && @$aSettings_LDAP_entry['sn'][0] != '' && @$aSettings_LDAP_entry['givenname'][0] != '' && @$aSettings_LDAP_entry['sn'][0] != '' ) {

				/*
					Important note: due to how iTop works, it's 'forbidden' to use INSERT ... ON DUPLICATE KEY UPDATE ... 
					The same applies for INSERT IGNORE. The before insert trigger will create a new replica whenever it is fired - it won't be when using an UPDATE query, but it will on all INSERT queries. 
					From iTop: 'We didn't came up with a solution for this - trigger are really tricky to work with.'
				*/
				
				// To match, 'email' will be used.
				// There could easily be two John Smith's in a company, but they won't have the same email address.
				// Avoid issues with duplicate email in LDAPs but other capitalization
				$result = $mysqli->query('
						SELECT * 
						FROM '.$aSettings_MySQL['table'].' 
						WHERE 
							email LIKE "'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['mail'][0]).'";
				');	 
					
				// Depending on the count:
				switch( $result->num_rows ) {
					
					case 0: 
					 
						// Initial insert.
						$mysqli->query('
							INSERT INTO '.$aSettings_MySQL['table'].' ( org_id, name, first_name, phone, mobile_phone, email )
							VALUES (
									"'.$mysqli->real_escape_string($sOrgName).'",
									"'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['sn'][0]).'",
									"'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['givenname'][0]).'",
									"'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['telephonenumber'][0]).'",
									"'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['mobile'][0]).'",
									"'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['mail'][0]).'"
							);								
						');
						 
						echo '<tr>';
						foreach( $aFindAttributes as $sAttribute ) {
							echo '<td>'.$sAttribute.' = '.@$aSettings_LDAP_entry[$a][0].'</td>'.PHP_EOL;
						}
						echo '<td>Inserted</td>'. PHP_EOL .'</tr>'; 
						
						break;
						
					case 1: 
					
						// Update 
						$mysqli->query('
							UPDATE '.$aSettings_MySQL['table'].' 
							SET 
								org_id = "'.$mysqli->real_escape_string($sOrgName).'", 
								name = "'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['sn'][0]).'",
								first_name = "'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['givenname'][0]).'", 
								phone = "'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['telephonenumber'][0]).'",
								mobile_phone = "'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['mobile_phone'][0]).'"
							WHERE 
								email = "'.$mysqli->real_escape_string(@$aSettings_LDAP_entry['mail'][0]).'";
						');
						
						 
						echo '<tr>';
						foreach( $aFindAttributes as $sAttribute ) {
							echo '<td>'.$sAttribute.' = '.@$aSettings_LDAP_entry[$sAttribute][0].'</td>';
						}
						echo '<td>Updated</td>'. PHP_EOL .'</tr>'; 
						
						break;
						
						
					default: 				
					
						// Error
						foreach( $aFindAttributes as $sAttribute ) {
							echo '<td>'.$sAttribute.' = '.@$aSettings_LDAP_entry[$sAttribute][0].'</td>';
						}
						echo '<td>Error: not unique</td>'. PHP_EOL .'</tr>'; 
						
				}
						
			}

		}
		echo '</table>';

		$mysqli->close();

	}

	// Sync iTop
	exec( $sSyncSource );

	echo 'Done';
