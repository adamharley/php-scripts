<?php

function extract_urls( $data ) {
	preg_match_all( "/href=\"(.*)\"/Uis", $data, $matches );
	return $matches[1];
}

error_reporting( E_ALL ^ E_NOTICE ^ E_DEPRECATED );

$urls = extract_urls( file_get_contents( 'links.txt' ) );

ini_set( 'user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9' );

foreach( $urls as $url ) {

	static $output = '';

	$url = substr( $url, 0, -1 );

	sleep( ( rand( 100, 500 ) / 100 ) );

	$tags = @get_meta_tags( $url );

	if( isset( $tags[ 'generator' ] ) )
		$generators[ $url ] = $tags['generator'];

	else if( isset( $tags[ 'GENERATOR' ] ) )
		$generators[ $url ] = $tags['GENERATOR'];

	if ( isset( $generators[ $url ] ) ) {
		$output .= "\r\n$url : $generators[$url]\r\n";
		echo "$url : $generators[$url]\r\n";
	}

	if ( strpos( $html, 'eval(base64_decode(' ) !== false ) {
		$output .= "Compromised with eval & base64_decode\r\n";
		echo " - Compromised with eval & base64_decode\r\n";
	}

}

file_put_contents( 'generators.txt', $output );