{{-- تبويب طلبات القبول والتسجيل --}}
<div class="tab-pane-content" id="applicationsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">طلبات التنسيق والتسجيل الإلكتروني</h2>
            <p class="tab-subtitle">متابعة وفحص ملفات المتقدمين، تدقيق الوثائق، وتأكيد القبول أو الرفض مع ذكر الأسباب.</p>
        </div>

        <div class="banner-actions">
            <a href="{{ route('university.requests.export') }}" class="btn btn-navy btn-sm" title="تصدير بيانات الطلبات إلى ملف Excel / CSV">
                <svg class="ico" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>تصدير الطلبات (CSV)</span>
            </a>
        </div>
    </div>

    <!-- بطاقة الفلاتر والبحث في الطلبات -->
    <div class="card-box mb-4">
        <div class="requests-filter-bar">
            <!-- أزرار تصفية الحالة -->
            <div class="status-filter-pills" role="tablist">
                <button type="button" class="filter-pill-btn active" data-filter="all" onclick="filterApplications('all')">
                    الكل ({{ $requests->count() }})
                </button>
                <button type="button" class="filter-pill-btn" data-filter="قيد المراجعة" onclick="filterApplications('قيد المراجعة')">
                    قيد المراجعة ({{ $stats['pending_requests'] ?? 0 }})
                </button>
                <button type="button" class="filter-pill-btn" data-filter="يتطلب استكمال" onclick="filterApplications('يتطلب استكمال')">
                    يتطلب استكمال ({{ $stats['needs_completion_requests'] ?? 0 }})
                </button>
                <button type="button" class="filter-pill-btn" data-filter="مقبول" onclick="filterApplications('مقبول')">
                    مقبول ({{ $stats['accepted_requests'] ?? 0 }})
                </button>
                <button type="button" class="filter-pill-btn" data-filter="مرفوض" onclick="filterApplications('مرفوض')">
                    مرفوض ({{ $stats['rejected_requests'] ?? 0 }})
                </button>
            </div>

            <!-- حقل البحث في جدول الطلبات -->
            <div class="table-search-box">
                <svg class="ico text-muted" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" id="requestsSearchInput" class="form-control" placeholder="بحث باسم الطالب، التخصص، أو الهاتف..." onkeyup="searchApplicationsTable(this.value)">
            </div>
        </div>

        <!-- جدول طلبات التسجيل -->
        <div class="table-responsive mt-3">
            <table class="modern-table" id="applicationsTable">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم الطالب</th>
                        <th>التخصص المرغوب</th>
                        <th>معدل الثانوية</th>
                        <th>تاريخ التقديم</th>
                        <th>الوثائق المرفوعة</th>
                        <th>حالة الطلب</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr class="app-row" data-status="{{ $req->status }}" data-student="{{ $req->full_name }}" data-spec="{{ $req->desired_spec }}" data-phone="{{ $req->phone }}">
                            <td><strong>#{{ $req->req_id }}</strong></td>
                            <td>
                                <div class="student-cell-info">
                                    <span class="student-name font-semibold text-navy">{{ $req->full_name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-spec-name">{{ $req->desired_spec }}</span>
                            </td>
                            <td>
                                <span class="badge-avg font-bold {{ $req->sec_avg >= 80 ? 'text-teal' : 'text-navy' }}">
                                    {{ $req->sec_avg ? number_format($req->sec_avg, 1) . '%' : '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted text-xs">{{ $req->req_date ? $req->req_date->format('Y-m-d') : '—' }}</span>
                            </td>
                            <td>
                                @if($req->regDocs->count() > 0)
                                    <span class="badge-docs-attached" title="عدد الوثائق المرفوعة">{{ $req->regDocs->count() }} وثائق</span>
                                @else
                                    <span class="text-muted text-xs">لا توجد وثائق</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-pill status-pill-{{ $req->status === 'مقبول' ? 'success' : ($req->status === 'مرفوض' ? 'danger' : ($req->status === 'يتطلب استكمال' ? 'info' : 'warning')) }}" id="statusPill-{{ $req->req_id }}">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-navy btn-sm px-3 fw-bold shadow-sm d-inline-flex align-items-center gap-1.5" onclick="viewRequestDetails({{ $req->req_id }})" title="عرض ومراجعة تفاصيل الطلب واتخاذ الإجراء">
                                    <i class="bi bi-eye-fill"></i>
                                    <span>عرض</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="empty-state-inner">
                                    <svg class="ico empty-icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                    <p>لا توجد طلبات تنسيق أو تسجيل مقدمة حتى الآن.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
