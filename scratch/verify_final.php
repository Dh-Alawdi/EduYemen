<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$erd = [
    'USER (users)' => [
        'table' => 'users',
        'cols' => ['user_id', 'username', 'password', 'user_type', 'phone', 'email', 'account_status', 'created_at', 'updated_at']
    ],
    'ADMIN (admins)' => [
        'table' => 'admins',
        'cols' => ['admin_id', 'user_id', 'full_name']
    ],
    'UNIVERSITY (universities)' => [
        'table' => 'universities',
        'cols' => ['university_id', 'user_id', 'university_name', 'university_type', 'description', 'logo', 'campus_image', 'district', 'contact_phone', 'address', 'latitude', 'longitude', 'phone', 'email', 'website', 'status', 'year_establ']
    ],
    'COLLEGE (colleges)' => [
        'table' => 'colleges',
        'cols' => ['college_id', 'university_id', 'college_name', 'description', 'status']
    ],
    'SPECIALIZATION (specializations)' => [
        'table' => 'specializations',
        'cols' => ['specialization_id', 'college_id', 'specialization_name', 'description', 'status', 'tuition_fees']
    ],
    'STUDY_PLAN (study_plans)' => [
        'table' => 'study_plans',
        'cols' => ['study_plan_id', 'specialization_id', 'plan_name', 'duration_years', 'total_hours', 'status']
    ],
    'SEMESTERS (semesters)' => [
        'table' => 'semesters',
        'cols' => ['semester_id', 'study_plan_id', 'semester_number']
    ],
    'COURSES (courses)' => [
        'table' => 'courses',
        'cols' => ['course_id', 'semester_id', 'course_name', 'course_code', 'hours', 'course_type', 'description']
    ],
    'ADMISSION (admission_data)' => [
        'table' => 'admission_data',
        'cols' => ['admission_id', 'specialization_id', 'admission_data', 'minimum_average', 'description', 'secondary_last_year', 'document_name', 'is_required']
    ],
    'STUDENT (students)' => [
        'table' => 'students',
        'cols' => ['student_id', 'user_id', 'full_name', 'gender', 'birth_date', 'address']
    ],
    'REC_DATA (rec_data)' => [
        'table' => 'rec_data',
        'cols' => ['r_d_id', 'student_id', 'average', 'interests', 'hobbies', 'abilities', 'preferences', 'location']
    ],
    'REC_RESULT (rec_results)' => [
        'table' => 'rec_results',
        'cols' => ['result_id', 'r_d_id', 'univ_name', 'spec_name', 'match_percentage', 'result_order', 'notes']
    ],
    'REG_REQUEST (reg_requests)' => [
        'table' => 'reg_requests',
        'cols' => ['req_id', 'student_id', 'university_id', 'full_name', 'nationality', 'gender', 'birth_date', 'birth_place', 'province', 'secondary_school', 'seat_number', 'grad_year', 'sec_section', 'residence', 'total_score', 'sec_avg', 'req_date', 'phone', 'email', 'guardian_name', 'guardian', 'guardian_rel', 'guardian_occup', 'home_address', 'gu_phone', 'desired_spec', 'status', 'decision_date', 'rejection_reason']
    ],
    'REG_DOC (reg_docs)' => [
        'table' => 'reg_docs',
        'cols' => ['reg_doc_id', 'req_id', 'file_name', 'file_path', 'up_at', 'ver_status', 'ver_note', 'verified_at']
    ],
    'PAY_METHOD (pay_methods)' => [
        'table' => 'pay_methods',
        'cols' => ['method_id', 'university_id', 'method_name', 'method_type', 'account_name', 'account_number', 'alternative_number', 'instructions', 'is_active']
    ],
    'PAYMENT (payments)' => [
        'table' => 'payments',
        'cols' => ['payment_id', 'req_id', 'method_id', 'amount', 'pay_date', 'reference', 'proof_file', 'ver_status', 'ver_date', 'ver_result', 'notes']
    ],
    'REFERRAL (referrals)' => [
        'table' => 'referrals',
        'cols' => ['referral_id', 'student_id', 'university_id', 'ref_code', 'ref_date', 'ref_status', 'notes']
    ],
    'REG_PROOF (reg_proofs)' => [
        'table' => 'reg_proofs',
        'cols' => ['reg_proof_id', 'referral_id', 'receipt_number', 'registration_date', 'student_name', 'submited_at', 'verification_status', 'verification_date', 'verification_result']
    ],
    'ADVERTISEMENT (advertisements)' => [
        'table' => 'advertisements',
        'cols' => ['adv_id', 'university_id', 'adv_title', 'adv_content', 'package', 'duration', 'amount', 'pay_method', 'pay_reference', 'review_status', 'status', 'notes', 'created_at']
    ],
    'NOTIFICATIONS (notifications)' => [
        'table' => 'notifications',
        'cols' => ['notification_id', 'user_id', 'notification_type', 'title', 'message', 'is_read', 'created_at']
    ]
];

$allPassed = true;
foreach ($erd as $name => $def) {
    $table = $def['table'];
    $expectedCols = $def['cols'];
    $actualCols = array_column(DB::select("DESCRIBE $table"), 'Field');
    
    $diff1 = array_diff($expectedCols, $actualCols);
    $diff2 = array_diff($actualCols, $expectedCols);
    
    if (!empty($diff1) || !empty($diff2)) {
        $allPassed = false;
        echo "[FAIL] $name:\n";
        if (!empty($diff1)) echo "  Missing: " . implode(', ', $diff1) . "\n";
        if (!empty($diff2)) echo "  Extra: " . implode(', ', $diff2) . "\n";
    } else {
        echo "[PASS] $name (" . count($expectedCols) . " columns matched 100%)\n";
    }
}

if ($allPassed) {
    echo "\n>>> ALL 20 TABLES MATCH THE ERD DIAGRAM 100% EXACTLY! <<<\n";
}
