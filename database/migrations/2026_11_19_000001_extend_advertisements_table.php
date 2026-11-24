<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: توسيع جدول advertisements بالحقول التكميلية لوظيفة الإعلانات
 *
 * الحقول المضافة:
 *   - receipt_url     : مسار صورة سند إثبات الدفع
 *   - media_type      : نوع الوسائط (image | gallery | video)
 *   - media_files     : JSON array لمسارات الصور (1–5 صور)
 *   - video_url       : مسار ملف الفيديو الترويجي
 *   - wizard_step     : مرحلة المعالج (1=باقة، 2=دفع، 3=محتوى، 4=معاينة)
 *   - published_at    : لحظة اعتماد المدير ونشر الإعلان
 *   - expires_at      : تاريخ انتهاء الإعلان
 *
 * ملاحظة review_status:
 *   الحقل VARCHAR — القيم المسموحة (موثقة هنا، لا تحتاج ALTER):
 *   'قيد المراجعة' | 'مقبول' | 'مرفوض' | 'مطلوب تعديل' | 'مسودة'
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {

            // سند إثبات الدفع
            if (!Schema::hasColumn('advertisements', 'receipt_url')) {
                $table->string('receipt_url', 500)->nullable()->after('pay_reference')
                      ->comment('مسار صورة سند إثبات دفع رسوم الإعلان');
            }

            // نوع الوسائط
            if (!Schema::hasColumn('advertisements', 'media_type')) {
                $table->enum('media_type', ['image', 'gallery', 'video'])->default('image')->after('receipt_url')
                      ->comment('نوع الوسائط: صورة واحدة / معرض صور / فيديو');
            }

            // مسارات الصور بصيغة JSON
            if (!Schema::hasColumn('advertisements', 'media_files')) {
                $table->text('media_files')->nullable()->after('media_type')
                      ->comment('JSON array لمسارات الصور — الحد الأقصى 5 صور');
            }

            // مسار الفيديو
            if (!Schema::hasColumn('advertisements', 'video_url')) {
                $table->string('video_url', 500)->nullable()->after('media_files')
                      ->comment('مسار ملف الفيديو الترويجي — الحد الأقصى 10 ثوانٍ للباقة الشاملة');
            }

            // مرحلة المعالج
            if (!Schema::hasColumn('advertisements', 'wizard_step')) {
                $table->tinyInteger('wizard_step')->default(1)->after('video_url')
                      ->comment('مرحلة المعالج: 1=اختيار الباقة، 2=الدفع وإثباته، 3=إنشاء المحتوى، 4=معاينة وإرسال');
            }

            // لحظة النشر
            if (!Schema::hasColumn('advertisements', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('wizard_step')
                      ->comment('لحظة اعتماد المدير ونشر الإعلان');
            }

            // تاريخ الانتهاء
            if (!Schema::hasColumn('advertisements', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('published_at')
                      ->comment('تاريخ انتهاء الإعلان = published_at + duration يوماً');
            }
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $columns = ['receipt_url', 'media_type', 'media_files', 'video_url', 'wizard_step', 'published_at', 'expires_at'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('advertisements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
