@extends('layouts.app')

@section('title', $university->university_name . ' — الكليات والتخصصات وشروط القبول | EduYemen')

@section('styles')
<style>
    /* ========================================================
       نظام الألوان والتصميم العصري لصفحة الجامعة الأكاديمية
       ======================================================== */
    :root {
        --uni-navy: #0b3d6b;
        --uni-navy-dark: #072644;
        --uni-blue: #1565a8;
        --uni-gold: #c59b27;
        --uni-gold-hover: #b0891e;
        --uni-light-gold: #fdf6e7;
        --uni-bg: #f8fafc;
        --uni-border: #e2e8f0;
        --uni-text-dark: #0f172a;
        --uni-text-muted: #64748b;
    }

    /* ====== 1. الرأس الأكاديمي للجامعة (Academic Hero Banner) ====== */
    .uni-academic-hero {
        background: linear-gradient(135deg, #072644 0%, #0b3d6b 65%, #1565a8 100%);
        color: #ffffff;
        position: relative;
        padding: 2.2rem 0 2rem;
        box-shadow: 0 4px 20px rgba(7, 38, 68, 0.12);
        overflow: hidden;
    }

    .uni-academic-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 15% 25%, rgba(197, 155, 39, 0.12) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(21, 101, 168, 0.22) 0%, transparent 50%),
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.025'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }

    .uni-hero-inner {
        position: relative;
        z-index: 2;
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* شبكة رأس الصفحة: معلومات الجامعة وأزرار الإجراء السريع في جهة، وصورة الحرم في الجهة المقابلة */
    .uni-hero-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 30px;
        align-items: center;
    }

    .uni-hero-info-col {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .uni-brand-identity {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .uni-emblem-badge {
        width: 95px;
        height: 95px;
        background: #ffffff;
        border-radius: 16px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(197, 155, 39, 0.4);
        flex-shrink: 0;
    }

    .uni-emblem-badge img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .uni-brand-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .uni-badge-chips-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .chip-badge {
        font-size: 0.74rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .chip-badge-type {
        background: rgba(224, 242, 254, 0.2);
        color: #7dd3fc;
        border: 1px solid rgba(125, 211, 252, 0.35);
    }

    .chip-badge-status {
        background: rgba(236, 253, 245, 0.2);
        color: #6ee7b7;
        border: 1px solid rgba(110, 231, 183, 0.4);
    }

    .chip-badge-year {
        background: rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .uni-hero-title {
        font-size: 1.85rem;
        font-weight: 900;
        color: #ffffff;
        margin: 0;
        line-height: 1.25;
        letter-spacing: -0.3px;
    }

    .uni-hero-location {
        font-size: 0.88rem;
        color: #cbd5e1;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .uni-hero-location i {
        color: var(--uni-gold);
    }

    .uni-hero-desc {
        font-size: 0.85rem;
        color: #e2e8f0;
        margin: 0;
        line-height: 1.6;
    }

    /* أزرار الإجراء السريع في رأس الصفحة تقع تحت المعلومات في نفس الجهة */
    .uni-hero-actions-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 4px;
    }

    /* الجهة المقابلة: إطار صورة الحرم الجامعي المستقل بدون أي تداخل */
    .uni-hero-campus-col {
        position: relative;
    }

    .uni-campus-frame {
        position: relative;
        width: 100%;
        height: 250px;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        background: #072644;
    }

    .uni-campus-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }

    .uni-campus-frame:hover .uni-campus-photo {
        transform: scale(1.03);
    }

    .campus-tag-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(7, 38, 68, 0.85);
        color: #ffffff;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        font-size: 0.76rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid rgba(197, 155, 39, 0.5);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        z-index: 2;
    }

    .btn-hero-cta {
        height: 44px;
        padding: 0 22px;
        background: linear-gradient(135deg, var(--uni-gold) 0%, #b0891e 100%);
        color: #072644;
        border-radius: 10px;
        font-size: 0.92rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        box-shadow: 0 4px 14px rgba(197, 155, 39, 0.4);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-hero-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(197, 155, 39, 0.6);
        color: #072644;
    }

    .btn-hero-outline {
        height: 44px;
        padding: 0 16px;
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1.5px solid rgba(255, 255, 255, 0.25);
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        backdrop-filter: blur(4px);
        transition: all 0.2s ease;
    }

    .btn-hero-outline:hover {
        background: #ffffff;
        color: var(--uni-navy);
        border-color: #ffffff;
    }

    /* ====== 2. شريط التبويبات الداخلي Sticky ====== */
    .uni-tabs-nav-bar {
        background: #ffffff;
        border-bottom: 2px solid var(--uni-border);
        position: sticky;
        top: 65px;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.04);
    }

    .uni-tabs-container {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 16px;
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .uni-tabs-container::-webkit-scrollbar {
        display: none;
    }

    .tab-nav-btn {
        padding: 14px 18px;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--uni-text-muted);
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tab-nav-btn:hover {
        color: var(--uni-navy);
        background-color: #f8fafc;
    }

    .tab-nav-btn.active {
        color: var(--uni-navy);
        border-bottom-color: var(--uni-gold);
        background-color: rgba(197, 155, 39, 0.08);
    }

    /* ====== 3. جسم الصفحة والشبكة الأساسية ====== */
    .uni-main-body {
        max-width: 1320px;
        margin: 0 auto;
        padding: 28px 16px 60px;
    }

    .section-block {
        background: #ffffff;
        border: 1px solid var(--uni-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.03);
        scroll-margin-top: 130px;
    }

    .section-head-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--uni-navy);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--uni-border);
    }

    .section-title-start {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title-start::before {
        content: "";
        width: 4px;
        height: 20px;
        background-color: var(--uni-gold);
        border-radius: 2px;
    }

    /* أزرار فلترة الكليات */
    .colleges-filter-pills {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 18px;
        scrollbar-width: thin;
    }

    .pill-btn {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.84rem;
        font-weight: 700;
        background: #f1f5f9;
        color: var(--uni-text-muted);
        border: 1px solid transparent;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pill-btn:hover {
        background: #e2e8f0;
        color: var(--uni-text-dark);
    }

    .pill-btn.active {
        background: var(--uni-navy);
        color: #ffffff;
        border-color: var(--uni-navy);
    }

    /* كروت الكليات والتخصصات */
    .college-accordion-card {
        border: 1px solid var(--uni-border);
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .college-accordion-card:hover {
        border-color: rgba(11, 61, 107, 0.35);
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.05);
    }

    .college-header-btn {
        width: 100%;
        padding: 14px 18px;
        background: #f8fafc;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        text-align: right;
        gap: 12px;
        transition: background 0.2s ease;
    }

    .college-header-btn:hover {
        background: #f1f5f9;
    }

    .college-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .college-icon-circle {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #e0f2fe;
        color: var(--uni-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .college-name {
        font-size: 1.02rem;
        font-weight: 800;
        color: var(--uni-navy);
        margin: 0;
    }

    .college-count-tag {
        font-size: 0.74rem;
        background: #e2e8f0;
        color: var(--uni-text-muted);
        padding: 3px 9px;
        border-radius: 12px;
        font-weight: 700;
    }

    .college-chevron {
        color: var(--uni-text-muted);
        transition: transform 0.25s ease;
        font-size: 0.85rem;
    }

    .college-accordion-card.open .college-chevron {
        transform: rotate(180deg);
    }

    .college-content-drawer {
        padding: 18px;
        background: #ffffff;
        border-top: 1px solid var(--uni-border);
        display: none;
    }

    .college-accordion-card.open .college-content-drawer {
        display: block;
    }

    /* ====== شبكة بطاقات التخصصات المحسنة ====== */
    .majors-list-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .major-card-item {
        background: #ffffff;
        border: 1.5px solid var(--uni-border);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(11, 61, 107, 0.04);
    }

    .major-card-item:hover {
        border-color: var(--uni-blue);
        box-shadow: 0 6px 18px rgba(11, 61, 107, 0.08);
    }

    /* رأس بطاقة التخصص: اسم التخصص + زر التسجيل الآن في مكانه الجديد */
    .major-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .major-name {
        font-size: 0.98rem;
        font-weight: 800;
        color: var(--uni-text-dark);
        margin: 0;
        line-height: 1.35;
    }

    /* زر التسجيل الآن في رأس البطاقة بحجم وتصميم ملائم جداً */
    .btn-apply-header-badge {
        background: linear-gradient(135deg, var(--uni-navy) 0%, var(--uni-blue) 100%);
        color: #ffffff;
        border: none;
        border-radius: 20px;
        font-size: 0.76rem;
        font-weight: 800;
        padding: 5px 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(11, 61, 107, 0.2);
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .btn-apply-header-badge:hover {
        background: linear-gradient(135deg, var(--uni-navy-dark) 0%, var(--uni-navy) 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(11, 61, 107, 0.3);
    }

    .major-specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
        font-size: 0.78rem;
        color: var(--uni-text-muted);
        background: #f8fafc;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid var(--uni-border);
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .spec-item i {
        color: var(--uni-gold);
        font-size: 0.85rem;
    }

    .spec-item strong {
        color: var(--uni-text-dark);
    }

    .spec-docs-summary {
        font-size: 0.76rem;
        color: var(--uni-text-muted);
        padding-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* أزرار أسفل بطاقة التخصص: تفاصيل القبول + عرض الخطة الدراسية */
    .major-footer-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: auto;
        padding-top: 4px;
    }

    .btn-card-action {
        flex: 1 1 50%;
        min-width: 0;
        height: 36px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .btn-admission-details {
        background: #ffffff;
        border: 1.5px solid var(--uni-blue);
        color: var(--uni-blue);
    }

    .btn-admission-details:hover {
        background: #f1f5f9;
        color: var(--uni-navy);
        border-color: var(--uni-navy);
    }

    .btn-study-plan-view {
        background: #f8fafc;
        border: 1.5px solid var(--uni-gold);
        color: #926c09;
    }

    .btn-study-plan-view:hover {
        background: var(--uni-gold);
        color: #072644;
    }

    .btn-toggle-major-specs {
        background: #f8fafc;
        border: 1.5px solid var(--uni-border);
        color: var(--uni-navy);
        flex: 1 1 100%;
        margin-top: 4px;
        font-size: 0.78rem;
    }

    .btn-toggle-major-specs:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
    }

    .major-specs-drawer {
        display: none;
        padding-top: 10px;
        border-top: 1px dashed var(--uni-border);
        animation: fadeIn 0.2s ease-in-out;
    }

    .major-card-item.open-specs .major-specs-drawer {
        display: block !important;
    }

    .major-card-item.open-specs .toggle-chevron {
        transform: rotate(180deg);
    }

    /* ====== 4. بطاقة المعلومات العامة المحسنة والحديثة ====== */
    .executive-info-card {
        background: #ffffff;
        border: 1px solid var(--uni-border);
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 22px;
        box-shadow: 0 2px 10px rgba(11, 61, 107, 0.03);
    }

    .executive-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--uni-navy);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--uni-border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-metrics-tiles {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .metric-tile {
        background: #f8fafc;
        border: 1px solid var(--uni-border);
        border-radius: 10px;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .metric-tile-label {
        font-size: 0.72rem;
        color: var(--uni-text-muted);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .metric-tile-val {
        font-size: 0.88rem;
        font-weight: 800;
        color: var(--uni-navy);
    }

    .admission-status-banner {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 10px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #065f46;
        font-weight: 700;
    }

    .status-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
    }

    /* ====== 5. بديل بطاقة نظام الإحالة (زر وتوضيح للحضور الشخصي) ====== */
    .referral-prompt-widget {
        background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%);
        border: 1.5px solid var(--uni-gold);
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 2px 8px rgba(197, 155, 39, 0.1);
    }

    .referral-prompt-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #854d0e;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 6px;
    }

    .referral-prompt-desc {
        font-size: 0.8rem;
        color: #713f12;
        line-height: 1.55;
        margin-bottom: 12px;
    }

    .btn-referral-prompt-action {
        width: 100%;
        height: 38px;
        background: var(--uni-gold);
        color: #072644;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-referral-prompt-action:hover {
        background: var(--uni-gold-hover);
        color: #072644;
    }

    /* خطوات وإجراءات التقديم */
    .registration-steps-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .step-item-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #f8fafc;
        border: 1px solid var(--uni-border);
        border-radius: 10px;
        padding: 14px 16px;
    }

    .step-number-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--uni-navy) 0%, var(--uni-blue) 100%);
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(11, 61, 107, 0.2);
    }

    .step-text-wrap h4 {
        font-size: 0.94rem;
        font-weight: 800;
        color: var(--uni-text-dark);
        margin-bottom: 2px;
    }

    .step-text-wrap p {
        font-size: 0.82rem;
        color: var(--uni-text-muted);
        margin: 0;
        line-height: 1.5;
    }

    /* ====== 6. النوافذ المنبثقة التفاعلية (Modals) ====== */
    .custom-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(7, 38, 68, 0.65);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .custom-modal-overlay.active {
        display: flex;
    }

    .custom-modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 24px;
        box-shadow: 0 16px 40px rgba(7, 38, 68, 0.25);
        position: relative;
    }

    .modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--uni-border);
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .modal-title-text {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--uni-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        color: var(--uni-text-dark);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: background 0.15s ease;
    }

    .btn-modal-close:hover {
        background: #e2e8f0;
    }

    .form-group-item {
        margin-bottom: 14px;
    }

    .form-group-item label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--uni-text-dark);
        margin-bottom: 5px;
    }

    .form-input-ctrl {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid var(--uni-border);
        border-radius: 8px;
        font-size: 0.86rem;
        outline: none;
        background: #f8fafc;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .form-input-ctrl:focus {
        border-color: var(--uni-blue);
        background: #ffffff;
    }

    /* ====== 7. تجاوب شاشات الهواتف المحمولة ====== */
    @media (max-width: 991.98px) {
        .uni-hero-grid {
            grid-template-columns: 1fr;
            gap: 22px;
        }
        .uni-brand-identity {
            width: 100%;
        }
        .uni-campus-frame {
            height: 220px;
        }
        .uni-hero-actions-group {
            width: 100%;
        }
        .uni-hero-actions-group > button,
        .uni-hero-actions-group > a {
            flex: 1 1 auto;
            justify-content: center;
        }
        .majors-list-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .uni-academic-hero {
            padding: 1.4rem 0 1.2rem;
        }
        .uni-emblem-badge {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            padding: 5px;
        }
        .uni-campus-frame {
            height: 185px;
        }
        .uni-hero-title {
            font-size: 1.35rem;
        }
        .uni-hero-location {
            font-size: 0.78rem;
        }
        .tab-nav-btn {
            padding: 10px 12px;
            font-size: 0.82rem;
            gap: 5px;
        }
        .section-block {
            padding: 16px;
        }
        .info-metrics-tiles {
            grid-template-columns: 1fr;
        }
        .major-specs-grid {
            grid-template-columns: 1fr;
            padding: 8px 10px;
        }
        .btn-card-action {
            font-size: 0.74rem;
            padding: 0 6px;
        }
        .btn-apply-header-badge {
            font-size: 0.72rem;
            padding: 4px 10px;
        }
    }

    @if(isset($isPreview) && $isPreview)
    .uni-preview-banner {
        background: linear-gradient(135deg, #072644 0%, #102a43 100%) !important;
        border-bottom: 2px solid #e4b75b !important;
        padding: 12px 0 !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25) !important;
    }
    .preview-locked-wrap a:not(.btn-return-dashboard):not([onclick*="closeSidebar"]):not([onclick*="openSidebar"]),
    .preview-locked-wrap button:not(.btn-return-dashboard):not([onclick*="closeSidebar"]):not([onclick*="openSidebar"]),
    .preview-locked-wrap input,
    .preview-locked-wrap select {
        pointer-events: none !important;
        cursor: not-allowed !important;
    }
    .btn-return-dashboard {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    @endif
</style>
@endsection

@section('content')

{{-- شريط تنبيه وضع المعاينة الأكاديمية الخاص بإدارة الجامعة --}}
@if(isset($isPreview) && $isPreview)
<div class="uni-preview-banner sticky-top" style="background: linear-gradient(135deg, #072644 0%, #102a43 100%); border-bottom: 2px solid #e4b75b; padding: 12px 0; z-index: 10050; box-shadow: 0 4px 20px rgba(0,0,0,0.25);">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 text-white">
            <span class="badge" style="background:#e4b75b; color:#0a1b2a; font-weight:800; font-size:12.5px; padding: 6px 12px; border-radius: 20px;">
                👁️ وضع معاينة صفحة الجامعة (للمعاينة فقط)
            </span>
            <span class="small fw-semibold text-light">
                أنت الآن في وضع معاينة صفحة <strong>{{ $university->university_name }}</strong>. لا يمكن تنفيذ أي إجراء أو الانتقال لصفحات أخرى.
            </span>
        </div>
        <div>
            <a href="{{ route('university.dashboard') }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 btn-return-dashboard" style="border-radius: 20px; box-shadow: 0 2px 10px rgba(228,183,91,0.4);">
                <i class="bi bi-arrow-right me-1"></i> العودة إلى لوحة التحكم
            </a>
        </div>
    </div>
</div>
@endif

<div class="{{ (isset($isPreview) && $isPreview) ? 'preview-locked-wrap' : '' }}">

@php
    $totalColleges = $university->colleges->count();
    $totalSpecs = $university->colleges->sum(fn($c) => $c->specializations->count());
@endphp

{{-- ====== 1. الرأس الأكاديمي للجامعة (معلومات وأزرار الإجراء في جهة، وصورة الحرم في الجهة المقابلة) ====== --}}
<section class="uni-academic-hero">
    <div class="uni-hero-inner">
        <div class="uni-hero-grid">
            
            {{-- الجانب الأيمن: اسم ومعلومات الجامعة وأزرار الإجراء السريع بالكامل في هذه الجهة دون أي تداخل مع الصورة --}}
            <div class="uni-hero-info-col">
                <div class="uni-brand-identity">
                    <div class="uni-emblem-badge">
                        @if($university->isItemQuarantined('logo'))
                            <div class="uni-emblem-quarantined" style="width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#fef2f2; border:2px dashed #f87171; border-radius:14px; color:#dc2626; text-align:center; padding:4px;" title="تم إيقاف ظهور شعار الجامعة بقرار رقابي إداري">
                                <i class="fa-solid fa-shield-virus" style="font-size:24px; margin-bottom:3px;"></i>
                                <span style="font-size:9.5px; font-weight:800; line-height:1.2;">محجوب رقابياً</span>
                            </div>
                        @else
                            <img src="{{ $university->logo_url }}" 
                                 alt="شعار {{ $university->university_name }}" 
                                 id="uniHeroLogo"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect width=\'100\' height=\'100\' fill=\'%23ffffff\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%230b3d6b\' font-size=\'40\'%3E🏛%3C/text%3E%3C/svg%3E'">
                        @endif
                    </div>

                    <div class="uni-brand-meta">
                        <div class="uni-badge-chips-row">
                            <span class="chip-badge chip-badge-type">
                                <i class="bi bi-mortarboard-fill"></i>
                                جامعة {{ $university->university_type }}
                            </span>
                            @if($university->status === 'نشطة')
                            <span class="chip-badge chip-badge-status">
                                <i class="bi bi-patch-check-fill"></i>
                                القبول والتسجيل مفتوح
                            </span>
                            @else
                            <span class="chip-badge" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4);">
                                <i class="bi bi-lock-fill"></i>
                                القبول مغلق حالياً
                            </span>
                            @endif
                            @if($university->year_establ)
                            <span class="chip-badge chip-badge-year">
                                <i class="bi bi-calendar3"></i>
                                تأسست {{ $university->year_establ }}م
                            </span>
                            @endif
                        </div>

                        <h1 class="uni-hero-title">{{ $university->university_name }}</h1>

                        <div class="uni-hero-location">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ $university->address ? $university->address . ' — ' : '' }}صنعاء، {{ $university->district }}</span>
                        </div>
                    </div>
                </div>

                @if($university->isItemQuarantined('description'))
                <div class="alert" style="background:rgba(239,68,68,0.18); border:1px solid rgba(239,68,68,0.35); border-radius:10px; padding:8px 14px; color:#fca5a5; font-size:0.8rem; margin-top:8px; display:inline-flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i>
                    <span>النبذة والوصف التعريفي للجامعة محجوب رقابياً بقرار إشرافي لمخالفته المعايير.</span>
                </div>
                @elseif($university->description)
                <p class="uni-hero-desc">
                    {{ Str::limit($university->description, 200) }}
                </p>
                @endif

                {{-- أزرار الإجراء السريع في رأس الصفحة تقع هنا تحت المعلومات في نفس الجهة دون أي تداخل بالصورة --}}
                <div class="uni-hero-actions-group">
                    @if(isset($isPreview) && $isPreview)
                    <button type="button" class="btn-hero-cta" style="background:#e4b75b; color:#072644; border-color:#e4b75b;" onclick="alert('أنت في وضع المعاينة المخصص لإدارة الجامعة ({{ addslashes($university->university_name) }}). تقديم طلبات التسجيل متاح لحسابات الطلبة فقط.')" title="معاينة نموذج التقديم">
                        <i class="bi bi-eye-fill"></i>
                        <span>معاينة التسجيل (للطلبة فقط)</span>
                    </button>
                    @elseif($university->status === 'مغلق')
                    <button type="button" class="btn-hero-cta" style="background:#64748b; border-color:#64748b; cursor:not-allowed;" disabled title="فترة القبول والتسجيل مغلقة حالياً في هذه الجامعة">
                        <i class="bi bi-lock-fill"></i>
                        <span>القبول مغلق حالياً</span>
                    </button>
                    @else
                    <button type="button" class="btn-hero-cta" onclick="openApplyModal()">
                        <i class="bi bi-send-check-fill"></i>
                        <span>التسجيل الآن</span>
                    </button>
                    @endif
                    
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($university->university_name . ' ' . $university->address . ' صنعاء') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="btn-hero-outline" 
                       title="عرض موقع الجامعة على الخريطة">
                        <i class="bi bi-map"></i>
                        <span>الموقع على الخريطة</span>
                    </a>

                    @if(!isset($isPreview) || !$isPreview)
                    <a href="{{ route('compare') }}?u1={{ $university->university_id }}" class="btn-hero-outline" title="مقارنة الجامعة">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>مقارنة</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- الجهة المقابلة (اليسار): صورة الحرم الجامعي نظيفة ومستقلة بدون أي تداخل نصوص أو أزرار --}}
            <div class="uni-hero-campus-col">
                @if($university->isItemQuarantined('campus_image'))
                    <div class="uni-campus-frame is-quarantined" style="display:flex; flex-direction:column; align-items:center; justify-content:center; background:linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border:2px dashed #fca5a5; border-radius:16px; padding:24px; text-align:center; color:#991b1b; min-height:220px;">
                        <i class="fa-solid fa-shield-halved" style="font-size:38px; color:#ef4444; margin-bottom:10px;"></i>
                        <h4 style="font-size:15px; font-weight:800; margin-bottom:6px; color:#991b1b;">صورة الحرم الجامعي محجوبة رقابياً</h4>
                        <p style="font-size:12px; color:#b91c1c; max-width:280px; margin:0; line-height:1.5;">تم إيقاف ظهور صورة الحرم الجامعي مؤقتاً بقرار من إدارة الرقابة لمراجعتها وتصحيحها وفق المعايير المعتمدة.</p>
                    </div>
                @else
                    <div class="uni-campus-frame">
                        <span class="campus-tag-badge">
                            <i class="bi bi-buildings-fill text-warning"></i>
                            <span>الحرم الجامعي</span>
                        </span>
                        <img src="{{ $university->campus_image_url ?: asset('images/campus-default.svg') }}" 
                             alt="صورة الحرم الجامعي لـ {{ $university->university_name }}" 
                             class="uni-campus-photo"
                             id="campusCoverImg"
                             onerror="this.onerror=null; this.src='/images/campus-default.svg';">
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>

