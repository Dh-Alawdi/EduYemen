@extends('dashboards.admin.layout')

@section('page_title', 'مركز تدفق ومراقبة طلبات الالتحاق')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>مركز تدفق ومراقبة طلبات الالتحاق</strong>
@endsection

@section('content')

@php
    $curMonth = date('m');
    $curYear = date('Y');

    // استخراج أجزاء تاريخ (من) إن وُجد في الطلب
    $fromDay = '';
    $fromMonth = $curMonth;
    $fromYear = $curYear;
    $hasFrom = false;
    if (request('date_from')) {
        try {
            $parsedFrom = \Carbon\Carbon::parse(request('date_from'));
            $fromDay = $parsedFrom->format('d');
            $fromMonth = $parsedFrom->format('m');
            $fromYear = $parsedFrom->format('Y');
            $hasFrom = true;
        } catch (\Exception $e) {}
    }

    // استخراج أجزاء تاريخ (إلى) إن وُجد في الطلب
    $toDay = '';
    $toMonth = $curMonth;
    $toYear = $curYear;
    $hasTo = false;
    if (request('date_to')) {
        try {
            $parsedTo = \Carbon\Carbon::parse(request('date_to'));
            $toDay = $parsedTo->format('d');
            $toMonth = $parsedTo->format('m');
            $toYear = $parsedTo->format('Y');
            $hasTo = true;
        } catch (\Exception $e) {}
    }
@endphp

<!-- ========================================================================
     1. الحاوية الأولى: ترويسة المركز الإشرافي ونسبة الإنجاز بالجامعات فقط
     ======================================================================== -->
<div class="cockpit-container">
    <div class="cockpit-header">
        <div class="cockpit-title-group">
            <div class="cockpit-live-badge">
                <span class="live-pulse-dot"></span>
                <span class="live-label">نظام المراقبة الميدانية المباشر</span>
            </div>
            <h1 class="cockpit-main-title">
                مركز تدفق ومراقبة طلبات الالتحاق
            </h1>
            <p class="cockpit-subtitle">
                رصد تشغيلي ورقابي متزامن لسلامة مسار التنسيق والقبول الجامعي • الصلاحيات التنفيذية وقرارات القبول والسداد محصورة بالجامعات
            </p>
        </div>

        <!-- نسبة الإنجاز بالجامعات فقط -->
        <div class="cockpit-sys-meter">
            <div class="sys-meter-label">نسبة الإنجاز بالجامعات</div>
            @php
                $processedCount = ($stats['accepted'] ?? 0) + ($stats['rejected'] ?? 0);
                $totalCount = ($stats['total'] ?? 0) > 0 ? $stats['total'] : 1;
                $resolutionRate = round(($processedCount / $totalCount) * 100);
            @endphp
            <div class="sys-meter-bar-wrap">
                <div class="sys-meter-bar" style="width: {{ $resolutionRate }}%;"></div>
            </div>
            <div class="sys-meter-values">
                <span>{{ number_format($processedCount) }} طلب منجز</span>
                <strong>{{ $resolutionRate }}%</strong>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================
     2. شريط أدوات الرقابة والبحث والتصفية الذكية
     ======================================================================== -->
