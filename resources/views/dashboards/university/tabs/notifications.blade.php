{{-- تبويب الإشعارات والتنبيهات --}}
<div class="tab-pane-content" id="notificationsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">الإشعارات والتنبيهات الإدارية والأكاديمية</h2>
            <p class="tab-subtitle">تنبيهات فورية وقرارات إشرافية من إدارة المنظومة، وطلبات التسجيل الجديدة والأنشطة الأكاديمية.</p>
        </div>
    </div>

    {{-- 1. قسم القرارات والتوجيهات الرقابية والإشرافية الصادرة من مدير المنظومة --}}
    @php
        $moderationNotifs = isset($notifications) ? $notifications->filter(fn($n) => in_array($n->notification_type, ['moderation_quarantine', 'moderation_resolved', 'system_alert'])) : collect();
        $otherNotifs = isset($notifications) ? $notifications->filter(fn($n) => !in_array($n->notification_type, ['moderation_quarantine', 'moderation_resolved', 'system_alert'])) : collect();
    @endphp

    @if($moderationNotifs->isNotEmpty())
    <div class="card-box mb-4" style="border: 1.5px solid #fca5a5; background: #fffdfd; box-shadow: 0 4px 16px rgba(220, 38, 38, 0.06);">
        <div class="card-box-header" style="border-bottom: 1px solid #fee2e2; padding-bottom: 14px;">
            <div class="header-icon-badge" style="background:#fef2f2; color:#dc2626;">
                <i class="fa-solid fa-shield-virus" style="font-size:20px;"></i>
            </div>
            <div>
                <h3 class="card-box-title" style="color: #991b1b;">القرارات والتوجيهات الرقابية من إدارة المنصة</h3>
                <p class="card-box-desc" style="color: #b91c1c;">إشعارات فورية ملزمة بشأن محتوى وبيانات الجامعة والتوجيهات الإدارية المطلوبة لتصحيحها</p>
            </div>
        </div>

        <div class="notifications-feed-list p-3" style="display:flex; flex-direction:column; gap:14px;">
            @foreach($moderationNotifs as $modNotif)
                <div class="notif-card-mod" style="background: #ffffff; border-radius: 12px; border: 1px solid {{ $modNotif->notification_type === 'moderation_quarantine' ? '#fecaca' : '#a7f3d0' }}; padding: 16px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="badge" style="background:{{ $modNotif->notification_type === 'moderation_quarantine' ? '#fef2f2' : '#ecfdf5' }}; color:{{ $modNotif->notification_type === 'moderation_quarantine' ? '#dc2626' : '#059669' }}; border:1px solid {{ $modNotif->notification_type === 'moderation_quarantine' ? '#fca5a5' : '#6ee7b7' }}; font-size:12px; font-weight:700; padding:4px 10px; border-radius:20px;">
                                <i class="fa-solid {{ $modNotif->notification_type === 'moderation_quarantine' ? 'fa-ban' : 'fa-check' }} me-1"></i>
                                {{ $modNotif->notification_type === 'moderation_quarantine' ? 'قرار رقابي: إيقاف ظهور' : 'اعتماد رسمي وفك حجب' }}
                            </span>
                            <strong style="font-size: 14.5px; color: #0f172a;">{{ $modNotif->title }}</strong>
                        </div>
                        <span style="font-size: 11.5px; color: #64748b; font-weight: 600;">
                            <i class="fa-regular fa-clock me-1"></i>
                            {{ $modNotif->created_at ? \Carbon\Carbon::parse($modNotif->created_at)->diffForHumans() : 'مؤخراً' }}
                        </span>
                    </div>

                    <div style="font-size: 13px; line-height: 1.7; color: #334155; white-space: pre-line; background: {{ $modNotif->notification_type === 'moderation_quarantine' ? '#fffafa' : '#fafffc' }}; padding: 12px 16px; border-radius: 8px; border-right: 4px solid {{ $modNotif->notification_type === 'moderation_quarantine' ? '#dc2626' : '#10b981' }};">
                        {{ $modNotif->message }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 2. قسم الإشعارات العامة والتشغيلية --}}
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-gold-subtle text-gold">
                <svg class="ico" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">سجل التنبيهات والأنشطة الأكاديمية</h3>
                <p class="card-box-desc">طلبات التسجيل الواردة، أكواد الإحالة المستخدمة، والعمليات الطلابية</p>
            </div>
        </div>

        <div class="notifications-feed-list">
            @php $notifCount = 0; @endphp

            {{-- إشعارات النظام الأخرى --}}
            @foreach($otherNotifs as $on)
                @php $notifCount++; @endphp
                <div class="notif-feed-item {{ !$on->is_read ? 'notif-unread' : '' }}">
                    <div class="notif-icon-circle bg-blue-subtle text-blue">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div class="notif-content-body">
                        <div class="notif-head">
                            <strong class="notif-title">{{ $on->title }}</strong>
                            <span class="notif-time">{{ $on->created_at ? \Carbon\Carbon::parse($on->created_at)->diffForHumans() : 'مؤخراً' }}</span>
                        </div>
                        <p class="notif-summary" style="white-space:pre-line;">{{ $on->message }}</p>
                    </div>
                </div>
            @endforeach

            {{-- طلبات التسجيل --}}
            @if(isset($requests))
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
            @endif

            {{-- الإحالات --}}
            @if(isset($referrals))
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
            @endif

            @if($notifCount === 0 && $moderationNotifs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-bell-slash fs-2 mb-2 d-block"></i>
                    <p>لا توجد تنبيهات جديدة في الوقت الراهن.</p>
                </div>
            @endif
        </div>
    </div>

</div>
