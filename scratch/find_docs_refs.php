<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/\.php$/');

foreach ($files as $file) {
    $content = file_get_contents($file->getPathname());
    $lines = explode("\n", $content);
    foreach ($lines as $num => $line) {
        if (preg_match('/admissionData\.docs|\'docs\'|"docs"|->docs\b/', $line)) {
            echo $file->getPathname() . ":" . ($num + 1) . ": " . trim($line) . "\n";
        }
    }
}
