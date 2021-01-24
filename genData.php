<?php

$numCount = $argv[1] ?? 1000;
echo "num count: " . $numCount . "\n";
$fid = fopen('data.txt', 'w');
$i = 0;
while ($i < $numCount) {
    fwrite($fid, rand(0, 999999) . "\n");
    $i++;
}
fclose($fid);