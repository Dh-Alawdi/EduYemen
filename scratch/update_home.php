<?php
$file = 'resources/views/home.blade.php';
$content = file_get_contents($file);

// 1. استبدال زر إنشاء حساب طالب بتسجيل الدخول
$oldBtn = '<a href="{{ route(\'register\') }}" class="btn btn-outline-light btn-lg px-4 fw-bold">' . "\r\n" .
'                        <i class="bi bi-person-plus me-2"></i><span>إنشاء حساب طالب</span>' . "\r\n" .
'                    </a>';

$newBtn = '<a href="{{ route(\'login\') }}" class="btn btn-outline-light btn-lg px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">' . "\r\n" .
'                        <i class="bi bi-box-arrow-in-left me-2"></i><span>تسجيل الدخول</span>' . "\r\n" .
'                    </a>';

if (strpos($content, $oldBtn) !== false) {
    $content = str_replace($oldBtn, $newBtn, $content);
    echo "Button replaced successfully.\n";
} else {
    echo "Button NOT found!\n";
}

// 2. تخفيف المسافة بين حاوية الخطوات والحاوية التالية
$oldSectionTag = '<section class="py-5 mt-4">';
$newSectionTag = '<section class="pt-2 pb-5">';
if (strpos($content, $oldSectionTag) !== false) {
    $content = str_replace($oldSectionTag, $newSectionTag, $content);
    echo "Section tag spacing reduced.\n";
} else {
    echo "Section tag NOT found!\n";
}

// 3. تخفيف الحشو السفلي لحاوية الخطوات
$oldPad = '.how-it-works-section {' . "\r\n" . '        padding: 3rem 0 1.5rem;';
$newPad = '.how-it-works-section {' . "\r\n" . '        padding: 2.5rem 0 0.5rem;';
if (strpos($content, $oldPad) !== false) {
    $content = str_replace($oldPad, $newPad, $content);
    echo "How it works padding reduced.\n";
} else {
    echo "How it works padding NOT found!\n";
}

file_put_contents($file, $content);
echo "Done updating home.blade.php\n";
