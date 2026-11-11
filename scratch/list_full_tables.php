<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select("SHOW FULL TABLES");
foreach ($tables as $t) {
    $arr = (array)$t;
    $values = array_values($arr);
    echo $values[0] . " (" . $values[1] . ")\n";
}
