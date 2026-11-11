<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\PublicController;
use Illuminate\Http\Request;

try {
    $controller = new PublicController();
    $response = $controller->showUniversity(2);
    echo "SUCCESS: Status code " . (is_object($response) && method_exists($response, 'status') ? $response->status() : '200 (View rendered)') . "\n";
    if (is_object($response) && method_exists($response, 'render')) {
        $html = $response->render();
        echo "HTML Length: " . strlen($html) . " bytes\n";
        echo "View rendered without any exceptions!\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "At: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