<div class="supervisory-toolbar">
    <form method="GET" action="{{ route('admin.requests') }}" id="filterForm" class="toolbar-form">
        <!-- الحفاظ على الفلاتر النشطة -->
        <input type="hidden" name="tab" id="filterTab" value="{{ request('tab', '') }}">
        <input type="hidden" name="status" id="filterStatus" value="{{ (!request('tab') && request('status')) ? request('status') : '' }}">

        <!-- القائمة المنسدلة المنسقة لحالات الطلبات في وسط الصفحة مع عرض الأعداد -->
        <div class="quick-chips-wrapper status-dropdown-center-wrapper">
            <div class="status-dropdown-box">
                <label for="statusQuickSelect" class="status-dropdown-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="chips-svg-icon"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    <span>حالة الطلبات:</span>
                </label>
                <div class="status-select-wrap">
                    <select id="statusQuickSelect" class="status-select-control" onchange="onStatusDropdownChange(this.value)">
                        <option value="all" {{ (!request('tab') && !request('status')) ? 'selected' : '' }}>
                            كافة الطلبات ({{ number_format($stats['total']) }})
                        </option>
                        <option value="tab:pending" {{ (request('tab') === 'pending' || request('status') === 'قيد المراجعة') ? 'selected' : '' }}>
                            الحالات المعلقة ({{ number_format($stats['pending']) }})
                        </option>
                        <option value="tab:recent" {{ request('tab') === 'recent' ? 'selected' : '' }}>
                            الطلبات الحديثة ({{ number_format($stats['recent']) }})
                        </option>
                        <option value="status:مقبول" {{ request('status') === 'مقبول' ? 'selected' : '' }}>
                            المقبولة ({{ number_format($stats['accepted']) }})
                        </option>
                        <option value="status:يتطلب استكمال" {{ request('status') === 'يتطلب استكمال' ? 'selected' : '' }}>
                            تتطلب استكمال ({{ number_format($stats['needs_complete']) }})
                        </option>
                        <option value="status:مرفوض" {{ request('status') === 'مرفوض' ? 'selected' : '' }}>
                            المرفوضة ({{ number_format($stats['rejected']) }})
                        </option>
                    </select>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="select-chevron-svg"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
        </div>

        <div class="toolbar-divider"></div>

        <!-- سطر الفلاتر وحقول التاريخ والبحث -->
        <div class="toolbar-controls-row">
            <!-- 1. فلتر الجامعة -->
            <div class="control-col col-univ">
                <div class="field-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4M5 21V10.85M19 21V10.85M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/></svg>
                </div>
                <select name="university_id" id="universitySelect" class="control-select" onchange="triggerAutoFilter(true)">
                    <option value="">جميع الجامعات المعتمدة ({{ $universities->count() }})</option>
                    @foreach($universities as $u)
                        <option value="{{ $u->university_id }}" {{ request('university_id') == $u->university_id ? 'selected' : '' }}>
                            {{ $u->university_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. حقول التاريخ الذكية (يبدأ باليوم ثم الشهر ثم العام - الشهر والعام مكتوبان تلقائياً - لا يؤثر على البحث إذا لم يحدد) -->
            <div class="control-col col-dates">
                <div class="smart-date-group">
                    <!-- تاريخ من -->
                    <div class="seg-date-picker {{ $hasFrom ? 'is-active-date' : '' }}" id="pickerWrapFrom" title="اكتب اليوم أولاً، الشهر والعام محدد تلقائياً، أو افتح التقويم">
                        <span class="seg-date-title">من:</span>
                        
                        <div class="seg-inputs-cluster" dir="ltr">
                            <!-- اليوم: يبدأ بكتابته -->
                            <input type="text" 
                                   id="dayFrom" 
                                   class="seg-sub-input input-day" 
                                   maxlength="2" 
                                   placeholder="DD" 
                                   value="{{ $fromDay }}" 
                                   autocomplete="off"
                                   title="اكتب رقم اليوم">
                            <span class="seg-sep">/</span>
                            <!-- الشهر: تلقائي لشهر اليوم الحالي ومحدد عند النقر -->
                            <input type="text" 
                                   id="monthFrom" 
                                   class="seg-sub-input input-month" 
                                   maxlength="2" 
                                   placeholder="MM" 
                                   value="{{ $fromMonth }}" 
                                   autocomplete="off"
                                   title="شهر اليوم الحالي - انقر لتحديده">
                            <span class="seg-sep">/</span>
                            <!-- العام: تلقائي للعام الحالي ومحدد عند النقر -->
                            <input type="text" 
                                   id="yearFrom" 
                                   class="seg-sub-input input-year" 
                                   maxlength="4" 
                                   placeholder="YYYY" 
                                   value="{{ $fromYear }}" 
                                   autocomplete="off"
                                   title="العام الحالي - انقر لتحديده">
                        </div>

                        <!-- الحقل المرسل: لا يحمل قيمة إلا إذا تم تحديد اليوم لكي لا يتأثر البحث -->
                        <input type="hidden" name="date_from" id="hiddenDateFrom" value="{{ $hasFrom ? request('date_from') : '' }}">

                        <!-- زر مسح التاريخ -->
                        <button type="button" class="btn-seg-clear" id="clearBtnFrom" onclick="resetDateSegment('From')" title="إلغاء تحديد تاريخ من" style="{{ $hasFrom ? 'display:inline-flex;' : 'display:none;' }}">
                            &times;
                        </button>

                        <!-- تقويم منتقي أصلي -->
                        <input type="date" id="nativeFrom" class="native-picker-hidden" onchange="onNativePicked('From', this.value)">
                        <button type="button" class="btn-seg-calendar" onclick="triggerNativePicker('nativeFrom')" title="فتح التقويم المرئي">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </button>
                    </div>

                    <span class="date-link-arrow">←</span>

                    <!-- تاريخ إلى -->
                    <div class="seg-date-picker {{ $hasTo ? 'is-active-date' : '' }}" id="pickerWrapTo" title="اكتب اليوم أولاً، الشهر والعام محدد تلقائياً، أو افتح التقويم">
                        <span class="seg-date-title">إلى:</span>
                        
                        <div class="seg-inputs-cluster" dir="ltr">
                            <input type="text" 
                                   id="dayTo" 
                                   class="seg-sub-input input-day" 
                                   maxlength="2" 
                                   placeholder="DD" 
                                   value="{{ $toDay }}" 
                                   autocomplete="off"
                                   title="اكتب رقم اليوم">
                            <span class="seg-sep">/</span>
                            <input type="text" 
                                   id="monthTo" 
                                   class="seg-sub-input input-month" 
                                   maxlength="2" 
                                   placeholder="MM" 
                                   value="{{ $toMonth }}" 
                                   autocomplete="off"
                                   title="شهر اليوم الحالي - انقر لتحديده">
                            <span class="seg-sep">/</span>
                            <input type="text" 
                                   id="yearTo" 
                                   class="seg-sub-input input-year" 
                                   maxlength="4" 
                                   placeholder="YYYY" 
                                   value="{{ $toYear }}" 
                                   autocomplete="off"
                                   title="العام الحالي - انقر لتحديده">
                        </div>

                        <!-- الحقل المرسل: لا يحمل قيمة إلا إذا تم تحديد اليوم لكي لا يتأثر البحث -->
                        <input type="hidden" name="date_to" id="hiddenDateTo" value="{{ $hasTo ? request('date_to') : '' }}">

                        <!-- زر مسح التاريخ -->
                        <button type="button" class="btn-seg-clear" id="clearBtnTo" onclick="resetDateSegment('To')" title="إلغاء تحديد تاريخ إلى" style="{{ $hasTo ? 'display:inline-flex;' : 'display:none;' }}">
                            &times;
                        </button>

                        <!-- تقويم منتقي أصلي -->
                        <input type="date" id="nativeTo" class="native-picker-hidden" onchange="onNativePicked('To', this.value)">
                        <button type="button" class="btn-seg-calendar" onclick="triggerNativePicker('nativeTo')" title="فتح التقويم المرئي">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. البحث السريع والتصفية الفورية التلقائية -->
            <div class="control-col col-search">
                <div class="smart-search-box">
                    <svg class="search-addon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="control-search-input" placeholder="رقم الطلب، اسم الطالب، رقم الهاتف..." autocomplete="off">
                    <button type="button" id="btnResetFilters" onclick="resetAllFilters(event)" class="btn-reset-filters" title="إلغاء كافة الفلاتر والبحث" style="{{ request()->anyFilled(['university_id', 'tab', 'status', 'date_from', 'date_to', 'search']) ? '' : 'display:none;' }}">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ========================================================================
     3. جدول المتابعة العصري فائق الوضوح
     ======================================================================== -->
<div class="stream-matrix-card" id="requestsResultsContainer">
    <div class="matrix-header-bar">
        <div class="matrix-meta-left">
            <div class="matrix-title-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                <span>سجل المتابعة الإشرافي للطلبات</span>
            </div>
            <span class="matrix-counter-tag">{{ number_format($requests->total()) }} معاملة مسجلة</span>
        </div>
        <div class="matrix-actions-right" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.requests.export', request()->query()) }}" class="btn-matrix-export" id="btnExportRequests" title="تصدير كافة طلبات الالتحاق المطابقة للبحث والتصفية الحالية إلى ملف Excel / CSV">
                <i class="fa-solid fa-file-arrow-down"></i>
                <span>تصدير الطلبات (CSV)</span>
            </a>
        </div>
    </div>

    <div class="matrix-table-responsive">
        <table class="stream-table">
            <thead>
                <tr>
                    <th class="th-id" style="width:100px; text-align:center;">رقم المعاملة</th>
                    <th class="th-student">اسم الطالب</th>
                    <th class="th-univ">الجامعة</th>
                    <th class="th-date" style="width:140px;">تاريخ التقديم</th>
                    <!-- لا يوجد عمود مستقل لتاريخ المراجعة؛ مدمج مع حالة الطلب عملاً بالمحددات -->
                    <th class="th-status" style="width:190px;">حالة الطلب وتاريخ مراجعته</th>
                    <th class="th-action" style="width:70px; text-align:center;">معاينة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    @php
                        $statusTheme = match($req->status) {
                            'مقبول'         => 'st-theme-accepted',
                            'قيد المراجعة'  => 'st-theme-pending',
                            'يتطلب استكمال' => 'st-theme-complete',
                            'مرفوض'         => 'st-theme-rejected',
                            'ملغي'          => 'st-theme-cancelled',
                            default         => 'st-theme-default'
                        };
                    @endphp
                    <tr class="stream-row">
                        <!-- 1. رقم المعاملة -->
                        <td class="td-id" style="text-align:center;">
                            <span class="order-id-badge">#{{ $req->req_id }}</span>
                        </td>

                        <!-- 2. اسم الطالب -->
                        <td class="td-student">
                            <div class="student-profile-cell">
                                <div class="student-avatar-ring">
                                    {{ mb_substr($req->full_name, 0, 1) }}
                                </div>
                                <div class="student-text-group">
                                    <div class="student-full-name">{{ $req->full_name }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- 3. الجامعة -->
                        <td class="td-univ">
                            <div class="academic-target-cell">
                                <div class="academic-univ-title">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="academic-svg"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                    <span>{{ $req->university?->university_name ?? '—' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- 4. تاريخ التقديم -->
                        <td class="td-date">
                            <div class="submission-date-cell">
                                <span class="date-calendar-str">{{ $req->req_date ? $req->req_date->format('Y/m/d') : '—' }}</span>
                                <div class="date-time-tag">
                                    <span>{{ $req->req_date ? $req->req_date->format('H:i') : '' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- 5. حالة الطلب وتاريخ مراجعته (مدمجان في عمود واحد دون عمود مستقل لتاريخ المراجعة) -->
                        <td class="td-status">
                            <div class="status-decision-stack">
                                <span class="status-quantum-pill {{ $statusTheme }}">
                                    @if($req->status === 'مقبول')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    @elseif($req->status === 'قيد المراجعة')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    @elseif($req->status === 'يتطلب استكمال')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    @elseif($req->status === 'مرفوض')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    @endif
                                    {{ $req->status }}
                                </span>

                                @if($req->decision_date)
                                    <!-- تاريخ المراجعة مدمج داخل نفس الخلية أسفل الحالة مباشرة -->
                                    <div class="review-subline">
                                        <span class="decision-recorded">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                            روجع: {{ $req->decision_date->format('Y/m/d') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- 6. زر المعاينة الإشرافية المقتصرة على الحقول المحددة -->
                        <td class="td-action" style="text-align:center;">
                            <button type="button" class="btn-inspect-stream" onclick="openReqOverviewModal({{ $req->req_id }})" title="معاينة إشرافية للطلب">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-stream-cell">
                            <div class="empty-stream-box">
                                <div class="empty-icon-halo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M8 11h6"/></svg>
                                </div>
                                <h4 class="empty-main-text">لم يتم العثور على أي طلبات مطابقة للمعايير المحددة</h4>
                                <p class="empty-sub-text">يرجى تجربة تعديل فلاتر البحث أو إعادة تعيين التصفية لاستعراض كافة طلبات الالتحاق المرفوعة بالمنظومة</p>
                                <a href="{{ route('admin.requests') }}" class="btn-reset-stream">
                                    إعادة تعيين واستعراض كافة الطلبات
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- شريط الترقيم والإحصاء السفلي المتطور فائق الوضوح -->
    <div class="matrix-pagination-strip" id="paginationStrip">
        @php
            $totalCount = $requests->total();
            $firstItem = $requests->firstItem() ?? 0;
            $lastItem = $requests->lastItem() ?? 0;
            $remaining = max(0, $totalCount - $lastItem);
            $currentPage = $requests->currentPage();
            $lastPage = $requests->lastPage();
        @endphp
        <div class="pagination-summary">
            <div class="summary-chip-group">
                <span class="summary-pill current-range">
                    <i class="fa-solid fa-list-ol"></i>
                    عرض <strong>{{ $firstItem }}</strong> إلى <strong>{{ $lastItem }}</strong> من أصل <strong>{{ number_format($totalCount) }}</strong> طلب
                </span>
                <span class="summary-pill remaining-count {{ $remaining > 0 ? 'has-remaining' : 'all-done' }}">
                    <i class="fa-solid fa-hourglass-half"></i>
                    المتبقي للاستعراض: <strong>{{ number_format($remaining) }}</strong> طلب
                </span>
            </div>
        </div>

        @if($lastPage > 1)
            <div class="pagination-navigation-modern">
                {{-- زر السابق --}}
                @if($requests->onFirstPage())
                    <span class="modern-page-btn disabled" aria-disabled="true">
                        <i class="fa-solid fa-chevron-right"></i>
                        السابق
                    </span>
                @else
                    <a href="{{ $requests->previousPageUrl() }}" class="modern-page-btn nav-link-btn" title="الانتقال للصفحة السابقة">
                        <i class="fa-solid fa-chevron-right"></i>
                        السابق
                    </a>
                @endif

                {{-- مؤشر الصفحة الحالية --}}
                <div class="modern-page-indicators">
                    <span class="page-current-indicator">
                        صفحة <strong>{{ $currentPage }}</strong> من <strong>{{ $lastPage }}</strong>
                    </span>
                </div>

                {{-- زر التالي --}}
                @if($requests->hasMorePages())
                    <a href="{{ $requests->nextPageUrl() }}" class="modern-page-btn nav-link-btn" title="الانتقال للصفحة التالية">
                        التالي
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @else
                    <span class="modern-page-btn disabled" aria-disabled="true">
                        التالي
                        <i class="fa-solid fa-chevron-left"></i>
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- ========================================================================
     4. نافذة المعاينة الإشرافية (Modal) مع زر التواصل الهاتفي المباشر بالجامعة
     ======================================================================== -->
<div id="reqModal" class="stream-modal-backdrop" style="display:none;" tabindex="-1" role="dialog" aria-hidden="true" onclick="if(event.target===this) closeReqModal()">
    <div class="stream-modal-dialog">
        <div class="modal-cockpit-header">
            <div class="modal-header-meta">
                <div class="modal-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div>
                    <h3 id="modalReqTitle" class="modal-heading">ملخص طلب الالتحاق</h3>
                    <span id="modalReqSub" class="modal-subheading">رقم المعاملة: #—</span>
                </div>
            </div>
            <button type="button" class="modal-dismiss-btn" onclick="closeReqModal()">&times;</button>
        </div>

        <div class="modal-cockpit-body">
            <!-- سياسة الرقابة والحوكمة -->
            <div class="modal-governance-notice">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <div>
                    <strong>ضوابط الحوكمة والرقابة:</strong>
                    هذه النافذة مخصصة للمتابعة الإشرافية فقط. لا تملك إدارة النظام صلاحية اعتماد أو تعديل أو البت في الطلبات أو سندات السداد، حيث أنها اختصاص تنفيذي مستقل للجامعة.
                </div>
            </div>

            <div class="modal-entries-stream" id="modalDetailsGrid">
                <div class="modal-loading-state">
                    <div class="modal-loading-spinner"></div>
                    <span>جاري استدعاء السجلات الإشرافية...</span>
                </div>
            </div>
        </div>

        <div class="modal-cockpit-footer">
            <button type="button" class="btn-modal-dismiss" onclick="closeReqModal()">إغلاق النافذة</button>
        </div>
    </div>
</div>

<!-- ========================================================================
     5. التنسيقات العصرية
     ======================================================================== -->
<style>
/* ---------------- 1. ترويسة الحاوية الأولى ونسبة الإنجاز ---------------- */
.cockpit-container {
    background: linear-gradient(135deg, #0b1329 0%, #111d40 50%, #0d1836 100%);
    border-radius: 18px;
    padding: 24px 28px;
    color: #ffffff;
    box-shadow: 0 14px 34px -10px rgba(11, 19, 41, 0.42), 0 0 0 1px rgba(255, 255, 255, 0.08);
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}

.cockpit-container::before {
    content: '';
    position: absolute;
    top: -40px;
    left: -40px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.16) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.cockpit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.cockpit-title-group {
    flex: 1 1 380px;
}

.cockpit-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(6, 182, 212, 0.12);
    border: 1px solid rgba(6, 182, 212, 0.3);
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    color: #38bdf8;
    margin-bottom: 8px;
}

.live-pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #38bdf8;
    box-shadow: 0 0 8px #38bdf8;
    animation: livePulse 2s infinite;
}

@keyframes livePulse {
    0% { transform: scale(0.9); opacity: 0.7; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.9); opacity: 0.7; }
}

.cockpit-main-title {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 6px 0;
    letter-spacing: -0.3px;
    color: #ffffff;
}

.cockpit-subtitle {
    font-size: 13px;
    color: #94a3b8;
    margin: 0;
    line-height: 1.5;
    max-width: 650px;
}

/* نسبة الإنجاز بالجامعات */
.cockpit-sys-meter {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    padding: 14px 20px;
    min-width: 220px;
    backdrop-filter: blur(8px);
}

.sys-meter-label {
    font-size: 12px;
    font-weight: 700;
    color: #cbd5e1;
    margin-bottom: 8px;
}

.sys-meter-bar-wrap {
    height: 7px;
    background: rgba(255, 255, 255, 0.14);
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 8px;
}

.sys-meter-bar {
    height: 100%;
    background: linear-gradient(90deg, #06b6d4, #10b981);
    border-radius: 5px;
    transition: width 0.6s ease;
}

.sys-meter-values {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #cbd5e1;
}

.sys-meter-values strong {
    color: #38bdf8;
    font-size: 13.5px;
}

/* ---------------- 2. شريط أدوات الرقابة والبحث والتصفية ---------------- */
.supervisory-toolbar {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px -4px rgba(15, 23, 42, 0.05);
    padding: 18px 22px;
    margin-bottom: 22px;
}

.toolbar-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* القائمة المنسدلة المنسقة لحالات الطلبات في وسط الصفحة */
.status-dropdown-center-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin: 0 auto;
    padding: 4px 0 10px;
}

.status-dropdown-box {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    padding: 6px 14px 6px 8px;
    box-shadow: 0 4px 14px rgba(11, 61, 107, 0.06);
    transition: all 0.25s ease;
    max-width: 480px;
    width: 100%;
}

.status-dropdown-box:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.15);
}

.status-dropdown-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
    margin: 0;
    cursor: default;
}

.status-dropdown-label svg {
    width: 16px;
    height: 16px;
    color: #0284c7;
}

.status-select-wrap {
    position: relative;
    flex: 1;
    display: flex;
    align-items: center;
}

.status-select-control {
    width: 100%;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 32px 8px 12px;
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    transition: all 0.2s ease;
}

.status-select-control:focus {
    background: #ffffff;
    border-color: #0284c7;
}

.select-chevron-svg {
    position: absolute;
    left: 10px;
    width: 14px;
    height: 14px;
    color: #64748b;
    pointer-events: none;
}

.toolbar-divider {
    height: 1px;
    background: #f1f5f9;
}

/* سطر الفلاتر والبحث */
.toolbar-controls-row {
    display: grid;
    grid-template-columns: 1.3fr 1.6fr 1.6fr;
    gap: 14px;
    align-items: center;
}

.control-col {
    position: relative;
    display: flex;
    align-items: center;
}

.field-icon-wrap {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #64748b;
    pointer-events: none;
    z-index: 2;
}

.field-icon-wrap svg {
    width: 100%;
    height: 100%;
}

.control-select {
    width: 100%;
    height: 44px;
    padding: 0 42px 0 14px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.control-select:focus {
    border-color: #0284c7;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
}

/* ---------------- حقول التاريخ المجزأة الذكية ---------------- */
.smart-date-group {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}

.seg-date-picker {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 3px 8px 3px 12px;
    height: 44px;
    flex: 1;
    position: relative;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.seg-date-picker:focus-within {
    border-color: #0284c7;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
}

.seg-date-picker.is-active-date {
    border-color: #0284c7;
    background: #f0f9ff;
}

.seg-date-title {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}

.seg-inputs-cluster {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
}

.seg-sub-input {
    border: none;
    background: transparent;
    font-family: ui-monospace, monospace;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    outline: none;
    text-align: center;
    padding: 2px 0;
    border-radius: 4px;
    transition: background 0.15s ease, color 0.15s ease;
}

.seg-sub-input::placeholder {
    color: #cbd5e1;
    font-weight: 400;
}

.seg-sub-input:focus {
    background: #e0f2fe;
    color: #0369a1;
}

.seg-sub-input.input-day {
    width: 28px;
}

.seg-sub-input.input-month {
    width: 28px;
}

.seg-sub-input.input-year {
    width: 44px;
}

.seg-sep {
    color: #94a3b8;
    font-weight: 700;
    font-size: 12px;
    user-select: none;
}

.btn-seg-clear {
    background: #fee2e2;
    color: #dc2626;
    border: none;
    cursor: pointer;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    line-height: 1;
    transition: background 0.15s ease;
    padding: 0;
    margin-right: 2px;
}

.btn-seg-clear:hover {
    background: #fca5a5;
}

.btn-seg-calendar {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.btn-seg-calendar:hover {
    color: #0284c7;
    background: #e0f2fe;
}

.btn-seg-calendar svg {
    width: 16px;
    height: 16px;
}

.native-picker-hidden {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}

.date-link-arrow {
    color: #94a3b8;
    font-size: 12px;
    font-weight: bold;
}

/* البحث الذكي */
.smart-search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    position: relative;
}

.search-addon-svg {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: #94a3b8;
    pointer-events: none;
}

.control-search-input {
    width: 100%;
    height: 44px;
    padding: 0 40px 0 14px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-size: 13px;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
}

.control-search-input:focus {
    border-color: #0284c7;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
}

.btn-apply-filters {
    height: 44px;
    padding: 0 18px;
    border-radius: 12px;
    background: #0f172a;
    color: #ffffff;
    border: none;
    font-size: 12.5px;
    font-weight: 700;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.btn-apply-filters:hover {
    background: #1e293b;
    transform: translateY(-1px);
}

.btn-reset-filters {
    height: 44px;
    padding: 0 14px;
    border-radius: 12px;
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.btn-reset-filters:hover {
    background: #fca5a5;
    color: #991b1b;
}

/* ---------------- 3. جدول المتابعة العصري ---------------- */
.stream-matrix-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

.matrix-header-bar {
    padding: 16px 22px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.matrix-meta-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.matrix-title-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14.5px;
    font-weight: 800;
    color: #0f172a;
}

.matrix-title-badge svg {
    width: 18px;
    height: 18px;
    color: #0284c7;
}

.matrix-counter-tag {
    background: #e2e8f0;
    color: #334155;
    font-size: 11.5px;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 12px;
}

.matrix-governance-pill {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
}

.matrix-governance-pill svg {
    width: 14px;
    height: 14px;
    color: #2563eb;
}

.btn-matrix-export {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #059669;
    color: #ffffff !important;
    font-size: 12.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(5, 150, 105, 0.22);
}

.btn-matrix-export:hover {
    background: #047857;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.32);
}

.btn-matrix-export i {
    font-size: 13px;
}

.matrix-table-responsive {
    overflow-x: auto;
}

.stream-table {
    width: 100%;
    border-collapse: collapse;
    text-align: right;
    font-size: 13px;
}

.stream-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    padding: 13px 18px;
    border-bottom: 1.5px solid #e2e8f0;
    font-size: 12.5px;
    white-space: nowrap;
}

.stream-table td {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}

.stream-row:hover td {
    background: #fbfcfe;
}

.order-id-badge {
    display: inline-block;
    font-family: ui-monospace, monospace;
    font-size: 13px;
    font-weight: 800;
    color: #0284c7;
    background: #e0f2fe;
    padding: 3px 9px;
    border-radius: 8px;
    border: 1px solid #bae6fd;
}

/* الطالب */
.student-profile-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.student-avatar-ring {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0f172a, #334155);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 14px;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(15, 23, 42, 0.2);
}

.student-text-group {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.student-full-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 13.5px;
}

.student-phone-row {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}

.phone-svg {
    width: 12px;
    height: 12px;
    color: #64748b;
}

.phone-link {
    color: #64748b;
    text-decoration: none;
    font-family: ui-monospace, monospace;
    font-size: 12px;
    transition: color 0.15s ease;
}

.phone-link:hover {
    color: #0284c7;
    text-decoration: underline;
}

.phone-unavailable {
    font-size: 11.5px;
    color: #94a3b8;
}

/* الجامعة والتخصص */
.academic-target-cell {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.academic-univ-title {
    font-weight: 700;
    color: #1e293b;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.academic-svg {
    width: 14px;
    height: 14px;
    color: #64748b;
    flex-shrink: 0;
}

.academic-spec-name {
    font-size: 12px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 5px;
    padding-right: 4px;
}

.spec-bullet {
    color: #0284c7;
    font-size: 10px;
}

/* التاريخ */
.submission-date-cell {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date-calendar-str {
    font-family: ui-monospace, monospace;
    font-weight: 600;
    color: #334155;
    font-size: 12.5px;
}

.date-time-tag {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    color: #94a3b8;
}

.freshness-chip {
    font-size: 9.5px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 6px;
    background: #dbeafe;
    color: #1d4ed8;
}

/* الحالة وتاريخ المراجعة مدمجان في نفس الخلية */
.status-decision-stack {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.status-quantum-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    align-self: flex-start;
}

.status-quantum-pill svg {
    width: 13px;
    height: 13px;
}

.st-theme-accepted { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.st-theme-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.st-theme-complete { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
.st-theme-rejected { background: #ffe4e6; color: #be123c; border: 1px solid #fecdd3; }
.st-theme-cancelled { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
.st-theme-default { background: #f1f5f9; color: #475569; }

.review-subline {
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.decision-recorded {
    color: #059669;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.decision-recorded svg { width: 12px; height: 12px; }

.decision-awaiting {
    color: #94a3b8;
    font-style: italic;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.awaiting-pulse {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #d97706;
}

/* حالة السداد */
.pay-quantum-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}

.pay-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.pay-theme-success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.pay-theme-success .pay-dot { background: #047857; }

.pay-theme-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.pay-theme-warning .pay-dot { background: #b45309; }

.pay-theme-danger { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
.pay-theme-danger .pay-dot { background: #be123c; }

.pay-theme-muted { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
.pay-theme-muted .pay-dot { background: #94a3b8; }

/* زر المعاينة */
.btn-inspect-stream {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #f8fafc;
    color: #0284c7;
    border: 1.5px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-inspect-stream svg { width: 17px; height: 17px; }

.btn-inspect-stream:hover {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
    transform: scale(1.06);
}

/* Empty State */
.empty-stream-cell {
    text-align: center;
    padding: 50px 20px !important;
}

.empty-stream-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 480px;
    margin: 0 auto;
}

.empty-icon-halo {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
}

.empty-icon-halo svg { width: 28px; height: 28px; }
.empty-main-text { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 6px 0; }
.empty-sub-text { font-size: 12.5px; color: #94a3b8; margin: 0 0 16px 0; line-height: 1.5; }

.btn-reset-stream {
    padding: 8px 18px;
    border-radius: 10px;
    background: #0f172a;
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s ease;
}

.btn-reset-stream:hover { background: #1e293b; }

.matrix-pagination-strip {
    padding: 14px 22px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    border-radius: 0 0 var(--radius-md) var(--radius-md);
}

.pagination-summary {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.summary-chip-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.summary-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12.5px;
    font-weight: 600;
}

.summary-pill.current-range {
    background: #eef4f9;
    color: #072b4f;
    border: 1px solid #d0e0ed;
}

.summary-pill.remaining-count {
    background: #fdf8eb;
    color: #8c6b12;
    border: 1px solid #fae6b2;
}

.summary-pill.remaining-count.all-done {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.pagination-navigation-modern {
    display: flex;
    align-items: center;
    gap: 8px;
}

.modern-page-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 15px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #0b3d6b;
    background: #ffffff;
    border: 1.5px solid #d0e0ed;
    transition: all .2s ease;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(11, 61, 107, 0.05);
}

.modern-page-btn:hover:not(.disabled) {
    background: #0b3d6b;
    color: #ffffff;
    border-color: #0b3d6b;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(11, 61, 107, 0.15);
}

.modern-page-btn.disabled {
    background: #f1f5f9;
    color: #94a3b8;
    border-color: #e2e8f0;
    cursor: not-allowed;
    box-shadow: none;
}

.page-current-indicator {
    padding: 7px 13px;
    background: #072b4f;
    color: #ffffff;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
}
.page-current-indicator strong {
    color: #f0c842;
}

/* ---------------- 4. نافذة المعاينة والاتصال بالجامعة ---------------- */
.stream-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(11, 19, 41, 0.72);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
}

.stream-modal-dialog {
    background: #ffffff;
    border-radius: 20px;
    max-width: 580px;
    width: 100%;
    box-shadow: 0 25px 60px -15px rgba(11, 19, 41, 0.4);
    overflow: hidden;
    animation: modalPopIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid rgba(255, 255, 255, 0.8);
}

@keyframes modalPopIn {
    from { transform: scale(0.94); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.modal-cockpit-header {
    padding: 16px 22px;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-header-meta {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-header-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #38bdf8;
}

.modal-header-icon svg { width: 18px; height: 18px; }
.modal-heading { margin: 0; font-size: 15px; font-weight: 800; color: #ffffff; }
.modal-subheading { font-size: 11.5px; color: #94a3b8; }

.modal-dismiss-btn {
    background: transparent;
    border: none;
    font-size: 24px;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    transition: color 0.15s ease;
}

.modal-dismiss-btn:hover { color: #ffffff; }

.modal-cockpit-body {
    padding: 22px;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-governance-notice {
    display: flex;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: 11.5px;
    line-height: 1.5;
    margin-bottom: 18px;
}

.modal-governance-notice svg {
    width: 18px;
    height: 18px;
    color: #2563eb;
    flex-shrink: 0;
    margin-top: 2px;
}

.modal-record-item {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 11px 15px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* حاوية الجامعة وزر الاتصال */
.univ-contact-card {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.univ-info-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.univ-main-name {
    font-weight: 800;
    color: #14532d;
    font-size: 13.5px;
}

.univ-phone-text {
    font-size: 12px;
    color: #166534;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-call-univ {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 10px;
    background: #16a34a;
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.btn-call-univ:hover {
    background: #15803d;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(22, 163, 74, 0.35);
}

.btn-call-univ svg {
    width: 15px;
    height: 15px;
}

.btn-call-disabled {
    font-size: 11.5px;
    color: #94a3b8;
    background: #e2e8f0;
    padding: 6px 12px;
    border-radius: 8px;
}

.modal-record-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.modal-record-value {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

.modal-loading-state {
    text-align: center;
    padding: 30px;
    color: #94a3b8;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.modal-loading-spinner {
    width: 28px;
    height: 28px;
    border: 3px solid #e2e8f0;
    border-top-color: #0284c7;
    border-radius: 50%;
    animation: modalSpin 0.8s linear infinite;
}

@keyframes modalSpin { to { transform: rotate(360deg); } }

.modal-cockpit-footer {
    padding: 12px 22px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
}

.btn-modal-dismiss {
    padding: 7px 18px;
    border-radius: 8px;
    background: #e2e8f0;
    color: #334155;
    font-weight: 700;
    font-size: 12.5px;
    border: none;
    cursor: pointer;
    transition: background 0.15s ease;
}

.btn-modal-dismiss:hover { background: #cbd5e1; }

/* التجاوب */
@media (max-width: 992px) {
    .toolbar-controls-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .cockpit-container { padding: 14px 12px; }
    .cockpit-main-title { font-size: 18px; }
    .cockpit-sys-meter { width: 100%; }
    .smart-date-group { flex-direction: column; gap: 10px; }
    .date-link-arrow { transform: rotate(90deg); }

    /* حصر أعمدة الجدول في الشاشات الصغيرة على 3 أعمدة فقط: الاسم، الجامعة، زر العين */
    .th-id, .td-id,
    .th-date, .td-date,
    .th-status, .td-status,
    .th-pay, .td-pay {
        display: none !important;
    }

    .matrix-table-responsive {
        overflow-x: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .stream-table {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
    }

    .th-student, .td-student {
        width: 48% !important;
        padding: 10px 8px !important;
    }

    .th-univ, .td-univ {
        width: 38% !important;
        padding: 10px 8px !important;
    }

    .th-action, .td-action {
        width: 14% !important;
        padding: 10px 4px !important;
        text-align: center !important;
    }

    /* حماية النصوص من تجاوز أبعاد الشاشة */
    .student-profile-cell {
        gap: 6px !important;
    }
    .student-avatar-ring {
        width: 28px !important;
        height: 28px !important;
        font-size: 11px !important;
        flex-shrink: 0;
    }
    .student-full-name {
        font-size: 12px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 115px;
    }
    .student-phone-row {
        font-size: 10px !important;
    }
    .phone-link {
        font-size: 10px !important;
    }
    .academic-target-cell {
        gap: 2px;
    }
    .academic-univ-title {
        font-size: 11.5px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 95px;
    }
    .academic-spec-name {
        font-size: 10px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 95px;
    }
    .btn-inspect-stream {
        width: 32px !important;
        height: 32px !important;
        font-size: 13px !important;
        margin: 0 auto;
    }

    /* شريط الترقيم والإحصاء في الشاشات الصغيرة */
    .matrix-pagination-strip {
        flex-direction: column;
        align-items: stretch;
        padding: 12px 14px;
        gap: 12px;
    }
    .summary-chip-group {
        justify-content: space-between;
        width: 100%;
        gap: 8px;
    }
    .summary-pill {
        font-size: 11px;
        padding: 5px 10px;
        flex: 1;
        justify-content: center;
    }
    .pagination-navigation-modern {
        justify-content: space-between;
        width: 100%;
    }
    .modern-page-btn {
        padding: 7px 12px;
        font-size: 12px;
        flex: 1;
        justify-content: center;
    }
    .page-current-indicator {
        padding: 7px 10px;
        font-size: 11px;
    }
}
</style>

<!-- ========================================================================
     6. البرمجيات التفاعلية: التاريخ المجزأ الذكي والمودال
     ======================================================================== -->
<script>
/**
 * إعداد حقول التاريخ المجزأة الذكية (يوم / شهر / عام)
 * - يبدأ باليوم، والشهر والعام مكتوب تلقائياً (شهر اليوم والعام الحالي).
 * - عند الضغط على الشهر يتم تحديد الرقمين (مثلاً 11) فوراً للكتابة المباشرة.
 * - عند الضغط على اليوم أو العام يتم تحديد الأرقام فوراً.
 * - عند كتابة اليوم، ينتقل التركيز فوراً للشهر ويحدده.
 * - التنقل السلس بالأسهم يميناً ويساراً.
 * - التنقل بـ Enter من حقل (من) إلى حقل (إلى)، وعند (إلى) يرسل النموذج.
 * - لا يتأثر البحث بالتاريخ إطلاقاً إذا لم يتم إدخال اليوم!
 */
function initSegmentedDatePicker(type) {
    const dayInput = document.getElementById('day' + type);
    const monthInput = document.getElementById('month' + type);
    const yearInput = document.getElementById('year' + type);
    const hiddenInput = document.getElementById('hiddenDate' + type);
    const wrap = document.getElementById('pickerWrap' + type);
    const clearBtn = document.getElementById('clearBtn' + type);

    if (!dayInput || !monthInput || !yearInput) return;

    // عند النقر أو التركيز على أي خانة، يتم تحديد محتواها بالكامل فوراً للكتابة المباشرة
    [dayInput, monthInput, yearInput].forEach(inp => {
        inp.addEventListener('focus', function() {
            setTimeout(() => inp.select(), 10);
        });
        inp.addEventListener('click', function() {
            inp.select();
        });
    });

    // تحديث التاريخ المكتمل في الحقل المخفي
    function updateHiddenValue() {
        let d = dayInput.value.trim();
        let m = monthInput.value.trim();
        let y = yearInput.value.trim();

        // تصحيح واستكمال النواقص تلقائياً
        if (d && d.length === 1) d = d.padStart(2, '0');
        if (m && m.length === 1) m = m.padStart(2, '0');
        if (y && y.length === 2) y = '20' + y;

        // الشرط الحاسم: لا يتم تفعيل فلتر التاريخ إلا إذا تم إدخال اليوم!
        if (d && d.length > 0) {
            hiddenInput.value = `${y}-${m}-${d}`;
            wrap.classList.add('is-active-date');
            if (clearBtn) clearBtn.style.display = 'inline-flex';
        } else {
            // فارغ -> لا يؤثر على البحث إطلاقاً
            hiddenInput.value = '';
            wrap.classList.remove('is-active-date');
            if (clearBtn) clearBtn.style.display = 'none';
        }
    }

    // تصحيح واستكمال الخانة عند مغادرتها (on blur)
    dayInput.addEventListener('blur', function() {
        if (this.value.length === 1) {
            this.value = this.value.padStart(2, '0');
        }
        updateHiddenValue();
    });

    monthInput.addEventListener('blur', function() {
        if (this.value.length === 1) {
            this.value = this.value.padStart(2, '0');
        }
        updateHiddenValue();
    });

    yearInput.addEventListener('blur', function() {
        if (this.value.length === 2) {
            this.value = '20' + this.value;
        }
        updateHiddenValue();
    });

    // معالجة الكتابة في خانة اليوم
    dayInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (parseInt(this.value, 10) > 31) this.value = '31';

        // إذا كان الرقم أكبر من أو يساوي 4، فهو بالتأكيد يوم فردي (4..9) يستكمل تلقائياً بـ 0 ويقفز للشهر
        if (this.value.length === 1 && parseInt(this.value, 10) >= 4) {
            this.value = this.value.padStart(2, '0');
            updateHiddenValue();
            monthInput.focus();
            monthInput.select();
        } else if (this.value.length === 2) {
            updateHiddenValue();
            monthInput.focus();
            monthInput.select();
        } else {
            updateHiddenValue();
        }
    });

    // معالجة الكتابة في خانة الشهر
    monthInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (parseInt(this.value, 10) > 12) this.value = '12';

        // إذا كان الرقم أكبر من أو يساوي 2، فهو شهر فردي (2..9) يستكمل تلقائياً بـ 0 ويقفز للعام
        if (this.value.length === 1 && parseInt(this.value, 10) >= 2) {
            this.value = this.value.padStart(2, '0');
            updateHiddenValue();
            yearInput.focus();
            yearInput.select();
        } else if (this.value.length === 2) {
            updateHiddenValue();
            yearInput.focus();
            yearInput.select();
        } else {
            updateHiddenValue();
        }
    });

    // معالجة الكتابة في خانة العام
    yearInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        updateHiddenValue();
    });

    // التنقل بالأسهم ومفتاح الفاصلة / والـ Enter
    function handleKeys(inp, prevInp, nextInp, isDay, isMonth) {
        inp.addEventListener('keydown', function(e) {
            // دعم كتابة الفاصلة / أو . أو المسافة للانتقال التلقائي للخانة التالية مع تصحيح النواقص
            if (e.key === '/' || e.key === '.' || e.key === '-' || e.key === ' ') {
                e.preventDefault();
                if (inp.value.length === 1) {
                    inp.value = inp.value.padStart(2, '0');
                    updateHiddenValue();
                }
                if (nextInp) {
                    nextInp.focus();
                    nextInp.select();
                }
                return;
            }

            if (e.key === 'ArrowRight') {
                if (nextInp && (inp.selectionEnd === inp.value.length || inp.selectionStart === 0)) {
                    e.preventDefault();
                    if (inp.value.length === 1) {
                        inp.value = inp.value.padStart(2, '0');
                        updateHiddenValue();
                    }
                    nextInp.focus();
                    nextInp.select();
                }
            } else if (e.key === 'ArrowLeft') {
                if (prevInp && (inp.selectionStart === 0 || inp.selectionEnd === inp.value.length)) {
                    e.preventDefault();
                    prevInp.focus();
                    prevInp.select();
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (inp.value.length === 1) {
                    inp.value = inp.value.padStart(2, '0');
                    updateHiddenValue();
                }
                if (type === 'From') {
                    const nextDay = document.getElementById('dayTo');
                    if (nextDay) {
                        nextDay.focus();
                        nextDay.select();
                    }
                } else {
                    triggerAutoFilter(true);
                }
            }
        });
    }

    handleKeys(dayInput, null, monthInput, true, false);
    handleKeys(monthInput, dayInput, yearInput, false, true);
    handleKeys(yearInput, monthInput, null, false, false);

    // التحقق المبدئي
    updateHiddenValue();
}

// تشغيل المنظومة لكل من (من) و (إلى)
initSegmentedDatePicker('From');
initSegmentedDatePicker('To');

/**
 * زر مسح التاريخ وإعادته للحالة الافتراضية غير المفعلة
 */
function resetDateSegment(type, skipFilter = false) {
    const curMonth = '{{ date("m") }}';
    const curYear = '{{ date("Y") }}';
    const dayInput = document.getElementById('day' + type);
    const monthInput = document.getElementById('month' + type);
    const yearInput = document.getElementById('year' + type);
    const hiddenInput = document.getElementById('hiddenDate' + type);
    const wrap = document.getElementById('pickerWrap' + type);
    const clearBtn = document.getElementById('clearBtn' + type);

    if (dayInput) dayInput.value = '';
    if (monthInput) monthInput.value = curMonth;
    if (yearInput) yearInput.value = curYear;
    if (hiddenInput) hiddenInput.value = '';
    if (wrap) wrap.classList.remove('is-active-date');
    if (clearBtn) clearBtn.style.display = 'none';

    if (!skipFilter) {
        triggerAutoFilter(true);
    }
}

/**
 * مزامنة التقويم المرئي مع الحقول
 */
function triggerNativePicker(nativeId) {
    const picker = document.getElementById(nativeId);
    if (!picker) return;
    try {
        picker.showPicker();
    } catch (err) {
        picker.focus();
        picker.click();
    }
}

function onNativePicked(type, val) {
    if (!val) return;
    const parts = val.split('-');
    if (parts.length === 3) {
        const y = parts[0];
        const m = parts[1];
        const d = parts[2];

        const dayInput = document.getElementById('day' + type);
        const monthInput = document.getElementById('month' + type);
        const yearInput = document.getElementById('year' + type);
        const hiddenInput = document.getElementById('hiddenDate' + type);
        const wrap = document.getElementById('pickerWrap' + type);
        const clearBtn = document.getElementById('clearBtn' + type);

        if (dayInput) dayInput.value = d;
        if (monthInput) monthInput.value = m;
        if (yearInput) yearInput.value = y;
        if (hiddenInput) hiddenInput.value = val;
        if (wrap) wrap.classList.add('is-active-date');
        if (clearBtn) clearBtn.style.display = 'inline-flex';

        triggerAutoFilter(true);
    }
}

/**
 * منظومة الفلترة التلقائية اللحظية والتمرير السلس
 */
let autoFilterTimeout = null;
let isFiltering = false;

function triggerAutoFilter(immediate = false) {
    clearTimeout(autoFilterTimeout);
    if (immediate) {
        executeAutoFilter();
    } else {
        autoFilterTimeout = setTimeout(executeAutoFilter, 380);
    }
}

function executeAutoFilter(customUrl = null) {
    if (isFiltering) return;
    isFiltering = true;

    const form = document.getElementById('filterForm');
    if (!form && !customUrl) {
        isFiltering = false;
        return;
    }

    // مزامنة حقول التاريخ المجزأة قبل التجهيز
    ['From', 'To'].forEach(type => {
        const d = document.getElementById('day' + type);
        const m = document.getElementById('month' + type);
        const y = document.getElementById('year' + type);
        const h = document.getElementById('hiddenDate' + type);
        if (d && d.value.trim().length === 1) d.value = d.value.trim().padStart(2, '0');
        if (m && m.value.trim().length === 1) m.value = m.value.trim().padStart(2, '0');
        if (y && y.value.trim().length === 2) y.value = '20' + y.value.trim();
        if (d && d.value.trim().length > 0 && h) {
            h.value = `${y.value.trim()}-${m.value.trim()}-${d.value.trim()}`;
        }
    });

    let fetchUrl = customUrl;
    if (!fetchUrl && form) {
        const formData = new FormData(form);
        const cleanParams = new URLSearchParams();
        for (const [key, val] of formData.entries()) {
            if (val !== null && val.toString().trim() !== '') {
                cleanParams.append(key, val.toString().trim());
            }
        }
        fetchUrl = form.action + (cleanParams.toString() ? '?' + cleanParams.toString() : '');
    }

    // إظهار حالة المعالجة الناعمة
    const resultsContainer = document.getElementById('requestsResultsContainer');
    if (resultsContainer) {
        resultsContainer.style.opacity = '0.55';
        resultsContainer.style.pointerEvents = 'none';
        resultsContainer.style.transition = 'opacity 0.2s ease';
    }

    // تحديث ظهور زر الإلغاء
    const resetBtn = document.getElementById('btnResetFilters');
    if (resetBtn) {
        const searchVal = document.getElementById('searchInput')?.value.trim();
        const univVal = document.querySelector('select[name="university_id"]')?.value;
        const fromVal = document.getElementById('hiddenDateFrom')?.value;
        const toVal = document.getElementById('hiddenDateTo')?.value;
        const hasFilters = Boolean(searchVal || univVal || fromVal || toVal);
        resetBtn.style.display = hasFilters ? 'inline-flex' : 'none';
    }

    fetch(fetchUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newResults = doc.getElementById('requestsResultsContainer');

        if (newResults && resultsContainer) {
            resultsContainer.innerHTML = newResults.innerHTML;
        }

        // تحديث أعداد وخيارات القائمة المنسدلة لحالات الطلبات
        const newChips = doc.querySelector('.quick-chips-wrapper');
        const oldChips = document.querySelector('.quick-chips-wrapper');
        if (newChips && oldChips) {
            oldChips.innerHTML = newChips.innerHTML;
        }

        // تحديث الرابط في شريط المتصفح دون إعادة تحميل
        window.history.pushState(null, '', fetchUrl);

        // سحب الشاشة بهدوء ونعومة لمكان النتائج مباشرة أسفل الفلاتر
        smoothScrollToResults();
    })
    .catch(err => {
        console.error('Filter fetch error:', err);
        if (form && !customUrl) form.submit();
    })
    .finally(() => {
        if (resultsContainer) {
            resultsContainer.style.opacity = '1';
            resultsContainer.style.pointerEvents = 'auto';
        }
        isFiltering = false;
        bindPaginationLinks();
    });
}

/**
 * معالجة اختيار حالة الطلبات من القائمة المنسدلة المنسقة في الوسط
 */
function onStatusDropdownChange(val) {
    const tabInput = document.getElementById('filterTab');
    const statusInput = document.getElementById('filterStatus');

    if (!val || val === 'all') {
        if (tabInput) tabInput.value = '';
        if (statusInput) statusInput.value = '';
    } else if (val.startsWith('tab:')) {
        if (tabInput) tabInput.value = val.replace('tab:', '');
        if (statusInput) statusInput.value = '';
    } else if (val.startsWith('status:')) {
        if (tabInput) tabInput.value = '';
        if (statusInput) statusInput.value = val.replace('status:', '');
    }

    triggerAutoFilter(true);
}

function smoothScrollToResults() {
    const container = document.getElementById('requestsResultsContainer');
    if (container) {
        const offset = 85; // يضمن بقاء شريط الفلاتر والبحث ظاهراً تماماً في الأعلى
        const targetY = container.getBoundingClientRect().top + window.pageYOffset - offset;
        const currentScroll = window.pageYOffset;
        if (Math.abs(targetY - currentScroll) > 40) {
            window.scrollTo({
                top: Math.max(0, targetY),
                behavior: 'smooth'
            });
        }
    }
}

function resetAllFilters(e) {
    if (e) e.preventDefault();
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = '';
    const univSelect = document.querySelector('select[name="university_id"]');
    if (univSelect) univSelect.value = '';
    
    // مسح حقول التاريخ
    resetDateSegment('From', true);
    resetDateSegment('To', true);

    const tabInput = document.getElementById('filterTab');
    if (tabInput) tabInput.value = '';
    const statusInput = document.getElementById('filterStatus');
    if (statusInput) statusInput.value = '';

    const statusSelect = document.getElementById('statusQuickSelect');
    if (statusSelect) statusSelect.value = 'all';

    triggerAutoFilter(true);
}

function bindPaginationLinks() {
    const pagLinks = document.querySelectorAll('.modern-page-btn.nav-link-btn');
    pagLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            executeAutoFilter(this.href);
        });
    });
}

// ربط حقل البحث والتنقل عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            triggerAutoFilter(false);
        });
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                triggerAutoFilter(true);
            }
        });
    }

    bindPaginationLinks();
});

/**
 * نافذة المعاينة الإشرافية مع زر التواصل الهاتفي بالجامعة
 */
function openReqOverviewModal(reqId) {
    const modal = document.getElementById('reqModal');
    const detailsGrid = document.getElementById('modalDetailsGrid');
    const modalTitle = document.getElementById('modalReqTitle');
    const modalSub = document.getElementById('modalReqSub');

    modalTitle.innerText = `ملخص المتابعة لطلب الالتحاق #${reqId}`;
    modalSub.innerText = `جاري استدعاء البيانات...`;
    detailsGrid.innerHTML = `
        <div class="modal-loading-state">
            <div class="modal-loading-spinner"></div>
            <span>جاري استدعاء السجلات الإشرافية...</span>
        </div>
    `;
    modal.style.display = 'flex';
    modal.classList.add('show');

    fetch(`{{ url('admin/requests') }}/${reqId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                detailsGrid.innerHTML = `<div style="color:#ef4444;text-align:center;padding:20px;">تعذر استرجاع بيانات الطلب</div>`;
                return;
            }
            const r = data.request;
            modalSub.innerText = `رقم المعاملة الرسمي: #${r.req_id}`;

            const reviewDateDisplay = r.decision_date 
                ? `<span style="color:#16a34a;font-weight:700;">${r.decision_date}</span>`
                : `<span style="color:#94a3b8;font-style:italic;">قيد انتظار قرار الجامعة</span>`;

            detailsGrid.innerHTML = `
                <!-- كتلة الجامعة وزر الاتصال الهاتفي المباشر -->
                <div class="univ-contact-card">
                    <div class="univ-info-group">
                        <span class="modal-record-label" style="color:#15803d;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4M5 21V10.85M19 21V10.85M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/></svg>
                            الجامعة المعنية بالمراجعة:
                        </span>
                        <div class="univ-main-name">${r.university}</div>
                        <div class="univ-phone-text">
                            <span>هاتف الجامعة الرسمي:</span>
                            <strong dir="ltr">${r.university_phone ? r.university_phone : 'غير مسجل'}</strong>
                        </div>
                    </div>
                    ${r.university_phone ? `
                        <a href="tel:${r.university_phone}" class="btn-call-univ" title="فتح الهاتف للاتصال بالجامعة مباشرة">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>اتصال بالجامعة</span>
                        </a>
                    ` : `
                        <span class="btn-call-disabled">لا يوجد هاتف مسجل</span>
                    `}
                </div>

                <div class="modal-record-item">
                    <span class="modal-record-label">اسم الطالب:</span>
                    <span class="modal-record-value">${r.full_name}</span>
                </div>
                <div class="modal-record-item">
                    <span class="modal-record-label">رقم هاتف الطالب:</span>
                    <span class="modal-record-value" dir="ltr">${r.phone ? `<a href="tel:${r.phone}" style="color:#0284c7;text-decoration:none;">${r.phone}</a>` : '—'}</span>
                </div>
                <div class="modal-record-item">
                    <span class="modal-record-label">البرنامج / التخصص:</span>
                    <span class="modal-record-value">${r.desired_spec}</span>
                </div>
                <div class="modal-record-item">
                    <span class="modal-record-label">تاريخ التقديم:</span>
                    <span class="modal-record-value">${r.req_date}</span>
                </div>
                <div class="modal-record-item">
                    <span class="modal-record-label">حالة الطلب:</span>
                    <span class="modal-record-value">${r.status}</span>
                </div>
                <div class="modal-record-item">
                    <span class="modal-record-label">تاريخ المراجعة:</span>
                    <span class="modal-record-value">${reviewDateDisplay}</span>
                </div>
                <div class="modal-record-item">
                    <span class="modal-record-label">حالة السداد:</span>
                    <span class="modal-record-value">
                        <span class="pay-quantum-badge" style="background:#e0f2fe;color:#0369a1;">${r.payment_status}</span>
                    </span>
                </div>
                ${r.rejection_reason ? `
                <div class="modal-record-item" style="grid-column: 1 / -1; background:#fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px;">
                    <span class="modal-record-label" style="color:#dc2626; font-weight:700;">سبب الرفض / ملاحظات الجامعة:</span>
                    <span class="modal-record-value" style="color:#991b1b; font-weight:600; margin-top:4px; display:block;">${r.rejection_reason}</span>
                </div>
                ` : ''}
            `;
        })
        .catch(err => {
            detailsGrid.innerHTML = `<div style="color:#ef4444;text-align:center;padding:20px;">حدث خطأ في الاتصال بالسيرفر</div>`;
        });
}

function closeReqModal() {
    const modal = document.getElementById('reqModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeReqModal();
});
</script>

@endsection
