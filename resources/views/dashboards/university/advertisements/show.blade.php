@extends('dashboards.university.layout')

@section('page_title', 'تفاصيل الإعلان #' . $ad->adv_id)

@push('styles')
<style>
    .ad-show-page,
    .ad-show-page *,
    button, input, select, textarea {
        font-family: 'Almarai', 'IBM Plex Sans Arabic', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif !important;
    }
</style>
@endpush

@section('content')
<div class="ad-show-page" style="max-width:960px;margin:0 auto;padding-bottom:50px;">

    <!-- شريط المسار والرجوع -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <a href="{{ route('university.advertisements.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:var(--ink-muted);text-decoration:none;font-weight:600;margin-bottom:6px;">
                <i class="fa-solid fa-arrow-right"></i> العودة لقائمة الإعلانات
            </a>
            <h1 style="font-size:22px;font-weight:800;color:var(--navy);margin:0;">
                تفاصيل الإعلان الترويجي #{{ $ad->adv_id }}
            </h1>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            @if($ad->review_status === 'مسودة')
                <a href="{{ route('university.advertisements.wizard', ['draft_id' => $ad->adv_id]) }}" class="btn btn-warning" style="background:#d97706;color:#fff;font-weight:700;padding:8px 18px;border-radius:var(--radius-sm);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-play"></i> استكمال المسودة
                </a>
            @elseif($ad->review_status === 'مطلوب تعديل')
                <a href="{{ route('university.advertisements.wizard', ['edit_id' => $ad->adv_id]) }}" class="btn" style="background:#ea580c;color:#fff;font-weight:700;padding:8px 18px;border-radius:var(--radius-sm);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-pen-to-square"></i> تصحيح وإعادة الإرسال
                </a>
            @endif
        </div>
    </div>

    <!-- بطاقة التنبيه للملاحظات إن وجدت -->
    @if($ad->notes)
        <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:var(--radius-md);padding:18px 20px;margin-bottom:24px;display:flex;gap:14px;align-items:flex-start;">
            <div style="color:#d97706;font-size:24px;flex-shrink:0;margin-top:2px;">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div>
                <h4 style="font-size:14px;font-weight:800;color:#92400e;margin:0 0 4px;">ملاحظات إدارة المنصة بخصوص هذا الإعلان:</h4>
                <p style="font-size:13px;color:#a16207;margin:0;line-height:1.6;">{{ $ad->notes }}</p>
            </div>
        </div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:24px;">
        <!-- تفاصيل الحملة والمحتوى -->
        <div style="background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);padding:24px;box-shadow:var(--shadow-sm);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:16px;">
                <div>
                    <span class="badge" style="background:#e0f2fe;color:#0369a1;padding:4px 12px;border-radius:20px;font-weight:700;font-size:12px;margin-bottom:6px;display:inline-block;">
                        الباقة: {{ $ad->getPackageLabel() }}
                    </span>
                    <h2 style="font-size:18px;font-weight:800;color:var(--navy);margin:0;">
                        {{ $ad->adv_title ?: 'إعلان بدون عنوان' }}
                    </h2>
                </div>
                <div style="text-align:left;">
                    <div style="font-size:20px;font-weight:900;color:var(--teal);">{{ number_format($ad->amount) }} ر.ي</div>
                    <div style="font-size:12px;color:var(--ink-muted);">المدة: {{ $ad->duration }} أيام</div>
                </div>
            </div>

            <!-- نص الإعلان -->
            <div style="background:var(--surface-subtle);border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;">
                <h5 style="font-size:12px;font-weight:700;color:var(--ink-muted);margin:0 0 6px;">نص ووصف الإعلان:</h5>
                <p style="font-size:13.5px;color:var(--ink);line-height:1.7;margin:0;white-space:pre-wrap;">{{ $ad->adv_content ?: 'لم يتم إدخال وصف بعد' }}</p>
            </div>

            <!-- الوسائط المرفقة -->
            <div style="margin-bottom:20px;">
                <h5 style="font-size:13px;font-weight:800;color:var(--navy);margin:0 0 10px;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-photo-film" style="color:var(--teal);"></i>
                    <span>الوسائط المرفقة بالإعلان:</span>
                </h5>

                @if($ad->media_type === 'video' && $ad->video_url)
                    <div style="border-radius:10px;overflow:hidden;background:#000;">
                        <video src="{{ asset($ad->video_url) }}" controls style="width:100%;max-height:260px;display:block;"></video>
                    </div>
                @elseif(!empty($ad->mediaFilesArray()))
                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(110px, 1fr));gap:12px;">
                        @foreach($ad->mediaFilesArray() as $file)
                            <a href="{{ asset($file) }}" target="_blank" style="display:block;border-radius:8px;overflow:hidden;border:1px solid var(--border);">
                                <img src="{{ asset($file) }}" alt="وسائط الإعلان" style="width:100%;height:110px;object-fit:cover;display:block;" />
                            </a>
                        @endforeach
                    </div>
                @else
                    <p style="color:var(--ink-muted);font-size:12.5px;margin:0;">لم يتم رفع وسائط بعد.</p>
                @endif
            </div>
        </div>

        <!-- معلومات الدفع وحالة المراجعة -->
        <div style="display:flex;flex-direction:column;gap:20px;">
            <!-- حالة الإعلان -->
            <div style="background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);padding:20px;box-shadow:var(--shadow-sm);">
                <h4 style="font-size:14.5px;font-weight:800;color:var(--navy);margin:0 0 16px;">حالة المراجعة والنشر</h4>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                    <div style="background:var(--surface-subtle);padding:12px;border-radius:8px;text-align:center;">
                        <div style="font-size:11px;color:var(--ink-muted);margin-bottom:4px;">حالة المراجعة</div>
                        <div style="font-weight:800;color:var(--navy);font-size:13px;">{{ $ad->review_status }}</div>
                    </div>
                    <div style="background:var(--surface-subtle);padding:12px;border-radius:8px;text-align:center;">
                        <div style="font-size:11px;color:var(--ink-muted);margin-bottom:4px;">حالة النشر</div>
                        <div style="font-weight:800;color:var(--navy);font-size:13px;">{{ $ad->status }}</div>
                    </div>
                </div>

                <div style="font-size:12.5px;display:flex;flex-direction:column;gap:8px;border-top:1px solid var(--border);padding-top:12px;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--ink-muted);">تاريخ الإنشاء:</span>
                        <strong>{{ $ad->created_at ? $ad->created_at->format('Y-m-d H:i') : '—' }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--ink-muted);">تاريخ النشر الفعلي:</span>
                        <strong>{{ $ad->published_at ? $ad->published_at->format('Y-m-d H:i') : 'بانتظار الاعتماد' }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--ink-muted);">تاريخ الانتهاء:</span>
                        <strong>{{ $ad->expires_at ? $ad->expires_at->format('Y-m-d H:i') : '—' }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--ink-muted);">المتبقي من الباقة:</span>
                        <strong style="color:var(--teal);">{{ $ad->remainingDays() }} يوم</strong>
                    </div>
                </div>
            </div>

            <!-- بيانات السداد وإثبات الدفع -->
            <div style="background:#fff;border-radius:var(--radius-md);border:1px solid var(--border);padding:20px;box-shadow:var(--shadow-sm);">
                <h4 style="font-size:14.5px;font-weight:800;color:var(--navy);margin:0 0 14px;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-receipt" style="color:var(--teal);"></i>
                    <span>بيانات وسند السداد</span>
                </h4>

                <div style="font-size:12.5px;display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--ink-muted);">طريقة الدفع:</span>
                        <strong>{{ $ad->pay_method ?: '—' }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--ink-muted);">رقم المرجع / الحوالة:</span>
                        <strong style="font-family:monospace;">{{ $ad->pay_reference ?: '—' }}</strong>
                    </div>
                </div>

                @if($ad->receipt_url)
                    <div style="padding:12px;background:#f8fafc;border-radius:8px;border:1px dashed var(--border);text-align:center;">
                        <div style="font-size:11.5px;color:var(--ink-muted);margin-bottom:8px;">سند إثبات التحويل المرفق:</div>
                        <a href="{{ asset($ad->receipt_url) }}" target="_blank" style="display:inline-block;">
                            <img src="{{ asset($ad->receipt_url) }}" alt="سند الدفع" style="max-height:160px;max-width:100%;border-radius:6px;border:1px solid var(--border);" />
                        </a>
                        <div style="font-size:11px;color:var(--ink-muted);margin-top:6px;">انقر لفتح السند بالحجم الكامل</div>
                    </div>
                @else
                    <p style="color:var(--ink-muted);font-size:12px;margin:0;">لم يتم إرفاق سند الدفع بعد.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
