{{-- تبويب إحالات الحضور الشخصي وفحص الأكواد --}}
<div class="tab-pane-content" id="referralsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">إحالات الحضور والتنسيق الشخصي</h2>
            <p class="tab-subtitle">فحص واعتماد أكواد الإحالة الصادرة للطلبة للحضور المباشر لمقر الجامعة واستحقاق خصم التنسيق.</p>
        </div>
    </div>

    <!-- بطاقة فحص واعتماد كود الإحالة الفوري عبر AJAX -->
    <div class="card-box mb-4 referral-verify-card">
        <div class="card-box-header">
            <div class="header-icon-badge bg-gold-subtle text-gold">
                <svg class="ico" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">فحص واعتماد كود الإحالة الفوري</h3>
                <p class="card-box-desc">أدخل كود الإحالة المقدم من الطالب الزائر للتحقق من أصالته واعتماده رسمياً</p>
            </div>
        </div>

        <form id="verifyReferralForm" onsubmit="submitVerifyReferral(event)" class="verify-code-form">
            @csrf
            <div class="verify-input-group">
                <div class="code-input-wrapper">
                    <svg class="ico code-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="text" id="verifyReferralInput" name="referral_code" class="form-control form-control-lg text-center code-input" placeholder="مثال: EDU-REF-4-1234 أو EDU-XXXXXX" required dir="ltr">
                </div>
                <button type="submit" class="btn btn-navy btn-lg" id="btnVerifyCode">
                    <svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                    <span>فحص واعتماد الكود</span>
                </button>
            </div>
        </form>

        <!-- نتيجة الفحص الفورية -->
        <div id="referralVerifyResult" class="verification-result-box" style="display:none;"></div>
    </div>

    <!-- جدول سجل الإحالات المعتمدة والنشطة -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-teal-subtle text-teal">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">سجل إحالات الطلبة الصادرة لهذه الجامعة</h3>
                <p class="card-box-desc">قائمة بجميع الإحالات الإلكترونية وتاريخ استخدامها وحالتها الحالية</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table" id="referralsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الطالب</th>
                        <th>كود الإحالة المعتمد</th>
                        <th>رقم هاتف الطالب</th>
                        <th>تاريخ الإصدار</th>
                        <th>تاريخ الاعتماد الحضوري</th>
                        <th>حالة الإحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $ref)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $ref->student->full_name ?? 'طالب' }}</strong>
                            </td>
                            <td>
                                <code class="referral-code-pill">{{ $ref->referral_code }}</code>
                            </td>
                            <td dir="ltr" class="text-right">{{ $ref->student->user->phone ?? '—' }}</td>
                            <td>
                                <span class="text-muted text-xs">{{ $ref->created_at ? $ref->created_at->format('Y-m-d') : '—' }}</span>
                            </td>
                            <td>
                                @if($ref->status === 'مستخدم')
                                    <span class="text-teal font-semibold text-xs">{{ $ref->used_at ? $ref->used_at->format('Y-m-d H:i') : 'تم الاعتماد' }}</span>
                                @else
                                    <span class="text-muted text-xs">— لم يعتمد بعد —</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-pill {{ $ref->status === 'مستخدم' ? 'pill-success' : 'pill-gold' }}">
                                    {{ $ref->status === 'مستخدم' ? 'تم الاعتماد والاستخدام' : 'فعال وجاهز' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                لا توجد إحالات حضور شخصي مسجلة لهذه الجامعة بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
