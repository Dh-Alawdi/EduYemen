<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- university_announcements ---\n";
print_r(DB::table('university_announcements')->get()->toArray());

echo "--- admission_docs sample ---\n";
print_r(DB::table('admission_docs')->limit(5)->get()->toArray());

echo "--- admission_data sample ---\n";
print_r(DB::table('admission_data')->limit(3)->get()->toArray());