{{-- ====== 2. شريط التنقل الداخلي الملتصق (Sticky Tabs) ====== --}}
<nav class="uni-tabs-nav-bar" aria-label="أقسام صفحة الجامعة">
    <div class="uni-tabs-container">
        <button class="tab-nav-btn active" type="button" onclick="switchSectionTab(this, 'collegesSection')">
            <i class="bi bi-mortarboard-fill text-primary"></i>
            <span>الكليات والتخصصات ({{ $totalSpecs }})</span>
        </button>
        <button class="tab-nav-btn" type="button" onclick="switchSectionTab(this, 'stepsSection')">
            <i class="bi bi-card-checklist text-primary"></i>
            <span>إجراءات التسجيل الإلكتروني</span>
        </button>
        <button class="tab-nav-btn" type="button" onclick="switchSectionTab(this, 'aboutSection')">
            <i class="bi bi-building-check text-primary"></i>
            <span>عن الجامعة والاعتمادات</span>
        </button>
        <button class="tab-nav-btn" type="button" onclick="switchSectionTab(this, 'contactSection')">
            <i class="bi bi-telephone-inbound-fill text-primary"></i>
            <span>الموقع ووسائل التواصل</span>
        </button>
    </div>
</nav>

{{-- ====== 3. المحتوى الرئيسي لصفحة الجامعة ====== --}}
<div class="uni-main-body">
    <div class="row g-4">

        {{-- العمود الأيمن الرئيسي (الكليات، التخصصات، خطوات التقديم، نبذة الجامعة) --}}
        <div class="col-lg-8 order-lg-first">

            {{-- قسم الكليات والتخصصات المعتمدة --}}
            <section class="section-block" id="collegesSection">
                <div class="section-head-title">
                    <div class="section-title-start">
                        <span>الكليات والتخصصات المعتمدة</span>
                    </div>
                    <span class="badge bg-primary px-3 py-2" style="font-size:0.78rem;">
                        {{ $totalColleges }} كليات &bull; {{ $totalSpecs }} تخصصاً
                    </span>
                </div>

                {{-- فلاتر الكليات التفاعلية --}}
                @if($university->colleges->isNotEmpty())
                <div class="colleges-filter-pills">
                    <button class="pill-btn active" onclick="filterColleges('all', this)">
                        <i class="bi bi-grid-fill me-1"></i>جميع الكليات
                    </button>
                    @foreach($university->colleges as $college)
                    <button class="pill-btn" onclick="filterColleges('college-{{ $college->college_id }}', this)">
                        {{ $college->college_name }}
                    </button>
                    @endforeach
                </div>
                @endif

                {{-- قائمة الكليات بالأكورديون التفاعلي (أول كلية مفتوحة وباقي الكليات مغلقة افتراضياً) --}}
                <div id="collegesAccordionContainer">
                    @forelse($university->colleges as $index => $college)
                    <div class="college-accordion-card {{ $index === 0 ? 'open' : '' }}" data-college-group="college-{{ $college->college_id }}">
                        <button class="college-header-btn" type="button" onclick="toggleCollegeCard(this)">
                            <div class="college-title-group">
                                <div class="college-icon-circle">
                                    @php
                                        $cName = $college->college_name;
                                        $cIcon = 'bi-book-half';
                                        if (str_contains($cName, 'طب') || str_contains($cName, 'صحي') || str_contains($cName, 'صيدل')) $cIcon = 'bi-hospital';
                                        elseif (str_contains($cName, 'هندس') || str_contains($cName, 'حاسوب') || str_contains($cName, 'تقني')) $cIcon = 'bi-laptop';
                                        elseif (str_contains($cName, 'إدار') || str_contains($cName, 'مال') || str_contains($cName, 'تجار')) $cIcon = 'bi-briefcase';
                                        elseif (str_contains($cName, 'قانون') || str_contains($cName, 'شريع')) $cIcon = 'bi-bank';
                                    @endphp
                                    <i class="bi {{ $cIcon }}"></i>
                                </div>
                                <div>
                                    <h3 class="college-name">{{ $college->college_name }}</h3>
                                    @if($college->description)
                                    <span style="font-size: 0.78rem; color: var(--uni-text-muted);">{{ $college->description }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="college-count-tag">{{ $college->specializations->count() }} تخصصات</span>
                                <i class="bi bi-chevron-down college-chevron"></i>
                            </div>
                        </button>
                        
                        <div class="college-content-drawer">
                            @if($college->specializations->isEmpty())
                                <p class="text-muted small mb-0 p-2">لا توجد تخصصات نشطة في هذه الكلية حالياً.</p>
                            @else
                            <div class="majors-list-grid">
                                @foreach($college->specializations as $spec)
                                <div class="major-card-item" id="major-card-{{ $spec->specialization_id }}">
                                    
                                    {{-- رأس البطاقة: اسم التخصص + زر التسجيل الآن في مكانه الجديد --}}
                                    <div class="major-header-row">
                                        <h4 class="major-name">{{ $spec->specialization_name }}</h4>
                                        @if(isset($isPreview) && $isPreview)
                                        <button type="button" 
                                                class="btn-apply-header-badge" 
                                                style="background:#cbd5e1; color:#475569; cursor:not-allowed;" 
                                                onclick="alert('أنت في وضع المعاينة الخاص بإدارة الجامعة. هذا الإجراء متاح للطلبة فقط.')"
                                                title="معاينة فقط (متاح للطلبة)">
                                            <i class="bi bi-eye me-1"></i>معاينة
                                        </button>
                                        @elseif($university->status === 'مغلق')
                                        <button type="button" 
                                                class="btn-apply-header-badge" 
                                                style="background:#cbd5e1; color:#64748b; cursor:not-allowed;" 
                                                disabled 
                                                title="القبول مغلق حالياً">
                                            <i class="bi bi-lock me-1"></i>مغلق
                                        </button>
                                        @else
                                        <button type="button" 
                                                class="btn-apply-header-badge" 
                                                onclick="openApplyModal('{{ addslashes($spec->specialization_name) }}')"
                                                title="التسجيل المباشر في هذا التخصص">
                                            <i class="bi bi-send-fill me-1"></i>التسجيل الآن
                                        </button>
                                        @endif
                                    </div>

                                    {{-- إعداد بيانات تفاصيل القبول والخطة لتمريرها للمودال بأمان --}}
                                    @php
                                        $adm = $spec->admissionData;
                                        $admDetails = $adm && !empty($adm->admission_data) ? $adm->admission_data : 'تعبئة استمارة التقديم الإلكتروني، واستيفاء الوثائق المقررة من وزارة التعليم العالي والبحث العلمي.';
                                        $admExtraDesc = $adm && !empty($adm->description) ? $adm->description : '';
                                        $minAvg = $adm && $adm->minimum_average ? $adm->minimum_average . '%' : '65%';
                                        $secYear = $adm && $adm->secondary_last_year ? $adm->secondary_last_year : '2021 فما بعد';
                                        $regFee = $adm && $adm->registration_fee !== null ? number_format($adm->registration_fee, 0) . ' ر.ي' : '5,000 ر.ي';
                                        $admStatus = $adm && $adm->admission_status ? $adm->admission_status : 'مفتوح';
                                        $startDate = $adm && $adm->admission_start_date ? \Carbon\Carbon::parse($adm->admission_start_date)->format('Y/m/d') : '2026/06/01';
                                        $endDate = $adm && $adm->admission_end_date ? \Carbon\Carbon::parse($adm->admission_end_date)->format('Y/m/d') : '2026/09/30';
                                        $admPeriod = "من {$startDate} إلى {$endDate}";
                                        
                                        $docsArray = [];
                                        if ($adm && $adm->docs && $adm->docs->count() > 0) {
                                            $docsArray = $adm->docs->map(fn($d) => [
                                                'name' => $d->document_name,
                                                'description' => $d->description,
                                                'is_required' => (bool)$d->is_required
                                            ])->toArray();
                                        } elseif ($adm && !empty($adm->required_documents)) {
                                            $docsArray = is_array($adm->required_documents) ? $adm->required_documents : json_decode($adm->required_documents, true);
                                        }
                                        $docsList = json_encode($docsArray ?: []);
                                        $planYears = $spec->duration_years ?? (str_contains($spec->specialization_name, 'طب') ? '5' : '4');
                                    @endphp

                                    {{-- أسفل البطاقة: تفاصيل القبول + عرض الخطة الدراسية فقط --}}
                                    <div class="major-footer-actions">
                                        <button type="button" 
                                                class="btn-card-action btn-admission-details" 
                                                onclick="openMajorDetailsModal(
                                                    '{{ addslashes($spec->specialization_name) }}',
                                                    '{{ addslashes($college->college_name) }}',
                                                    '{{ $minAvg }}',
                                                    '{{ number_format($spec->tuition_fees) }} دولار / ر.ي',
                                                    '{{ $regFee }}',
                                                    '{{ addslashes($admDetails) }}',
                                                    '{{ $secYear }}',
                                                    '{{ $admPeriod }}',
                                                    '{{ $admStatus }}',
                                                    '{{ addslashes($admExtraDesc) }}',
                                                    {{ $docsList }}
                                                )">
                                            <i class="bi bi-info-circle"></i>
                                            <span>تفاصيل القبول</span>
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn-card-action btn-study-plan-view" 
                                                onclick="openStudyPlanModal('{{ addslashes($spec->specialization_name) }}', '{{ addslashes($college->college_name) }}', '{{ $planYears }}')">
                                            <i class="bi bi-journal-text"></i>
                                            <span>عرض الخطة الدراسية</span>
                                        </button>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 bg-light rounded-4 border">
                        <i class="bi bi-mortarboard display-4 text-muted d-block mb-3"></i>
                        <h6 class="text-muted">لا توجد كليات نشطة معلنة لهذه الجامعة حالياً.</h6>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- قسم خطوات وإجراءات التسجيل الإلكتروني عبر المنصة --}}
            <section class="section-block" id="stepsSection">
                <div class="section-head-title">
                    <div class="section-title-start">
                        <span>مسار التسجيل الإلكتروني ومتابعة الطلب (EduYemen)</span>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1" style="font-size:0.75rem;">
                        <i class="bi bi-shield-check me-1"></i>نظام قبول معتمد
                    </span>
                </div>

                <div class="registration-steps-list">
                    <div class="step-item-card">
                        <div class="step-number-circle">1</div>
                        <div class="step-text-wrap">
                            <h4>إنشاء طلب التسجيل الإلكتروني واختيار التخصص</h4>
                            <p>تعبئة البيانات الشخصية والأكاديمية للطالب، رقم الجلوس، واختيار الكلية والتخصص المطلوب في {{ $university->university_name }} مباشرة.</p>
                        </div>
                    </div>

                    <div class="step-item-card">
                        <div class="step-number-circle">2</div>
                        <div class="step-text-wrap">
                            <h4>رفع الوثائق والمستندات المطلوبة (REG_DOC)</h4>
                            <p>إرفاق صورة كشف علامات الثانوية العامة، البطاقة الشخصية، وفحص اللياقة لتتم مراجعتها وتدقيقها إلكترونياً من نيابة شؤون الطلاب.</p>
                        </div>
                    </div>

                    <div class="step-item-card">
                        <div class="step-number-circle">3</div>
                        <div class="step-text-wrap">
                            <h4>استكمال إجراءات التسجيل بعد القبول المبدئي</h4>
                            <p>بعد مراجعة الطلب واعتماده، يتم إشعار الطالب وإتاحة بيانات سداد الرسوم وحجز المقعد في التخصص المختار.</p>
                        </div>
                    </div>

                    <div class="step-item-card">
                        <div class="step-number-circle">4</div>
                        <div class="step-text-wrap">
                            <h4>استلام رقم القيد وإشعار القبول النهائي</h4>
                            <p>متابعة حالة الطلب لحظياً حتى صدور قرار القبول النهائي والرقم الجامعي عبر المنصة ورسائل التنبيه SMS.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    @if(isset($isPreview) && $isPreview)
                    <button type="button" class="btn-hero-cta" style="width:auto; padding:0 36px; background:#e4b75b; color:#072644;" onclick="alert('أنت في وضع المعاينة الخاص بإدارة الجامعة. التقديم متاح للطلبة فقط.')">
                        <i class="bi bi-eye-fill me-1"></i>
                        <span>معاينة التسجيل الإلكتروني (للطلبة فقط)</span>
                    </button>
                    @elseif($university->status === 'مغلق')
                    <button type="button" class="btn-hero-cta" style="width:auto; padding:0 36px; background:#64748b; cursor:not-allowed;" disabled title="القبول مغلق حالياً">
                        <i class="bi bi-lock-fill me-1"></i>
                        <span>القبول والتسجيل مغلق حالياً</span>
                    </button>
                    @else
                    <button type="button" class="btn-hero-cta" style="width:auto; padding:0 36px;" onclick="openApplyModal()">
                        <i class="bi bi-send-check-fill me-1"></i>
                        <span>تقديم طلب التسجيل الإلكتروني الآن</span>
                    </button>
                    @endif
                </div>
            </section>

            {{-- قسم عن الجامعة والاعتمادات المؤسسية --}}
            <section class="section-block" id="aboutSection">
                <div class="section-head-title">
                    <div class="section-title-start">
                        <span>عن الجامعة والاعتمادات المؤسسية</span>
                    </div>
                    <span class="badge bg-light text-secondary border px-2 py-1" style="font-size:0.75rem;">
                        <i class="bi bi-award-fill text-warning me-1"></i>جودة أكاديمية
                    </span>
                </div>
                
                @if($university->isItemQuarantined('description'))
                <div class="alert" style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:18px 22px; color:#991b1b; display:flex; align-items:flex-start; gap:14px; margin-bottom:20px;">
                    <i class="fa-solid fa-shield-virus" style="font-size:24px; color:#dc2626; margin-top:2px;"></i>
                    <div>
                        <strong style="display:block; font-size:14.5px; margin-bottom:4px; color:#991b1b;">تنبيه رقابي: النبذة والبيانات التعريفية محجوبة مؤقتاً</strong>
                        <p style="font-size:13px; color:#b91c1c; margin:0; line-height:1.6;">تم إيقاف عرض النبذة والتعريف الخاص بالجامعة بقرار إشرافي صادر من إدارة الرقابة والحوكمة لإلزام الجامعة بتصحيح وتحديث بياناتها بما يتوافق مع المعايير واللوائح الرسمية المقرة من وزارة التعليم العالي والبحث العلمي.</p>
                    </div>
                </div>
                @else
                <p style="font-size: 0.94rem; color: var(--uni-text-dark); line-height: 1.85; margin-bottom: 16px;">
                    {{ $university->description ?? 'تعتبر الجامعة إحدى أبرز المؤسسات الأكاديمية الرائدة في الجمهورية اليمنية، وتلتزم بتقديم تعليم جامعي نوعي يجمع بين التأصيل العلمي والتطبيق العملي. تمتلك الجامعة كادراً تدريسياً متميزاً، وبنية تحتية متطورة تشمل قاعات دراسية مجهزة، ومختبرات تخصصية ومستشفيات تدريبية، وتخضع جميع برامجها لمعايير مجلس الاعتماد الأكاديمي وضمان جودة التعليم العالي.' }}
                </p>
                @endif

                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border d-flex align-items-center gap-3">
                            <i class="bi bi-patch-check-fill text-success fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size:0.9rem;">الاعتماد البرامجي والمؤسسي</h6>
                                <span class="small text-muted">برامج أكاديمية مرخصة ومطابقة لوزارة التعليم العالي</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border d-flex align-items-center gap-3">
                            <i class="bi bi-building-gear text-primary fs-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size:0.9rem;">معامل وبنية تحتية ذكية</h6>
                                <span class="small text-muted">مختبرات تخصصية، مكتبات ورقية وإلكترونية حديثة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        {{-- العمود الأيسر الجانبي (زر تفاصيل الإحالة + التواصل) --}}
        <div class="col-lg-4">
            
            {{-- زر وتوضيح نظام الإحالة للطلاب الراغبين في الحضور الشخصي --}}
            <div class="referral-prompt-widget">
                <div class="referral-prompt-title">
                    <i class="bi bi-person-walking fs-5"></i>
                    <span>ترغب بالتسجيل المباشر بمقر الجامعة؟</span>
                </div>
                <p class="referral-prompt-desc">
                    إذا كنت تفضل زيارة الجامعة والتسجيل شخصياً، يمكنك طلب رمز إحالة معتمد من EduYemen يمنحك خصماً جامعياً خاصاً لدى نيابة شؤون الطلاب.
                </p>
                @if(isset($isPreview) && $isPreview)
                <button type="button" class="btn-referral-prompt-action" style="opacity:0.65; cursor:not-allowed;" disabled title="متاح للطلبة فقط (وضع معاينة)">
                    <i class="bi bi-eye me-1"></i>
                    <span>طلب رمز الخصم (متاح للطلبة فقط)</span>
                </button>
                @else
                <button type="button" class="btn-referral-prompt-action" onclick="openReferralModal()">
                    <i class="bi bi-ticket-perforated-fill me-1"></i>
                    <span>تفاصيل الإحالة وطلب رمز الخصم</span>
                </button>
                @endif
            </div>

            {{-- بطاقة مقر الجامعة وأرقام التواصل --}}
            <div class="executive-info-card" id="contactSection">
                <h3 class="executive-card-title">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    <span>مقر الجامعة وأرقام التواصل</span>
                </h3>
                
                <div style="font-size: 0.88rem; color: var(--uni-text-dark); line-height: 1.8;">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-pin-map text-primary mt-1"></i>
                        <div>
                            <strong>العنوان:</strong>
                            <div class="text-muted">{{ $university->address ? $university->address . ' — ' : '' }}صنعاء، {{ $university->district }}</div>
                        </div>
                    </div>

                    @if($university->contact_phone)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-telephone-outbound text-success"></i>
                        <div>
                            <strong>هاتف التنسيق والقبول:</strong>
                            <a href="tel:{{ $university->contact_phone }}" class="text-decoration-none fw-bold" dir="ltr" style="color:var(--uni-blue);">
                                {{ $university->contact_phone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($university->phone && $university->phone !== $university->contact_phone)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-telephone text-secondary"></i>
                        <div>
                            <strong>هاتف الاستعلامات:</strong>
                            <span dir="ltr" class="text-muted">{{ $university->phone }}</span>
                        </div>
                    </div>
                    @endif

                    @if($university->email)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-envelope-at text-info"></i>
                        <div>
                            <strong>البريد الإلكتروني:</strong>
                            <a href="mailto:{{ $university->email }}" class="text-muted text-decoration-none">
                                {{ $university->email }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($university->website)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-globe text-primary"></i>
                        <div>
                            <strong>الموقع الإلكتروني:</strong>
                            <a href="{{ $university->website }}" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">
                                {{ parse_url($university->website, PHP_URL_HOST) ?? $university->website }} <i class="bi bi-box-arrow-up-right small"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="d-grid gap-2 pt-2 border-top">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($university->university_name . ' ' . $university->address . ' صنعاء') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="btn btn-outline-primary btn-sm fw-bold">
                            <i class="bi bi-map me-1"></i>فتح في خرائط Google
                        </a>
                        @if($university->contact_phone)
                        <a href="https://wa.me/967{{ preg_replace('/[^0-9]/', '', $university->contact_phone) }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="btn btn-outline-success btn-sm fw-bold">
                            <i class="bi bi-whatsapp me-1"></i>مراسلة عبر واتساب
                        </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- ====== 4. النوافذ المنبثقة التفاعلية (Modals) ====== --}}

{{-- مودال 2: تفاصيل القبول والشروط والرسوم للتخصص --}}
<div class="custom-modal-overlay" id="majorDetailsModal">
    <div class="custom-modal-card" style="max-width: 650px;">
        <div class="modal-head">
            <h3 class="modal-title-text">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                <span id="modalMajorTitle">تفاصيل التخصص</span>
            </h3>
            <button type="button" class="btn-modal-close" onclick="closeCustomModal('majorDetailsModal')" aria-label="إغلاق">✕</button>
        </div>

        <div class="p-1">
            <div class="alert alert-info py-2 px-3 d-flex align-items-center justify-content-between mb-3" style="font-size:0.85rem;">
                <div>
                    <i class="bi bi-building me-1"></i>
                    <span>الكلية: <strong id="modalCollegeTitle">—</strong></span>
                </div>
                <span class="badge" id="modalMajorStatusBadge">مفتوح للتقديم</span>
            </div>

            <!-- بطاقات المؤشرات الرقمية والرسوم المعتمدة -->
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded-3 bg-light text-center h-100">
                        <div class="small text-muted mb-1"><i class="bi bi-award me-1"></i>الحد الأدنى للقبول</div>
                        <div class="fw-bold fs-5 text-primary" id="modalMajorAvg">—</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded-3 bg-light text-center h-100">
                        <div class="small text-muted mb-1"><i class="bi bi-cash-stack me-1"></i>الرسوم الدراسية</div>
                        <div class="fw-bold fs-6 text-success" id="modalMajorFees">—</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded-3 bg-light text-center h-100">
                        <div class="small text-muted mb-1"><i class="bi bi-wallet2 me-1"></i>رسوم التسجيل</div>
                        <div class="fw-bold fs-6 text-dark" id="modalMajorRegFee">—</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded-3 bg-light text-center h-100">
                        <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i>سنة الثانوية</div>
                        <div class="fw-bold fs-6 text-dark" id="modalMajorYear">—</div>
                    </div>
                </div>
            </div>

            <!-- فترة القبول والتسجيل -->
            <div class="p-2 px-3 border rounded-3 bg-light mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="font-size:0.85rem;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span class="text-muted">فترة القبول والتسجيل:</span>
                    <strong class="text-dark" id="modalMajorPeriod">—</strong>
                </div>
                <div class="text-muted" style="font-size:0.8rem;">
                    <i class="bi bi-shield-check text-success me-1"></i>وفق نظام القبول الموحد
                </div>
            </div>

            <!-- شروط وضوابط القبول -->
            <div class="mb-3">
                <h6 class="fw-bold mb-2" style="font-size:0.9rem;">
                    <i class="bi bi-file-earmark-text text-primary me-1"></i>شروط وضوابط القبول والتسجيل ومعايير المفاضلة:
                </h6>
                <div class="p-3 bg-light rounded-3 border" style="font-size:0.86rem; line-height:1.7;" id="modalMajorConditions">
                    —
                </div>
            </div>

            <!-- إرشادات إضافية -->
            <div class="mb-3" id="modalMajorDescWrap" style="display:none;">
                <h6 class="fw-bold mb-2" style="font-size:0.9rem;">
                    <i class="bi bi-info-circle text-info me-1"></i>إرشادات وتوضيحات إضافية:
                </h6>
                <div class="p-3 bg-light rounded-3 border text-muted" style="font-size:0.84rem; line-height:1.6;" id="modalMajorDesc">
                    —
                </div>
            </div>

            <!-- الوثائق المطلوبة -->
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-paperclip text-primary me-1"></i>الوثائق المطلوبة للتسجيل:
                    </h6>
                    <span class="badge bg-secondary-subtle text-secondary" id="modalDocsCountBadge">0 وثائق</span>
                </div>
                <ul class="list-group list-group-flush border rounded-3" id="modalMajorDocsList" style="font-size:0.84rem; max-height:220px; overflow-y:auto;">
                    {{-- سيتم تعبئتها ديناميكياً --}}
                </ul>
            </div>

            <div class="d-flex gap-2 mt-4 pt-2 border-top">
                <button type="button" class="btn btn-secondary flex-grow-1" onclick="closeCustomModal('majorDetailsModal')">
                    إغلاق
                </button>
                <button type="button" class="btn-hero-cta flex-grow-2 justify-content-center" id="modalMajorApplyBtn">
                    <i class="bi bi-send-fill me-1"></i>
                    <span>التسجيل في هذا التخصص الآن</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- مودال 3: نافذة عرض الخطة الدراسية التفاعلية --}}
