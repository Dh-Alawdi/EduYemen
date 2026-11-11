<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
$dbName = DB::getDatabaseName();
$key = 'Tables_in_' . $dbName;
$schema = [];
foreach ($tables as $t) {
    $tbl = $t->$key;
    $cols = DB::select('DESCRIBE ' . $tbl);
    $schema[$tbl] = array_map(function($c) { 
        return [
            'field' => $c->Field,
            'type' => $c->Type,
            'null' => $c->Null,
            'key' => $c->Key,
            'default' => $c->Default,
            'extra' => $c->Extra,
        ]; 
    }, $cols);
}
file_put_contents(__DIR__ . '/db_schema_actual.json', json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Saved actual schema to scratch/db_schema_actual.json\n";
