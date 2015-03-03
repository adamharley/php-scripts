
<?php
$t = 0;
foreach (glob("*.php") as $filename) {
	$i = 0;
	foreach( file($filename) as $line ) {
		if( trim( $line ) != false )
			$i++;
	}
	$t += $i;
	echo "$filename: $i lines\n\n";
}
echo "\nTotal of $t lines\n";