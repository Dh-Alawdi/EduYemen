@extends('dashboards.university.layout')

@section('page_title', 'الحملات والإعلانات الممولة')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* تطبيق الخط الطبيعي لجميع عناصر ونصوص الصفحة مع حماية تامة لخطوط الأيقونات */
    .ads-management-wrap,
    .ads-management-wrap *:not(i):not([class*="fa-"]):not([class*="bi-"]),
    .custom-modal-overlay,
    .custom-modal-overlay *:not(i):not([class*="fa-"]):not([class*="bi-"]) {
        font-family: 'Almarai', 'IBM Plex Sans Arabic', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif !important;
    }

    /* إصلاح جذري لعرض أيقونات FontAwesome و Bootstrap Icons ومنع استبدال خطوطها */
    i.fa, i.fas, i.far, i.fal, i.fad, i.fab, i.fa-solid, i.fa-regular, i.fa-brands, [class*="fa-"], [class^="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        font-style: normal;
        display: inline-block;
        line-height: 1;
    }
    i.bi, [class^="bi-"], [class*=" bi-"] {
        font-family: "bootstrap-icons" !important;
        font-style: normal;
        display: inline-block;
        line-height: 1;
    }

    /* تحسينات عامة على بطاقات الإعلانات */
    .ad-vertical-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(16, 42, 67, 0.12) !important;
        border-color: var(--teal) !important;
    }

    .ad-card-media-top {
        cursor: pointer;
        position: relative;
        background: linear-gradient(135deg, #0a1e32 0%, #153c5e 100%);
        overflow: hidden;
    }
    .ad-card-media-top img {
        transition: transform 0.3s ease;
    }
    .ad-card-media-top:hover img {
        transform: scale(1.04);
    }

    /* أشكال الحبوب البيضاوية لتعبئة العناوين تلقائياً */
    .title-pill-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .title-pill-chip:hover {
        background: #dcfce7;
        border-color: #86efac;
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(22, 101, 52, 0.12);
    }
    .title-pill-chip.active {
        background: #166534;
        color: #ffffff;
        border-color: #166534;
        box-shadow: 0 4px 12px rgba(22, 101, 52, 0.25);
    }

    /* بطاقات الأوصاف المقترحة */
    .suggested-desc-card {
        padding: 10px 14px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 12px;
        line-height: 1.6;
        color: #334155;
    }
    .suggested-desc-card:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #1d4ed8;
    }

    /* تصميم بطاقات الباقات الإعلانية الموحد والمستوحى من المعايير العالمية */
    .global-pricing-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px 18px;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .global-pricing-card:hover {
        border-color: #2b8a85;
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(43, 138, 133, 0.12);
    }
    .global-pricing-card.selected {
        border-color: #2b8a85 !important;
        background: #fbfdfd;
        box-shadow: 0 0 0 3px rgba(43, 138, 133, 0.2), 0 12px 30px rgba(43, 138, 133, 0.15);
    }
    .pricing-radio-indicator {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .global-pricing-card.selected .pricing-radio-indicator {
        border-color: #2b8a85;
        background: #2b8a85;
    }
    .pricing-radio-indicator::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ffffff;
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s ease;
    }
    .global-pricing-card.selected .pricing-radio-indicator::after {
        opacity: 1;
        transform: scale(1);
    }

    /* البطاقات المائية الشفافة لمعالج الإعلان */
    .stepper-watermark-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 14px;
        padding: 12px 18px;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .stepper-watermark-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(228, 183, 91, 0.25), transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .stepper-watermark-card:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(228, 183, 91, 0.6);
        transform: translateY(-2px);
    }
    .stepper-watermark-card.active {
        background: rgba(255, 255, 255, 0.24);
        border-color: #e4b75b !important;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.25);
    }
    .stepper-watermark-card.active::before {
        opacity: 1;
    }
    .stepper-badge-num {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        color: #fff;
        flex-shrink: 0;
    }
    .stepper-watermark-card.active .stepper-badge-num {
        background: #e4b75b;
        color: #0a1b2a;
        border-color: #ffffff;
    }

    /* المعاينة الحية للإعلان المطابقة للواجهة المعروضة للطالب */
    .student-preview-wrapper {
        background: #081a2e;
        border-radius: 18px;
        padding: 16px;
        border: 1.5px solid rgba(228, 183, 91, 0.4);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        color: #ffffff;
    }
    .student-mock-card {
        background: rgba(10, 42, 74, 0.95);
        border-radius: 16px;
        border: 1px solid rgba(197, 155, 39, 0.5);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .student-mock-media {
        width: 100%;
        height: 160px;
        background: #051424;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .student-mock-media img,
    .student-mock-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .student-mock-info {
        padding: 14px 16px;
    }
    .btn-mock-uni {
        background: linear-gradient(135deg, #e4b75b 0%, #c9932b 100%);
        color: #091b2c !important;
        font-weight: 800;
        font-size: 12px;
        padding: 7px 16px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        border: none;
        box-shadow: 0 3px 10px rgba(228, 183, 91, 0.3);
    }

    /* استجابة الشاشات */
    @media (max-width: 1024px) {
        .header-split-grid {
            grid-template-columns: 1fr !important;
        }
        .ads-vertical-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 16px !important;
            padding: 16px !important;
        }
        .stepper-watermark-grid {
            grid-template-columns: 1fr !important;
        }
        .wizard-step2-grid {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 640px) {
        .ads-vertical-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@section('content')
<div class="ads-management-wrap">

    @php
        $hasActive = isset($activeAds) && $activeAds->isNotEmpty();
        $hasExpired = isset($expiredAds) && $expiredAds->isNotEmpty();
        $topAd = $hasActive ? $activeAds->first() : ($hasExpired ? $expiredAds->first() : null);
    @endphp

    @if($hasActive || $hasExpired)
        <!-- عرض 50/50: نصف للحاوية التعريفية ونصف لعرض الإعلان النشط / المنتهي -->
        <div class="header-split-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
            
            <!-- النصف الأول: الحاوية التعريفية والترحيبية مع زر فتح معالج الإنشاء -->
            <div class="page-title-banner" style="display:flex;flex-direction:column;justify-content:space-between;padding:24px 26px;background:linear-gradient(135deg, #102a43, #183b5c);border-radius:var(--radius-md);color:#fff;box-shadow:var(--shadow-md);position:relative;overflow:hidden;">
                <div style="position:relative;z-index:2;">
                    <div style="display:inline-flex;align-items:center;gap:8px;font-size:12px;color:var(--gold);font-weight:700;margin-bottom:8px;">
                        <i class="fa-solid fa-rectangle-ad"></i>
                        <span>الخدمات الترويجية والحملات التسويقية</span>
                    </div>
                    <h1 style="font-size:21px;font-weight:800;margin-bottom:8px;color:#fff;line-height:1.3;">
                        الحملات والإعلانات الممولة
                    </h1>
                    <p style="font-size:12.5px;color:#cbd5e1;line-height:1.6;margin:0 0 16px;">
                        أطلق حملات إعلانية مميزة لجامعتك لتتصدر الصفحة الرئيسية ودليل الجامعات وتصل لعشرات الآلاف من الطلبة الباحثين عن تخصصاتهم الجامعية.
                    </p>
                </div>

                <div style="position:relative;z-index:2;display:flex;gap:12px;align-items:center;flex-wrap:wrap;padding-top:12px;border-top:1px solid rgba(255,255,255,0.12);">
                    <button type="button" onclick="openNewAdModal()" class="btn-create-ad" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:linear-gradient(135deg, var(--gold), #d99a22);color:#0a1b2a;font-weight:800;font-size:13.5px;border-radius:var(--radius-md);border:none;cursor:pointer;box-shadow:0 4px 15px rgba(228,183,91,0.3);transition:all 0.25s ease;">
                        <i class="fa-solid fa-plus-circle"></i>
                        <span>إطلاق إعلان جديد</span>
                    </button>
                    <span style="font-size:12px;color:#94a3b8;">
                        <i class="fa-solid fa-layer-group"></i> إجمالي إعلاناتك: {{ $ads->count() }}
                    </span>
                </div>

                <!-- زخرفة الخلفية -->
                <div style="position:absolute;left:-25px;bottom:-35px;opacity:0.06;font-size:150px;color:#fff;pointer-events:none;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>

            <!-- النصف الثاني: كرت الإعلان النشط أو المنتهي -->
            @if($hasActive)
                <!-- حالة وجود إعلان نشط -->
                <div class="header-ad-showcase-card" style="background:#ffffff;border:1.5px solid #10b981;border-radius:var(--radius-md);box-shadow:var(--shadow-md);display:flex;flex-direction:column;overflow:hidden;position:relative;">
                    
                    <div style="padding:10px 18px;background:linear-gradient(135deg, #065f46, #047857);color:#fff;display:flex;justify-content:space-between;align-items:center;font-size:12px;">
                        <div style="display:inline-flex;align-items:center;gap:8px;font-weight:700;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#34d399;display:inline-block;box-shadow:0 0 8px #34d399;"></span>
                            <span>إعلان نشط ومنشور حالياً للطلبة</span>
                        </div>
                        <div style="display:inline-flex;align-items:center;gap:6px;">
                            @if($activeAds->count() > 1)
                                <span style="background:rgba(255,255,255,0.2);padding:2px 8px;border-radius:10px;font-size:11px;">
                                    لديك {{ $activeAds->count() }} إعلانات نشطة
                                </span>
                            @endif
                            <span class="badge" style="background:rgba(255,255,255,0.25);color:#fff;padding:3px 10px;border-radius:12px;font-weight:700;font-size:11px;">
                                {{ $topAd->getPackageLabel() }}
                            </span>
                        </div>
                    </div>

                    <div style="padding:16px 18px;display:flex;gap:16px;flex:1;align-items:center;">
                        <div onclick="viewAdDetails({{ $topAd->adv_id }})" style="width:110px;height:90px;border-radius:8px;overflow:hidden;background:linear-gradient(135deg, #0e2a47, #18486f);flex-shrink:0;position:relative;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                            @if($topAd->media_type === 'video' && $topAd->formattedVideoUrl())
                                <video src="{{ $topAd->formattedVideoUrl() }}" style="width:100%;height:100%;object-fit:cover;"></video>
                                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                            @elseif($topAd->firstMediaUrl())
                                <img src="{{ $topAd->firstMediaUrl() }}" alt="{{ $topAd->adv_title }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                                <div style="display:none;width:100%;height:100%;align-items:center;justify-content:center;color:#f0c842;font-size:24px;">
                                    <i class="fa-solid fa-rectangle-ad"></i>
                                </div>
                            @else
                                <i class="fa-solid fa-rectangle-ad" style="color:#64748b;font-size:28px;"></i>
                            @endif

                            @if($topAd->media_type === 'gallery')
                                <span style="position:absolute;bottom:4px;right:4px;background:rgba(0,0,0,0.7);color:#fff;font-size:10px;padding:1px 5px;border-radius:4px;">
                                    <i class="fa-solid fa-images"></i> {{ count($topAd->mediaFilesArray()) }}
                                </span>
                            @endif
                        </div>

                        <div style="flex:1;min-width:0;">
                            <h4 style="font-size:14.5px;font-weight:800;color:var(--navy);margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $topAd->adv_title ?: 'إعلان ترويجي للجامعة' }}
                            </h4>
                            <p style="font-size:12px;color:var(--ink-muted);margin:0 0 10px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $topAd->adv_content ?: 'لا يوجد محتوى مضاف' }}
                            </p>

                            <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                                <div style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#065f46;font-weight:700;">
                                    <i class="fa-solid fa-hourglass-start" style="color:#10b981;"></i>
                                    <span>الوقت المتبقي: <strong>{{ $topAd->remainingDays() }} يوم</strong></span>
                                </div>
                                <div style="font-size:11px;color:#047857;">
                                    ينتهي في: {{ $topAd->expires_at ? $topAd->expires_at->format('Y-m-d') : '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="padding:10px 18px;background:var(--surface-subtle);border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11.5px;color:var(--ink-muted);">
                            <i class="fa-solid fa-location-dot" style="color:var(--teal);"></i> 
                            {{ in_array('universities', $topAd->getPackageConfig()['locations'] ?? []) ? 'الرئيسية ودليل الجامعات' : 'الصفحة الرئيسية' }}
                        </span>
                        <div style="display:flex;gap:8px;">
                            <button type="button" class="btn btn-sm" onclick="viewAdDetails({{ $topAd->adv_id }})" style="background:var(--navy);color:#fff;border-radius:6px;padding:5px 14px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;border:none;cursor:pointer;">
                                <i class="fa-solid fa-eye"></i>
                                <span>عرض التفاصيل</span>
                            </button>
                            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-ghost" style="background:#fff;border:1px solid var(--border);color:var(--navy);border-radius:6px;padding:5px 10px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;" title="معاينة في الموقع">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($hasExpired)
                <!-- حالة انتهاء الإعلان مع إمكانية التجديد السريع -->
                <div class="header-ad-showcase-card" style="background:#ffffff;border:1.5px dashed #f59e0b;border-radius:var(--radius-md);box-shadow:var(--shadow-sm);display:flex;flex-direction:column;overflow:hidden;position:relative;">
                    
                    <div style="padding:10px 18px;background:linear-gradient(135deg, #78350f, #92400e);color:#fff;display:flex;justify-content:space-between;align-items:center;font-size:12px;">
                        <div style="display:inline-flex;align-items:center;gap:8px;font-weight:700;">
                            <i class="fa-solid fa-clock-rotate-left" style="color:#fbbf24;"></i>
                            <span>إعلان منتهي الصلاحية</span>
                        </div>
                        <span class="badge" style="background:rgba(255,255,255,0.25);color:#fff;padding:3px 10px;border-radius:12px;font-weight:700;font-size:11px;">
                            {{ $topAd->getPackageLabel() }}
                        </span>
                    </div>

                    <div style="padding:16px 18px;display:flex;gap:16px;flex:1;align-items:center;">
                        <div onclick="viewAdDetails({{ $topAd->adv_id }})" style="width:110px;height:90px;border-radius:8px;overflow:hidden;background:linear-gradient(135deg, #0e2a47, #18486f);flex-shrink:0;position:relative;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                            @if($topAd->firstMediaUrl())
                                <img src="{{ $topAd->firstMediaUrl() }}" alt="{{ $topAd->adv_title }}" style="width:100%;height:100%;object-fit:cover;opacity:0.7;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                                <div style="display:none;width:100%;height:100%;align-items:center;justify-content:center;color:#fbbf24;font-size:24px;">
                                    <i class="fa-solid fa-rectangle-ad"></i>
                                </div>
                            @else
                                <i class="fa-solid fa-rectangle-ad" style="color:#94a3b8;font-size:28px;"></i>
                            @endif
                        </div>

                        <div style="flex:1;min-width:0;">
                            <h4 style="font-size:14.5px;font-weight:800;color:var(--navy);margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $topAd->adv_title ?: 'إعلان ترويجي منتهي' }}
                            </h4>
                            <p style="font-size:12px;color:var(--ink-muted);margin:0 0 8px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $topAd->adv_content ?: 'لا يوجد محتوى' }}
                            </p>
                            <div style="font-size:11.5px;color:#b45309;font-weight:700;">
                                انتهت فترة الحملة في: {{ $topAd->expires_at ? $topAd->expires_at->format('Y-m-d') : '—' }}
                            </div>
                        </div>
                    </div>

                    <div style="padding:10px 18px;background:var(--surface-subtle);border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:11.5px;color:var(--ink-muted);">
                            <i class="fa-solid fa-circle-exclamation"></i> يمكنك إعادة تنشيط الحملة الآن
                        </span>
                        <div style="display:flex;gap:8px;">
                            <button type="button" class="btn btn-sm btn-ghost" onclick="viewAdDetails({{ $topAd->adv_id }})" style="background:#fff;border:1px solid #d1d5db;color:var(--navy);border-radius:6px;padding:6px 14px;font-size:12px;font-weight:700;cursor:pointer;">
                                <i class="fa-solid fa-eye"></i> عرض المحتوى
                            </button>
                            <button type="button" onclick="openNewAdModal('{{ $topAd->package }}')" class="btn btn-sm" style="background:#d97706;color:#fff;border-radius:6px;padding:6px 16px;font-size:12px;font-weight:800;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 8px rgba(217,119,6,0.3);transition:all 0.2s ease;">
                                <i class="fa-solid fa-arrows-rotate"></i>
                                <span>تجديد الإعلان</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    @else
        <!-- لا يوجد إعلانات نشطة أو منتهية: بانر تعريفي كامل العرض -->
        <div class="page-title-banner mb-4" style="display:flex;justify-content:space-between;align-items:center;padding:26px 30px;background:linear-gradient(135deg, #102a43, #183b5c);border-radius:var(--radius-md);color:#fff;box-shadow:var(--shadow-md);position:relative;overflow:hidden;margin-bottom:24px;">
            <div style="position:relative;z-index:2;max-width:650px;">
                <div style="display:inline-flex;align-items:center;gap:8px;font-size:12px;color:var(--gold);font-weight:700;margin-bottom:8px;">
                    <i class="fa-solid fa-rectangle-ad"></i>
                    <span>الخدمات الترويجية والحملات التسويقية</span>
                </div>
                <h1 style="font-size:22px;font-weight:800;margin-bottom:8px;color:#fff;">
                    الحملات والإعلانات الممولة
                </h1>
                <p style="font-size:13px;color:#cbd5e1;line-height:1.6;margin:0;">
                    أطلق حملات إعلانية مميزة لجامعتك لتتصدر الصفحة الرئيسية ودليل الجامعات وتصل لعشرات الآلاف من الطلبة الباحثين عن تخصصاتهم الجامعية.
                </p>
            </div>
            <div style="position:relative;z-index:2;">
                <button type="button" onclick="openNewAdModal()" class="btn-create-ad" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:linear-gradient(135deg, var(--gold), #d99a22);color:#0a1b2a;font-weight:800;font-size:14px;border-radius:var(--radius-md);border:none;cursor:pointer;box-shadow:0 4px 15px rgba(228,183,91,0.3);transition:all 0.25s ease;">
                    <i class="fa-solid fa-plus-circle" style="font-size:16px;"></i>
                    <span>إطلاق إعلان جديد</span>
                </button>
            </div>
            <div style="position:absolute;left:-20px;bottom:-30px;opacity:0.06;font-size:160px;color:#fff;pointer-events:none;">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
        </div>
    @endif

    {{-- رسائل التنبيه والنجاح --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;">
            <i class="fa-solid fa-circle-check" style="color:#10b981;font-size:18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;">
            <i class="fa-solid fa-circle-info" style="color:#3b82f6;font-size:18px;"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;">
            <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:18px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- تنبيه بوجود مسودة معلقة غير مكتملة --}}
    @if(isset($pendingDraft) && $pendingDraft)
        <div class="draft-alert-banner mb-4 p-3 rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-3" style="background:#fef3c7;border:1.5px solid #f59e0b;color:#92400e;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:42px;height:42px;border-radius:10px;background:#fde68a;display:flex;align-items:center;justify-content:center;color:#b45309;font-size:20px;flex-shrink:0;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h5 style="margin:0 0 2px;font-size:14px;font-weight:800;color:#78350f;">
                        لديك مسودة إعلان غير مكتملة (الباقة: {{ $pendingDraft->package }})
                    </h5>
                    <p style="margin:0;font-size:12px;color:#92400e;">
                        تم حفظ بيانات الإعلان الأولية بتاريخ {{ $pendingDraft->created_at->format('Y-m-d') }}. يمكنك استكمال الخطوات وإرسالها للمراجعة.
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" onclick="resumeDraftInModal({{ $pendingDraft->adv_id }})" class="btn btn-sm" style="background:#b45309;color:#fff;border-radius:var(--radius-sm);padding:8px 16px;font-weight:800;font-size:12.5px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <span>متابعة إكمال المسودة</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button type="button" class="btn btn-sm" onclick="cancelDraftAd({{ $pendingDraft->adv_id }})" title="حذف المسودة" style="background:#fee2e2;color:#b91c1c;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:8px 12px;font-size:12px;cursor:pointer;">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- قائمة وبطاقات الإعلانات المسجلة للجامعة -->
    <div class="card-box" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);overflow:hidden;">
        
        <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <h3 style="font-size:16px;font-weight:800;color:var(--navy);margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-list-check" style="color:var(--teal);"></i>
                    <span>سجل حملات وإعلانات الجامعة</span>
                </h3>
            </div>
            <div style="display:flex;gap:10px;align-items:center;">
                <button type="button" onclick="openNewAdModal()" class="btn btn-sm" style="background:var(--teal);color:#fff;border-radius:8px;padding:7px 14px;font-weight:700;font-size:12px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-plus"></i>
                    <span>إعلان جديد</span>
                </button>
                <span class="badge" style="background:var(--teal-subtle);color:var(--teal);padding:6px 14px;border-radius:20px;font-weight:800;font-size:12.5px;">
                    {{ $ads->count() }} إعلان مسجل
                </span>
            </div>
        </div>

        <!-- شبكة البطاقات (3 بطاقات في الصف الواحد بالشاشات الكبيرة) -->
        <div class="ads-vertical-grid" style="display:grid;grid-template-columns:repeat(3, 1fr);gap:22px;padding:24px;">
            @forelse($ads as $ad)
                <div class="ad-vertical-card" style="background:#ffffff;border:1px solid var(--border);border-radius:var(--radius-md);box-shadow:var(--shadow-sm);display:flex;flex-direction:column;overflow:hidden;transition:all 0.25s ease;position:relative;">
                    
                    <!-- 1. منطقة الوسائط أعلى البطاقة مع إمكانية النقر للعرض والـ fallback الأنيق -->
                    <div class="ad-card-media-top" onclick="viewAdDetails({{ $ad->adv_id }})" title="انقر لعرض تفاصيل الإعلان" style="height:185px;width:100%;position:relative;background:linear-gradient(135deg, #091e33 0%, #153c5e 100%);overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if($ad->media_type === 'video' && $ad->formattedVideoUrl())
                            <video src="{{ $ad->formattedVideoUrl() }}" style="width:100%;height:100%;object-fit:cover;"></video>
                            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;">
                                <i class="fa-solid fa-play" style="background:rgba(255,255,255,0.25);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(3px);"></i>
                            </div>
                            <span style="position:absolute;bottom:8px;right:8px;background:rgba(15,23,42,0.85);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;backdrop-filter:blur(2px);">
                                <i class="fa-solid fa-video" style="color:var(--blue-accent);"></i> فيديو ترويجي
                            </span>
                        @elseif($ad->firstMediaUrl())
                            <img src="{{ $ad->firstMediaUrl() }}" alt="{{ $ad->adv_title }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                            <div style="display:none;width:100%;height:100%;flex-direction:column;align-items:center;justify-content:center;color:#f0c842;padding:16px;text-align:center;">
                                <i class="fa-solid fa-bullhorn" style="font-size:36px;margin-bottom:8px;"></i>
                                <span style="font-size:12px;color:#e2e8f0;font-weight:700;">{{ $ad->adv_title }}</span>
                            </div>

                            @if($ad->media_type === 'gallery')
                                <span style="position:absolute;bottom:8px;right:8px;background:rgba(15,23,42,0.85);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;backdrop-filter:blur(2px);">
                                    <i class="fa-solid fa-images" style="color:var(--teal);"></i> معرض صور ({{ count($ad->mediaFilesArray()) }})
                                </span>
                            @else
                                <span style="position:absolute;bottom:8px;right:8px;background:rgba(15,23,42,0.85);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;backdrop-filter:blur(2px);">
                                    <i class="fa-solid fa-image"></i> صورة مفردة
                                </span>
                            @endif
                        @else
                            <div style="text-align:center;color:#94a3b8;padding:16px;">
                                <i class="fa-solid fa-rectangle-ad" style="font-size:36px;margin-bottom:6px;display:block;color:#f0c842;"></i>
                                <span style="font-size:12px;color:#cbd5e1;font-weight:700;">{{ $ad->review_status === 'مسودة' ? 'مسودة بدون وسائط' : 'إعلان ترويجي' }}</span>
                            </div>
                        @endif

                        <!-- شارة الباقة في أعلى يمين الصورة -->
                        <div style="position:absolute;top:10px;right:10px;z-index:2;">
                            @if($ad->package === 'شاملة')
                                <span class="badge" style="background:rgba(190,24,93,0.92);color:#fff;font-weight:800;padding:4px 10px;border-radius:20px;font-size:11.5px;box-shadow:0 2px 6px rgba(0,0,0,0.25);">
                                    <i class="fa-solid fa-crown" style="color:#fbcfe8;"></i> الشاملة
                                </span>
                            @elseif($ad->package === 'مميزة')
                                <span class="badge" style="background:rgba(161,98,7,0.92);color:#fff;font-weight:800;padding:4px 10px;border-radius:20px;font-size:11.5px;box-shadow:0 2px 6px rgba(0,0,0,0.25);">
                                    <i class="fa-solid fa-star" style="color:#fef08a;"></i> المميزة
                                </span>
                            @else
                                <span class="badge" style="background:rgba(21,128,61,0.92);color:#fff;font-weight:800;padding:4px 10px;border-radius:20px;font-size:11.5px;box-shadow:0 2px 6px rgba(0,0,0,0.25);">
                                    الاقتصادية
                                </span>
                            @endif
                        </div>

                        <!-- شارة حالة المراجعة والنشر في أعلى يسار الصورة -->
                        <div style="position:absolute;top:10px;left:10px;z-index:2;">
                            @if($ad->isActive())
                                <span class="badge" style="background:rgba(4,120,87,0.95);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:800;box-shadow:0 2px 6px rgba(0,0,0,0.25);display:inline-flex;align-items:center;gap:5px;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#34d399;display:inline-block;"></span>
                                    نشط ومنشور
                                </span>
                            @elseif($ad->isExpired())
                                <span class="badge" style="background:rgba(71,85,105,0.92);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                    منتهي الصلاحية
                                </span>
                            @elseif($ad->review_status === 'قيد المراجعة')
                                <span class="badge" style="background:rgba(180,83,9,0.92);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                    <i class="fa-solid fa-hourglass-half"></i> قيد المراجعة
                                </span>
                            @elseif($ad->review_status === 'مطلوب تعديل')
                                <span class="badge" style="background:rgba(194,65,12,0.95);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:800;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> مطلوب تعديل
                                </span>
                            @elseif($ad->review_status === 'مرفوض')
                                <span class="badge" style="background:rgba(185,28,28,0.95);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                    مرفوض
                                </span>
                            @else
                                <span class="badge" style="background:rgba(51,65,85,0.92);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                    مسودة ({{ $ad->wizard_step }}/4)
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 2. جسم البطاقة: البيانات والمعلومات الأساسية -->
                    <div class="ad-card-body" style="padding:18px 20px;flex:1;display:flex;flex-direction:column;">
                        <h4 style="font-size:15px;font-weight:800;color:var(--navy);margin:0 0 6px;line-height:1.4;">
                            {{ $ad->adv_title ?: 'إعلان بدون عنوان' }}
                        </h4>
                        
                        <p style="font-size:12.5px;color:var(--ink-muted);line-height:1.55;margin:0 0 12px;flex:1;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $ad->adv_content ?: 'لا يوجد محتوى نصي مضاف للإعلان حتى الآن' }}
                        </p>

                        <!-- مواصفات الباقة ومكان الظهور -->
                        <div style="background:var(--surface-subtle);border:1px solid var(--border-light);border-radius:8px;padding:8px 12px;margin-bottom:12px;font-size:11.5px;display:flex;flex-direction:column;gap:4px;">
                            <div style="display:flex;justify-content:space-between;color:var(--ink);">
                                <span style="color:var(--ink-muted);">مكان الظهور:</span>
                                <strong>{{ in_array('universities', $ad->getPackageConfig()['locations'] ?? []) ? 'الرئيسية + دليل الجامعات' : 'الصفحة الرئيسية' }}</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;color:var(--ink);">
                                <span style="color:var(--ink-muted);">المدة والتكلفة:</span>
                                <span><strong>{{ $ad->duration }} أيام</strong> ({{ number_format($ad->amount) }} ر.ي)</span>
                            </div>
                        </div>

                        <!-- تواريخ الإنشاء والنشر -->
                        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--ink-muted);margin-top:auto;">
                            <span>أنشئ: {{ $ad->created_at ? $ad->created_at->format('Y-m-d') : '—' }}</span>
                            @if($ad->published_at)
                                <span style="color:#15803d;">نُشر: {{ $ad->published_at->format('Y-m-d') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- 3. تذييل البطاقة: أزرار الإجراءات المتكاملة -->
                    <div class="ad-card-footer" style="padding:12px 18px;background:var(--surface-subtle);border-top:1px solid var(--border);display:flex;gap:8px;align-items:center;justify-content:space-between;">
                        <button type="button" class="btn btn-sm btn-ghost" onclick="viewAdDetails({{ $ad->adv_id }})" title="عرض التفاصيل الكاملة للإعلان" style="flex:1;padding:7px 12px;border-radius:var(--radius-sm);background:#fff;border:1px solid var(--border);color:var(--navy);font-weight:700;font-size:12.5px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                            <i class="fa-solid fa-eye"></i>
                            <span>عرض التفاصيل</span>
                        </button>

                        @if($ad->isExpired())
                            <button type="button" onclick="openNewAdModal('{{ $ad->package }}')" class="btn btn-sm" title="تجديد هذا الإعلان وإعادة نشره" style="padding:7px 14px;border-radius:var(--radius-sm);background:#d97706;color:#fff;border:none;cursor:pointer;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:5px;">
                                <i class="fa-solid fa-arrows-rotate"></i>
                                <span>تجديد</span>
                            </button>
                        @elseif($ad->review_status === 'مسودة')
                            <button type="button" onclick="resumeDraftInModal({{ $ad->adv_id }})" class="btn btn-sm btn-warning" title="متابعة إكمال المسودة" style="padding:7px 12px;border-radius:var(--radius-sm);background:#f59e0b;color:#fff;border:none;cursor:pointer;font-size:12px;font-weight:700;">
                                <i class="fa-solid fa-play"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="cancelDraftAd({{ $ad->adv_id }})" title="إلغاء وحذف المسودة" style="padding:7px 10px;border-radius:var(--radius-sm);background:#fee2e2;color:#b91c1c;border:none;cursor:pointer;font-size:12px;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        @elseif($ad->review_status === 'مطلوب تعديل')
                            <button type="button" onclick="resumeDraftInModal({{ $ad->adv_id }})" class="btn btn-sm" title="تعديل الإعلان وإعادة إرساله" style="padding:7px 14px;border-radius:var(--radius-sm);background:#ea580c;color:#fff;border:none;cursor:pointer;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:5px;">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>تصحيح</span>
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 50px 20px; text-align: center; color: var(--ink-muted);">
                    <div style="width:70px;height:70px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:var(--navy);font-size:28px;">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin-bottom:6px;">لا توجد أي إعلانات مسجلة حالياً</h4>
                    <p style="font-size:13px;max-width:440px;margin:0 auto 20px;line-height:1.6;">
                        قم بإطلاق أول حملة إعلانية لجامعتك للوصول للطلاب والظهور في واجهة المنصة ودليل الجامعات.
                    </p>
                    <button type="button" onclick="openNewAdModal()" style="display:inline-flex;align-items:center;gap:8px;padding:10px 24px;border-radius:var(--radius-sm);background:linear-gradient(135deg, var(--gold), #d99a22);color:#0a1b2a;font-weight:800;border:none;cursor:pointer;font-size:13.5px;">
                        <i class="fa-solid fa-plus-circle"></i>
                        <span>إطلاق أول إعلان الآن</span>
                    </button>
                </div>
            @endforelse
        </div>

    </div>

</div>

<!-- ========================================================
     نافذة عرض تفاصيل الإعلان (Ad Details Modal)
     ======================================================== -->
<div class="custom-modal-overlay" id="adDetailsModal" style="display:none;position:fixed;inset:0;background:rgba(10,27,42,0.68);z-index:9999;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;">
    <div class="custom-modal-dialog" style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:680px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow-lg);border:1px solid var(--border);">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg, #091e33, #153c5e);color:#fff;border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
            <h3 style="font-size:16px;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;color:#fff;">
                <i class="fa-solid fa-rectangle-ad" style="color:var(--gold);"></i>
                <span>تفاصيل الإعلان الترويجي</span>
            </h3>
            <button type="button" onclick="closeAdDetailsModal()" style="background:none;border:none;font-size:20px;color:#cbd5e1;cursor:pointer;padding:4px 8px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding:24px;" id="adDetailsModalBody">
            <div style="text-align:center;padding:40px;color:var(--ink-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size:30px;color:var(--teal);"></i>
                <p style="margin-top:12px;font-size:13px;">جارٍ تحميل تفاصيل الإعلان...</p>
            </div>
        </div>

        <div style="padding:14px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:10px;background:var(--surface-subtle);border-radius:0 0 var(--radius-lg) var(--radius-lg);">
            <button type="button" class="btn btn-ghost" onclick="closeAdDetailsModal()" style="padding:8px 22px;border:1px solid var(--border);border-radius:var(--radius-sm);font-weight:700;cursor:pointer;background:#fff;font-size:13px;">
                إغلاق
            </button>
        </div>
    </div>
</div>

<!-- ========================================================
     النافذة المنبثقة الشاملة: إطلاق إعلان جديد (New Ad Modal Wizard)
     ======================================================== -->
<div class="custom-modal-overlay" id="newAdModal" style="display:none;position:fixed;inset:0;background:rgba(8,23,38,0.78);z-index:99999;backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:15px;overflow-y:auto;">
    <div class="custom-modal-dialog" style="background:#ffffff;border-radius:20px;width:100%;max-width:1120px;max-height:94vh;display:flex;flex-direction:column;box-shadow:0 24px 60px rgba(0,0,0,0.35);border:1px solid rgba(228,183,91,0.3);overflow:hidden;margin:auto;">
        
        <!-- ترويسة النافذة المنبثقة باللون الترويزي/النيفي المتناسق مع المنصة -->
        <div style="background:linear-gradient(135deg, #091e33 0%, #0e3659 50%, #17547f 100%);padding:22px 28px 18px;color:#ffffff;position:relative;border-bottom:1px solid rgba(228,183,91,0.3);">
            
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:16px;">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:8px;font-size:12px;color:var(--gold);font-weight:700;margin-bottom:4px;">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>بوابة الحملات التسويقية المباشرة</span>
                    </div>
                    <h2 style="font-size:22px;font-weight:800;margin:0 0 4px;color:#ffffff;display:flex;align-items:center;gap:10px;">
                        <span>إطلاق إعلان جديد</span>
                        <span id="activeDraftBadge" style="display:none;font-size:11px;background:#e4b75b;color:#0a1b2a;padding:2px 10px;border-radius:20px;font-weight:800;">تعديل مسودة</span>
                    </h2>
                    <p style="font-size:12.5px;color:#cbd5e1;margin:0;line-height:1.5;">
                        معالج تفاعلي لاختيار المدة ومعاينة الإعلان وسداد رسوم عرض الإعلان
                    </p>
                </div>
                
                <button type="button" onclick="closeNewAdModal()" style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.25);width:36px;height:36px;border-radius:50%;color:#ffffff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;transition:all 0.2s;" title="إغلاق">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- البطائق المائية الشفافة الثلاث لمعالج الإعلان -->
            <div class="stepper-watermark-grid" style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px;">
                
                <!-- 1. الباقة الإعلانية -->
                <div class="stepper-watermark-card active" id="stepperTab-1" onclick="switchWizardTab(1)">
                    <div class="stepper-badge-num">1</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13.5px;font-weight:800;line-height:1.2;">الباقة الإعلانية</div>
                        <div style="font-size:11px;color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">مكان الظهور والتسعير</div>
                    </div>
                    <i class="fa-solid fa-crown" style="font-size:24px;opacity:0.18;"></i>
                </div>

                <!-- 2. تصميم الإعلان -->
                <div class="stepper-watermark-card" id="stepperTab-2" onclick="switchWizardTab(2)">
                    <div class="stepper-badge-num">2</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13.5px;font-weight:800;line-height:1.2;">تصميم الإعلان</div>
                        <div style="font-size:11px;color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">المحتوى والمعاينة الحية</div>
                    </div>
                    <i class="fa-solid fa-palette" style="font-size:24px;opacity:0.18;"></i>
                </div>

                <!-- 3. السداد والشروط -->
                <div class="stepper-watermark-card" id="stepperTab-3" onclick="switchWizardTab(3)">
                    <div class="stepper-badge-num">3</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13.5px;font-weight:800;line-height:1.2;">السداد والشروط</div>
                        <div style="font-size:11px;color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">بيانات السداد والموافقة</div>
                    </div>
                    <i class="fa-solid fa-shield-halved" style="font-size:24px;opacity:0.18;"></i>
                </div>

            </div>

        </div>

        <!-- جسم النافذة: الخطوات الثلاث التفاعلية -->
        <div style="padding:24px 28px;overflow-y:auto;flex:1;">
            
            <form id="newAdWizardForm" onsubmit="event.preventDefault();">
                <input type="hidden" id="wizard_ad_id" name="ad_id" value="">
                <input type="hidden" id="wizard_package" name="package" value="اقتصادية">

                <!-- ================== الخطوة 1: اختيار الباقة والتسعير ================== -->
                <div class="wizard-pane-step" id="wizardPane-1" style="display:block;">
                    
                    <div style="text-align:center;max-width:650px;margin:0 auto 24px;">
                        <h3 style="font-size:18px;font-weight:800;color:var(--navy);margin-bottom:6px;">
                            اختر الباقة الترويجية المناسبة لأهداف جامعتك
                        </h3>
                        <p style="font-size:12.5px;color:var(--ink-muted);margin:0;">
                            باقات مرنة وموحدة تضمن لجامعتك أعلى معدلات الظهور والتفاعل المباشر مع آلاف الطلبة
                        </p>
                    </div>

                    <!-- شبكة بطاقات الباقات بتصميم عالمي موحد -->
                    <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:20px;margin-bottom:24px;">
                        
                        <!-- 1. الباقة الاقتصادية -->
                        <div class="global-pricing-card selected" id="pkgCard-اقتصادية" onclick="selectWizardPackage('اقتصادية', 5000, 2)">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                                <div>
                                    <span style="font-size:11px;color:var(--teal);font-weight:700;background:var(--teal-subtle);padding:2px 8px;border-radius:20px;">ظهور قياسي</span>
                                    <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin:6px 0 0;">الباقة الاقتصادية</h4>
                                </div>
                                <div class="pricing-radio-indicator"></div>
                            </div>

                            <div style="margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
                                <div style="display:flex;align-items:baseline;gap:4px;">
                                    <span style="font-size:24px;font-weight:800;color:var(--navy);">5,000</span>
                                    <span style="font-size:12px;color:var(--ink-muted);font-weight:700;">ريال يمني</span>
                                </div>
                                <div style="font-size:11.5px;color:var(--ink-muted);margin-top:2px;">
                                    مدة العرض: <strong>2 يوم (48 ساعة)</strong>
                                </div>
                            </div>

                            <ul style="list-style:none;padding:0;margin:0 0 20px;font-size:12px;display:flex;flex-direction:column;gap:10px;color:#334155;">
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>الظهور في <strong>الصفحة الرئيسية</strong></span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>رفع <strong>صورة إعلانية واحدة</strong></span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>زر انتقال مباشر لصفحة الجامعة</span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;color:#94a3b8;">
                                    <i class="fa-solid fa-circle-xmark" style="color:#cbd5e1;"></i>
                                    <span>الظهور في دليل الجامعات</span>
                                </li>
                            </ul>

                            <button type="button" class="btn" style="width:100%;padding:10px;border-radius:10px;background:var(--navy);color:#fff;font-weight:800;font-size:13px;border:none;">
                                تم اختيار هذه الباقة
                            </button>
                        </div>

                        <!-- 2. الباقة المميزة ⭐ (الموصى بها) -->
                        <div class="global-pricing-card" id="pkgCard-مميزة" onclick="selectWizardPackage('مميزة', 9000, 4)">
                            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg, #d97706, #b45309);color:#fff;font-size:10.5px;font-weight:800;padding:3px 14px;border-radius:20px;box-shadow:0 3px 8px rgba(217,119,6,0.3);">
                                <i class="fa-solid fa-star text-warning"></i> الأكثر طلباً
                            </div>

                            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                                <div>
                                    <span style="font-size:11px;color:#b45309;font-weight:700;background:#fef3c7;padding:2px 8px;border-radius:20px;">تغطية مضاعفة</span>
                                    <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin:6px 0 0;">الباقة المميزة</h4>
                                </div>
                                <div class="pricing-radio-indicator"></div>
                            </div>

                            <div style="margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
                                <div style="display:flex;align-items:baseline;gap:4px;">
                                    <span style="font-size:24px;font-weight:800;color:#b45309;">9,000</span>
                                    <span style="font-size:12px;color:var(--ink-muted);font-weight:700;">ريال يمني</span>
                                </div>
                                <div style="font-size:11.5px;color:var(--ink-muted);margin-top:2px;">
                                    مدة العرض: <strong>4 أيام كاملة</strong>
                                </div>
                            </div>

                            <ul style="list-style:none;padding:0;margin:0 0 20px;font-size:12px;display:flex;flex-direction:column;gap:10px;color:#334155;">
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>الظهور في <strong>الرئيسية + دليل الجامعات</strong></span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>معرض من <strong>1 إلى 3 صور</strong> (سلايدر متحرك)</span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>شارة تمييز خاصة وأولوية ظهور</span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>زر انتقال مباشر لصفحة الجامعة</span>
                                </li>
                            </ul>

                            <button type="button" class="btn" style="width:100%;padding:10px;border-radius:10px;background:#f1f5f9;color:var(--navy);font-weight:800;font-size:13px;border:none;">
                                اختيار الباقة المميزة
                            </button>
                        </div>

                        <!-- 3. الباقة الشاملة -->
                        <div class="global-pricing-card" id="pkgCard-شاملة" onclick="selectWizardPackage('شاملة', 20000, 10)">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                                <div>
                                    <span style="font-size:11px;color:#7e22ce;font-weight:700;background:#f3e8ff;padding:2px 8px;border-radius:20px;">أقصى وصول</span>
                                    <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin:6px 0 0;">الباقة الشاملة</h4>
                                </div>
                                <div class="pricing-radio-indicator"></div>
                            </div>

                            <div style="margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
                                <div style="display:flex;align-items:baseline;gap:4px;">
                                    <span style="font-size:24px;font-weight:800;color:#7e22ce;">20,000</span>
                                    <span style="font-size:12px;color:var(--ink-muted);font-weight:700;">ريال يمني</span>
                                </div>
                                <div style="font-size:11.5px;color:var(--ink-muted);margin-top:2px;">
                                    مدة العرض: <strong>10 أيام كاملة</strong>
                                </div>
                            </div>

                            <ul style="list-style:none;padding:0;margin:0 0 20px;font-size:12px;display:flex;flex-direction:column;gap:10px;color:#334155;">
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>الظهور في <strong>الرئيسية + الدليل + أعلى القوائم</strong></span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span><strong>حتى 5 صور</strong> أو <strong>فيديو ترويجي 10 ثوانٍ</strong></span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>أعلى أولوية ظهور (Priority 3) طوال المدة</span>
                                </li>
                                <li style="display:flex;align-items:center;gap:8px;">
                                    <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                                    <span>أزرار نداء ودعم فني وتنسيقي مباشر</span>
                                </li>
                            </ul>

                            <button type="button" class="btn" style="width:100%;padding:10px;border-radius:10px;background:#f1f5f9;color:var(--navy);font-weight:800;font-size:13px;border:none;">
                                اختيار الباقة الشاملة
                            </button>
                        </div>

                    </div>

                    <!-- شريط التنقل السفلي للخطوة 1 -->
                    <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:16px;border-top:1px solid var(--border);">
                        <button type="button" onclick="saveStep1AndProceed()" id="btnStep1Next" style="padding:10px 28px;border-radius:10px;background:linear-gradient(135deg, var(--teal), #1f6f6b);color:#fff;font-weight:800;font-size:13.5px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(43,138,133,0.25);">
                            <span>التالي: تصميم الإعلان</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>

                </div>

                <!-- ================== الخطوة 2: تصميم الإعلان والمعاينة الحية ================== -->
                <div class="wizard-pane-step" id="wizardPane-2" style="display:none;">
                    
                    <div class="wizard-step2-grid" style="display:grid;grid-template-columns:1.2fr 1fr;gap:26px;align-items:start;">
                        
                        <!-- العمود الأيمن: حقول إدخال المحتوى واقتراحات العناوين والأوصاف -->
                        <div>
                            
                            <!-- 1. عنوان الإعلان مع أشكال بيضاوية لتعبئة العنوان تلقائياً -->
                            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;margin-bottom:18px;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                    <label for="wizard_adv_title" style="font-size:13px;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:6px;">
                                        <i class="fa-solid fa-heading" style="color:var(--teal);"></i>
                                        <span>عنوان الإعلان الرئيسي</span>
                                        <span style="color:#ef4444;">*</span>
                                    </label>
                                    <span id="titleCharCount" style="font-size:11px;color:var(--ink-muted);">0 / 75</span>
                                </div>

                                <!-- خيارات أشكال بيضاوية لتعبئة العنوان تلقائياً -->
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:11.5px;color:var(--ink-muted);margin-bottom:6px;font-weight:700;">
                                        اختر عنواناً نموذجياً جاهزاً للتعبئة المباشرة:
                                    </div>
                                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                        <span class="title-pill-chip" onclick="applyPresetTitle('فتح باب القبول والتسجيل')">
                                            <i class="fa-solid fa-graduation-cap"></i>
                                            <span>فتح باب القبول والتسجيل</span>
                                        </span>
                                        <span class="title-pill-chip" onclick="applyPresetTitle('منح وتخفيضات تصل الى 30%')">
                                            <i class="fa-solid fa-percent"></i>
                                            <span>منح وتخفيضات تصل الى 30%</span>
                                        </span>
                                        <span class="title-pill-chip" onclick="applyPresetTitle('دورات تدريبية وندوات علمية')">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                            <span>دورات تدريبية وندوات علمية</span>
                                        </span>
                                        <span class="title-pill-chip" onclick="applyPresetTitle('فعاليات طلابيه')">
                                            <i class="fa-solid fa-users"></i>
                                            <span>فعاليات طلابية</span>
                                        </span>
                                    </div>
                                </div>

                                <input type="text" id="wizard_adv_title" name="adv_title" maxlength="75" oninput="updateTitlePreview()" placeholder="مثال: فتح باب القبول والتسجيل للعام الجامعي الجديد" style="width:100%;padding:11px 14px;border:1.5px solid #cbd5e1;border-radius:10px;font-size:13px;color:var(--navy);font-weight:700;outline:none;box-sizing:border-box;transition:all 0.2s;" required>
                            </div>

                            <!-- 2. وصف ومحتوى الإعلان مع اقتراحات ذكية وبنك الأوصاف -->
                            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;margin-bottom:18px;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                    <label for="wizard_adv_content" style="font-size:13px;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:6px;">
                                        <i class="fa-solid fa-align-right" style="color:var(--teal);"></i>
                                        <span>وصف وتفاصيل الإعلان</span>
                                        <span style="color:#ef4444;">*</span>
                                    </label>
                                    <span id="contentCharCount" style="font-size:11px;color:var(--ink-muted);">0 / 200</span>
                                </div>

                                <!-- الحاوية الديناميكية للأوصاف المقترحة بناءً على العنوان المختار -->
                                <div id="contextualDescBox" style="display:none;margin-bottom:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 12px;">
                                    <div style="font-size:11.5px;font-weight:800;color:#166534;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                                        <span>أوصاف جاهزة ومتناسقة مع العنوان المختار (انقر لاختيار أحدها):</span>
                                    </div>
                                    <div id="contextualDescList" style="display:flex;flex-direction:column;gap:6px;"></div>
                                </div>

                                <!-- زر البحث عن وصف / بنك الأوصاف الذكية (في حال كتابة عنوان يدوي) -->
                                <div id="searchDescBtnWrap" style="margin-bottom:10px;">
                                    <button type="button" onclick="toggleDescLibrary()" style="display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1px solid #cbd5e1;color:var(--navy);font-size:12px;font-weight:700;padding:6px 12px;border-radius:8px;cursor:pointer;">
                                        <i class="fa-solid fa-magnifying-glass" style="color:var(--teal);"></i>
                                        <span>البحث عن وصف ملائم / اقتراحات الأوصاف</span>
                                    </button>
                                </div>

                                <!-- درج مكتبة الأوصاف القابلة للبحث -->
                                <div id="descLibraryDrawer" style="display:none;margin-bottom:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                        <span style="font-size:11.5px;font-weight:800;color:var(--navy);">اختر من مكتبة الأوصاف الأكاديمية:</span>
                                        <button type="button" onclick="toggleDescLibrary()" style="background:none;border:none;font-size:12px;color:var(--ink-muted);cursor:pointer;">إخفاء</button>
                                    </div>
                                    <div style="display:flex;flex-direction:column;gap:6px;max-height:160px;overflow-y:auto;padding-left:4px;">
                                        <div class="suggested-desc-card" onclick="applyCustomDesc(this.innerText)">
                                            تعلن الجامعة عن فتح باب القبول والتسجيل للعام الجامعي الجديد في كافة الكليات الطبية والهندسية والإدارية مع أحدث المعامل ونخبة الأكاديميين.
                                        </div>
                                        <div class="suggested-desc-card" onclick="applyCustomDesc(this.innerText)">
                                            يسر الجامعة تقديم منح دراسية وتخفيضات خاصة تصل إلى 30% للطلبة المتفوقين في الثانوية العامة وأوائل الأقسام، سارع بالتسجيل الآن.
                                        </div>
                                        <div class="suggested-desc-card" onclick="applyCustomDesc(this.innerText)">
                                            تدعوكم الجامعة للمشاركة في حزمة البرامج التدريبية التخصصية والندوات العلمية المعتمدة لتعزيز المهارات العملية وتأهيل الطلبة لسوق العمل.
                                        </div>
                                        <div class="suggested-desc-card" onclick="applyCustomDesc(this.innerText)">
                                            شاركونا فعاليات أسبوع الإبداع والأنشطة الطلابية الثقافية والرياضية في رحاب الحرم الجامعي، تجربة تفاعلية مليئة بالحماس والجوائز القيمة.
                                        </div>
                                    </div>
                                </div>

                                <textarea id="wizard_adv_content" name="adv_content" maxlength="200" rows="3" oninput="updateContentPreview()" placeholder="اكتب وصف الإعلان المختصر الجاذب للطلبة هنا..." style="width:100%;padding:11px 14px;border:1.5px solid #cbd5e1;border-radius:10px;font-size:13px;color:var(--ink);outline:none;box-sizing:border-box;resize:none;line-height:1.6;transition:all 0.2s;" required></textarea>
                            </div>

                            <!-- 3. رفع وسائط الإعلان (صور أو فيديو) -->
                            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                
                                <label style="font-size:13px;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                                    <i class="fa-solid fa-photo-film" style="color:var(--teal);"></i>
                                    <span>وسائط الإعلان (صورة أو فيديو بحسب باقتك)</span>
                                </label>
                                <p style="font-size:11.5px;color:var(--ink-muted);margin:0 0 12px;" id="mediaUploadHelp">
                                    للباقة الاقتصادية: صورة واحدة بدقة عالية. للباقة المميزة: حتى 3 صور. للباقة الشاملة: صور أو فيديو 10 ثوانٍ.
                                </p>

                                <!-- تبديل الوسائط للباقة الشاملة فقط -->
                                <div id="comprehensiveMediaToggle" style="display:none;margin-bottom:12px;gap:10px;">
                                    <label style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:700;cursor:pointer;">
                                        <input type="radio" name="media_type_radio" value="gallery" checked onchange="toggleComprehensiveMediaType('gallery')">
                                        <span>معرض صور (حتى 5 صور)</span>
                                    </label>
                                    <label style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:700;cursor:pointer;">
                                        <input type="radio" name="media_type_radio" value="video" onchange="toggleComprehensiveMediaType('video')">
                                        <span>فيديو ترويجي (حتى 10 ثوانٍ)</span>
                                    </label>
                                </div>

                                <!-- منطقة رفع الصور -->
                                <div id="imageUploadArea">
                                    <div style="border:2px dashed #cbd5e1;border-radius:12px;padding:20px;text-align:center;background:#f8fafc;cursor:pointer;" onclick="document.getElementById('wizardImageInput').click()">
                                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:28px;color:var(--teal);margin-bottom:6px;"></i>
                                        <div style="font-size:13px;font-weight:800;color:var(--navy);">انقر لرفع صورة الإعلان</div>
                                        <div style="font-size:11px;color:var(--ink-muted);">يدعم صيغ JPG، PNG، WEBP (بحد أقصى 3 ميجابايت)</div>
                                    </div>
                                    <input type="file" id="wizardImageInput" accept="image/*" multiple onchange="handleImageSelection(this)" style="display:none;">
                                    
                                    <!-- مصغرات الصور المحددة -->
                                    <div id="selectedImagesThumbnails" style="display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;"></div>
                                </div>

                                <!-- منطقة رفع الفيديو للباقة الشاملة -->
                                <div id="videoUploadArea" style="display:none;">
                                    <div style="border:2px dashed #cbd5e1;border-radius:12px;padding:20px;text-align:center;background:#f8fafc;cursor:pointer;" onclick="document.getElementById('wizardVideoInput').click()">
                                        <i class="fa-solid fa-film" style="font-size:28px;color:#7e22ce;margin-bottom:6px;"></i>
                                        <div style="font-size:13px;font-weight:800;color:var(--navy);">انقر لرفع مقطع الفيديو الترويجي</div>
                                        <div style="font-size:11px;color:var(--ink-muted);">صيغة MP4 أو WEBM (مدة قصيرة 10 ثوانٍ، بحد أقصى 15 ميجابايت)</div>
                                    </div>
                                    <input type="file" id="wizardVideoInput" accept="video/mp4,video/webm" onchange="handleVideoSelection(this)" style="display:none;">
                                    
                                    <div id="selectedVideoPreview" style="display:none;margin-top:12px;"></div>
                                </div>

                            </div>

                        </div>

                        <!-- العمود الأيسر: بطاقة المعاينة الحية المطابقة لشكل الإعلان أمام الطالب -->
                        <div>
                            <div class="student-preview-wrapper" style="position:sticky;top:10px;">
                                
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;border-bottom:1px solid rgba(255,255,255,0.12);padding-bottom:10px;">
                                    <span style="font-size:12px;font-weight:800;color:var(--gold);display:flex;align-items:center;gap:6px;">
                                        <i class="fa-solid fa-mobile-screen"></i>
                                        <span>معاينة حية (كما تظهر للطلاب في الموقع)</span>
                                    </span>
                                    <span class="badge" id="previewPackageTag" style="background:rgba(228,183,91,0.25);color:#f0c842;border:1px solid rgba(228,183,91,0.5);font-size:10.5px;padding:2px 8px;border-radius:12px;">
                                        الباقة الاقتصادية
                                    </span>
                                </div>

                                <!-- كرت الإعلان الحقيقي للطالب (نفس حاوية العرض المعدلة) -->
                                <div class="student-mock-card">
                                    
                                    <!-- منطقة الوسائط مع أسهم التنقل والصور -->
                                    <div class="student-mock-media" id="studentMockMediaContainer">
                                        <img id="mockPreviewImg" src="/images/logo.png" alt="معاينة الإعلان" onerror="this.onerror=null; this.src='/images/logo.png';" style="width:100%;height:100%;object-fit:cover;">
                                        <video id="mockPreviewVideo" controls style="display:none;width:100%;height:100%;object-fit:cover;"></video>
                                        
                                        <!-- شارة فيديو إعلاني للمعاينة -->
                                        <span id="mockVideoBadge" style="display:none;position:absolute;top:8px;right:8px;background:rgba(220,38,38,0.92);color:#ffffff;font-size:10px;font-weight:800;padding:2px 8px;border-radius:12px;z-index:10;align-items:center;gap:4px;">
                                            <i class="fa-solid fa-play"></i> فيديو إعلاني
                                        </span>

                                        <!-- أسهم التنقل بين الصور في حال المعرض -->
                                        <div id="mockGalleryArrows" style="display:none;">
                                            <button type="button" onclick="navigateMockGallery(-1)" style="position:absolute;top:50%;right:6px;transform:translateY(-50%);width:28px;height:28px;border-radius:50%;background:rgba(10,27,42,0.85);color:#f0c842;border:1px solid rgba(240,200,66,0.6);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;z-index:10;">
                                                <i class="fa-solid fa-chevron-right"></i>
                                            </button>
                                            <button type="button" onclick="navigateMockGallery(1)" style="position:absolute;top:50%;left:6px;transform:translateY(-50%);width:28px;height:28px;border-radius:50%;background:rgba(10,27,42,0.85);color:#f0c842;border:1px solid rgba(240,200,66,0.6);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;z-index:10;">
                                                <i class="fa-solid fa-chevron-left"></i>
                                            </button>
                                            <span id="mockGalleryCount" style="position:absolute;bottom:6px;right:6px;background:rgba(0,0,0,0.7);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;z-index:10;">1 / 1</span>
                                        </div>

                                        <!-- شارة إعلان ممول -->
                                        <span style="position:absolute;top:8px;left:8px;background:rgba(10,27,42,0.85);color:#f0c842;border:1px solid rgba(197,155,39,0.5);font-size:10px;font-weight:800;padding:2px 8px;border-radius:12px;z-index:10;">
                                            إعلان ممول
                                        </span>
                                    </div>

                                    <!-- منطقة النصوص والأزرار -->
                                    <div class="student-mock-info">
                                        
                                        <div style="display:flex;align-items:center;gap:6px;font-size:11.5px;color:#f0c842;font-weight:700;margin-bottom:6px;">
                                            <i class="fa-solid fa-building-columns"></i>
                                            <span>{{ $university->university_name }}</span>
                                        </div>

                                        <h4 id="mockPreviewTitle" style="font-size:14px;font-weight:800;color:#ffffff;margin:0 0 6px;line-height:1.4;">
                                            عنوان الإعلان يظهر هنا...
                                        </h4>

                                        <p id="mockPreviewContent" style="font-size:12px;color:#cbd5e1;line-height:1.55;margin:0 0 14px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                            نص ووصف الإعلان الذي ستقوم بكتابته سيظهر للطلاب في هذه المساحة بدقة ووضوح...
                                        </p>

                                        <!-- زر الانتقال إلى الجامعة الكبير المعتمد للطلبة -->
                                        <div style="display:flex;justify-content:flex-end;">
                                            <span class="btn-mock-uni">
                                                <span>الانتقال إلى الجامعة</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </span>
                                        </div>

                                    </div>

                                </div>

                                <div style="margin-top:12px;text-align:center;font-size:11px;color:#94a3b8;">
                                    <i class="fa-solid fa-circle-check text-success me-1"></i> يتم تحديث هذه المعاينة فوراً مع أي تغيير تدخله
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- شريط التنقل السفلي للخطوة 2 -->
                    <div style="display:flex;justify-content:space-between;gap:12px;padding-top:20px;border-top:1px solid var(--border);margin-top:20px;">
                        <button type="button" onclick="switchWizardTab(1)" style="padding:10px 22px;border-radius:10px;background:#f1f5f9;color:var(--navy);font-weight:800;font-size:13px;border:1px solid #cbd5e1;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <span>السابق: الباقة</span>
                        </button>
                        <button type="button" onclick="saveStep2AndProceed()" id="btnStep2Next" style="padding:10px 28px;border-radius:10px;background:linear-gradient(135deg, var(--teal), #1f6f6b);color:#fff;font-weight:800;font-size:13.5px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(43,138,133,0.25);">
                            <span>التالي: السداد والشروط</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>

                </div>

                <!-- ================== الخطوة 3: السداد وتأكيد الإرسال ================== -->
                <div class="wizard-pane-step" id="wizardPane-3" style="display:none;">
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:26px;align-items:start;">
                        
                        <!-- العمود الأيمن: تفاصيل الحسابات البنكية الرسمية للمنصة مع زر النسخ السريع -->
                        <div>
                            
                            <!-- ملخص الحملة المختارة -->
                            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:16px;margin-bottom:18px;">
                                <div style="font-size:12px;font-weight:800;color:var(--navy);margin-bottom:8px;">
                                    ملخص الحملة الإعلانية:
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #cbd5e1;">
                                    <span style="font-size:12.5px;color:var(--ink-muted);">الباقة المختارة:</span>
                                    <span id="summaryPkgName" style="font-size:13px;font-weight:800;color:var(--navy);">الباقة الاقتصادية</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #cbd5e1;">
                                    <span style="font-size:12.5px;color:var(--ink-muted);">مدة الحملة:</span>
                                    <span id="summaryDuration" style="font-size:13px;font-weight:800;color:var(--navy);">2 يوم</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;margin-top:4px;">
                                    <span style="font-size:13px;font-weight:800;color:var(--navy);">إجمالي الرسوم المطلوبة:</span>
                                    <span id="summaryAmount" style="font-size:18px;font-weight:800;color:var(--teal);">5,000 ر.ي</span>
                                </div>
                            </div>

                            <!-- الحسابات المعتمدة للسداد -->
                            <div style="font-size:13px;font-weight:800;color:var(--navy);margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                                <i class="fa-solid fa-building-columns" style="color:var(--teal);"></i>
                                <span>الحسابات البنكية المعتمدة للمنصة:</span>
                            </div>

                            <!-- حساب الكريمي -->
                            <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;margin-bottom:10px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <strong style="font-size:12.5px;color:#1e40af;">بنك الكريمي الإسلامي</strong>
                                    <button type="button" onclick="copyAccountText('120456789', this)" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;">
                                        <i class="fa-solid fa-copy"></i> <span>نسخ الحساب</span>
                                    </button>
                                </div>
                                <div style="font-size:14px;font-weight:800;color:var(--navy);letter-spacing:1px;margin:4px 0;">120456789</div>
                                <div style="font-size:11px;color:var(--ink-muted);">باسم: منصة إديو يمن - EduYemen للخدمات التعليمية</div>
                            </div>

                            <!-- حساب محفظة ون كاش -->
                            <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;margin-bottom:10px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <strong style="font-size:12.5px;color:#047857;">محفظة ون كاش (OneCash)</strong>
                                    <button type="button" onclick="copyAccountText('777000123', this)" style="background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;">
                                        <i class="fa-solid fa-copy"></i> <span>نسخ الحساب</span>
                                    </button>
                                </div>
                                <div style="font-size:14px;font-weight:800;color:var(--navy);letter-spacing:1px;margin:4px 0;">777000123</div>
                                <div style="font-size:11px;color:var(--ink-muted);">باسم: EduYemen - إديو يمن</div>
                            </div>

                            <!-- حساب بنك التضامن -->
                            <div style="background:#ffffff;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <strong style="font-size:12.5px;color:#b45309;">بنك التضامن الدولي</strong>
                                    <button type="button" onclick="copyAccountText('YE2900040000000123456789', this)" style="background:#fef3c7;border:1px solid #fde68a;color:#b45309;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;">
                                        <i class="fa-solid fa-copy"></i> <span>نسخ IBAN</span>
                                    </button>
                                </div>
                                <div style="font-size:12px;font-weight:800;color:var(--navy);letter-spacing:0.5px;margin:4px 0;word-break:break-all;">YE2900040000000123456789</div>
                                <div style="font-size:11px;color:var(--ink-muted);">باسم: شركة إديو يمن لتقنية المعلومات والتعليم</div>
                            </div>

                        </div>

                        <!-- العمود الأيسر: تعبئة بيانات السداد وإرفاق السند والموافقة -->
                        <div>
                            
                            <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                
                                <h4 style="font-size:15px;font-weight:800;color:var(--navy);margin:0 0 14px;display:flex;align-items:center;gap:6px;">
                                    <i class="fa-solid fa-receipt" style="color:var(--teal);"></i>
                                    <span>بيانات السداد وسند التحويل</span>
                                </h4>

                                <!-- وسيلة الدفع -->
                                <div style="margin-bottom:14px;">
                                    <label for="wizard_pay_method" style="display:block;font-size:12.5px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                        وسيلة الدفع المستخدمة: <span style="color:#ef4444;">*</span>
                                    </label>
                                    <select id="wizard_pay_method" name="pay_method" style="width:100%;padding:10px 14px;border:1.5px solid #cbd5e1;border-radius:10px;font-size:13px;color:var(--navy);font-weight:700;outline:none;" required>
                                        <option value="بنك الكريمي">بنك الكريمي للتمويل الأصغر</option>
                                        <option value="محفظة ون كاش">محفظة ون كاش (OneCash)</option>
                                        <option value="بنك التضامن">بنك التضامن الإسلامي الدولي</option>
                                        <option value="تحويل عبر الصرافة">حوالة عبر شبكات الصرافة (النجم/الامتياز)</option>
                                    </select>
                                </div>

                                <!-- رقم الإشعار/المرجع -->
                                <div style="margin-bottom:14px;">
                                    <label for="wizard_pay_reference" style="display:block;font-size:12.5px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                        رقم الحوالة أو إشعار التحويل البنكي: <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input type="text" id="wizard_pay_reference" name="pay_reference" placeholder="مثال: 789456123 أو TR-2026-X" style="width:100%;padding:10px 14px;border:1.5px solid #cbd5e1;border-radius:10px;font-size:13px;color:var(--navy);font-weight:700;outline:none;box-sizing:border-box;" required>
                                </div>

                                <!-- إرفاق سند السداد -->
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:12.5px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                        صورة إثبات الدفع (سند التحويل): <span style="color:#ef4444;">*</span>
                                    </label>
                                    
                                    <div style="border:2px dashed #cbd5e1;border-radius:10px;padding:16px;text-align:center;background:#f8fafc;cursor:pointer;" onclick="document.getElementById('wizardReceiptInput').click()">
                                        <i class="fa-solid fa-file-invoice" style="font-size:24px;color:var(--teal);margin-bottom:4px;"></i>
                                        <div style="font-size:12.5px;font-weight:700;color:var(--navy);">انقر لرفع صورة السند</div>
                                        <div style="font-size:11px;color:var(--ink-muted);">JPG، PNG، WEBP أو PDF (بحد أقصى 5 ميجابايت)</div>
                                    </div>
                                    <input type="file" id="wizardReceiptInput" accept="image/*,.pdf" onchange="handleReceiptSelection(this)" style="display:none;" required>
                                    
                                    <div id="receiptPreviewBox" style="display:none;margin-top:10px;padding:8px 12px;background:#f1f5f9;border-radius:8px;font-size:12px;color:var(--navy);align-items:center;justify-content:space-between;">
                                        <span id="receiptFileName" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                    </div>
                                </div>

                                <!-- إقرار الشروط والموافقة -->
                                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px;margin-bottom:18px;">
                                    <label style="display:flex;align-items:flex-start;gap:8px;font-size:11.5px;color:#1e3a8a;line-height:1.6;cursor:pointer;margin:0;">
                                        <input type="checkbox" id="wizard_terms_agree" required style="margin-top:3px;">
                                        <span>أقر بصحة البيانات المرفوعة ومطابقة الإعلان للضوابط والمعايير الأكاديمية والتربوية المعمول بها في منصة إديو يمن والجمهورية اليمنية.</span>
                                    </label>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- شريط الإرسال النهائي للخطوة 3 -->
                    <div style="display:flex;justify-content:space-between;gap:12px;padding-top:20px;border-top:1px solid var(--border);margin-top:20px;">
                        <button type="button" onclick="switchWizardTab(2)" style="padding:10px 22px;border-radius:10px;background:#f1f5f9;color:var(--navy);font-weight:800;font-size:13px;border:1px solid #cbd5e1;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <span>السابق: تعديل التصميم</span>
                        </button>
                        
                        <button type="button" onclick="submitFinalWizardAd()" id="btnWizardSubmit" style="padding:11px 32px;border-radius:10px;background:linear-gradient(135deg, #10b981 0%, #059669 100%);color:#fff;font-weight:800;font-size:14px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 16px rgba(16,185,129,0.35);transition:all 0.2s;">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>تأكيد وإرسال الإعلان للمراجعة</span>
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    /* ========================================================
       بيانات ومعالجات المعالج التفاعلي لإطلاق الإعلانات
       ======================================================== */
    let wizardCurrentStep = 1;
    let wizardSelectedPackage = 'اقتصادية';
    let wizardPackageAmount = 5000;
    let wizardPackageDuration = 2;
    let wizardAdId = null;
    let wizardUploadedImages = [];
    let wizardMockGalleryIndex = 0;

    // بنك الأوصاف الذكية المرتبطة بالعناوين النموذجية
    const presetDescriptions = {
        'فتح باب القبول والتسجيل': [
            "تعلن الجامعة عن فتح باب القبول والتسجيل للعام الجامعي الجديد في كافة الكليات والتخصصات المعتمدة، مع إمكانية التنسيق الإلكتروني المباشر عبر المنصة.",
            "سارع بالتسجيل وحجز مقعدك الدراسي في أحدث البرامج الأكاديمية والطبية والهندسية، كادر تدريسي متميز ومعامل مجهزة بأعلى المعايير العالمية."
        ],
        'منح وتخفيضات تصل الى 30%': [
            "يسر الجامعة الإعلان عن تقديم منح دراسية وتخفيضات استثنائية تصل إلى 30% في الرسوم الدراسية للطلبة المتفوقين والمسجلين الأوائل.",
            "فرصتك الذهبية للالتحاق بالتعليم الجامعي المتميز مع خصومات حصرية وتسهيلات مرنة في سداد الرسوم لكافة الأقسام والتخصصات."
        ],
        'دورات تدريبية وندوات علمية': [
            "تدعوكم الجامعة لحضور سلسلة من الدورات التدريبية المتقدمة وورش العمل التخصصية بشهادات معتمدة لتأهيل الطلاب لسوق العمل الحديث.",
            "انضم إلينا في المؤتمر العلمي وحلقات النقاش التفاعلية بمشاركة نخبة من الأكاديميين والخبراء والمتخصصين لاكتساب أحدث المهارات."
        ],
        'فعاليات طلابيه': [
            "شاركونا فعاليات أسبوع الطالب الجامعي والأنشطة الثقافية والرياضية والابتكارية في رحاب الحرم الجامعي وسط أجواء حماسية وتنافسية.",
            "معرض المشاريع والابتكارات الطلابية السنوي لعرض أفكار الشباب الريادية واختراعاتهم الواعدة بمشاركة مختلف الكليات والمراكز البحثية."
        ]
    };

    function openNewAdModal(initialPackage = 'اقتصادية') {
        wizardAdId = null;
        document.getElementById('wizard_ad_id').value = '';
        document.getElementById('activeDraftBadge').style.display = 'none';
        document.getElementById('newAdWizardForm').reset();
        
        selectWizardPackage(initialPackage, initialPackage === 'شاملة' ? 20000 : (initialPackage === 'مميزة' ? 9000 : 5000), initialPackage === 'شاملة' ? 10 : (initialPackage === 'مميزة' ? 4 : 2));
        switchWizardTab(1);
        
        document.getElementById('newAdModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeNewAdModal() {
        document.getElementById('newAdModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // التنقل بين بطاقات المعالج الثلاث
    function switchWizardTab(step) {
        wizardCurrentStep = step;

        for (let i = 1; i <= 3; i++) {
            const pane = document.getElementById(`wizardPane-${i}`);
            const tab = document.getElementById(`stepperTab-${i}`);
            if (pane) pane.style.display = (i === step) ? 'block' : 'none';
            if (tab) {
                if (i === step) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            }
        }
    }

    // اختيار باقة إعلانية وتحديث البطاقات الموحدة
    function selectWizardPackage(pkg, amount, duration) {
        wizardSelectedPackage = pkg;
        wizardPackageAmount = amount;
        wizardPackageDuration = duration;

        document.getElementById('wizard_package').value = pkg;
        
        ['اقتصادية', 'مميزة', 'شاملة'].forEach(p => {
            const card = document.getElementById(`pkgCard-${p}`);
            if (card) {
                if (p === pkg) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            }
        });

        // تحديث ملخص السداد
        const pkgNames = { 'اقتصادية': 'الباقة الاقتصادية', 'مميزة': 'الباقة المميزة ⭐', 'شاملة': 'الباقة الشاملة' };
        document.getElementById('summaryPkgName').textContent = pkgNames[pkg] || pkg;
        document.getElementById('summaryDuration').textContent = `${duration} أيام`;
        document.getElementById('summaryAmount').textContent = `${amount.toLocaleString()} ر.ي`;
        document.getElementById('previewPackageTag').textContent = pkgNames[pkg] || pkg;

        // ضبط واجهة الوسائط بحسب الباقة
        const compToggle = document.getElementById('comprehensiveMediaToggle');
        const mediaHelp = document.getElementById('mediaUploadHelp');
        if (pkg === 'شاملة') {
            if (compToggle) compToggle.style.display = 'flex';
            if (mediaHelp) mediaHelp.textContent = 'الباقة الشاملة تتيح لك رفع حتى 5 صور أو فيديو ترويجي قصير (10 ثوانٍ).';
        } else if (pkg === 'مميزة') {
            if (compToggle) compToggle.style.display = 'none';
            toggleComprehensiveMediaType('gallery');
            if (mediaHelp) mediaHelp.textContent = 'الباقة المميزة تتيح لك رفع من 1 إلى 3 صور لإنشاء سلايدر معرض صور متحرك.';
        } else {
            if (compToggle) compToggle.style.display = 'none';
            toggleComprehensiveMediaType('gallery');
            if (mediaHelp) mediaHelp.textContent = 'الباقة الاقتصادية تتيح رفع صورة ترويجية واحدة بدقة واضحة.';
        }
    }

    // حفظ الخطوة 1 والتنقل للخطوة 2 عبر AJAX
    async function saveStep1AndProceed() {
        const btn = document.getElementById('btnStep1Next');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> جارٍ الحفظ...`;

        try {
            const formData = new FormData();
            formData.append('package', wizardSelectedPackage);
            if (wizardAdId) formData.append('ad_id', wizardAdId);

            const res = await fetch(`{{ route('university.advertisements.wizard.step2') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();
            if (data.success && data.ad_id) {
                wizardAdId = data.ad_id;
                document.getElementById('wizard_ad_id').value = wizardAdId;
                switchWizardTab(2);
            } else {
                alert(data.message || 'حدث خطأ أثناء حفظ الباقة، يرجى المحاولة ثانية.');
            }
        } catch(e) {
            switchWizardTab(2); // متابعة في بيئة العرض
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // تطبيق أحد العناوين النموذجية من الأشكال البيضاوية وعرض أوصاف متناسقة معها
    function applyPresetTitle(titleText) {
        const titleInput = document.getElementById('wizard_adv_title');
        titleInput.value = titleText;
        updateTitlePreview();

        // تحديث حالة الأشكال البيضاوية النشطة
        document.querySelectorAll('.title-pill-chip').forEach(chip => {
            if (chip.innerText.includes(titleText)) {
                chip.classList.add('active');
            } else {
                chip.classList.remove('active');
            }
        });

        // عرض الأوصاف المتناسقة مع هذا العنوان
        const descBox = document.getElementById('contextualDescBox');
        const descList = document.getElementById('contextualDescList');
        const searchBtn = document.getElementById('searchDescBtnWrap');

        if (presetDescriptions[titleText] && presetDescriptions[titleText].length > 0) {
            descList.innerHTML = presetDescriptions[titleText].map(desc => `
                <div class="suggested-desc-card" onclick="applyCustomDesc(this.innerText)">
                    <i class="fa-solid fa-check text-success me-1"></i> ${desc}
                </div>
            `).join('');
            descBox.style.display = 'block';
            if (searchBtn) searchBtn.style.display = 'none';
        } else {
            descBox.style.display = 'none';
            if (searchBtn) searchBtn.style.display = 'block';
        }
    }

    function applyCustomDesc(descText) {
        const contentInput = document.getElementById('wizard_adv_content');
        contentInput.value = descText.replace(/^✓\s*/, '').trim();
        updateContentPreview();
    }

    function toggleDescLibrary() {
        const drawer = document.getElementById('descLibraryDrawer');
        if (drawer) {
            drawer.style.display = (drawer.style.display === 'none' || !drawer.style.display) ? 'block' : 'none';
        }
    }

    function updateTitlePreview() {
        const input = document.getElementById('wizard_adv_title');
        const count = document.getElementById('titleCharCount');
        const mock = document.getElementById('mockPreviewTitle');
        
        if (count) count.textContent = `${input.value.length} / 75`;
        if (mock) mock.textContent = input.value.trim() || 'عنوان الإعلان يظهر هنا...';
    }

    function updateContentPreview() {
        const input = document.getElementById('wizard_adv_content');
        const count = document.getElementById('contentCharCount');
        const mock = document.getElementById('mockPreviewContent');
        
        if (count) count.textContent = `${input.value.length} / 200`;
        if (mock) mock.textContent = input.value.trim() || 'نص ووصف الإعلان الذي ستقوم بكتابته سيظهر للطلاب في هذه المساحة...';
    }

    function toggleComprehensiveMediaType(type) {
        const imgArea = document.getElementById('imageUploadArea');
        const vidArea = document.getElementById('videoUploadArea');
        if (type === 'video') {
            if (imgArea) imgArea.style.display = 'none';
            if (vidArea) vidArea.style.display = 'block';
        } else {
            if (imgArea) imgArea.style.display = 'block';
            if (vidArea) vidArea.style.display = 'none';
        }
    }

    function handleImageSelection(input) {
        if (!input.files || input.files.length === 0) return;
        
        wizardUploadedImages = Array.from(input.files);
        const thumbsContainer = document.getElementById('selectedImagesThumbnails');
        thumbsContainer.innerHTML = '';

        wizardUploadedImages.forEach((file, idx) => {
            const url = URL.createObjectURL(file);
            const thumb = document.createElement('div');
            thumb.style.cssText = 'position:relative;width:65px;height:65px;border-radius:8px;overflow:hidden;border:1.5px solid var(--teal);';
            thumb.innerHTML = `<img src="${url}" style="width:100%;height:100%;object-fit:cover;">`;
            thumbsContainer.appendChild(thumb);
            
            // أول صورة توضع في المعاينة
            if (idx === 0) {
                const mockImg = document.getElementById('mockPreviewImg');
                const mockVid = document.getElementById('mockPreviewVideo');
                const mockVidBadge = document.getElementById('mockVideoBadge');
                if (mockImg) {
                    mockImg.src = url;
                    mockImg.style.display = 'block';
                }
                if (mockVid) mockVid.style.display = 'none';
                if (mockVidBadge) mockVidBadge.style.display = 'none';
            }
        });

        // إذا كانت صور متعددة، نظهر أسهم التنقل في المعاينة
        const arrows = document.getElementById('mockGalleryArrows');
        const count = document.getElementById('mockGalleryCount');
        if (wizardUploadedImages.length > 1) {
            wizardMockGalleryIndex = 0;
            if (arrows) arrows.style.display = 'block';
            if (count) count.textContent = `1 / ${wizardUploadedImages.length}`;
        } else {
            if (arrows) arrows.style.display = 'none';
        }
    }

    function navigateMockGallery(direction) {
        if (!wizardUploadedImages || wizardUploadedImages.length <= 1) return;
        
        wizardMockGalleryIndex += direction;
        if (wizardMockGalleryIndex < 0) wizardMockGalleryIndex = wizardUploadedImages.length - 1;
        if (wizardMockGalleryIndex >= wizardUploadedImages.length) wizardMockGalleryIndex = 0;

        const file = wizardUploadedImages[wizardMockGalleryIndex];
        const url = URL.createObjectURL(file);
        const mockImg = document.getElementById('mockPreviewImg');
        const count = document.getElementById('mockGalleryCount');
        
        if (mockImg) mockImg.src = url;
        if (count) count.textContent = `${wizardMockGalleryIndex + 1} / ${wizardUploadedImages.length}`;
    }

    function handleVideoSelection(input) {
        if (!input.files || input.files.length === 0) return;
        const file = input.files[0];
        const url = URL.createObjectURL(file);

        const previewBox = document.getElementById('selectedVideoPreview');
        previewBox.style.display = 'block';
        previewBox.innerHTML = `
            <div style="font-size:12px;font-weight:700;color:var(--navy);margin-bottom:4px;">معاينة الفيديو المختار:</div>
            <video src="${url}" controls style="width:100%;max-height:160px;border-radius:8px;background:#000;"></video>
        `;

        const mockImg = document.getElementById('mockPreviewImg');
        const mockVid = document.getElementById('mockPreviewVideo');
        const mockVidBadge = document.getElementById('mockVideoBadge');
        const arrows = document.getElementById('mockGalleryArrows');
        if (arrows) arrows.style.display = 'none';
        if (mockImg) mockImg.style.display = 'none';
        if (mockVid) {
            mockVid.src = url;
            mockVid.style.display = 'block';
        }
        if (mockVidBadge) mockVidBadge.style.display = 'inline-flex';
    }

    function handleReceiptSelection(input) {
        if (!input.files || input.files.length === 0) return;
        const file = input.files[0];
        const previewBox = document.getElementById('receiptPreviewBox');
        const nameBox = document.getElementById('receiptFileName');
        
        if (previewBox && nameBox) {
            nameBox.textContent = `ملف السند: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            previewBox.style.display = 'flex';
        }
    }

    // حفظ الخطوة 2 والانتقال للخطوة 3
    async function saveStep2AndProceed() {
        const title = document.getElementById('wizard_adv_title').value.trim();
        const content = document.getElementById('wizard_adv_content').value.trim();

        if (title.length < 5) {
            alert('يرجى إدخال عنوان للإعلان لا يقل عن 5 أحرف.');
            document.getElementById('wizard_adv_title').focus();
            return;
        }
        if (content.length < 10) {
            alert('يرجى إدخال وصف للإعلان لا يقل عن 10 أحرف.');
            document.getElementById('wizard_adv_content').focus();
            return;
        }

        const btn = document.getElementById('btnStep2Next');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> جارٍ الحفظ...`;

        try {
            const formData = new FormData();
            formData.append('ad_id', wizardAdId);
            formData.append('adv_title', title);
            formData.append('adv_content', content);

            // نوع الوسائط
            let mediaType = 'image';
            if (wizardSelectedPackage === 'مميزة') {
                mediaType = (wizardUploadedImages.length > 1) ? 'gallery' : 'image';
            } else if (wizardSelectedPackage === 'شاملة') {
                const vidRadio = document.querySelector('input[name="media_type_radio"]:checked');
                mediaType = (vidRadio && vidRadio.value === 'video') ? 'video' : 'gallery';
            }
            formData.append('media_type', mediaType);

            // رفع الملفات
            if (mediaType === 'video') {
                const vidInput = document.getElementById('wizardVideoInput');
                if (vidInput.files && vidInput.files[0]) {
                    formData.append('video', vidInput.files[0]);
                }
            } else if (wizardSelectedPackage === 'اقتصادية') {
                if (wizardUploadedImages.length > 0) {
                    formData.append('image', wizardUploadedImages[0]);
                }
            } else {
                wizardUploadedImages.forEach(file => {
                    formData.append('images[]', file);
                });
            }

            const res = await fetch(`{{ route('university.advertisements.wizard.step4') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                switchWizardTab(3);
            } else {
                alert(data.message || 'يرجى التأكد من رفع الوسائط المطلوبة وإكمال الحقول.');
            }
        } catch(e) {
            switchWizardTab(3);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // إرسال الإعلان النهائي للمراجعة (الخطوة 3)
    async function submitFinalWizardAd() {
        const payMethod = document.getElementById('wizard_pay_method').value;
        const payRef = document.getElementById('wizard_pay_reference').value.trim();
        const receiptInput = document.getElementById('wizardReceiptInput');
        const termsAgree = document.getElementById('wizard_terms_agree').checked;

        if (!payRef) {
            alert('يرجى إدخال رقم الحوالة أو إشعار التحويل البنكي.');
            document.getElementById('wizard_pay_reference').focus();
            return;
        }
        if (!termsAgree) {
            alert('يرجى الموافقة على شروط الإعلان والتأكيد على صحة البيانات.');
            return;
        }

        const btn = document.getElementById('btnWizardSubmit');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> جارٍ التحقق وإرسال الإعلان...`;

        try {
            // أولاً: حفظ السند وبيانات الدفع (Step 3)
            const payFormData = new FormData();
            payFormData.append('ad_id', wizardAdId);
            payFormData.append('pay_method', payMethod);
            payFormData.append('pay_reference', payRef);
            if (receiptInput.files && receiptInput.files[0]) {
                payFormData.append('receipt', receiptInput.files[0]);
            }

            const payRes = await fetch(`{{ route('university.advertisements.wizard.step3') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: payFormData
            });
            const payData = await payRes.json();
            if (!payData.success) {
                alert(payData.message || 'يرجى التأكد من إرفاق صورة سند الدفع بصيغة صحيحة.');
                btn.disabled = false;
                btn.innerHTML = originalText;
                return;
            }

            // ثانياً: الإرسال والاعتماد النهائي (Submit)
            const subFormData = new FormData();
            subFormData.append('ad_id', wizardAdId);

            const subRes = await fetch(`{{ route('university.advertisements.wizard.submit') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: subFormData
            });
            const subData = await subRes.json();

            if (subData.success) {
                alert(subData.message || 'تم إرسال الإعلان للمراجعة والاعتماد بنجاح!');
                location.reload();
            } else {
                alert(subData.message || 'تعذر إرسال الإعلان، يرجى مراجعة الحقول.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }

        } catch(e) {
            alert('حدث خطأ في الاتصال بالخادم، يرجى المحاولة ثانية.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // استكمال مسودة إعلان في النافذة المنبثقة
    async function resumeDraftInModal(adId) {
        openNewAdModal();
        wizardAdId = adId;
        document.getElementById('wizard_ad_id').value = adId;
        document.getElementById('activeDraftBadge').style.display = 'inline-block';

        try {
            const res = await fetch(`/university/advertisements/${adId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (data.success && data.ad) {
                const ad = data.ad;
                selectWizardPackage(ad.package, ad.amount, ad.duration);
                if (ad.title) document.getElementById('wizard_adv_title').value = ad.title;
                if (ad.content) document.getElementById('wizard_adv_content').value = ad.content;
                updateTitlePreview();
                updateContentPreview();

                if (ad.media_files && ad.media_files.length > 0) {
                    const mockImg = document.getElementById('mockPreviewImg');
                    if (mockImg) mockImg.src = ad.media_files[0];
                }

                // فتح الخطوة المناسبة للمسودة
                if (!ad.title || !ad.content) {
                    switchWizardTab(2);
                } else if (!ad.receipt_url) {
                    switchWizardTab(3);
                } else {
                    switchWizardTab(2);
                }
            }
        } catch(e) {}
    }

    // نسخ رقم الحساب
    function copyAccountText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-check"></i> <span>تم النسخ</span>`;
            btn.style.background = '#dcfce7';
            btn.style.color = '#15803d';
            setTimeout(() => {
                btn.innerHTML = original;
                btn.style.background = '';
                btn.style.color = '';
            }, 2000);
        });
    }

    /* ========================================================
       نافذة عرض التفاصيل القديمة (Ad Details Modal)
       ======================================================== */
    async function viewAdDetails(adId) {
        const modal = document.getElementById('adDetailsModal');
        const body = document.getElementById('adDetailsModalBody');
        modal.style.display = 'flex';
        body.innerHTML = `
            <div style="text-align:center;padding:40px;color:var(--ink-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size:30px;color:var(--teal);"></i>
                <p style="margin-top:12px;font-size:13px;">جارٍ تحميل تفاصيل الإعلان...</p>
            </div>
        `;

        try {
            const res = await fetch(`/university/advertisements/${adId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();

            if (!data.success || !data.ad) {
                body.innerHTML = `<p style="color:#ef4444;text-align:center;padding:20px;">تعذر تحميل تفاصيل الإعلان.</p>`;
                return;
            }

            const ad = data.ad;

            let mediaHtml = '';
            if (ad.media_type === 'video' && ad.video_url) {
                mediaHtml = `
                    <div style="margin-top:14px;">
                        <label style="display:block;font-size:12px;font-weight:700;color:var(--ink-muted);margin-bottom:6px;">الفيديو الترويجي المرفق:</label>
                        <video src="${ad.video_url}" controls style="width:100%;max-height:240px;border-radius:8px;background:#000;"></video>
                    </div>
                `;
            } else if (ad.media_files && ad.media_files.length > 0) {
                mediaHtml = `
                    <div style="margin-top:14px;">
                        <label style="display:block;font-size:12px;font-weight:700;color:var(--ink-muted);margin-bottom:6px;">الصور المرفقة (${ad.media_files.length}):</label>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(100px, 1fr));gap:10px;">
                            ${ad.media_files.map(url => `
                                <a href="${url}" target="_blank">
                                    <img src="${url}" alt="صورة الإعلان" style="width:100%;height:100px;object-fit:cover;border-radius:8px;border:1px solid var(--border);" onerror="this.onerror=null; this.src='/images/logo.png';" />
                                </a>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                mediaHtml = `
                    <div style="margin-top:14px;padding:16px;background:var(--surface-subtle);border:1px dashed var(--border);border-radius:8px;text-align:center;color:var(--ink-muted);font-size:12px;">
                        <i class="fa-solid fa-image" style="font-size:24px;margin-bottom:4px;color:#cbd5e1;display:block;"></i>
                        <span>لا توجد وسائط مرفقة بهذا الإعلان حتى الآن (مسودة)</span>
                    </div>
                `;
            }

            let receiptHtml = '';
            if (ad.receipt_url) {
                receiptHtml = `
                    <div style="margin-top:14px;padding:12px;background:#f8fafc;border-radius:8px;border:1px dashed var(--border);">
                        <label style="display:block;font-size:12px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                            <i class="fa-solid fa-receipt" style="color:var(--teal);"></i> سند إثبات الدفع المرفوع:
                        </label>
                        <a href="${ad.receipt_url}" target="_blank" style="display:inline-block;">
                            <img src="${ad.receipt_url}" alt="سند الدفع" style="max-height:140px;max-width:100%;border-radius:6px;border:1px solid var(--border);" onerror="this.onerror=null; this.src='/images/logo.png';" />
                        </a>
                        <div style="font-size:11px;color:var(--ink-muted);margin-top:4px;">انقر على الصورة لفتح السند بالحجم الكامل</div>
                    </div>
                `;
            }

            let notesHtml = '';
            if (ad.notes) {
                notesHtml = `
                    <div style="margin-top:16px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;">
                        <strong style="color:#b45309;font-size:12.5px;display:block;margin-bottom:4px;">
                            <i class="fa-solid fa-circle-exclamation"></i> ملاحظات إدارة المنصة:
                        </strong>
                        <p style="font-size:12.5px;color:#92400e;margin:0;">${ad.notes}</p>
                    </div>
                `;
            }

            body.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:16px;">
                    <div>
                        <h4 style="font-size:18px;font-weight:800;color:var(--navy);margin-bottom:4px;">${ad.title || 'إعلان بدون عنوان'}</h4>
                        <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;font-size:11.5px;padding:3px 10px;border-radius:12px;">
                            الباقة: ${ad.package_label || ad.package}
                        </span>
                    </div>
                    <div style="text-align:left;">
                        <div style="font-size:18px;font-weight:800;color:var(--teal);">${ad.amount_formatted}</div>
                        <div style="font-size:11px;color:var(--ink-muted);">${ad.duration} أيام</div>
                    </div>
                </div>

                <div style="padding:14px;background:var(--surface-subtle);border-radius:8px;margin-bottom:14px;">
                    <div style="font-size:12px;font-weight:700;color:var(--ink-muted);margin-bottom:4px;">نص ووصف الإعلان:</div>
                    <p style="font-size:13.5px;color:var(--ink);line-height:1.6;margin:0;white-space:pre-wrap;">${ad.content || 'لا يوجد وصف مضاف'}</p>
                </div>

                ${mediaHtml}

                <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid var(--border);font-size:12.5px;">
                    <div><span style="color:var(--ink-muted);">طريقة الدفع:</span> <strong>${ad.pay_method || '—'}</strong></div>
                    <div><span style="color:var(--ink-muted);">رقم المرجع/الحوالة:</span> <strong>${ad.pay_reference || '—'}</strong></div>
                    <div><span style="color:var(--ink-muted);">تاريخ الإنشاء:</span> <strong>${ad.created_at || '—'}</strong></div>
                    <div><span style="color:var(--ink-muted);">تاريخ النشر:</span> <strong>${ad.published_at || 'لم يُنشر بعد'}</strong></div>
                    <div><span style="color:var(--ink-muted);">حالة المراجعة:</span> <strong style="color:var(--teal);">${ad.review_status}</strong></div>
                    <div><span style="color:var(--ink-muted);">حالة النشر:</span> <strong>${ad.status}</strong></div>
                </div>

                ${receiptHtml}
                ${notesHtml}
            `;
        } catch(e) {
            body.innerHTML = `<p style="color:#ef4444;text-align:center;padding:20px;">حدث خطأ في الاتصال بالخادم.</p>`;
        }
    }

    function closeAdDetailsModal() {
        document.getElementById('adDetailsModal').style.display = 'none';
    }

    async function cancelDraftAd(adId) {
        if (!confirm('هل أنت متأكد من رغبتك في إلغاء وحذف مسودة هذا الإعلان؟ لا يمكن التراجع عن هذا الإجراء.')) {
            return;
        }

        try {
            const res = await fetch(`/university/advertisements/${adId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'تعذر حذف المسودة.');
            }
        } catch(e) {
            alert('حدث خطأ في الاتصال.');
        }
    }

    // إغلاق النوافذ عند النقر خارجها
    window.addEventListener('click', function(e) {
        const detailsModal = document.getElementById('adDetailsModal');
        const newModal = document.getElementById('newAdModal');
        if (e.target === detailsModal) closeAdDetailsModal();
        if (e.target === newModal) closeNewAdModal();
    });
</script>
@endpush
