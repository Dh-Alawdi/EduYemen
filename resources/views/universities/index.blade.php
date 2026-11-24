@extends('layouts.app')

@section('title', 'دليل الجامعات اليمنية — EduYemen')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">

<style>
    /* تطبيق خط IBM Plex Sans Arabic الحديث فائق الوضوح والأناقة على كافة عناصر دليل الجامعات */
    body,
    .univ-page-header,
    .filter-card,
    .univ-modern-card,
    .btn,
    .badge,
    input,
    select,
    button,
    #universitiesGridContainer {
        font-family: 'IBM Plex Sans Arabic', 'Almarai', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        letter-spacing: normal;
    }

    /* حماية خط الأيقونات من التداخل */
    .bi, [class*="bi-"], [class^="bi-"] {
        font-family: "bootstrap-icons" !important;
    }
    .fa, .fa-solid, .fa-regular, .fa-brands, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
    }

    /* ترويسة الصفحة الحديثة المتوافقة مع ألوان الشعار */
    .univ-page-header {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 60%, #1565a8 100%);
        padding: 2.8rem 0 4.8rem;
        position: relative;
        overflow: hidden;
    }

    .univ-page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #c59b27 0%, #f0c842 50%, #c59b27 100%);
    }

    /* كرت الفلترة والبحث الفاخر */
    .filter-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 12px 35px -5px rgba(11, 61, 107, 0.08), 0 0 0 1px rgba(11, 61, 107, 0.06);
        border: 1px solid rgba(226, 232, 240, 0.85);
        position: relative;
        z-index: 10;
        margin-top: -3rem;
    }

    /* حقل وأيقونة البحث المحسنة */
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon-indicator {
        position: absolute;
        right: 14px;
        color: #64748b;
        font-size: 1.15rem;
        pointer-events: none;
        transition: all 0.25s ease;
        z-index: 5;
    }

    .modern-search-input {
        padding-right: 44px !important;
        padding-left: 38px !important;
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 0.92rem;
        transition: all 0.25s ease;
        color: #1e293b;
    }

    .modern-search-input:focus {
        background-color: #ffffff;
        border-color: #c59b27;
        box-shadow: 0 0 0 4px rgba(197, 155, 39, 0.18);
    }

    .search-input-wrapper:focus-within .search-icon-indicator {
        color: #c59b27;
        transform: scale(1.15) rotate(-8deg);
    }

    .btn-clear-search {
        position: absolute;
        left: 12px;
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: color 0.15s;
        z-index: 5;
    }

    .btn-clear-search:hover {
        color: #ef4444;
    }

    .search-spinner-indicator {
        position: absolute;
        left: 14px;
        z-index: 5;
    }

    .modern-select {
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
        transition: all 0.2s ease;
    }

    .modern-select:focus {
        border-color: #1565a8;
        box-shadow: 0 0 0 3px rgba(21, 101, 168, 0.15);
    }

    .btn-reset-compact {
        height: 48px;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    /* =========================================================
       بطاقة عرض الجامعة الفاخرة والعصرية (World-Class University Card)
       ========================================================= */
    .univ-modern-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(11, 61, 107, 0.05);
        overflow: hidden;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .univ-modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #0b3d6b 0%, #1565a8 50%, #c59b27 100%);
        opacity: 0.85;
        transition: opacity 0.3s ease, height 0.3s ease;
        z-index: 2;
    }

    .univ-modern-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 38px -8px rgba(11, 61, 107, 0.13), 0 0 0 1px rgba(197, 155, 39, 0.3);
        border-color: rgba(197, 155, 39, 0.35);
    }

    .univ-modern-card:hover::before {
        height: 4px;
        opacity: 1;
    }

    /* رأس كرت الجامعة */
    .univ-card-header-box {
        padding: 1.15rem 1.15rem 1rem 1.15rem;
        background: linear-gradient(135deg, #f8fafc 0%, #edf4fc 100%);
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .univ-logo-box-inner {
        width: 66px;
        height: 66px;
        min-width: 66px;
        background: #ffffff;
        border-radius: 14px;
        padding: 5px;
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.25s ease;
        flex-shrink: 0;
    }

    .univ-modern-card:hover .univ-logo-box-inner {
        transform: scale(1.04);
    }

    .univ-logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .univ-logo-quarantined {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: #fef2f2;
        border-radius: 8px;
        color: #dc2626;
    }

    .univ-header-info-col {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
    }

    .univ-badges-row {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }

    .badge-type-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2.5px 9px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .badge-gov-modern {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
    }

    .badge-private-modern {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
    }

    .badge-status-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2.5px 8px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .badge-open-modern {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .badge-closed-modern {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .live-dot-green {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 6px #10b981;
        display: inline-block;
        animation: livePulseDot 2s infinite;
    }

    @keyframes livePulseDot {
        0% { transform: scale(0.9); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.9); opacity: 0.8; }
    }

    .univ-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin: 3px 0 0 0;
        line-height: 1.4;
        transition: color 0.2s ease;
    }

    .univ-modern-card:hover .univ-card-title {
        color: #0b3d6b;
    }

    /* جسم البطاقة */
    .univ-card-body-area {
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .univ-card-description {
        font-size: 0.81rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 0.85rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* إحصائيات الكليات والتخصصات المتاحة بتصميم راقي */
    .univ-academic-stats-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 9px;
        margin-bottom: 0.85rem;
    }

    .univ-stat-chip {
        padding: 7px 10px;
        border-radius: 12px;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .univ-stat-colleges {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .univ-stat-specs {
        background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
        border: 1px solid rgba(14, 165, 233, 0.22);
    }

    .univ-stat-chip:hover {
        transform: translateY(-1px);
    }

    .univ-stat-count {
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.2;
        text-align: center;
    }

    .univ-stat-colleges .univ-stat-count {
        color: #047857;
    }

    .univ-stat-specs .univ-stat-count {
        color: #0369a1;
    }

    .univ-stat-label {
        font-size: 0.69rem;
        font-weight: 700;
        margin-top: 2px;
    }

    .univ-stat-colleges .univ-stat-label {
        color: #065f46;
    }

    .univ-stat-specs .univ-stat-label {
        color: #0284c7;
    }

    /* بيانات الموقع وعام التأسيس ورقم الهاتف متناسقة ومنسجمة معاً دون تباعد شاذ */
    .univ-card-meta-section {
        margin-top: auto;
        padding-top: 0.65rem;
        border-top: 1px dashed #e2e8f0;
    }

    .univ-meta-location {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.77rem;
        color: #475569;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .univ-meta-location i {
        font-size: 0.86rem;
    }

    .univ-compact-meta-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        font-size: 0.76rem;
        color: #64748b;
    }

    .univ-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }

    .univ-meta-item i {
        font-size: 0.84rem;
    }

    .univ-meta-bullet {
        color: #cbd5e1;
        font-weight: 900;
        font-size: 0.75rem;
        user-select: none;
    }

    /* أزرار الإجراءات السفلية */
    .univ-card-footer-actions {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 0.75rem 1.15rem !important;
        background: #ffffff !important;
        border-top: 1px solid #f1f5f9 !important;
    }

    .btn-univ-view {
        flex: 1 1 auto !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 7px !important;
        background: linear-gradient(135deg, #0b3d6b 0%, #1565a8 100%) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 0.84rem !important;
        padding: 0.52rem 0.95rem !important;
        border-radius: 11px !important;
        border: none !important;
        text-decoration: none !important;
        box-shadow: 0 3px 8px rgba(11, 61, 107, 0.16) !important;
        transition: all 0.22s ease !important;
        white-space: nowrap !important;
    }

    .btn-univ-view:hover {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%) !important;
        color: #ffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 14px rgba(11, 61, 107, 0.25) !important;
    }

    .btn-univ-view i {
        transition: transform 0.2s ease !important;
    }

    .btn-univ-view:hover i {
        transform: translateX(-4px) !important;
    }

    .btn-univ-compare {
        flex: 0 0 auto !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 5px !important;
        background: #f8fafc !important;
        color: #334155 !important;
        border: 1.5px solid #e2e8f0 !important;
        font-weight: 700 !important;
        font-size: 0.82rem !important;
        padding: 0.5rem 0.8rem !important;
        border-radius: 11px !important;
        text-decoration: none !important;
        transition: all 0.22s ease !important;
        white-space: nowrap !important;
    }

    .btn-univ-compare:hover {
        background: #fffdf5 !important;
        border-color: #c59b27 !important;
        color: #926f14 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 3px 8px rgba(197, 155, 39, 0.18) !important;
    }

    /* =========================================================
       تحسينات متجاوبة مع شاشات الهواتف (< 768px)
    ========================================================= */
    @media (max-width: 767.98px) {
        .univ-page-header {
            padding: 1.5rem 0 2.8rem !important;
        }
        .filter-card {
            margin-top: -1.6rem !important;
            padding: 0.75rem 0.85rem !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 16px rgba(11, 61, 107, 0.08) !important;
            margin-bottom: 1.2rem !important;
        }
        .modern-search-input {
            height: 40px !important;
            font-size: 0.84rem !important;
            padding-right: 36px !important;
            padding-left: 30px !important;
            border-radius: 10px !important;
        }
        .search-icon-indicator {
            right: 11px !important;
            font-size: 0.95rem !important;
        }
        .modern-select {
            height: 40px !important;
            font-size: 0.82rem !important;
            padding: 0.35rem 0.5rem !important;
            border-radius: 10px !important;
        }
        .btn-reset-compact {
            height: 40px !important;
            padding: 0.35rem 0.45rem !important;
            font-size: 0.82rem !important;
            border-radius: 10px !important;
        }
        .univ-card-header-box {
            padding: 0.85rem 0.95rem !important;
            gap: 11px !important;
        }
        .univ-logo-box-inner {
            width: 54px !important;
            height: 54px !important;
            min-width: 54px !important;
            border-radius: 11px !important;
            padding: 4px !important;
        }
        .univ-card-title {
            font-size: 0.96rem !important;
        }
        .univ-card-body-area {
            padding: 0.95rem !important;
        }
        .univ-card-footer-actions {
            padding: 0.6rem 0.95rem !important;
            gap: 6px !important;
        }
        .btn-univ-view {
            font-size: 0.78rem !important;
            padding: 0.45rem 0.65rem !important;
        }
        .btn-univ-compare {
            font-size: 0.78rem !important;
            padding: 0.45rem 0.65rem !important;
        }
    }

    @media (max-width: 576px) {
        .univ-page-header h1 {
            font-size: 1.35rem !important;
        }
        .univ-page-header p {
            font-size: 0.8rem !important;
        }
        .univ-header-logo {
            width: 44px !important;
            height: 44px !important;
        }
    }

    /* بانر إعلاني مدمج لدليل الجامعات (مميزة وشاملة) */
    .directory-ad-strip {
        background: linear-gradient(135deg, rgba(11, 61, 107, 0.04) 0%, rgba(197, 155, 39, 0.08) 100%);
        border: 1px solid rgba(197, 155, 39, 0.35);
        border-radius: 14px;
        padding: 0.65rem 1.1rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(11, 61, 107, 0.04);
        transition: all 0.25s ease;
    }

    .directory-ad-strip:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(197, 155, 39, 0.15);
    }

    .ad-strip-badge {
        background: linear-gradient(135deg, rgba(197, 155, 39, 0.15) 0%, rgba(197, 155, 39, 0.25) 100%);
        color: #926f14;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid rgba(197, 155, 39, 0.3);
        white-space: nowrap;
    }

    .ad-strip-thumb {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 1.5px solid rgba(197, 155, 39, 0.35);
        flex-shrink: 0;
        background: #ffffff;
    }

    .ad-strip-btn {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #ffffff !important;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.4rem 0.95rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(11, 61, 107, 0.15);
        flex-shrink: 0;
    }

    .ad-strip-btn:hover {
        background: linear-gradient(135deg, #072b4f 0%, var(--primary) 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(11, 61, 107, 0.25);
    }

    @media (max-width: 576px) {
        .directory-ad-strip {
            padding: 0.6rem 0.75rem;
        }
        .ad-strip-thumb {
            width: 40px;
            height: 40px;
        }
        .ad-strip-btn {
            font-size: 0.74rem;
            padding: 0.35rem 0.7rem;
        }
    }

    /* زر عرض المزيد الحديث التفاعلي */
    .btn-load-more-modern {
        background: #ffffff;
        color: var(--primary);
        border: 1.5px solid var(--primary-light);
        border-radius: 50px;
        padding: 0.65rem 2.4rem;
        font-size: 0.92rem;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.08);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-load-more-modern:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(11, 61, 107, 0.18);
    }

    .btn-load-more-modern:active {
        transform: translateY(0);
    }

    .btn-load-more-modern .btn-text,
    .btn-load-more-modern .btn-loading {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endsection

@section('content')

{{-- ====== PAGE HEADER ====== --}}
<div class="univ-page-header">
    <div class="container text-white">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size:0.84rem;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">الرئيسية</a></li>
                    <li class="breadcrumb-item active text-white">دليل الجامعات</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-3">
            <img src="/images/logo.png"
                 class="univ-header-logo"
                 onerror="this.onerror=null; this.src='/images/eduyemen-logo.svg';"
                 style="width: 58px; height: 58px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));"
                 alt="شعار EduYemen">
            <div>
                <h1 class="fw-bold mb-1" style="font-size:1.85rem;">دليل الجامعات اليمنية</h1>
                <p class="text-white-50 mb-0" style="font-size: 0.92rem;">استعرض الجامعات الحكومية والأهلية المعتمدة في أمانة العاصمة صنعاء وتعرف على تفاصيلها</p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    {{-- ====== FILTER & REAL-TIME SEARCH CARD (ملمومة ومضغوطة لشاشة الهاتف) ====== --}}
    <div class="filter-card mb-4">
        <form method="GET" action="{{ route('universities.index') }}" id="searchFilterForm" class="row g-2 g-md-3 align-items-end" onsubmit="event.preventDefault();">
            
            {{-- حقل البحث الفوري أثناء الكتابة --}}
            <div class="col-12 col-md-5">
                <label for="liveSearchInput" class="form-label small fw-bold text-secondary mb-1 d-none d-md-block">
                    البحث التلقائي السريع
                </label>
                <div class="search-input-wrapper">
                    <span class="search-icon-indicator" id="searchIconIndicator">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           id="liveSearchInput"
                           value="{{ request('search') }}"
                           class="form-control modern-search-input"
                           placeholder="اكتب اسم الجامعة أو كلمة مفتاحية للبحث فوراً…"
                           autocomplete="off">
                    
                    {{-- زر مسح البحث السريع --}}
                    <button type="button" class="btn-clear-search d-none" id="btnClearSearch" onclick="clearLiveSearch()" title="مسح البحث">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>

                    {{-- مؤشر التحميل أثناء الجلب --}}
                    <div class="search-spinner-indicator d-none" id="searchSpinner">
                        <div class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1rem; height: 1rem;"></div>
                    </div>
                </div>
            </div>

            {{-- نوع الجامعة --}}
            <div class="col-5 col-md-3">
                <label for="filterType" class="form-label small fw-bold text-secondary mb-1 d-none d-md-block">نوع الجامعة</label>
                <select name="type" id="filterType" class="form-select modern-select" onchange="performLiveSearch()">
                    <option value="">كل الأنواع</option>
                    <option value="حكومية" {{ request('type')=='حكومية' ? 'selected' : '' }}>حكومية</option>
                    <option value="أهلية"  {{ request('type')=='أهلية'  ? 'selected' : '' }}>أهلية</option>
                </select>
            </div>

            {{-- المديرية --}}
            <div class="col-4 col-md-2">
                <label for="filterDistrict" class="form-label small fw-bold text-secondary mb-1 d-none d-md-block">المديرية</label>
                <select name="district" id="filterDistrict" class="form-select modern-select" onchange="performLiveSearch()">
                    <option value="">كل المديريات</option>
                    @foreach($districts as $d)
                        <option value="{{ $d }}" {{ request('district')==$d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            {{-- زر إعادة الضبط --}}
            <div class="col-3 col-md-2 d-flex">
                <button type="button" class="btn btn-outline-secondary w-100 btn-reset-compact" onclick="resetAllFilters()" title="إعادة ضبط الفلاتر">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span class="d-none d-sm-inline ms-1">ضبط</span>
                </button>
            </div>
        </form>
    </div>

    {{-- مساحة إعلانية مصغرة لدليل الجامعات (مميزة وشاملة فقط) --}}
    @if(isset($directoryAds) && $directoryAds->isNotEmpty())
    <div class="directory-ad-strip position-relative" id="directoryAdStrip">
        @foreach($directoryAds as $idx => $dirAd)
            @php
                $firstImg = $dirAd->firstMediaUrl() ?? ($dirAd->university?->logo_url ? asset(ltrim($dirAd->university->logo_url, '/')) : null);
            @endphp
            <div class="directory-ad-item d-flex align-items-center justify-content-between gap-3 {{ $idx === 0 ? '' : 'd-none' }}" data-ad-idx="{{ $idx }}">
                <div class="d-flex align-items-center gap-3 overflow-hidden">
                    <span class="ad-strip-badge">
                        <i class="bi bi-megaphone-fill text-warning"></i>
                        <span>إعلان ممول</span>
                    </span>
                    @if($firstImg)
                        <img src="{{ $firstImg }}" alt="{{ $dirAd->adv_title }}" class="ad-strip-thumb" onerror="this.onerror=null; this.src='/images/logo.png';">
                    @elseif($dirAd->media_type === 'video')
                        <div class="ad-strip-thumb d-flex align-items-center justify-content-center text-warning" style="background:#091e33;">
                            <i class="bi bi-play-circle-fill fs-5"></i>
                        </div>
                    @else
                        <img src="/images/logo.png" alt="{{ $dirAd->adv_title }}" class="ad-strip-thumb">
                    @endif
                    <div class="overflow-hidden">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark text-truncate" style="font-size: 0.88rem;">{{ $dirAd->university?->university_name }}</span>
                            @if($dirAd->package === 'شاملة')
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem; font-weight: 700; padding: 1px 6px;">شاملة</span>
                            @endif
                            @if($dirAd->media_type === 'video')
                                <span class="badge bg-primary text-white" style="font-size: 0.65rem; font-weight: 700; padding: 1px 6px;">
                                    <i class="bi bi-play-circle-fill me-1"></i>فيديو إعلاني
                                </span>
                            @endif
                        </div>
                        <div class="text-muted text-truncate" style="font-size: 0.8rem;" title="{{ $dirAd->adv_title }}">
                            {{ $dirAd->adv_title }}
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    @if($directoryAds->count() > 1)
                        <div class="d-flex align-items-center gap-1 me-1">
                            <button type="button" class="btn btn-sm btn-light border p-0 d-flex align-items-center justify-content-center" style="width:24px;height:24px;border-radius:50%;font-size:11px;" onclick="cycleDirectoryAd(-1)" title="الإعلان السابق">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                            <span class="small text-muted" style="font-size: 0.72rem;" id="dirAdCounter">{{ $idx + 1 }}/{{ $directoryAds->count() }}</span>
                            <button type="button" class="btn btn-sm btn-light border p-0 d-flex align-items-center justify-content-center" style="width:24px;height:24px;border-radius:50%;font-size:11px;" onclick="cycleDirectoryAd(1)" title="الإعلان التالي">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        </div>
                    @endif
                    <a href="{{ route('universities.show', $dirAd->university_id) }}" class="ad-strip-btn">
                        <span>الانتقال إلى الجامعة</span>
                        <i class="bi bi-box-arrow-up-left ms-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    @if($directoryAds->count() > 1)
    <script>
    let dirAdCurrentIdx = 0;
    const dirAdItems = document.querySelectorAll('#directoryAdStrip .directory-ad-item');
    let dirAdTimer = null;

    function showDirectoryAd(idx) {
        if (!dirAdItems || dirAdItems.length === 0) return;
        dirAdItems.forEach(el => el.classList.add('d-none'));
        dirAdCurrentIdx = (idx + dirAdItems.length) % dirAdItems.length;
        dirAdItems[dirAdCurrentIdx].classList.remove('d-none');
        const counter = document.getElementById('dirAdCounter');
        if (counter) counter.textContent = `${dirAdCurrentIdx + 1}/${dirAdItems.length}`;
    }

    function cycleDirectoryAd(dir) {
        showDirectoryAd(dirAdCurrentIdx + dir);
        resetDirAdTimer();
    }

    function resetDirAdTimer() {
        if (dirAdTimer) clearInterval(dirAdTimer);
        dirAdTimer = setInterval(() => {
            showDirectoryAd(dirAdCurrentIdx + 1);
        }, 7000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (dirAdItems.length > 1) {
            resetDirAdTimer();
            const strip = document.getElementById('directoryAdStrip');
            if (strip) {
                strip.addEventListener('mouseenter', () => clearInterval(dirAdTimer));
                strip.addEventListener('mouseleave', resetDirAdTimer);
            }
        }
    });
    </script>
    @endif
    @endif

    {{-- ====== RESULTS HEADER ====== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="small text-muted" id="resultsCounter">
            <i class="bi bi-grid-3x3-gap me-1 text-primary"></i>
            عُثر على <strong class="text-dark" id="totalCountDisplay">{{ $universities->total() }}</strong> جامعة
        </div>
        <a href="{{ route('compare') }}" class="btn btn-sm btn-outline-primary fw-bold">
            <i class="bi bi-arrow-left-right me-1"></i>مقارنة الجامعات
        </a>
    </div>

    {{-- ====== UNIVERSITIES GRID CONTAINER ====== --}}
    <div id="universitiesGridContainer">
        @if($universities->isEmpty())
        <div class="text-center bg-white rounded-4 border p-5">
            <i class="bi bi-search text-muted opacity-50" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold text-dark mt-3">لم يُعثر على جامعات مطابقة</h5>
            <p class="small text-muted mb-3">جرّب كتابة اسم آخر أو تغيير فلاتر البحث</p>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="resetAllFilters()">عرض كل الجامعات</button>
        </div>
        @else
        <div class="row g-4" id="universitiesCardRow">
            @foreach($universities as $univ)
            <div class="col-md-6 col-lg-4 univ-grid-item"
                 data-name="{{ mb_strtolower($univ->university_name) }}"
                 data-type="{{ $univ->university_type }}"
                 data-district="{{ $univ->district }}">
                <div class="univ-modern-card">
                    
                    {{-- رأس البطاقة المطور: الشعار في إطار فاخر + الشارات + اسم الجامعة --}}
                    <div class="univ-card-header-box">
                        {{-- إطار شعار الجامعة في جهة اليمين --}}
                        <div class="univ-logo-box-inner">
                            @if($univ->isItemQuarantined('logo'))
                                <div class="univ-logo-quarantined" title="شعار الجامعة محجوب رقابياً بقرار إشرافي">
                                    <i class="fa-solid fa-shield-virus"></i>
                                    <span style="font-size:8px; font-weight:800; margin-top:2px;">محجوب</span>
                                </div>
                            @else
                                <img src="{{ $univ->logo_url }}"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'65\' height=\'65\'%3E%3Crect width=\'65\' height=\'65\' fill=\'%23ffffff\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%230b3d6b\' font-size=\'26\'%3E🏛%3C/text%3E%3C/svg%3E'"
                                     class="univ-logo-img"
                                     alt="شعار {{ $univ->university_name }}">
                            @endif
                        </div>

                        {{-- اسم الجامعة ونوعها إلى جانبه --}}
                        <div class="univ-header-info-col">
                            <div class="univ-badges-row">
                                <span class="badge-type-pill {{ $univ->university_type == 'حكومية' ? 'badge-gov-modern' : 'badge-private-modern' }}">
                                    <i class="bi {{ $univ->university_type == 'حكومية' ? 'bi-bank2' : 'bi-mortarboard-fill' }}"></i>
                                    <span>{{ $univ->university_type }}</span>
                                </span>
                                @if($univ->status === 'نشطة')
                                    <span class="badge-status-pill badge-open-modern">
                                        <span class="live-dot-green"></span>
                                        <span>القبول مفتوح</span>
                                    </span>
                                @else
                                    <span class="badge-status-pill badge-closed-modern">
                                        <i class="bi bi-x-circle-fill"></i>
                                        <span>القبول مغلق</span>
                                    </span>
                                @endif
                            </div>
                            <h3 class="univ-card-title text-truncate" title="{{ $univ->university_name }}">
                                {{ $univ->university_name }}
                            </h3>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="univ-card-body-area">
                        <p class="univ-card-description">
                            @if($univ->isItemQuarantined('description'))
                                <span class="text-danger fw-bold"><i class="fa-solid fa-shield-halved me-1"></i> [النبذة التعريفية محجوبة رقابياً للمراجعة]</span>
                            @else
                                {{ Str::limit($univ->description, 110) }}
                            @endif
                        </p>

                        {{-- كليات وتخصصات بتصميم إحصائي راقي ومميز (أرقام واضحة دون أيقونات) --}}
                        <div class="univ-academic-stats-row">
                            <div class="univ-stat-chip univ-stat-colleges">
                                <div class="univ-stat-count">{{ $univ->colleges_count }}</div>
                                <div class="univ-stat-label">كليات معتمدة</div>
                            </div>
                            <div class="univ-stat-chip univ-stat-specs">
                                <div class="univ-stat-count">{{ $univ->specializations_count }}</div>
                                <div class="univ-stat-label">تخصص متاح</div>
                            </div>
                        </div>

                        {{-- تفاصيل الموقع والتأسيس ورقم الهاتف متناسقة ومنسجمة معاً دون تباعد شاذ --}}
                        <div class="univ-card-meta-section">
                            <div class="univ-meta-location">
                                <i class="bi bi-geo-alt-fill text-danger flex-shrink-0"></i>
                                <span class="text-truncate">{{ $univ->district }} — {{ Str::limit($univ->address, 36) }}</span>
                            </div>
                            @if($univ->year_establ || ($univ->contact_phone ?? $univ->phone))
                            <div class="univ-compact-meta-group">
                                @if($univ->year_establ)
                                <div class="univ-meta-item" title="عام التأسيس">
                                    <i class="bi bi-calendar-check text-primary"></i>
                                    <span>تأسست عام {{ $univ->year_establ }}</span>
                                </div>
                                @endif
                                @if($univ->year_establ && ($univ->contact_phone ?? $univ->phone))
                                <span class="univ-meta-bullet">•</span>
                                @endif
                                @if($univ->contact_phone ?? $univ->phone)
                                <div class="univ-meta-item" title="رقم الهاتف للتواصل">
                                    <i class="bi bi-telephone-fill text-success"></i>
                                    <span dir="ltr">{{ $univ->contact_phone ?? $univ->phone }}</span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Actions: استكشف الجامعة + مقارنة في سطر واحد متناسق --}}
                    <div class="univ-card-footer-actions">
                        <a href="{{ route('universities.show', $univ->university_id) }}" class="btn-univ-view" title="الانتقال إلى صفحة الجامعة">
                            <span class="btn-univ-view-text">استكشف الجامعة</span>
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <a href="{{ route('compare') }}?u1={{ $univ->university_id }}" class="btn-univ-compare" title="مقارنة هذه الجامعة" aria-label="مقارنة الجامعة">
                            <i class="bi bi-arrow-left-right"></i>
                            <span class="btn-univ-compare-text">مقارنة</span>
                        </a>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        {{-- زر عرض المزيد التفاعلي (استبدال الترقيم التقليدي) لاستكمال الصفحة دون حدوث أي انتقال أو قفز للشاشة --}}
        <div class="d-flex flex-column align-items-center justify-content-center mt-4 mb-3" id="paginationWrapper">
            @if($universities->hasMorePages())
                <button type="button" 
                        class="btn-load-more-modern" 
                        id="btnLoadMore" 
                        data-next-page="{{ $universities->nextPageUrl() }}" 
                        onclick="loadMoreUniversities(this)">
                    <span class="btn-text">
                        <i class="bi bi-arrow-down-circle"></i>
                        <span>عرض المزيد</span>
                    </span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>جاري تحميل الجامعات...</span>
                    </span>
                </button>
            @endif
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    let searchDebounceTimer = null;

    const liveInput = document.getElementById('liveSearchInput');
    const clearBtn  = document.getElementById('btnClearSearch');
    const spinner   = document.getElementById('searchSpinner');

    // إظهار زر المسح عند وجود قيمة
    if (liveInput) {
        liveInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                clearBtn.classList.remove('d-none');
            } else {
                clearBtn.classList.add('d-none');
            }

            // فلترة بصرية فورية لحظية (0ms) للبطاقات المعروضة
            filterCardsInstantly(this.value.trim().toLowerCase());

            // جلب نتائج الخادم بالـ debounce (250ms)
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                performLiveSearch();
            }, 250);
        });
    }

    // فلترة فورية للبطاقات الموجودة في الصفحة
    function filterCardsInstantly(query) {
        const cards = document.querySelectorAll('.univ-grid-item');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const district = card.getAttribute('data-district') || '';
            if (!query || name.includes(query) || district.includes(query)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const totalDisplay = document.getElementById('totalCountDisplay');
        if (totalDisplay && query) {
            totalDisplay.textContent = visibleCount;
        }
    }

    // تنفيذ طلب البحث المباشر وجلب المحتوى عبر AJAX
    function performLiveSearch() {
        const search   = liveInput ? liveInput.value.trim() : '';
        const type     = document.getElementById('filterType').value;
        const district = document.getElementById('filterDistrict').value;

        if (spinner) spinner.classList.remove('d-none');

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (type) params.append('type', type);
        if (district) params.append('district', district);

        const targetUrl = "{{ route('universities.index') }}?" + params.toString();

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            // استخراج محتوى الحاوية والترقيم
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('universitiesGridContainer');
            const newCounter = doc.getElementById('resultsCounter');

            if (newGrid) {
                document.getElementById('universitiesGridContainer').innerHTML = newGrid.innerHTML;
            }
            if (newCounter) {
                document.getElementById('resultsCounter').innerHTML = newCounter.innerHTML;
            }

            // تحديث رابط المتصفح دون إعادة تحميل الصفحة
            window.history.replaceState({}, '', targetUrl);
        })
        .catch(err => {
            console.error('Error in live search:', err);
        })
        .finally(() => {
            if (spinner) spinner.classList.add('d-none');
        });
    }

    // مسح البحث
    function clearLiveSearch() {
        if (liveInput) {
            liveInput.value = '';
            clearBtn.classList.add('d-none');
            performLiveSearch();
        }
    }

    // إعادة ضبط جميع الفلاتر
    function resetAllFilters() {
        if (liveInput) liveInput.value = '';
        if (clearBtn) clearBtn.classList.add('d-none');
        document.getElementById('filterType').value = '';
        document.getElementById('filterDistrict').value = '';
        performLiveSearch();
    }

    /**
     * جلب واستكمال بقية الجامعات بسلاسة دون اهتزاز أو انتقال في الشاشة
     */
    function loadMoreUniversities(btn) {
        const nextUrl = btn.getAttribute('data-next-page');
        if (!nextUrl || btn.disabled) return;

        const btnText = btn.querySelector('.btn-text');
        const btnLoading = btn.querySelector('.btn-loading');

        btn.disabled = true;
        if (btnText) btnText.classList.add('d-none');
        if (btnLoading) btnLoading.classList.remove('d-none');

        fetch(nextUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newCards = doc.querySelectorAll('#universitiesCardRow .univ-grid-item');
            const cardRow = document.getElementById('universitiesCardRow');

            if (cardRow && newCards.length > 0) {
                newCards.forEach(card => {
                    cardRow.appendChild(card);
                });
            }

            // التحقق من وجود زر عرض المزيد بالصفحة التالية
            const nextBtnInDoc = doc.getElementById('btnLoadMore');
            const pagWrapper = document.getElementById('paginationWrapper');

            if (nextBtnInDoc && nextBtnInDoc.getAttribute('data-next-page')) {
                btn.setAttribute('data-next-page', nextBtnInDoc.getAttribute('data-next-page'));
                btn.disabled = false;
                if (btnText) btnText.classList.remove('d-none');
                if (btnLoading) btnLoading.classList.add('d-none');
            } else {
                // انتهت كافة الجامعات
                if (pagWrapper) {
                    const totalLoaded = document.querySelectorAll('.univ-grid-item').length;
                    pagWrapper.innerHTML = `
                        <div class="text-center py-2" style="font-size: 0.84rem; color: #64748b; font-weight: 600;">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            تم استعراض كافة الجامعات المعتمدة (${totalLoaded})
                        </div>
                    `;
                }
            }
        })
        .catch(err => {
            console.error('Error loading more universities:', err);
            btn.disabled = false;
            if (btnText) btnText.classList.remove('d-none');
            if (btnLoading) btnLoading.classList.add('d-none');
        });
    }
</script>
@endsection
