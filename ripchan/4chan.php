<?php
/*
	Title: 4chan Ripper
	Description: Searches the first webpage of an image board on 4chan for
				 A list of pages, searches those pages for threads, searches
				 those threads for images, downloads the images to a folder
				 based on date and image board.
	
	
*/

include('POSTcode.php');	//POSTcode, for moving arrays between webpages using forms and POST.

set_time_limit(300000000000); //Depending on the internet connection used, this can take quite
							  //A while...

//getSourceList looks at the bottom of the main page of an image board. It looks for links, and
//if it finds a link, it'll add it to an array of "Source Pages". These are checked for
//"Sub-source Pages."
function getSourceList($origUrl) 
{
	$justBeforePos = "";
	$justAfterPos = "";
	$sourceList = array();
	$url = file($origUrl);
	
	array_push($sourceList, $origUrl);
	
	for($i=0;$i<count($url);$i++)
	{
		if(strpos($url[$i], 'document.delform.pwd.value=get_pass("4chan_pass");'))
		{
			for($j=10;$j>=0;$j--)
			{
				if(strpos($url[$i], '[<a href="' . $j . '.html">' . $j . '</a>]'))
				{
						array_push($sourceList, $origUrl . $j . '.html');
				}
			}
		}
	}
	return($sourceList);
}

//getSubSourceList searches every page in the source list for threads. Threads are marked with
//the string "[Reply]". These threads are added to a list, and passed to the getPictureList
//function.
function getSubSourceList($sourceList, $origUrl)
{
	$justBeforePos = "";
	$justAfterPos = "";
	$subSourceList = array();
	
	for($j=0;$j<count($sourceList);$j++)
	{
		$url = file($sourceList[$j]);
		
		for($i=0;$i<count($url);$i++)
		{
			if(strpos($url[$i], 'Reply</a>]</span>'))
			{
				$justAfterPos = strpos($url[$i], 'Reply</a>]</span>') - 2;
				$justBeforePos = strrpos($url[$i], '[<a href="') + strlen('[<a href="');
				array_push($subSourceList, ($origUrl . substr($url[$i], $justBeforePos, ($justAfterPos - $justBeforePos))));
			}
		}
	}
	return($subSourceList);
}

//This searches the threads for posts with pictures in them. When it finds them, it extracts the
//link to the file, and adds it to a list of pictures.
function getPictureList($subSourceList, $origUrl)
{
	$justBeforePos = "";
	$justAfterPos = "";
	$pictureList = array();
	
	for($i=0;$i<count($subSourceList);$i++)
	{
		$url = file($subSourceList[$i]);
		for($j=0;$j<count($url);$j++)
		{
			if(strpos($url[$j], '<span class="filesize">'))
			{
				$justAfterPos = strpos($url[$j], '" target="_blank">');
				$justBeforePos = strpos($url[$j], 'File :<a href="') + strlen('File :<a href="');
				array_push($pictureList, substr($url[$j], $justBeforePos, ($justAfterPos - $justBeforePos)));
			}
		}
	}
	return($pictureList);
}

//Main Script

//Checks whether the GUI worked properly. If not, default to /b/
if(isset($_POST['url']))
{
	$url = $_POST['url'];
}
else
{
	$url = "http://img.4chan.org/b/";
}

//Checks for whether the source is one webpage or an entire image board
if(!isset($_POST['onePage']))
{
	$SL = getSourceList($url);
	$SSL = getSubSourceList($SL, $url);
}
else
{
	$SSL = array($_POST['onePageURL']);	
}

$PL = getPictureList($SSL, $url);

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