<?

$source = 'Text messages.txt';
$type = 'texte';


if ($type == 'phone')
	$output = 'Name,Number';
else
	$output = ',Number,Message';

$sourceArr = file($source);
array_shift($sourceArr);

foreach ($sourceArr as $line) {

$entry = &$entries[];

	if ($type == 'phone') {
		list( , $entry['Name'], , $entry['Number']) = explode("	", $line);
		if (!empty($entry['Number'])) {
			$entry['Number'] = trim(substr($entry['Number'],0,5) . ' ' . substr($entry['Number'],5));
			$output .= chr(10) . $entry['Name'] . ',+"' . $entry['Number'] . '"';
		}
	}
	else {
		list( , $entry['Status'], $entry['Number'], $entry['Message']) = explode("	", $line);
		if (!empty($entry['Number'])) {
			$entry['Number'] = trim(substr($entry['Number'],0,5) . ' ' . substr($entry['Number'],5));
			$output .= chr(10) . $entry['Status'] . ',"' . $entry['Number'] . '","' . trim($entry['Message']) . '"';
		}
	}

}	

file_put_contents("output.csv",$output);

?>