@extends('dashboards.admin.layout')

@section('page_title', 'مركز الرقابة وحوكمة المحتوى الجامعي')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" style="color: rgba(255,255,255,0.7); text-decoration:none;">الرئيسية</a>
    <i class="fa-solid fa-angle-left" style="font-size: 11px; margin: 0 8px; color: rgba(255,255,255,0.4);"></i>
    <a href="{{ route('admin.universities') }}" style="color: rgba(255,255,255,0.7); text-decoration:none;">كآفة الجامعات</a>
    <i class="fa-solid fa-angle-left" style="font-size: 11px; margin: 0 8px; color: rgba(255,255,255,0.4);"></i>
    <strong>مركز الرقابة والحوكمة</strong>
@endsection

@section('extra_styles')
<style>
    /* =========================================================
       أنماط مركز الرقابة وحوكمة المحتوى الجامعي
       ========================================================= */
    :root {
        --mod-danger: #ef4444;
        --mod-danger-bg: #fef2f2;
        --mod-danger-border: #fecaca;
        --mod-success: #10b981;
        --mod-success-bg: #ecfdf5;
        --mod-success-border: #a7f3d0;
        --mod-warning: #f59e0b;
        --mod-warning-bg: #fffbeb;
        --mod-card-bg: #ffffff;
        --mod-border: #e2e8f0;
        --mod-text-main: #0f172a;
        --mod-text-muted: #64748b;
    }

    .mod-hero-banner {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 60%, #0f4c81 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #ffffff;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(7, 43, 79, 0.25);
        position: relative;
        overflow: hidden;
    }

    .mod-hero-banner::after {
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

    .mod-hero-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        position: relative;
        z-index: 2;
    }

    .mod-hero-title-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .mod-hero-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        flex-shrink: 0;
    }

    .mod-hero-title-group h1 {
        font-size: 21px;
        font-weight: 800;
        margin: 0 0 6px 0;
        color: #ffffff;
    }

    .mod-hero-title-group p {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        max-width: 680px;
        line-height: 1.5;
    }

    .mod-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-mod-light {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-mod-light:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* KPI Cards */
    .mod-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .mod-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid var(--mod-border);
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .mod-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }

    .mod-stat-info h4 {
        margin: 0 0 4px 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--mod-text-muted);
    }

    .mod-stat-info .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--mod-text-main);
        line-height: 1.2;
    }

    .mod-stat-badge {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    /* Tabs Bar */
    .mod-tabs-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 22px;
        padding-bottom: 4px;
        overflow-x: auto;
    }

    .mod-tab-btn {
        background: transparent;
        border: none;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 700;
        color: var(--mod-text-muted);
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .mod-tab-btn:hover {
        color: var(--primary);
        background: #f1f5f9;
    }

    .mod-tab-btn.active {
        color: #ffffff;
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%);
        box-shadow: 0 3px 10px rgba(7, 43, 79, 0.2);
    }

    .mod-tab-btn .tab-count-badge {
        padding: 2px 7px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
    }

    .mod-tab-btn.active .tab-count-badge {
        background: rgba(255,255,255,0.25);
        color: #ffffff;
    }

    .mod-tab-btn:not(.active) .tab-count-badge {
        background: #e2e8f0;
        color: #475569;
    }

    /* Tab Contents */
    .mod-tab-pane {
        display: none;
    }

    .mod-tab-pane.active {
        display: block;
        animation: fadeIn 0.25s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Auditor Section */
    .auditor-header-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 18px 22px;
        border: 1px solid var(--mod-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .auditor-univ-select {
        min-width: 300px;
        padding: 10px 14px;
        border-radius: 9px;
        border: 1.5px solid #cbd5e1;
        font-size: 14.5px;
        font-weight: 600;
        color: #0f172a;
        background-color: #f8fafc;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .auditor-univ-select:focus {
        border-color: #0b3d6b;
        background-color: #ffffff;
    }

    /* =========================================================
       القائمة المنسدلة القابلة للبحث الفوري (Searchable Combobox)
       ========================================================= */
    .searchable-combobox-wrapper {
        position: relative;
        min-width: 340px;
        max-width: 440px;
        flex: 1 1 340px;
    }

    .combobox-input-group {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .combobox-input-group:focus-within {
        border-color: #0b3d6b;
        box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.12);
        background: #ffffff;
    }

    .combobox-search-icon {
        position: absolute;
        right: 13px;
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        transition: color 0.2s;
    }

    .combobox-input-group:focus-within .combobox-search-icon {
        color: #0b3d6b;
    }

    .combobox-input {
        width: 100%;
        padding: 10px 38px 10px 65px;
        border: none;
        outline: none;
        background: transparent;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        box-sizing: border-box;
    }

    .combobox-input::placeholder {
        color: #94a3b8;
        font-weight: 500;
    }

    .combobox-clear-btn {
        position: absolute;
        left: 34px;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
        transition: all 0.15s;
    }

    .combobox-clear-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .combobox-toggle-btn {
        position: absolute;
        left: 8px;
        background: none;
        border: none;
        color: #64748b;
        padding: 6px 8px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, color 0.15s;
    }

    .combobox-toggle-btn:hover {
        color: #0b3d6b;
    }

    .combobox-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        left: 0;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
        z-index: 1050;
        overflow: hidden;
        animation: comboboxDropdownIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes comboboxDropdownIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .combobox-options-list {
        max-height: 270px;
        overflow-y: auto;
        padding: 6px;
    }

    .combobox-option-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
        gap: 8px;
        margin-bottom: 2px;
    }

    .combobox-option-item:hover,
    .combobox-option-item.highlighted {
        background: #f0f7ff;
        border-right: 3.5px solid #0b3d6b;
    }

    .combobox-option-item.selected {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
    }

    .combobox-option-item .opt-name {
        font-size: 13.5px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .combobox-option-item.selected .opt-name {
        color: #0369a1;
    }

    .badge-gov {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .badge-priv {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .combobox-no-results {
        padding: 16px;
        text-align: center;
        font-size: 13px;
        color: #64748b;
    }

    /* Assets Grid in Auditor */
    .audit-category-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 24px 0 14px 0;
        display: flex;
        align-items: center;
        gap: 9px;
        padding-right: 4px;
        border-right: 4px solid #0b3d6b;
    }

    .audit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }

    .audit-asset-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--mod-border);
        padding: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s;
    }

    .audit-asset-card.is-quarantined {
        border-color: var(--mod-danger-border);
        background: #fffafa;
    }

    .asset-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .asset-type-badge {
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
    }

    .asset-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 12px;
    }

    .asset-status-pill.active {
        background: var(--mod-success-bg);
        color: var(--mod-success);
        border: 1px solid var(--mod-success-border);
    }

    .asset-status-pill.quarantined {
        background: var(--mod-danger-bg);
        color: var(--mod-danger);
        border: 1px solid var(--mod-danger-border);
    }

    .asset-preview-box {
        background: #f8fafc;
        border-radius: 9px;
        padding: 12px;
        border: 1px dashed #cbd5e1;
        margin-bottom: 14px;
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .asset-preview-box img {
        max-height: 110px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 6px;
    }

    .asset-preview-text {
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
        width: 100%;
        max-height: 120px;
        overflow-y: auto;
    }

    .asset-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-quarantine-action {
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 7px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        border: none;
    }

    .btn-quarantine-action.suspend {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-quarantine-action.suspend:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    .btn-quarantine-action.resolve {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .btn-quarantine-action.resolve:hover {
        background: #d1fae5;
        color: #047857;
    }

    /* Quarantines Table */
    .mod-table-container {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid var(--mod-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .mod-table-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--mod-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: #f8fafc;
    }

    .mod-filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-btn-pill {
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 16px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s;
    }

    .filter-btn-pill.active {
        background: #0b3d6b;
        color: #ffffff;
        border-color: #0b3d6b;
    }

    .mod-search-input {
        padding: 6px 12px;
        font-size: 12.5px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        width: 220px;
        outline: none;
    }

    .mod-search-input:focus {
        border-color: #0b3d6b;
    }

    /* زر إشعار مراجعات وتعديلات الجامعات اللافت للانتباه */
    .btn-review-notice-pulse {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        position: relative;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        transition: all 0.25s ease;
        text-decoration: none;
        animation: pulseReviewShadow 2s infinite;
    }

    .btn-review-notice-pulse:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 22px rgba(245, 158, 11, 0.6);
        color: #ffffff !important;
    }

    .pulse-beacon {
        width: 10px;
        height: 10px;
        background-color: #ffffff;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }

    .pulse-beacon::after {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        animation: beaconWave 1.6s infinite ease-out;
    }

    @keyframes beaconWave {
        0% { transform: scale(0.6); opacity: 1; }
        100% { transform: scale(2.3); opacity: 0; }
    }

    @keyframes pulseReviewShadow {
        0%, 100% { box-shadow: 0 4px 14px rgba(245, 158, 11, 0.45); }
        50% { box-shadow: 0 4px 24px rgba(245, 158, 11, 0.8); }
    }

    /* حالة التنبيه لتحديد الجامعة */
    .auditor-select-prompt {
        background: #ffffff;
        border-radius: 14px;
        border: 2px dashed #cbd5e1;
        padding: 45px 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .prompt-icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #0b3d6b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .auditor-select-prompt h3 {
        margin: 0 0 6px 0;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .auditor-select-prompt p {
        margin: 0;
        font-size: 13.5px;
        color: #64748b;
        max-width: 480px;
        line-height: 1.6;
    }

    .mod-table-container {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 12px;
        border: 1px solid var(--mod-border);
        background: #ffffff;
        margin-bottom: 24px;
    }

    .mod-table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: right;
    }

    .mod-table th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        padding: 12px 14px;
        border-bottom: 1px solid #cbd5e1;
        white-space: nowrap;
    }

    .mod-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }

    .mod-table tr:hover {
        background-color: #f8fafc;
    }

    .row-pending-review {
        background-color: #fffdf5 !important;
        border-right: 3px solid #f59e0b;
    }

    /* تحسين العرض والتجاوب للشاشات الصغيرة */
    @media (max-width: 768px) {
        .col-hide-mobile {
            display: none !important;
        }

        .mod-hero-banner {
            padding: 16px 18px !important;
            margin-bottom: 16px !important;
        }

        .mod-hero-title-group h1 {
            font-size: 17px !important;
        }

        .mod-hero-actions {
            width: 100% !important;
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
        }

        .btn-review-notice-pulse {
            width: 100% !important;
            justify-content: center !important;
            padding: 9px 14px !important;
        }

        .auditor-header-card {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 14px 16px !important;
            gap: 12px !important;
        }

        .auditor-univ-select,
        .searchable-combobox-wrapper {
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .mod-table-container {
            overflow-x: hidden !important; /* منع تجاوز عرض الشاشة */
            border-radius: 8px !important;
        }

        .mod-table {
            table-layout: auto !important;
            font-size: 11.5px !important;
            width: 100% !important;
        }

        .mod-table th, .mod-table td {
            padding: 8px 6px !important;
            white-space: normal !important;
            word-break: break-word !important;
        }

        .btn-quarantine-action {
            padding: 5px 8px !important;
            font-size: 11px !important;
            white-space: nowrap !important;
        }

        .audit-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* Modal Styling */
    .mod-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 2500;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .mod-modal-overlay.open {
        display: flex;
        animation: fadeIn 0.2s ease;
    }

    .mod-modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 580px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255,255,255,0.4);
        overflow: hidden;
    }

    .mod-modal-header {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%);
        color: #ffffff;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mod-modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .mod-modal-close {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.8);
        font-size: 18px;
        cursor: pointer;
    }

    .mod-modal-body {
        padding: 20px;
    }

    .mod-form-group {
        margin-bottom: 16px;
    }

    .mod-form-group label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .mod-form-control {
        width: 100%;
        padding: 9px 12px;
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .mod-form-control:focus {
        border-color: #0b3d6b;
    }

    .mod-modal-footer {
        padding: 14px 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Toast */
    .mod-toast {
        position: fixed;
        bottom: 24px;
        left: 24px;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 3000;
        display: flex;
        align-items: center;
        gap: 10px;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .mod-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>
@endsection

@section('content')
    <!-- Hero Banner -->
    <div class="mod-hero-banner">
        <div class="mod-hero-flex">
            <div class="mod-hero-title-group">
                <div class="mod-hero-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h1>مركز الرقابة وحوكمة المحتوى الجامعي</h1>
                </div>
            </div>
            <div class="mod-hero-actions">
                @if(isset($pendingReviewsCount) && $pendingReviewsCount > 0)
                    <button type="button" class="btn-review-notice-pulse" onclick="openPendingReviewsModal()" title="تعديلات واردة من الجامعات بانتظار الاعتماد">
                        <span class="pulse-beacon"></span>
                        <i class="fa-solid fa-bell"></i>
                        <span>تعديلات جامعية بانتظار المراجعة ({{ $pendingReviewsCount }})</span>
                    </button>
                @endif
                <a href="{{ route('admin.universities') }}" class="btn-mod-light">
                    <i class="fa-solid fa-arrow-right"></i>
                    <span>إدارة الجامعات</span>
                </a>
                <button type="button" class="btn-mod-light" onclick="refreshCurrentView()">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>تحديث</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mod-tabs-nav">
        <button type="button" class="mod-tab-btn active" id="tabBtn-auditor" onclick="switchModTab('auditor')">
            <i class="fa-solid fa-magnifying-glass-chart"></i>
            <span>أداة فحص وتدقيق أصول الجامعة</span>
        </button>
        <button type="button" class="mod-tab-btn" id="tabBtn-quarantines" onclick="switchModTab('quarantines')">
            <i class="fa-solid fa-shield-virus"></i>
            <span>سجل العناصر المحجوبة حالياً</span>
            <span class="tab-count-badge" id="tabBadgeQuarantines">{{ $stats['total_active'] }}</span>
        </button>
        <button type="button" class="mod-tab-btn" id="tabBtn-history" onclick="switchModTab('history')">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>أرشيف القرارات والمعالجات</span>
            <span class="tab-count-badge" id="tabBadgeHistory">{{ $stats['resolved'] }}</span>
        </button>
    </div>

    <!-- TAB 1: أداة فحص وتدقيق أصول الجامعة -->
    <div class="mod-tab-pane active" id="pane-auditor">
        <!-- University Selection Card -->
        <div class="auditor-header-card">
            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1 1 auto;">
                <label for="univSearchInput" style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0; white-space: nowrap;">
                    <i class="fa-solid fa-landmark" style="color: #0b3d6b; margin-left: 5px;"></i> اختر الجامعة المراد تدقيقها:
                </label>

                <!-- قائمة منسدلة قابلة للكتابة والبحث الفوري -->
                <div class="searchable-combobox-wrapper" id="univComboboxWrapper">
                    <input type="hidden" id="universityAuditorSelect" name="university_id" value="{{ $selectedUnivId ?: '' }}">
                    
                    <div class="combobox-input-group">
                        <i class="fa-solid fa-magnifying-glass combobox-search-icon"></i>
                        <input type="text" 
                               id="univSearchInput" 
                               class="combobox-input" 
                               placeholder="اكتب اسم الجامعة للبحث والاختيار..." 
                               autocomplete="off"
                               value="{{ $selectedUnivId ? ($universities->firstWhere('university_id', $selectedUnivId)?->university_name ?? '') : '' }}"
                               onfocus="openComboboxDropdown()" 
                               oninput="filterComboboxOptions(this.value)"
                               onkeydown="handleComboboxKeydown(event)">
                        <button type="button" class="combobox-clear-btn" id="univComboboxClearBtn" onclick="clearComboboxSelection(event)" style="{{ empty($selectedUnivId) ? 'display:none;' : 'display:flex;' }}" title="مسح الاختيار">
                            &times;
                        </button>
                        <button type="button" class="combobox-toggle-btn" onclick="toggleComboboxDropdown()" title="عرض القائمة">
                            <i class="fa-solid fa-chevron-down" id="comboboxCaret"></i>
                        </button>
                    </div>

                    <div class="combobox-dropdown" id="univComboboxDropdown" style="display: none;">
                        <div class="combobox-options-list" id="univComboboxList">
                            @foreach($universities as $univ)
                                <div class="combobox-option-item {{ $selectedUnivId == $univ->university_id ? 'selected' : '' }}" 
                                     data-id="{{ $univ->university_id }}" 
                                     data-name="{{ $univ->university_name }}" 
                                     data-type="{{ $univ->university_type }}"
                                     onclick="selectComboboxOption({{ $univ->university_id }}, '{{ addslashes($univ->university_name) }}', '{{ $univ->university_type }}')">
                                    <div class="opt-name">
                                        <i class="fa-solid fa-building-columns text-muted" style="font-size: 11px;"></i>
                                        <strong>{{ $univ->university_name }}</strong>
                                    </div>
                                    <span class="badge {{ $univ->university_type === 'حكومية' ? 'badge-gov' : 'badge-priv' }}">
                                        {{ $univ->university_type }}
                                    </span>
                                </div>
                            @endforeach
                            <div id="comboboxNoResults" class="combobox-no-results" style="display: none;">
                                <i class="fa-solid fa-circle-exclamation me-1 text-warning"></i> لا توجد جامعة مطابقة لكلمة البحث
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="univAuditSummaryBadges" style="display: flex; align-items: center; gap: 8px;">
                <!-- Filled dynamically via JS -->
            </div>
        </div>

        <!-- Prompt state when no university is selected -->
        <div id="auditorSelectPrompt" class="auditor-select-prompt" style="{{ empty($selectedUnivId) ? 'display:flex;' : 'display:none;' }}">
            <div class="prompt-icon-circle">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <h3>حدد الجامعة لبدء الفحص والتدقيق الرقابي</h3>
            <p>
                يرجى اختيار الجامعة من القائمة أعلاه لعرض ومراجعة أصولها الإعلامية، بياناتها التعريفية، كلياتها، وتخصصاتها الأكاديمية.
            </p>
        </div>

        <!-- Auditor Assets Content Container -->
        <div id="auditorLoadingState" style="text-align: center; padding: 40px; display: none;">
            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 32px; color: #0b3d6b;"></i>
            <p style="margin-top: 12px; font-size: 14px; color: #64748b; font-weight: 600;">جاري تحميل ومراجعة أصول وبيانات الجامعة...</p>
        </div>

        <div id="auditorAssetsContainer" style="{{ empty($selectedUnivId) ? 'display:none;' : 'display:block;' }}">
            <!-- 1. الهوية البصرية والوسائط -->
            <div class="audit-category-title">
                <i class="fa-solid fa-palette" style="color: #0b3d6b;"></i>
                <span>الهوية البصرية والوسائط المرفوعة</span>
            </div>
            <div class="audit-grid">
                <!-- شعار الجامعة -->
                <div class="audit-asset-card" id="cardAsset-logo">
                    <div>
                        <div class="asset-header">
                            <div>
                                <span class="asset-type-badge">صورة وهوية</span>
                                <h4 style="margin: 6px 0 0 0; font-size: 14px; font-weight: 800;">شعار الجامعة الرسمي (Logo)</h4>
                            </div>
                            <span class="asset-status-pill active" id="badgeStatus-logo">
                                <i class="fa-solid fa-circle-check"></i> معروض للطلاب
                            </span>
                        </div>
                        <div class="asset-preview-box" id="previewBox-logo">
                            <img src="" id="imgPreview-logo" alt="شعار الجامعة">
                        </div>
                        <div id="detailsBox-logo" style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                            <span>مسار الملف: </span><code id="pathText-logo" style="background:#e2e8f0; padding:2px 5px; border-radius:4px; font-size:11px;">—</code>
                        </div>
                    </div>
                    <div class="asset-action-bar">
                        <span id="modReasonNotice-logo" style="font-size: 11.5px; color: #dc2626; font-weight: 600;"></span>
                        <div id="actionBtnWrap-logo">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>

                <!-- صورة الحرم الجامعي -->
                <div class="audit-asset-card" id="cardAsset-campus_image">
                    <div>
                        <div class="asset-header">
                            <div>
                                <span class="asset-type-badge">وسائط ومباني</span>
                                <h4 style="margin: 6px 0 0 0; font-size: 14px; font-weight: 800;">صورة الحرم الجامعي (Campus / Cover)</h4>
                            </div>
                            <span class="asset-status-pill active" id="badgeStatus-campus_image">
                                <i class="fa-solid fa-circle-check"></i> معروض للطلاب
                            </span>
                        </div>
                        <div class="asset-preview-box" id="previewBox-campus_image">
                            <img src="" id="imgPreview-campus_image" alt="صورة الحرم">
                        </div>
                        <div id="detailsBox-campus_image" style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                            <span>مسار الملف: </span><code id="pathText-campus_image" style="background:#e2e8f0; padding:2px 5px; border-radius:4px; font-size:11px;">—</code>
                        </div>
                    </div>
                    <div class="asset-action-bar">
                        <span id="modReasonNotice-campus_image" style="font-size: 11.5px; color: #dc2626; font-weight: 600;"></span>
                        <div id="actionBtnWrap-campus_image">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. البيانات التعريفية والتواصل -->
            <div class="audit-category-title">
                <i class="fa-solid fa-address-card" style="color: #0b3d6b;"></i>
                <span>البيانات التعريفية ومعلومات الاتصال</span>
            </div>
            <div class="audit-grid">
                <!-- النبذة والوصف التعريفي -->
                <div class="audit-asset-card" id="cardAsset-description">
                    <div>
                        <div class="asset-header">
                            <div>
                                <span class="asset-type-badge">نص ومحتوى تعريفي</span>
                                <h4 style="margin: 6px 0 0 0; font-size: 14px; font-weight: 800;">النبذة والوصف التعريفي (Description / Bio)</h4>
                            </div>
                            <span class="asset-status-pill active" id="badgeStatus-description">
                                <i class="fa-solid fa-circle-check"></i> معروض للطلاب
                            </span>
                        </div>
                        <div class="asset-preview-box">
                            <div class="asset-preview-text" id="textPreview-description">
                                <!-- Text description -->
                            </div>
                        </div>
                    </div>
                    <div class="asset-action-bar">
                        <span id="modReasonNotice-description" style="font-size: 11.5px; color: #dc2626; font-weight: 600;"></span>
                        <div id="actionBtnWrap-description">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>

                <!-- بيانات الاتصال والموقع -->
                <div class="audit-asset-card" id="cardAsset-contact_info">
                    <div>
                        <div class="asset-header">
                            <div>
                                <span class="asset-type-badge">بيانات تواصل</span>
                                <h4 style="margin: 6px 0 0 0; font-size: 14px; font-weight: 800;">قنوات الاتصال والموقع الرسمي</h4>
                            </div>
                            <span class="asset-status-pill active" id="badgeStatus-contact_info">
                                <i class="fa-solid fa-circle-check"></i> معروض للطلاب
                            </span>
                        </div>
                        <div class="asset-preview-box" style="display:block;">
                            <div style="font-size: 12.5px; line-height: 1.8; color: #1e293b;" id="textPreview-contact">
                                <!-- Contact items -->
                            </div>
                        </div>
                    </div>
                    <div class="asset-action-bar">
                        <span id="modReasonNotice-contact_info" style="font-size: 11.5px; color: #dc2626; font-weight: 600;"></span>
                        <div id="actionBtnWrap-contact_info">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. الكليات الأكاديمية -->
            <div class="audit-category-title">
                <i class="fa-solid fa-landmark-dome" style="color: #0b3d6b;"></i>
                <span>الكليات الأكاديمية التابعة للجامعة</span>
            </div>
            <div class="audit-grid" id="collegesCardsGrid">
                <!-- Filled dynamically via JS -->
            </div>

            <!-- 4. التخصصات الأكاديمية التابعة -->
            <div class="audit-category-title">
                <i class="fa-solid fa-graduation-cap" style="color: #0b3d6b;"></i>
                <span>التخصصات الأكاديمية وشروط القبول</span>
            </div>
            <div class="mod-table-container">
                <table class="mod-table">
                    <thead>
                        <tr>
                            <th>التخصص الأكاديمي</th>
                            <th class="col-hide-mobile">الكلية التابعة</th>
                            <th class="col-hide-mobile">الرسوم السنوية</th>
                            <th>حالة الظهور</th>
                            <th class="col-hide-mobile">ملاحظات الرقابة</th>
                            <th style="text-align: center;">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody id="specializationsTableBody">
                        <!-- Filled dynamically via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: سجل العناصر المحجوبة حالياً عبر كافة الجامعات -->
    <div class="mod-tab-pane" id="pane-quarantines">
        <div class="mod-table-container">
            <div class="mod-table-header">
                <div class="mod-filter-group">
                    <span style="font-size: 13px; font-weight: 700; color: #475569;">تصفية حسب نوع العنصر:</span>
                    <button type="button" class="filter-btn-pill active" onclick="filterQuarantineRows('all', this)">الكل</button>
                    <button type="button" class="filter-btn-pill" onclick="filterQuarantineRows('logo', this)">شعار الجامعة</button>
                    <button type="button" class="filter-btn-pill" onclick="filterQuarantineRows('campus_image', this)">صورة الحرم</button>
                    <button type="button" class="filter-btn-pill" onclick="filterQuarantineRows('description', this)">النبذة والوصف</button>
                    <button type="button" class="filter-btn-pill" onclick="filterQuarantineRows('college', this)">الكليات</button>
                    <button type="button" class="filter-btn-pill" onclick="filterQuarantineRows('specialization', this)">التخصصات</button>
                </div>
                <div>
                    <input type="text" id="quarantineSearchInput" class="mod-search-input" placeholder="ابحث باسم الجامعة أو العنصر..." onkeyup="searchQuarantinesTable(this.value)">
                </div>
            </div>

            <table class="mod-table" id="allQuarantinesTable">
                <thead>
                    <tr>
                        <th class="col-hide-mobile">#</th>
                        <th>الجامعة والعنصر</th>
                        <th class="col-hide-mobile">نوع العنصر</th>
                        <th>سبب المخالفة / المراجعة</th>
                        <th class="col-hide-mobile">التوجيه الإداري</th>
                        <th class="col-hide-mobile">تاريخ الإيقاف</th>
                        <th style="text-align: center;">إجراء الاعتماد</th>
                    </tr>
                </thead>
                <tbody id="allQuarantinesTableBody">
                    @forelse($activeModerations as $index => $item)
                        <tr data-type="{{ $item->item_type }}" data-title="{{ $item->item_title }}" data-univ="{{ $item->university?->university_name }}" class="{{ (!empty($item->correction_notes) || $item->status === 'قيد_المراجعة') ? 'row-pending-review' : '' }}">
                            <td class="col-hide-mobile" style="font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                            <td>
                                <strong style="font-size: 13px; color: #0f172a;">{{ $item->university?->university_name ?? '—' }}</strong>
                                <div style="font-size: 11.5px; color: #0b3d6b; font-weight: 700; margin-top: 3px;">
                                    {{ $item->item_title }}
                                </div>
                            </td>
                            <td class="col-hide-mobile">
                                @if(in_array($item->item_type, ['logo', 'campus_image']))
                                    <span class="badge" style="background:#fffbeb; color:#b45309; border:1px solid #fde68a;">
                                        {{ $item->item_type == 'logo' ? 'شعار الجامعة' : 'صورة الحرم' }}
                                    </span>
                                @elseif(in_array($item->item_type, ['description', 'contact_info']))
                                    <span class="badge" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                                        {{ $item->item_type == 'description' ? 'النبذة والوصف' : 'بيانات الاتصال' }}
                                    </span>
                                @else
                                    <span class="badge" style="background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe;">
                                        {{ $item->item_type == 'college' ? 'كلية' : 'تخصص أكاديمي' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="color: #dc2626; font-weight: 600; font-size: 12px; display: block;">{{ $item->violation_reason }}</span>
                                @if(!empty($item->correction_notes) || $item->status === 'قيد_المراجعة')
                                    <div style="margin-top:6px; background:#fffbeb; border:1px solid #fde68a; border-radius:6px; padding:6px 9px; font-size:11.5px; color:#92400e;">
                                        <strong><i class="fa-solid fa-clock-rotate-left me-1"></i> رد وتعديل الجامعة:</strong>
                                        <div style="margin-top:2px; white-space: pre-line;">{{ $item->correction_notes }}</div>
                                    </div>
                                @endif
                            </td>
                            <td class="col-hide-mobile">
                                <span style="color: #475569; font-size: 12px;">{{ $item->admin_guidance ?: '—' }}</span>
                            </td>
                            <td class="col-hide-mobile" style="font-size: 12px; color: #64748b; white-space: nowrap;">
                                {{ $item->suspended_at?->format('Y/m/d H:i') ?? '—' }}
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect({{ $item->moderation_id }})">
                                    <i class="fa-solid fa-check"></i>
                                    <span>فك الحجب والاعتماد</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyQuarantineRow">
                            <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                <i class="fa-solid fa-shield-heart" style="font-size: 38px; color: #10b981; margin-bottom: 12px; display: block;"></i>
                                <strong style="font-size: 15px; color: #0f172a;">لا توجد أي عناصر محجوبة حالياً</strong>
                                <p style="font-size: 13px; margin: 4px 0 0 0;">كافة بيانات وأصول وتخصصات الجامعات في المنصة تعمل وتظهر للطلاب وفق المعايير والضوابط المعتمدة.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: أرشيف القرارات وسجل التدقيق -->
    <div class="mod-tab-pane" id="pane-history">
        <div class="mod-table-container">
            <div class="mod-table-header">
                <span style="font-size: 13.5px; font-weight: 700; color: #0f172a;">
                    <i class="fa-solid fa-list-check" style="color: #10b981; margin-left: 6px;"></i>
                    سجل القرارات التاريخية التي تمت معالجتها وفك الحجب عنها
                </span>
            </div>
            <table class="mod-table">
                <thead>
                    <tr>
                        <th class="col-hide-mobile">#</th>
                        <th>الجامعة والعنصر</th>
                        <th class="col-hide-mobile">نوع العنصر</th>
                        <th class="col-hide-mobile">سبب المخالفة السابق</th>
                        <th class="col-hide-mobile">المسؤول عن الفك</th>
                        <th>تاريخ فك الحجب</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyModerations as $hIndex => $hist)
                        <tr>
                            <td class="col-hide-mobile" style="font-weight: 700; color: #64748b;">{{ $hIndex + 1 }}</td>
                            <td>
                                <strong style="font-size: 13px; color: #0f172a;">{{ $hist->university?->university_name ?? '—' }}</strong>
                                <div style="font-size: 11.5px; color: #475569; margin-top: 2px;">{{ $hist->item_title }}</div>
                            </td>
                            <td class="col-hide-mobile"><span class="asset-type-badge">{{ $hist->item_type }}</span></td>
                            <td class="col-hide-mobile" style="font-size: 12px; color: #64748b;">{{ $hist->violation_reason }}</td>
                            <td class="col-hide-mobile">{{ $hist->resolvedByUser?->username ?? 'مدير النظام' }}</td>
                            <td style="font-size: 12px; color: #64748b; white-space: nowrap;">{{ $hist->resolved_at?->format('Y/m/d') ?? '—' }}</td>
                            <td>
                                <span class="badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;">
                                    <i class="fa-solid fa-check me-1"></i> تم الحل
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px; color: #64748b;">
                                لا يوجد سجل تاريخي لقرارات الحجب السابقة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ============================================================
     MODAL: الحجب الرقابي الفوري للعنصر
     ============================================================ -->
<div class="mod-modal-overlay" id="quarantineActionModal">
    <div class="mod-modal-card">
        <div class="mod-modal-header">
            <h3>
                <i class="fa-solid fa-shield-virus" style="color: #f87171;"></i>
                <span>إيقاف ظهور عنصر رقابياً (حجر فوري)</span>
            </h3>
            <button type="button" class="mod-modal-close" onclick="closeQuarantineModal()">&times;</button>
        </div>
        <form id="quarantineActionForm" onsubmit="submitQuarantineAction(event)">
            <input type="hidden" id="qFieldUnivId" name="university_id">
            <input type="hidden" id="qFieldItemType" name="item_type">
            <input type="hidden" id="qFieldItemId" name="item_id">
            <input type="hidden" id="qFieldItemTitle" name="item_title">

            <div class="mod-modal-body">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 9px; padding: 12px 14px; margin-bottom: 16px;">
                    <div style="font-size: 12px; color: #64748b;">الجامعة المستهدفة:</div>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a;" id="qDisplayUnivName">—</div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 6px;">العنصر المراد إيقاف ظهوره:</div>
                    <div style="font-size: 13.5px; font-weight: 700; color: #dc2626;" id="qDisplayItemTitle">—</div>
                </div>

                <div class="mod-form-group">
                    <label for="qSelectViolationReason">سبب المخالفة الإدارية / الرقابية: <span style="color:#dc2626;">*</span></label>
                    <select id="qSelectViolationReason" class="mod-form-control" onchange="onReasonPresetChange(this.value)" required>
                        <!-- Dynamic options loaded via JS based on item_type -->
                    </select>
                </div>

                <div class="mod-form-group">
                    <label for="qCustomViolationReason">تفاصيل المخالفة والملاحظات الدقيقة: <span style="color:#dc2626;">*</span></label>
                    <textarea id="qCustomViolationReason" name="violation_reason" class="mod-form-control" rows="3" placeholder="اشرح بدقة حيثيات المخالفة التي تستوجب إيقاف الظهور..." required></textarea>
                </div>

                <div class="mod-form-group">
                    <label for="qAdminGuidance">توجيهات لمدير الجامعة لتصحيح الوضع (تظهر للجامعة):</label>
                    <textarea id="qAdminGuidance" name="admin_guidance" class="mod-form-control" rows="2" placeholder="المطلوب من مسؤول الجامعة لإعادة الاعتماد وفك الحجب..."></textarea>
                </div>

                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 10px 12px; font-size: 12px; color: #991b1b; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 15px;"></i>
                    <span>سيتم إخفاء هذا العنصر فوراً من واجهات الطلاب دون حذف بياناته الأكاديمية أو التأثير على بقية أقسام الجامعة.</span>
                </div>
            </div>

            <div class="mod-modal-footer">
                <button type="button" class="btn-quarantine-action" style="background:#e2e8f0; color:#334155;" onclick="closeQuarantineModal()">إلغاء</button>
                <button type="submit" class="btn-quarantine-action suspend" id="qSubmitBtn" style="padding: 8px 18px;">
                    <i class="fa-solid fa-ban"></i>
                    <span>تأكيد إيقاف الظهور والحجر</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================
     MODAL: مراجعة وتدقيق تعديلات الجامعات بانتظار الاعتماد
     ============================================================ -->
<div class="mod-modal-overlay" id="pendingReviewsModal">
    <div class="mod-modal-card" style="max-width: 680px;">
        <div class="mod-modal-header" style="background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%); color: #ffffff;">
            <h3 style="color: #ffffff; display: flex; align-items: center; gap: 9px; margin: 0; font-size: 16px;">
                <i class="fa-solid fa-bell" style="color: #f59e0b;"></i>
                <span>التعديلات الواردة من الجامعات بانتظار المراجعة</span>
            </h3>
            <button type="button" class="mod-modal-close" style="color:#ffffff;" onclick="closePendingReviewsModal()">&times;</button>
        </div>
        <div class="mod-modal-body" style="max-height: 70vh; overflow-y: auto; padding: 20px;">
            @if(isset($pendingReviews) && $pendingReviews->isNotEmpty())
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    @foreach($pendingReviews as $rev)
                        <div style="background: #ffffff; border: 1.5px solid #fde68a; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                                <div>
                                    <span class="badge" style="background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; font-size:11px;">
                                        {{ $rev->university?->university_name }}
                                    </span>
                                    <h4 style="margin: 6px 0 0 0; font-size: 14.5px; font-weight: 800; color: #0f172a;">
                                        {{ $rev->item_title }}
                                    </h4>
                                </div>
                                <span class="badge" style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; font-size:11px; padding: 4px 8px;">
                                    <i class="fa-solid fa-clock me-1"></i> بانتظار قرار المدير
                                </span>
                            </div>

                            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                                <strong style="color:#dc2626;">سبب الحجب السابق:</strong> {{ $rev->violation_reason }}
                            </div>

                            <div style="background: #fffdf5; border-right: 4px solid #f59e0b; padding: 10px 14px; border-radius: 6px; font-size: 13px; color: #92400e; margin-bottom: 12px;">
                                <strong><i class="fa-solid fa-comment-dots me-1"></i> توضيح وتعديل الجامعة:</strong>
                                <div style="margin-top: 4px; white-space: pre-line; line-height: 1.5;">{{ $rev->correction_notes ?: 'تم إجراء التعديل المطلوب من قبل الجامعة.' }}</div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <span style="font-size: 11.5px; color: #94a3b8;">
                                    تاريخ الإرسال: {{ $rev->updated_at ? $rev->updated_at->format('Y/m/d H:i') : 'مؤخراً' }}
                                </span>
                                <button type="button" class="btn-quarantine-action resolve" onclick="resolveReviewAndReload({{ $rev->moderation_id }})">
                                    <i class="fa-solid fa-check"></i>
                                    <span>اعتماد التعديل وفك الحجب</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #64748b;">
                    <i class="fa-solid fa-circle-check" style="font-size: 36px; color: #10b981; margin-bottom: 10px; display: block;"></i>
                    <p style="margin: 0; font-size: 14px; font-weight: 600;">لا توجد تعديلات معلقة من الجامعات حالياً.</p>
                </div>
            @endif
        </div>
        <div class="mod-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closePendingReviewsModal()">إغلاق</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="modToastNotification" class="mod-toast">
    <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 18px;" id="modToastIcon"></i>
    <span id="modToastMessage">تم الإجراء بنجاح</span>
</div>

@endsection

@section('scripts')
<script>
    // State variables
    let currentAuditedUniversity = null;
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Preset violation options by item type
    const VIOLATION_PRESETS = {
        logo: [
            "شعار غير رسمي أو منسوب لجهة أخرى",
            "صورة الشعار منخفضة الجودة أو غير لائقة",
            "انتهاك حقوق الملكية الفكرية أو العلامات التجارية",
            "أخرى (توضيح يدوي)"
        ],
        campus_image: [
            "صورة مضللة لا تعكس الحرم الجامعي الحقيقي",
            "محتوى بصري غير مرخص أو غير ملائم",
            "استخدام صور مشاريع لم يتم إنجازها لتضليل الطلاب",
            "أخرى (توضيح يدوي)"
        ],
        description: [
            "ادعاءات تسويقية مضللة أو غير معتمدة من الوزارة",
            "محتوى مسيء أو مخل بالمعايير الأخلاقية والأكاديمية",
            "معلومات تاريخية أو بيانات تصنيف غير دقيقة ومضللة",
            "أخرى (توضيح يدوي)"
        ],
        contact_info: [
            "بيانات اتصال أو قنوات وهمية أو غير رسمية",
            "روابط خارجية مشبوهة أو غير آمنة للطلاب",
            "أخرى (توضيح يدوي)"
        ],
        college: [
            "كلية غير مرخصة رسمياً بقرار من وزارة التعليم العالي",
            "قرارات تعليق نشاط الكلية من الجهات المختصة",
            "أخرى (توضيح يدوي)"
        ],
        specialization: [
            "برنامج أو تخصص غير مرخص رسمياً من وزارة التعليم العالي",
            "رسوم دراسية مخالفة للائحة الرسمية المعتمدة",
            "شروط ومعايير قبول مضللة للطلاب",
            "شهادة أو برنامج غير معادل أكاديمياً",
            "أخرى (توضيح يدوي)"
        ]
    };

    document.addEventListener('DOMContentLoaded', function() {
        const initialUnivId = document.getElementById('universityAuditorSelect')?.value;
        if (initialUnivId) {
            onUniversitySelectChanged(initialUnivId);
        }

        // إغلاق قائمة البحث المنسدلة عند النقر في أي مكان خارجها
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('univComboboxWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                closeComboboxDropdown();
            }
        });
    });

    // ==========================================
    // القائمة المنسدلة القابلة للبحث الفوري (Combobox)
    // ==========================================
    let comboboxHighlightedIndex = -1;

    function openComboboxDropdown() {
        const dropdown = document.getElementById('univComboboxDropdown');
        const caret = document.getElementById('comboboxCaret');
        if (dropdown) dropdown.style.display = 'block';
        if (caret) caret.style.transform = 'rotate(180deg)';
    }

    function closeComboboxDropdown() {
        const dropdown = document.getElementById('univComboboxDropdown');
        const caret = document.getElementById('comboboxCaret');
        if (dropdown) dropdown.style.display = 'none';
        if (caret) caret.style.transform = 'rotate(0deg)';
        comboboxHighlightedIndex = -1;
    }

    function toggleComboboxDropdown() {
        const dropdown = document.getElementById('univComboboxDropdown');
        if (!dropdown) return;
        if (dropdown.style.display === 'block') {
            closeComboboxDropdown();
        } else {
            openComboboxDropdown();
            document.getElementById('univSearchInput')?.focus();
        }
    }

    // تسوية الحروف العربية لتسهيل البحث (الهمزات، التاء المربوطة، الياء)
    function normalizeArabic(text) {
        if (!text) return '';
        return String(text)
            .toLowerCase()
            .replace(/[أإآ]/g, 'ا')
            .replace(/ة/g, 'ه')
            .replace(/ى/g, 'ي')
            .trim();
    }

    // تصفية نتائج البحث فور الكتابة على الحقل وتحديد الجامعة الأولى تلقائياً
    function filterComboboxOptions(query) {
        openComboboxDropdown();
        const normQuery = normalizeArabic(query);
        const items = document.querySelectorAll('.combobox-option-item');
        let visibleItems = [];

        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const type = item.getAttribute('data-type') || '';
            const normName = normalizeArabic(name);
            const normType = normalizeArabic(type);

            if (!normQuery || normName.includes(normQuery) || normType.includes(normQuery)) {
                item.style.display = 'flex';
                visibleItems.push(item);
            } else {
                item.style.display = 'none';
                item.classList.remove('highlighted');
            }
        });

        const noResults = document.getElementById('comboboxNoResults');
        if (noResults) {
            noResults.style.display = visibleItems.length === 0 ? 'block' : 'none';
        }

        const clearBtn = document.getElementById('univComboboxClearBtn');
        if (clearBtn) {
            clearBtn.style.display = query ? 'flex' : 'none';
        }

        // عند الكتابة تكون الجامعة الأولى محددة تلقائياً لاختيارها فوراً بالضغط على Enter
        if (visibleItems.length > 0) {
            comboboxHighlightedIndex = 0;
            highlightComboboxItem(visibleItems);
        } else {
            comboboxHighlightedIndex = -1;
        }
    }

    // اختيار جامعة من القائمة
    function selectComboboxOption(univId, univName, univType) {
        const hiddenInput = document.getElementById('universityAuditorSelect');
        const searchInput = document.getElementById('univSearchInput');
        const clearBtn = document.getElementById('univComboboxClearBtn');

        if (hiddenInput) hiddenInput.value = univId;
        if (searchInput) searchInput.value = univName;
        if (clearBtn) clearBtn.style.display = 'flex';

        // تحديث العنصر النشط
        document.querySelectorAll('.combobox-option-item').forEach(item => {
            if (item.getAttribute('data-id') == univId) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });

        closeComboboxDropdown();
        onUniversitySelectChanged(univId);
    }

    // مسح الاختيار وإعادة التعيين
    function clearComboboxSelection(event) {
        if (event) event.stopPropagation();

        const hiddenInput = document.getElementById('universityAuditorSelect');
        const searchInput = document.getElementById('univSearchInput');
        const clearBtn = document.getElementById('univComboboxClearBtn');

        if (hiddenInput) hiddenInput.value = '';
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
        if (clearBtn) clearBtn.style.display = 'none';

        document.querySelectorAll('.combobox-option-item').forEach(item => {
            item.classList.remove('selected');
            item.classList.remove('highlighted');
            item.style.display = 'flex';
        });

        const noResults = document.getElementById('comboboxNoResults');
        if (noResults) noResults.style.display = 'none';

        comboboxHighlightedIndex = -1;
        onUniversitySelectChanged('');
        openComboboxDropdown();
    }

    // التنقل والاختيار عبر لوحة المفاتيح
    function handleComboboxKeydown(e) {
        const dropdown = document.getElementById('univComboboxDropdown');
        const visibleItems = Array.from(document.querySelectorAll('.combobox-option-item')).filter(el => el.style.display !== 'none');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (dropdown.style.display !== 'block') {
                openComboboxDropdown();
                return;
            }
            if (visibleItems.length === 0) return;
            comboboxHighlightedIndex = (comboboxHighlightedIndex + 1) % visibleItems.length;
            highlightComboboxItem(visibleItems);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (dropdown.style.display !== 'block') return;
            if (visibleItems.length === 0) return;
            comboboxHighlightedIndex = (comboboxHighlightedIndex - 1 + visibleItems.length) % visibleItems.length;
            highlightComboboxItem(visibleItems);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            // اختيار الجامعة المحددة أو الأولى فوراً وعرض بياناتها
            const targetItem = (comboboxHighlightedIndex >= 0 && visibleItems[comboboxHighlightedIndex]) 
                ? visibleItems[comboboxHighlightedIndex] 
                : (visibleItems.length > 0 ? visibleItems[0] : null);

            if (targetItem) {
                targetItem.click();
            }
        } else if (e.key === 'Escape') {
            closeComboboxDropdown();
        }
    }

    function highlightComboboxItem(visibleItems) {
        visibleItems.forEach((item, idx) => {
            if (idx === comboboxHighlightedIndex) {
                item.classList.add('highlighted');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('highlighted');
            }
        });
    }

    // تبديل اختيار الجامعة من القائمة المنسدلة
    function onUniversitySelectChanged(univId) {
        const prompt = document.getElementById('auditorSelectPrompt');
        const container = document.getElementById('auditorAssetsContainer');

        if (!univId) {
            if (prompt) prompt.style.display = 'flex';
            if (container) container.style.display = 'none';
            return;
        }

        if (prompt) prompt.style.display = 'none';
        if (container) container.style.display = 'block';

        loadUniversityAudit(univId);
    }

    // نوافذ مراجعة تعديلات الجامعات
    function openPendingReviewsModal() {
        const modal = document.getElementById('pendingReviewsModal');
        if (modal) modal.classList.add('show');
    }

    function closePendingReviewsModal() {
        const modal = document.getElementById('pendingReviewsModal');
        if (modal) modal.classList.remove('show');
    }

    function resolveReviewAndReload(modId) {
        resolveQuarantineDirect(modId);
        setTimeout(() => {
            closePendingReviewsModal();
            window.location.reload();
        }, 600);
    }

    // تبديل التبويبات الرئيسية
    function switchModTab(tabName) {
        document.querySelectorAll('.mod-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.mod-tab-pane').forEach(pane => pane.classList.remove('active'));

        const targetBtn = document.getElementById(`tabBtn-${tabName}`);
        const targetPane = document.getElementById(`pane-${tabName}`);

        if (targetBtn && targetPane) {
            targetBtn.classList.add('active');
            targetPane.classList.add('active');
        }
    }

    // تحميل وتدقيق أصول الجامعة المحددة عبر AJAX
    function loadUniversityAudit(univId) {
        if (!univId) return;

        const loading = document.getElementById('auditorLoadingState');
        const container = document.getElementById('auditorAssetsContainer');

        if (loading) loading.style.display = 'block';
        if (container) container.style.opacity = '0.5';

        fetch(`/admin/moderation/university/${univId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (loading) loading.style.display = 'none';
            if (container) container.style.opacity = '1';

            if (data.success && data.university) {
                currentAuditedUniversity = data.university;
                renderAuditorAssets(data.university);
            } else {
                showToast('تعذر جلب بيانات الجامعة المحددة', 'error');
            }
        })
        .catch(err => {
            if (loading) loading.style.display = 'none';
            if (container) container.style.opacity = '1';
            console.error('Error fetching university audit:', err);
            showToast('حدث خطأ أثناء تحميل بيانات الجامعة', 'error');
        });
    }

    // رسم وعرض أصول الجامعة في الواجهة
    function renderAuditorAssets(univ) {
        // 1. الشعار Logo
        const logoImg = document.getElementById('imgPreview-logo');
        const logoPath = document.getElementById('pathText-logo');
        const logoBadge = document.getElementById('badgeStatus-logo');
        const logoCard = document.getElementById('cardAsset-logo');
        const logoNotice = document.getElementById('modReasonNotice-logo');
        const logoActions = document.getElementById('actionBtnWrap-logo');

        if (logoImg) logoImg.src = univ.logo_url || '/logo.png';
        if (logoPath) logoPath.textContent = univ.raw_logo || 'افتراضي';

        if (univ.logo_quarantined) {
            logoCard.classList.add('is-quarantined');
            logoBadge.className = 'asset-status-pill quarantined';
            logoBadge.innerHTML = '<i class="fa-solid fa-ban"></i> محجوب رقابياً';
            logoNotice.textContent = univ.logo_mod ? `سبب الحجب: ${univ.logo_mod.violation_reason}` : '';
            logoActions.innerHTML = `
                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect(${univ.logo_mod.moderation_id})">
                    <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                </button>
            `;
        } else {
            logoCard.classList.remove('is-quarantined');
            logoBadge.className = 'asset-status-pill active';
            logoBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> معروض للطلاب';
            logoNotice.textContent = '';
            logoActions.innerHTML = `
                <button type="button" class="btn-quarantine-action suspend" onclick="openQuarantineModal('logo', ${univ.id}, 'شعار الجامعة')">
                    <i class="fa-solid fa-ban"></i> إيقاف الظهور
                </button>
            `;
        }

        // 2. صورة الحرم Campus Image
        const campusImg = document.getElementById('imgPreview-campus_image');
        const campusPath = document.getElementById('pathText-campus_image');
        const campusBadge = document.getElementById('badgeStatus-campus_image');
        const campusCard = document.getElementById('cardAsset-campus_image');
        const campusNotice = document.getElementById('modReasonNotice-campus_image');
        const campusActions = document.getElementById('actionBtnWrap-campus_image');

        if (campusImg) campusImg.src = univ.campus_image_url || '/images/campus-default.svg';
        if (campusPath) campusPath.textContent = univ.raw_campus_image || 'افتراضي';

        if (univ.campus_quarantined) {
            campusCard.classList.add('is-quarantined');
            campusBadge.className = 'asset-status-pill quarantined';
            campusBadge.innerHTML = '<i class="fa-solid fa-ban"></i> محجوب رقابياً';
            campusNotice.textContent = univ.campus_mod ? `سبب الحجب: ${univ.campus_mod.violation_reason}` : '';
            campusActions.innerHTML = `
                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect(${univ.campus_mod.moderation_id})">
                    <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                </button>
            `;
        } else {
            campusCard.classList.remove('is-quarantined');
            campusBadge.className = 'asset-status-pill active';
            campusBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> معروض للطلاب';
            campusNotice.textContent = '';
            campusActions.innerHTML = `
                <button type="button" class="btn-quarantine-action suspend" onclick="openQuarantineModal('campus_image', ${univ.id}, 'صورة الحرم الجامعي')">
                    <i class="fa-solid fa-ban"></i> إيقاف الظهور
                </button>
            `;
        }

        // 3. النبذة والوصف Description
        const descText = document.getElementById('textPreview-description');
        const descBadge = document.getElementById('badgeStatus-description');
        const descCard = document.getElementById('cardAsset-description');
        const descNotice = document.getElementById('modReasonNotice-description');
        const descActions = document.getElementById('actionBtnWrap-description');

        if (descText) descText.textContent = univ.description || 'لا يوجد وصف مدخل للجامعة حالياً.';

        if (univ.desc_quarantined) {
            descCard.classList.add('is-quarantined');
            descBadge.className = 'asset-status-pill quarantined';
            descBadge.innerHTML = '<i class="fa-solid fa-ban"></i> محجوب رقابياً';
            descNotice.textContent = univ.desc_mod ? `سبب الحجب: ${univ.desc_mod.violation_reason}` : '';
            descActions.innerHTML = `
                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect(${univ.desc_mod.moderation_id})">
                    <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                </button>
            `;
        } else {
            descCard.classList.remove('is-quarantined');
            descBadge.className = 'asset-status-pill active';
            descBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> معروض للطلاب';
            descNotice.textContent = '';
            descActions.innerHTML = `
                <button type="button" class="btn-quarantine-action suspend" onclick="openQuarantineModal('description', ${univ.id}, 'النبذة والوصف التعريفي')">
                    <i class="fa-solid fa-ban"></i> إيقاف الظهور
                </button>
            `;
        }

        // 4. بيانات الاتصال والتواصل Contact Info
        const contactWrap = document.getElementById('textPreview-contact');
        const contactBadge = document.getElementById('badgeStatus-contact_info');
        const contactCard = document.getElementById('cardAsset-contact_info');
        const contactNotice = document.getElementById('modReasonNotice-contact_info');
        const contactActions = document.getElementById('actionBtnWrap-contact_info');

        if (contactWrap) {
            contactWrap.innerHTML = `
                <div><i class="fa-solid fa-phone me-2" style="color:#0b3d6b;"></i> <strong>الهاتف:</strong> ${univ.phone || 'غير محدد'}</div>
                <div><i class="fa-solid fa-envelope me-2" style="color:#0b3d6b;"></i> <strong>البريد:</strong> ${univ.email || 'غير محدد'}</div>
                <div><i class="fa-solid fa-globe me-2" style="color:#0b3d6b;"></i> <strong>الموقع:</strong> ${univ.website ? `<a href="${univ.website}" target="_blank">${univ.website}</a>` : 'غير محدد'}</div>
                <div><i class="fa-solid fa-location-dot me-2" style="color:#0b3d6b;"></i> <strong>العنوان:</strong> ${univ.district ? univ.district + ' - ' : ''}${univ.address || 'غير محدد'}</div>
            `;
        }

        if (univ.contact_quarantined) {
            contactCard.classList.add('is-quarantined');
            contactBadge.className = 'asset-status-pill quarantined';
            contactBadge.innerHTML = '<i class="fa-solid fa-ban"></i> محجوب رقابياً';
            contactNotice.textContent = univ.contact_mod ? `سبب الحجب: ${univ.contact_mod.violation_reason}` : '';
            contactActions.innerHTML = `
                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect(${univ.contact_mod.moderation_id})">
                    <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                </button>
            `;
        } else {
            contactCard.classList.remove('is-quarantined');
            contactBadge.className = 'asset-status-pill active';
            contactBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> معروض للطلاب';
            contactNotice.textContent = '';
            contactActions.innerHTML = `
                <button type="button" class="btn-quarantine-action suspend" onclick="openQuarantineModal('contact_info', ${univ.id}, 'بيانات الاتصال والتواصل')">
                    <i class="fa-solid fa-ban"></i> إيقاف الظهور
                </button>
            `;
        }

        // 5. رسم الكليات
        const collegesGrid = document.getElementById('collegesCardsGrid');
        if (collegesGrid) {
            if (!univ.colleges || univ.colleges.length === 0) {
                collegesGrid.innerHTML = `
                    <div style="grid-column: 1/-1; padding: 24px; text-align: center; color: #64748b; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
                        لا توجد كليات مسجلة لهذه الجامعة بعد.
                    </div>
                `;
            } else {
                collegesGrid.innerHTML = univ.colleges.map(c => `
                    <div class="audit-asset-card ${c.is_quarantined ? 'is-quarantined' : ''}">
                        <div>
                            <div class="asset-header">
                                <div>
                                    <span class="asset-type-badge">كلية أكاديمية</span>
                                    <h4 style="margin: 6px 0 0 0; font-size: 14px; font-weight: 800;">${c.name}</h4>
                                </div>
                                <span class="asset-status-pill ${c.is_quarantined ? 'quarantined' : 'active'}">
                                    ${c.is_quarantined ? '<i class="fa-solid fa-ban"></i> محجوبة رقابياً' : '<i class="fa-solid fa-circle-check"></i> معتمدة'}
                                </span>
                            </div>
                            <div style="font-size: 12.5px; color: #64748b; margin: 8px 0;">
                                عدد التخصصات التابعة: <strong>${c.specializations_count} تخصص</strong>
                            </div>
                            ${c.is_quarantined && c.moderation ? `
                                <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:6px; padding:6px 10px; font-size:11.5px; color:#991b1b; margin-top:6px;">
                                    <strong>السبب:</strong> ${c.moderation.violation_reason}
                                </div>
                            ` : ''}
                        </div>
                        <div class="asset-action-bar">
                            <span style="font-size:11.5px; color:#64748b;">${c.status}</span>
                            ${c.is_quarantined ? `
                                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect(${c.moderation.moderation_id})">
                                    <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                                </button>
                            ` : `
                                <button type="button" class="btn-quarantine-action suspend" onclick="openQuarantineModal('college', ${c.id}, '${c.name.replace(/'/g, "\\'")}')">
                                    <i class="fa-solid fa-ban"></i> إيقاف ظهور الكلية
                                </button>
                            `}
                        </div>
                    </div>
                `).join('');
            }
        }

        // 6. رسم جدول التخصصات
        const specsTbody = document.getElementById('specializationsTableBody');
        if (specsTbody) {
            let allSpecs = [];
            if (univ.colleges) {
                univ.colleges.forEach(c => {
                    if (c.specializations) {
                        c.specializations.forEach(s => {
                            allSpecs.push({
                                ...s,
                                college_name: c.name
                            });
                        });
                    }
                });
            }

            if (allSpecs.length === 0) {
                specsTbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #64748b;">
                            لا توجد تخصصات مسجلة لهذه الجامعة بعد.
                        </td>
                    </tr>
                `;
            } else {
                specsTbody.innerHTML = allSpecs.map(s => `
                    <tr>
                        <td>
                            <strong style="color: #0f172a; font-size: 13px;">${s.name}</strong>
                        </td>
                        <td class="col-hide-mobile">${s.college_name}</td>
                        <td class="col-hide-mobile" style="font-weight: 600;">$${s.tuition_fees.toLocaleString()}</td>
                        <td>
                            <span class="asset-status-pill ${s.is_quarantined ? 'quarantined' : 'active'}">
                                ${s.is_quarantined ? '<i class="fa-solid fa-ban"></i> محجوب' : '<i class="fa-solid fa-circle-check"></i> معروض'}
                            </span>
                        </td>
                        <td class="col-hide-mobile">
                            ${s.is_quarantined && s.moderation ? `
                                <span style="color:#dc2626; font-size:12px; font-weight:600;">${s.moderation.violation_reason}</span>
                            ` : '<span style="color:#94a3b8; font-size:12px;">—</span>'}
                        </td>
                        <td style="text-align: center;">
                            ${s.is_quarantined ? `
                                <button type="button" class="btn-quarantine-action resolve" onclick="resolveQuarantineDirect(${s.moderation.moderation_id})">
                                    <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                                </button>
                            ` : `
                                <button type="button" class="btn-quarantine-action suspend" onclick="openQuarantineModal('specialization', ${s.id}, '${s.name.replace(/'/g, "\\'")}')">
                                    <i class="fa-solid fa-ban"></i> إيقاف الظهور
                                </button>
                            `}
                        </td>
                    </tr>
                `).join('');
            }
        }
    }

    // فتح نافذة إجراء الحجب الرقابي
    function openQuarantineModal(itemType, itemId, itemTitle) {
        if (!currentAuditedUniversity) return;

        document.getElementById('qFieldUnivId').value = currentAuditedUniversity.id;
        document.getElementById('qFieldItemType').value = itemType;
        document.getElementById('qFieldItemId').value = itemId;
        document.getElementById('qFieldItemTitle').value = itemTitle;

        document.getElementById('qDisplayUnivName').textContent = currentAuditedUniversity.name;
        document.getElementById('qDisplayItemTitle').textContent = `${itemTitle} (${getItemTypeLabel(itemType)})`;

        // إعداد خيارات الأسباب المسبقة حسب النوع
        const select = document.getElementById('qSelectViolationReason');
        const presets = VIOLATION_PRESETS[itemType] || VIOLATION_PRESETS['specialization'];
        select.innerHTML = '<option value="">-- اختر من أسباب المخالفة الشائعة --</option>' +
            presets.map(p => `<option value="${p}">${p}</option>`).join('');

        document.getElementById('qCustomViolationReason').value = '';
        document.getElementById('qAdminGuidance').value = '';

        const modal = document.getElementById('quarantineActionModal');
        modal.classList.add('open');
    }

    function closeQuarantineModal() {
        const modal = document.getElementById('quarantineActionModal');
        modal.classList.remove('open');
    }

    function onReasonPresetChange(val) {
        const textarea = document.getElementById('qCustomViolationReason');
        if (val && val !== 'أخرى (توضيح يدوي)') {
            textarea.value = val;
        }
    }

    function getItemTypeLabel(type) {
        const labels = {
            logo: 'شعار الجامعة',
            campus_image: 'صورة الحرم',
            description: 'النبذة والوصف',
            contact_info: 'بيانات الاتصال',
            college: 'كلية أكاديمية',
            specialization: 'تخصص أكاديمي'
        };
        return labels[type] || type;
    }

    // إرسال طلب الحجب الفوري عبر AJAX
    function submitQuarantineAction(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('qSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> جاري الحجب...';

        const payload = {
            university_id: document.getElementById('qFieldUnivId').value,
            item_type: document.getElementById('qFieldItemType').value,
            item_id: document.getElementById('qFieldItemId').value,
            item_title: document.getElementById('qFieldItemTitle').value,
            violation_reason: document.getElementById('qCustomViolationReason').value,
            admin_guidance: document.getElementById('qAdminGuidance').value,
        };

        fetch('/admin/moderations/quarantine', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-ban"></i> تأكيد إيقاف الظهور والحجر';

            if (data.success) {
                closeQuarantineModal();
                showToast(data.message || 'تم حجب العنصر بنجاح وإيقاف ظهوره للطلاب.', 'success');
                // إعادة تحميل تدقيق الجامعة الحالية لتحديث الشارات
                if (currentAuditedUniversity) {
                    loadUniversityAudit(currentAuditedUniversity.id);
                }
                updateTopCounters();
            } else {
                showToast(data.message || 'تعذر إتمام الإجراء', 'error');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-ban"></i> تأكيد إيقاف الظهور والحجر';
            console.error('Error submitting quarantine:', err);
            showToast('حدث خطأ أثناء الاتصال بالخادم', 'error');
        });
    }

    // فك الحجب والاعتماد الفوري
    function resolveQuarantineDirect(moderationId) {
        if (!confirm('هل أنت متأكد من فك الحجب عن هذا العنصر وإعادة ظهوره للطلاب في المنصة بعد التحقق والتصحيح؟')) {
            return;
        }

        fetch(`/admin/moderations/${moderationId}/resolve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'تم فك الحجب والاعتماد بنجاح.', 'success');
                if (currentAuditedUniversity) {
                    loadUniversityAudit(currentAuditedUniversity.id);
                }
                updateTopCounters();
                // إذا كنا في تبويب السجل، يتم إخفاء الصف فوراً
                const row = document.querySelector(`tr:has(button[onclick="resolveQuarantineDirect(${moderationId})"])`);
                if (row) {
                    row.remove();
                }
            } else {
                showToast(data.message || 'تعذر فك الحجب', 'error');
            }
        })
        .catch(err => {
            console.error('Error resolving quarantine:', err);
            showToast('حدث خطأ أثناء معالجة فك الحجب', 'error');
        });
    }

    // تصفية صفوف جدول العناصر المحجوبة
    function filterQuarantineRows(type, btn) {
        document.querySelectorAll('.filter-btn-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const rows = document.querySelectorAll('#allQuarantinesTableBody tr[data-type]');
        rows.forEach(r => {
            if (type === 'all' || r.getAttribute('data-type') === type) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    // بحث في جدول العناصر المحجوبة
    function searchQuarantinesTable(query) {
        const q = (query || '').toLowerCase().trim();
        const rows = document.querySelectorAll('#allQuarantinesTableBody tr[data-type]');
        rows.forEach(r => {
            const title = (r.getAttribute('data-title') || '').toLowerCase();
            const univ = (r.getAttribute('data-univ') || '').toLowerCase();
            if (!q || title.includes(q) || univ.includes(q)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    // تحديث مؤشرات الصفحة
    function updateTopCounters() {
        fetch('/admin/moderations/quarantined', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const count = data.count || 0;
                const kpiTotal = document.getElementById('kpiTotalActive');
                const tabBadge = document.getElementById('tabBadgeQuarantines');
                if (kpiTotal) kpiTotal.textContent = count;
                if (tabBadge) tabBadge.textContent = count;
            }
        });
    }

    function refreshCurrentView() {
        if (currentAuditedUniversity) {
            loadUniversityAudit(currentAuditedUniversity.id);
        }
        updateTopCounters();
        showToast('تم تحديث البيانات الرقابية', 'info');
    }

    // إشعار Toast
    function showToast(message, type = 'success') {
        const toast = document.getElementById('modToastNotification');
        const text = document.getElementById('modToastMessage');
        const icon = document.getElementById('modToastIcon');

        if (!toast || !text) return;

        text.textContent = message;
        if (type === 'error') {
            icon.className = 'fa-solid fa-circle-xmark';
            icon.style.color = '#ef4444';
        } else if (type === 'info') {
            icon.className = 'fa-solid fa-circle-info';
            icon.style.color = '#3b82f6';
        } else {
            icon.className = 'fa-solid fa-circle-check';
            icon.style.color = '#10b981';
        }

        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3500);
    }

    // Explicitly attach all interactive functions to window
    window.switchModTab = switchModTab;
    window.loadUniversityAudit = loadUniversityAudit;
    window.renderAuditorAssets = renderAuditorAssets;
    window.openQuarantineModal = openQuarantineModal;
    window.closeQuarantineModal = closeQuarantineModal;
    window.onReasonPresetChange = onReasonPresetChange;
    window.submitQuarantineAction = submitQuarantineAction;
    window.resolveQuarantineDirect = resolveQuarantineDirect;
    window.filterQuarantineRows = filterQuarantineRows;
    window.searchQuarantinesTable = searchQuarantinesTable;
    window.refreshCurrentView = refreshCurrentView;
    window.showToast = showToast;
</script>
@endsection
