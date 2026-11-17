@extends('dashboards.admin.layout')

@section('page_title', 'إدارة العمولات المالية')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>إدارة العمولات المالية</strong>
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h1>إدارة وصرف العمولات المالية</h1>
        <p>متابعة وتدقيق استحقاقات العمولات للطلاب وصرف المكافآت المالية المعتمدة</p>
    </div>
</div>

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns:repeat(auto-fill,minmax(180px,1fr));margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-trophy"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['total_completed'] }}</div><div class="stat-label">إجمالي المؤهلة للعمولة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['pending_commission'] }}</div><div class="stat-label">بانتظار الصرف</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['paid_commission'] }}</div><div class="stat-label">تم صرفها</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['rejected'] }}</div><div class="stat-label">مرفوضة الصرف</div></div>
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
                <option value="">جميع الحالات</option>
                <option value="pending"  {{ request('status')=='pending'  ? 'selected' : '' }}>بانتظار الصرف (المستحقة)</option>
                <option value="paid"     {{ request('status')=='paid'     ? 'selected' : '' }}>تم الصرف</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>مرفوضة</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> تصفية</button>
            @if(request()->anyFilled(['search','status']))
                <a href="{{ route('admin.commissions') }}" class="btn btn-ghost btn-sm"><i class="fa-solid fa-xmark"></i> إلغاء</a>
            @endif
        </form>
    </div>
</div>

