<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
$dbName = DB::getDatabaseName();
$key = 'Tables_in_' . $dbName;
foreach ($tables as $t) {
    $tbl = $t->$key;
    try {
        $count = DB::table($tbl)->count();
        if ($count > 0) {
            echo "$tbl: $count rows\n";
        }
    } catch (\Exception $e) {
        echo "$tbl: error (" . $e->getMessage() . ")\n";
    }
}
