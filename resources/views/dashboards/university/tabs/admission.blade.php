{{-- تبويب شروط القبول والتسجيل العامة --}}
<div class="tab-pane-content" id="admissionTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">دليل شروط وضوابط القبول والتسجيل</h2>
            <p class="tab-subtitle">نظرة شاملة على شروط التنسيق والقبول المحددة لبرامج كليات الجامعة للعام الجامعي الحالي.</p>
        </div>

        <div class="banner-actions">
            <button type="button" class="btn btn-navy btn-sm" onclick="switchTab('colleges')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                <span>تعديل الشروط عبر التخصصات</span>
            </button>
        </div>
    </div>

    <!-- جدول شروط القبول بحسب الكليات والتخصصات -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-teal-subtle text-teal">
                <svg class="ico" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">محددات القبول الأكاديمي المعتمدة</h3>
                <p class="card-box-desc">جدول تفصيلي يوضح الحد الأدنى للثانوية، سنة التخرج، الرسوم، والوثائق المطلوبة</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table" id="admissionRulesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الكلية</th>
                        <th>التخصص الأكاديمي</th>
                        <th>الحد الأدنى</th>
                        <th>سنة الثانوية</th>
                        <th>الرسوم السنوية</th>
                        <th>رسوم التسجيل</th>
                        <th>فترة القبول</th>
                        <th>الوثائق المطلوبة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @php $idx = 1; @endphp
                    @forelse($university->colleges as $col)
                        @foreach($col->specializations as $sp)
                            @php
                                $adm = $sp->admissionData;
                                $docsCount = $adm && $adm->docs ? $adm->docs->count() : (is_array($adm->required_documents ?? null) ? count($adm->required_documents) : 0);
                                $reqDocsCount = $adm && $adm->docs ? $adm->docs->where('is_required', true)->count() : $docsCount;
                                $admStatus = $adm->admission_status ?? 'مفتوح';
                                $regFee = $adm->registration_fee ?? 5000;
                            @endphp
                            <tr>
                                <td>{{ $idx++ }}</td>
                                <td><strong>{{ $col->college_name }}</strong></td>
                                <td>
                                    <span class="text-navy font-semibold">{{ $sp->specialization_name }}</span>
                                    <div class="small text-muted">{{ $sp->language ?? 'العربية' }} | {{ $sp->duration_years ?? 4 }} سنوات</div>
                                </td>
                                <td>
                                    <span class="badge-teal font-bold">{{ $adm->minimum_average ?? 70 }}%</span>
                                </td>
                                <td>{{ $adm->secondary_last_year ?? 2021 }}</td>
                                <td><span class="text-gold font-bold">${{ number_format($sp->tuition_fees, 0) }}</span></td>
                                <td><span class="text-navy font-bold">{{ number_format($regFee, 0) }} ر.ي</span></td>
                                <td>
                                    <span class="status-pill {{ $admStatus == 'مفتوح' ? 'pill-success' : ($admStatus == 'مغلق' ? 'pill-danger' : 'pill-secondary') }}">
                                        {{ $admStatus }}
                                    </span>
                                    @if($adm && $adm->admission_start_date && $adm->admission_end_date)
                                        <div class="small text-muted" style="font-size:0.75rem;">
                                            {{ \Carbon\Carbon::parse($adm->admission_start_date)->format('m/d') }} - {{ \Carbon\Carbon::parse($adm->admission_end_date)->format('m/d') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-pill pill-secondary font-bold">
                                        {{ $docsCount }} وثائق
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-navy btn-xs" onclick="selectAndEditSpec({{ $col->college_id }}, {{ $sp->specialization_id }})" title="إدارة وتعديل بيانات القبول والوثائق لهذا التخصص">
                                        <svg class="ico" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        <span>إدارة بيانات القبول</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">لم يتم إضافة كليات أو تخصصات بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
