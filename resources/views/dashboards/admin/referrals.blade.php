@extends('dashboards.admin.layout')

@section('page_title', 'إدارة الإحالات')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>إدارة الإحالات</strong>
@endsection

@section('content')

<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <div class="page-header-title">
        <h1>إدارة إحالات الطلاب</h1>
        <p>متابعة وتوليد وتدقيق مسارات الإحالة والرموز الترويجية للطلبة بالجامعات</p>
    </div>
    <div>
        <a href="{{ route('admin.commissions') }}" class="btn btn-gold" style="display:inline-flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-sack-dollar"></i>
            <span>إدارة العمولات المالية</span>
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns:repeat(auto-fill,minmax(150px,1fr));margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-share-nodes"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">إجمالي الإحالات</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-dot"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">إحالات نشطة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-check-double"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['used'] }}</div><div class="stat-label">مستخدمة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-trophy"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['completed'] }}</div><div class="stat-label">مكتملة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-dollar-sign"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['pending_commission'] }}</div><div class="stat-label">عمولات بانتظار الصرف</div></div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div class="filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="بحث برمز الإحالة أو اسم الطالب أو الجامعة..." value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select">
                <option value="">كل الحالات</option>
                <option value="نشط"      {{ request('status')=='نشط'      ? 'selected' : '' }}>نشط</option>
                <option value="مستخدم"   {{ request('status')=='مستخدم'   ? 'selected' : '' }}>مستخدم</option>
                <option value="مكتملة"   {{ request('status')=='مكتملة'   ? 'selected' : '' }}>مكتملة (بانتظار العمولة)</option>
                <option value="مرفوضة"   {{ request('status')=='مرفوضة'   ? 'selected' : '' }}>مرفوضة</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> تصفية</button>
            @if(request()->anyFilled(['search','status']))
                <a href="{{ route('admin.referrals') }}" class="btn btn-ghost btn-sm"><i class="fa-solid fa-xmark"></i> إلغاء</a>
            @endif
        </form>
    </div>
</div>