<div class="custom-modal-overlay" id="studyPlanModal">
    <div class="custom-modal-card" style="max-width: 780px;">
        <div class="modal-head">
            <h3 class="modal-title-text">
                <i class="bi bi-journal-bookmark-fill text-primary"></i>
                <span>الخطة الدراسية المعتمدة: <span id="planMajorTitle" class="text-primary"></span></span>
            </h3>
            <button type="button" class="btn-modal-close" onclick="closeCustomModal('studyPlanModal')" aria-label="إغلاق">✕</button>
        </div>

        <div>
            {{-- شريط ملخص الخطة --}}
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 border mb-3 flex-wrap gap-2">
                <div>
                    <span class="text-muted small">الكلية:</span>
                    <strong id="planCollegeTitle" class="text-dark small me-2">—</strong>
                    <span class="badge bg-primary-subtle text-primary border me-2">درجة البكالوريوس</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border">
                        <i class="bi bi-check-circle me-1"></i>خطة أكاديمية معتمدة
                    </span>
                    <span class="badge bg-warning-subtle text-dark border" id="planTotalHoursBadge">
                        136 ساعة معتمدة
                    </span>
                </div>
            </div>

            {{-- أزرار التنقل بين السنوات الدراسية --}}
            <ul class="nav nav-pills mb-3 gap-2" id="planYearsTabList" role="tablist">
                {{-- تُولَّد ديناميكياً --}}
            </ul>

            {{-- حاوية مقررات الفصول --}}
            <div id="planSemestersContainer">
                {{-- تُولَّد ديناميكياً بواسطة JavaScript --}}
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>طباعة الخطة الدراسية
                </button>
                <button type="button" class="btn-hero-cta" style="height:38px; padding:0 20px;" id="planApplyNowBtn">
                    <i class="bi bi-send-fill me-1"></i>التقديم في هذا التخصص
                </button>
            </div>
        </div>
    </div>
