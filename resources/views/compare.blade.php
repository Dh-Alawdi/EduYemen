@extends('layouts.app')

@section('title', 'مقارنة الجامعات والتخصصات — EduYemen')

@section('styles')
<style>
    /* ========================================================
       تصميم صفحة المقارنة الشاملة — الهوية الرسمية EduYemen
       ======================================================== */
    :root {
        --cmp-navy: #0b3d6b;
        --cmp-navy-dark: #072644;
        --cmp-blue: #1565a8;
        --cmp-gold: #c59b27;
        --cmp-border: #e2e8f0;
        --cmp-bg-light: #f8fafc;
    }

    /* رأس الصفحة (Hero Banner) */
    .compare-hero {
        background: linear-gradient(135deg, var(--cmp-navy-dark) 0%, var(--cmp-navy) 65%, var(--cmp-blue) 100%);
        padding: 2.2rem 0 3.5rem;
        position: relative;
        color: #ffffff;
    }

    .compare-hero-wave {
        position: absolute;
        bottom: -1px; left: 0; right: 0;
        pointer-events: none;
    }

    /* كرت اختيار الجامعات */
    .compare-form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 18px rgba(11, 61, 107, 0.07);
        border: 1px solid var(--cmp-border);
        margin-top: -2rem;
        position: relative;
        z-index: 10;
    }




    /* زر بدء المقارنة الكبير وتنسيقه المتجاوب */
    .btn-start-compare-lg {
        padding: 0.6rem 2.4rem;
        font-size: 1.05rem;
        border-radius: 8px;
        min-width: 200px;
        transition: all 0.25s ease-in-out;
    }
    .btn-start-compare-lg:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 61, 107, 0.28) !important;
    }
    @media (max-width: 767.98px) {
        .compare-action-box {
            margin-top: 0.75rem;
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
        }
        .btn-start-compare-lg {
            width: 100%;
            max-width: 320px;
            font-size: 1rem;
            padding: 0.65rem 1.5rem;
        }
    }

    /* تأثير بروز خيارات المقارنة عند تحديد الجامعات */
    #compareScopeSelectorRow {
        transition: all 0.3s ease-in-out;
    }
    .scope-ready-highlight {
        background: rgba(11, 61, 107, 0.04);
        border-radius: 8px;
        padding: 8px 12px !important;
        border-color: rgba(11, 61, 107, 0.2) !important;
    }
    .scroll-target-section {
        scroll-margin-top: 80px;
    }

    .compare-scope-btn {
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 0.86rem;
        font-weight: 700;
        border: 1.5px solid var(--cmp-border);
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .compare-scope-btn:hover {
        border-color: var(--cmp-navy);
        color: var(--cmp-navy);
        background: #f1f5f9;
    }

    .compare-scope-btn.active {
        background: var(--cmp-navy);
        border-color: var(--cmp-navy);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(11, 61, 107, 0.22);
    }

    /* جدول مقارنة الجامعات الشامل - مظهر نظيف وبسيط بدون تعبئة نصوص أو زخارف */
    .compare-table-wrap {
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--cmp-border);
        margin-bottom: 2rem;
    }

    .compare-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .compare-table thead th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.2rem 1rem;
        text-align: center;
        font-weight: 800;
        color: #0f172a;
        vertical-align: middle;
    }

    /* تثبيت العمود الأول لوجه المقارنة عند التمرير الأفقي في الشاشات الصغيرة */
    .compare-table th:first-child,
    .compare-table td:first-child {
        position: sticky;
        right: 0;
        z-index: 5;
        background: #ffffff;
        min-width: 150px;
        max-width: 180px;
        text-align: right;
        font-weight: 700;
        color: #475569;
        border-left: 1px solid #e2e8f0;
    }

    .compare-table thead th:first-child {
        background: #f8fafc;
        z-index: 6;
    }

    /* خلايا الجدول بدون خلفيات ملونة أو حواف مزخرفة للنصوص */
    .compare-table tbody td {
        padding: 1rem 0.9rem;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.9rem;
        text-align: center;
        vertical-align: middle;
        background: #ffffff;
        color: #1e293b;
    }

    .compare-table tbody tr:hover td {
        background: #fcfdfe;
    }

    .univ-col-header {
        min-width: 220px;
    }

    .univ-emblem-box {
        width: 68px;
        height: 68px;
        background: #ffffff;
        border-radius: 12px;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.6rem;
        border: 1px solid var(--cmp-border);
    }

    .univ-emblem-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .univ-header-name {
        font-size: 1rem;
        font-weight: 800;
        color: var(--cmp-navy);
        line-height: 1.4;
        margin-bottom: 4px;
    }

    /* زر التسجيل الأنيق غير المزعج */
    .btn-apply-pleasant {
        background-color: var(--cmp-navy);
        border: 1px solid var(--cmp-navy);
        color: #ffffff;
        transition: all 0.2s ease;
    }

    .btn-apply-pleasant:hover {
        background-color: var(--cmp-navy-dark);
        border-color: var(--cmp-navy-dark);
        color: #ffffff;
    }

    /* قسم مقارنة تخصصين متقابلين (Major vs Major) */
    .spec-compare-section {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.6rem;
        border: 1px solid var(--cmp-border);
        margin-bottom: 2rem;
    }

    .spec-select-box {
        background: #f8fafc;
        border: 1px solid var(--cmp-border);
        border-radius: 10px;
        padding: 0.9rem;
    }

    .spec-vs-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--cmp-navy);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .spec-cmp-table {
        border-collapse: collapse;
        width: 100%;
    }

    .spec-cmp-table th,
    .spec-cmp-table td {
        padding: 0.75rem 0.8rem;
        font-size: 0.86rem;
        border: 1px solid #edf2f7;
    }

    /* تجاوب وتصغير الحجم في الشاشات الصغيرة والهواتف */
    @media (max-width: 767.98px) {
        .compare-hero {
            padding: 1.6rem 0 3rem;
        }
        .compare-form-card {
            padding: 1rem;
        }
        .compare-table th:first-child,
        .compare-table td:first-child {
            min-width: 120px;
            font-size: 0.8rem;
            padding: 0.75rem 0.5rem;
        }
        .univ-col-header {
            min-width: 175px;
        }
        .univ-emblem-box {
            width: 52px;
            height: 52px;
        }
        .univ-header-name {
            font-size: 0.9rem;
        }

        /* تصغير حجم قسم مقارنة التخصصين عند العرض في الهاتف */
        .spec-compare-section {
            padding: 0.85rem !important;
            border-radius: 10px;
        }
        .spec-compare-section h4 {
            font-size: 0.98rem !important;
        }
        .spec-select-box {
            padding: 0.55rem !important;
            border-radius: 8px;
        }
        .spec-select-box label {
            font-size: 0.76rem !important;
            margin-bottom: 3px !important;
        }
        .spec-select-box select {
            font-size: 0.78rem !important;
            padding: 0.35rem 0.5rem !important;
        }
        .spec-vs-badge {
            width: 30px !important;
            height: 30px !important;
            font-size: 0.72rem !important;
        }
        .spec-cmp-table th,
        .spec-cmp-table td {
            padding: 0.45rem 0.4rem !important;
            font-size: 0.76rem !important;
        }
        .spec-cmp-table .btn {
            font-size: 0.72rem !important;
            padding: 0.25rem 0.45rem !important;
        }
    }
