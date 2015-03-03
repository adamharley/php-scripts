<?

array_shift($argv);

foreach($argv as &$arg) {

  $tempArg = explode(chr(92), $arg);
  list($tempFile, $tempExt) = explode('.', array_pop($tempArg), 2);
  rename($arg, implode(chr(92), $tempArg) . chr(92) . 'folder.' . $tempExt);
  echo("Renamed " . $arg . chr(10));

}
sleep(3);
?>