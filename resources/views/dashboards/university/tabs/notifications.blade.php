{{-- تبويب الإشعارات والتنبيهات الموحد للجامعة --}}
<div class="tab-pane-content" id="notificationsTabContent">

    <style>
        .notif-feed-item {
            position: relative;
            padding: 14px 16px 14px 95px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .notif-feed-item.is-clickable {
            cursor: pointer;
        }
        .notif-feed-item.is-clickable:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 14px rgba(16, 42, 67, 0.06);
            transform: translateY(-1px);
        }
        .notif-badge-nowrap {
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
            line-height: 1.2;
        }
        .notif-time-corner {
            position: absolute;
            top: 12px;
            left: 14px;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            z-index: 2;
        }
        @media (max-width: 576px) {
            .notif-feed-item {
                padding: 12px 12px 12px 85px;
            }
            .notif-time-corner {
                top: 10px;
                left: 10px;
                font-size: 10.5px;
            }
        }
    </style>

    {{-- الحاوية الموحدة لجميع الإشعارات مرتبة بالكامل حسب الوقت --}}
    <div class="card-box" style="border: 1px solid #e2e8f0; border-radius: 14px; background: #ffffff; box-shadow: 0 4px 18px rgba(16, 42, 67, 0.04); overflow: hidden;">
        
        <div class="card-box-header" style="border-bottom: 1px solid #f1f5f9; padding: 14px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="header-icon-badge" style="background:#eff6ff; color:#1d4ed8; width:34px; height:34px; font-size:15px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <h3 class="card-box-title" style="font-size:15px; font-weight:800; color:#0f172a; margin:0;">سجل الإشعارات والتنبيهات</h3>
                </div>
            </div>
            
            <div style="display:flex; align-items:center; gap:10px;">
                @if(isset($notifications) && $notifications->isNotEmpty())
                    <button type="button" class="btn btn-sm" style="background:#f8fafc; border:1px solid #cbd5e1; color:#334155; font-weight:700; border-radius:8px; padding:6px 12px; font-size:12px; display:inline-flex; align-items:center; gap:6px; cursor:pointer; transition:all 0.2s;" onclick="markAllNotificationsAsReadAjax()">
                        <i class="fa-solid fa-check-double text-primary"></i>
                        <span>تحديد الكل كمقروء</span>
                    </button>
                @endif
                @if(isset($unreadNotifsCount) && $unreadNotifsCount > 0)
                    <span class="badge unread-notifs-badge" style="background:#ef4444; color:#fff; font-size:11.5px; font-weight:700; padding:4px 10px; border-radius:20px;">
                        {{ $unreadNotifsCount }} غير مقروء
                    </span>
                @endif
            </div>
        </div>

        <div class="notifications-feed-list p-3" style="display:flex; flex-direction:column; gap:10px;">
            @forelse($notifications ?? [] as $n)
                @php
                    $isUnread = !$n->is_read;
                    $type = $n->notification_type;
                    $timeFormatted = $n->created_at ? \Carbon\Carbon::parse($n->created_at)->diffForHumans() : 'مؤخراً';
                    $fullDate = $n->created_at ? \Carbon\Carbon::parse($n->created_at)->format('Y-m-d H:i') : '';

                    // استخراج معرف الطلب إن وجد
                    $reqId = null;
                    if (preg_match('/#(\d+)/', $n->title . ' ' . $n->message, $matches)) {
                        $reqId = (int)$matches[1];
                    }

                    $isModQuarantine = ($type === 'moderation_quarantine');
                    $isModResolved = ($type === 'moderation_resolved');
                    $isRegistration = in_array($type, ['new_registration_request', 'registration_submitted', 'registration_resubmitted', 'payment_proof_submitted']);
                    $isReferral = in_array($type, ['referral_used', 'referral_generated']);
                    $isAd = str_starts_with($type, 'ad_');

                    // تحديد ما إذا كان الإشعار قابلاً للنقر وما هي وجهته
                    $isClickable = ($isModQuarantine || $isRegistration || $isReferral || $isAd);
                @endphp

                <div class="notif-feed-item {{ $isUnread ? 'notif-unread' : '' }} {{ $isClickable ? 'is-clickable' : '' }}"
                     id="notif-item-{{ $n->notification_id }}"
                     @if($isModQuarantine)
                         onclick="handleNotificationClick({{ $n->notification_id }}, 'moderation')"
                     @elseif($isRegistration)
                         onclick="handleNotificationClick({{ $n->notification_id }}, 'application', {{ $reqId ?? 0 }})"
                     @elseif($isReferral)
                         onclick="handleNotificationClick({{ $n->notification_id }}, 'referrals')"
                     @elseif($isAd)
                         onclick="handleNotificationClick({{ $n->notification_id }}, 'advertisements')"
                     @else
                         onclick="handleNotificationClick({{ $n->notification_id }}, 'none')"
                     @endif
                     style="border-color: {{ $isUnread ? ($isModQuarantine ? '#fca5a5' : '#cbd5e1') : '#e2e8f0' }}; background: {{ $isUnread ? ($isModQuarantine ? '#fff8f8' : '#f8fafc') : '#ffffff' }};">

                    {{-- توقيت الإشعار مرفوع في الزاوية العلوية جهة اليسار --}}
                    <span class="notif-time-corner" title="{{ $fullDate }}">
                        <i class="fa-regular fa-clock"></i>
                        <span>{{ $timeFormatted }}</span>
                    </span>

                    {{-- محتوى الإشعار والأيقونة --}}
                    <div style="display:flex; align-items:flex-start; gap:12px; width:100%;">
                        
                        {{-- الأيقونة حسب نوع الإشعار --}}
                        <div class="notif-icon-circle" style="width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;
                            @if($isModQuarantine) background:#fee2e2; color:#dc2626;
                            @elseif($isModResolved) background:#ecfdf5; color:#059669;
                            @elseif($isRegistration) background:#eff6ff; color:#1d4ed8;
                            @elseif($isReferral) background:#fffbeb; color:#b45309;
                            @elseif($isAd) background:#f5f3ff; color:#6d28d9;
                            @else background:#f1f5f9; color:#475569;
                            @endif">
                            @if($isModQuarantine)
                                <i class="fa-solid fa-shield-virus" style="font-size:16px;"></i>
                            @elseif($isModResolved)
                                <i class="fa-solid fa-shield-halved" style="font-size:16px;"></i>
                            @elseif($isRegistration)
                                <i class="fa-solid fa-file-signature" style="font-size:16px;"></i>
                            @elseif($isReferral)
                                <i class="fa-solid fa-share-nodes" style="font-size:16px;"></i>
                            @elseif($isAd)
                                <i class="fa-solid fa-rectangle-ad" style="font-size:16px;"></i>
                            @else
                                <i class="fa-solid fa-bell" style="font-size:16px;"></i>
                            @endif
                        </div>

                        {{-- نص الإشعار والشارة --}}
                        <div style="flex:1; min-width:0;">
                            
                            {{-- سطر الشارة والنقطة --}}
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:4px;">
                                
                                {{-- شارة نوع الإشعار في سطر واحد بدون انكسار --}}
                                @if($isModQuarantine)
                                    <span class="badge notif-badge-nowrap" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                        <i class="fa-solid fa-ban"></i>
                                        <span>قرار رقابي: إيقاف ظهور</span>
                                    </span>
                                @elseif($isModResolved)
                                    <span class="badge notif-badge-nowrap" style="background:#ecfdf5; color:#059669; border:1px solid #6ee7b7; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                        <i class="fa-solid fa-check"></i>
                                        <span>اعتماد رسمي وفك حجب</span>
                                    </span>
                                @elseif($isRegistration)
                                    <span class="badge notif-badge-nowrap" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                        <span>{{ $type === 'payment_proof_submitted' ? 'سند سداد مالي' : 'طلب تسجيل جديد' }}</span>
                                    </span>
                                @elseif($isReferral)
                                    <span class="badge notif-badge-nowrap" style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                        <i class="fa-solid fa-ticket"></i>
                                        <span>إحالة طلابية</span>
                                    </span>
                                @elseif($isAd)
                                    <span class="badge notif-badge-nowrap" style="background:#f5f3ff; color:#6d28d9; border:1px solid #ddd6fe; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                        <i class="fa-solid fa-rectangle-ad"></i>
                                        <span>إعلان ممول</span>
                                    </span>
                                @else
                                    <span class="badge notif-badge-nowrap" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                        <i class="fa-solid fa-info"></i>
                                        <span>إشعار نظام</span>
                                    </span>
                                @endif

                                {{-- نقطة التنبيه غير المقروء --}}
                                @if($isUnread)
                                    <span class="unread-dot" style="width:7px; height:7px; border-radius:50%; background:#ef4444; display:inline-block; flex-shrink:0;" title="إشعار غير مقروء"></span>
                                @endif
                            </div>

                            {{-- عنوان الإشعار بالكامل بدون قص ولا تغطية (مع إزالة أي عبارات زائدة مثل إشعار رقابي عاجل أو إشعار اعتمادي لأن شارة الإشعار تكفي) --}}
                            @php
                                $cleanTitle = preg_replace('/^(إشعار\s*رقابي\s*عاجل:\s*|إشعار\s*اعتمادي:\s*|إشعار\s*اعتماد:\s*|اشعار\s*رقابي\s*عاجل:\s*|اشعار\s*اعتماد:\s*)/u', '', $n->title);
                            @endphp
                            <div style="font-size:13.5px; font-weight:700; color:#0f172a; line-height:1.55; word-break:break-word;">
                                {{ $cleanTitle }}
                            </div>

                            {{-- ملخص نص الإشعار لغير الرقابي إن وجد --}}
                            @if(!$isModQuarantine && !$isModResolved && !empty($n->message))
                                <div style="font-size:12.5px; color:#475569; line-height:1.55; margin-top:4px; word-break:break-word;">
                                    {{ $n->message }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-5 text-muted" style="padding:40px 20px;">
                    <i class="fa-regular fa-bell-slash fs-2 mb-2 d-block" style="font-size:32px; color:#94a3b8;"></i>
                    <p style="font-size:14px; color:#64748b; margin:0;">لا توجد إشعارات حالياً. ستظهر هنا كافة التنبيهات والطلبات والمستجدات مرتبة زمنياً.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
