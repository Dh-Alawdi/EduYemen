{{-- تبويب الحسابات ووسائل الدفع الجامعية --}}
<div class="tab-pane-content" id="paymentsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">الحسابات ووسائل الدفع المعتمدة</h2>
            <p class="tab-subtitle">إدارة الحسابات البنكية، المحافظ الإلكترونية، وأرقام التحويل لتسديد رسوم التنسيق والجامعة.</p>
        </div>

        <div class="banner-actions">
            <button type="button" class="btn btn-navy btn-sm" onclick="openPaymentMethodModal()">
                <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                <span>إضافة وسيلة دفع جديدة</span>
            </button>
        </div>
    </div>

    <!-- شبكة كروت وسائل الدفع -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-purple-subtle text-purple">
                <svg class="ico" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">وسائل الدفع المعلنة للطلاب</h3>
                <p class="card-box-desc">تظهر هذه الوسائل للطالب أثناء تقديم طلب التنسيق لسداد الرسوم مباشرة</p>
            </div>
        </div>

        <div class="payment-cards-grid">
            @forelse($paymentMethods as $pm)
                <div class="payment-method-card {{ $pm->is_active ? 'pm-active' : 'pm-inactive' }}" id="paymentCard-{{ $pm->method_id }}">
                    <div class="pm-header">
                        <div class="pm-type-badge">{{ $pm->method_type }}</div>
                        <div class="pm-status-switch">
                            <label class="switch-toggle" title="تفعيل / تعطيل ظهور وسيلة الدفع">
                                <input type="checkbox" {{ $pm->is_active ? 'checked' : '' }} onchange="togglePaymentActive({{ $pm->method_id }})">
                                <span class="slider-round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="pm-body">
                        <h4 class="pm-title">{{ $pm->method_name }}</h4>
                        <div class="pm-account-number" dir="ltr">{{ $pm->account_number }}</div>
                        @if($pm->account_name)
                            <div class="pm-account-holder">باسم: <strong>{{ $pm->account_name }}</strong></div>
                        @endif
                        @if($pm->instructions)
                            <p class="pm-instructions">{{ $pm->instructions }}</p>
                        @endif
                    </div>

                    <div class="pm-footer">
                        <span class="pm-status-label">{{ $pm->is_active ? 'مفعلة وتظهر للطلبة' : 'معطلة مؤقتاً' }}</span>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0.5 px-2 fw-bold" onclick='editPaymentMethod(@json($pm))' title="تعديل بيانات وسيلة الدفع">
                                <i class="bi bi-pencil-square me-1"></i>تعديل
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-xs py-0.5 px-2" onclick="deletePaymentMethod({{ $pm->method_id }})" title="حذف وسيلة الدفع">
                                حذف
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-5 text-muted">
                    <p>لم يتم إضافة وسائل دفع أو حسابات بنكية حتى الآن.</p>
                    <button type="button" class="btn btn-navy btn-sm mt-2" onclick="openPaymentMethodModal()">
                        + إضافة أول وسيلة دفع الآن
                    </button>
                </div>
            @endforelse
        </div>
    </div>

</div>
