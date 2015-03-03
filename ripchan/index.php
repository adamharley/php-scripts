<?php
function get4chanBoards($url)
{
	$url = file($url);
	$imageboards = array();
	$output = array();
	array_push($output, array());
	array_push($output, array());
	
	for($i=0;$i<count($url);$i++)
	{
		if(strpos($url[$i], "toggle(this,'img');"))
		{
			$imageboardStart = $i + 2;
		}
		if(strpos($url[$i], "toggle(this,'draw')"))
		{
			$imageboardEnd = $i - 2;
		}
	}
	for($i=$imageboardStart;$i<=$imageboardEnd;$i++)
	{
		if(strpos($url[$i], 'class="hl"'))
		{
			$start2 = strpos($url[$i], 'imgboard.html" target="main" class="hl">') + strlen('imgboard.html" target="main" class="hl">');
			$end1 = strpos($url[$i], 'imgboard.html" target="main" class="hl">');	
		}
		else
		{
			$start2 = strpos($url[$i], 'imgboard.html" target="main">') + strlen('imgboard.html" target="main">');
			$end1 = strpos($url[$i], 'imgboard.html" target="main">');
		}
		$start1 = strpos($url[$i], '<li><a href="') + strlen('<li><a href="');
		$end2 = strpos($url[$i], '</a></li>');
		array_push($output[0], substr($url[$i], $start1, ($end1 - $start1)));
		array_push($output[1], substr($url[$i], $start2, ($end2 - $start2)));
	}
	return($output);
}

function get7chanBoards($url)
{
	$url = file($url);
	$record = FALSE;
	$linkArray = array();
	$output = array();
	array_push($output, array());
	array_push($output, array());
	
	for($i=0;$i<count($url);$i++)
	{
		if(strpos($url[$i], "toggle(this,'vip');"))
		{
			$record = TRUE;
		}
		if(strpos($url[$i], "<h2>IRC</h2>"))
		{
			$record = FALSE;
		}
		if($record == TRUE)
		{
			if(substr($url[$i], 0, 6)=='<li><a')
			{
				array_push($linkArray, $url[$i]);
			}
		}
	}
	
	for($i=0;$i<count($linkArray);$i++)
	{
		$start1 = strlen('<li><a href="');
		if(strpos($linkArray[$i], '<i>'))
		{
			$end1 = strpos($linkArray[$i], '"><i>');
			$end2 = strpos($linkArray[$i], '</i></a></li>');
		}
		else
		{
			$end1 = strpos($linkArray[$i], '">');
			$end2 = strpos($linkArray[$i], '</a></li>');
		}
		$start2 = strpos($linkArray[$i], '- ') + 2;
		if(strpos($linkArray[$i], 'drug'))
		{
			array_push($output[0], substr($linkArray[$i], $start1, ($end1 - $start1)) . '/');
		}
		else
		{
			array_push($output[0], substr($linkArray[$i], $start1, ($end1 - $start1)));
		}
		array_push($output[1], substr($linkArray[$i], $start2, ($end2 - $start2)));
	}
	array_pop($output[0]);
	array_pop($output[1]);
	return $output;
}

function imageBoardFormatting($Array)
{
	for($i=0;$i<count($Array[0]);$i++)
	{
		echo '<option value="' . $Array[0][$i] . '">' . $Array[1][$i] . "</option>\n";
	}
}

echo '
<center><h1>4CHAN RIPPER</h1>
<form name="form1" method="post" action="4chan.php">
	<select name="url">';
  
	echo imageBoardFormatting(get4chanBoards('http://4chan.org/nav.php?disclaimer=accept'));
	
  echo '
  	</select><br>
	<input type="submit" name="Submit" value="Download imageboard"><br><br>
  	<input type="text" name="onePageURL"><br>
  	<input type="submit" name="onePage" value="Download from one page">
</form>
<h1>7CHAN RIPPER</h1>
<form name="form2" method="post" action="7chan.php">
	<select name="url">';
	
	echo imageBoardFormatting(get7chanBoards('http://7chan.org/menu.php'));
	
	echo '
	</select><br>
	<input type="submit" name="Submit" value="Download imageboard"><br><br>
	<input type="text" name="onePageURL"><br>
	<input type="submit" name="onePage" value="Download from one page">
</form>
</center>
';
?>