<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Support\Facades\Auth;

$publicCtrl = new PublicController();
$dashCtrl = new DashboardController();

echo "1. Testing PublicController::home()...\n";
try {
    $res = $publicCtrl->home();
    echo "   [OK] Home rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] Home: " . $e->getMessage() . "\n";
}

echo "2. Testing PublicController::universities()...\n";
try {
    $res = $publicCtrl->universities(new \Illuminate\Http\Request());
    echo "   [OK] Universities rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] Universities: " . $e->getMessage() . "\n";
}

echo "3. Testing PublicController::showUniversity(2)...\n";
try {
    $res = $publicCtrl->showUniversity(2);
    echo "   [OK] showUniversity(2) rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] showUniversity(2): " . $e->getMessage() . "\n";
}

echo "4. Testing PublicController::compare()...\n";
try {
    $res = $publicCtrl->compare();
    echo "   [OK] compare rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] compare: " . $e->getMessage() . "\n";
}

echo "5. Testing PublicController::getSpecializationAdmissionData(1)...\n";
try {
    $firstSpec = Specialization::first();
    if ($firstSpec) {
        $res = $publicCtrl->getSpecializationAdmissionData($firstSpec->specialization_id);
        echo "   [OK] getSpecializationAdmissionData: " . json_encode($res->getData()->success) . "\n";
    } else {
        echo "   [SKIP] No specialization found\n";
    }
} catch (\Throwable $e) {
    echo "   [FAIL] getSpecializationAdmissionData: " . $e->getMessage() . "\n";
}

echo "6. Testing DashboardController::adminDashboard()...\n";
try {
    $admin = User::where('user_type', 'admin')->first();
    if ($admin) Auth::login($admin);
    $res = $dashCtrl->adminDashboard();
    echo "   [OK] adminDashboard rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] adminDashboard: " . $e->getMessage() . "\n";
}

echo "7. Testing DashboardController::universityDashboard()...\n";
try {
    $univUser = User::where('user_type', 'university')->first();
    if ($univUser) Auth::login($univUser);
    $res = $dashCtrl->universityDashboard();
    echo "   [OK] universityDashboard rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] universityDashboard: " . $e->getMessage() . "\n";
}

echo "8. Testing DashboardController::studentDashboard()...\n";
try {
    $studentUser = User::where('user_type', 'student')->first();
    if ($studentUser) Auth::login($studentUser);
    view()->share('errors', new \Illuminate\Support\ViewErrorBag());
    $res = $dashCtrl->studentDashboard();
    echo "   [OK] studentDashboard rendered (" . strlen($res->render()) . " bytes)\n";
} catch (\Throwable $e) {
    echo "   [FAIL] studentDashboard: " . $e->getMessage() . "\n";
}
