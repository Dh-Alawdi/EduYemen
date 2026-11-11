<?php
$f = file_get_contents('resources/views/home.blade.php');
$lines = explode("\n", $f);
for ($i = 945; $i <= 958; $i++) {
    echo $i . ": " . bin2hex($lines[$i]) . " | " . $lines[$i] . "\n";
}
