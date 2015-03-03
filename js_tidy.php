<?php

$code = file_get_contents( 'code.txt' );

$search = array(

'};' => 'SEMIBRACKET',
';' => ";\n",
'	' => '',
'(' => ' ( ',
')' => ' ) ',
'{' => "{\n",
'}' => "}\n\n",
'(  )' => '()',
'if (' => "\n\nif (",
'else' => "\nelse",
' ;' => ';',
'function ' => "\n\nfunction ",
'SEMIBRACKET' => "};\n"

);

$code = str_replace( array_keys( $search ), $search, $code );

$code = trim( $code );

file_put_contents( 'code2.txt', $code );