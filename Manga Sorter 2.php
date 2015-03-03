<?
// Otter's ultra-useful ultra-unsafe manga chapter sorter!
// Written for use sorting Ichigo 100% on the 11th of March 2005 (Red Nose Day, which I didn't get to celebrate ;.;) and finished on the 12th
// Settings will need to be tweaked for any other manga

if ($handle = opendir('.')) {
	while (false !== ($file = readdir($handle))) { 
		if ($file != "." && $file != ".." && is_dir($file)) {
			$fileParts = explode('_', $file);print_r($fileParts);

			$chapter = $fileParts[5];
			$volume = 'Volume ' . $fileParts[5][1];
			$chapter = substr($fileParts[5], 4, 2);
			list($chapter) = explode('[', $chapter, 2);
			if (!is_dir($volume)) { // If the volume folder needs to be created
				if(!mkdir($volume)) { // Create it
					echo "Error creating folder for ". $volume . chr(10);
					echo "Failed to sort " . $chapter . chr(10);
					continue;
				}
			}
			if (rename($file, $volume . chr(92) . $chapter) === FALSE)
				echo "Failed to move {$chapter} to " . $volume . chr(10);
			else
				echo "Moved {$chapter} to " . $volume . chr(10);
		}
	}
}
	closedir($handle);
?>