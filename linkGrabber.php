<?

$lines = @file('source-irc.txt');

if (!$lines)
  die("No data");

foreach ($lines as $key => $line) {
   if ((strpos($line, 'http:') || strpos($line, 'www.') || strpos($line, 'blog')) && (strpos($line, 'imdb') == false) ) {
      $newLines[] = trim($lines[$key-2]);
      $newLines[] = trim($lines[$key-1]);
      $newLines[] = trim($line);
      $newLines[] = trim($lines[$key+1]);
      $newLines[] = trim($lines[$key+2]);
      $newLines[] = '<br>';
   }
}

file_put_contents('sorted.htm', implode($newLines, chr(10) . '<br>'));
echo "Parsed ".count($lines)." lines, picked out ".(count($newLines) / 6)." lines with URLs";

?>