<!-- Commissions Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-sack-dollar" style="color:#f0c842;"></i> سجل العمولات والمكافآت</div>
        <span class="badge badge-gold">{{ $commissions->total() }} سجل</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="col-hide-mobile">#</th>
                    <th>رمز الإحالة</th>
                    <th>الطالب المستحق</th>
                    <th>الجامعة المسجل بها</th>
                    <th>حالة الاستحقاق</th>
                    <th>سند / إثبات التسجيل</th>
                    <th>حالة صرف العمولة</th>
                    <th>الإجراءات والاعتماد</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissions as $comm)
                @php
                    $isPaid = !empty($comm->commission_paid_at);
                    $isPending = (($comm->ref_status ?? '') === 'مكتملة') && !$isPaid;
                @endphp
                <tr id="comm-row-{{ $comm->referral_id }}">
                    <td class="col-hide-mobile" style="color:var(--ink-muted);font-size:12px;">{{ $comm->referral_id }}</td>
                    <td>
                        <span style="font-family:monospace;font-size:12px;font-weight:700;background:var(--surface-alt);padding:3px 8px;border-radius:4px;border:1px solid var(--border);">
                            {{ $comm->ref_code }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:13px;">{{ $comm->student?->full_name ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--ink-muted);" dir="ltr">{{ $comm->student?->user?->phone ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;font-weight:600;">{{ $comm->university?->university_name ?? '—' }}</td>
                    <td>
                        <span class="badge {{ match($comm->ref_status ?? '') {
                            'مكتملة' => 'badge-purple',
                            'مستخدم' => 'badge-blue',
                            'مرفوضة' => 'badge-red',
                            default  => 'badge-gray',
                        } }}">
                            {{ $comm->ref_status ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @if($comm->proof && $comm->proof->proof_file)
                            <a href="{{ asset('storage/' . $comm->proof->proof_file) }}" target="_blank" class="btn btn-outline btn-xs" title="استعراض سند الإثبات">
                                <i class="fa-solid fa-file-invoice"></i> عرض الإثبات
                            </a>
                        @else
                            <span class="badge badge-gray" style="font-size:10px;">لا يوجد إثبات مرفوع</span>
                        @endif
                    </td>
                    <td id="comm-status-cell-{{ $comm->referral_id }}">
                        @if($isPaid)
                            <span class="badge badge-green">
                                <i class="fa-solid fa-circle-check"></i> تم الصرف ({{ $comm->commission_paid_at->format('Y/m/d') }})
                            </span>
                        @elseif($isPending)
                            <span class="badge badge-orange">
                                <i class="fa-solid fa-hourglass-half"></i> بانتظار صرف العمولة
                            </span>
                        @elseif(($comm->ref_status ?? '') === 'مرفوضة')
                            <span class="badge badge-red">
                                <i class="fa-solid fa-circle-xmark"></i> مرفوضة
                            </span>
                        @else
                            <span class="badge badge-gray">قيد المتابعة</span>
                        @endif
                    </td>
                    <td>
                        @if($isPending)
                            <div style="display:flex;gap:6px;" id="comm-actions-{{ $comm->referral_id }}">
                                <button class="btn btn-success btn-xs" onclick="confirmCommission({{ $comm->referral_id }})" title="تأكيد صرف العمولة">
                                    <i class="fa-solid fa-check"></i> تأكيد الصرف
                                </button>
                                <button class="btn btn-danger btn-xs" onclick="openRejectCommissionModal({{ $comm->referral_id }})" title="رفض استحقاق العمولة">
                                    <i class="fa-solid fa-ban"></i> رفض
                                </button>
                            </div>
                        @else
                            <span style="color:var(--ink-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:32px;color:var(--ink-muted);">
                        <i class="fa-solid fa-folder-open" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                        لا توجد سجلات عمولات مطابقة
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($commissions->hasPages())
        <div class="card-footer" style="padding:12px 20px;">
            {{ $commissions->links() }}
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal-overlay" id="rejectCommissionModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3><i class="fa-solid fa-ban" style="color:var(--danger);"></i> رفض طلب العمولة</h3>
            <button class="modal-close" onclick="closeModal('rejectCommissionModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="rejectCommId">
            <div class="form-group">
                <label class="form-label">سبب الرفض <span class="req">*</span></label>
                <textarea id="rejectReason" class="form-control" rows="3" placeholder="وضّح سبب رفض صرف العمولة (سند غير صالح، إلغاء تسجيل...)" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('rejectCommissionModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitRejectCommission()"><i class="fa-solid fa-ban"></i> تأكيد الرفض</button>
        </div>
    </div>
</div>

@endsection

@section('extra_scripts')
<script>
async function confirmCommission(id) {
    if (!confirm('هل أنت متأكد من تأكيد صرف العمولة للطالب؟')) return;
    try {
        const res = await fetch(`{{ url('admin/referrals') }}/${id}/confirm-commission`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, 'success');
            const statusCell = document.getElementById(`comm-status-cell-${id}`);
            const actions = document.getElementById(`comm-actions-${id}`);
            if (statusCell) {
                statusCell.innerHTML = '<span class="badge badge-green"><i class="fa-solid fa-circle-check"></i> تم الصرف</span>';
            }
            if (actions) actions.innerHTML = '<span style="color:var(--ink-muted);font-size:12px;">—</span>';
        } else {
            showToast(data.message || 'حدث خطأ', 'error');
        }
    } catch(e) {
        showToast('تعذر الاتصال بالخادم', 'error');
    }
}

function openRejectCommissionModal(id) {
    document.getElementById('rejectCommId').value = id;
    document.getElementById('rejectReason').value = '';
    openModal('rejectCommissionModal');
}

async function submitRejectCommission() {
    const id = document.getElementById('rejectCommId').value;
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) {
        alert('يرجى كتابة سبب الرفض');
        return;
    }
    try {
        const res = await fetch(`{{ url('admin/referrals') }}/${id}/reject-commission`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reason })
        });
        const data = await res.json();
        if (data.success) {
            closeModal('rejectCommissionModal');
            showToast(data.message, 'success');
            const statusCell = document.getElementById(`comm-status-cell-${id}`);
            const actions = document.getElementById(`comm-actions-${id}`);
            if (statusCell) {
                statusCell.innerHTML = '<span class="badge badge-red"><i class="fa-solid fa-circle-xmark"></i> مرفوضة</span>';
            }
            if (actions) actions.innerHTML = '<span style="color:var(--ink-muted);font-size:12px;">—</span>';
        } else {
            showToast(data.message || 'حدث خطأ', 'error');
        }
    } catch(e) {
        showToast('تعذر الاتصال بالخادم', 'error');
    }
}
</script>
@endsection
