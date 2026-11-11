<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إكمال مخطط قاعدة البيانات وفق ERD المعتمد في وثيقة تحليل النظام
     * الجداول المضافة: admins, notifications, discounts, payments,
     *   advertisements, study_plans, semesters, courses,
     *   rec_data, rec_results, reg_proofs
     * التعديلات: students (nationality), reg_docs (doc_id FK),
     *   university_payment_methods (method_type, alternative_number),
     *   student_referrals (ref_code, ref_date, ref_status, notes)
     */
    public function up(): void
    {
        // ============================================================
        // 1. تعديل جدول students — إضافة nationality حسب ERD
        // ============================================================
        if (!Schema::hasColumn('students', 'nationality')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('nationality', 50)->nullable()->after('full_name');
            });
        }

        // ============================================================
        // 2. تعديل جدول reg_docs — إضافة doc_id (FK→admission_docs)
        //    حسب ERD: REG_DOC.doc_id(NULL) يرتبط بـ ADMISSION_DOC
        // ============================================================
        if (!Schema::hasColumn('reg_docs', 'doc_id')) {
            Schema::table('reg_docs', function (Blueprint $table) {
                $table->unsignedInteger('doc_id')->nullable()->after('req_id');
                $table->foreign('doc_id')
                      ->references('doc_id')
                      ->on('admission_docs')
                      ->nullOnDelete();
            });
        }

        // ============================================================
        // 3. تعديل جدول student_referrals — مطابقة حقول ERD
        //    ERD: ref_code, ref_date, ref_status, notes
        // ============================================================
        Schema::table('student_referrals', function (Blueprint $table) {
            if (!Schema::hasColumn('student_referrals', 'ref_code')) {
                // referral_code موجود، نضيف ref_code كـ alias أو نعيد تسميته
                // نضيف ref_date وref_status وnotes لأنها غير موجودة
            }
            if (!Schema::hasColumn('student_referrals', 'ref_date')) {
                $table->date('ref_date')->nullable()->after('referral_code');
            }
            if (!Schema::hasColumn('student_referrals', 'ref_status')) {
                $table->string('ref_status', 30)->default('نشط')->after('ref_date');
                // status موجود لكن نضيف ref_status للتوافق مع ERD
            }
            if (!Schema::hasColumn('student_referrals', 'notes')) {
                $table->text('notes')->nullable()->after('ref_status');
            }
        });

        // ============================================================
        // 4. تعديل university_payment_methods — إضافة حقول ERD
        //    ERD: method_type, alternative_number
        // ============================================================
        Schema::table('university_payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('university_payment_methods', 'method_name')) {
                $table->string('method_name', 100)->nullable()->after('university_id');
            }
            if (!Schema::hasColumn('university_payment_methods', 'method_type')) {
                $table->string('method_type', 50)->nullable()->after('method_name');
            }
            if (!Schema::hasColumn('university_payment_methods', 'alternative_number')) {
                $table->string('alternative_number', 50)->nullable()->after('account_number');
            }
        });

        // ============================================================
        // 5. جدول ADMIN — مدير النظام
        // ============================================================
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->increments('admin_id');
                $table->unsignedInteger('user_id')->unique();
                $table->string('full_name', 150);
                $table->timestamps();

                $table->foreign('user_id')
                      ->references('user_id')
                      ->on('users')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 6. جدول NOTIFICATIONS — الإشعارات
        // ============================================================
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->increments('notification_id');
                $table->unsignedInteger('user_id');
                $table->string('notification_type', 50); // قبول, رفض, إشعار, نظام, تنبيه
                $table->string('title', 200);
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('user_id')
                      ->references('user_id')
                      ->on('users')
                      ->cascadeOnDelete();

                $table->index(['user_id', 'is_read']);
            });
        }

        // ============================================================
        // 7. جدول PAYMENT — عملية الدفع
        //    مرتبط بـ req_id (REG_REQUEST) وmethod_id (PAY_METHOD)
        // ============================================================
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->increments('payment_id');
                $table->unsignedInteger('req_id');
                $table->unsignedInteger('method_id')->nullable(); // طريقة الدفع
                $table->decimal('amount', 12, 2);
                $table->date('pay_date')->nullable();
                $table->string('reference', 150)->nullable(); // رقم العملية/المرجع
                $table->string('proof_file', 500)->nullable(); // إيصال الدفع
                $table->string('ver_status', 30)->default('قيد التحقق'); // قيد التحقق, مؤكد, مرفوض
                $table->date('ver_date')->nullable();    // تاريخ التحقق
                $table->string('ver_result', 100)->nullable(); // نتيجة التحقق
                $table->text('notes')->nullable();

                $table->foreign('req_id')
                      ->references('req_id')
                      ->on('reg_requests')
                      ->cascadeOnDelete();

                $table->foreign('method_id')
                      ->references('id')
                      ->on('university_payment_methods')
                      ->nullOnDelete();
            });
        }

        // ============================================================
        // 9. جدول ADVERTISEMENT — الإعلانات الجامعية
        // ============================================================
        if (!Schema::hasTable('advertisements')) {
            Schema::create('advertisements', function (Blueprint $table) {
                $table->increments('adv_id');
                $table->unsignedInteger('university_id');
                $table->string('adv_title', 300);
                $table->text('adv_content')->nullable();
                $table->string('package', 100)->nullable();    // نوع الباقة الإعلانية
                $table->integer('duration')->nullable();        // المدة بالأيام
                $table->decimal('amount', 12, 2)->nullable();  // قيمة الإعلان
                $table->string('pay_method', 100)->nullable(); // طريقة الدفع
                $table->string('pay_reference', 150)->nullable(); // مرجع الدفع
                $table->string('review_status', 30)->default('قيد المراجعة'); // قيد المراجعة, مقبول, مرفوض
                $table->string('status', 30)->default('غير نشط'); // نشط, غير نشط, منتهي
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('university_id')
                      ->references('university_id')
                      ->on('universities')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 10. جدول STUDY_PLAN — الخطة الدراسية
        //     مرتبط بـ specialization_id
        // ============================================================
        if (!Schema::hasTable('study_plans')) {
            Schema::create('study_plans', function (Blueprint $table) {
                $table->increments('study_plan_id');
                $table->unsignedInteger('specialization_id');
                $table->string('plan_name', 200);
                $table->tinyInteger('duration_years')->default(4); // عدد سنوات الدراسة
                $table->smallInteger('total_credit_hours')->default(0);
                $table->string('status', 20)->default('نشط'); // نشط, موقوف, مؤرشف

                $table->foreign('specialization_id')
                      ->references('specialization_id')
                      ->on('specializations')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 11. جدول SEMESTERS — الفصول الدراسية
        //     مرتبط بـ study_plan_id
        // ============================================================
        if (!Schema::hasTable('semesters')) {
            Schema::create('semesters', function (Blueprint $table) {
                $table->increments('semester_id');
                $table->unsignedInteger('study_plan_id');
                $table->string('plan_name', 200)->nullable();   // اسم/وصف الفصل
                $table->tinyInteger('semester_number');         // رقم الفصل (1,2,3...)

                $table->foreign('study_plan_id')
                      ->references('study_plan_id')
                      ->on('study_plans')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 12. جدول COURSES — المقررات الدراسية
        //     مرتبط بـ semester_id
        // ============================================================
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->increments('course_id');
                $table->unsignedInteger('semester_id');
                $table->string('course_name', 200);
                $table->string('course_code', 30)->nullable(); // رمز المقرر
                $table->tinyInteger('hours')->default(3);       // الساعات المعتمدة
                $table->string('course_type', 50)->nullable();  // إجباري, اختياري, متطلب جامعة
                $table->text('description')->nullable();

                $table->foreign('semester_id')
                      ->references('semester_id')
                      ->on('semesters')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 13. جدول REC_DATA — بيانات التوصية (للمساعد الذكي)
        //     مرتبط بـ student_id
        // ============================================================
        if (!Schema::hasTable('rec_data')) {
            Schema::create('rec_data', function (Blueprint $table) {
                $table->increments('r_d_id');
                $table->unsignedInteger('student_id');
                $table->decimal('average', 5, 2)->nullable();   // المعدل الدراسي
                $table->text('interests')->nullable();           // الاهتمامات
                $table->text('hobbies')->nullable();             // الهوايات
                $table->text('abilities')->nullable();           // القدرات والمهارات
                $table->text('preferences')->nullable();         // التفضيلات الأكاديمية
                $table->string('location', 150)->nullable();     // الموقع الجغرافي المفضل

                $table->foreign('student_id')
                      ->references('student_id')
                      ->on('students')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 14. جدول REC_RESULT — نتائج التوصية (مخرجات المساعد الذكي)
        //     مرتبط بـ r_d_id
        // ============================================================
        if (!Schema::hasTable('rec_results')) {
            Schema::create('rec_results', function (Blueprint $table) {
                $table->increments('result_id');
                $table->unsignedInteger('r_d_id');
                $table->string('univ_name', 200);              // اسم الجامعة المقترحة
                $table->string('spec_name', 200);              // اسم التخصص المقترح
                $table->decimal('match_percentage', 5, 2)->nullable(); // نسبة التوافق %
                $table->tinyInteger('result_order')->default(1);       // ترتيب النتيجة
                $table->text('notes')->nullable();              // ملاحظات التوصية

                $table->foreign('r_d_id')
                      ->references('r_d_id')
                      ->on('rec_data')
                      ->cascadeOnDelete();
            });
        }

        // ============================================================
        // 15. جدول REG_PROOF — إثبات إتمام التسجيل
        //     مرتبط بـ referral_id (UNIQUE — كل إحالة لها إثبات واحد)
        // ============================================================
        if (!Schema::hasTable('reg_proofs')) {
            Schema::create('reg_proofs', function (Blueprint $table) {
                $table->increments('reg_proof_id');
                $table->unsignedInteger('referral_id')->unique(); // علاقة واحد-لواحد مع الإحالة
                $table->string('receipt_number', 100)->nullable();     // رقم الإيصال
                $table->date('registration_date')->nullable();         // تاريخ التسجيل
                $table->string('student_name', 150)->nullable();       // اسم الطالب
                $table->timestamp('submitted_at')->nullable();         // وقت التقديم
                $table->string('verification_status', 30)->default('قيد التحقق'); // قيد التحقق, تم التحقق, مرفوض
                $table->date('verification_date')->nullable();         // تاريخ التحقق
                $table->string('verification_result', 100)->nullable(); // نتيجة التحقق

                $table->foreign('referral_id')
                      ->references('referral_id')
                      ->on('student_referrals')
                      ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف بالترتيب العكسي لتجنب أخطاء المفاتيح الخارجية
        Schema::dropIfExists('reg_proofs');
        Schema::dropIfExists('rec_results');
        Schema::dropIfExists('rec_data');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('study_plans');
        Schema::dropIfExists('advertisements');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('admins');

        // إزالة الحقول المضافة
        if (Schema::hasColumn('reg_proofs', 'doc_id')) {
            Schema::table('reg_docs', function (Blueprint $table) {
                $table->dropForeign(['doc_id']);
                $table->dropColumn('doc_id');
            });
        }
        if (Schema::hasColumn('students', 'nationality')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('nationality');
            });
        }
        Schema::table('student_referrals', function (Blueprint $table) {
            foreach (['ref_date', 'ref_status', 'notes'] as $col) {
                if (Schema::hasColumn('student_referrals', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        Schema::table('university_payment_methods', function (Blueprint $table) {
            foreach (['method_name', 'method_type', 'alternative_number'] as $col) {
                if (Schema::hasColumn('university_payment_methods', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
