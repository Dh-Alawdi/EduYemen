<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/api/verify-seat-number', 'GET', [
    'seat_number' => '423000',
    'grad_year' => 2024,
    'sec_section' => 'علمي'
]);

$controller = new App\Http\Controllers\DashboardController();
$service = new App\Services\MinistryVerificationService();
$response = $controller->verifyHighSchoolSeatNumber($request, $service);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
