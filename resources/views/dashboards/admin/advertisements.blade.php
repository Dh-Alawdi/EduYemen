@extends('dashboards.admin.layout')

@section('page_title', 'إدارة الإعلانات')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>إدارة الإعلانات</strong>
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h1>إدارة الإعلانات</h1>
        <p>مراجعة واعتماد أو رفض الإعلانات المقدمة من الجامعات والإشراف على الإعلانات المنشورة</p>
    </div>
</div>

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns:repeat(auto-fill,minmax(150px,1fr));margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-rectangle-ad"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">إجمالي الإعلانات</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">قيد المراجعة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['approved'] }}</div><div class="stat-label">معتمدة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['rejected'] }}</div><div class="stat-label">مرفوضة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-broadcast-tower"></i></div>
        <div class="stat-info"><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">منشورة حالياً</div></div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div class="filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="بحث باسم الجامعة أو عنوان الإعلان..." value="{{ request('search') }}">
            </div>
            <select name="review_status" class="filter-select">
                <option value="">كل حالات المراجعة</option>
                <option value="قيد المراجعة" {{ request('review_status')=='قيد المراجعة' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="معتمد"         {{ request('review_status')=='معتمد'         ? 'selected' : '' }}>معتمد</option>
                <option value="مرفوض"         {{ request('review_status')=='مرفوض'         ? 'selected' : '' }}>مرفوض</option>
            </select>
            <select name="status" class="filter-select">
                <option value="">كل حالات النشر</option>
                <option value="نشط"   {{ request('status')=='نشط'   ? 'selected' : '' }}>نشط</option>
                <option value="متوقف" {{ request('status')=='متوقف' ? 'selected' : '' }}>متوقف</option>
                <option value="منتهي" {{ request('status')=='منتهي' ? 'selected' : '' }}>منتهي</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> تصفية</button>
            @if(request()->anyFilled(['search','review_status','status']))
                <a href="{{ route('admin.advertisements') }}" class="btn btn-ghost btn-sm"><i class="fa-solid fa-xmark"></i> إلغاء</a>
            @endif
        </form>
    </div>
</div>

<!-- Advertisements Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list"></i> قائمة الإعلانات</div>
        <span class="badge badge-blue">{{ $advertisements->total() }} إعلان</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="col-hide-mobile">#</th>
                    <th>الجامعة</th>
                    <th>عنوان الإعلان</th>
                    <th class="col-hide-mobile">الباقة / المدة</th>
                    <th class="col-hide-mobile">مبلغ الإعلان</th>
                    <th class="col-hide-mobile">طريقة الدفع</th>
                    <th>حالة المراجعة</th>
                    <th class="col-hide-mobile">حالة النشر</th>
                    <th class="col-hide-mobile">التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advertisements as $ad)
                <tr id="ad-row-{{ $ad->adv_id }}">
                    <td class="col-hide-mobile" style="color:var(--ink-muted);font-size:12px;">{{ $ad->adv_id }}</td>
                    <td>
                        <div style="font-weight:700;font-size:13px;color:var(--ink);">{{ $ad->university?->university_name ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;max-width:200px;" class="truncate">{{ $ad->adv_title }}</div>
                    </td>
                    <td class="col-hide-mobile">
                        <div style="font-size:12px;font-weight:600;">{{ $ad->package ?: '—' }}</div>
                        <div style="font-size:11px;color:var(--ink-muted);">{{ $ad->duration ? $ad->duration . ' يوم' : '' }}</div>
                    </td>
                    <td class="col-hide-mobile">
                        @if($ad->amount)
                            <span style="font-weight:700;color:var(--admin-green);">{{ number_format($ad->amount, 0) }} ر.ي</span>
                        @else
                            <span style="color:var(--ink-muted);">—</span>
                        @endif
                    </td>
                    <td class="col-hide-mobile">
                        <div style="font-size:12px;">{{ $ad->pay_method ?: '—' }}</div>
                        @if($ad->pay_reference)
                            <div style="font-size:11px;color:var(--ink-muted);">رقم: {{ $ad->pay_reference }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ match($ad->review_status ?? 'قيد المراجعة') {
                            'معتمد'         => 'badge-green',
                            'مرفوض'         => 'badge-red',
                            'قيد المراجعة'  => 'badge-yellow',
                            default          => 'badge-gray',
                        } }}" id="ad-review-badge-{{ $ad->adv_id }}">
                            {{ $ad->review_status ?? 'قيد المراجعة' }}
                        </span>
                    </td>
                    <td class="col-hide-mobile">
                        <span class="badge {{ match($ad->status ?? 'متوقف') {
                            'نشط'    => 'badge-green',
                            'متوقف'  => 'badge-gray',
                            'منتهي'  => 'badge-orange',
                            default   => 'badge-gray',
                        } }}" id="ad-status-badge-{{ $ad->adv_id }}">
                            {{ $ad->status ?? 'متوقف' }}
                        </span>
                    </td>
                    <td class="col-hide-mobile" style="font-size:11px;color:var(--ink-muted);">
                        {{ $ad->created_at ? $ad->created_at->format('Y/m/d') : '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            <button class="btn btn-ghost btn-xs" onclick="viewAd({{ $ad->adv_id }})" title="عرض التفاصيل">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @if(($ad->review_status ?? 'قيد المراجعة') === 'قيد المراجعة')
                            <button class="btn btn-ghost btn-xs" onclick="approveAd({{ $ad->adv_id }})"
                                    title="اعتماد الإعلان" style="color:var(--admin-green);">
                                <i class="fa-solid fa-check"></i> اعتماد
                            </button>
                            <button class="btn btn-ghost btn-xs" onclick="openRejectAdModal({{ $ad->adv_id }})"
                                    title="رفض الإعلان" style="color:var(--admin-red);">
                                <i class="fa-solid fa-xmark"></i> رفض
                            </button>
                            @endif
                            <button class="btn btn-ghost btn-xs" onclick="confirmDeleteAd({{ $ad->adv_id }})"
                                    title="حذف الإعلان" style="color:var(--admin-red);">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="fa-solid fa-rectangle-ad"></i>
                            <h4>لا توجد إعلانات</h4>
                            <p>لم تقدم أي جامعة إعلانات بعد</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($advertisements->hasPages())
    <div class="pagination-wrap">
        <span>عرض {{ $advertisements->firstItem() }}–{{ $advertisements->lastItem() }} من {{ $advertisements->total() }}</span>
        {{ $advertisements->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>

<!-- View Ad Modal -->
<div class="modal-overlay" id="viewAdModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fa-solid fa-rectangle-ad"></i> تفاصيل الإعلان</h3>
            <button class="modal-close" onclick="closeModal('viewAdModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" id="viewAdBody">
            <div style="text-align:center;padding:40px;color:var(--ink-muted);"><span class="spinner" style="border-top-color:var(--admin-blue);width:30px;height:30px;border-width:3px;"></span></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('viewAdModal')">إغلاق</button>
            <button class="btn btn-success" id="viewAdApproveBtn" style="display:none;" onclick="approveAdFromModal()">
                <i class="fa-solid fa-check"></i> اعتماد الإعلان ونشره
            </button>
            <button class="btn btn-danger" id="viewAdRejectBtn" style="display:none;" onclick="openRejectAdModal(viewingAdId)">
                <i class="fa-solid fa-xmark"></i> رفض الإعلان
            </button>
        </div>
    </div>
</div>

<!-- Reject Ad Modal -->
<div class="modal-overlay" id="rejectAdModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3 style="color:var(--admin-red);"><i class="fa-solid fa-xmark-circle"></i> رفض الإعلان</h3>
            <button class="modal-close" onclick="closeModal('rejectAdModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">سبب الرفض <span class="req">*</span></label>
                <textarea id="rejectAdReason" class="form-control" rows="4" placeholder="اكتب سبب رفض الإعلان هنا ليتم إبلاغ الجامعة به..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('rejectAdModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitRejectAd()" id="rejectAdBtn">
                <i class="fa-solid fa-xmark"></i> تأكيد الرفض
            </button>
        </div>
    </div>
</div>

<!-- Delete Ad Modal -->
<div class="modal-overlay" id="deleteAdModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3 style="color:var(--admin-red);"><i class="fa-solid fa-triangle-exclamation"></i> تأكيد الحذف</h3>
            <button class="modal-close" onclick="closeModal('deleteAdModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:14px;color:var(--ink);">هل أنت متأكد من حذف هذا الإعلان نهائياً؟</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('deleteAdModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitDeleteAd()" id="deleteAdBtn">
                <i class="fa-solid fa-trash"></i> حذف
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let activeAdId = null;
    let viewingAdId = null;

    async function viewAd(adId) {
        viewingAdId = adId;
        openModal('viewAdModal');
        const body = document.getElementById('viewAdBody');
        body.innerHTML = `<div style="text-align:center;padding:40px;"><span class="spinner" style="border-top-color:var(--admin-blue);width:30px;height:30px;border-width:3px;"></span></div>`;
        document.getElementById('viewAdApproveBtn').style.display = 'none';
        document.getElementById('viewAdRejectBtn').style.display = 'none';

        try {
            const data = await adminFetch(`/admin/advertisements/${adId}`, 'GET');
            if (data.success) {
                const ad = data.ad;
                body.innerHTML = `
                    <div style="margin-bottom:16px;">
                        <div style="font-size:11px;color:var(--ink-muted);margin-bottom:4px;">الجامعة المقدِّمة</div>
                        <div style="font-size:14px;font-weight:700;color:var(--ink);">${ad.university_name||'—'}</div>
                    </div>
                    <div style="margin-bottom:16px;">
                        <div style="font-size:11px;color:var(--ink-muted);margin-bottom:4px;">عنوان الإعلان</div>
                        <div style="font-size:15px;font-weight:800;color:var(--ink);">${ad.title}</div>
                    </div>
                    <div style="background:var(--surface-alt);border-radius:var(--radius-sm);padding:14px;margin-bottom:16px;line-height:1.8;font-size:13px;">
                        ${ad.content?.replace(/\n/g,'<br>')||'—'}
                    </div>
                    <div class="form-grid" style="margin-bottom:16px;">
                        <div><div style="font-size:11px;color:var(--ink-muted);margin-bottom:3px;">الباقة الإعلانية</div><div style="font-weight:600;">${ad.package||'—'}</div></div>
                        <div><div style="font-size:11px;color:var(--ink-muted);margin-bottom:3px;">مدة الإعلان</div><div style="font-weight:600;">${ad.duration ? ad.duration+' يوم' : '—'}</div></div>
                        <div><div style="font-size:11px;color:var(--ink-muted);margin-bottom:3px;">مبلغ الإعلان</div><div style="font-weight:800;color:var(--admin-green);font-size:15px;">${ad.amount ? Number(ad.amount).toLocaleString('ar-YE')+' ر.ي' : '—'}</div></div>
                        <div><div style="font-size:11px;color:var(--ink-muted);margin-bottom:3px;">طريقة الدفع</div><div style="font-weight:600;">${ad.pay_method||'—'}</div></div>
                        <div><div style="font-size:11px;color:var(--ink-muted);margin-bottom:3px;">رقم الحوالة / المرجع</div><div style="font-weight:600;font-family:monospace;font-size:13px;">${ad.pay_reference||'—'}</div></div>
                        <div><div style="font-size:11px;color:var(--ink-muted);margin-bottom:3px;">تاريخ التقديم</div><div style="font-weight:600;">${ad.created_at||'—'}</div></div>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <div style="flex:1;text-align:center;padding:10px;background:var(--surface-alt);border-radius:var(--radius-sm);">
                            <div style="font-size:11px;color:var(--ink-muted);">حالة المراجعة</div>
                            <span class="badge ${ad.review_status=='معتمد'?'badge-green':ad.review_status=='مرفوض'?'badge-red':'badge-yellow'}" style="margin-top:4px;">${ad.review_status||'قيد المراجعة'}</span>
                        </div>
                        <div style="flex:1;text-align:center;padding:10px;background:var(--surface-alt);border-radius:var(--radius-sm);">
                            <div style="font-size:11px;color:var(--ink-muted);">حالة النشر</div>
                            <span class="badge ${ad.status=='نشط'?'badge-green':ad.status=='منتهي'?'badge-orange':'badge-gray'}" style="margin-top:4px;">${ad.status||'متوقف'}</span>
                        </div>
                    </div>
                    ${ad.notes ? `<div style="margin-top:12px;background:#fef9c3;border:1px solid #fde047;border-radius:var(--radius-sm);padding:10px 14px;font-size:12.5px;color:#a16207;"><strong>ملاحظات: </strong>${ad.notes}</div>` : ''}
                `;

                if (ad.review_status === 'قيد المراجعة' || !ad.review_status) {
                    document.getElementById('viewAdApproveBtn').style.display = 'flex';
                    document.getElementById('viewAdRejectBtn').style.display = 'flex';
                }
            }
        } catch(e) {
            body.innerHTML = `<p style="color:var(--admin-red);text-align:center;">حدث خطأ في تحميل البيانات.</p>`;
        }
    }

    async function approveAd(adId) {
        activeAdId = adId;
        if (!confirm('هل تريد اعتماد هذا الإعلان ونشره؟')) return;

        try {
            const data = await adminFetch(`/admin/advertisements/${adId}/approve`, 'POST');
            if (data.success) {
                showToast(data.message, 'success');
                document.getElementById(`ad-review-badge-${adId}`)?.remove();
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message, 'error');
            }
        } catch(e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        }
    }

    async function approveAdFromModal() {
        await approveAd(viewingAdId);
        closeModal('viewAdModal');
    }

    function openRejectAdModal(adId) {
        activeAdId = adId;
        document.getElementById('rejectAdReason').value = '';
        openModal('rejectAdModal');
    }

    async function submitRejectAd() {
        const reason = document.getElementById('rejectAdReason').value.trim();
        if (!reason) { showToast('يرجى كتابة سبب الرفض.', 'error'); return; }

        const btn = document.getElementById('rejectAdBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الرفض...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/advertisements/${activeAdId}/reject`, 'POST', { reason });
            if (data.success) {
                closeModal('rejectAdModal');
                closeModal('viewAdModal');
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

    function confirmDeleteAd(adId) {
        activeAdId = adId;
        openModal('deleteAdModal');
    }

    async function submitDeleteAd() {
        const btn = document.getElementById('deleteAdBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الحذف...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/advertisements/${activeAdId}`, 'DELETE');
            if (data.success) {
                closeModal('deleteAdModal');
                document.getElementById(`ad-row-${activeAdId}`)?.remove();
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        } catch(e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> حذف';
            btn.disabled = false;
        }
    }
</script>
@endsection
