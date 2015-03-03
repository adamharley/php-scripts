<?

if ($handle = opendir('F:/')) {
	while (false !== ($file = readdir($handle))) { 
		if ($file != "." && $file != "..") {
		   $temp = explode('.', $file, 2);
                   $files[$temp[0]] = true;
		}
	}
file_put_contents('C:/files.rtf', implode(chr(10), array_keys($files)));
}
	closedir($handle);
?>