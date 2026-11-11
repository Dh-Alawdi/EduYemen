{{-- تبويب التقارير والإحصائيات التحليلية --}}
<div class="tab-pane-content" id="reportsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">التقارير والإحصائيات التحليلية</h2>
            <p class="tab-subtitle">مؤشرات الأداء الأكاديمي، إحصائيات إقبال الطلبة، ومعدلات القبول لهذا الموسم الجامعي.</p>
        </div>

        <div class="banner-actions">
            <button type="button" class="btn btn-navy btn-sm" onclick="window.print()">
                <svg class="ico" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>طباعة التقرير الشامل</span>
            </button>
        </div>
    </div>

    <!-- شبكة المؤشرات البيانية السريعة -->
    <div class="reports-metrics-grid mb-4">
        <div class="report-metric-card">
            <div class="metric-top">
                <span>متوسط معدل الثانوية للمتقدمين</span>
                <span class="metric-icon text-teal">📊</span>
            </div>
            <div class="metric-value">{{ $stats['avg_highschool_score'] ?? 0 }}%</div>
            <div class="metric-desc">محسوب من إجمالي طلبات التنسيق المستلمة</div>
        </div>

        <div class="report-metric-card">
            <div class="metric-top">
                <span>نسبة قبول الطلبات المكتملة</span>
                <span class="metric-icon text-green">✅</span>
            </div>
            <div class="metric-value">
                {{ $stats['total_requests'] > 0 ? round(($stats['accepted_requests'] / $stats['total_requests']) * 100, 1) : 0 }}%
            </div>
            <div class="metric-desc">{{ $stats['accepted_requests'] ?? 0 }} طلب مقبول رسمياً</div>
        </div>

        <div class="report-metric-card">
            <div class="metric-top">
                <span>إجمالي استحقاق إحالات التنسيق</span>
                <span class="metric-icon text-gold">⭐</span>
            </div>
            <div class="metric-value">{{ $stats['total_referrals'] ?? 0 }}</div>
            <div class="metric-desc">{{ $stats['used_referrals'] ?? 0 }} طالب استكمل التنسيق بمقر الجامعة</div>
        </div>

        <div class="report-metric-card">
            <div class="metric-top">
                <span>جاهزية الطاقة الاستيعابية</span>
                <span class="metric-icon text-blue">🏛️</span>
            </div>
            <div class="metric-value">{{ $stats['specializations_count'] ?? 0 }} برنامج</div>
            <div class="metric-desc">عبر {{ $stats['colleges_count'] ?? 0 }} كليات جامعية معتمدة</div>
        </div>
    </div>

    <!-- جدول توزيع التخصصات ونسب التقديم -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-blue-subtle text-blue">
                <svg class="ico" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">توزيع البرامج الأكاديمية ونشاط القبول</h3>
                <p class="card-box-desc">ملخص البرامج الأكاديمية والرسوم وشروط القبول المعتمدة</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>الكلية</th>
                        <th>عدد التخصصات</th>
                        <th>أدنى معدل قبول</th>
                        <th>أعلى رسوم سنوية</th>
                        <th>حالة القبول</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($university->colleges as $col)
                        @php
                            $specs = $col->specializations;
                            $minAvg = $specs->min(fn($s) => $s->admissionData->minimum_average ?? 100);
                            $maxFee = $specs->max('tuition_fees');
                        @endphp
                        <tr>
                            <td><strong>{{ $col->college_name }}</strong></td>
                            <td><span class="badge-blue font-bold">{{ $specs->count() }} تخصصات</span></td>
                            <td>{{ $minAvg ? $minAvg . '%' : 'غير محدد' }}</td>
                            <td class="text-gold font-bold">${{ number_format($maxFee ?? 0, 0) }}</td>
                            <td>
                                <span class="status-pill pill-success">مفتوح للتنسيق</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">لا توجد بيانات كليات لتحليلها بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
