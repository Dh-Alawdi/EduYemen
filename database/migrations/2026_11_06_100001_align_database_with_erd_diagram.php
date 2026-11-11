<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * مطابقة قاعدة البيانات والجداول والخصائص مع مخطط ERD المعتمد وحذف كل ما هو غير مذكور فيه
     */
    public function up(): void
    {
        // تعطيل قيود المفاتيح الأجنبية مؤقتاً لتنفيذ عمليات الحذف وإعادة التسمية بأمان
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // =========================================================================
        // 0. ترحيل بيانات الإعلانات من university_announcements إلى advertisements
        // =========================================================================
        if (Schema::hasTable('university_announcements') && Schema::hasTable('advertisements')) {
            $announcements = DB::table('university_announcements')->get();
            foreach ($announcements as $ann) {
                // تجنب التكرار إذا كان موجوداً
                $exists = DB::table('advertisements')->where('adv_title', $ann->title)->exists();
                if (!$exists) {
                    DB::table('advertisements')->insert([
                        'university_id' => $ann->university_id,
                        'adv_title' => $ann->title,
                        'adv_content' => $ann->content,
                        'package' => 'أساسية',
                        'duration' => 30,
                        'amount' => 0.00,
                        'pay_method' => 'مجاني',
                        'pay_reference' => 'ANN-' . $ann->id,
                        'review_status' => 'معتمد',
                        'status' => $ann->status ?? 'نشط',
                        'notes' => 'تم استيراده من الإعلانات السابقة',
                        'created_at' => $ann->created_at ?? now(),
                    ]);
                }
            }
        }

        // =========================================================================
        // 1. حذف الجداول غير المذكورة في مخطط ERD
        // =========================================================================
        Schema::dropIfExists('admission_docs');
        Schema::dropIfExists('university_announcements');

        // =========================================================================
        // 2. جدول users — الاحتفاظ فقط بالحقول المذكورة في المخطط
        // المخطط: user_id, username, password, user_type, phone, email, account_status, created_at, updated_at
        // =========================================================================
        $usersExtraColumns = [
            'last_login', 'theme_mode', 'theme_color', 'notifications_muted',
            'notification_sound', 'notification_tone', 'referral_code', 'remember_token'
        ];
        Schema::table('users', function (Blueprint $table) use ($usersExtraColumns) {
            foreach ($usersExtraColumns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // =========================================================================
        // 3. جدول admins — الاحتفاظ فقط بالحقول المذكورة في المخطط
        // المخطط: admin_id, user_id, full_name
        // =========================================================================
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('admins', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });

        // =========================================================================
        // 4. جدول specializations — الاحتفاظ فقط بالحقول المذكورة في المخطط
        // المخطط: specialization_id, college_id, specialization_name, description, status, tuition_fees
        // =========================================================================
        $specExtraColumns = ['language', 'duration_years', 'study_plans'];
        Schema::table('specializations', function (Blueprint $table) use ($specExtraColumns) {
            foreach ($specExtraColumns as $col) {
                if (Schema::hasColumn('specializations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // =========================================================================
        // 5. جدول study_plans — تصحيح اسم الحقل ليطابق المخطط
        // المخطط: study_plan_id, specialization_id, plan_name, duration_years, total_hours, status
        // =========================================================================
        if (Schema::hasColumn('study_plans', 'total_credit_hours') && !Schema::hasColumn('study_plans', 'total_hours')) {
            DB::statement('ALTER TABLE study_plans CHANGE total_credit_hours total_hours INT NULL');
        }

        // =========================================================================
        // 6. جدول semesters — حذف الحقول الزائدة غير المذكورة في المخطط
        // المخطط: semester_id, study_plan_id, semester_number
        // =========================================================================
        if (Schema::hasColumn('semesters', 'plan_name')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->dropColumn('plan_name');
            } );
        }

        // =========================================================================
        // 7. جدول admission_data — مطابقة الحقول مع المخطط
        // المخطط: admission_id, specialization_id, admission_data, minimum_average, description, secondary_last_year, document_name, is_required
        // =========================================================================
        Schema::table('admission_data', function (Blueprint $table) {
            if (!Schema::hasColumn('admission_data', 'document_name')) {
                $table->string('document_name', 150)->nullable()->after('secondary_last_year');
            }
            if (!Schema::hasColumn('admission_data', 'is_required')) {
                $table->boolean('is_required')->default(true)->after('document_name');
            }
        });

        $admissionExtraCols = [
            'registration_fee', 'tuition_fees', 'admission_start_date',
            'admission_end_date', 'admission_status', 'required_documents'
        ];
        Schema::table('admission_data', function (Blueprint $table) use ($admissionExtraCols) {
            foreach ($admissionExtraCols as $col) {
                if (Schema::hasColumn('admission_data', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // =========================================================================
        // 8. جدول students — الاحتفاظ فقط بالحقول المذكورة في المخطط
        // المخطط: student_id, user_id, full_name, gender, birth_date, address
        // =========================================================================
        $studentExtraCols = ['nationality', 'city', 'high_school_name', 'high_school_avg', 'grad_year', 'desired_major', 'bio'];
        Schema::table('students', function (Blueprint $table) use ($studentExtraCols) {
            foreach ($studentExtraCols as $col) {
                if (Schema::hasColumn('students', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // =========================================================================
        // 9. جدول rec_results — حذف الحقول الزائدة
        // المخطط: result_id, r_d_id, univ_name, spec_name, match_percentage, result_order, notes
        // =========================================================================
        Schema::table('rec_results', function (Blueprint $table) {
            if (Schema::hasColumn('rec_results', 'university_id')) {
                $table->dropForeign(['university_id']);
                $table->dropColumn('university_id');
            }
            if (Schema::hasColumn('rec_results', 'specialization_id')) {
                $table->dropForeign(['specialization_id']);
                $table->dropColumn('specialization_id');
            }
        });

        // =========================================================================
        // 10. جدول reg_docs — حذف الحقول الزائدة
        // المخطط: reg_doc_id, req_id, file_name, file_path, up_at, ver_status, ver_note, verified_at
        // =========================================================================
        Schema::table('reg_docs', function (Blueprint $table) {
            if (Schema::hasColumn('reg_docs', 'doc_id')) {
                try {
                    $table->dropForeign(['doc_id']);
                } catch (\Throwable $e) {}
                $table->dropColumn('doc_id');
            }
            if (Schema::hasColumn('reg_docs', 'document_name')) {
                $table->dropColumn('document_name');
            }
        });

        // =========================================================================
        // 11. جدول PAY_METHOD (pay_methods) ومطابقته بدقة
        // المخطط: method_id, university_id, method_name, method_type, account_name, account_number, alternative_number, instructions, is_active
        // =========================================================================
        if (Schema::hasTable('university_payment_methods') && !Schema::hasTable('pay_methods')) {
            // حذف أي قيد مفتاح خارجي مرتبط بـ id القديم في payments
            try {
                DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_method_id_foreign');
            } catch (\Throwable $e) {}

            // تعديل الحقول
            if (Schema::hasColumn('university_payment_methods', 'id')) {
                DB::statement('ALTER TABLE university_payment_methods CHANGE id method_id INT UNSIGNED AUTO_INCREMENT');
            }

            $payMethodExtraCols = ['provider_name', 'created_at', 'updated_at'];
            Schema::table('university_payment_methods', function (Blueprint $table) use ($payMethodExtraCols) {
                foreach ($payMethodExtraCols as $col) {
                    if (Schema::hasColumn('university_payment_methods', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });

            // إعادة تسمية الجدول إلى pay_methods
            Schema::rename('university_payment_methods', 'pay_methods');

            // إعادة ربط المفتاح الخارجي في payments
            try {
                Schema::table('payments', function (Blueprint $table) {
                    $table->foreign('method_id')
                          ->references('method_id')
                          ->on('pay_methods')
                          ->cascadeOnDelete();
                });
            } catch (\Throwable $e) {}

            // إنشاء view لتوافق أي استعلام قديم
            DB::statement('DROP VIEW IF EXISTS university_payment_methods');
            DB::statement('CREATE VIEW university_payment_methods AS SELECT *, method_id AS id FROM pay_methods');
        }

        // =========================================================================
        // 12. جدول REFERRAL (referrals) ومطابقته بدقة
        // المخطط: referral_id, student_id, university_id, ref_code, ref_date, ref_status, notes
        // =========================================================================
        if (Schema::hasTable('student_referrals') && !Schema::hasTable('referrals')) {
            // مزامنة ref_code من referral_code إذا كان فارغاً
            if (Schema::hasColumn('student_referrals', 'referral_code') && Schema::hasColumn('student_referrals', 'ref_code')) {
                DB::statement('UPDATE student_referrals SET ref_code = referral_code WHERE ref_code IS NULL OR ref_code = ""');
            } elseif (Schema::hasColumn('student_referrals', 'referral_code') && !Schema::hasColumn('student_referrals', 'ref_code')) {
                DB::statement('ALTER TABLE student_referrals CHANGE referral_code ref_code VARCHAR(50) NOT NULL');
            }

            // مزامنة ref_status من status إذا كان فارغاً
            if (Schema::hasColumn('student_referrals', 'status') && Schema::hasColumn('student_referrals', 'ref_status')) {
                DB::statement('UPDATE student_referrals SET ref_status = status WHERE ref_status IS NULL OR ref_status = ""');
            }

            $referralExtraCols = ['referral_code', 'status', 'created_at', 'used_at'];
            Schema::table('student_referrals', function (Blueprint $table) use ($referralExtraCols) {
                foreach ($referralExtraCols as $col) {
                    if (Schema::hasColumn('student_referrals', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });

            // إعادة تسمية الجدول إلى referrals
            Schema::rename('student_referrals', 'referrals');

            // تحديث المفتاح الخارجي في reg_proofs
            try {
                DB::statement('ALTER TABLE reg_proofs DROP FOREIGN KEY reg_proofs_referral_id_foreign');
                Schema::table('reg_proofs', function (Blueprint $table) {
                    $table->foreign('referral_id')
                          ->references('referral_id')
                          ->on('referrals')
                          ->cascadeOnDelete();
                });
            } catch (\Throwable $e) {}

            // إنشاء view لتوافق أي استعلام قديم
            DB::statement('DROP VIEW IF EXISTS student_referrals');
            DB::statement('CREATE VIEW student_referrals AS SELECT * FROM referrals');
        }

        // =========================================================================
        // 13. جدول REG_PROOF (reg_proofs) — تصحيح اسم الحقل ليطابق المخطط
        // المخطط: reg_proof_id, referral_id, receipt_number, registration_date, student_name, submited_at, verification_status, verification_date, verification_result
        // =========================================================================
        if (Schema::hasColumn('reg_proofs', 'submitted_at') && !Schema::hasColumn('reg_proofs', 'submited_at')) {
            DB::statement('ALTER TABLE reg_proofs CHANGE submitted_at submited_at TIMESTAMP NULL');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * تراجع
     */
    public function down(): void
    {
        // 
    }
};
