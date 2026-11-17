@extends('dashboards.admin.layout')

@section('page_title', 'إعدادات النظام والمنصة')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>إعدادات النظام</strong>
@endsection

@section('content')

<style>
    /* =========================================================
       EduYemen System Settings Page Styles
       ========================================================= */
    .settings-hero {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 55%, #13528c 100%);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 10px 30px rgba(7, 43, 79, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .settings-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.25) 0%, transparent 70%);
        pointer-events: none;
    }
    .settings-hero::after {
        content: '\f1de';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        bottom: -25px;
        left: 20px;
        font-size: 140px;
        color: rgba(255, 255, 255, 0.04);
        pointer-events: none;
        line-height: 1;
    }
    .settings-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .settings-hero-info h1 {
        font-size: 20px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .settings-hero-info h1 i {
        color: var(--accent);
    }
    .settings-hero-info p {
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.82);
        max-width: 620px;
        line-height: 1.6;
    }
    .settings-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-hero-save {
        background: linear-gradient(135deg, #c59b27 0%, #e0b438 100%);
        color: #072b4f;
        font-weight: 800;
        font-size: 13px;
        border: none;
        padding: 9px 20px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(197, 155, 39, 0.35);
        transition: all 0.25s ease;
    }
    .btn-hero-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(197, 155, 39, 0.5);
        color: #072b4f;
    }
    .btn-hero-reset {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-size: 12.5px;
        font-weight: 700;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 9px 16px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }
    .btn-hero-reset:hover {
        background: rgba(220, 38, 38, 0.2);
        border-color: rgba(220, 38, 38, 0.4);
        color: #fca5a5;
    }

    /* Search & Nav Bar */
    .settings-toolbar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        box-shadow: var(--shadow-sm);
    }
    .settings-search-box {
        position: relative;
        flex: 1 1 240px;
        max-width: 360px;
    }
    .settings-search-box i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-light);
        font-size: 13px;
        pointer-events: none;
    }
    .settings-search-input {
        width: 100%;
        padding: 8px 36px 8px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 12.5px;
        background: var(--surface-alt);
        color: var(--ink);
        transition: all 0.2s ease;
    }
    .settings-search-input:focus {
        outline: none;
        background: #fff;
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.1);
    }

    /* Category Nav Pills */
    .settings-nav-tabs {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
        flex: 2 1 500px;
    }
    .settings-nav-tabs::-webkit-scrollbar {
        height: 4px;
    }
    .settings-nav-tabs::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 4px;
    }
    .nav-tab-btn {
        background: var(--surface-alt);
        border: 1px solid var(--border-light);
        color: var(--ink-muted);
        font-size: 11.5px;
        font-weight: 700;
        padding: 7px 13px;
        border-radius: var(--radius-sm);
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .nav-tab-btn:hover {
        background: var(--primary-subtle);
        color: var(--primary);
        border-color: var(--primary-light);
    }
    .nav-tab-btn.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.25);
    }
    .nav-tab-btn.active i {
        color: var(--accent-light);
    }

    /* Settings Sections */
    .settings-group-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .settings-group-header {
        background: linear-gradient(to left, var(--surface) 0%, var(--surface-alt) 100%);
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .group-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .group-icon-box {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        background: var(--primary-subtle);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .group-header-title h3 {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 2px;
    }
    .group-header-title p {
        font-size: 11.5px;
        color: var(--ink-muted);
    }
    .group-count-badge {
        font-size: 11px;
        font-weight: 700;
        background: #f1f5f9;
        color: var(--ink-muted);
        padding: 3px 9px;
        border-radius: 20px;
        border: 1px solid var(--border);
    }

    /* Setting Rows */
    .setting-item-row {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        transition: background 0.15s ease;
    }
    .setting-item-row:last-child {
        border-bottom: none;
    }
    .setting-item-row:hover {
        background: rgba(11, 61, 107, 0.015);
    }
    .setting-item-info {
        flex: 1;
        min-width: 200px;
    }
    .setting-item-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .setting-item-desc {
        font-size: 11.5px;
        color: var(--ink-muted);
        line-height: 1.45;
    }
    .setting-item-control {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    /* iOS-Style Toggle Switch */
    .edu-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin: 0;
    }
    .edu-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .edu-switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 24px;
    }
    .edu-switch-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }
    .edu-switch input:checked + .edu-switch-slider {
        background-color: #0b3d6b;
    }
    .edu-switch input:focus + .edu-switch-slider {
        box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.18);
    }
    .edu-switch input:checked + .edu-switch-slider:before {
        transform: translateX(20px);
    }

    /* Stepper & Units Control */
    .edu-input-unit {
        display: inline-flex;
        align-items: center;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        overflow: hidden;
    }
    .edu-input-unit input {
        width: 75px;
        padding: 6px 10px;
        border: none;
        background: transparent;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        color: var(--primary-dark);
    }
    .edu-input-unit input:focus {
        outline: none;
        background: #fff;
    }
    .edu-input-unit .unit-tag {
        background: var(--border-light);
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 700;
        color: var(--ink-muted);
        border-right: 1px solid var(--border);
        white-space: nowrap;
    }

    /* Custom Select */
    .edu-select {
        padding: 6px 28px 6px 10px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--surface-alt);
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ink);
        min-width: 150px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .edu-select:focus {
        outline: none;
        border-color: var(--primary-light);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.1);
    }

    /* Floating Save Bar */
    .floating-save-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #072b4f;
        color: #fff;
        padding: 12px 24px;
        border-radius: 40px;
        box-shadow: 0 10px 30px rgba(7, 43, 79, 0.35);
        display: flex;
        align-items: center;
        gap: 18px;
        z-index: 1000;
        opacity: 0;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .floating-save-bar.active {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .floating-save-text {
        font-size: 12.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .floating-save-dot {
        width: 8px;
        height: 8px;
        background: var(--accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--accent);
        animation: pulseDot 1.5s infinite;
    }
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.8); }
    }

    /* Modal Reset */
    .edu-modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1100;
        padding: 16px;
    }
    .edu-modal-backdrop.open {
        display: flex;
    }
    .edu-modal-box {
        background: #fff;
        border-radius: var(--radius-md);
        max-width: 440px;
        width: 100%;
        padding: 24px;
        box-shadow: var(--shadow-lg);
        text-align: center;
        animation: modalZoom .25s ease-out;
    }
    @keyframes modalZoom {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @media (max-width: 768px) {
        .settings-hero {
            padding: 18px 20px;
        }
        .settings-hero-content {
            flex-direction: column;
            align-items: stretch;
        }
        .settings-hero-actions {
            justify-content: flex-start;
        }
        .setting-item-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .setting-item-control {
            width: 100%;
            justify-content: flex-start;
        }
        .floating-save-bar {
            width: 90%;
            justify-content: space-between;
            padding: 10px 18px;
            bottom: 16px;
        }
    }
</style>

<!-- ================= Header / Hero ================= -->
<div class="settings-hero">
    <div class="settings-hero-content">
        <div class="settings-hero-info">
            <h1>
                <i class="fa-solid fa-sliders"></i>
                إعدادات المنصة والنظام
            </h1>
            <p>
                التحكم المركزي في معايير قبول الجامعات، شروط التقديم والتسجيل، برامج الإحالات، نشر الإعلانات، والسياسات الأمنية للمنصة.
            </p>
        </div>
        <div class="settings-hero-actions">
            <button type="button" class="btn-hero-save" onclick="saveAllPlatformSettings()" id="heroSaveBtn">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                حفظ التعديلات
            </button>
            <button type="button" class="btn-hero-reset" onclick="openResetModal()">
                <i class="fa-solid fa-rotate-left"></i>
                استعادة الافتراضي
            </button>
        </div>
    </div>
</div>

<!-- ================= Search & Category Navigation ================= -->
<div class="settings-toolbar">
    <div class="settings-search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="settingsSearchInput" class="settings-search-input" placeholder="بحث سريع في الإعدادات...">
    </div>

    <div class="settings-nav-tabs" id="settingsNavTabs">
        <button type="button" class="nav-tab-btn active" data-target="all">
            <i class="fa-solid fa-layer-group"></i> كافة الإعدادات
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-university">
            <i class="fa-solid fa-building-columns"></i> الجامعات
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-academic">
            <i class="fa-solid fa-graduation-cap"></i> المحتوى الأكاديمي
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-student">
            <i class="fa-solid fa-user-graduate"></i> الطلاب والتسجيل
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-referrals">
            <i class="fa-solid fa-diagram-project"></i> الإحالات
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-ads">
            <i class="fa-solid fa-rectangle-ad"></i> الإعلانات
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-finance">
            <i class="fa-solid fa-coins"></i> الرسوم والمالية
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-notifications">
            <i class="fa-solid fa-bell"></i> الإشعارات
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-security">
            <i class="fa-solid fa-shield-halved"></i> الأمان والنظام
        </button>
        <button type="button" class="nav-tab-btn" data-target="sec-account">
            <i class="fa-solid fa-user-gear"></i> حساب المدير
        </button>
    </div>
</div>

<form id="platformSettingsForm">
    @csrf

    <!-- ================= 1. الجامعات ================= -->
    <div class="settings-group-card" id="sec-university">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <h3>إعدادات الجامعات والاعتماد</h3>
                    <p>معايير التحقق، تفعيل الحسابات الجامعية، والإشراف الإداري</p>
                </div>
            </div>
            <span class="group-count-badge">5 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الموافقة المسبقة على الجامعات الجديدة</div>
                <div class="setting-item-desc">اشتراط مراجعة واعتماد مدير النظام قبل تفعيل أي جامعة في المنصة</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="university_manual_approval" value="1" {{ ($settings['university_manual_approval'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">مراجعة تعديلات بيانات الجامعات</div>
                <div class="setting-item-desc">إخضاع تعديلات الملف التعريفي والبيانات الأكاديمية للموافقة الإدارية قبل النشر</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="university_edit_review" value="1" {{ ($settings['university_edit_review'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">التحقق من اكتمال بيانات الجامعة</div>
                <div class="setting-item-desc">إلزامية استكمال الملف التعريفي والترخيص قبل إتاحة الجامعة للطلاب</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="university_data_validation" value="1" {{ ($settings['university_data_validation'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">تعليق خدمات الجامعات الموقوفة</div>
                <div class="setting-item-desc">تجميد التقديم وإخفاء الخدمات مباشرة عند إيقاف الجامعة إدارياً</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="university_suspend_services" value="1" {{ ($settings['university_suspend_services'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">المهلة الإدارية لمراجعة طلب الاعتماد</div>
                <div class="setting-item-desc">المدة الزمنية المستهدفة للرد على طلبات الانضمام والاعتماد المرفوعة</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="university_review_days" min="1" max="30" value="{{ $settings['university_review_days'] ?? '3' }}">
                    <span class="unit-tag">أيام</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= 2. المحتوى الأكاديمي ================= -->
    <div class="settings-group-card" id="sec-academic">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h3>إعدادات المحتوى الأكاديمي والتخصصات</h3>
                    <p>ضوابط عرض التخصصات، شروط القبول، وترتيب نتائج البحث</p>
                </div>
            </div>
            <span class="group-count-badge">4 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">حجب التخصصات غير المكتملة</div>
                <div class="setting-item-desc">إخفاء التخصصات التي تنقصها الرسوم الدراسية أو معايير القبول من الظهور للطلاب</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="academic_hide_incomplete" value="1" {{ ($settings['academic_hide_incomplete'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">مراجعة تعديلات شروط القبول</div>
                <div class="setting-item-desc">إشعار الإدارة فور تعديل الجامعة لنسب القبول أو المتطلبات للمراجعة والتدقيق</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="academic_admission_review" value="1" {{ ($settings['academic_admission_review'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">كثافة عرض التخصصات في الصفحة</div>
                <div class="setting-item-desc">العدد الافتراضي للتخصصات الأكاديمية المعروضة لكل صفحة بالدليل</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="academic_per_page" min="6" max="48" step="3" value="{{ $settings['academic_per_page'] ?? '12' }}">
                    <span class="unit-tag">تخصص/صفحة</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الترتيب الافتراضي للتخصصات</div>
                <div class="setting-item-desc">آلية فرز التخصصات في نتائج البحث ومحرك الاستكشاف الأكاديمي</div>
            </div>
            <div class="setting-item-control">
                <select name="academic_default_sort" class="edu-select">
                    <option value="latest" {{ ($settings['academic_default_sort'] ?? 'latest') == 'latest' ? 'selected' : '' }}>الأحدث إضافة</option>
                    <option value="price_asc" {{ ($settings['academic_default_sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>الأقل رسوماً</option>
                    <option value="rating_desc" {{ ($settings['academic_default_sort'] ?? '') == 'rating_desc' ? 'selected' : '' }}>الأعلى تقييماً</option>
                    <option value="popular" {{ ($settings['academic_default_sort'] ?? '') == 'popular' ? 'selected' : '' }}>الأكثر طلباً</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= 3. الطلاب والتسجيل ================= -->
    <div class="settings-group-card" id="sec-student">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <h3>إعدادات الطلاب والتسجيل الأكاديمي</h3>
                    <p>سقوف التقديم، اشتراطات الوثائق، وأدوات مقارنة الجامعات</p>
                </div>
            </div>
            <span class="group-count-badge">6 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">إتاحة التقديم المتعدد للطلاب</div>
                <div class="setting-item-desc">السماح للطالب بتقديم طلبات قبول في أكثر من جامعة بالتوازي</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="student_multiple_applications" value="1" {{ ($settings['student_multiple_applications'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">إلزامية رفع الوثائق عند التقديم</div>
                <div class="setting-item-desc">اشتراط إرفاق الشهادة الثانوية والمستندات قبل اعتماد إرسال طلب القبول</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="student_require_documents" value="1" {{ ($settings['student_require_documents'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الحد الأقصى للطلبات النشطة للطالب</div>
                <div class="setting-item-desc">أقصى عدد للطلبات قيد المراجعة المسموح بها للطالب في الوقت ذاته</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="student_max_active_apps" min="1" max="10" value="{{ $settings['student_max_active_apps'] ?? '3' }}">
                    <span class="unit-tag">طلبات نشطة</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الحد الأقصى لحجم المرفقات</div>
                <div class="setting-item-desc">سقف حجم الملف أو الصورة لكل وثيقة يرفعها الطالب</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="student_max_upload_size" min="1" max="25" value="{{ $settings['student_max_upload_size'] ?? '5' }}">
                    <span class="unit-tag">ميجابايت (MB)</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">صيغ الملفات المعتمدة للمرفقات</div>
                <div class="setting-item-desc">الامتدادات المصرح بها في نماذج التقديم ورفع السندات</div>
            </div>
            <div class="setting-item-control">
                <select name="student_allowed_file_types" class="edu-select">
                    <option value="pdf,jpg,jpeg,png" {{ ($settings['student_allowed_file_types'] ?? 'pdf,jpg,jpeg,png') == 'pdf,jpg,jpeg,png' ? 'selected' : '' }}>PDF, JPG, PNG (الافتراضي)</option>
                    <option value="pdf,jpg,png" {{ ($settings['student_allowed_file_types'] ?? '') == 'pdf,jpg,png' ? 'selected' : '' }}>PDF, JPG, PNG فقط</option>
                    <option value="pdf_only" {{ ($settings['student_allowed_file_types'] ?? '') == 'pdf_only' ? 'selected' : '' }}>مستندات PDF فقط</option>
                </select>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">تفعيل أداة مقارنة الجامعات والتخصصات</div>
                <div class="setting-item-desc">تمكين الطلاب من المقارنة المباشرة بين البرامج الأكاديمية والرسوم</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="student_comparison_tool" value="1" {{ ($settings['student_comparison_tool'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- ================= 4. إعدادات الإحالات ================= -->
    <div class="settings-group-card" id="sec-referrals">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
                <div>
                    <h3>إعدادات برامج الإحالات والتسويق</h3>
                    <p>أكواد الترويج، التحقق من السندات، وتتبع عمولات المسوقين</p>
                </div>
            </div>
            <span class="group-count-badge">5 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">توليد أكواد الإحالة تلقائياً</div>
                <div class="setting-item-desc">إنشاء كود إحالة فوري وفريد عند تسجيل الطالب أو المسوق المعتمد</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="referral_auto_code" value="1" {{ ($settings['referral_auto_code'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">اشتراط اعتماد سند الإحالة قبل التأكيد</div>
                <div class="setting-item-desc">مراجعة سند سداد الطالب المسجل عبر كود إحالة قبل صرف أي عمولة للمسوق</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="referral_require_proof" value="1" {{ ($settings['referral_require_proof'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">تفعيل نظام التتبع الذكي للإحالات</div>
                <div class="setting-item-desc">تتبع مسار روابط الإحالة وحفظ الكود بجلسة الطالب حتى إتمام التسجيل</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="referral_tracking" value="1" {{ ($settings['referral_tracking'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">مهلة تقديم سند التسجيل للعمولة</div>
                <div class="setting-item-desc">الفترة الزمنية القصوى المتاحة لرفع إثبات القيد لاحتساب مكافأة الإحالة</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="referral_proof_window_days" min="1" max="60" value="{{ $settings['referral_proof_window_days'] ?? '7' }}">
                    <span class="unit-tag">أيام</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">إشعار الجامعة بالمسجلين عبر كود</div>
                <div class="setting-item-desc">إرسال إشعار فوري للجامعة عند تقدم طالب بواسطة كود تسويقي معتمد</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="referral_notify_university" value="1" {{ ($settings['referral_notify_university'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- ================= 5. الإعلانات والنشر ================= -->
    <div class="settings-group-card" id="sec-ads">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-rectangle-ad"></i>
                </div>
                <div>
                    <h3>إعدادات الإعلانات والحملات الترويجية</h3>
                    <p>اعتماد البنرات، أوقات العرض، الأسعار الافتراضية، والوسائط</p>
                </div>
            </div>
            <span class="group-count-badge">6 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الموافقة المسبقة على الإعلانات</div>
                <div class="setting-item-desc">اشتراط تدقيق وموافقة الإدارة قبل نشر أي بنر أو حملة ترويجية بالمنصة</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="ads_manual_approval" value="1" {{ ($settings['ads_manual_approval'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">حصر الإعلانات على الجامعات المفعلة فقط</div>
                <div class="setting-item-desc">منع الجامعات المعلقة أو تحت المراجعة من إطلاق أي إعلان أو بنر ترويجي</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="ads_active_universities_only" value="1" {{ ($settings['ads_active_universities_only'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الأرشفة التلقائية بعد انتهاء الحملة</div>
                <div class="setting-item-desc">حجب الإعلانات المنتهية تاريخياً وإزالتها من الواجهات الرئيسية بشكل تلقائي</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="ads_auto_hide_expired" value="1" {{ ($settings['ads_auto_hide_expired'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">المدة الافتراضية لعرض الإعلان</div>
                <div class="setting-item-desc">عدد أيام الحملة الافتراضي عند إنشاء إعلان جديد في لوحة التحكم</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="ads_default_duration_days" min="1" max="365" value="{{ $settings['ads_default_duration_days'] ?? '30' }}">
                    <span class="unit-tag">يوماً</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">السعر الافتراضي للإعلان الترويجي</div>
                <div class="setting-item-desc">التكلفة المبدئية المقترحة لحجز البنر الإعلاني خلال مدة العرض القياسية</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="ads_default_price" min="0" step="5000" value="{{ $settings['ads_default_price'] ?? '50000' }}">
                    <span class="unit-tag">ر.ي</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">وسائط الإعلانات المصرح بها</div>
                <div class="setting-item-desc">صيغ وتنسيقات الملفات المسموح استخدامها في تصاميم البنرات والحملات</div>
            </div>
            <div class="setting-item-control">
                <select name="ads_allowed_media" class="edu-select">
                    <option value="images_gifs" {{ ($settings['ads_allowed_media'] ?? 'images_gifs') == 'images_gifs' ? 'selected' : '' }}>صور ثابتة ومتحركة (JPG/PNG/GIF)</option>
                    <option value="images_only" {{ ($settings['ads_allowed_media'] ?? '') == 'images_only' ? 'selected' : '' }}>صور ثابتة فقط (JPG/PNG)</option>
                    <option value="all_media" {{ ($settings['ads_allowed_media'] ?? '') == 'all_media' ? 'selected' : '' }}>شامل الفيديو والصور (Video/Image)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= 6. الرسوم والمالية ================= -->
    <div class="settings-group-card" id="sec-finance">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <h3>إعدادات الرسوم والعمولات والمالية</h3>
                    <p>عمولة المنصة، تدقيق السندات، والعملات المعتمدة</p>
                </div>
            </div>
            <span class="group-count-badge">3 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">عمولة المنصة لكل تسجيل مؤكد</div>
                <div class="setting-item-desc">المبلغ المالي المستحق للمنصة عن كل طالب يتم تسجيله بنجاح بالجامعة</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="finance_commission_per_registration" min="0" step="500" value="{{ $settings['finance_commission_per_registration'] ?? '5000' }}">
                    <span class="unit-tag">ر.ي/تسجيل</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">التدقيق المالي لإيصالات السداد</div>
                <div class="setting-item-desc">اشتراط مراجعة وتأكيد المحاسب لسندات التحويل والإيداع البنكي قبل التأكيد</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="finance_payment_proof_review" value="1" {{ ($settings['finance_payment_proof_review'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">العملة الافتراضية للمنصة</div>
                <div class="setting-item-desc">العملة الأساسية المستخدمة لعرض الرسوم الدراسية وعمليات التسوية المالية</div>
            </div>
            <div class="setting-item-control">
                <select name="finance_default_currency" class="edu-select">
                    <option value="YER" {{ ($settings['finance_default_currency'] ?? 'YER') == 'YER' ? 'selected' : '' }}>ريال يمني (YER)</option>
                    <option value="SAR" {{ ($settings['finance_default_currency'] ?? '') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                    <option value="USD" {{ ($settings['finance_default_currency'] ?? '') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= 7. الإشعارات والتنبيهات ================= -->
    <div class="settings-group-card" id="sec-notifications">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <h3>إعدادات الإشعارات والتنبيهات الفورية</h3>
                    <p>إشعارات الإدارة، تنبيهات الطلاب والجامعات، وقنوات الإرسال</p>
                </div>
            </div>
            <span class="group-count-badge">5 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">تنبيه الإدارة بطلبات الجامعات الجديدة</div>
                <div class="setting-item-desc">إشعار فوري لمدير النظام فور تقديم جامعة جديدة لطلب انضمام للمنصة</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="notify_admin_university" value="1" {{ ($settings['notify_admin_university'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">تنبيه الإدارة بحجوزات الإعلانات</div>
                <div class="setting-item-desc">إشعار فوري عند طلب جامعة لحملة إعلانية أو تجديد بنر ترويجي</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="notify_admin_ads" value="1" {{ ($settings['notify_admin_ads'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">إشعار الطالب بمستجدات القبول</div>
                <div class="setting-item-desc">إرسال تنبيه للطالب فور تحديث حالة طلبه بالقبول، الرفض، أو طلب نواقص</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="notify_student_status" value="1" {{ ($settings['notify_student_status'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">إشعار الجامعات بالطلبات الجديدة</div>
                <div class="setting-item-desc">إشعار فوري لمسؤولي التسجيل بالجامعة عند تقديم طالب جديد لطلب التحاق</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="notify_university_apps" value="1" {{ ($settings['notify_university_apps'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">القناة الافتراضية لتوصيل الإشعارات</div>
                <div class="setting-item-desc">وسيلة الإرسال المعتمدة لتسليم التنبيهات للمستخدمين بالمنصة</div>
            </div>
            <div class="setting-item-control">
                <select name="notify_default_channel" class="edu-select">
                    <option value="all" {{ ($settings['notify_default_channel'] ?? 'all') == 'all' ? 'selected' : '' }}>كافة القنوات (نظام + بريد)</option>
                    <option value="system_only" {{ ($settings['notify_default_channel'] ?? '') == 'system_only' ? 'selected' : '' }}>إشعارات النظام فقط</option>
                    <option value="mail_only" {{ ($settings['notify_default_channel'] ?? '') == 'mail_only' ? 'selected' : '' }}>البريد الإلكتروني فقط</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= 8. النظام والأمان ================= -->
    <div class="settings-group-card" id="sec-security">
        <div class="settings-group-header">
            <div class="group-header-title">
                <div class="group-icon-box">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h3>إعدادات الأمان والنظام والذكاء الاصطناعي</h3>
                    <p>سجل العمليات، حماية تسجيل الدخول، ومحرك التوصيات الأكاديمي</p>
                </div>
            </div>
            <span class="group-count-badge">5 خيارات</span>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">سجل تدقيق عمليات الإدارة (Audit Log)</div>
                <div class="setting-item-desc">توثيق جميع العمليات الإدارية الحساسة وإجراءات المدراء مع الوقت وعنوان IP</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="security_admin_audit_log" value="1" {{ ($settings['security_admin_audit_log'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">تأكيد العمليات الحساسة</div>
                <div class="setting-item-desc">إظهار نافذة تأكيد إضافية قبل تنفيذ عمليات الحذف النهائي أو إيقاف الحسابات</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="security_sensitive_action_confirm" value="1" {{ ($settings['security_sensitive_action_confirm'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">مهلة انتهاء الجلسة الخاملة</div>
                <div class="setting-item-desc">تسجيل خروج المشرف تلقائياً بعد فترة من عدم التفاعل حمايةً للأمان</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="security_session_timeout" min="15" max="480" step="15" value="{{ $settings['security_session_timeout'] ?? '60' }}">
                    <span class="unit-tag">دقيقة</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">الحد الأقصى لمحاولات الدخول الخاطئة</div>
                <div class="setting-item-desc">سقف المحاولات المسموح بها قبل إيقاف تسجيل الدخول مؤقتاً لحظر الهجمات</div>
            </div>
            <div class="setting-item-control">
                <div class="edu-input-unit">
                    <input type="number" name="security_max_login_attempts" min="3" max="10" value="{{ $settings['security_max_login_attempts'] ?? '5' }}">
                    <span class="unit-tag">محاولات</span>
                </div>
            </div>
        </div>

        <div class="setting-item-row">
            <div class="setting-item-info">
                <div class="setting-item-title">محرك التوصيات الأكاديمي الذكي</div>
                <div class="setting-item-desc">تفعيل خوارزمية الترشيح الذكية لاقتراح التخصصات المناسبة للطالب بحسب معدله واهتماماته</div>
            </div>
            <div class="setting-item-control">
                <label class="edu-switch">
                    <input type="checkbox" name="security_recommendation_engine" value="1" {{ ($settings['security_recommendation_engine'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span class="edu-switch-slider"></span>
                </label>
            </div>
        </div>
    </div>

</form>

<!-- ================= 9. الحساب الإداري والأمان الشخصي ================= -->
<div class="settings-group-card" id="sec-account">
    <div class="settings-group-header">
        <div class="group-header-title">
            <div class="group-icon-box">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <h3>بيانات حساب المشرف وتغيير كلمة المرور</h3>
                <p>إدارة الملف الشخصي للمدير وتعيين كلمة مرور جديدة آمنة</p>
            </div>
        </div>
        <span class="group-count-badge">أمان الحساب</span>
    </div>

    <div style="padding: 20px;">
        <div class="grid-2">
            <!-- Account Form -->
            <div style="background:var(--surface-alt);padding:18px;border-radius:var(--radius-sm);border:1px solid var(--border-light);">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-light));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:18px;">
                        {{ mb_substr($admin->username ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight:800;font-size:14px;color:var(--ink);">{{ $admin->username ?? 'مدير النظام' }}</div>
                        <div style="font-size:11.5px;color:var(--ink-muted);">حساب المشرف العام — صلاحيات كاملة</div>
                    </div>
                </div>

                <form id="settingsAccountForm">
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label" style="font-size:12px;font-weight:700;">اسم المستخدم <span class="req">*</span></label>
                        <input type="text" name="username" id="adminUsername" class="form-control" value="{{ $admin->username }}" required minlength="4">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label" style="font-size:12px;font-weight:700;">رقم الهاتف</label>
                        <input type="text" name="phone" id="adminPhone" class="form-control" value="{{ $admin->phone }}" placeholder="770000000">
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label class="form-label" style="font-size:12px;font-weight:700;">البريد الإلكتروني</label>
                        <input type="email" name="email" id="adminEmail" class="form-control" value="{{ $admin->email }}" placeholder="admin@eduyemen.com">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveAdminAccount()" id="saveAccountBtn" style="font-size:12.5px;">
                        <i class="fa-solid fa-floppy-disk"></i> حفظ بيانات الحساب
                    </button>
                </form>
            </div>

            <!-- Password Form -->
            <div style="background:var(--surface-alt);padding:18px;border-radius:var(--radius-sm);border:1px solid var(--border-light);">
                <div style="font-weight:800;font-size:14px;color:var(--primary-dark);margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-lock" style="color:var(--accent);"></i>
                    تغيير كلمة المرور
                </div>
                <p style="font-size:11.5px;color:var(--ink-muted);margin-bottom:14px;">
                    لحماية النظام، استخدم كلمة مرور قوية تتضمن أرقاماً ورموزاً مميزة.
                </p>

                <form id="passwordForm">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="form-label" style="font-size:12px;font-weight:700;">كلمة المرور الحالية <span class="req">*</span></label>
                        <input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="form-label" style="font-size:12px;font-weight:700;">كلمة المرور الجديدة <span class="req">*</span></label>
                        <input type="password" name="password" id="newPassword" class="form-control" placeholder="6 أحرف على الأقل" required minlength="6">
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label class="form-label" style="font-size:12px;font-weight:700;">تأكيد كلمة المرور الجديدة <span class="req">*</span></label>
                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" placeholder="إعادة إدخال كلمة المرور" required>
                    </div>
                    <button type="button" class="btn btn-warning" onclick="changeAdminPassword()" id="changePassBtn" style="font-size:12.5px;">
                        <i class="fa-solid fa-key"></i> تحديث كلمة المرور
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= Floating Save Bar ================= -->
<div class="floating-save-bar" id="floatingSaveBar">
    <div class="floating-save-text">
        <span class="floating-save-dot"></span>
        لديك تعديلات غير محفوظة في إعدادات المنصة
    </div>
    <button type="button" class="btn-hero-save" onclick="saveAllPlatformSettings()" style="padding:7px 16px;font-size:12px;">
        <i class="fa-solid fa-check"></i> حفظ الآن
    </button>
</div>

<!-- ================= Modal: Reset Defaults ================= -->
<div class="edu-modal-backdrop" id="resetModal">
    <div class="edu-modal-box">
        <div style="width:54px;height:54px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 16px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 style="font-size:16px;font-weight:800;color:var(--ink);margin-bottom:8px;">استعادة الإعدادات الافتراضية؟</h3>
        <p style="font-size:12.5px;color:var(--ink-muted);margin-bottom:20px;line-height:1.6;">
            سيتم إعادة ضبط كافة خيارات المنصة (الجامعات، الإعلانات، المحتوى، الأمان، الرسوم) إلى القيم القياسية الموصى بها.
        </p>
        <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
            <button type="button" class="btn btn-outline" onclick="closeResetModal()" style="font-size:12.5px;">إلغاء</button>
            <button type="button" class="btn btn-danger" onclick="confirmResetDefaults()" id="confirmResetBtn" style="font-size:12.5px;background:#dc2626;color:#fff;">
                <i class="fa-solid fa-rotate-left"></i> نعم، استعادة الافتراضي
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let hasUnsavedChanges = false;
    const floatingBar = document.getElementById('floatingSaveBar');
    const platformForm = document.getElementById('platformSettingsForm');

    // تتبع التعديلات لإظهار شريط الحفظ العائم
    platformForm.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('change', () => {
            hasUnsavedChanges = true;
            floatingBar.classList.add('active');
        });
        if (el.tagName === 'INPUT' && el.type === 'number') {
            el.addEventListener('input', () => {
                hasUnsavedChanges = true;
                floatingBar.classList.add('active');
            });
        }
    });

    // التنقل بين الأقسام عبر الأزرار (Category Tabs)
    const tabBtns = document.querySelectorAll('.nav-tab-btn');
    const groupCards = document.querySelectorAll('.settings-group-card');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const target = this.getAttribute('data-target');

            if (target === 'all') {
                groupCards.forEach(card => card.style.display = 'block');
                const firstCard = groupCards[0];
                if (firstCard) firstCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                groupCards.forEach(card => {
                    if (card.id === target) {
                        card.style.display = 'block';
                        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });
    });

    // البحث الفوري في الإعدادات
    const searchInput = document.getElementById('settingsSearchInput');
    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        
        // عند البحث، نظهر كافة الأقسام
        if (query.length > 0) {
            tabBtns.forEach(b => b.classList.remove('active'));
            document.querySelector('.nav-tab-btn[data-target="all"]').classList.add('active');
            groupCards.forEach(card => card.style.display = 'block');
        }

        const rows = document.querySelectorAll('.setting-item-row');
        rows.forEach(row => {
            const title = row.querySelector('.setting-item-title')?.textContent.toLowerCase() || '';
            const desc = row.querySelector('.setting-item-desc')?.textContent.toLowerCase() || '';
            
            if (title.includes(query) || desc.includes(query)) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });

        // إخفاء الأقسام التي لا تحتوي نتائج مطابقة
        groupCards.forEach(card => {
            const visibleRows = card.querySelectorAll('.setting-item-row:not([style*="display: none"])');
            if (visibleRows.length === 0 && query.length > 0) {
                card.style.display = 'none';
            } else {
                card.style.display = 'block';
            }
        });
    });

    // حفظ جميع إعدادات المنصة عبر AJAX
    async function saveAllPlatformSettings() {
        const heroBtn = document.getElementById('heroSaveBtn');
        const originalText = heroBtn.innerHTML;
        heroBtn.innerHTML = '<span class="spinner"></span> جارٍ الحفظ...';
        heroBtn.disabled = true;

        const formData = new FormData(platformForm);
        const data = {};

        // جمع الحقول وتأمين قيم الـ Checkboxes (إذا غير محدد يُرسل '0')
        platformForm.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            data[cb.name] = cb.checked ? '1' : '0';
        });

        platformForm.querySelectorAll('input[type="number"], select').forEach(input => {
            data[input.name] = input.value;
        });

        try {
            const result = await adminFetch('{{ route("admin.settings.platform") }}', 'POST', data);
            if (result.success) {
                showToast(result.message || 'تم حفظ كافة الإعدادات بنجاح.', 'success');
                hasUnsavedChanges = false;
                floatingBar.classList.remove('active');
            } else {
                showToast(result.message || 'حدث خطأ أثناء حفظ الإعدادات.', 'error');
            }
        } catch(e) {
            showToast('فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.', 'error');
        } finally {
            heroBtn.innerHTML = originalText;
            heroBtn.disabled = false;
        }
    }

    // مودال استعادة الافتراضي
    function openResetModal() {
        document.getElementById('resetModal').classList.add('open');
    }
    function closeResetModal() {
        document.getElementById('resetModal').classList.remove('open');
    }

    async function confirmResetDefaults() {
        const btn = document.getElementById('confirmResetBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الاستعادة...';
        btn.disabled = true;

        try {
            const result = await adminFetch('{{ route("admin.settings.reset") }}', 'POST', {});
            if (result.success) {
                showToast(result.message, 'success');
                closeResetModal();
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(result.message || 'فشلت عملية الاستعادة.', 'error');
            }
        } catch(e) {
            showToast('حدث خطأ في الاتصال بالخادم.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-rotate-left"></i> نعم، استعادة الافتراضي';
            btn.disabled = false;
        }
    }

    // حفظ بيانات حساب المدير
    async function saveAdminAccount() {
        const btn = document.getElementById('saveAccountBtn');
        const form = document.getElementById('settingsAccountForm');
        const data = {
            username: form.querySelector('[name="username"]').value.trim(),
            phone:    form.querySelector('[name="phone"]').value.trim(),
            email:    form.querySelector('[name="email"]').value.trim(),
        };

        if (!data.username || data.username.length < 4) {
            showToast('اسم المستخدم يجب ألا يقل عن 4 أحرف.', 'error');
            return;
        }

        btn.innerHTML = '<span class="spinner"></span> جارٍ الحفظ...';
        btn.disabled = true;

        try {
            const result = await adminFetch('{{ route("admin.settings.update") }}', 'POST', data);
            if (result.success) {
                showToast(result.message, 'success');
            } else {
                const errors = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'حدث خطأ.');
                showToast(errors, 'error');
            }
        } catch(e) {
            showToast('حدث خطأ أثناء حفظ بيانات الحساب.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> حفظ بيانات الحساب';
            btn.disabled = false;
        }
    }

    // تغيير كلمة مرور المدير
    async function changeAdminPassword() {
        const current  = document.getElementById('currentPassword').value;
        const newPass  = document.getElementById('newPassword').value;
        const confirm  = document.getElementById('confirmPassword').value;

        if (!current || !newPass || !confirm) {
            showToast('يرجى ملء جميع حقول كلمة المرور.', 'error'); return;
        }
        if (newPass.length < 6) {
            showToast('كلمة المرور الجديدة يجب ألا تقل عن 6 أحرف.', 'error'); return;
        }
        if (newPass !== confirm) {
            showToast('كلمة المرور وتأكيدها غير متطابقين.', 'error'); return;
        }

        const btn = document.getElementById('changePassBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الحفظ...';
        btn.disabled = true;

        try {
            const result = await adminFetch('{{ route("admin.settings.password") }}', 'POST', {
                current_password: current,
                password: newPass,
                password_confirmation: confirm,
            });

            if (result.success) {
                showToast(result.message, 'success');
                document.getElementById('passwordForm').reset();
            } else {
                showToast(result.message || 'فشل تغيير كلمة المرور.', 'error');
            }
        } catch(e) {
            showToast('حدث خطأ أثناء تغيير كلمة المرور.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-key"></i> تحديث كلمة المرور';
            btn.disabled = false;
        }
    }
</script>
@endsection
