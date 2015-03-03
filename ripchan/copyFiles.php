<?php

include('POSTcode.php');
set_time_limit(300000000000);

function copyPictures($pictureList, $url)
{
	$downloadCount = 0;
	
	//Make the folder
	$folder = 'Pictures\\';
	$slash1 = strrpos($url, '/');
	$slash2 = strrpos(substr($url, 0, strlen($url)-1), '/');
	$folder2 = $folder . substr($url, $slash2 + 1, ($slash1 - $slash2 - 1)) . '_' . date('d.m.y');
	
	if(!is_dir($folder))
	{
		mkdir($folder);
	}
	if(!is_dir($folder2))
	{
		mkdir($folder2);
	}
	
	//Copy the pictures
	for($i=0;$i<count($pictureList);$i++)
	{
		$file = $folder2.'\\'.basename($pictureList[$i]);
		if(!is_file($file))
		{
			copy($pictureList[$i],$file);
			if(is_file($file))
			{
				$downloadCount++;
			}
		}
	}
	return($downloadCount . ' Files Downloaded...');
}

if(isset($_POST['pictureArray']) && isset($_POST['url']))
{
	copyPictures(POSTcode_dec($_POST['pictureArray']), $_POST['url']);
}
?>