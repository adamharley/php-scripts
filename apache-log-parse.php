<?php
/*
+----------------------------------------------+
|                                              |
|      PHP example apache log parser class     |
|                                              |
+----------------------------------------------+
| Filename   : example.php                     |
| Created    : 21-Sep-05 23:28 GMT             |
| Created By : Sam Clarke                      |
| Email      : admin@free-webmaster-help.com   |
| Version    : 1.0                             |
|                                              |
+----------------------------------------------+


LICENSE

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License (GPL)
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

To read the license please visit http://www.gnu.org/copyleft/gpl.html

*/


$file = 'photosoc.union.shef.ac.uk';


function linkify( $text ) {
  $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
    '<a href="\\1">\\1</a>', $text);

  $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
    '\\1<a href="http://\\2">\\2</a>', $text);

  $text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})',
    '<a href="mailto:\\1">\\1</a>', $text);
  return $text;
}


error_reporting(0);

include 'apache-log-parser.php';

$apache_log_parser = new apache_log_parser(); // Create an apache log parser

$lines = file($file);

foreach ($lines as $line) {
	$line = trim($line);
	$parsed_line = $apache_log_parser->format_line($line); // format the line
//	print_r($parsed_line); // print out the array
	
	$agent = substr( $parsed_line['agent'], 1, -1 );
	
	if( substr( $agent, 0, 24 ) == 'Mozilla/4.0 (compatible;' || substr( $agent, 0, 24 ) == 'Mozilla/5.0 (compatible;' )
		$agent = trim( substr( $agent, 24, -1 ) );
	
	if( substr( $agent, 0, 13 ) == 'Mozilla/5.0 (' )
		$agent = trim( substr( $agent, 13, -1 ) );
	
	if( empty( $agent ) || $agent == '-' || $agent == 'Mozilla/4.0' )
		continue;

	if ( !isset( $agents[ $agent ] ) )
		$agents[ $agent ] = 0;
	$agents[ $agent ]++;
}

arsort($agents);

foreach ( $agents as $agent => $count ) {

	if( strpos( $agent, 'Windows' ) === false && strpos( $agent, 'OS X' ) === false && strpos( $agent, 'Linux' ) === false && strpos( $agent, $file ) === false )
		$output .= "$count: $agent\r\n";

}

file_put_contents( "$file.log", trim( $output ) );

$html = '<strong>' . str_replace( array( "\r\n", ': ' ), array( "\n<br />\n<strong>", '</strong> ' ), trim( $output ) ) . '</strong>';

$html = "<html>
<head>
<title>Results for $file</title>
<base target=\"_blank\" />
</head>
<body>
".linkify($html)."
</body>
</html>";

file_put_contents( "$file.htm", $html );