</div>

{{-- مودال 4: نافذة توضيح وتفاصيل نظام الإحالة وإصدار رمز الخصم --}}
<div class="custom-modal-overlay" id="referralModal">
    <div class="custom-modal-card" style="max-width: 540px;">
        <div class="modal-head">
            <h3 class="modal-title-text mx-auto">
                <i class="bi bi-ticket-perforated-fill text-warning"></i>
                <span>تفاصيل الإحالة والخصم الطلابي المعتمد</span>
            </h3>
            <button type="button" class="btn-modal-close" onclick="closeCustomModal('referralModal')" aria-label="إغلاق">✕</button>
        </div>

        <div>
            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                <span>ميزة حصرية للطلاب الراغبين بالتسجيل المباشر بمقر <strong>{{ $university->university_name }}</strong></span>
            </div>

            <h6 class="fw-bold mb-2" style="font-size:0.9rem;">
                <i class="bi bi-list-check text-primary me-1"></i>كيف تستفيد من الخصم بواسطة رمز الإحالة؟
            </h6>
            <ol class="small text-muted mb-3 pe-3" style="line-height:1.7;">
                <li>قم بنسخ وحفظ رمز الإحالة الفريد الظاهر أدناه.</li>
                <li>توجّه إلى نيابة شؤون الطلاب أو وحدة القبول والتسجيل في مقر الجامعة.</li>
                <li>أبرز الرمز لمسؤول التسجيل لإثبات قدومك عبر منصة EduYemen والحصول على الخصم الجامعي المقرر.</li>
                <li>يتم اعتماد تسجيلك وتوثيق الخصم في إيصال السداد الرسمي.</li>
            </ol>

            <div style="background: #f8fafc; border: 2px dashed var(--uni-gold); border-radius: 12px; padding: 18px; margin-bottom: 18px; text-align: center;">
                <div style="font-size: 0.8rem; color: var(--uni-text-muted);">
                    رمز الإحالة الخاص بك لـ <strong style="color:var(--uni-navy);">{{ $university->university_name }}</strong>:
                </div>
                <div style="font-size: 1.8rem; font-weight: 900; color: var(--uni-navy); letter-spacing: 2px; margin: 8px 0;" id="generatedRefCodeBox">
                    EDU-REF-{{ $university->university_id }}{{ rand(100, 999) }}
                </div>
                <div style="font-size: 0.78rem; color: #16a34a; font-weight: 800;">
                    <i class="bi bi-check-circle-fill me-1"></i>كود صالح ومفعل بنظام القبول
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn-hero-cta w-100 justify-content-center" onclick="copyReferralCodeAction()">
                    <i class="bi bi-clipboard-check-fill me-1"></i>
                    <span>نسخ رمز الإحالة وحفظه</span>
                </button>
            </div>
        </div>
    </div>