</style>
@endsection

@section('content')

{{-- ====== 1. الرأس الرئيسي لصفحة المقارنة ====== --}}
<div class="compare-hero">
    <div class="container position-relative">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0" style="font-size:0.8rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">الرئيسية</a></li>
                <li class="breadcrumb-item active text-white">مقارنة الجامعات</li>
            </ol>
        </nav>
        <h1 class="text-white fw-bold mb-1" style="font-size:1.85rem;">
            <i class="bi bi-arrow-left-right me-2 text-warning"></i>مقارنة الجامعات والتخصصات
        </h1>
        <p class="text-white-50 mb-0">أداة المقارنة الأكاديمية الشاملة للمفاضلة بين الجامعات اليمنية وتخصصاتها جنباً إلى جنب</p>
    </div>
    <div class="compare-hero-wave">
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 20C360 40 1080 0 1440 20V40H0V20Z" fill="#f4f6fb"/>
        </svg>
    </div>
</div>

<div class="container mb-5">

    {{-- ====== 2. استمارة اختيار الجامعات للمقارنة ====== --}}
    <div class="compare-form-card mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size:1rem;">
                <i class="bi bi-sliders me-1 text-primary"></i>تحديد الجامعات للمقارنة المباشرة
            </h5>
            <span class="text-muted small" style="font-size:0.75rem;">
                مقارنة جامعتين أو 3 جامعات معاً
            </span>
        </div>

        <form method="GET" action="{{ route('compare') }}" class="row g-2 g-md-3 align-items-end">
            {{-- حقول اختيار الجامعات مع زر إعادة الضبط بجانبها --}}
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">
                    <i class="bi bi-building text-primary me-1"></i>الجامعة الأولى <span class="text-danger">*</span>
                </label>
                <select name="u1" class="form-select form-select-sm" required>
                    <option value="">اختر الجامعة الأولى...</option>
                    @foreach($universities as $u)
                        <option value="{{ $u->university_id }}" {{ request('u1') == $u->university_id ? 'selected' : '' }}>
                            {{ $u->university_name }} ({{ $u->university_type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">
                    <i class="bi bi-building text-warning me-1"></i>الجامعة الثانية <span class="text-danger">*</span>
                </label>
                <select name="u2" class="form-select form-select-sm" required>
                    <option value="">اختر الجامعة الثانية...</option>
                    @foreach($universities as $u)
                        <option value="{{ $u->university_id }}" {{ request('u2') == $u->university_id ? 'selected' : '' }}>
                            {{ $u->university_name }} ({{ $u->university_type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">
                    <i class="bi bi-building text-info me-1"></i>الجامعة الثالثة <span class="text-muted fw-normal" style="font-size:0.75rem;">(اختياري)</span>
                </label>
                <div class="input-group input-group-sm">
                    <select name="u3" class="form-select form-select-sm">
                        <option value="">اختياري (إضافة جامعة)...</option>
                        @foreach($universities as $u)
                            <option value="{{ $u->university_id }}" {{ request('u3') == $u->university_id ? 'selected' : '' }}>
                                {{ $u->university_name }} ({{ $u->university_type }})
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('compare') }}" class="btn btn-outline-secondary px-3 fw-bold d-inline-flex align-items-center gap-1" title="إعادة ضبط حقول الجامعات">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>إعادة ضبط</span>
                    </a>
                </div>
            </div>

            {{-- خيارات عرض المقارنة بحسب وزر بدء المقارنة الكبير --}}
            <div class="col-12 mt-3 pt-2 border-top" id="compareScopeSelectorRow">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="small fw-bold text-secondary">
                            <i class="bi bi-funnel-fill text-primary me-1"></i>عرض المقارنة بحسب:
                        </span>
                        <input type="hidden" name="scope" id="formCompareScope" value="{{ request('scope', 'all') }}">
                        <div class="btn-group btn-group-sm" role="group" aria-label="نطاق المقارنة">
                            <button type="button" class="btn {{ request('scope', 'all') === 'all' ? 'btn-primary' : 'btn-outline-primary' }} fw-bold px-3" id="formScopeBtnAll" onclick="selectFormCompareScope('all')">
                                <i class="bi bi-ui-checks-grid me-1"></i>كافة المعلومات الشاملة
                            </button>
                            <button type="button" class="btn {{ request('scope') === 'specs' ? 'btn-primary' : 'btn-outline-primary' }} fw-bold px-3" id="formScopeBtnSpecs" onclick="selectFormCompareScope('specs')">
                                <i class="bi bi-mortarboard-fill me-1"></i>الكليات والتخصصات المتاحة
                            </button>
                        </div>
                    </div>

                    <div class="compare-action-box w-100 w-md-auto d-flex justify-content-center justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-start-compare-lg fw-bold shadow-sm" id="btnStartCompare">
                            <i class="bi bi-arrow-left-right me-2"></i>بدء المقارنة
                        </button>
                    </div>
                </div>

                {{-- علامة التحميل والمعالجة المضمنة في الصفحة (ليست نافذة منبثقة) --}}
                <div id="compareLoadingIndicator" class="mt-3" style="display: none;">
                    <div class="p-3 rounded-3 border bg-light text-center shadow-sm">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div class="spinner-border text-primary" style="width: 1.8rem; height: 1.8rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-navy" style="font-size:0.95rem;">جاري معالجة وتجهيز المقارنة الأكاديمية...</div>
                                <div class="text-muted small" style="font-size:0.78rem;">يتم الآن استخراج ومقارنة معايير القبول والرسوم والتخصصات للجامعات المختارة</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ====== معالجة واستخراج الجامعات المحددة ====== --}}
    @php
        $selected = [];
        foreach(['u1','u2','u3'] as $key) {
            if(request($key)) {
                $u = $universities->firstWhere('university_id', request($key));
                if($u && !in_array($u, $selected)) $selected[] = $u;
            }
        }
    @endphp

    @if(count($selected) >= 2)

    {{-- ====== حاوية نتائج وبيانات المقارنة مع المعرّف للسحب المباشر للشاشة ====== --}}
    <div id="comparisonResults" class="scroll-target-section">



    {{-- ====== حاوية العرض التبادلي (مقارنة الجامعات / مقارنة التخصصات) بدون انتقال أو قفز في الصفحة ====== --}}
    <div id="comparisonViewContainer">

        {{-- ====== العرض 1: جدول مقارنة الجامعات الشامل ====== --}}
        <div class="compare-table-wrap" id="compareUnivsTableView">
            <div class="table-responsive">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>
                                <span class="d-block fw-bold" style="font-size:0.95rem;">وجه المقارنة</span>
                                <span class="small text-muted fw-normal">معايير المفاضلة</span>
                            </th>
                            @foreach($selected as $su)
                            <th class="univ-col-header">
                                <div class="univ-emblem-box">
                                    <img src="{{ $su->logo_url }}" 
                                         alt="{{ $su->university_name }}" 
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'68\' height=\'68\'%3E%3Crect width=\'68\' height=\'68\' fill=\'%23ffffff\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%230b3d6b\' font-size=\'26\'%3E🏛%3C/text%3E%3C/svg%3E'">
                                </div>
                                <div class="univ-header-name">{{ $su->university_name }}</div>
                                <a href="{{ route('universities.show', $su->university_id) }}" class="small text-decoration-none text-primary fw-bold" style="font-size:0.78rem;">
                                    عرض صفحة الجامعة <i class="bi bi-arrow-left"></i>
                                </a>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        
                        {{-- 1. نوع الجامعة --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-building me-1 text-primary"></i>
                                <span>نوع الجامعة</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <span>جامعة {{ $su->university_type }}</span>
                            </td>
                            @endforeach
                        </tr>

                        {{-- 2. الموقع والعنوان التفصيلي --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                <span>الموقع والعنوان التفصيلي</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <div>{{ $su->address ? $su->address . ' — ' : '' }}صنعاء، {{ $su->district }}</div>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($su->university_name . ' ' . $su->address . ' صنعاء') }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="text-decoration-none text-muted small mt-1 d-inline-block">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>عرض على الخريطة
                                </a>
                            </td>
                            @endforeach
                        </tr>

                        {{-- 3. عدد الكليات المعتمدة --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-book-half me-1 text-primary"></i>
                                <span>عدد الكليات</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <span>{{ $su->colleges->count() }} كليات</span>
                            </td>
                            @endforeach
                        </tr>

                        {{-- 4. عدد التخصصات + أزرار في خلايا هذا الصف لعرض تخصصات كل جامعة --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-mortarboard-fill me-1 text-primary"></i>
                                <span>عدد التخصصات الأكاديمية</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <div class="mb-2">
                                    {{ $su->specializations->count() }} تخصصاً معتمداً
                                </div>
                                <button type="button" 
                                        class="btn btn-sm btn-primary fw-bold rounded-pill px-3 shadow-sm" 
                                        onclick="openUnivSpecsModal({{ $su->university_id }})">
                                    <i class="bi bi-list-columns-reverse me-1"></i>
                                    <span>عرض تخصصات الجامعة</span>
                                </button>
                            </td>
                            @endforeach
                        </tr>

                        {{-- 5. سنة التأسيس --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-calendar3 me-1 text-primary"></i>
                                <span>سنة التأسيس</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <span>{{ $su->year_establ ? $su->year_establ . ' م' : '—' }}</span>
                            </td>
                            @endforeach
                        </tr>

                        {{-- 6. الاعتماد الأكاديمي والترخيص --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-patch-check-fill me-1 text-success"></i>
                                <span>الاعتماد الأكاديمي</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <span>معتمدة ومسجلة رسمياً</span>
                            </td>
                            @endforeach
                        </tr>

                        {{-- 7. نطاق الرسوم الدراسية --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-cash-stack me-1 text-primary"></i>
                                <span>الرسوم الدراسية السنوية</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                @php
                                    $minFee = $su->specializations->min('tuition_fees');
                                    $maxFee = $su->specializations->max('tuition_fees');
                                @endphp
                                @if($minFee)
                                    <div>{{ number_format($minFee) }} - {{ number_format($maxFee) }} ر.ي</div>
                                    <span class="small text-muted">بحسب الكلية والتخصص</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- 8. هاتف التواصل وواتساب --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-telephone-fill me-1 text-primary"></i>
                                <span>هاتف التواصل وواتساب</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                @if($su->contact_phone || $su->phone)
                                    @php $ph = $su->contact_phone ?? $su->phone; @endphp
                                    <div dir="ltr" class="mb-1">{{ $ph }}</div>
                                    <a href="https://wa.me/967{{ preg_replace('/[^0-9]/', '', $ph) }}" target="_blank" class="small text-muted text-decoration-none">
                                        <i class="bi bi-whatsapp me-1 text-success"></i>مراسلة واتساب
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- 9. الموقع الإلكتروني الرسمي --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-globe2 me-1 text-primary"></i>
                                <span>الموقع الإلكتروني</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                @if($su->website)
                                    <a href="{{ $su->website }}" target="_blank" class="small text-decoration-none text-primary">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>زيارة الموقع الرسمي
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- 10. روابط الإجراء والتقديم المباشر مع زر بلون غير مزعج --}}
                        <tr class="row-cmp-item">
                            <td>
                                <i class="bi bi-send-check-fill me-1 text-primary"></i>
                                <span>إجراءات التسجيل</span>
                            </td>
                            @foreach($selected as $su)
                            <td>
                                <div class="d-flex flex-column gap-2 align-items-center">
                                    <a href="{{ route('student.apply') }}?university_id={{ $su->university_id }}" class="btn btn-apply-pleasant btn-sm fw-bold px-3">
                                        <i class="bi bi-send me-1"></i>التسجيل الآن
                                    </a>
                                    <a href="{{ route('universities.show', $su->university_id) }}" class="btn btn-outline-secondary btn-sm px-3">
                                        صفحة الجامعة
                                    </a>
                                </div>
                            </td>
                            @endforeach
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- ====== العرض 2: أداة مقارنة تخصصين متقابلين بين الجامعتين (تظهر مكان جدول الجامعات) ====== --}}
        @php
            $u1 = $selected[0];
            $u2 = $selected[1];

            // تجهيز بيانات التخصصات للمقارنة التفاعلية
            $specsJsonMap = [];
            foreach([$u1, $u2] as $univItem) {
                $specsJsonMap[$univItem->university_id] = $univItem->colleges->flatMap(function($col) use ($univItem) {
                    return $col->specializations->map(function($spec) use ($col, $univItem) {
                        $adm = $spec->admissionData;
                        return [
                            'id' => $spec->specialization_id,
                            'name' => $spec->specialization_name,
                            'college' => $col->college_name,
                            'univ_id' => $univItem->university_id,
                            'univ_name' => $univItem->university_name,
                            'fees' => (float)$spec->tuition_fees,
                            'fees_formatted' => number_format($spec->tuition_fees) . ' ر.ي',
                            'min_avg' => $adm ? ($adm->minimum_average . '%') : '65%',
                            'min_avg_num' => $adm ? (float)$adm->minimum_average : 65,
                            'duration' => str_contains($spec->specialization_name, 'طب') ? '5 سنوات' : '4 سنوات',
                            'language' => (str_contains($spec->specialization_name, 'إنجليز') ? 'الإنجليزية' : (str_contains($spec->specialization_name, 'طب') || str_contains($spec->specialization_name, 'هندس') || str_contains($spec->specialization_name, 'حاسوب') ? 'الإنجليزية / العربية' : 'العربية')),
                            'last_year' => $adm && $adm->secondary_last_year ? $adm->secondary_last_year : '2020 فما بعد',
                            'conditions' => $adm && $adm->admission_data ? $adm->admission_data : 'استيفاء الحد الأدنى لمعدل الثانوية العامة وتقديم أصل استمارة الثانوية.',
                            'docs' => $adm && !empty($adm->required_documents) ? count($adm->required_documents) . ' وثائق رسمية معتمدة' : 'استمارة الثانوية، صورة البطاقة، 4 صور شخصية',
                        ];
                    });
                })->values()->all();
            }
        @endphp

        <div class="spec-compare-section" id="specComparisonToolSection" style="display: none;">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold mb-1 text-dark" style="font-size:1.15rem;">
                        <i class="bi bi-columns-gap text-primary me-2"></i>مقارنة تخصصين متقابلين بين الجامعتين
                    </h4>
                    <p class="text-muted small mb-0">اختر تخصصاً من كل جامعة لمقارنة الرسوم ومعدل القبول ولغة التدريس والشروط مباشرة</p>
                </div>
            </div>

            {{-- قوائم الاختيار للتخصصين --}}
            <div class="row g-2 g-md-3 align-items-center mb-3">
                {{-- تخصص الجامعة الأولى --}}
                <div class="col-md-5">
                    <div class="spec-select-box">
                        <label class="form-label small fw-bold text-primary mb-1">
                            <i class="bi bi-building me-1"></i>تخصص من: {{ $u1->university_name }}
                        </label>
                        <select class="form-select form-select-sm" id="specSelectU1" onchange="renderSpecializationComparison()">
                            @foreach($u1->colleges as $col)
                                <optgroup label="{{ $col->college_name }}">
                                    @foreach($col->specializations as $s)
                                    <option value="{{ $s->specialization_id }}">{{ $s->specialization_name }} ({{ number_format($s->tuition_fees) }} ر.ي)</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- علامة VS في المنتصف --}}
                <div class="col-md-2 text-center py-1">
                    <div class="spec-vs-badge">VS</div>
                </div>

                {{-- تخصص الجامعة الثانية --}}
                <div class="col-md-5">
                    <div class="spec-select-box">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="bi bi-building me-1"></i>تخصص من: {{ $u2->university_name }}
                        </label>
                        <select class="form-select form-select-sm" id="specSelectU2" onchange="renderSpecializationComparison()">
                            @foreach($u2->colleges as $col)
                                <optgroup label="{{ $col->college_name }}">
                                    @foreach($col->specializations as $s)
                                    <option value="{{ $s->specialization_id }}">{{ $s->specialization_name }} ({{ number_format($s->tuition_fees) }} ر.ي)</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- كرت نتائج المقارنة المباشرة للتخصصين --}}
            <div id="specComparisonResultContainer"></div>
        </div>

    </div>

    @else
    {{-- ====== حالة عدم اختيار جامعتين ====== --}}
    <div class="text-center bg-white rounded-4 border p-5 shadow-sm">
        <div class="display-3 text-muted mb-3">
            <i class="bi bi-arrow-left-right text-primary"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">اختر جامعتين على الأقل للمقارنة</h4>
        <p class="text-muted small mb-4">حدد الجامعات من النموذج بالأعلى لمشاهدة المقارنة الأكاديمية الشاملة ومقارنة التخصصات مباشرة</p>

        <a href="{{ route('universities.index') }}" class="btn btn-outline-primary fw-bold px-4">
            <i class="bi bi-buildings me-1"></i>تصفح دليل الجامعات
        </a>
    </div>
    @endif

</div>

{{-- ====== نافذة منبثقة تفاعلية لعرض تخصصات الجامعة كاملة (Univ Specs Modal) ====== --}}
<div class="modal fade" id="univSpecsModal" tabindex="-1" aria-labelledby="univSpecsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom py-3">
                <h5 class="modal-title fw-bold text-primary d-flex align-items-center gap-2" id="univSpecsModalLabel">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span id="modalUnivNameText">تخصصات الجامعة</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body p-3">
                <div id="modalUnivSpecsListContainer"></div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">إغلاق</button>
                <a href="#" id="modalUnivPageLink" class="btn btn-primary btn-sm fw-bold">الانتقال لصفحة الجامعة</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // بيانات التخصصات المعالجة للمقارنة التفاعلية
    window.univsSpecsData = @json($specsJsonMap ?? []);
    window.allUnivsData = @json($allUnivsData ?? []);

    // تبديل نطاق المقارنة ونقل الشاشة بسلاسة لبيانات المقارنة
    function setCompareScope(scope) {
        const isAll = (scope === 'all');
        const btnAll = document.getElementById('formScopeBtnAll');
        const btnSpecs = document.getElementById('formScopeBtnSpecs');
        if (btnAll && btnSpecs) {
            btnAll.classList.toggle('btn-primary', isAll);
            btnAll.classList.toggle('btn-outline-primary', !isAll);
            btnSpecs.classList.toggle('btn-primary', !isAll);
            btnSpecs.classList.toggle('btn-outline-primary', isAll);
        }

        const univsTable = document.getElementById('compareUnivsTableView');
        const specsSection = document.getElementById('specComparisonToolSection');

        if (isAll) {
            if (specsSection) specsSection.style.display = 'none';
            if (univsTable) {
                univsTable.style.display = 'block';
                if (window.hasScrolledInitial) {
                    univsTable.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        } else {
            if (univsTable) univsTable.style.display = 'none';
            if (specsSection) {
                specsSection.style.display = 'block';
                renderSpecializationComparison();
                // نقل الشاشة بسلاسة إلى مكان عرض جدول وتخصصات المقارنة
                setTimeout(() => {
                    specsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 80);
            }
        }
    }

    // فتح نافذة عرض تخصصات الجامعة عند النقر على الزر في خلية "عدد التخصصات"
    function openUnivSpecsModal(univId) {
        const univ = window.allUnivsData[univId];
        if (!univ) return;

        document.getElementById('modalUnivNameText').textContent = 'دليل تخصصات ' + univ.name;
        document.getElementById('modalUnivPageLink').href = '/universities/' + univId;

        let html = '';
        if (univ.colleges && univ.colleges.length > 0) {
            univ.colleges.forEach(col => {
                html += `
                    <div class="card border rounded-3 mb-3">
                        <div class="card-header bg-light py-2 px-3 fw-bold text-dark d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-book text-primary me-2"></i>${col.name}</span>
                            <span class="badge bg-secondary-subtle text-dark border">${col.specs.length} تخصصات</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>اسم التخصص</th>
                                        <th class="text-center">السنوات</th>
                                        <th class="text-center">الحد الأدنى للقبول</th>
                                        <th class="text-center">الرسوم السنوية</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                col.specs.forEach(s => {
                    html += `
                        <tr class="small">
                            <td class="text-dark">${s.name}</td>
                            <td class="text-center">${s.years} سنوات</td>
                            <td class="text-center text-primary">${s.min_avg}%</td>
                            <td class="text-center">${s.fees} ر.ي</td>
                        </tr>
                    `;
                });
                html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            });
        } else {
            html = '<div class="alert alert-info small">لا توجد بيانات تخصصات مدخلة لهذه الجامعة حالياً.</div>';
        }

        document.getElementById('modalUnivSpecsListContainer').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('univSpecsModal'));
        modal.show();
    }

    // تحديث ومقارنة التخصصين المختارين تفاعلياً جنباً إلى جنب
    function renderSpecializationComparison() {
        const u1Id = {{ $u1->university_id ?? 'null' }};
        const u2Id = {{ $u2->university_id ?? 'null' }};
        if (!u1Id || !u2Id) return;

        const spec1Select = document.getElementById('specSelectU1');
        const spec2Select = document.getElementById('specSelectU2');
        if (!spec1Select || !spec2Select) return;

        const spec1Id = spec1Select.value;
        const spec2Id = spec2Select.value;

        const spec1 = (window.univsSpecsData[u1Id] || []).find(s => s.id == spec1Id);
        const spec2 = (window.univsSpecsData[u2Id] || []).find(s => s.id == spec2Id);

        if (!spec1 || !spec2) return;

        // حساب فارق الرسوم الدراسية
        const feeDiff = Math.abs(spec1.fees - spec2.fees);
        let feeComparisonNote = '';
        if (spec1.fees < spec2.fees) {
            feeComparisonNote = `<span class="small text-muted">تخصص ${spec1.univ_name} أقل تكلفة بفارق ${Number(feeDiff).toLocaleString()} ر.ي</span>`;
        } else if (spec2.fees < spec1.fees) {
            feeComparisonNote = `<span class="small text-muted">تخصص ${spec2.univ_name} أقل تكلفة بفارق ${Number(feeDiff).toLocaleString()} ر.ي</span>`;
        } else {
            feeComparisonNote = `<span class="small text-muted">الرسوم الدراسية متطابقة</span>`;
        }

        const container = document.getElementById('specComparisonResultContainer');
        container.innerHTML = `
            <div class="card border rounded-3 overflow-hidden mb-2">
                <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="fw-bold small text-dark"><i class="bi bi-arrow-left-right text-primary me-1"></i>المفاضلة المباشرة بين التخصصين</span>
                    <div>${feeComparisonNote}</div>
                </div>
                <div class="table-responsive">
                    <table class="table spec-cmp-table mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th style="width: 25%;">معيار التخصص</th>
                                <th style="width: 37.5%; text-align: center; color: var(--cmp-navy);">${spec1.univ_name}</th>
                                <th style="width: 37.5%; text-align: center; color: #475569;">${spec2.univ_name}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-secondary small"><i class="bi bi-mortarboard me-1 text-primary"></i>اسم التخصص والكلية</td>
                                <td class="text-center">
                                    <div class="fw-bold text-dark">${spec1.name}</div>
                                    <span class="small text-muted">${spec1.college}</span>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold text-dark">${spec2.name}</div>
                                    <span class="small text-muted">${spec2.college}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-secondary small"><i class="bi bi-cash me-1 text-primary"></i>الرسوم الدراسية السنوية</td>
                                <td class="text-center font-monospace">${spec1.fees_formatted}</td>
                                <td class="text-center font-monospace">${spec2.fees_formatted}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary small"><i class="bi bi-award me-1 text-primary"></i>الحد الأدنى للقبول</td>
                                <td class="text-center">${spec1.min_avg}</td>
                                <td class="text-center">${spec2.min_avg}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary small"><i class="bi bi-clock-history me-1 text-primary"></i>مدة الدراسة</td>
                                <td class="text-center">${spec1.duration}</td>
                                <td class="text-center">${spec2.duration}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary small"><i class="bi bi-translate me-1 text-primary"></i>لغة الدراسة</td>
                                <td class="text-center">${spec1.language}</td>
                                <td class="text-center">${spec2.language}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary small"><i class="bi bi-calendar-check me-1 text-primary"></i>أحدث سنة ثانوية مقبولة</td>
                                <td class="text-center small">${spec1.last_year}</td>
                                <td class="text-center small">${spec2.last_year}</td>
                            </tr>

                            <tr>
                                <td class="text-secondary small"><i class="bi bi-send me-1 text-primary"></i>التسجيل المباشر</td>
                                <td class="text-center">
                                    <a href="/student/apply?university_id=${spec1.univ_id}&major=${encodeURIComponent(spec1.name)}" class="btn btn-apply-pleasant btn-sm fw-bold px-3">
                                        التسجيل الآن
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="/student/apply?university_id=${spec2.univ_id}&major=${encodeURIComponent(spec2.name)}" class="btn btn-apply-pleasant btn-sm fw-bold px-3">
                                        التسجيل الآن
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    // تشغيل تهيئة مقارنة التخصصات عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('specSelectU1')) {
            renderSpecializationComparison();
        }
    });

    // دالة اختيار نطاق المقارنة في النموذج قبل بدء المقارنة
    function selectFormCompareScope(scope) {
        const input = document.getElementById('formCompareScope');
        if (input) input.value = scope;

        const btnAll = document.getElementById('formScopeBtnAll');
        const btnSpecs = document.getElementById('formScopeBtnSpecs');
        if (btnAll && btnSpecs) {
            if (scope === 'all') {
                btnAll.classList.add('btn-primary');
                btnAll.classList.remove('btn-outline-primary');
                btnSpecs.classList.remove('btn-primary');
                btnSpecs.classList.add('btn-outline-primary');
            } else {
                btnSpecs.classList.add('btn-primary');
                btnSpecs.classList.remove('btn-outline-primary');
                btnAll.classList.remove('btn-primary');
                btnAll.classList.add('btn-outline-primary');
            }
        }

        // إذا كانت نتائج المقارنة ظاهرة بالفعل في الصفحة، يتم التبديل فورياً
        if (typeof setCompareScope === 'function') {
            setCompareScope(scope);
        }
    }

    // تمييز خيارات المقارنة عند تحديد الجامعات
    function checkUniversitiesSelected() {
        const u1 = document.querySelector('select[name="u1"]')?.value;
        const u2 = document.querySelector('select[name="u2"]')?.value;
        const row = document.getElementById('compareScopeSelectorRow');
        if (row) {
            if (u1 && u2) {
                row.classList.add('scope-ready-highlight');
            } else {
                row.classList.remove('scope-ready-highlight');
            }
        }
    }

    // سحب الشاشة بسلاسة باتجاه بيانات المقارنة فور التحميل
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select[name="u1"], select[name="u2"], select[name="u3"]').forEach(sel => {
            sel.addEventListener('change', checkUniversitiesSelected);
        });
        checkUniversitiesSelected();

        // إظهار علامة التحميل المضمنة في الصفحة عند الضغط على زر بدء المقارنة
        const cmpForm = document.querySelector('form[action*="compare"]');
        if (cmpForm) {
            cmpForm.addEventListener('submit', function() {
                const btn = document.getElementById('btnStartCompare');
                if (btn) {
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span>جاري المعالجة...</span>`;
                    btn.disabled = true;
                    btn.style.opacity = '0.85';
                }
                const loader = document.getElementById('compareLoadingIndicator');
                if (loader) {
                    loader.style.display = 'block';
                    loader.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }


        const scopeParam = '{{ request('scope', 'all') }}';
        if (typeof setCompareScope === 'function') {
            setCompareScope(scopeParam);
        }

        @if(count($selected) >= 2)
        // سحب الشاشة إلى بيانات المقارنة حتى يرى المستخدم المعلومات فوراً
        setTimeout(function() {
            const resultsSection = document.getElementById('comparisonResults');
            if (resultsSection) {
                resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 150);
        @endif
    });

</script>
@endsection
