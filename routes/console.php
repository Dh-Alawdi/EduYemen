<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Advertisement;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// تحويل الإعلانات المنتهية تلقائياً كل ساعة
Schedule::call(function () {
    Advertisement::where('status', 'نشط')
        ->whereNotNull('expires_at')
        ->where('expires_at', '<=', now())
        ->update(['status' => 'منتهي']);
})->hourly()->name('expire-advertisements')->withoutOverlapping();
