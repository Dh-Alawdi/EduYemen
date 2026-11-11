<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$uniques = DB::select("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
      AND CONSTRAINT_NAME != 'PRIMARY'
      AND (
        (TABLE_NAME = 'universities' AND COLUMN_NAME = 'user_id') OR
        (TABLE_NAME = 'students' AND COLUMN_NAME = 'user_id') OR
        (TABLE_NAME = 'reg_proofs' AND COLUMN_NAME = 'referral_id')
      )
");
print_r($uniques);