</div>

</div> {{-- إغلاق preview-locked-wrap --}}

@endsection

@section('scripts')
<script>
    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    // فتح تفاصيل التخصص تلقائياً عند التوجيه برابط مباشر للتخصص
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const specParam = urlParams.get('spec') || urlParams.get('specialization_id');
        let targetCard = null;
        if (specParam) {
            targetCard = document.getElementById('major-card-' + specParam);
        } else if (window.location.hash && window.location.hash.startsWith('#major-card-')) {
            targetCard = document.querySelector(window.location.hash);
        }

        if (targetCard) {
            const accordion = targetCard.closest('.college-accordion-card');
            if (accordion) {
                accordion.classList.add('open');
            }
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetCard.style.boxShadow = '0 0 0 3px var(--uni-teal)';
            const btn = targetCard.querySelector('.btn-admission-details');
            if (btn) {
                setTimeout(() => btn.click(), 350);
            }
        }
    });

    // تبديل الأقسام عبر شريط التبويبات العلوي بسلاسة
    function switchSectionTab(btn, sectionId) {
        document.querySelectorAll('.tab-nav-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const targetEl = document.getElementById(sectionId);
        if (targetEl) {
            targetEl.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // فتح وإغلاق أكورديون الكلية
    function toggleCollegeCard(btn) {
        const card = btn.closest('.college-accordion-card');
        if (card) {
            card.classList.toggle('open');
        }
    }

    // فلترة التخصصات بحسب الكلية مع الحفاظ على فتح أول كلية عند اختيار 'الكل'
    function filterColleges(groupKey, btn) {
        document.querySelectorAll('.pill-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const cards = document.querySelectorAll('.college-accordion-card');
        cards.forEach((card, idx) => {
            if (groupKey === 'all') {
                card.style.display = 'block';
                if (idx === 0) {
                    card.classList.add('open');
                } else {
                    card.classList.remove('open');
                }
            } else if (card.getAttribute('data-college-group') === groupKey) {
                card.style.display = 'block';
                card.classList.add('open');
            } else {
                card.style.display = 'none';
            }
        });
    }

    // الانتقال إلى استمارة التقديم الإلكتروني الرسمية مع تحديد الجامعة والتخصص تلقائياً
    function openApplyModal(majorName) {
        let url = "{{ route('apply.direct') }}?university_id={{ $university->university_id }}";
        if (majorName) {
            url += "&major=" + encodeURIComponent(majorName);
        }
        window.location.href = url;
    }

    // فتح نافذة تفاصيل القبول
    function openMajorDetailsModal(name, college, minAvg, fees, regFee, conditions, year, period, status, description, docs) {
        // إذا تم استدعاؤها بالنظام القديم (7 معامِلات)
        if (arguments.length <= 7) {
            docs = year;
            year = conditions;
            conditions = regFee;
            regFee = '5,000 ر.ي';
            period = 'فترة القبول المعتمدة';
            status = 'مفتوح للتقديم';
            description = '';
        }

        document.getElementById('modalMajorTitle').textContent = name || 'تفاصيل التخصص';
        document.getElementById('modalCollegeTitle').textContent = college || '—';
        document.getElementById('modalMajorAvg').textContent = minAvg || '—';
        document.getElementById('modalMajorFees').textContent = fees || '—';
        if (document.getElementById('modalMajorRegFee')) {
            document.getElementById('modalMajorRegFee').textContent = regFee || '5,000 ر.ي';
        }
        document.getElementById('modalMajorConditions').textContent = conditions || 'استيفاء شروط القبول والتسجيل المعتمدة.';
        document.getElementById('modalMajorYear').textContent = year || '2021 فما بعد';
        
        if (document.getElementById('modalMajorPeriod')) {
            document.getElementById('modalMajorPeriod').textContent = period || 'طوال فترة التنسيق الجامعي';
        }
        
        const statusBadge = document.getElementById('modalMajorStatusBadge');
        if (statusBadge) {
            statusBadge.textContent = status || 'مفتوح للتقديم';
            statusBadge.className = 'badge ' + (status === 'مفتوح' || status === 'مفتوح للتقديم' ? 'bg-success' : (status === 'مغلق' ? 'bg-danger' : 'bg-warning text-dark'));
        }

        const descWrap = document.getElementById('modalMajorDescWrap');
        const descEl = document.getElementById('modalMajorDesc');
        if (descWrap && descEl) {
            if (description && description.trim() !== '') {
                descEl.textContent = description;
                descWrap.style.display = 'block';
            } else {
                descWrap.style.display = 'none';
            }
        }

        const docsUl = document.getElementById('modalMajorDocsList');
        docsUl.innerHTML = '';

        const docsArray = Array.isArray(docs) ? docs : [];
        const badgeCount = document.getElementById('modalDocsCountBadge');
        if (badgeCount) {
            badgeCount.textContent = docsArray.length + ' وثائق';
        }

        if (docsArray.length > 0) {
            docsArray.forEach(doc => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3';
                const isReq = (doc.is_required !== undefined) ? doc.is_required : true;
                const reqBadge = isReq 
                    ? '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-asterisk me-1"></i>إلزامية</span>' 
                    : '<span class="badge bg-secondary-subtle text-secondary border"><i class="bi bi-check2 me-1"></i>اختيارية</span>';
                
                const docName = escapeHtml(doc.name || doc.document_name || String(doc));
                const docDesc = doc.description ? `<div class="small text-muted mt-1">${escapeHtml(doc.description)}</div>` : '';

                li.innerHTML = `<div><div class="fw-semibold text-dark"><i class="bi bi-file-earmark-check text-primary me-2"></i>${docName}</div>${docDesc}</div> ${reqBadge}`;
                docsUl.appendChild(li);
            });
        } else {
            const defaultDocs = [
                { name: 'أصل استمارة الثانوية العامة', description: 'صورة طبق الأصل أو الأصل للمطابقة', is_required: true },
                { name: 'صورة البطاقة الشخصية أو جواز السفر', description: 'سارية المفعول', is_required: true },
                { name: 'صور شخصية حديثة خلفية بيضاء (4×6)', description: 'عدد 4 صور', is_required: true },
                { name: 'فحص اللياقة الطبية المخبري', description: 'للتخصصات الطبية والصحية', is_required: false }
            ];
            defaultDocs.forEach(doc => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3';
                const reqBadge = doc.is_required 
                    ? '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-asterisk me-1"></i>إلزامية</span>' 
                    : '<span class="badge bg-secondary-subtle text-secondary border"><i class="bi bi-check2 me-1"></i>اختيارية</span>';
                li.innerHTML = `<div><div class="fw-semibold text-dark"><i class="bi bi-file-earmark-check text-primary me-2"></i>${doc.name}</div><div class="small text-muted mt-1">${doc.description}</div></div> ${reqBadge}`;
                docsUl.appendChild(li);
            });
        }

        const applyBtn = document.getElementById('modalMajorApplyBtn');
        applyBtn.onclick = function() {
            closeCustomModal('majorDetailsModal');
            openApplyModal(name);
        };

        document.getElementById('majorDetailsModal').classList.add('active');
    }

    // فتح نافذة تفاصيل الإحالة وتوليد الرمز المعتمد للجامعة
    function openReferralModal() {
        @if(isset($isPreview) && $isPreview)
            alert('أنت في وضع المعاينة الخاص بإدارة الجامعة. خدمة إصدار وتفعيل رموز الإحالة والخصم مخصصة لحسابات الطلبة فقط.');
            return;
        @endif
        @if(!auth()->check())
            if (confirm('خدمة إصدار وتفعيل رمز الإحالة المعتمد تتطلب تسجيل الدخول بحساب طالب، هل ترغب في تسجيل الدخول الآن؟')) {
                window.location.href = "{{ route('login') }}";
            }
            return;
        @endif
        document.getElementById('referralModal').classList.add('active');
        fetch('{{ route("student.referral.create_univ") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ university_id: {{ $university->university_id }} })
        }).then(r => r.json()).then(data => {
            if(data.referral_code) {
                const box = document.getElementById('generatedRefCodeBox');
                if (box) box.textContent = data.referral_code;
            }
        }).catch(err => console.log('Referral fetched'));
    }

    // نسخ رمز الإحالة
    function copyReferralCodeAction() {
        const code = document.getElementById('generatedRefCodeBox').textContent.trim();
        navigator.clipboard.writeText(code).then(() => {
            alert(`تم نسخ رمز الإحالة [ ${code} ] بنجاح!\nاحفظ هذا الرمز وقدّمه لمسؤول القبول والتسجيل للاستفادة من الخصم.`);
            closeCustomModal('referralModal');
            const refInput = document.getElementById('formReferralInput');
            if (refInput) refInput.value = code;
        }).catch(() => {
            alert(`رمز الإحالة هو: ${code}`);
        });
    }

    // ====== بيانات وتوليد الخطة الدراسية التفاعلية لكل تخصص ======
    function getCurriculumData(majorName, yearsCount) {
        const isMed = majorName.includes('طب') || majorName.includes('صيدل') || majorName.includes('أسنان') || majorName.includes('مختبر');
        const isTech = majorName.includes('حاسوب') || majorName.includes('برمج') || majorName.includes('تقني') || majorName.includes('أمن') || majorName.includes('ذكاء') || majorName.includes('IT') || majorName.includes('CS');
        const isBiz = majorName.includes('إدار') || majorName.includes('محاسب') || majorName.includes('مال') || majorName.includes('تسويق');

        let years = [];
        const numYears = parseInt(yearsCount) || 4;

        for (let y = 1; y <= numYears; y++) {
            let yearData = {
                yearNum: y,
                yearTitle: `السنة الدراسية ${y == 1 ? 'الأولى' : (y == 2 ? 'الثانية' : (y == 3 ? 'الثالثة' : (y == 4 ? 'الرابعة' : 'الخامسة')))}`,
                semesters: []
            };

            const sem1Num = (y - 1) * 2 + 1;
            const sem2Num = (y - 1) * 2 + 2;

            if (isTech) {
                if (y === 1) {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'MATH101', name: 'تفاضل وتكامل 1', hours: 3, type: 'إجباري' },
                            { code: 'PROG101', name: 'مبادئ البرمجة المنظمة (C++)', hours: 4, type: 'إجباري' },
                            { code: 'IT101', name: 'مقدمة في تكنولوجيا المعلومات', hours: 3, type: 'إجباري' },
                            { code: 'ENG101', name: 'اللغة الإنجليزية التخصصية 1', hours: 2, type: 'متطلب' },
                            { code: 'SKIL101', name: 'مهارات الحاسوب والإنترنت', hours: 2, type: 'متطلب' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'MATH102', name: 'جبر خطي وتراكيب متقطعة', hours: 3, type: 'إجباري' },
                            { code: 'PROG102', name: 'برمجة كائنية التوجه (OOP)', hours: 4, type: 'إجباري' },
                            { code: 'ELEC102', name: 'مبادئ الإلكترونيات الرقمية', hours: 3, type: 'إجباري' },
                            { code: 'ENG102', name: 'اللغة الإنجليزية التخصصية 2', hours: 2, type: 'متطلب' },
                            { code: 'STAT102', name: 'احتمالات وإحصاء تطبيقي', hours: 3, type: 'إجباري' }
                        ]}
                    ];
                } else if (y === 2) {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'DS201', name: 'هياكل البيانات والخوارزميات', hours: 4, type: 'إجباري' },
                            { code: 'DB201', name: 'أسس أنظمة قواعد البيانات (SQL)', hours: 4, type: 'إجباري' },
                            { code: 'NET201', name: 'أساسيات شبكات الحاسوب', hours: 3, type: 'إجباري' },
                            { code: 'ARCH201', name: 'معمارية وتنظيم الحاسوب', hours: 3, type: 'إجباري' },
                            { code: 'WEB201', name: 'تطوير واجهات الويب (Frontend)', hours: 3, type: 'إجباري' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'OS202', name: 'نظم التشغيل (Linux/Windows)', hours: 3, type: 'إجباري' },
                            { code: 'SE202', name: 'تحليل وتصميم النظم والبرمجيات', hours: 3, type: 'إجباري' },
                            { code: 'DB202', name: 'إدارة قواعد البيانات المتقدمة', hours: 3, type: 'إجباري' },
                            { code: 'NET202', name: 'أجهزة وتوجيه الشبكات (Routing)', hours: 3, type: 'إجباري' },
                            { code: 'WEB202', name: 'تطوير تطبيقات الويب الكاملة', hours: 3, type: 'إجباري' }
                        ]}
                    ];
                } else if (y === 3) {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'SEC301', name: 'أمن المعلومات والأنظمة السيبرانية', hours: 3, type: 'إجباري' },
                            { code: 'AI301', name: 'أسس الذكاء الاصطناعي وتعلم الآلة', hours: 3, type: 'إجباري' },
                            { code: 'MOB301', name: 'برمجة تطبيقات الهاتف الذكي', hours: 3, type: 'إجباري' },
                            { code: 'PM301', name: 'إدارة مشاريع تكنولوجيا المعلومات', hours: 3, type: 'إجباري' },
                            { code: 'ETH301', name: 'أخلاقيات وقوانين تكنولوجيا المعلومات', hours: 2, type: 'متطلب' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'CLOUD302', name: 'الحوسبة السحابية والنظم الموزعة', hours: 3, type: 'إجباري' },
                            { code: 'SEC302', name: 'التحري الرقمي والاستجابة للحوادث', hours: 3, type: 'إجباري' },
                            { code: 'DATA302', name: 'علم وهندسة البيانات الضخمة', hours: 3, type: 'اختياري' },
                            { code: 'TEST302', name: 'جودة وضمان البرمجيات', hours: 3, type: 'اختياري' },
                            { code: 'INTERN302', name: 'تدريب ميداني صيفي معتمد', hours: 2, type: 'إجباري' }
                        ]}
                    ];
                } else {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'PROJ401', name: 'مشروع التخرج الأكاديمي 1 (دراسة وتحليل)', hours: 3, type: 'إجباري' },
                            { code: 'DEVOPS401', name: 'هندسة العمليات والتحول السحابي (DevOps)', hours: 3, type: 'إجباري' },
                            { code: 'IOT401', name: 'إنترنت الأشياء والنظم المدمجة', hours: 3, type: 'اختياري' },
                            { code: 'ADV401', name: 'موضوعات متقدمة في التخصص', hours: 3, type: 'اختياري' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'PROJ402', name: 'مشروع التخرج الأكاديمي 2 (تطوير وتطبيق)', hours: 4, type: 'إجباري' },
                            { code: 'ENTR402', name: 'ريادة الأعمال والابتكار التكنولوجي', hours: 2, type: 'متطلب' },
                            { code: 'GOV402', name: 'حوكمة تكنولوجيا المعلومات والتدقيق', hours: 3, type: 'إجباري' }
                        ]}
                    ];
                }
            } else if (isMed) {
                if (y === 1) {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'MED101', name: 'علم التشريح العام 1 (Anatomy)', hours: 4, type: 'إجباري' },
                            { code: 'PHY101', name: 'علم وظائف الأعضاء 1 (Physiology)', hours: 4, type: 'إجباري' },
                            { code: 'BIO101', name: 'الكيمياء الحيوية الطبية 1', hours: 3, type: 'إجباري' },
                            { code: 'ENG101', name: 'المصطلحات الطبية واللغة الإنجليزية', hours: 2, type: 'متطلب' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'MED102', name: 'علم التشريح والأنسجة 2 (Histology)', hours: 4, type: 'إجباري' },
                            { code: 'PHY102', name: 'علم وظائف الأعضاء 2', hours: 4, type: 'إجباري' },
                            { code: 'MIC102', name: 'علم الأحياء الدقيقة والمناعة', hours: 3, type: 'إجباري' },
                            { code: 'MED102E', name: 'أخلاقيات الممارسة الطبية', hours: 2, type: 'متطلب' }
                        ]}
                    ];
                } else if (y === 2) {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'PATH201', name: 'علم الأمراض العام (Pathology)', hours: 4, type: 'إجباري' },
                            { code: 'PHARM201', name: 'علم الأدوية 1 (Pharmacology)', hours: 4, type: 'إجباري' },
                            { code: 'PARA201', name: 'علم الطفيليات الطبية', hours: 3, type: 'إجباري' },
                            { code: 'GEN201', name: 'علم الوراثة الطبية', hours: 2, type: 'إجباري' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'PATH202', name: 'علم الأمراض السريري والتشخيصي', hours: 4, type: 'إجباري' },
                            { code: 'PHARM202', name: 'علم الأدوية السريري 2', hours: 4, type: 'إجباري' },
                            { code: 'COMM202', name: 'طب المجتمع وعلم الأوبئة', hours: 3, type: 'إجباري' },
                            { code: 'SKIL202', name: 'مهارات الفحص السريري الأولي', hours: 2, type: 'إجباري' }
                        ]}
                    ];
                } else {
                    yearData.semesters = [
                        { name: `الفصل الدراسي ${sem1Num}`, courses: [
                            { code: 'CLIN301', name: 'التدريب السريري التخصصي', hours: 5, type: 'إجباري' },
                            { code: 'DIAG301', name: 'التشخيص المخبري والأشعة', hours: 4, type: 'إجباري' },
                            { code: 'THER301', name: 'المعالجة الدوائية المتكاملة', hours: 4, type: 'إجباري' }
                        ]},
                        { name: `الفصل الدراسي ${sem2Num}`, courses: [
                            { code: 'HOSP302', name: 'التدريب السريري بالمستشفيات', hours: 6, type: 'إجباري' },
                            { code: 'QUAL302', name: 'سلامة المرضى ومكافحة العدوى', hours: 2, type: 'إجباري' },
                            { code: 'RES302', name: 'مشروع البحث السريري', hours: 3, type: 'إجباري' }
                        ]}
                    ];
                }
            } else {
                // إداري / مالي / إنساني
                yearData.semesters = [
                    { name: `الفصل الدراسي ${sem1Num}`, courses: [
                        { code: 'BUS101', name: 'مبادئ إدارة الأعمال الحديثة', hours: 3, type: 'إجباري' },
                        { code: 'ACC101', name: 'مبادئ المحاسبة المالية 1', hours: 3, type: 'إجباري' },
                        { code: 'ECON101', name: 'مبادئ الاقتصاد الجزئي', hours: 3, type: 'إجباري' },
                        { code: 'MATH101', name: 'رياضيات الأعمال والإحصاء', hours: 3, type: 'إجباري' },
                        { code: 'ENG101', name: 'لغة إنجليزية للأعمال 1', hours: 2, type: 'متطلب' }
                    ]},
                    { name: `الفصل الدراسي ${sem2Num}`, courses: [
                        { code: 'MKT102', name: 'مبادئ التسويق وسلوك المستهلك', hours: 3, type: 'إجباري' },
                        { code: 'ACC102', name: 'مبادئ المحاسبة المالية 2', hours: 3, type: 'إجباري' },
                        { code: 'ECON102', name: 'مبادئ الاقتصاد الكلي', hours: 3, type: 'إجباري' },
                        { code: 'LAW102', name: 'مبادئ القانون التجاري والشركات', hours: 3, type: 'إجباري' },
                        { code: 'ENG102', name: 'لغة إنجليزية للأعمال 2', hours: 2, type: 'متطلب' }
                    ]}
                ];
            }

            years.push(yearData);
        }

        return years;
    }

    // فتح نافذة الخطة الدراسية
    function openStudyPlanModal(majorName, collegeName, yearsCount) {
        document.getElementById('planMajorTitle').textContent = majorName;
        document.getElementById('planCollegeTitle').textContent = collegeName;
        
        const totalHours = majorName.includes('طب') ? '165 ساعة معتمدة' : (majorName.includes('صيدل') || majorName.includes('أسنان') ? '154 ساعة معتمدة' : '136 ساعة معتمدة');
        document.getElementById('planTotalHoursBadge').textContent = totalHours;

        const curriculum = getCurriculumData(majorName, yearsCount);
        const tabsList = document.getElementById('planYearsTabList');
        const container = document.getElementById('planSemestersContainer');

        tabsList.innerHTML = '';
        container.innerHTML = '';

        curriculum.forEach((yr, idx) => {
            // إضافة زر التبويب للسنة
            const li = document.createElement('li');
            li.className = 'nav-item';
            li.innerHTML = `
                <button class="nav-link ${idx === 0 ? 'active' : ''} fw-bold" 
                        style="font-size:0.82rem; border-radius:8px;" 
                        onclick="switchPlanYear(${idx}, this)">
                    ${yr.yearTitle}
                </button>
            `;
            tabsList.appendChild(li);

            // إضافة حاوية فصول السنة
            const yrDiv = document.createElement('div');
            yrDiv.id = `planYearContent-${idx}`;
            yrDiv.style.display = idx === 0 ? 'block' : 'none';

            let semsHtml = '';
            yr.semesters.forEach(sem => {
                let coursesRows = '';
                sem.courses.forEach(c => {
                    coursesRows += `
                        <tr>
                            <td class="fw-bold text-primary font-monospace" style="font-size:0.8rem;">${c.code}</td>
                            <td class="fw-semibold" style="font-size:0.84rem;">${c.name}</td>
                            <td class="text-center" style="font-size:0.82rem;">${c.hours}</td>
                            <td class="text-center"><span class="badge ${c.type === 'إجباري' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary'}" style="font-size:0.7rem;">${c.type}</span></td>
                        </tr>
                    `;
                });

                semsHtml += `
                    <div class="card border rounded-3 mb-3">
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark" style="font-size:0.86rem;"><i class="bi bi-calendar2-range text-primary me-2"></i>${sem.name}</span>
                            <span class="badge bg-white text-muted border" style="font-size:0.75rem;">${sem.courses.length} مقررات</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr style="font-size:0.76rem;">
                                        <th style="width:20%;">رمز المقرر</th>
                                        <th style="width:45%;">اسم المقرر الدراسي</th>
                                        <th style="width:15%;" class="text-center">الساعات</th>
                                        <th style="width:20%;" class="text-center">النوع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${coursesRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            });

            yrDiv.innerHTML = semsHtml;
            container.appendChild(yrDiv);
        });

        const applyBtn = document.getElementById('planApplyNowBtn');
        applyBtn.onclick = function() {
            closeCustomModal('studyPlanModal');
            openApplyModal(majorName);
        };

        document.getElementById('studyPlanModal').classList.add('active');
    }

    // تبديل السنة الدراسية داخل مودال الخطة
    function switchPlanYear(yearIndex, btn) {
        document.querySelectorAll('#planYearsTabList .nav-link').forEach(l => l.classList.remove('active'));
        btn.classList.add('active');

        const container = document.getElementById('planSemestersContainer');
        Array.from(container.children).forEach((child, i) => {
            child.style.display = i === yearIndex ? 'block' : 'none';
        });
    }

    // إغلاق أي مودال
    function closeCustomModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('active');
    }

    // إغلاق المودال عند النقر على الخلفية المعتمة
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('custom-modal-overlay')) {
            e.target.classList.remove('active');
        }
    });



    @if(isset($isPreview) && $isPreview)
    document.addEventListener('click', function(e) {
        // السماح فقط بزر العودة إلى لوحة التحكم أو فتح/إغلاق القائمة الجانبية للجامعة
        const returnBtn = e.target.closest('.btn-return-dashboard');
        const drawerBtn = e.target.closest('.btn-hamburger-menu, .btn-close-sidebar, #sidebarOverlay, .sidebar-drawer a, .sidebar-drawer button');
        if (returnBtn || drawerBtn) {
            return;
        }

        // اعتراض أي زر أو رابط آخر على الصفحة ومنعه
        const interactive = e.target.closest('a, button, input[type="submit"], input[type="button"]');
        if (interactive) {
            e.preventDefault();
            e.stopPropagation();
            alert('وضع المعاينة مخصص للاطلاع فقط، لا يمكن تنفيذ أي إجراء أو الانتقال لصفحات أخرى. للعودة انقر على "العودة إلى لوحة التحكم".');
        }
    }, true);
    @endif
</script>
@endsection
