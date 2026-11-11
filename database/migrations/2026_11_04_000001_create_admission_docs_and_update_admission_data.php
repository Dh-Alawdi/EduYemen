<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. تحديث جدول بيانات القبول والتسجيل لإضافة الرسوم وفترة القبول وفق ملف التوثيق
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
        });

        // 2. إنشاء جدول وثائق القبول المطلوبة ADMISSION_DOC (جدول 3.27 في ملف التوثيق)
        if (!Schema::hasTable('admission_docs')) {
            Schema::create('admission_docs', function (Blueprint $table) {
                $table->increments('doc_id');
                $table->unsignedInteger('admission_id');
                $table->string('document_name', 150);
                $table->text('description')->nullable();
                $table->boolean('is_required')->default(true);

                $table->foreign('admission_id')->references('admission_id')->on('admission_data')->cascadeOnDelete();
            });
        }

        // 3. ترحيل الوثائق الموجودة في حقل required_documents إلى جدول admission_docs وتحديث الرسوم والتواريخ الافتراضية
        $admissions = DB::table('admission_data')->get();
        foreach ($admissions as $adm) {
            // تحديث رسوم التسجيل والتواريخ الافتراضية إذا كانت فارغة
            DB::table('admission_data')
                ->where('admission_id', $adm->admission_id)
                ->update([
                    'registration_fee' => $adm->registration_fee ?: 5000.00,
                    'admission_start_date' => $adm->admission_start_date ?: '2026-06-01',
                    'admission_end_date' => $adm->admission_end_date ?: '2026-09-30',
                    'admission_status' => $adm->admission_status ?: 'مفتوح',
                ]);

            if (!empty($adm->required_documents)) {
                $docs = json_decode($adm->required_documents, true);
                if (is_array($docs)) {
                    foreach ($docs as $doc) {
                        $docName = is_array($doc) ? ($doc['name'] ?? null) : (string)$doc;
                        $docDesc = is_array($doc) ? ($doc['description'] ?? null) : null;
                        $isReq = is_array($doc) ? (!empty($doc['is_required'])) : true;

                        if ($docName) {
                            // تجنب التكرار
                            $exists = DB::table('admission_docs')
                                ->where('admission_id', $adm->admission_id)
                                ->where('document_name', $docName)
                                ->exists();

                            if (!$exists) {
                                DB::table('admission_docs')->insert([
                                    'admission_id' => $adm->admission_id,
                                    'document_name' => $docName,
                                    'description' => $docDesc,
                                    'is_required' => $isReq,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_docs');

        Schema::table('admission_data', function (Blueprint $table) {
            $table->dropColumn([
                'registration_fee',
                'tuition_fees',
                'admission_start_date',
                'admission_end_date',
                'admission_status',
            ]);
        });
    }
};
