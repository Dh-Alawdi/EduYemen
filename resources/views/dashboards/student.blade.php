@extends('layouts.app')

@section('title', 'الملف الشخصي ولوحة التحكم — EduYemen')

@section('styles')
<style>
    /* ========================================================
       تصميم لوحة تحكم وملف الطالب الشخصي — الهوية الرسمية EduYemen
       ======================================================== */
    .student-dashboard-wrap {
        max-width: 1060px;
        margin: 0 auto;
    }

    /* كرت هوية الطالب الرئيسية (Header Profile Card) */
    .student-id-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 70%, var(--primary-light) 100%);
        border-radius: 18px;
        padding: 1.6rem;
        color: #ffffff;
        box-shadow: 0 6px 24px rgba(11, 61, 107, 0.16);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .student-id-card::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.25) 0%, transparent 70%);
        pointer-events: none;
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
        color: var(--primary);
        font-size: 1.85rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        border: 3px solid rgba(255, 255, 255, 0.85);
        flex-shrink: 0;
    }

    /* شارة الإشعارات غير المقروءة */
    .notif-unread-badge {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        font-size: 0.7rem !important;
        padding: 0.2rem 0.45rem !important;
        border-radius: 50rem !important;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ========================================================
       1. القائمة العمودية للشاشات الكبيرة (Desktop Vertical Nav)
       ======================================================== */
    .student-vnav-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 12px rgba(11, 61, 107, 0.05);
        padding: 0.6rem;
    }

    .student-vnav-item {
        width: 100%;
        text-align: right;
        border: none;
        background: transparent;
        color: var(--text-dark);
        font-weight: 700;
        font-size: 0.9rem;
        padding: 11px 14px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        cursor: pointer;
        margin-bottom: 3px;
        text-decoration: none;
    }

    .student-vnav-item i {
        font-size: 1.15rem;
        transition: transform 0.2s ease;
    }

    .student-vnav-item:hover {
        background: rgba(11, 61, 107, 0.06);
        color: var(--primary);
    }

    .student-vnav-item:hover i {
        transform: scale(1.1);
    }

    .student-vnav-item.active {
        background: var(--primary);
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(11, 61, 107, 0.22);
    }

    .student-vnav-item.active i {
        color: #ffffff !important;
    }

    /* حاويات المحتوى المقابلة للقائمة العمودية في الشاشات الكبيرة */
    .student-content-pane {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 12px rgba(11, 61, 107, 0.05);
        padding: 1.6rem;
    }

    /* ========================================================
       2. القوائم المنسدلة في شاشة الهاتف (Mobile Accordion System)
       ======================================================== */
    .mobile-accordion-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .mobile-accordion-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.04);
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .mobile-accordion-card.open {
        border-color: var(--primary);
        box-shadow: 0 4px 16px rgba(11, 61, 107, 0.08);
    }

    .mobile-accordion-header {
        width: 100%;
        background: #ffffff;
        border: none;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--text-dark);
        transition: all 0.2s ease;
        text-align: right;
    }

    .mobile-accordion-card.open .mobile-accordion-header {
        background: #f8fafc;
        color: var(--primary);
        border-bottom: 1px solid var(--border-color);
    }

    .mobile-chevron {
        font-size: 0.95rem;
        transition: transform 0.25s ease;
        color: var(--text-muted);
    }

    .mobile-accordion-card.open .mobile-chevron {
        transform: rotate(180deg);
        color: var(--primary);
    }

    .mobile-accordion-body {
        padding: 1.1rem;
        background: #ffffff;
    }

    /* بطاقة الإحالة الطلابية لجامعة محددة */
    .univ-referral-card {
        background: linear-gradient(135deg, #0b3d6b 0%, #072b4f 100%);
        border: 1.5px solid var(--accent);
        border-radius: 14px;
        padding: 1.25rem;
        color: #ffffff;
        box-shadow: 0 4px 18px rgba(197, 155, 39, 0.18);
        position: relative;
    }

    .referral-code-badge {
        font-size: 1.4rem;
        letter-spacing: 1.5px;
        font-weight: 900;
        color: var(--accent-light);
        font-family: monospace;
        background: rgba(0, 0, 0, 0.28);
        padding: 5px 14px;
        border-radius: 8px;
        display: inline-block;
        border: 1px dashed rgba(197, 155, 39, 0.6);
    }

    /* كروت خيارات الثيمات والمظهر */
    .theme-select-card {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        background: #ffffff;
    }

    .theme-select-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .theme-select-card.active {
        border-color: var(--primary);
        background: rgba(11, 61, 107, 0.05);
        box-shadow: 0 3px 12px rgba(11, 61, 107, 0.15);
    }

    /* كروت اختيار نغمات الإشعارات */
    .tone-select-item {
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        transition: all 0.15s ease;
        background: #ffffff;
    }

    .tone-select-item:hover {
        border-color: var(--primary);
        background: #f8fafc;
    }

    .tone-select-item.selected {
        border-color: var(--primary);
        background: rgba(11, 61, 107, 0.05);
    }

    /* كرت حالة طلب التسجيل */
    .req-compact-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.1rem;
        transition: all 0.2s ease;
    }

    .req-compact-card:hover {
        border-color: var(--primary);
        box-shadow: 0 3px 14px rgba(11, 61, 107, 0.06);
    }

    /* كروت وتنبيهات تنظيم الملف الشخصي الحديث */
    .profile-section-card {
        background: #fbfcfe;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        transition: all 0.2s ease;
    }
    .profile-section-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }
    .profile-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 0.6rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .profile-completeness-wrap {
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border: 1px solid #dbeafe;
        border-radius: 14px;
        padding: 1.15rem;
        margin-bottom: 1.5rem;
    }
    .profile-completeness-progress {
        height: 10px;
        border-radius: 8px;
        background-color: #e2e8f0;
        overflow: hidden;
    }
    .profile-completeness-bar {
        background: linear-gradient(90deg, #10b981 0%, #0284c7 100%);
        height: 100%;
        border-radius: 8px;
        transition: width 0.5s ease;
    }
    .academic-glance-badge {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        text-align: center;
    }
    .academic-glance-badge .label {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 2px;
    }
    .academic-glance-badge .val {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* كرت وتنبيه استكمال الطلب */
    .req-needs-completion-card {
        border: 2px solid #f59e0b !important;
        background: #fffdf7 !important;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.12) !important;
    }
    .completion-alert-banner {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fde68a;
        border-radius: 14px;
        padding: 1.1rem;
        margin-bottom: 1.25rem;
    }
    .completion-note-highlight {
        background: #ffffff;
        border: 1.5px dashed #f59e0b;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        color: #78350f;
        font-size: 0.88rem;
        line-height: 1.6;
    }

    /* عناصر الإشعارات */
    .notification-item {
        padding: 0.85rem 0.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .notification-item:last-child { border-bottom: none; }

    /* التجاوب مع شاشات الهواتف */
    @media (max-width: 767.98px) {
        .student-id-card {
            padding: 1.1rem;
        }
        .student-avatar {
            width: 54px;
            height: 54px;
            font-size: 1.35rem;
        }
        .student-content-pane {
            padding: 1.1rem;
        }
    }
</style>

{{-- Modal: سداد رسوم التنسيق ورفع السند للطالب --}}
<div class="modal fade" id="studentPaymentModal" tabindex="-1" aria-labelledby="studentPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px; height:36px; border-radius:8px; background:rgba(255,255,255,0.2); display:grid; place-items:center;">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="studentPaymentModalLabel">سداد رسوم التنسيق ورفع السند</h5>
                        <small class="text-white-50" id="spModalSubtitle">طلب #...</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="studentPaymentForm" onsubmit="submitStudentPaymentProof(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="spModalReqId" value="">

                <div class="modal-body p-4">
                    {{-- بطاقة الحسابات المعتمدة للجامعة --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">
                            <i class="bi bi-bank2 text-primary me-1"></i>حسابات الدفع المعتمدة لدى الجامعة:
                        </label>
                        <div class="p-2.5 bg-light rounded border" id="spModalMethodsList" style="max-height:160px; overflow-y:auto;">
                            <div class="text-center py-2 text-muted small">جارٍ تحميل حسابات الدفع...</div>
                        </div>
                    </div>

                    {{-- حقول إدخال بيانات السند --}}
                    <div class="row g-2">
                        <div class="col-md-6 col-12">
                            <label for="sp_method_id" class="form-label small fw-bold">وسيلة الدفع المستخدمة <span class="text-danger">*</span></label>
                            <select name="method_id" id="sp_method_id" class="form-select form-select-sm" required>
                                <option value="">-- اختر وسيلة التحويل --</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_reference" class="form-label small fw-bold">رقم الإشعار / الحوالة / السند <span class="text-danger">*</span></label>
                            <input type="text" name="reference" id="sp_reference" class="form-control form-control-sm font-monospace" placeholder="مثال: REF-8874125" dir="ltr" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_amount" class="form-label small fw-bold">المبلغ المسدد (ر.ي) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="sp_amount" class="form-control form-control-sm font-monospace" value="5000" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_pay_date" class="form-label small fw-bold">تاريخ السداد / التحويل</label>
                            <input type="date" name="pay_date" id="sp_pay_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label for="sp_proof_file" class="form-label small fw-bold">صورة أو ملف إشعار/سند السداد <span class="text-danger">*</span></label>
                            <input type="file" name="proof_file" id="sp_proof_file" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted d-block mt-0.5" style="font-size:0.72rem;">الصيغ المقبولة: JPG, PNG, PDF (الحد الأقصى 5MB)</small>
                        </div>
                        <div class="col-12">
                            <label for="sp_notes" class="form-label small text-muted">ملاحظات إضافية (اختياري)</label>
                            <input type="text" name="notes" id="sp_notes" class="form-control form-control-sm" placeholder="اسم المودع أو أي تفاصيل إضافية">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold" id="btnSubmitPaymentModal">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>رفع وتأكيد السند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: معاينة سند السداد --}}
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-dark text-white py-2 px-3">
                <h6 class="modal-title mb-0 small"><i class="bi bi-file-earmark-image me-1"></i>معاينة سند السداد: <span id="rpReferenceText" class="font-monospace"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center bg-light" id="rpModalBody" style="min-height:300px; max-height:80vh; overflow:auto;">
            </div>
            <div class="modal-footer py-1.5 px-3 bg-white justify-content-between">
                <a href="#" id="rpDownloadBtn" download class="btn btn-sm btn-primary py-1 px-3">
                    <i class="bi bi-download me-1"></i>تنزيل الملف
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="container py-4 student-dashboard-wrap">

    {{-- رسائل التنبيه والنجاح --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>يرجى تصحيح الأخطاء التالية:</div>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ====== 1. البطاقة الأولى: هوية ومعلومات المستخدم ====== --}}
    <div class="student-id-card mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="student-avatar">
                    {{ mb_substr($student->full_name ?? $user->username, 0, 1) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h4 class="fw-bold mb-0 text-white">{{ $student->full_name }}</h4>
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:0.75rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>طالب مسجل
                        </span>
                    </div>
                    <div class="small text-white-50 mt-1 d-flex align-items-center gap-3 flex-wrap">
                        <span><i class="bi bi-person me-1"></i>{{ $user->username }}@</span>
                        <span><i class="bi bi-telephone me-1"></i>{{ $user->phone ?? 'لا يوجد هاتف' }}</span>
                        @if($user->email)
                        <span><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                        @endif
                        @if($student->city)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $student->city }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- شريط الإحصائيات المختصر المعدل بدقة --}}
        <div class="row g-2 mt-3 pt-3 border-top border-white border-opacity-10 text-center">
            <div class="col-4 col-md-3">
                <div class="small text-white-50">طلبات التسجيل</div>
                <div class="fw-bold fs-6 text-white">{{ $requests->count() }} طلب</div>
            </div>
            <div class="col-4 col-md-3">
                <div class="small text-white-50">رموز الإحالة الصادرة</div>
                <div class="fw-bold fs-6 {{ $referrals->count() > 0 ? 'text-warning' : 'text-white-50' }}">
                    {{ $referrals->count() }} {{ $referrals->count() == 1 ? 'رمز' : 'رموز' }}
                </div>
            </div>
            <div class="col-4 col-md-3">
                <div class="small text-white-50">حالة الحساب</div>
                <div class="fw-bold fs-6 text-white">نشط ومعتمد</div>
            </div>
            <div class="col-12 col-md-3">
                <div class="small text-white-50">المظهر النشط</div>
                <div class="fw-bold fs-6 text-warning" id="activeThemeTextBadge">
                    {{ $user->theme_mode === 'dark' ? 'الوضع الليلي' : 'الوضع النهاري' }}
                </div>
            </div>
        </div>
    </div>

    {{-- حساب عدد الإشعارات غير المقروءة مبدئياً --}}
    @php
        $activeTab = request('tab', session('tab', 'profile'));
        $unreadCount = max(1, $requests->count() + $referrals->count());
    @endphp

    {{-- ====== 2. العرض في الشاشات الكبيرة (Desktop: قائمة عمودية يميناً وبجانبها المحتوى) ====== --}}
    <div class="d-none d-lg-block">
        <div class="row g-4">
            {{-- القائمة العمودية في يمين الصفحة (RTL) --}}
            <div class="col-lg-3 col-md-4">
                <div class="student-vnav-card">
                    <button type="button" class="student-vnav-item {{ $activeTab === 'profile' ? 'active' : '' }}" onclick="switchDesktopTab('profile')">
                        <i class="bi bi-person-vcard text-primary"></i>
                        <span>البيانات الشخصية</span>
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'requests' ? 'active' : '' }}" onclick="switchDesktopTab('requests')">
                        <i class="bi bi-file-earmark-check text-primary"></i>
                        <span>طلبات التسجيل</span>
                        @if($requests->count() > 0)
                        <span class="badge bg-primary-subtle text-primary border ms-auto" style="font-size:0.72rem;">{{ $requests->count() }}</span>
                        @endif
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'referral' ? 'active' : '' }}" onclick="switchDesktopTab('referral')">
                        <i class="bi bi-qr-code text-primary"></i>
                        <span>رموز الإحالة الصادرة</span>
                        @if($referrals->count() > 0)
                        <span class="badge bg-warning text-dark ms-auto" style="font-size:0.72rem;">{{ $referrals->count() }}</span>
                        @endif
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'notifications' ? 'active' : '' }}" onclick="switchDesktopTab('notifications')">
                        <i class="bi bi-bell text-primary"></i>
                        <span>الإشعارات والأصوات</span>
                        <span class="notif-unread-badge ms-auto" id="notifBadgeDesktop">{{ $unreadCount }}</span>
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'appearance' ? 'active' : '' }}" onclick="switchDesktopTab('appearance')">
                        <i class="bi bi-palette text-primary"></i>
                        <span>المظهر والثيمات</span>
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'security' ? 'active' : '' }}" onclick="switchDesktopTab('security')">
                        <i class="bi bi-shield-lock text-primary"></i>
                        <span>الأمان وكلمة المرور</span>
                    </button>
                </div>
            </div>

            {{-- المحتوى المقابل للتبويب النشط على اليسار --}}
            <div class="col-lg-9 col-md-8" id="desktopPaneContainer">
                {{-- سيتم وضع الحاويات هنا تلقائياً في الشاشات الكبيرة --}}
            </div>
        </div>
    </div>

    {{-- ====== 3. العرض في شاشة الهاتف (Mobile: قوائم منسدلة مغلقة في البداية تفتح إعداداتها تحتها مباشرة) ====== --}}
    <div class="d-lg-none mobile-accordion-wrapper mb-4">

        {{-- القائمة 1: البيانات الشخصية --}}
        <div class="mobile-accordion-card" id="mobileCard_profile">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('profile')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard text-primary fs-5"></i>
                    <span>البيانات الشخصية</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_profile" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_profile">
                    {{-- توضع حاوية البيانات الشخصية هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 2: طلبات التسجيل --}}
        <div class="mobile-accordion-card" id="mobileCard_requests">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('requests')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-check text-primary fs-5"></i>
                    <span>طلبات التسجيل</span>
                    @if($requests->count() > 0)
                    <span class="badge bg-primary-subtle text-primary border" style="font-size:0.72rem;">{{ $requests->count() }}</span>
                    @endif
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_requests" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_requests">
                    {{-- توضع حاوية طلبات التسجيل هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 3: رموز الإحالة الصادرة --}}
        <div class="mobile-accordion-card" id="mobileCard_referral">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('referral')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-qr-code text-primary fs-5"></i>
                    <span>رموز الإحالة الصادرة</span>
                    @if($referrals->count() > 0)
                    <span class="badge bg-warning text-dark" style="font-size:0.72rem;">{{ $referrals->count() }}</span>
                    @endif
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_referral" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_referral">
                    {{-- توضع حاوية رموز الإحالة هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 4: الإشعارات والأصوات --}}
        <div class="mobile-accordion-card" id="mobileCard_notifications">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('notifications')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bell text-primary fs-5"></i>
                    <span>الإشعارات والأصوات</span>
                    <span class="notif-unread-badge" id="notifBadgeMobile">{{ $unreadCount }}</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_notifications" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_notifications">
                    {{-- توضع حاوية الإشعارات هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 5: المظهر والثيمات --}}
        <div class="mobile-accordion-card" id="mobileCard_appearance">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('appearance')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-palette text-primary fs-5"></i>
                    <span>المظهر والثيمات</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_appearance" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_appearance">
                    {{-- توضع حاوية المظهر والثيمات هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 6: الأمان وكلمة المرور --}}
        <div class="mobile-accordion-card" id="mobileCard_security">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('security')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-primary fs-5"></i>
                    <span>الأمان وكلمة المرور</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_security" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_security">
                    {{-- توضع حاوية الأمان وتغيير كلمة المرور هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

    </div>

    {{-- ====== الحاويات الفعلية للمحتوى (Unique Content Panes) ====== --}}
    <div id="allPanesMasterSource" style="display: none;">

        {{-- ---------------- الحاوية 1: البيانات الشخصية والملف التعريفي المنظم ---------------- --}}
        <div id="pane_profile" class="student-content-pane">
            @php
                $profScore = 0;
                if ($student->full_name) $profScore += 20;
                if ($user->phone) $profScore += 20;
                if ($user->email) $profScore += 15;
                if ($student->gender) $profScore += 15;
                if ($student->birth_date) $profScore += 15;
                if ($student->city || $student->address) $profScore += 15;
                $completeness = min(100, $profScore);

                // استخراج المؤهل الأكاديمي من أحدث طلب تسجيل مسجل إن وجد
                $latestReq = $requests->first();
                $academicSecAvg = $latestReq ? $latestReq->sec_avg : ($student->high_school_avg ?? null);
                $academicSchool = $latestReq ? $latestReq->secondary_school : ($student->high_school_name ?? 'ثانوية عامة');
                $academicSeatNo = $latestReq ? $latestReq->seat_number : ($student->high_school_seat_no ?? '—');
                $academicYear = $latestReq ? $latestReq->grad_year : ($student->grad_year ?? 2024);
                $academicSection = $latestReq ? $latestReq->sec_section : 'علمي';
            @endphp

            {{-- شريط اكتمال الملف الشخصي التفاعلي --}}
            <div class="profile-completeness-wrap shadow-sm">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill text-primary fs-5"></i>
                        <span class="fw-bold text-dark fs-6">جاهزية الملف الشخصي:</span>
                        <span class="badge {{ $completeness >= 80 ? 'bg-success' : 'bg-warning text-dark' }} px-2.5 py-1">
                            {{ $completeness }}% مكتمل
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap small">
                        <span class="badge bg-light text-secondary border font-monospace">@ {{ $user->username }}</span>
                        <span class="badge bg-primary-subtle text-primary border">طالب جامعي</span>
                    </div>
                </div>
                <div class="profile-completeness-progress">
                    <div class="profile-completeness-bar" style="width: {{ $completeness }}%;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                    <span>{{ $completeness >= 90 ? 'كافة البيانات الأساسية مكتملة وموثقة للتقديم.' : 'يرجى استكمال الحقول الإلزامية لتسهيل إجراءات القبول.' }}</span>
                    <span class="fw-bold text-primary">{{ $completeness }}/100</span>
                </div>
            </div>

            <form action="{{ route('student.profile.update') }}" method="POST">
                @csrf

                {{-- القسم الأول: بيانات الهوية الشخصية --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title">
                        <i class="bi bi-person-vcard text-primary fs-5"></i>
                        <span>1. بيانات الهوية الشخصية والأساسية</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">الاسم الكامل (الرباعي وفق الهوية) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $student->full_name) }}" required placeholder="الاسم الرباعي الكامل">
                            </div>
                            @error('full_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">الجنس</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-gender-ambiguous"></i></span>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">اختر الجنس...</option>
                                    <option value="ذكر" {{ old('gender', $student->gender) == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                                    <option value="أنثى" {{ old('gender', $student->gender) == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">تاريخ الميلاد</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                            </div>
                            @error('birth_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الثاني: بيانات التواصل المباشر والإشعارات --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title">
                        <i class="bi bi-telephone-inbound text-primary fs-5"></i>
                        <span>2. قنوات التواصل والتنبيهات المعتمدة</span>
                        <span class="badge bg-light text-muted border ms-auto small" style="font-size:0.72rem;">ضرورية لاستلام قرارات القبول</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم الهاتف الأساسي <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control font-monospace @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required placeholder="77XXXXXXX">
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">يُستخدم للتواصل المباشر مع الجامعة وإرسال إشعارات القبول.</small>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="name@example.com">
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">تصلك عليه الإشعارات والرسائل الرسمية الصادرة من المنصة والجامعات.</small>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الثالث: العنوان ومقر الإقامة --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title">
                        <i class="bi bi-geo-alt text-primary fs-5"></i>
                        <span>3. مقر السكن والإقامة الحالية</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">المحافظة / المدينة</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-building"></i></span>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $student->city) }}" placeholder="صنعاء، عدن، تعز...">
                            </div>
                            @error('city')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-7">
                            <label class="form-label small fw-bold">العنوان السكني التفصيلي</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-pin-map"></i></span>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $student->address) }}" placeholder="المديرية، الحي، الشارع الرئيسي">
                            </div>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الرابع: المؤهل الأكاديمي المعتمد للثانوية العامة --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-mortarboard text-primary fs-5"></i>
                            <span>4. السجل الأكاديمي لشهادة الثانوية العامة</span>
                        </div>
                        <span class="badge bg-success-subtle text-success border small" style="font-size:0.72rem;">
                            <i class="bi bi-shield-check me-1"></i>موثق من الوزارة
                        </span>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">النسبة المئوية</div>
                                <div class="val text-primary">{{ $academicSecAvg ? number_format($academicSecAvg, 2) . '%' : 'قيد التدقيق' }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">رقم الجلوس</div>
                                <div class="val font-monospace">{{ $academicSeatNo }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">القسم والمسار</div>
                                <div class="val">{{ $academicSection }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">سنة التخرج</div>
                                <div class="val">{{ $academicYear }}</div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="p-2.5 rounded-3 bg-light border small text-muted d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div><i class="bi bi-building-check me-1 text-primary"></i>المدرسة: <strong>{{ $academicSchool }}</strong></div>
                                <span class="text-secondary" style="font-size:0.75rem;">يتم التحقق والمزامنة تلقائياً مع نظام وزارة التربية والتعليم عند تقديم أي طلب.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top flex-wrap gap-2">
                    <span class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>تحديث البيانات الشخصية لا يؤثر على الطلبات السابقة التي تم قبولها نهائياً.
                    </span>
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>حفظ وتحديث البيانات</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ---------------- الحاوية 2: طلبات التسجيل وضوابطها ---------------- --}}
        <div id="pane_requests" class="student-content-pane">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-text text-primary me-2"></i>طلبات التسجيل المقدمة</h5>
                    <p class="text-muted small mb-0">متابعة حالة طلبات التحاقك بالجامعات ومراجعة قرارات لجان القبول</p>
                </div>
                <a href="{{ route('student.apply') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>تقديم طلب تسجيل جديد</span>
                </a>
            </div>

            {{-- إشعار وتنبيه بارز في حال وجود طلبات تتطلب استكمال --}}
            @php
                $needsCompletionReqs = $requests->where('status', 'يتطلب استكمال');
            @endphp
            @if($needsCompletionReqs->count() > 0)
            <div class="completion-alert-banner shadow-sm mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="text-warning fs-3 lh-1 mt-1">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                            <h6 class="fw-bold text-dark mb-0">
                                تنبيه عاجل: لديك ({{ $needsCompletionReqs->count() }}) طلب تسجيل بحاجة لاستكمال وثائق أو بيانات!
                            </h6>
                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1">مطلوب إجراء من الطالب</span>
                        </div>
                        <p class="small text-muted mb-2">
                            قامت لجان القبول والتسجيل بمراجعة طلبك وطلبت استكمال نواقص معينة لإتمام القبول. يرجى مراجعة ملاحظات الجامعة واستكمالها فوراً:
                        </p>
                        @foreach($needsCompletionReqs as $compReq)
                        <div class="p-2.5 rounded-3 bg-white border border-warning-subtle mb-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <span class="fw-bold text-dark small">
                                    <i class="bi bi-building me-1 text-primary"></i>{{ $compReq->university->university_name }} — {{ $compReq->desired_spec }} (طلب رقم #{{ $compReq->req_id }})
                                </span>
                                <a href="{{ route('student.apply', ['req_id' => $compReq->req_id]) }}" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm" style="font-size:0.78rem;">
                                    <i class="bi bi-pencil-square me-1"></i>استكمال النواقص وإعادة الإرسال الآن
                                </a>
                            </div>
                            <div class="completion-note-highlight mt-1">
                                <i class="bi bi-chat-quote-fill me-1 text-warning"></i>
                                <strong>ملاحظة وتوجيهات الجامعة للاستكمال:</strong>
                                <span class="fw-bold text-dark">{{ $compReq->rejection_reason ?: 'يرجى فتح الاستمارة ومراجعة الوثائق وإعادة رفع ما يلزم.' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($requests->count() > 0)
            <div class="row g-3">
                @foreach($requests as $req)
                <div class="col-12">
                    <div class="req-compact-card {{ $req->status === 'يتطلب استكمال' ? 'req-needs-completion-card' : '' }}">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 mb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-light text-secondary border font-monospace">#{{ $req->req_id }}</span>
                                <span class="fw-bold text-dark fs-6">{{ $req->university->university_name }}</span>
                                <span class="badge bg-primary-subtle text-primary border">{{ $req->desired_spec }}</span>
                                @if($req->is_replacement)
                                <span class="badge bg-secondary-subtle text-secondary border" title="طلب بديل تم تقديمه بعد إلغاء طلب سابق خلال 24 ساعة">
                                    <i class="bi bi-arrow-repeat me-1"></i>طلب بديل
                                </span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                {{-- شارة حالة سداد الرسوم --}}
                                @if(!in_array($req->status, ['مسودة', 'ملغي']))
                                    @if($req->payment_status === 'مسدد ومعتمد')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i>الرسوم: مسددة ومعتمدة
                                        </span>
                                    @elseif($req->payment_status === 'قيد التحقق')
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                            <i class="bi bi-clock-history me-1"></i>الرسوم: قيد التدقيق المالي
                                        </span>
                                    @elseif($req->payment_status === 'سند مرفوض')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>الرسوم: سند مرفوض
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                            <i class="bi bi-cash-coin me-1"></i>الرسوم: بانتظار السداد
                                        </span>
                                    @endif
                                @endif

                                {{-- شارة حالة الطلب الأكاديمي --}}
                                @if($req->status === 'مقبول')
                                    <span class="badge bg-success px-3 py-1.5"><i class="bi bi-check-circle-fill me-1"></i>مقبول نهائياً</span>
                                @elseif($req->status === 'مرفوض')
                                    <span class="badge bg-danger px-3 py-1.5"><i class="bi bi-x-circle-fill me-1"></i>مرفوض</span>
                                @elseif($req->status === 'يتطلب استكمال')
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5"><i class="bi bi-exclamation-triangle-fill me-1"></i>يتطلب استكمال وثائق</span>
                                @elseif($req->status === 'مسودة')
                                    <span class="badge bg-secondary text-white px-3 py-1.5"><i class="bi bi-bookmark-fill me-1"></i>مسودة (محفوظة لديك فقط)</span>
                                @elseif($req->status === 'ملغي')
                                    <span class="badge bg-dark text-white px-3 py-1.5"><i class="bi bi-slash-circle me-1"></i>ملغي من قِبل الطالب</span>
                                @else
                                    <span class="badge bg-primary text-white px-3 py-1.5"><i class="bi bi-clock-history me-1"></i>قيد المراجعة لدى الجامعة</span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-2 small text-muted align-items-center">
                            <div class="col-md-3">
                                <i class="bi bi-calendar3 me-1 text-primary"></i>تاريخ التقديم: <strong>{{ $req->req_date ? $req->req_date->format('Y-m-d') : '—' }}</strong>
                            </div>
                            <div class="col-md-3">
                                <i class="bi bi-paperclip me-1 text-info"></i>الوثائق: <strong>{{ $req->regDocs->count() }} مستند</strong>
                            </div>
                            <div class="col-md-6 text-md-end d-flex justify-content-md-end gap-1 flex-wrap align-items-center">
                                @if($req->status === 'مسودة')
                                    <a href="{{ route('student.apply', ['req_id' => $req->req_id]) }}" class="btn btn-primary btn-xs py-1 px-2.5 fw-bold" style="font-size:0.75rem;">
                                        <i class="bi bi-send-fill me-1"></i>متابعة وإرسال الطلب
                                    </a>
                                @elseif($req->status === 'يتطلب استكمال')
                                    <a href="{{ route('student.apply', ['req_id' => $req->req_id]) }}" class="btn btn-warning btn-xs py-1 px-2.5 fw-bold" style="font-size:0.75rem;">
                                        <i class="bi bi-pencil-square me-1"></i>استكمال النواقص
                                    </a>
                                @endif

                                {{-- أزرار سداد الرسوم ومعاينة السند --}}
                                @if(!in_array($req->status, ['مسودة', 'ملغي']))
                                    @if(in_array($req->payment_status, ['بانتظار السداد', 'سند مرفوض']))
                                        <button type="button" class="btn btn-success btn-xs py-1 px-2.5 fw-bold" style="font-size:0.75rem;" onclick="openStudentPaymentModal({{ $req->req_id }}, '{{ addslashes($req->university->university_name) }}', '{{ addslashes($req->desired_spec) }}', {{ $req->university_id }})">
                                            <i class="bi bi-cash-stack me-1"></i>سداد الرسوم / رفع السند
                                        </button>
                                    @endif
                                    @if($req->latestPayment && $req->latestPayment->proof_file)
                                        <button type="button" class="btn btn-outline-info btn-xs py-1 px-2 fw-bold" style="font-size:0.75rem;" onclick="viewPaymentReceipt('{{ asset('storage/' . $req->latestPayment->proof_file) }}', '{{ $req->latestPayment->reference }}')">
                                            <i class="bi bi-receipt me-1"></i>معاينة السند
                                        </button>
                                    @endif
                                @endif

                                {{-- ضابط إلغاء الطلب: متاح فقط للطلبات النشطة مع استثناء الطلب الثاني خلال 24 ساعة --}}
                                @if(in_array($req->status, ['قيد المراجعة', 'يتطلب استكمال', 'مسودة']))
                                    @if($req->can_cancel && !$req->is_replacement)
                                        <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2.5 fw-bold" style="font-size:0.75rem;" onclick="cancelStudentRequest({{ $req->req_id }}, '{{ addslashes($req->desired_spec) }}', '{{ addslashes($req->university->university_name) }}')">
                                            <i class="bi bi-x-circle me-1"></i>إلغاء الطلب
                                        </button>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border py-1 px-2" style="font-size:0.72rem;" title="وفق ضوابط المنصة: لا يمكن إلغاء الطلب البديل (الثاني) لنفس الجامعة خلال 24 ساعة">
                                            <i class="bi bi-lock-fill me-1"></i>غير متاح الإلغاء (طلب بديل)
                                        </span>
                                    @endif
                                @endif

                                <a href="{{ route('universities.show', $req->university_id) }}" class="btn btn-outline-secondary btn-xs py-1 px-2" style="font-size:0.75rem;">
                                    صفحة الجامعة <i class="bi bi-arrow-left"></i>
                                </a>
                            </div>

                            {{-- ملاحظة رفض سند السداد إن وجدت --}}
                            @if($req->payment_status === 'سند مرفوض' && $req->latestPayment && $req->latestPayment->ver_result)
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="alert alert-danger py-2 px-3 small mb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <strong class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>ملاحظة رفض السند المالي:</strong>
                                        <span class="text-dark">{{ $req->latestPayment->ver_result }}</span>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-xs py-1 px-2.5 fw-bold" onclick="openStudentPaymentModal({{ $req->req_id }}, '{{ addslashes($req->university->university_name) }}', '{{ addslashes($req->desired_spec) }}', {{ $req->university_id }})">
                                        <i class="bi bi-arrow-clockwise me-1"></i>إعادة رفع سند سداد صحيح
                                    </button>
                                </div>
                            </div>
                            @endif

                            {{-- ملاحظة الاستكمال أو الرفض --}}
                            @if($req->status === 'يتطلب استكمال')
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="completion-note-highlight">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="text-warning-emphasis fw-bold"><i class="bi bi-exclamation-diamond-fill me-1"></i>ملاحظة لجنة القبول والتسجيل للاستكمال:</span>
                                        <span class="badge bg-warning text-dark">مطلوب تعديل / إضافة</span>
                                    </div>
                                    <div class="text-dark fw-bold">{{ $req->rejection_reason ?: 'يرجى مراجعة البيانات واستكمال الوثائق الناقصة.' }}</div>
                                    <div class="mt-2">
                                        <a href="{{ route('student.apply', ['req_id' => $req->req_id]) }}" class="btn btn-warning btn-sm fw-bold px-3">
                                            <i class="bi bi-upload me-1"></i>فتح الاستمارة واستكمال النواقص والمستندات الآن
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @elseif($req->status === 'مرفوض' && $req->rejection_reason)
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="alert alert-danger border-danger mb-0 py-2 px-3 small">
                                    <i class="bi bi-info-circle-fill me-1"></i><strong>سبب قرار الرفض من الجامعة:</strong> {{ $req->rejection_reason }}
                                </div>
                            </div>
                            @elseif($req->status === 'ملغي')
                            <div class="col-12 mt-2 pt-1 border-top text-muted small">
                                <i class="bi bi-info-circle me-1"></i>تم إلغاء هذا الطلب من قِبل الطالب بتاريخ {{ $req->cancelled_at ? $req->cancelled_at->format('Y-m-d H:i') : ($req->req_date ? $req->req_date->format('Y-m-d') : '') }}. يمكنك التقديم على تخصص بديل.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-4 text-center">
                <div class="display-5 text-muted mb-2"><i class="bi bi-inbox text-primary"></i></div>
                <h6 class="fw-bold text-dark mb-1">لم تقم بتقديم أي طلب تسجيل حتى الآن</h6>
                <p class="text-muted small mb-3">يمكنك البدء الآن بتقديم طلب تسجيل رسمي أو استعراض دليل الجامعات</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('student.apply') }}" class="btn btn-primary btn-sm fw-bold px-4 py-2">
                        <i class="bi bi-pencil-square me-1"></i>تقديم طلب تسجيل الآن
                    </a>
                    <a href="{{ route('universities.index') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3 py-2">
                        <i class="bi bi-buildings me-1"></i>تصفح دليل الجامعات
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- ---------------- الحاوية 3: رموز الإحالة الصادرة (خاصة بالجامعات) ---------------- --}}
        <div id="pane_referral" class="student-content-pane">
            <div class="mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-qr-code text-primary me-2"></i>رموز الإحالة المعتمدة الصادرة</h5>
                <p class="text-muted small mb-0">رموز الإحالة الرسمية الصادرة لحسابك للحضور الشخصي ومراجعة مسؤول القبول والتسجيل في كل جامعة</p>
            </div>

            @if($referrals->count() > 0)
            <div class="row g-3">
                @foreach($referrals as $ref)
                <div class="col-md-6">
                    <div class="univ-referral-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-building text-warning fs-5"></i>
                                <span class="fw-bold text-white small">{{ $ref->university->university_name }}</span>
                            </div>
                            <span class="badge bg-success px-2 py-1" style="font-size:0.7rem;">
                                <i class="bi bi-check-circle me-1"></i>{{ $ref->status }}
                            </span>
                        </div>

                        <div class="text-center my-3">
                            <div class="text-white-50 small mb-1" style="font-size:0.75rem;">رمز الإحالة الخاص بالجامعة:</div>
                            <div class="referral-code-badge" id="refCode_{{ $ref->referral_id }}">{{ $ref->referral_code }}</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-white border-opacity-10">
                            <span class="small text-white-50" style="font-size:0.75rem;">
                                تاريخ الإصدار: {{ $ref->created_at ? $ref->created_at->format('Y-m-d') : 'معتمد' }}
                            </span>
                            <button type="button" class="btn btn-warning btn-sm fw-bold px-3 text-dark" onclick="copySpecificReferralCode('refCode_{{ $ref->referral_id }}')">
                                <i class="bi bi-clipboard-check me-1"></i>نسخ الرمز
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-4 text-center">
                <div class="display-5 text-muted mb-2"><i class="bi bi-ticket-perforated text-warning"></i></div>
                <h6 class="fw-bold text-dark mb-1">لم يتم إصدار أي رمز إحالة لحسابك حتى الآن</h6>
                <p class="text-muted small mb-3">
                    يتم إصدار رمز الإحالة لجامعة محددة عند زيارة صفحة تلك الجامعة والنقر على "طلب إحالة للحضور الشخصي" ليتم تفعيل الرمز لدى مسؤول التسجيل في الجامعة بناءً على بياناتك.
                </p>
                <a href="{{ route('universities.index') }}" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="bi bi-buildings me-1"></i>تصفح دليل الجامعات واطلب إحالة
                </a>
            </div>
            @endif
        </div>

        {{-- ---------------- الحاوية 4: الإشعارات والأصوات ---------------- --}}
        <div id="pane_notifications" class="student-content-pane">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-bell-fill text-primary me-2"></i>الإشعارات وأصوات التنبيه</h5>
                    <p class="text-muted small mb-0">التحكم في الإشعارات ونغمة التنبيه المفضلة</p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="notificationMuteToggle" {{ $user->notifications_muted ? 'checked' : '' }} onchange="saveNotificationSettings()">
                        <label class="form-check-label small fw-bold" for="notificationMuteToggle">كتم الإشعارات</label>
                    </div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="notificationSoundToggle" {{ $user->notification_sound ? 'checked' : '' }} onchange="saveNotificationSettings()">
                        <label class="form-check-label small fw-bold" for="notificationSoundToggle">صوت التنبيه</label>
                    </div>
                </div>
            </div>

            {{-- قسم تخصيص نغمات الإشعارات المختلفة --}}
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary mb-2">
                    <i class="bi bi-music-note-beamed text-primary me-1"></i>اختيار نغمة التنبيه المفضلة:
                </label>
                @php
                    $currentTone = $user->notification_tone ?? 'chime';
                @endphp
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'chime' ? 'selected' : '' }}" onclick="selectNotificationTone('chime')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="chime" {{ $currentTone === 'chime' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة رنين كلاسيكية (Chime)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('chime');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'bell' ? 'selected' : '' }}" onclick="selectNotificationTone('bell')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="bell" {{ $currentTone === 'bell' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة تنبيه ناعمة (Soft Bell)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('bell');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'chord' ? 'selected' : '' }}" onclick="selectNotificationTone('chord')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="chord" {{ $currentTone === 'chord' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة إنجاز وترية (Upbeat Chord)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('chord');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'pluck' ? 'selected' : '' }}" onclick="selectNotificationTone('pluck')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="pluck" {{ $currentTone === 'pluck' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة نقر هادئة (Gentle Pluck)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('pluck');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- سجل الإشعارات --}}
            <div>
                <label class="form-label small fw-bold text-secondary mb-2">
                    <i class="bi bi-clock-history text-primary me-1"></i>سجل الإشعارات المستلمة:
                </label>
                <div class="notifications-list">
                    @if($requests->count() > 0)
                        @foreach($requests as $r)
                        <div class="notification-item">
                            <div class="text-primary fs-5 mt-1"><i class="bi bi-file-earmark-check-fill"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">{{ $r->university->university_name }}</span>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $r->req_date->diffForHumans() }}</span>
                                </div>
                                <p class="mb-0 small text-muted">
                                    طلبك للالتحاق بتخصص <strong>{{ $r->desired_spec }}</strong> حالته: 
                                    <span class="badge {{ $r->status === 'مقبول' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $r->status }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($referrals->count() > 0)
                        @foreach($referrals as $ref)
                        <div class="notification-item">
                            <div class="text-warning fs-5 mt-1"><i class="bi bi-qr-code"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">تم إصدار رمز إحالة لـ {{ $ref->university->university_name }}</span>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $ref->created_at ? $ref->created_at->diffForHumans() : 'معتمد' }}</span>
                                </div>
                                <p class="mb-0 small text-muted">
                                    رمز الإحالة الخاص بك هو <strong>{{ $ref->referral_code }}</strong>، يمكنك إبرازه لمسؤول القبول.
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    <div class="notification-item">
                        <div class="text-success fs-5 mt-1"><i class="bi bi-shield-check"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark small">منصة EduYemen الموحدة</span>
                                <span class="text-muted" style="font-size:0.75rem;">نظام</span>
                            </div>
                            <p class="mb-0 small text-muted">
                                حسابك نشط ومؤهل للتقديم على الجامعات واستعراض كافة التخصصات والخدمات المتاحة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---------------- الحاوية 5: المظهر والثيمات ---------------- --}}
        <div id="pane_appearance" class="student-content-pane">
            <div class="mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-palette-fill text-primary me-2"></i>تخصيص المظهر ووضع الشاشة</h5>
            </div>

            <div class="row g-3">
                {{-- خيار الوضع النهاري (الافتراضي المعتمد للمنصة) --}}
                <div class="col-md-6">
                    <div class="theme-select-card {{ ($user->theme_mode === 'light' || !$user->theme_mode || $user->theme_mode === 'auto') ? 'active' : '' }}" id="themeCard_light" onclick="selectStudentTheme('light')">
                        <div class="fs-1 text-warning mb-2"><i class="bi bi-sun-fill"></i></div>
                        <h6 class="fw-bold text-dark mb-1">الوضع النهاري (Light)</h6>
                        <p class="text-muted small mb-0">واجهة مشرقة واضحة ومعتمدة لجميع صفحات المنصة (الافتراضي)</p>
                    </div>
                </div>

                {{-- خيار الوضع الليلي --}}
                <div class="col-md-6">
                    <div class="theme-select-card {{ $user->theme_mode === 'dark' ? 'active' : '' }}" id="themeCard_dark" onclick="selectStudentTheme('dark')">
                        <div class="fs-1 text-primary mb-2"><i class="bi bi-moon-stars-fill"></i></div>
                        <h6 class="fw-bold text-dark mb-1">الوضع الليلي (Dark)</h6>
                        <p class="text-muted small mb-0">واجهة داكنة مخصصة للاستخدام الليلي</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---------------- الحاوية 6: الأمان وتغيير كلمة المرور ---------------- --}}
        <div id="pane_security" class="student-content-pane">
            <div class="mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-lock-fill text-primary me-2"></i>تغيير كلمة المرور وتأمين الحساب</h5>
                <p class="text-muted small mb-0">يجب تأكيد كلمة المرور الحالية قبل اعتماد كلمة المرور الجديدة</p>
            </div>

            <form action="{{ route('student.password.change') }}" method="POST" style="max-width: 520px;">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">كلمة المرور الحالية <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" class="form-control" required placeholder="أدخل كلمة مرورك الحالية">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="على الأقل 6 أحرف أو أرقام">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">تأكيد كلمة المرور الجديدة <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6" placeholder="أعد إدخال كلمة المرور الجديدة">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-key-fill me-1"></i>تحديث كلمة المرور
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

{{-- Modal: سداد رسوم التنسيق ورفع السند للطالب --}}
<div class="modal fade" id="studentPaymentModal" tabindex="-1" aria-labelledby="studentPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px; height:36px; border-radius:8px; background:rgba(255,255,255,0.2); display:grid; place-items:center;">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="studentPaymentModalLabel">سداد رسوم التنسيق ورفع السند</h5>
                        <small class="text-white-50" id="spModalSubtitle">طلب #...</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="studentPaymentForm" onsubmit="submitStudentPaymentProof(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="spModalReqId" value="">

                <div class="modal-body p-4">
                    {{-- بطاقة الحسابات المعتمدة للجامعة --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">
                            <i class="bi bi-bank2 text-primary me-1"></i>حسابات الدفع المعتمدة لدى الجامعة:
                        </label>
                        <div class="p-2.5 bg-light rounded border" id="spModalMethodsList" style="max-height:160px; overflow-y:auto;">
                            <div class="text-center py-2 text-muted small">جارٍ تحميل حسابات الدفع...</div>
                        </div>
                    </div>

                    {{-- حقول إدخال بيانات السند --}}
                    <div class="row g-2">
                        <div class="col-md-6 col-12">
                            <label for="sp_method_id" class="form-label small fw-bold">وسيلة الدفع المستخدمة <span class="text-danger">*</span></label>
                            <select name="method_id" id="sp_method_id" class="form-select form-select-sm" required>
                                <option value="">-- اختر وسيلة التحويل --</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_reference" class="form-label small fw-bold">رقم الإشعار / الحوالة / السند <span class="text-danger">*</span></label>
                            <input type="text" name="reference" id="sp_reference" class="form-control form-control-sm font-monospace" placeholder="مثال: REF-8874125" dir="ltr" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_amount" class="form-label small fw-bold">المبلغ المسدد (ر.ي) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="sp_amount" class="form-control form-control-sm font-monospace" value="5000" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_pay_date" class="form-label small fw-bold">تاريخ السداد / التحويل</label>
                            <input type="date" name="pay_date" id="sp_pay_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label for="sp_proof_file" class="form-label small fw-bold">صورة أو ملف إشعار/سند السداد <span class="text-danger">*</span></label>
                            <input type="file" name="proof_file" id="sp_proof_file" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted d-block mt-0.5" style="font-size:0.72rem;">الصيغ المقبولة: JPG, PNG, PDF (الحد الأقصى 5MB)</small>
                        </div>
                        <div class="col-12">
                            <label for="sp_notes" class="form-label small text-muted">ملاحظات إضافية (اختياري)</label>
                            <input type="text" name="notes" id="sp_notes" class="form-control form-control-sm" placeholder="اسم المودع أو أي تفاصيل إضافية">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold" id="btnSubmitPaymentModal">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>رفع وتأكيد السند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: معاينة سند السداد --}}
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-dark text-white py-2 px-3">
                <h6 class="modal-title mb-0 small"><i class="bi bi-file-earmark-image me-1"></i>معاينة سند السداد: <span id="rpReferenceText" class="font-monospace"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center bg-light" id="rpModalBody" style="min-height:300px; max-height:80vh; overflow:auto;">
            </div>
            <div class="modal-footer py-1.5 px-3 bg-white justify-content-between">
                <a href="#" id="rpDownloadBtn" download class="btn btn-sm btn-primary py-1 px-3">
                    <i class="bi bi-download me-1"></i>تنزيل الملف
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // التبويب النشط الحالي على الشاشات الكبيرة
    let currentDesktopTab = '{{ $activeTab }}';
    const allTabKeys = ['profile', 'requests', 'referral', 'notifications', 'appearance', 'security'];

    // فحص وتطبيق حالة قراءة الإشعارات
    function checkNotificationsReadState() {
        if (localStorage.getItem('student_notifications_read') === 'true') {
            const badgeDesktop = document.getElementById('notifBadgeDesktop');
            const badgeMobile = document.getElementById('notifBadgeMobile');
            if (badgeDesktop) badgeDesktop.style.display = 'none';
            if (badgeMobile) badgeMobile.style.display = 'none';
        }
    }

    // إخفاء شارة الإشعارات عند فتح التبويب وتسجيلها كمقروءة
    function markNotificationsAsRead() {
        localStorage.setItem('student_notifications_read', 'true');
        const badgeDesktop = document.getElementById('notifBadgeDesktop');
        const badgeMobile = document.getElementById('notifBadgeMobile');
        if (badgeDesktop) badgeDesktop.style.display = 'none';
        if (badgeMobile) badgeMobile.style.display = 'none';
    }

    // تنظيم وتوزيع الحاويات بحسب حجم الشاشة (هاتف مقابل ديسكتوب)
    function organizeResponsivePanes() {
        const isMobile = window.innerWidth < 992;
        const desktopContainer = document.getElementById('desktopPaneContainer');

        allTabKeys.forEach(key => {
            const pane = document.getElementById('pane_' + key);
            if (!pane) return;

            if (isMobile) {
                const mobileSlot = document.getElementById('slot_mobile_' + key);
                if (mobileSlot && pane.parentElement !== mobileSlot) {
                    mobileSlot.appendChild(pane);
                    pane.style.display = 'block'; // ظهور الحاوية داخل فتحة الأكورديون
                }
            } else {
                if (desktopContainer && pane.parentElement !== desktopContainer) {
                    desktopContainer.appendChild(pane);
                }
                // في الشاشات الكبيرة: عرض التبويب النشط فقط
                pane.style.display = (key === currentDesktopTab) ? 'block' : 'none';
            }
        });
    }

    // تبديل التبويب في الشاشات الكبيرة (Desktop)
    function switchDesktopTab(tabName) {
        currentDesktopTab = tabName;
        document.querySelectorAll('.student-vnav-item').forEach(b => b.classList.remove('active'));

        const activeBtn = Array.from(document.querySelectorAll('.student-vnav-item')).find(b => b.getAttribute('onclick') && b.getAttribute('onclick').includes(tabName));
        if (activeBtn) activeBtn.classList.add('active');

        allTabKeys.forEach(key => {
            const pane = document.getElementById('pane_' + key);
            if (pane) {
                pane.style.display = (key === tabName) ? 'block' : 'none';
            }
        });

        // إذا تم فتح تبويب الإشعارات، تمييزها كمقروءة وإخفاء الرقم
        if (tabName === 'notifications') {
            markNotificationsAsRead();
        }

        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    // فتح وإغلاق القوائم المنسدلة في شاشة الهاتف (Mobile Accordion)
    function toggleMobileAccordion(sectionKey) {
        const collapseEl = document.getElementById('mobileCollapse_' + sectionKey);
        const cardEl = document.getElementById('mobileCard_' + sectionKey);
        if (!collapseEl || !cardEl) return;

        const isCurrentlyOpen = (collapseEl.style.display !== 'none');

        if (isCurrentlyOpen) {
            collapseEl.style.display = 'none';
            cardEl.classList.remove('open');
        } else {
            collapseEl.style.display = 'block';
            cardEl.classList.add('open');

            // إذا فتح الطالب قائمة الإشعارات في الهاتف، إخفاء الرقم فورياً
            if (sectionKey === 'notifications') {
                markNotificationsAsRead();
            }

            // تمرير خفيف ومريح لعرض القائمة المفتوحة إن لزم
            setTimeout(() => {
                cardEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 50);
        }
    }

    // نسخ رمز إحالة محدد لجامعة
    function copySpecificReferralCode(elementId) {
        const codeEl = document.getElementById(elementId);
        if (!codeEl) return;
        const code = codeEl.textContent.trim();

        navigator.clipboard.writeText(code).then(() => {
            alert('تم نسخ رمز الإحالة بنجاح: ' + code);
        }).catch(err => {
            prompt('انسخ رمز الإحالة:', code);
        });
    }

    // تغيير وتحديد المظهر (ليلي / ضوئي / تلقائي)
    function selectStudentTheme(mode) {
        document.querySelectorAll('.theme-select-card').forEach(c => c.classList.remove('active'));
        const activeCard = document.getElementById('themeCard_' + mode);
        if (activeCard) activeCard.classList.add('active');

        window.applyEduYemenTheme(mode);

        const badge = document.getElementById('activeThemeTextBadge');
        if (badge) {
            badge.textContent = mode === 'dark' ? 'الوضع الليلي' : 'الوضع النهاري';
        }

        fetch('{{ route("student.theme.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ theme_mode: mode })
        }).catch(err => console.log('Theme saved locally'));
    }

    // اختيار نغمة التنبيه المفضلة
    function selectNotificationTone(tone) {
        document.querySelectorAll('.tone-select-item').forEach(item => item.classList.remove('selected'));
        const selectedRadio = document.querySelector(`input[name="toneRadio"][value="${tone}"]`);
        if (selectedRadio) {
            selectedRadio.checked = true;
            selectedRadio.closest('.tone-select-item').classList.add('selected');
        }

        playToneSound(tone);
        saveNotificationSettings(tone);
    }

    // حفظ تفضيلات الإشعارات (كتم / صوت / نغمة)
    function saveNotificationSettings(customTone) {
        const isMuted = document.getElementById('notificationMuteToggle').checked;
        const hasSound = document.getElementById('notificationSoundToggle').checked;
        const selectedRadio = document.querySelector('input[name="toneRadio"]:checked');
        const tone = customTone || (selectedRadio ? selectedRadio.value : 'chime');

        fetch('{{ route("student.notifications.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                notifications_muted: isMuted ? 1 : 0,
                notification_sound: hasSound ? 1 : 0,
                notification_tone: tone
            })
        });
    }

    // تشغيل نغمات الإشعارات المتعددة باستخدام Web Audio API
    function playToneSound(tone) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;

            if (tone === 'bell') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(659.25, now);
                gain.gain.setValueAtTime(0.2, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.8);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.8);
            } else if (tone === 'chord') {
                [523.25, 659.25, 783.99].forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(freq, now + (idx * 0.08));
                    gain.gain.setValueAtTime(0.14, now + (idx * 0.08));
                    gain.gain.exponentialRampToValueAtTime(0.001, now + (idx * 0.08) + 0.5);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + (idx * 0.08));
                    osc.stop(now + (idx * 0.08) + 0.5);
                });
            } else if (tone === 'pluck') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(440, now);
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.15);
                gain.gain.setValueAtTime(0.22, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.35);
            } else {
                const osc1 = ctx.createOscillator();
                const osc2 = ctx.createOscillator();
                const gain = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now);
                osc1.frequency.setValueAtTime(880, now + 0.12);
                gain.gain.setValueAtTime(0.18, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.55);
                osc1.connect(gain);
                gain.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.55);
            }
        } catch (e) {
            console.log('Audio playback not supported');
        }
    }

    // إلغاء طلب تسجيل من قِبل الطالب مع فحص الضوابط والتأكيد
    function cancelStudentRequest(reqId, specName, univName) {
        if (window.Swal) {
            Swal.fire({
                title: 'تأكيد إلغاء الطلب',
                html: `
                    <p class="mb-2">هل أنت متأكد من رغبتك في إلغاء طلب الالتحاق بتخصص <strong>"${specName}"</strong> لدى <strong>${univName}</strong>؟</p>
                    <div class="alert alert-warning text-start small mb-0 py-2 px-3 border border-warning-subtle" style="font-size:0.8rem; line-height:1.5;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>ضوابط المنصة:</strong> في حال إلغاء هذا الطلب، ستتمكن من التقديم على طلب بديل لنفس الجامعة، ولكن <strong>لن تتمكن من إلغاء الطلب الثاني</strong> إذا تم تقديمه خلال 24 ساعة.
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill me-1"></i>نعم، إلغاء الطلب الآن',
                cancelButtonText: 'تراجع وإبقاء الطلب'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeCancelRequest(reqId);
                }
            });
        } else {
            if (confirm(`هل أنت متأكد من رغبتك في إلغاء طلب الالتحاق بتخصص "${specName}" لدى ${univName}؟\nتنبيه: لن تتمكن من إلغاء الطلب الثاني البديل في حال تقديمه خلال 24 ساعة.`)) {
                executeCancelRequest(reqId);
            }
        }
    }

    function executeCancelRequest(reqId) {
        fetch(`/student/requests/${reqId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                throw new Error(data.message || 'حدث خطأ أثناء محاولة إلغاء الطلب.');
            }
            return data;
        })
        .then(data => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم إلغاء الطلب بنجاح',
                    text: data.message || 'تم إلغاء طلب التسجيل بنجاح. يمكنك الآن التقديم على تخصص آخر بديل.',
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'حسناً'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                alert(data.message || 'تم إلغاء طلب التسجيل بنجاح.');
                window.location.reload();
            }
        })
        .catch(err => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'تعذر إلغاء الطلب',
                    text: err.message,
                    confirmButtonColor: '#1e3a8a'
                });
            } else {
                alert(err.message);
            }
        });
    }

    // التهيئة عند تحميل المستند ومراقبة تغيير حجم الشاشة
    document.addEventListener('DOMContentLoaded', function() {
        organizeResponsivePanes();
        checkNotificationsReadState();

        // في الشاشة الصغيرة: تكون القوائم مغلقة مبدئياً إلا إذا كان هناك تبويب مطلوب فتحه أو رسالة نجاح/أخطاء
        if (window.innerWidth < 992) {
            allTabKeys.forEach(key => {
                const collapseEl = document.getElementById('mobileCollapse_' + key);
                const cardEl = document.getElementById('mobileCard_' + key);
                @if(request('tab') || session('success') || $errors->any())
                    const shouldOpen = (key === '{{ $activeTab }}');
                    if (collapseEl) collapseEl.style.display = shouldOpen ? 'block' : 'none';
                    if (cardEl) {
                        if (shouldOpen) cardEl.classList.add('open');
                        else cardEl.classList.remove('open');
                    }
                @else
                    if (collapseEl) collapseEl.style.display = 'none';
                    if (cardEl) cardEl.classList.remove('open');
                @endif
            });
        }
    });

    window.addEventListener('resize', function() {
        organizeResponsivePanes();
    });

    // فتح نافذة سداد الرسوم للطلب
    function openStudentPaymentModal(reqId, univName, specName, univId) {
        document.getElementById('spModalReqId').value = reqId;
        document.getElementById('spModalSubtitle').textContent = `الطلب #${reqId} لدى ${univName} (${specName})`;
        
        const listContainer = document.getElementById('spModalMethodsList');
        const selectEl = document.getElementById('sp_method_id');
        listContainer.innerHTML = '<div class="text-center py-2 text-muted small"><span class="spinner-border spinner-border-sm me-1"></span> جارٍ تحميل حسابات الدفع...</div>';
        selectEl.innerHTML = '<option value="">-- اختر وسيلة التحويل --</option>';

        // جلب وسائل الدفع الخاصة بالجامعة
        fetch(`/api/universities/${univId}/payment-methods`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success && res.payment_methods && res.payment_methods.length > 0) {
                let html = '<div class="row g-2">';
                res.payment_methods.forEach(pm => {
                    const opt = document.createElement('option');
                    opt.value = pm.method_id;
                    opt.textContent = `${pm.method_name} - ${pm.account_number} (${pm.method_type || 'حساب'})`;
                    selectEl.appendChild(opt);

                    html += `
                        <div class="col-md-6 col-12">
                            <div class="p-2 bg-white rounded border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small">${escapeHtml(pm.method_name)}</strong>
                                    <span class="badge bg-light text-secondary border" style="font-size:0.65rem;">${escapeHtml(pm.method_type || 'حساب')}</span>
                                </div>
                                <div class="font-monospace fw-bold text-navy" dir="ltr" style="font-size:0.8rem;">${escapeHtml(pm.account_number)}</div>
                                ${pm.account_name ? `<div class="text-muted" style="font-size:0.68rem;">باسم: ${escapeHtml(pm.account_name)}</div>` : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                listContainer.innerHTML = html;
            } else {
                listContainer.innerHTML = '<div class="text-muted small text-center py-2">لم تسجل الجامعة حسابات بنكية إلكترونية بعد، يرجى مراجعة الإدارة المالية بالجامعة.</div>';
            }
        })
        .catch(() => {
            listContainer.innerHTML = '<div class="text-danger small text-center py-2">تعذر تحميل بيانات وسائل الدفع.</div>';
        });

        const modalEl = document.getElementById('studentPaymentModal');
        if (window.bootstrap && bootstrap.Modal) {
            const m = bootstrap.Modal.getOrCreateInstance(modalEl);
            m.show();
        } else {
            $(modalEl).modal('show');
        }
    }

    // رفع سند السداد وتأكيد الدفع
    function submitStudentPaymentProof(e) {
        e.preventDefault();
        const reqId = document.getElementById('spModalReqId').value;
        const form = document.getElementById('studentPaymentForm');
        const submitBtn = document.getElementById('btnSubmitPaymentModal');
        const origText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الرفع والتوثيق...';

        const formData = new FormData(form);

        fetch(`/student/requests/${reqId}/payment`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(async r => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) throw new Error(data.message || 'فشل رفع سند السداد.');
            return data;
        })
        .then(data => {
            const modalEl = document.getElementById('studentPaymentModal');
            if (window.bootstrap && bootstrap.Modal) {
                const m = bootstrap.Modal.getInstance(modalEl);
                if (m) m.hide();
            } else {
                $(modalEl).modal('hide');
            }

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم رفع سند السداد بنجاح!',
                    text: data.message || 'تم تسجيل بيانات وسند السداد المالي بنجاح، وسيتم تدقيقه من الإدارة المالية للجامعة.',
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'حسناً'
                }).then(() => location.reload());
            } else {
                alert(data.message || 'تم رفع سند السداد بنجاح.');
                location.reload();
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'خطأ', text: err.message, confirmButtonColor: '#1e3a8a' });
            } else {
                alert(err.message);
            }
        });
    }

    // معاينة سند السداد
    function viewPaymentReceipt(url, reference) {
        const body = document.getElementById('rpModalBody');
        const refText = document.getElementById('rpReferenceText');
        const dlBtn = document.getElementById('rpDownloadBtn');

        if (refText) refText.textContent = reference ? `#${reference}` : '';
        if (dlBtn) dlBtn.href = url;

        const ext = url.split('.').pop().toLowerCase();
        if (ext === 'pdf') {
            body.innerHTML = `<iframe src="${url}" style="width:100%; height:500px; border:none; border-radius:8px;"></iframe>`;
        } else {
            body.innerHTML = `<img src="${url}" class="img-fluid rounded shadow-sm" style="max-height:550px; object-fit:contain;" alt="سند السداد">`;
        }

        const modalEl = document.getElementById('receiptPreviewModal');
        if (window.bootstrap && bootstrap.Modal) {
            const m = bootstrap.Modal.getOrCreateInstance(modalEl);
            m.show();
        } else {
            $(modalEl).modal('show');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

</script>

{{-- Modal: سداد رسوم التنسيق ورفع السند للطالب --}}
<div class="modal fade" id="studentPaymentModal" tabindex="-1" aria-labelledby="studentPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px; height:36px; border-radius:8px; background:rgba(255,255,255,0.2); display:grid; place-items:center;">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="studentPaymentModalLabel">سداد رسوم التنسيق ورفع السند</h5>
                        <small class="text-white-50" id="spModalSubtitle">طلب #...</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="studentPaymentForm" onsubmit="submitStudentPaymentProof(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="spModalReqId" value="">

                <div class="modal-body p-4">
                    {{-- بطاقة الحسابات المعتمدة للجامعة --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">
                            <i class="bi bi-bank2 text-primary me-1"></i>حسابات الدفع المعتمدة لدى الجامعة:
                        </label>
                        <div class="p-2.5 bg-light rounded border" id="spModalMethodsList" style="max-height:160px; overflow-y:auto;">
                            <div class="text-center py-2 text-muted small">جارٍ تحميل حسابات الدفع...</div>
                        </div>
                    </div>

                    {{-- حقول إدخال بيانات السند --}}
                    <div class="row g-2">
                        <div class="col-md-6 col-12">
                            <label for="sp_method_id" class="form-label small fw-bold">وسيلة الدفع المستخدمة <span class="text-danger">*</span></label>
                            <select name="method_id" id="sp_method_id" class="form-select form-select-sm" required>
                                <option value="">-- اختر وسيلة التحويل --</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_reference" class="form-label small fw-bold">رقم الإشعار / الحوالة / السند <span class="text-danger">*</span></label>
                            <input type="text" name="reference" id="sp_reference" class="form-control form-control-sm font-monospace" placeholder="مثال: REF-8874125" dir="ltr" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_amount" class="form-label small fw-bold">المبلغ المسدد (ر.ي) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="sp_amount" class="form-control form-control-sm font-monospace" value="5000" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sp_pay_date" class="form-label small fw-bold">تاريخ السداد / التحويل</label>
                            <input type="date" name="pay_date" id="sp_pay_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label for="sp_proof_file" class="form-label small fw-bold">صورة أو ملف إشعار/سند السداد <span class="text-danger">*</span></label>
                            <input type="file" name="proof_file" id="sp_proof_file" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted d-block mt-0.5" style="font-size:0.72rem;">الصيغ المقبولة: JPG, PNG, PDF (الحد الأقصى 5MB)</small>
                        </div>
                        <div class="col-12">
                            <label for="sp_notes" class="form-label small text-muted">ملاحظات إضافية (اختياري)</label>
                            <input type="text" name="notes" id="sp_notes" class="form-control form-control-sm" placeholder="اسم المودع أو أي تفاصيل إضافية">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold" id="btnSubmitPaymentModal">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>رفع وتأكيد السند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: معاينة سند السداد --}}
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-dark text-white py-2 px-3">
                <h6 class="modal-title mb-0 small"><i class="bi bi-file-earmark-image me-1"></i>معاينة سند السداد: <span id="rpReferenceText" class="font-monospace"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center bg-light" id="rpModalBody" style="min-height:300px; max-height:80vh; overflow:auto;">
            </div>
            <div class="modal-footer py-1.5 px-3 bg-white justify-content-between">
                <a href="#" id="rpDownloadBtn" download class="btn btn-sm btn-primary py-1 px-3">
                    <i class="bi bi-download me-1"></i>تنزيل الملف
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@endsection
