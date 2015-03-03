<?php
include('POSTcode.php');
set_time_limit(300000000000);

function getSources($board)
{
	$sourceArray = array();
	$file = file($board);
	
	for($i=0;$i<count($file);$i++)
	{
		if(strpos($file[$i], '&#91;0&#93;'))
		{
			for($j=10;$j>=0;$j--)
			{
				if(strpos($file[$i], $j.'.html">'.$j))
				{
					array_push($sourceArray, $board.$j.'.html');
				}
			}
		}
	}
	array_push($sourceArray, $board);
	return $sourceArray;
}

function getSubSources($sourceList)
{
	$subSourceArray = array();
	for($i=0;$i<count($sourceList);$i++)
	{
		$file = file($sourceList[$i]);
		for($j=0;$j<count($file);$j++)
		{
			if(strpos($file[$j], 'Reply</a>&#93;'))
			{
				$before = strpos($file[$j], '&nbsp;&#91;<a href="') + strlen('&nbsp;&#91;<a href="');
				$after = strpos($file[$j], '">Reply</a>&#93;');
				array_push($subSourceArray, substr($file[$j], $before, ($after - $before)));
			}
		}
	}
	return $subSourceArray;
}

function getPictures($subSourceList)
{
	$pictureList = array();
	for($i=0;$i<count($subSourceList);$i++)
	{
		$file = file($subSourceList[$i]);
		for($j=0;$j<count($file);$j++)
		{
			if(strpos($file[$j], '<span class="filesize">File: <a target="_blank" href="'))
			{
				$before = strpos($file[$j], 'for full size.</span><br><a target="_blank" href="') + strlen('for full size.</span><br><a target="_blank" href="');
				$after = strpos($file[$j], '"><img src="');
				array_push($pictureList, substr($file[$j], $before, ($after - $before)));
			}
		}
	}
	return $pictureList;
}

if(isset($_POST['onePage']))
{
	$SSL = array($_POST['onePageUrl']);
}
else
{
	if(isset($_POST['url']))
	{
		$url = $_POST['url'];
	}
	else
	{
		$url = 'http://img.7chan.org/b/';
	}
	
	$SL = getSources($url);
	$SSL = getSubSources($SL);
}
$PL = getPictures($SSL);

echo count($PL) . ' Pictures:<br>';
for($i=0;$i<count($PL);$i++)
{
	echo $PL[$i] . '<br>';
}

echo '
<form name="form1" method="post" action="copyFiles.php">
  <input type="submit" name="Submit2" value="Submit">
  <input type="hidden" name="url" value="' . $url . '">
  <input type="hidden" name="pictureArray" value="' . POSTcode_enc($PL) . '">
</form>
';

?>