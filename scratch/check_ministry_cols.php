<?php
require 'c:/xampp/htdocs/ministry_exams_service/src/Database.php';
$pdo = MinistryService\Database::getConnection();
$cols = $pdo->query('DESCRIBE exam_records')->fetchAll(PDO::FETCH_COLUMN);
echo implode(', ', $cols) . "\n";
