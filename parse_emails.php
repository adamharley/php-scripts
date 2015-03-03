<?php

function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

$existing = explode(', ' , file_get_contents( 'emails.txt') );
$emails = array_flip( $existing );

$arr = file( 'emails.csv' );
$sql = "TRUNCATE TABLE gen_mail;INSERT INTO gen_mail (email) VALUES ";

foreach ( $arr as $line ) {
	
	static $i=0; $i++;
	if ( $i===1 )
		continue;
	
	list( $name, $email, $paid, $notes) = str_getcsv( $line );
	
	$email = strtolower( $email );
	
	if( empty( $email ) )
		continue;
	
	if ( !isValidEmail( $email ) ) {
		echo "Invalid: $name / $email\n";
		continue;
	}
	else {
		if( isset( $emails[$email] ) ) {
			echo "$email\n";static $e=0;$e++;
			continue;
		}
		$emails[$email] = $name;
	}

}

foreach ($emails as $email => $name)
	$sql .= "('$email'), ";

$sql = substr( $sql, 0, -2);

file_put_contents( 'sql-pho.txt', $sql );
echo $e;