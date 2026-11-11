<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== specializations columns ===\n";
$cols = DB::select('DESCRIBE specializations');
foreach ($cols as $c) {
    echo "{$c->Field} | {$c->Type} | Null: {$c->Null} | Key: {$c->Key} | Default: {$c->Default}\n";
}

echo "\n=== admins table exists? ===\n";
echo Schema::hasTable('admins') ? "YES (admins exists)" : "NO (admins deleted successfully)";
echo "\n";
