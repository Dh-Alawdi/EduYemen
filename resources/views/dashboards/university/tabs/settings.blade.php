{{-- تبويب إعدادات النظام والأمان وحالة القبول --}}
<div class="tab-pane-content" id="settingsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">إعدادات النظام والأمان وحالة القبول</h2>
            <p class="tab-subtitle">إدارة أمان الحساب، كلمة المرور، التحكم بفترة فتح وإغلاق التنسيق، وتخصيص تفضيلات العرض.</p>
        </div>
    </div>

    <!-- 1. التحكم في حالة القبول والتسجيل العامة للجامعة -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-teal-subtle text-teal">
                <svg class="ico" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">حالة فتح وإغلاق القبول والتنسيق الجامعي</h3>
                <p class="card-box-desc">عند إغلاق القبول، لن يتمكن الطلاب من إرسال طلبات تنسيق جديدة عبر البوابة</p>
            </div>
        </div>

        <form id="admissionSettingsForm" onsubmit="submitAdmissionSettings(event)">
            @csrf
            <div class="settings-toggle-row">
                <div class="toggle-info">
                    <strong class="toggle-title">تفعيل فترة القبول والتسجيل للعام الجامعي</strong>
                    <p class="toggle-desc" id="admissionStatusDescription">
                        {{ $university->status === 'نشطة' ? 'القبول والتسجيل مفتوح حالياً ويستقبل طلبات الطلبة.' : 'القبول مغلق حالياً، ولا يمكن للطلبة تقديم طلبات جديدة.' }}
                    </p>
                </div>
                <div class="toggle-switch-wrapper">
                    <label class="switch-toggle switch-lg">
                        <input type="checkbox" id="admissionStatusSwitch" name="admission_status" value="open" {{ $university->status === 'نشطة' ? 'checked' : '' }} onchange="onAdmissionSwitchChange(this.checked)">
                        <span class="slider-round"></span>
                    </label>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-teal btn-md" id="btnSaveAdmissionStatus">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                    <span>حفظ حالة القبول والتسجيل</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. أمان الحساب وتغيير كلمة المرور -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-blue-subtle text-blue">
                <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">تغيير كلمة المرور وتأمين حساب الجامعة</h3>
                <p class="card-box-desc">ينصح باختيار كلمة مرور قوية مكونة من أحرف وأرقام ورموز خاصة</p>
            </div>
        </div>

        <form id="universityPasswordForm" action="{{ route('university.password.change') }}" method="POST" onsubmit="submitChangePassword(event)">
            @csrf
            <div class="mobile-two-col-grid">
                <div class="form-group col-span-full-mobile">
                    <label for="current_password" class="form-label required">كلمة المرور الحالية</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="أدخل كلمة المرور الحالية للتأكيد">
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label required">كلمة المرور الجديدة</label>
                    <input type="password" id="new_password" name="password" class="form-control" required minlength="6" placeholder="على الأقل 6 خانات">
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label required">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" id="new_password_confirmation" name="password_confirmation" class="form-control" required minlength="6" placeholder="أعد كتابة كلمة المرور الجديدة">
                </div>
            </div>

            <div class="form-actions-bar">
                <button type="submit" class="btn btn-navy btn-md" id="btnChangePassword">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                    <span>تحديث كلمة المرور</span>
                </button>
            </div>
        </form>
    </div>

</div>