<!-- Referrals Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list"></i> قائمة الإحالات</div>
        <span class="badge badge-blue">{{ $referrals->total() }} إحالة</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="col-hide-mobile">#</th>
                    <th class="col-hide-mobile">رمز الإحالة</th>
                    <th>الطالب</th>
                    <th>الجامعة المقصودة</th>
                    <th class="col-hide-mobile">تاريخ الإحالة</th>
                    <th>الحالة</th>
                    <th class="col-hide-mobile">إثبات التسجيل</th>
                    <th class="col-hide-mobile">العمولة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $ref)
                <tr id="ref-row-{{ $ref->referral_id }}">
                    <td class="col-hide-mobile" style="color:var(--ink-muted);font-size:12px;">{{ $ref->referral_id }}</td>
                    <td class="col-hide-mobile">
                        <span style="font-family:monospace;font-size:12px;font-weight:700;background:var(--surface-alt);padding:3px 8px;border-radius:4px;border:1px solid var(--border);">
                            {{ $ref->ref_code }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:13px;">{{ $ref->student?->full_name ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--ink-muted);">{{ $ref->student?->user?->phone ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;font-weight:600;">{{ $ref->university?->university_name ?? '—' }}</td>
                    <td class="col-hide-mobile" style="font-size:12px;color:var(--ink-muted);">
                        {{ $ref->ref_date ? $ref->ref_date->format('Y/m/d') : '—' }}
                    </td>
                    <td>
                        <span class="badge {{ match($ref->ref_status ?? 'نشط') {
                            'نشط'      => 'badge-green',
                            'مستخدم'   => 'badge-blue',
                            'مكتملة'   => 'badge-purple',
                            'مرفوضة'   => 'badge-red',
                            default     => 'badge-gray',
                        } }}" id="ref-status-{{ $ref->referral_id }}">
                            {{ $ref->ref_status ?? 'نشط' }}
                        </span>
                    </td>
                    <td class="col-hide-mobile">
                        @if($ref->proof)
                            <span class="badge badge-teal" style="font-size:10px;">
                                <i class="fa-solid fa-file-check"></i> مرفوع
                            </span>
                        @else
                            <span class="badge badge-gray" style="font-size:10px;">
                                <i class="fa-solid fa-clock"></i> لم يُرفع
                            </span>
                        @endif
                    </td>
                    <td class="col-hide-mobile">
                        @php $hasPaidCommission = !empty($ref->commission_paid_at); @endphp
                        @if($hasPaidCommission)
                            <span class="badge badge-green" style="font-size:10px;">
                                <i class="fa-solid fa-check-circle"></i> تم الصرف
                            </span>
                        @elseif(($ref->ref_status ?? '') === 'مكتملة')
                            <span class="badge badge-orange" style="font-size:10px;">
                                <i class="fa-solid fa-clock"></i> مستحقة
                            </span>
                        @else
                            <span style="color:var(--ink-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            @if(($ref->ref_status ?? '') === 'مكتملة' && empty($ref->commission_paid_at))
                                <button class="btn btn-ghost btn-xs" style="color:var(--admin-green);"
                                        onclick="confirmCommission({{ $ref->referral_id }})">
                                    <i class="fa-solid fa-check"></i> تأكيد صرف العمولة
                                </button>
                                <button class="btn btn-ghost btn-xs" style="color:var(--admin-red);"
                                        onclick="openRejectCommissionModal({{ $ref->referral_id }})">
                                    <i class="fa-solid fa-xmark"></i> رفض
                                </button>
                            @endif
                            @if($ref->notes)
                                <span title="{{ $ref->notes }}" style="cursor:help;color:var(--ink-muted);">
                                    <i class="fa-solid fa-circle-info"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fa-solid fa-share-nodes"></i>
                            <h4>لا توجد إحالات</h4>
                            <p>لم يتم تسجيل أي إحالات في المنصة بعد</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($referrals->hasPages())
    <div class="pagination-wrap">
        <span>عرض {{ $referrals->firstItem() }}–{{ $referrals->lastItem() }} من {{ $referrals->total() }}</span>
        {{ $referrals->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>

<!-- Reject Commission Modal -->
<div class="modal-overlay" id="rejectCommissionModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3 style="color:var(--admin-red);"><i class="fa-solid fa-xmark-circle"></i> رفض طلب العمولة</h3>
            <button class="modal-close" onclick="closeModal('rejectCommissionModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">سبب الرفض <span class="req">*</span></label>
                <textarea id="rejectCommissionReason" class="form-control" rows="4" placeholder="اكتب سبب رفض طلب العمولة..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('rejectCommissionModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitRejectCommission()" id="rejectCommissionBtn">
                <i class="fa-solid fa-xmark"></i> تأكيد الرفض
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let activeRefId = null;

    async function confirmCommission(refId) {
        if (!confirm('هل تريد تأكيد صرف العمولة لهذه الإحالة؟')) return;

        try {
            const data = await adminFetch(`/admin/referrals/${refId}/confirm-commission`, 'POST');
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message, 'error');
            }
        } catch(e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        }
    }

    function openRejectCommissionModal(refId) {
        activeRefId = refId;
        document.getElementById('rejectCommissionReason').value = '';
        openModal('rejectCommissionModal');
    }

    async function submitRejectCommission() {
        const reason = document.getElementById('rejectCommissionReason').value.trim();
        if (!reason) { showToast('يرجى كتابة سبب الرفض.', 'error'); return; }

        const btn = document.getElementById('rejectCommissionBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الرفض...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/referrals/${activeRefId}/reject-commission`, 'POST', { reason });
            if (data.success) {
                closeModal('rejectCommissionModal');
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message, 'error');
            }
        } catch(e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-xmark"></i> تأكيد الرفض';
            btn.disabled = false;
        }
    }
</script>
@endsection
