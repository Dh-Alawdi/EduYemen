<?php
$actual = json_decode(file_get_contents(__DIR__ . '/db_schema_actual.json'), true);

$erd = [
    'users' => [
        'user_id', 'username', 'password', 'user_type', 'phone', 'email', 'account_status', 'created_at', 'updated_at'
    ],
    'admins' => [
        'admin_id', 'user_id', 'full_name'
    ],
    'universities' => [
        'university_id', 'user_id', 'university_name', 'university_type', 'description', 'logo', 'campus_image', 'district', 'contact_phone', 'address', 'latitude', 'longitude', 'phone', 'email', 'website', 'status', 'year_establ'
    ],
    'colleges' => [
        'college_id', 'university_id', 'college_name', 'description', 'status'
    ],
    'specializations' => [
        'specialization_id', 'college_id', 'specialization_name', 'description', 'status', 'tuition_fees'
    ],
    'study_plans' => [
        'study_plan_id', 'specialization_id', 'plan_name', 'duration_years', 'total_hours', 'status'
    ],
    'semesters' => [
        'semester_id', 'study_plan_id', 'semester_number'
    ],
    'courses' => [
        'course_id', 'semester_id', 'course_name', 'course_code', 'hours', 'course_type', 'description'
    ],
    'admission_data' => [ // or admissions
        'admission_id', 'specialization_id', 'admission_data', 'minimum_average', 'description', 'secondary_last_year', 'document_name', 'is_required'
    ],
    'students' => [
        'student_id', 'user_id', 'full_name', 'gender', 'birth_date', 'address'
    ],
    'rec_data' => [
        'r_d_id', 'student_id', 'average', 'interests', 'hobbies', 'abilities', 'preferences', 'location'
    ],
    'rec_results' => [
        'result_id', 'r_d_id', 'univ_name', 'spec_name', 'match_percentage', 'result_order', 'notes'
    ],
    'reg_requests' => [
        'req_id', 'student_id', 'university_id', 'full_name', 'nationality', 'gender', 'birth_date', 'birth_place', 'province', 'secondary_school', 'seat_number', 'grad_year', 'sec_section', 'residence', 'total_score', 'sec_avg', 'req_date', 'phone', 'email', 'guardian_name', 'guardian', 'guardian_rel', 'guardian_occup', 'home_address', 'gu_phone', 'desired_spec', 'status', 'decision_date', 'rejection_reason'
    ],
    'reg_docs' => [
        'reg_doc_id', 'req_id', 'file_name', 'file_path', 'up_at', 'ver_status', 'ver_note', 'verified_at'
    ],
    'pay_methods' => [ // or university_payment_methods
        'method_id', 'university_id', 'method_name', 'method_type', 'account_name', 'account_number', 'alternative_number', 'instructions', 'is_active'
    ],
    'payments' => [
        'payment_id', 'req_id', 'method_id', 'amount', 'pay_date', 'reference', 'proof_file', 'ver_status', 'ver_date', 'ver_result', 'notes'
    ],
    'referrals' => [ // or student_referrals
        'referral_id', 'student_id', 'university_id', 'ref_code', 'ref_date', 'ref_status', 'notes'
    ],
    'reg_proofs' => [
        'reg_proof_id', 'referral_id', 'receipt_number', 'registration_date', 'student_name', 'submited_at', 'verification_status', 'verification_date', 'verification_result'
    ],
    'advertisements' => [
        'adv_id', 'university_id', 'adv_title', 'adv_content', 'package', 'duration', 'amount', 'pay_method', 'pay_reference', 'review_status', 'status', 'notes', 'created_at'
    ],
    'notifications' => [
        'notification_id', 'user_id', 'notification_type', 'title', 'message', 'is_read', 'created_at'
    ]
];

$laravelSystemTables = [
    'migrations', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'sessions', 'password_reset_tokens'
];

echo "=== TABLES IN DB BUT NOT IN ERD (excluding laravel system tables) ===\n";
foreach (array_keys($actual) as $t) {
    if (in_array($t, $laravelSystemTables)) continue;
    $matched = false;
    foreach (array_keys($erd) as $et) {
        if ($t === $et) { $matched = true; break; }
    }
    // Also check variations: pay_methods vs university_payment_methods, referrals vs student_referrals, admission_data vs admissions
    if ($t === 'university_payment_methods' || $t === 'student_referrals' || $t === 'admission_data' || $t === 'university_announcements' || $t === 'admission_docs') {
        echo "Variation / Candidate: $t\n";
    } elseif (!$matched) {
        echo "EXTRA TABLE: $t\n";
    }
}

echo "\n=== DETAILED COLUMN COMPARISON ===\n";
// Let's check table name mappings:
$mapping = [
    'users' => 'users',
    'admins' => 'admins',
    'universities' => 'universities',
    'colleges' => 'colleges',
    'specializations' => 'specializations',
    'study_plans' => 'study_plans',
    'semesters' => 'semesters',
    'courses' => 'courses',
    'admission_data' => isset($actual['admission']) ? 'admission' : 'admission_data',
    'students' => 'students',
    'rec_data' => 'rec_data',
    'rec_results' => 'rec_results',
    'reg_requests' => 'reg_requests',
    'reg_docs' => 'reg_docs',
    'pay_methods' => isset($actual['pay_methods']) ? 'pay_methods' : (isset($actual['payment_methods']) ? 'payment_methods' : 'university_payment_methods'),
    'payments' => 'payments',
    'referrals' => isset($actual['referrals']) ? 'referrals' : 'student_referrals',
    'reg_proofs' => 'reg_proofs',
    'advertisements' => 'advertisements',
    'notifications' => 'notifications'
];

foreach ($erd as $erdTable => $erdCols) {
    $dbTable = $mapping[$erdTable] ?? $erdTable;
    echo "\n--- ERD Table: $erdTable (DB Table: $dbTable) ---\n";
    if (!isset($actual[$dbTable])) {
        echo "MISSING TABLE in DB: $dbTable\n";
        continue;
    }
    $actualCols = array_column($actual[$dbTable], 'field');
    
    $missingInDb = array_diff($erdCols, $actualCols);
    $extraInDb = array_diff($actualCols, $erdCols);
    
    if (!empty($missingInDb)) {
        echo "  MISSING COLUMNS in DB: " . implode(', ', $missingInDb) . "\n";
    }
    if (!empty($extraInDb)) {
        echo "  EXTRA COLUMNS in DB: " . implode(', ', $extraInDb) . "\n";
    }
    if (empty($missingInDb) && empty($extraInDb)) {
        echo "  MATCH OK!\n";
    }
}
