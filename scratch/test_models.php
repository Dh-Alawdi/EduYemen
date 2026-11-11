<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Specialization;
use App\Models\User;

$spec = Specialization::first();
echo "First Specialization: " . ($spec ? "ID: {$spec->specialization_id}, Name: {$spec->specialization_name}, Duration: {$spec->duration_years}, Language: {$spec->language}, Fees: {$spec->tuition_fees}, Status: {$spec->status}" : "None") . "\n";

$adminUser = User::where('user_type', 'admin')->first();
echo "Admin User: " . ($adminUser ? "ID: {$adminUser->user_id}, Username: {$adminUser->username}, Type: {$adminUser->user_type}, isAdmin: " . ($adminUser->isAdmin() ? "true" : "false") : "None") . "\n";
