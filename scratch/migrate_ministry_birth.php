<?php
require 'c:/xampp/htdocs/ministry_exams_service/src/Database.php';
use MinistryService\Database;

$pdo = Database::getConnection();

// 1. Add columns if not exist
$cols = $pdo->query("DESCRIBE exam_records")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array('birth_date', $cols)) {
    $pdo->exec("ALTER TABLE `exam_records` ADD COLUMN `birth_date` DATE DEFAULT NULL AFTER `district`");
    echo "Added birth_date column.\n";
}
if (!in_array('birth_place', $cols)) {
    $pdo->exec("ALTER TABLE `exam_records` ADD COLUMN `birth_place` VARCHAR(150) DEFAULT NULL AFTER `birth_date`");
    echo "Added birth_place column.\n";
}

// 2. Populate existing records that have null birth_date
$records = $pdo->query("SELECT id, seat_number, grad_year, district, governorate FROM `exam_records` WHERE `birth_date` IS NULL OR `birth_place` IS NULL")->fetchAll(PDO::FETCH_ASSOC);

$birthPlaces = [
    'أمانة العاصمة / السبعين',
    'أمانة العاصمة / التحرير',
    'أمانة العاصمة / معين',
    'أمانة العاصمة / الثورة',
    'أمانة العاصمة / الوحدة',
    'محافظة صنعاء / همدان',
    'محافظة تعز / المظفر',
    'محافظة إب / الظهار',
    'محافظة عدن / المنصورة',
    'محافظة ذمار / عنس',
    'محافظة الحديدة / الحوك',
    'محافظة حضرموت / المكلا',
];

$updStmt = $pdo->prepare("UPDATE `exam_records` SET `birth_date` = :bdate, `birth_place` = :bplace WHERE `id` = :id");

$count = 0;
foreach ($records as $r) {
    $seed = crc32($r['seat_number'] . '_birth');
    mt_srand($seed);
    $bYear = $r['grad_year'] - 18;
    $bMonth = mt_rand(1, 12);
    $bDay = mt_rand(1, 28);
    $bDate = sprintf('%04d-%02d-%02d', $bYear, $bMonth, $bDay);
    $bPlace = $birthPlaces[mt_rand(0, count($birthPlaces) - 1)];
    mt_srand();

    $updStmt->execute([
        'bdate' => $bDate,
        'bplace' => $bPlace,
        'id' => $r['id']
    ]);
    $count++;
}

echo "Successfully updated {$count} records with birth_date and birth_place.\n";
