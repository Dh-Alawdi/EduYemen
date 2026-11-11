{{-- تبويب الإشعارات والتنبيهات --}}
<div class="tab-pane-content" id="notificationsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">الإشعارات والتنبيهات الأكاديمية</h2>
            <p class="tab-subtitle">تنبيهات فورية بطلبات التسجيل الجديدة، استفسارات الطلبة، وأكواد الإحالة المستخدمة.</p>
        </div>
    </div>

    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-gold-subtle text-gold">
                <svg class="ico" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">سجل التنبيهات والأنشطة الأخيرة</h3>
                <p class="card-box-desc">الأحداث التي تمت على حساب الجامعة خلال الساعات الماضية</p>
            </div>
        </div>

        <div class="notifications-feed-list">
            @php $notifCount = 0; @endphp
            @foreach($requests->take(5) as $r)
                @php $notifCount++; @endphp
                <div class="notif-feed-item {{ $r->status === 'قيد المراجعة' ? 'notif-unread' : '' }}" onclick="switchTab('applications'); viewRequestDetails({{ $r->req_id }})">
                    <div class="notif-icon-circle bg-blue-subtle text-blue">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                    </div>
                    <div class="notif-content-body">
                        <div class="notif-head">
                            <strong class="notif-title">طلب تسجيل جديد من الطالب {{ $r->full_name }}</strong>
                            <span class="notif-time">{{ $r->req_date ? $r->req_date->diffForHumans() : 'مؤخراً' }}</span>
                        </div>
                        <p class="notif-summary">تخصص: <strong>{{ $r->desired_spec }}</strong> - معدل الثانوية: {{ $r->sec_avg }}% - الحالة: {{ $r->status }}</p>
                    </div>
                    <span class="notif-arrow">←</span>
                </div>
            @endforeach

            @foreach($referrals->where('status', 'مستخدم')->take(3) as $ref)
                @php $notifCount++; @endphp
                <div class="notif-feed-item" onclick="switchTab('referrals')">
                    <div class="notif-icon-circle bg-gold-subtle text-gold">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div class="notif-content-body">
                        <div class="notif-head">
                            <strong class="notif-title">تم اعتماد كود إحالة للطالب {{ $ref->student->full_name ?? 'طالب' }}</strong>
                            <span class="notif-time">{{ $ref->used_at ? $ref->used_at->diffForHumans() : 'مؤخراً' }}</span>
                        </div>
                        <p class="notif-summary">كود الإحالة المعتمد: <code>{{ $ref->referral_code }}</code> مع استحقاق التنسيق الجامعي.</p>
                    </div>
                    <span class="notif-arrow">←</span>
                </div>
            @endforeach

            @if($notifCount === 0)
                <div class="text-center py-5 text-muted">
                    <p>لا توجد تنبيهات جديدة في الوقت الراهن.</p>
                </div>
            @endif
        </div>
    </div>

</div>
