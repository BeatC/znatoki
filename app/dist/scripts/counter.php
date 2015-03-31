<?php

$file='98895385948578953.txt';
$linecount = 0;
$handle = fopen($file, "r");
while(!feof($handle)){
    $line = fgets($handle);
    $linecount++;
}

fclose($handle);

echo json_encode(array("count" => $linecount, "by_digit" => str_split(sprintf("%03d", $linecount))));