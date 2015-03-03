<?php
function POSTcode_enc($array)
{
	$string = "";
	for($i=0;$i<count($array);$i++)
	{
		$string=$string.$array[$i].';';
	}
	return($string);	
}

function POSTcode_dec($string)
{
	$array = array();
	do
	{
		$colonPos = strpos($string, ';');
		array_push($array, substr($string, 0, $colonPos));
		$string = substr($string, $colonPos + 1);
	}
	while(strpos($string, ';'));
	return($array);
}

?>