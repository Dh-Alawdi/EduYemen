<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * استعادة الحقول والجداول الضرورية لضمان تكامل النظام ولوحات تحكم الطالب والجامعة والزائر
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // 1. جدول users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'theme_mode')) {
                $table->string('theme_mode', 20)->default('light')->after('account_status');
            }
            if (!Schema::hasColumn('users', 'theme_color')) {
                $table->string('theme_color', 30)->default('navy')->after('theme_mode');
            }
            if (!Schema::hasColumn('users', 'notifications_muted')) {
                $table->boolean('notifications_muted')->default(false)->after('theme_color');
            }
            if (!Schema::hasColumn('users', 'notification_sound')) {
                $table->boolean('notification_sound')->default(true)->after('notifications_muted');
            }
            if (!Schema::hasColumn('users', 'notification_tone')) {
                $table->string('notification_tone', 50)->default('chime')->after('notification_sound');
            }
            if (!Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable()->after('notification_tone');
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('last_login');
            }
        });

        // 2. جدول students
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'city')) {
                $table->string('city', 100)->nullable()->after('birth_date');
            }
        });

        // 3. جدول admission_data
        Schema::table('admission_data', function (Blueprint $table) {
            if (!Schema::hasColumn('admission_data', 'registration_fee')) {
                $table->decimal('registration_fee', 10, 2)->default(0)->nullable()->after('secondary_last_year');
            }
            if (!Schema::hasColumn('admission_data', 'tuition_fees')) {
                $table->decimal('tuition_fees', 10, 2)->nullable()->after('registration_fee');
            }
            if (!Schema::hasColumn('admission_data', 'admission_start_date')) {
                $table->date('admission_start_date')->nullable()->after('tuition_fees');
            }
            if (!Schema::hasColumn('admission_data', 'admission_end_date')) {
                $table->date('admission_end_date')->nullable()->after('admission_start_date');
            }
            if (!Schema::hasColumn('admission_data', 'admission_status')) {
                $table->string('admission_status', 30)->default('مفتوح')->nullable()->after('admission_end_date');
            }
            if (!Schema::hasColumn('admission_data', 'required_documents')) {
                $table->json('required_documents')->nullable()->after('admission_status');
            }
        });

        // 4. جدول admission_docs
        if (!Schema::hasTable('admission_docs')) {
            Schema::create('admission_docs', function (Blueprint $table) {
                $table->increments('doc_id');
                $table->unsignedInteger('admission_id');
                $table->string('document_name', 150);
                $table->text('description')->nullable();
                $table->boolean('is_required')->default(true);

                $table->foreign('admission_id')
                      ->references('admission_id')
                      ->on('admission_data')
                      ->cascadeOnDelete();
            });
        }

        // 5. جدول admins
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->increments('admin_id');
                $table->unsignedInteger('user_id');
                $table->string('full_name', 150);

                $table->foreign('user_id')
                      ->references('user_id')
                      ->on('users')
                      ->cascadeOnDelete();
            });
        }

        // 6. جدول rec_results
        Schema::table('rec_results', function (Blueprint $table) {
            if (!Schema::hasColumn('rec_results', 'university_id')) {
                $table->unsignedInteger('university_id')->nullable()->after('r_d_id');
                $table->foreign('university_id')
                      ->references('university_id')
                      ->on('universities')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('rec_results', 'specialization_id')) {
                $table->unsignedInteger('specialization_id')->nullable()->after('university_id');
                $table->foreign('specialization_id')
                      ->references('specialization_id')
                      ->on('specializations')
                      ->nullOnDelete();
            }
        });

        // 7. جدول referrals
        Schema::table('referrals', function (Blueprint $table) {
            if (!Schema::hasColumn('referrals', 'ref_code')) {
                $table->string('ref_code', 50)->nullable()->after('university_id');
            }
            if (!Schema::hasColumn('referrals', 'referral_code')) {
                $table->string('referral_code', 50)->nullable()->after('ref_code');
            }
            if (!Schema::hasColumn('referrals', 'status')) {
                $table->string('status', 30)->default('نشط')->after('ref_status');
            }
        });

        // مزامنة الوثائق المطلوبة في admission_docs من admission_data إن وجدت
        $admissions = DB::table('admission_data')->get();
        foreach ($admissions as $adm) {
            if (Schema::hasTable('admission_docs')) {
                $docCount = DB::table('admission_docs')->where('admission_id', $adm->admission_id)->count();
                if ($docCount === 0) {
                    $docName = !empty($adm->document_name) ? $adm->document_name : 'أصل استمارة الثانوية العامة';
                    DB::table('admission_docs')->insert([
                        'admission_id' => $adm->admission_id,
                        'document_name' => $docName,
                        'description' => $adm->description ?? 'صورة طبق الأصل أو الأصل للمطابقة',
                        'is_required' => $adm->is_required ?? 1,
                    ]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
