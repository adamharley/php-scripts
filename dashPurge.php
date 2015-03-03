<?
// Otter's ultra-useful ultra-unsafe manga chapter sorter!
// Written for use sorting Ichigo 100% on the 11th of March 2005 (Red Nose Day, which I didn't get to celebrate ;.;) and finished on the 12th
// Settings will need to be tweaked for any other manga

if ($handle = opendir('.')) {
	while (false !== ($file = readdir($handle))) { 
		if ($file != "." && $file != "..") {
			$charToRem = '_';
			if(isset($argv[1]))
				$charToRem = $argv[1];
			$tempArr = explode(" - ", $file);
			$newName = $tempArr[2];
			if ($file == $newName)
				continue;
			if (rename($file, $newName) === FALSE)
				echo "Failed to rename {$file} to " . $newName . chr(10);
			else
				echo "Renamed {$file} to " . $newName . chr(10);
		}
	}
}
	closedir($handle);
?>