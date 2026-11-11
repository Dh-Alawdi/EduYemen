@extends('layouts.app')

@section('title', 'استمارة طلب التسجيل الإلكتروني — EduYemen')

@section('content')
<div class="apply-stepper-page">

    {{-- علامة التحميل وسط الشاشة عند الاستعلام من الوزارة --}}
    <div id="screenLoadingOverlay" class="screen-loading-overlay d-none">
        <div class="screen-loading-card">
            <div class="spinner-border text-primary" role="status" style="width: 3.2rem; height: 3.2rem; border-width: 0.25rem;">
                <span class="visually-hidden">جارٍ التحميل...</span>
            </div>
            <h6 class="mt-3 fw-bold text-dark mb-1" style="font-size:0.95rem;">جارٍ التحقق من قاعدة بيانات الوزارة...</h6>
            <p class="text-muted small mb-0" style="font-size:0.75rem;">يتم الآن مطابقة رقم الجلوس وسحب الدرجات المعتمدة</p>
        </div>
    </div>

    <div class="container py-3">

        {{-- الشريط العلوي وزر الرجوع --}}
        <div class="apply-top-bar d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                @if(isset($selectedUniversityId) && $selectedUniversityId)
                    <a href="{{ route('universities.show', $selectedUniversityId) }}" class="btn btn-outline-secondary btn-sm py-1 px-3 rounded-pill fw-bold" style="font-size:0.82rem;">
                        <i class="bi bi-arrow-right me-1"></i>العودة لصفحة الجامعة
                    </a>
                @else
                    <a href="{{ route('universities.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-3 rounded-pill fw-bold" style="font-size:0.82rem;">
                        <i class="bi bi-arrow-right me-1"></i>دليل الجامعات
                    </a>
                @endif
            </div>

            @if(isset($existingRequest) && $existingRequest)
                @if($existingRequest->status === 'مسودة')
                    <span class="badge bg-secondary text-white px-3 py-1.5 font-monospace fw-bold" style="font-size:0.82rem;">
                        <i class="bi bi-bookmark-fill me-1"></i>مسودة محفوظة #{{ $existingRequest->req_id }}
                    </span>
                @else
                    <span class="badge bg-warning text-dark px-3 py-1.5 font-monospace fw-bold" style="font-size:0.82rem;">
                        <i class="bi bi-pencil-square me-1"></i>استكمال الطلب #{{ $existingRequest->req_id }}
                    </span>
                @endif
            @endif
        </div>

        {{-- تنبيه وضع المعاينة إن وجد --}}
        @if(isset($isPreview) && $isPreview)
        <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-2 rounded-3 border-warning shadow-sm">
            <i class="bi bi-eye-fill fs-5 text-warning"></i>
            <div><strong>وضع المعاينة:</strong> وضع مخصص لإدارة الجامعة. إرسال الطلب الفعلي متاح لحسابات الطلبة فقط.</div>
        </div>
        @endif

        {{-- تنبيه استكمال النواقص إن وجد --}}
        @if(isset($existingRequest) && $existingRequest && $existingRequest->rejection_reason)
        <div class="alert alert-info py-2 px-3 small d-flex align-items-start gap-2 mb-2 rounded-3 border-info shadow-sm">
            <i class="bi bi-info-circle-fill fs-5 text-info mt-1"></i>
            <div>
                <strong>ملاحظات لجنة القبول المطلوب استكمالها:</strong>
                <p class="mb-0 mt-1 text-dark">{{ $existingRequest->rejection_reason }}</p>
            </div>
        </div>
        @endif

        {{-- مؤشر الخطوات المتصل في أعلى الشاشة للجوال (Mobile Connected Stepper) --}}
        <div class="apply-mobile-stepper d-lg-none mb-3">
            <div class="h-stepper">
                <div class="h-step active" id="mobileDot_1" onclick="jumpToStep(1)">
                    <div class="h-step-circle">١</div>
                    <span class="h-step-text">الثانوية</span>
                </div>
                <div class="h-step-line" id="mobileLine_1"></div>
                <div class="h-step" id="mobileDot_2" onclick="jumpToStep(2)">
                    <div class="h-step-circle">٢</div>
                    <span class="h-step-text">الطالب</span>
                </div>
                <div class="h-step-line" id="mobileLine_2"></div>
                <div class="h-step" id="mobileDot_3" onclick="jumpToStep(3)">
                    <div class="h-step-circle">٣</div>
                    <span class="h-step-text">التخصص</span>
                </div>
                <div class="h-step-line" id="mobileLine_3"></div>
                <div class="h-step" id="mobileDot_4" onclick="jumpToStep(4)">
                    <div class="h-step-circle">٤</div>
                    <span class="h-step-text">الوثائق</span>
                </div>
                <div class="h-step-line" id="mobileLine_4"></div>
                <div class="h-step" id="mobileDot_5" onclick="jumpToStep(5)">
                    <div class="h-step-circle">٥</div>
                    <span class="h-step-text">الرسوم</span>
                </div>
            </div>
        </div>

        {{-- الهيكل الرئيسي: جانب الخطوات (Desktop) ونموذج الإدخال --}}
        <div class="row g-3">

            {{-- =========================================================
                 الجانب 1: الخطوات المتصلة بخط عمودي للشاشات الكبيرة
                 ========================================================= --}}
            <aside class="col-lg-4 col-xl-3 d-none d-lg-block">
                <div class="apply-steps-sidebar sticky-top" style="top: 25px;">
                    <div class="v-stepper">

                        {{-- الخطوة 1 --}}
                        <div class="v-step active" id="stepIndicator_1" onclick="jumpToStep(1)">
                            <div class="v-step-marker">
                                <div class="v-step-circle" id="stepCircle_1">
                                    <span class="step-num-text">١</span>
                                    <i class="bi bi-check-lg step-check-icon d-none"></i>
                                </div>
                                <div class="v-step-line" id="vLine_1"></div>
                            </div>
                            <div class="v-step-text">
                                <span class="v-step-title d-block">بيانات الثانوية</span>
                                <small class="v-step-subtitle d-block">برقم الجلوس وسنة التخرج</small>
                            </div>
                        </div>

                        {{-- الخطوة 2 --}}
                        <div class="v-step" id="stepIndicator_2" onclick="jumpToStep(2)">
                            <div class="v-step-marker">
                                <div class="v-step-circle" id="stepCircle_2">
                                    <span class="step-num-text">٢</span>
                                    <i class="bi bi-check-lg step-check-icon d-none"></i>
                                </div>
                                <div class="v-step-line" id="vLine_2"></div>
                            </div>
                            <div class="v-step-text">
                                <span class="v-step-title d-block">بيانات الطالب</span>
                                <small class="v-step-subtitle d-block">العنوان ومعلومات التواصل</small>
                            </div>
                        </div>

                        {{-- الخطوة 3 --}}
                        <div class="v-step" id="stepIndicator_3" onclick="jumpToStep(3)">
                            <div class="v-step-marker">
                                <div class="v-step-circle" id="stepCircle_3">
                                    <span class="step-num-text">٣</span>
                                    <i class="bi bi-check-lg step-check-icon d-none"></i>
                                </div>
                                <div class="v-step-line" id="vLine_3"></div>
                            </div>
                            <div class="v-step-text">
                                <span class="v-step-title d-block">اختيار التخصص</span>
                                <small class="v-step-subtitle d-block">الجامعة والكلية والبرنامج</small>
                            </div>
                        </div>

                        {{-- الخطوة 4 --}}
                        <div class="v-step" id="stepIndicator_4" onclick="jumpToStep(4)">
                            <div class="v-step-marker">
                                <div class="v-step-circle" id="stepCircle_4">
                                    <span class="step-num-text">٤</span>
                                    <i class="bi bi-check-lg step-check-icon d-none"></i>
                                </div>
                                <div class="v-step-line" id="vLine_4"></div>
                            </div>
                            <div class="v-step-text">
                                <span class="v-step-title d-block">الوثائق والمستندات</span>
                                <small class="v-step-subtitle d-block">المرفقات المطلوبة للقبول</small>
                            </div>
                        </div>

                        {{-- الخطوة 5 --}}
                        <div class="v-step" id="stepIndicator_5" onclick="jumpToStep(5)">
                            <div class="v-step-marker">
                                <div class="v-step-circle" id="stepCircle_5">
                                    <span class="step-num-text">٥</span>
                                    <i class="bi bi-check-lg step-check-icon d-none"></i>
                                </div>
                            </div>
                            <div class="v-step-text">
                                <span class="v-step-title d-block">رسوم وسند السداد</span>
                                <small class="v-step-subtitle d-block">بيانات السند وتأكيد الطلب</small>
                            </div>
                        </div>

                    </div>
                </div>
            </aside>

            {{-- =========================================================
                 الجانب 2: الحقول ونموذج الإدخال النشط (Active Step Form)
                 ========================================================= --}}
            <main class="col-lg-8 col-xl-9 col-12">
                <div class="apply-form-main-card">

                    <form id="registrationForm" onsubmit="handleApplySubmit(event)" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="is_draft" id="applyIsDraft" value="0">
                        @if(isset($existingRequest) && $existingRequest)
                            <input type="hidden" name="existing_req_id" value="{{ $existingRequest->req_id }}">
                        @endif

                        {{-- الحقول المدارة تلقائياً ومخفية من العرض المباشر في الاستمارة --}}
                        <input type="hidden" name="nationality" id="nationality" value="{{ old('nationality', $existingRequest->nationality ?? 'يمني') }}">
                        <input type="hidden" name="province" id="province" value="{{ old('province', $existingRequest->province ?? 'أمانة العاصمة') }}">
                        <input type="hidden" name="total_score" id="total_score" value="{{ old('total_score', $existingRequest->total_score ?? '') }}">

                        {{-- =================================================
                             الخطوة 1: بيانات الثانوية
                             ================================================= --}}
                        <div class="apply-step-pane active" id="stepPane_1">
                            <div class="apply-pane-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="apply-badge-step">١</span>
                                    <h5 class="apply-pane-title mb-0">بيانات الثانوية</h5>
                                </div>
                            </div>

                            <div class="apply-pane-body">
                                <div class="row g-2">

                                    {{-- التحقق من رقم الجلوس --}}
                                    <div class="col-12 mb-3">
                                        <div class="apply-ministry-box">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="fw-bold text-dark small"><i class="bi bi-shield-check text-success me-1"></i>التحقق من رقم الجلوس</span>
                                                <span class="badge bg-success text-white small" id="badgeMinistryVerified" style="display:none; font-size:0.75rem;">
                                                    <i class="bi bi-check-circle me-1"></i>تم التحقق
                                                </span>
                                            </div>

                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3 col-6">
                                                    <label class="apply-label required" for="seat_number">رقم الجلوس</label>
                                                    <input type="text" class="form-control apply-input font-monospace fw-bold text-primary" name="seat_number" id="seat_number"
                                                           value="{{ old('seat_number', $existingRequest->seat_number ?? '') }}"
                                                           placeholder="مثال: 423000" autofocus required>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <label class="apply-label required" for="grad_year">سنة التخرج</label>
                                                    <select class="form-select apply-input font-monospace" name="grad_year" id="grad_year" required>
                                                        @php $currentY = (int)date('Y'); @endphp
                                                        @for($y = $currentY; $y >= $currentY - 10; $y--)
                                                            <option value="{{ $y }}" {{ old('grad_year', $existingRequest->grad_year ?? $currentY) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <label class="apply-label required" for="sec_section">القسم</label>
                                                    <select class="form-select apply-input" name="sec_section" id="sec_section" required>
                                                        <option value="علمي" {{ old('sec_section', $existingRequest->sec_section ?? 'علمي') === 'علمي' ? 'selected' : '' }}>علمي</option>
                                                        <option value="أدبي" {{ old('sec_section', $existingRequest->sec_section ?? '') === 'أدبي' ? 'selected' : '' }}>أدبي</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <button type="button" class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-sm" id="btnVerifyMinistry" onclick="queryMinistryData()" style="height:38px; font-size:0.84rem;">
                                                        <i class="bi bi-search"></i>
                                                        <span>جلب النتيجة</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="ministryLookupAlert" class="mt-2" style="display:none;"></div>
                                        </div>
                                    </div>

                                    {{-- الاسم الكامل --}}
                                    <div class="col-12">
                                        <label class="apply-label required mb-1" for="full_name">الاسم الكامل (الرباعي كما في وثيقة الثانوية)</label>
                                        <input type="text" class="form-control apply-input" name="full_name" id="full_name"
                                               value="{{ old('full_name', $existingRequest->full_name ?? '') }}"
                                               placeholder="يُجلب تلقائياً بعد الضغط على 'جلب النتيجة'..." required readonly>
                                    </div>

                                    {{-- المدرسة الثانوية --}}
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label required" for="secondary_school">المدرسة الثانوية</label>
                                        <input type="text" class="form-control apply-input" name="secondary_school" id="secondary_school"
                                               value="{{ old('secondary_school', $existingRequest->secondary_school ?? '') }}"
                                               placeholder="المدرسة الثانوية..." required readonly>
                                    </div>

                                    {{-- النسبة والمجموع --}}
                                    <div class="col-md-6 col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="apply-label required mb-0" for="sec_avg">النسبة (%)</label>
                                            <span class="text-muted small" style="font-size:0.7rem;">
                                                 المجموع: <strong id="calculatedScoreBadge" class="text-primary font-monospace">—</strong>
                                            </span>
                                        </div>
                                        <input type="number" step="0.01" min="50" max="100" class="form-control apply-input font-monospace" name="sec_avg" id="sec_avg"
                                               value="{{ old('sec_avg', $existingRequest->sec_avg ?? '') }}"
                                               placeholder="النسبة..." required readonly oninput="autoCalculateScore()">
                                    </div>

                                    {{-- الجنس، تاريخ الميلاد، ومكان الميلاد --}}
                                    <div class="col-md-3 col-12">
                                        <label class="apply-label required">الجنس</label>
                                        <div class="d-flex align-items-center gap-3 pt-1">
                                            <label class="apply-radio-opt">
                                                <input type="radio" name="gender" id="gender_male" value="ذكر" {{ old('gender', $existingRequest->gender ?? 'ذكر') === 'ذكر' ? 'checked' : '' }} required>
                                                <span>ذكر</span>
                                            </label>
                                            <label class="apply-radio-opt">
                                                <input type="radio" name="gender" id="gender_female" value="أنثى" {{ old('gender', $existingRequest->gender ?? '') === 'أنثى' ? 'checked' : '' }}>
                                                <span>أنثى</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <label class="apply-label required" for="birth_date">تاريخ الميلاد</label>
                                        <input type="date" class="form-control apply-input" name="birth_date" id="birth_date"
                                               value="{{ old('birth_date', isset($existingRequest->birth_date) ? $existingRequest->birth_date->format('Y-m-d') : '') }}"
                                               placeholder="يُجلب تلقائياً..." required readonly>
                                    </div>
                                    <div class="col-md-5 col-sm-6 col-12">
                                        <label class="apply-label required" for="birth_place">مكان الميلاد</label>
                                        <input type="text" class="form-control apply-input" name="birth_place" id="birth_place"
                                               value="{{ old('birth_place', $existingRequest->birth_place ?? '') }}"
                                               placeholder="يُجلب تلقائياً من سجلات الوزارة..." required readonly>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- =================================================
                             الخطوة 2: بيانات الطالب
                             ================================================= --}}
                        <div class="apply-step-pane" id="stepPane_2">
                            <div class="apply-pane-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="apply-badge-step">٢</span>
                                    <h5 class="apply-pane-title mb-0">بيانات الطالب</h5>
                                </div>
                            </div>

                            <div class="apply-pane-body">
                                <div class="row g-2">
                                    {{-- المديرية وعنوان السكن (محافظة السكن مدرجة تلقائياً كأمانة العاصمة) --}}
                                    <div class="col-md-5 col-12">
                                        <label class="apply-label required" for="residence">المديرية</label>
                                        <input type="text" class="form-control apply-input" name="residence" id="residence"
                                               value="{{ old('residence', $existingRequest->residence ?? '') }}"
                                               placeholder="مثال: السبعين، معين، التحرير..." required>
                                    </div>
                                    <div class="col-md-7 col-12">
                                        <label class="apply-label required" for="home_address">عنوان السكن بالتفصيل</label>
                                        <input type="text" class="form-control apply-input" name="home_address" id="home_address"
                                               value="{{ old('home_address', $existingRequest->home_address ?? '') }}"
                                               placeholder="الحي، الشارع، أقرب معلم..." required>
                                    </div>

                                    {{-- هاتف الطالب والبريد الإلكتروني --}}
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label required" for="phone">رقم هاتف الطالب (واتساب)</label>
                                        <input type="tel" class="form-control apply-input font-monospace" name="phone" id="phone"
                                               value="{{ old('phone', $existingRequest->phone ?? ($user->phone ?? '')) }}"
                                               placeholder="77XXXXXXX" required>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label" for="email">البريد الإلكتروني (إن وجد)</label>
                                        <input type="email" class="form-control apply-input" name="email" id="email"
                                               value="{{ old('email', $existingRequest->email ?? ($user->email ?? '')) }}"
                                               placeholder="student@example.com">
                                    </div>

                                    {{-- اسم ولي الأمر وصلة القرابة --}}
                                    <div class="col-md-8 col-12">
                                        <label class="apply-label required" for="guardian_name">اسم ولي الأمر</label>
                                        <input type="text" class="form-control apply-input" name="guardian_name" id="guardian_name"
                                               value="{{ old('guardian_name', $existingRequest->guardian_name ?? '') }}"
                                               placeholder="اسم ولي الأمر كاملاً..." required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="apply-label required" for="guardian_rel">صلة القرابة</label>
                                        <select class="form-select apply-input" name="guardian_rel" id="guardian_rel" required>
                                            @php $rels = ['أب' => 'الأب', 'أم' => 'الأم', 'أخ' => 'الأخ', 'عم' => 'العم', 'خال' => 'الخال', 'ولي أمر' => 'ولي أمر آخر']; @endphp
                                            @foreach($rels as $k => $lbl)
                                                <option value="{{ $k }}" {{ old('guardian_rel', $existingRequest->guardian_rel ?? 'أب') == $k ? 'selected' : '' }}>{{ $lbl }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- هاتف ولي الأمر ومهنة ولي الأمر (اختيارية) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label required" for="gu_phone">رقم هاتف ولي الأمر</label>
                                        <input type="tel" class="form-control apply-input font-monospace" name="gu_phone" id="gu_phone"
                                               value="{{ old('gu_phone', $existingRequest->gu_phone ?? '') }}"
                                               placeholder="77XXXXXXX" required>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label" for="guardian_occup">مهنة ولي الأمر <span class="text-muted fw-normal" style="font-size:0.72rem;">(اختيارية)</span></label>
                                        <input type="text" class="form-control apply-input" name="guardian_occup" id="guardian_occup"
                                               value="{{ old('guardian_occup', $existingRequest->guardian_occup ?? '') }}"
                                               placeholder="مهنة ولي الأمر إن رغبت...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- =================================================
                             الخطوة 3: اختيار التخصص
                             ================================================= --}}
                        <div class="apply-step-pane" id="stepPane_3">
                            <div class="apply-pane-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="apply-badge-step">٣</span>
                                    <h5 class="apply-pane-title mb-0">اختيار الجامعة والتخصص الأكاديمي</h5>
                                </div>
                            </div>

                            <div class="apply-pane-body">
                                <div class="row g-3">
                                    {{-- قائمة اختيار الجامعة --}}
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label required mb-1" for="university_id">
                                            <i class="bi bi-bank text-primary me-1"></i>الجامعة المعتمدة
                                        </label>
                                        <select class="form-select apply-input fw-bold text-primary" name="university_id" id="university_id" required onchange="onUniversitySelectChange()">
                                            <option value="" disabled {{ !isset($selectedUniversityId) ? 'selected' : '' }}>-- اختر الجامعة --</option>
                                            @foreach($universities as $u)
                                                <option value="{{ $u->university_id }}" {{ (isset($selectedUniversityId) && $selectedUniversityId == $u->university_id) ? 'selected' : '' }}>
                                                    {{ $u->university_name }} ({{ $u->university_type }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">اختر الجامعة لعرض التخصصات الأكاديمية المتاحة بها.</small>
                                    </div>

                                    {{-- قائمة منسدلة لاختيار التخصص مصنفة حسب الكليات --}}
                                    <div class="col-md-6 col-12">
                                        <label class="apply-label required mb-1" for="desired_spec_select">
                                            <i class="bi bi-mortarboard-fill text-primary me-1"></i>التخصص الأكاديمي المرغوب
                                        </label>
                                        <select class="form-select apply-input fw-bold text-navy" name="desired_spec" id="desired_spec_select" required onchange="onSpecialtySelectChange(this)">
                                            <option value="" disabled selected>-- اختر التخصص الأكاديمي --</option>
                                        </select>
                                        <small class="text-muted d-block mt-1" id="specSelectHint">قائمة التخصصات المتاحة مصنفة حسب الكليات (اختر التخصص المطلوب).</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- =================================================
                             الخطوة 4: الوثائق والإرسال
                             ================================================= --}}
                        <div class="apply-step-pane" id="stepPane_4">
                            <div class="apply-pane-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="apply-badge-step">٤</span>
                                    <h5 class="apply-pane-title mb-0">الوثائق والإرسال</h5>
                                </div>
                            </div>

                            <div class="apply-pane-body">
                                <div class="row g-2">

                                    {{-- ملخص حالة الطلب المعتمد للمراجعة قبل الإرسال --}}
                                    <div class="col-12 mb-2">
                                        <div class="apply-summary-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                                <span class="fw-bold text-dark small"><i class="bi bi-file-earmark-check-fill text-primary me-1"></i>ملخص حالة الطلب وبيانات التسجيل</span>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">مراجعة قبل الإرسال</span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    <div class="summary-item-box">
                                                        <span class="summary-item-label">اسم الطالب:</span>
                                                        <strong class="summary-item-value text-dark" id="sumStudentName">—</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="summary-item-box">
                                                        <span class="summary-item-label">النسبة:</span>
                                                        <strong class="summary-item-value text-success font-monospace" id="sumPercentage">—</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <div class="summary-item-box">
                                                        <span class="summary-item-label">المدرسة:</span>
                                                        <strong class="summary-item-value text-dark" id="sumSchool">—</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <div class="summary-item-box">
                                                        <span class="summary-item-label">رقم الهاتف:</span>
                                                        <strong class="summary-item-value text-dark font-monospace" id="sumPhone">—</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <div class="summary-item-box">
                                                        <span class="summary-item-label">البريد الإلكتروني:</span>
                                                        <strong class="summary-item-value text-dark" id="sumEmail">—</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <div class="summary-item-box">
                                                        <span class="summary-item-label">الجامعة والتخصص:</span>
                                                        <strong class="summary-item-value text-primary" id="sumUnivSpec">—</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- وثيقة 1: البطاقة الشخصية --}}
                                    <div class="col-md-6 col-12">
                                        <div class="apply-doc-card" id="card_doc_id_card">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="apply-doc-icon"><i class="bi bi-person-vcard"></i></span>
                                                    <div>
                                                        <strong class="apply-doc-name">صورة البطاقة الشخصية</strong>
                                                        <small class="text-muted d-block" style="font-size:0.68rem;">أصل البطاقة أو جواز السفر ساري المفعول</small>
                                                    </div>
                                                </div>
                                                <span class="apply-tag-req">إلزامية</span>
                                            </div>
                                            <div class="apply-doc-pick-row">
                                                <input type="file" name="doc_id_card" id="file_doc_id_card" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="onFilePicked(this, 'card_doc_id_card', 'status_doc_id_card')">
                                                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2.5 apply-btn-pick" onclick="document.getElementById('file_doc_id_card').click();">
                                                    <i class="bi bi-upload me-1"></i>اختيار ملف
                                                </button>
                                                <span class="apply-file-status" id="status_doc_id_card">لم يتم إرفاق ملف</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- وثيقة 2: شهادة الثانوية الأصل --}}
                                    <div class="col-md-6 col-12">
                                        <div class="apply-doc-card" id="card_doc_cert">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="apply-doc-icon"><i class="bi bi-award"></i></span>
                                                    <div>
                                                        <strong class="apply-doc-name">شهادة الثانوية الأصل</strong>
                                                        <small class="text-muted d-block" style="font-size:0.68rem;">صورة واضحة أو ملف PDF كامل</small>
                                                    </div>
                                                </div>
                                                <span class="apply-tag-req">إلزامية</span>
                                            </div>
                                            <div class="apply-doc-pick-row">
                                                <input type="file" name="doc_cert" id="file_doc_cert" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="onFilePicked(this, 'card_doc_cert', 'status_doc_cert')">
                                                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2.5 apply-btn-pick" onclick="document.getElementById('file_doc_cert').click();">
                                                    <i class="bi bi-upload me-1"></i>اختيار ملف
                                                </button>
                                                <span class="apply-file-status" id="status_doc_cert">لم يتم إرفاق ملف</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- وثيقة 3: استمارة الثانوية --}}
                                    <div class="col-md-6 col-12">
                                        <div class="apply-doc-card" id="card_doc_transcript">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="apply-doc-icon"><i class="bi bi-file-earmark-spreadsheet"></i></span>
                                                    <div>
                                                        <strong class="apply-doc-name">صورة استمارة الثانوية</strong>
                                                        <small class="text-muted d-block" style="font-size:0.68rem;">كشف الدرجات والبيانات كاملة وغير مقصوصة</small>
                                                    </div>
                                                </div>
                                                <span class="apply-tag-req">إلزامية</span>
                                            </div>
                                            <div class="apply-doc-pick-row">
                                                <input type="file" name="doc_transcript" id="file_doc_transcript" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="onFilePicked(this, 'card_doc_transcript', 'status_doc_transcript')">
                                                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2.5 apply-btn-pick" onclick="document.getElementById('file_doc_transcript').click();">
                                                    <i class="bi bi-upload me-1"></i>اختيار ملف
                                                </button>
                                                <span class="apply-file-status" id="status_doc_transcript">لم يتم إرفاق ملف</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- وثيقة 4: أخرى (اختيارية) --}}
                                    <div class="col-md-6 col-12">
                                        <div class="apply-doc-card" id="card_doc_other">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="apply-doc-icon"><i class="bi bi-paperclip"></i></span>
                                                    <div>
                                                        <strong class="apply-doc-name">وثيقة أخرى (اختيارية)</strong>
                                                        <small class="text-muted d-block" style="font-size:0.68rem;">فحص طبي، لغة، أو شهادات تميز إضافية</small>
                                                    </div>
                                                </div>
                                                <span class="apply-tag-opt">اختيارية</span>
                                            </div>
                                            <div class="apply-doc-pick-row">
                                                <input type="file" name="doc_other" id="file_doc_other" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="onFilePicked(this, 'card_doc_other', 'status_doc_other')">
                                                <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2.5 apply-btn-pick" onclick="document.getElementById('file_doc_other').click();">
                                                    <i class="bi bi-upload me-1"></i>اختيار ملف
                                                </button>
                                                <span class="apply-file-status" id="status_doc_other">لم يتم إرفاق ملف</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-light border small mt-3 mb-0 text-muted d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-primary"></i>
                                    <span>بعد التأكد من إرفاق وثائقك، انقر على زر "الخطوة التالية" للانتقال إلى شاشة رسوم التنسيق وسند السداد.</span>
                                </div>
                            </div>
                        </div>

                        {{-- =================================================
                             الخطوة 5: رسوم التنسيق وسند السداد
                             ================================================= --}}
                        <div class="apply-step-pane" id="stepPane_5">
                            <div class="apply-pane-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="apply-badge-step">٥</span>
                                    <h5 class="apply-pane-title mb-0">دفع رسوم التسجيل</h5>
                                </div>
                            </div>

                            <div class="apply-pane-body">

                                {{-- الحاوية الواقعة تحت عنوان الخطوة: عرض التخصص وقيمة الرسوم --}}
                                <div class="payment-fee-summary-card mb-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-receipt text-primary fs-4"></i>
                                            <div>
                                                <span class="text-muted d-block small" style="font-size:0.75rem;">التخصص المختار:</span>
                                                <strong class="text-dark" id="feeSpecNameDisplay">تخصص عام</strong>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="text-muted d-block small" style="font-size:0.75rem;">قيمة الرسوم:</span>
                                            <span class="badge bg-success fs-6 font-monospace" id="feeAmountBadge">5,000 ر.ي</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- خيارا السداد فقط بدون أي بيانات بجانبهما --}}
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 col-12">
                                        <label class="payment-choice-card" id="payChoiceNowLabel" onclick="togglePaymentTiming('now')">
                                            <input type="radio" name="payment_timing" id="payTimingNow" value="now">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-credit-card-2-front text-success fs-5"></i>
                                                <div>
                                                    <strong class="d-block text-dark small">سداد الرسوم الآن وإرفاق إثبات</strong>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="payment-choice-card" id="payChoiceLaterLabel" onclick="togglePaymentTiming('later')">
                                            <input type="radio" name="payment_timing" id="payTimingLater" value="later">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-clock-history text-warning fs-5"></i>
                                                <div>
                                                    <strong class="d-block text-dark small">السداد لاحقاً بعد المراجعة</strong>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- في حال اختيار سداد الرسوم الآن: عرض خطوات السداد والحسابات ونموذج السند --}}
                                <div id="payNowContainer" class="d-none">

                                    {{-- عنوان تعليمات السداد فقط بدون ملاحظات فرعية --}}
                                    <div class="mb-3 pb-2 border-bottom">
                                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-check text-primary me-1"></i>خطوات وتعليمات إتمام السداد</h6>
                                    </div>

                                    {{-- التعليمة 1: اختيار الحساب البنكي أو المحفظة --}}
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="step-circle-badge">١</span>
                                            <strong class="text-dark" style="font-size:0.92rem;">اختر الحساب البنكي أو المحفظة المناسبة لك:</strong>
                                        </div>

                                        {{-- كبسولات / أيقونات بيضاوية لطرق الدفع المتاحة لدى الجامعة --}}
                                        <div class="pay-methods-pills-wrap mb-2" id="univPayMethodsContainer">
                                            {{-- تُحقن ديناميكياً بواسطة JavaScript --}}
                                        </div>

                                        {{-- تفاصيل طريقة الدفع عند الضغط عليها --}}
                                        <div id="selectedMethodDetailsBox" class="selected-method-details-card d-none">
                                            <div class="text-muted small mb-2">
                                                <i class="bi bi-info-circle text-primary me-1"></i>انسخ رقم الحساب المعتمد للجامعة أدناه بحسب البنك أو المحفظة المتاحة لديك:
                                            </div>
                                            <div class="account-card-box p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <div>
                                                    <span class="badge bg-light text-primary border mb-1" id="detailMethodName">بنك الكريمي</span>
                                                    <div class="fs-5 fw-bold font-monospace text-primary" id="detailAccountNum" dir="ltr">00000000</div>
                                                    <small class="text-muted d-block" id="detailAccountName">باسم: جامعة ...</small>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-outline-primary btn-sm px-3 fw-bold" id="btnCopyAccount" onclick="copyActiveAccount()">
                                                        <i class="bi bi-clipboard me-1"></i>نسخ رقم الحساب
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- التعليمة 2: التحويل المالي --}}
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="step-circle-badge">٢</span>
                                            <strong class="text-dark" style="font-size:0.92rem;">قم بالتحويل المالي بقيمة رسوم التسجيل (<span class="text-success font-monospace" id="guideFeeAmount">5,000 ر.ي</span>):</strong>
                                        </div>
                                        <small class="text-muted d-block ps-4 pe-1" style="font-size:0.8rem;">
                                            * اكتب اسمك ورقم هاتفك في خانة بيان الحوالة لتسهيل مطابقة السداد.
                                        </small>
                                    </div>

                                    {{-- التعليمة 3: توثيق العملية ورفع السند --}}
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="step-circle-badge">٣</span>
                                            <strong class="text-dark" style="font-size:0.92rem;">وثّق بيانات العملية وارفع سند السداد:</strong>
                                        </div>

                                        {{-- حاوية تسجيل وتوثيق بيانات سند التحويل المالي --}}
                                        <div class="voucher-form-card p-3 shadow-sm">
                                            <div class="row g-2.5">
                                                <div class="col-md-6 col-12">
                                                    <label class="form-label small fw-bold text-dark mb-1">وسيلة / بنك التحويل <span class="text-danger">*</span></label>
                                                    <select name="payment_method_id" id="payment_method_id" class="form-select form-select-sm" onchange="syncSelectedMethodFromDropdown(this.value)">
                                                        <option value="">-- اختر طريقة الدفع --</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <label class="form-label small fw-bold text-dark mb-1">رقم العملية / الحوالة / السند <span class="text-danger">*</span></label>
                                                    <input type="text" name="payment_reference" id="payment_reference" class="form-control form-control-sm font-monospace" placeholder="أدخل رقم العملية أو الإشعار" dir="ltr">
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <label class="form-label small fw-bold text-dark mb-1">المبلغ المسدد (ر.ي) <span class="text-danger">*</span></label>
                                                    <input type="number" name="payment_amount" id="payment_amount" class="form-control form-control-sm font-monospace fw-bold" value="5000">
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <label class="form-label small fw-bold text-dark mb-1">تاريخ التحويل <span class="text-danger">*</span></label>
                                                    <input type="date" name="payment_date" id="payment_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label small fw-bold text-dark mb-1">صورة إشعار أو سند السداد <span class="text-danger">*</span></label>
                                                    <div class="upload-voucher-box p-2.5 d-flex align-items-center justify-content-between flex-wrap gap-2" id="card_payment_proof">
                                                        <input type="file" name="payment_proof" id="file_payment_proof" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="onFilePicked(this, 'card_payment_proof', 'status_payment_proof')">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bi bi-file-earmark-arrow-up text-primary fs-5"></i>
                                                            <span class="small text-muted" id="status_payment_proof">لم يتم اختيار صورة السند</span>
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary btn-sm py-1 px-3" onclick="document.getElementById('file_payment_proof').click();">
                                                            <i class="bi bi-upload me-1"></i>اختيار الملف
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label small text-muted mb-1">ملاحظات إضافية (اختياري)</label>
                                                    <input type="text" name="payment_notes" id="payment_notes" class="form-control form-control-sm" placeholder="اسم المودع أو أي تفاصيل إضافية">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{-- تنبيه عند اختيار السداد لاحقاً --}}
                                <div class="alert alert-light border small text-muted py-2 px-3 mb-3 d-none" id="payLaterNotice">
                                    <i class="bi bi-info-circle text-primary me-1"></i>سيتم إرسال طلبك للمراجعة والتدقيق، ويمكنك سداد الرسوم لاحقاً عبر صفحة طلباتي.
                                </div>

                                {{-- إقرار الطالب والتعهد النهائي --}}
                                <div class="apply-pledge-card mt-3" id="applyPledgeCard">
                                    <label class="d-flex align-items-start gap-2 cursor-pointer mb-0">
                                        <input type="checkbox" id="pledge_check" class="form-check-input mt-1" required>
                                        <span class="small text-dark" style="font-size:0.83rem;">
                                            أقر أنا الطالب بأن كافة البيانات صحيحة ومطابقة لوثائقي، وأتحمل المسؤولية الكاملة عن صحة البيانات والوثائق.
                                        </span>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="apply-wizard-footer d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                            <div>
                                <button type="button" class="btn btn-outline-secondary px-3 py-1.5 fw-bold" id="btnPrevStep" onclick="prevStep()" style="display:none; font-size:0.88rem;">
                                    <i class="bi bi-arrow-right me-1"></i>الخطوة السابقة
                                </button>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                @if(!isset($isPreview) || !$isPreview)
                                    <button type="button" class="btn btn-outline-primary px-3 py-1.5 fw-bold" id="btnSaveDraft" onclick="submitAsDraft()" style="font-size:0.88rem;">
                                        <i class="bi bi-bookmark me-1"></i>حفظ كمسودة
                                    </button>
                                @endif

                                {{-- زر التالي (يظهر في الخطوات 1 و 2 و 3) --}}
                                <button type="button" class="btn btn-primary px-4 py-1.5 fw-bold shadow-sm" id="btnNextStep" onclick="nextStep()" style="font-size:0.9rem; min-width:140px;">
                                    <span>الانتقال للتالي</span>
                                    <i class="bi bi-arrow-left ms-1"></i>
                                </button>

                                {{-- زر الإرسال النهائي (يظهر في الخطوة 4 فقط) --}}
                                @if(isset($isPreview) && $isPreview)
                                    <button type="button" class="btn btn-secondary px-4 py-1.5 fw-bold" id="btnSubmitApp" style="display:none; font-size:0.9rem;" disabled>
                                        <i class="bi bi-eye me-1"></i>وضع المعاينة (الإرسال للطلبة فقط)
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success px-4 py-1.5 fw-bold shadow-sm" id="btnSubmitApp" style="display:none; font-size:0.92rem; min-width:180px;">
                                        <i class="bi bi-send-check-fill me-1"></i>إرسال طلب التسجيل
                                    </button>
                                @endif
                            </div>
                        </div>

                    </form>

                </div>
            </main>

        </div>
    </div>
</div>

{{-- التنسيقات المتطورة والمنسقة للشاشات الكبيرة والهاتف --}}
<style>
/* ضبط عرض الحاوية لتكون متناسقة ومريحة للنظر على كافة الشاشات دون تمدد مفرط */
.apply-stepper-page .container {
    max-width: 960px !important;
    margin: 0 auto;
}

/* شاشة التحميل المعلقة وسط الشاشة */
.screen-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.screen-loading-card {
    background: #ffffff;
    padding: 24px 30px;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
    border: 1px solid #e2e8f0;
    max-width: 380px;
    width: 88%;
    animation: zoomInLoading 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes zoomInLoading {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* خلفية الصفحة العامة */
.apply-stepper-page {
    background-color: #f1f5f9;
    min-height: calc(100vh - 70px);
}

/* شريط الأمان */
.apply-secure-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e8f5e9;
    border: 1px solid #c8e6c9;
    color: #1b5e20;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 3px 12px;
    border-radius: 20px;
}

/* =========================================================
   مؤشر الخطوات للجوال (أعلى الشاشة - خط أفقي متصل)
   ========================================================= */
.apply-mobile-stepper {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.h-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.h-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    cursor: pointer;
}

.h-step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 2px solid #cbd5e1;
    color: #475569;
    font-weight: 800;
    font-size: 0.86rem;
    display: grid;
    place-items: center;
    transition: all 0.2s ease;
}

.h-step.active .h-step-circle {
    background: #003366;
    border-color: #003366;
    color: #ffffff;
    box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.15);
}

.h-step.completed .h-step-circle {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
}

.h-step-line {
    flex: 1;
    height: 2px;
    background: #cbd5e1;
    margin: 0 6px 16px;
    transition: background 0.25s ease;
}

.h-step-line.completed {
    background: #10b981;
}

.h-step-text {
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}

.h-step.active .h-step-text {
    color: #003366;
    font-weight: 800;
}

.h-step.completed .h-step-text {
    color: #0f172a;
}

/* =========================================================
   مؤشر الخطوات للشاشات الكبيرة (جانب الشاشة - خط عمودي متصل)
   ========================================================= */
.apply-steps-sidebar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px 18px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
}

.v-stepper {
    display: flex;
    flex-direction: column;
}

.v-step {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    cursor: pointer;
    position: relative;
}

.v-step-marker {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    align-self: stretch;
}

.v-step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 2px solid #cbd5e1;
    color: #475569;
    display: grid;
    place-items: center;
    font-weight: 800;
    font-size: 0.92rem;
    flex-shrink: 0;
    z-index: 2;
    transition: all 0.2s ease;
}

.v-step.active .v-step-circle {
    background: #003366;
    border-color: #003366;
    color: #ffffff;
    box-shadow: 0 0 0 4px rgba(0, 51, 102, 0.15);
}

.v-step.completed .v-step-circle {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
}

.v-step-line {
    width: 2px;
    flex: 1 1 auto;
    min-height: 38px;
    background: #cbd5e1;
    margin: 4px 0;
    transition: background 0.25s ease;
}

.v-step-line.completed,
.v-step.completed .v-step-line {
    background: #10b981;
}

.v-step-text {
    padding-top: 3px;
    padding-bottom: 14px;
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 3px;
    text-align: right;
    user-select: none;
}

.v-step-title {
    display: block !important;
    font-size: 0.94rem !important;
    font-weight: 800 !important;
    color: #334155;
    line-height: 1.35;
    transition: color 0.2s ease;
}

.v-step.active .v-step-title {
    color: #003366 !important;
    font-weight: 800 !important;
}

.v-step.completed .v-step-title {
    color: #0f172a !important;
}

.v-step-subtitle {
    display: block !important;
    font-size: 0.72rem !important;
    font-weight: 400 !important;
    color: #64748b !important;
    line-height: 1.35 !important;
    margin-top: 2px !important;
    letter-spacing: -0.2px;
}

.v-step.active .v-step-subtitle {
    color: #475569 !important;
    font-weight: 500 !important;
}

/* بطاقة ملخص حالة الطلب المعتمد */
.apply-summary-card {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 14px 16px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
}

.summary-item-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 7px 10px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    height: 100%;
}

.summary-item-label {
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 600;
}

.summary-item-value {
    font-size: 0.85rem;
    color: #0f172a;
    word-break: break-word;
}

/* =========================================================
   تنسيقات البطاقة الرئيسية ونموذج الإدخال
   ========================================================= */
.apply-form-main-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-top: 5px solid #003366;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
    padding: 16px 20px 20px;
}

.apply-step-pane {
    display: none;
    animation: fadeInStep 0.22s ease;
}

.apply-step-pane.active {
    display: block;
}

@keyframes fadeInStep {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.apply-pane-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 2px solid #e2e8f0;
}

.apply-badge-step {
    width: 30px;
    height: 30px;
    background: #003366;
    color: #ffffff;
    border-radius: 8px;
    display: grid;
    place-items: center;
    font-weight: 800;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.apply-pane-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
}

.apply-pane-sub {
    margin: 2px 0 0;
    font-size: 0.74rem;
    color: #64748b;
}

.apply-step-counter {
    background: #f1f5f9;
    color: #475467;
    font-weight: 800;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 12px;
    font-family: monospace;
}

/* الحقول والتسميات */
.apply-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 3px;
    display: block;
}

.apply-label.required::after {
    content: " *";
    color: #dc2626;
    font-weight: bold;
}

.apply-input {
    min-height: 38px;
    height: 38px;
    font-size: 0.83rem;
    padding: 4px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    color: #0f172a;
    transition: all 0.15s ease;
}

.apply-input:focus {
    border-color: #003366;
    box-shadow: 0 0 0 2.5px rgba(0, 51, 102, 0.15);
    outline: none;
}

.apply-radio-opt {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
}

.apply-radio-opt input {
    accent-color: #003366;
    width: 16px;
    height: 16px;
}

.apply-academic-strip {
    background: #fffdf7;
    border: 1px solid #f2e3b6;
    border-radius: 8px;
    padding: 10px 14px;
}

/* صندوق الاستعلام والتحقق من الوزارة */
.apply-ministry-box {
    background: #f0fdf4;
    border: 1.5px solid #86efac;
    border-radius: 8px;
    padding: 12px 14px;
    box-shadow: 0 2px 6px rgba(13, 95, 56, 0.05);
}

.apply-moe-icon {
    width: 32px;
    height: 32px;
    background: #dcfce7;
    color: #15803d;
    border-radius: 50%;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.apply-input-locked {
    background-color: #f8fafc !important;
    border-color: #86efac !important;
    color: #065f46 !important;
    font-weight: 700;
    cursor: not-allowed;
}

/* شريط التخصص في الخطوة 3 */
.apply-univ-pick-toolbar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 12px;
}

.apply-chosen-pill {
    min-height: 38px;
    height: 38px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 4px 12px;
    font-size: 0.86rem;
    color: #003366;
}

.apply-quick-spec-box {
    background: #f0f7ff;
    border: 1px dashed #93c5fd;
    border-radius: 8px;
    padding: 8px 12px;
}

/* =========================================================
   كليات وتخصصات الجامعة كقوائم منسدلة مطوية (Accordion Dropdowns)
   ========================================================= */
.apply-colleges-accordion-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.apply-college-accordion-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    overflow: hidden;
    transition: all 0.2s ease;
}

.apply-college-accordion-card.is-expanded {
    border-color: #93c5fd;
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.06);
}

.apply-college-dropdown-btn {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 12px;
    background: #f8fafc;
    border: none;
    text-align: right;
    cursor: pointer;
    transition: background 0.15s ease;
}

.apply-college-dropdown-btn:hover {
    background: #f1f5f9;
}

.apply-college-accordion-card.is-expanded .apply-college-dropdown-btn {
    background: #eff6ff;
    border-bottom: 1px solid #dbeafe;
}

.apply-college-name-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    font-size: 0.84rem;
    color: #003366;
}

.apply-college-chevron {
    color: #64748b;
    font-size: 0.85rem;
    transition: transform 0.25s ease;
}

.apply-college-accordion-card.is-expanded .apply-college-chevron {
    transform: rotate(180deg);
    color: #003366;
}

.apply-college-specs-dropdown {
    display: none;
    padding: 6px 8px;
    background: #ffffff;
    flex-direction: column;
    gap: 4px;
}

.apply-college-accordion-card.is-expanded .apply-college-specs-dropdown {
    display: flex;
}

.apply-spec-dropdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 10px;
    border-radius: 6px;
    border: 1px solid #f1f5f9;
    cursor: pointer;
    font-size: 0.82rem;
    color: #334155;
    transition: all 0.15s ease;
}

.apply-spec-dropdown-item:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.apply-spec-dropdown-item.selected {
    background: #fef9e7;
    border-color: #d4a72c;
    color: #003366;
    font-weight: 800;
    box-shadow: 0 1px 4px rgba(212, 167, 44, 0.15);
}

.apply-spec-meta-tag {
    font-size: 0.72rem;
    color: #64748b;
    font-family: monospace;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* بطاقات رفع الوثائق */
.apply-docs-notice {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 6px;
    padding: 6px 10px;
    color: #92400e;
    font-size: 0.75rem;
    font-weight: 600;
}

.apply-doc-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 9px 12px;
    background: #ffffff;
    transition: all 0.15s ease;
}

.apply-doc-card.has-file {
    border-color: #10b981;
    background: #f0fdf4;
}

.apply-doc-icon {
    width: 28px;
    height: 28px;
    background: #e0f2fe;
    color: #0284c7;
    border-radius: 6px;
    display: grid;
    place-items: center;
    font-size: 0.95rem;
}

.apply-doc-name {
    font-size: 0.82rem;
    color: #0f172a;
}

.apply-tag-req {
    font-size: 0.65rem;
    font-weight: 800;
    color: #dc2626;
    background: #fef2f2;
    padding: 2px 6px;
    border-radius: 4px;
}

.apply-tag-opt {
    font-size: 0.65rem;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
}

.apply-doc-pick-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 6px;
}

.apply-btn-pick {
    font-size: 0.74rem;
    padding: 3px 8px;
    font-weight: 700;
}

.apply-file-status {
    font-size: 0.72rem;
    color: #64748b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 180px;
}

.apply-doc-card.has-file .apply-file-status {
    color: #059669;
    font-weight: 700;
}

.payment-fee-summary-card {
    background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 12px 16px;
}
.fee-icon-badge {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #10b981;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
.fee-amount-badge {
    display: inline-block;
    background: #065f46;
    color: #fff;
    font-weight: 800;
    font-size: 1.15rem;
    padding: 4px 12px;
    border-radius: 6px;
    font-family: monospace;
}
.univ-pay-methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 10px;
}
.univ-pay-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
}
.univ-pay-card:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(59,130,246,0.1);
}
.payment-choice-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    height: 100%;
}
.payment-choice-card.active {
    background: #f0f9ff;
    border-color: #0284c7;
    box-shadow: 0 0 0 2px rgba(2,132,199,0.15);
}
.payment-choice-card input[type="radio"] {
    accent-color: #0284c7;
    width: 17px;
    height: 17px;
}
.payment-choice-card .choice-title {
    font-size: 0.86rem;
    font-weight: 700;
    color: #0f172a;
}
.payment-choice-card .choice-desc {
    display: block;
    font-size: 0.72rem;
    color: #64748b;
}
.payment-proof-fields-box {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.pay-steps-guide-card {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 14px 16px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.guide-icon-badge {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #eff6ff;
    color: #2563eb;
    display: grid;
    place-items: center;
    font-size: 20px;
    flex-shrink: 0;
}
.pay-steps-timeline {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 14px;
    position: relative;
}
.pay-timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.timeline-step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    display: grid;
    place-items: center;
    font-weight: 800;
    font-size: 0.85rem;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(37,99,235,0.25);
}
.timeline-step-content {
    flex: 1;
}
.timeline-step-content strong {
    font-size: 0.88rem;
    color: #0f172a;
    margin-bottom: 2px;
}
.pulse-focus {
    animation: pulseBorder 1.5s ease-out;
}
@keyframes pulseBorder {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.6);
        border-color: #2563eb;
    }
    70% {
        box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
        border-color: #2563eb;
    }
    100% {
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.25s ease-out;
}

.apply-pledge-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 12px;
}

/* التجاوب المميز على شاشات الهواتف المحمولة */
@media (max-width: 768px) {
    .apply-form-main-card {
        padding: 12px 14px 16px;
        border-radius: 10px;
    }
    .apply-pane-title {
        font-size: 0.96rem;
    }
    .apply-input {
        min-height: 40px;
        height: 40px;
        font-size: 0.86rem;
    }
    .apply-label {
        font-size: 0.8rem;
    }
    .apply-wizard-footer {
        flex-direction: column-reverse;
        align-items: stretch !important;
        gap: 8px;
    }
    .apply-wizard-footer > div {
        display: flex;
        width: 100%;
    }
    .apply-wizard-footer .btn {
        flex: 1;
        text-align: center;
        justify-content: center;
    }
    .apply-spec-dropdown-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }
}


/* ========================================================
   الخط الرسمي وتنسيقات الخطوة 5 (دفع الرسوم)
   ======================================================== */
#stepPane_5,
#stepPane_5 *:not(i):not(.bi) {
    font-family: 'Segoe UI', Tahoma, Arial, sans-serif !important;
}

#stepPane_5 .font-monospace {
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
}

/* شارة الرسوم */
.payment-fee-summary-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
}

/* خيارا السداد */
.payment-choice-card {
    display: block;
    padding: 11px 14px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.payment-choice-card:hover {
    border-color: #2563eb;
    background: #f0f7ff;
}

.payment-choice-card.active {
    background: #eff6ff;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
}

.payment-choice-card input[type="radio"] {
    display: none;
}

/* كبسولات طرق الدفع البيضاوية */
.pay-methods-pills-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.pay-method-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 15px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 50px;
    color: #1e293b;
    font-size: 0.83rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
}

.pay-method-pill:hover {
    border-color: #2563eb;
    background: #f0f7ff;
    color: #1d4ed8;
}

.pay-method-pill.active {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(37,99,235,0.25);
}

.pay-method-pill-icon {
    font-size: 0.85rem;
}

/* بطاقة تفاصيل الحساب المعتمد عند الضغط على الكبسولة */
.selected-method-details-card {
    background: #f8fafc;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: 12px 14px;
    margin-top: 8px;
}

.account-card-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

/* دائرة ترقيم الخطوة */
.step-circle-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #2563eb;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    flex-shrink: 0;
}

/* حاوية التوثيق والسند */
.voucher-form-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
}

.upload-voucher-box {
    border: 1.5px dashed #cbd5e1;
    border-radius: 6px;
    background: #f8fafc;
    transition: border-color 0.2s;
}

.upload-voucher-box:hover {
    border-color: #2563eb;
}

</style>

{{-- برمجة الخطوات واحتساب المجموع والقوائم المنسدلة والتنقل --}}
<script>
// كائن بيانات الجامعات المعتمدة
window.universitiesDatabase = @json($universities);

let currentStep = 1;
const totalSteps = 5;
let currentUnivId = "{{ $selectedUniversityId ?? '' }}";
let currentDesiredSpec = "{{ $selectedMajor ?? '' }}";

const stepLabels = {
    1: 'بيانات الثانوية',
    2: 'بيانات الطالب',
    3: 'التخصص المرغوب',
    4: 'الوثائق والمستندات',
    5: 'رسوم وسند السداد'
};

document.addEventListener('DOMContentLoaded', function() {
    setupEnterKeyNavigation();
    initUniversitySelection();
    autoCalculateScore();
    updateStepUI();
    updateProgressState();
    updateApplicationSummary();

    // ربط الحقول للتحديث المباشر للملخص
    document.querySelectorAll('#full_name, #sec_avg, #secondary_school, #phone, #email, #university_id, #desired_spec_select').forEach(el => {
        el.addEventListener('input', updateApplicationSummary);
        el.addEventListener('change', updateApplicationSummary);
    });
});

// ميزة التنقل السلس عند الضغط على مفتاح Enter
function setupEnterKeyNavigation() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const target = e.target;
            if (target.tagName === 'TEXTAREA' || target.type === 'submit' || target.type === 'button') {
                return;
            }

            e.preventDefault(); // منع الإرسال التلقائي

            const currentPane = document.getElementById(`stepPane_${currentStep}`);
            if (!currentPane) return;

            const focusable = Array.from(currentPane.querySelectorAll(
                'input:not([type="hidden"]):not([type="file"]):not([disabled]), select:not([disabled]), textarea:not([disabled])'
            )).filter(el => el.offsetParent !== null);

            const currentIndex = focusable.indexOf(target);
            if (currentIndex > -1 && currentIndex + 1 < focusable.length) {
                const nextField = focusable[currentIndex + 1];
                nextField.focus();
                if (nextField.select && typeof nextField.select === 'function') {
                    nextField.select();
                }
            } else if (currentIndex === focusable.length - 1 && currentStep < totalSteps) {
                // إذا وصل لنهاية الخطوة ينتقل للخطوة التالية
                nextStep();
            }
        }
    });
}

// الانتقال المباشر لخطوة معينة
function jumpToStep(targetStep) {
    if (targetStep < currentStep) {
        currentStep = targetStep;
        updateStepUI();
    } else if (targetStep > currentStep) {
        if (validateStep(currentStep)) {
            currentStep = targetStep;
            updateStepUI();
        }
    }
}

// الخطوة التالية
function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepUI();
            window.scrollTo({ top: document.querySelector('.apply-form-main-card').offsetTop - 25, behavior: 'smooth' });
        }
    }
}

// الخطوة السابقة
function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepUI();
        window.scrollTo({ top: document.querySelector('.apply-form-main-card').offsetTop - 25, behavior: 'smooth' });
    }
}

// الاستعلام والتحقق التلقائي من نتيجة الثانوية عبر خادم الوزارة
async function queryMinistryData() {
    const seatInput = document.getElementById('seat_number');
    const yearSelect = document.getElementById('grad_year');
    const secSelect = document.getElementById('sec_section');
    const btn = document.getElementById('btnVerifyMinistry');
    const alertBox = document.getElementById('ministryLookupAlert');
    const badgeVerified = document.getElementById('badgeMinistryVerified');
    const overlay = document.getElementById('screenLoadingOverlay');

    if (!seatInput || !seatInput.value.trim()) {
        alert('يرجى إدخال رقم الجلوس أولاً للاستعلام.');
        seatInput.focus();
        return;
    }

    const seat = seatInput.value.trim();
    const year = yearSelect ? yearSelect.value : 2024;
    const sec = secSelect ? secSelect.value : 'علمي';

    const originalBtnHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الفحص...';
    alertBox.style.display = 'none';

    // إظهار شاشة التحميل المعلقة وسط الشاشة
    if (overlay) overlay.classList.remove('d-none');

    try {
        const url = `{{ route('api.verify.seat') }}?seat_number=${encodeURIComponent(seat)}&grad_year=${encodeURIComponent(year)}&sec_section=${encodeURIComponent(sec)}`;
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        const res = await response.json();

        if (response.ok && res.success && res.data) {
            const d = res.data;

            // 1. تعبئة الاسم الكامل وتثبيته كحقل مقفل غير قابل للتعديل اليدوي
            const nameInput = document.getElementById('full_name');
            if (nameInput) {
                nameInput.value = d.student_name;
                nameInput.readOnly = true;
                nameInput.classList.add('apply-input-locked');
            }

            // 2. تعبئة المدرسة وتثبيتها كحقل مقفل
            const schoolInput = document.getElementById('secondary_school');
            if (schoolInput) {
                schoolInput.value = d.school_name;
                schoolInput.readOnly = true;
                schoolInput.classList.add('apply-input-locked');
            }

            // 3. تعبئة المعدل والمجموع وتثبيتهما كحقلين مقفلين
            const avgInput = document.getElementById('sec_avg');
            if (avgInput) {
                avgInput.value = d.percentage;
                avgInput.readOnly = true;
                avgInput.classList.add('apply-input-locked');
            }

            const totalInput = document.getElementById('total_score');
            if (totalInput) {
                totalInput.value = d.total_score;
            }

            // 4. تحديد الجنس
            if (d.gender === 'أنثى') {
                const femaleRadio = document.getElementById('gender_female');
                if (femaleRadio) femaleRadio.checked = true;
            } else {
                const maleRadio = document.getElementById('gender_male');
                if (maleRadio) maleRadio.checked = true;
            }

            // 5. تاريخ الميلاد ومكان الميلاد المعتمدان من سجلات الوزارة
            const bdateInput = document.getElementById('birth_date');
            if (bdateInput && d.birth_date) {
                bdateInput.value = d.birth_date;
                bdateInput.readOnly = true;
                bdateInput.classList.add('apply-input-locked');
            }

            const birthPlaceInput = document.getElementById('birth_place');
            if (birthPlaceInput) {
                birthPlaceInput.value = d.birth_place || `${d.governorate} / ${d.district}`;
                birthPlaceInput.readOnly = true;
                birthPlaceInput.classList.add('apply-input-locked');
            }

            // تحديث بطاقة المجموع والملخص
            autoCalculateScore();
            if (typeof updateApplicationSummary === 'function') {
                updateApplicationSummary();
            }

            // إظهار شارة المصادقة الرسمية
            if (badgeVerified) badgeVerified.style.display = 'inline-block';

            // رسالة التنبيه الرسمية المعتمدة
            alertBox.className = 'alert alert-success py-2 px-3 small mt-2 mb-0 d-flex align-items-center justify-content-between border-success';
            alertBox.innerHTML = `
                <div>
                    <i class="bi bi-patch-check-fill text-success me-1"></i>
                    <strong>تمت المصادقة الوزارية بنجاح:</strong> الاسم [${d.student_name}] — المدرسة [${d.school_name}] — النسبة [${d.percentage}%] (${d.appreciation}).
                </div>
            `;
            alertBox.style.display = 'block';

        } else {
            if (badgeVerified) badgeVerified.style.display = 'none';
            alertBox.className = 'alert alert-danger py-2 px-3 small mt-2 mb-0 border-danger';
            alertBox.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i>${res.message || 'رقم الجلوس غير مسجل في قاعدة بيانات الوزارة لهذه السنة.'}`;
            alertBox.style.display = 'block';
        }
    } catch (err) {
        alertBox.className = 'alert alert-warning py-2 px-3 small mt-2 mb-0 border-warning';
        alertBox.innerHTML = '<i class="bi bi-exclamation-circle-fill me-1"></i>تعذر الاتصال بخادم الاستعلام الحكومي حالياً.';
        alertBox.style.display = 'block';
    } finally {
        if (overlay) overlay.classList.add('d-none');
        btn.disabled = false;
        btn.innerHTML = originalBtnHtml;
    }
}

// احتساب المجموع العام تلقائياً من المعدل (المعدل × 8)
function autoCalculateScore() {
    const avgInput = document.getElementById('sec_avg');
    const totalInput = document.getElementById('total_score');
    const badge = document.getElementById('calculatedScoreBadge');
    if (!avgInput) return;

    const avg = parseFloat(avgInput.value);
    if (!isNaN(avg) && avg > 0) {
        const calculated = Math.round(avg * 8);
        if (totalInput) totalInput.value = calculated;
        if (badge) badge.textContent = `${calculated} درجة`;
    } else {
        if (badge) badge.textContent = '—';
    }
}

// التحقق من صحة حقول الخطوة الحالية
function validateStep(step) {
    const pane = document.getElementById(`stepPane_${step}`);
    if (!pane) return true;

    // التحقق من اختيار التخصص في الخطوة 3
    if (step === 3) {
        const univ = document.getElementById('university_id');
        if (!univ || !univ.value) {
            alert('يرجى اختيار الجامعة المعتمدة أولاً.');
            univ.focus();
            return false;
        }
        const specSelect = document.getElementById('desired_spec_select');
        const specVal = specSelect ? specSelect.value : currentDesiredSpec;
        if (!specVal) {
            alert('يرجى اختيار التخصص الأكاديمي المرغوب به من القائمة.');
            if (specSelect) specSelect.focus();
            return false;
        }
        currentDesiredSpec = specVal;
        return true;
    }

    // التحقق من الخطوة 5 (رسوم وسند السداد والإقرار)
    if (step === 5) {
        const payTiming = document.querySelector('input[name="payment_timing"]:checked')?.value;
        if (!payTiming) {
            alert('يرجى تحديد طريقة إتمام السداد: سداد الرسوم الآن أو السداد لاحقاً.');
            const cardNow = document.getElementById('payChoiceNowLabel');
            if (cardNow) cardNow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        if (payTiming === 'now') {
            const method = document.getElementById('payment_method_id');
            const ref = document.getElementById('payment_reference');
            const proof = document.getElementById('file_payment_proof');

            if (method && !method.value) {
                alert('يرجى تحديد وسيلة أو بنك التحويل المالي المستخدم.');
                method.focus();
                return false;
            }
            if (ref && !ref.value.trim()) {
                alert('يرجى إدخال رقم إشعار أو حوالة أو سند السداد.');
                ref.focus();
                return false;
            }
            if (proof && !proof.files.length) {
                alert('يرجى إرفاق صورة أو ملف سند السداد (أو اختر "السداد لاحقاً بعد المراجعة").');
                return false;
            }
        }

        const pledge = document.getElementById('pledge_check');
        if (pledge && !pledge.checked) {
            alert('يجب الموافقة على التعهد والإقرار بصحة البيانات قبل إرسال الطلب.');
            pledge.focus();
            return false;
        }

        return true;
    }

    const requiredFields = Array.from(pane.querySelectorAll('input[required], select[required]'));
    for (const field of requiredFields) {
        if (field.type === 'radio') {
            const checked = pane.querySelector(`input[name="${field.name}"]:checked`);
            if (!checked) {
                alert('يرجى تحديد الخيار المطلوب للمتابعة.');
                field.focus();
                return false;
            }
        } else if (!field.value || field.value.trim() === '') {
            field.classList.add('is-invalid');
            alert('يرجى استكمال الحقل المطلوب قبل الانتقال للخطوة التالية.');
            field.focus();
            return false;
        } else {
            field.classList.remove('is-invalid');
        }
    }

    return true;
}

// تحديث واجهة الخطوات على الكمبيوتر والجوال
function updateStepUI() {
    // تبديل محتوى الخطوات
    for (let i = 1; i <= totalSteps; i++) {
        const pane = document.getElementById(`stepPane_${i}`);
        const indicator = document.getElementById(`stepIndicator_${i}`);
        const circle = document.getElementById(`stepCircle_${i}`);

        if (pane) {
            pane.classList.toggle('active', i === currentStep);
        }

        if (indicator) {
            indicator.classList.toggle('active', i === currentStep);
            indicator.classList.toggle('completed', i < currentStep);
        }

        if (circle) {
            const numText = circle.querySelector('.step-num-text');
            const checkIcon = circle.querySelector('.step-check-icon');
            if (i < currentStep) {
                if (numText) numText.classList.add('d-none');
                if (checkIcon) checkIcon.classList.remove('d-none');
            } else {
                if (numText) numText.classList.remove('d-none');
                if (checkIcon) checkIcon.classList.add('d-none');
            }
        }

        // الخط الرأسي المتصل بالشريط الجانبي (Desktop)
        const vLine = document.getElementById(`vLine_${i}`);
        if (vLine) {
            vLine.classList.toggle('completed', i < currentStep);
        }

        // نقاط وخطوات مؤشر الجوال (Mobile)
        const dot = document.getElementById(`mobileDot_${i}`);
        if (dot) {
            dot.classList.toggle('active', i === currentStep);
            dot.classList.toggle('completed', i < currentStep);
            const dotCircle = dot.querySelector('.h-step-circle');
            if (dotCircle) {
                if (i < currentStep) {
                    dotCircle.innerHTML = '<i class="bi bi-check-lg" style="font-size:0.75rem;"></i>';
                } else {
                    const arabicNums = ['١', '٢', '٣', '٤', '٥'];
                    dotCircle.textContent = arabicNums[i - 1];
                }
            }
        }

        const line = document.getElementById(`mobileLine_${i}`);
        if (line) {
            line.classList.toggle('completed', i < currentStep);
        }
    }

    // نصوص مؤشر الجوال
    const mNum = document.getElementById('mobileStepNum');
    const mLabel = document.getElementById('mobileStepLabel');
    if (mNum) mNum.textContent = currentStep;
    if (mLabel) mLabel.textContent = stepLabels[currentStep] || '';

    // أزرار التنقل
    const btnPrev = document.getElementById('btnPrevStep');
    const btnNext = document.getElementById('btnNextStep');
    const btnSubmit = document.getElementById('btnSubmitApp');

    if (btnPrev) btnPrev.style.display = currentStep > 1 ? 'inline-flex' : 'none';
    if (btnNext) btnNext.style.display = currentStep < totalSteps ? 'inline-flex' : 'none';
    if (btnSubmit) btnSubmit.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';

    // التركيز على أول حقل بالخطوة
    const activePane = document.getElementById(`stepPane_${currentStep}`);
    if (activePane) {
        const firstInput = activePane.querySelector('input:not([type="hidden"]):not([type="radio"]), select');
        if (firstInput) setTimeout(() => firstInput.focus(), 150);
    }

    updateProgressState();
    updateApplicationSummary();
}

// تحديث بطاقة ملخص حالة الطلب المعتمد
function updateApplicationSummary() {
    const fullName = document.getElementById('full_name')?.value || '—';
    const secAvg = document.getElementById('sec_avg')?.value || '—';
    const school = document.getElementById('secondary_school')?.value || '—';
    const phone = document.getElementById('phone')?.value || '—';
    const email = document.getElementById('email')?.value || 'غير محدد';

    const univSelect = document.getElementById('university_id');
    let univText = '';
    if (univSelect && univSelect.selectedIndex > 0) {
        univText = univSelect.options[univSelect.selectedIndex].text.split('(')[0].trim();
    }
    const specSelect = document.getElementById('desired_spec_select');
    const specText = (specSelect?.value || currentDesiredSpec || '').trim();

    let univSpecDisplay = '—';
    if (univText && specText) {
        univSpecDisplay = `${univText} / ${specText}`;
    } else if (univText) {
        univSpecDisplay = univText;
    } else if (specText) {
        univSpecDisplay = specText;
    }

    const elName = document.getElementById('sumStudentName');
    if (elName) elName.textContent = fullName;

    const elAvg = document.getElementById('sumPercentage');
    if (elAvg) elAvg.textContent = (secAvg && secAvg !== '—') ? `${secAvg}%` : '—';

    const elSchool = document.getElementById('sumSchool');
    if (elSchool) elSchool.textContent = school;

    const elPhone = document.getElementById('sumPhone');
    if (elPhone) elPhone.textContent = phone;

    const elEmail = document.getElementById('sumEmail');
    if (elEmail) elEmail.textContent = email;

    const elUnivSpec = document.getElementById('sumUnivSpec');
    if (elUnivSpec) elUnivSpec.textContent = univSpecDisplay;
}

// تحديث مؤشر التقدم
function updateProgressState() {
    const percent = Math.round((currentStep / totalSteps) * 100);
    const bar = document.getElementById('sideProgressBar');
    const text = document.getElementById('sideProgressPercent');
    const mBar = document.getElementById('mobileProgressBar');
    const mText = document.getElementById('mobileProgressPercent');

    if (bar) bar.style.width = percent + '%';
    if (text) text.textContent = percent + '%';
    if (mBar) mBar.style.width = percent + '%';
    if (mText) mText.textContent = percent + '%';
}

// تهيئة اختيار الجامعة وعرض كلياتها
function initUniversitySelection() {
    const univSelect = document.getElementById('university_id');
    if (univSelect && univSelect.value) {
        currentUnivId = univSelect.value;
        renderUniversitySpecialties(currentUnivId, currentDesiredSpec);
        renderUniversityPaymentMethods(currentUnivId);
        updateHeaderAndSideUniv();
        updateFeeDetails();
    }
}

// عند تغيير الجامعة
function onUniversitySelectChange() {
    const univSelect = document.getElementById('university_id');
    if (!univSelect) return;

    currentUnivId = univSelect.value;
    updateHeaderAndSideUniv();
    renderUniversitySpecialties(currentUnivId, '');
    renderUniversityPaymentMethods(currentUnivId);
    updateFeeDetails();
    updateApplicationSummary();
}

// تحديث اسم الجامعة في الشريط الجانبي
function updateHeaderAndSideUniv() {
    const univSelect = document.getElementById('university_id');
    const sideEl = document.getElementById('sideUnivName');

    if (univSelect && univSelect.selectedIndex >= 0) {
        const option = univSelect.options[univSelect.selectedIndex];
        if (option && option.value) {
            const cleanName = option.text.split('(')[0].trim();
            if (sideEl) sideEl.textContent = cleanName;
            return;
        }
    }
    if (sideEl) sideEl.textContent = 'تحدد في الخطوة 3';
}

// توليد قائمة التخصصات المنسدلة مصنفة حسب الكليات (الأسماء فقط)
function renderUniversitySpecialties(univId, preferredSpecName) {
    const specSelect = document.getElementById('desired_spec_select');
    const hintEl = document.getElementById('specSelectHint');
    if (!specSelect) return;

    specSelect.innerHTML = '';

    if (!univId) {
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.disabled = true;
        defaultOpt.selected = true;
        defaultOpt.textContent = '-- يرجى اختيار الجامعة أولاً --';
        specSelect.appendChild(defaultOpt);
        if (hintEl) hintEl.textContent = 'يرجى اختيار الجامعة أولاً لعرض التخصصات المتاحة بها.';
        currentDesiredSpec = '';
        const sideSpecEl = document.getElementById('sideSpecName');
        if (sideSpecEl) sideSpecEl.textContent = 'حدد التخصص في الخطوة 3';
        return;
    }

    const univ = window.universitiesDatabase.find(u => String(u.university_id) === String(univId));
    if (!univ || !univ.colleges || univ.colleges.length === 0) {
        const emptyOpt = document.createElement('option');
        emptyOpt.value = '';
        emptyOpt.disabled = true;
        emptyOpt.selected = true;
        emptyOpt.textContent = '-- لا توجد كليات أو تخصصات مسجلة لهذه الجامعة --';
        specSelect.appendChild(emptyOpt);
        if (hintEl) hintEl.textContent = 'لا توجد كليات أو تخصصات مسجلة حالياً لهذه الجامعة.';
        currentDesiredSpec = '';
        const sideSpecEl = document.getElementById('sideSpecName');
        if (sideSpecEl) sideSpecEl.textContent = 'حدد التخصص في الخطوة 3';
        return;
    }

    // خيار افتراضي محايد
    const placeholderOpt = document.createElement('option');
    placeholderOpt.value = '';
    placeholderOpt.disabled = true;
    placeholderOpt.textContent = '-- اختر التخصص الأكاديمي --';
    specSelect.appendChild(placeholderOpt);

    let totalSpecCount = 0;
    let foundPreferred = false;

    univ.colleges.forEach(college => {
        if (!college.specializations || college.specializations.length === 0) return;

        const optgroup = document.createElement('optgroup');
        optgroup.label = college.college_name;

        college.specializations.forEach(sp => {
            totalSpecCount++;
            const opt = document.createElement('option');
            opt.value = sp.specialization_name;
            // الأسماء فقط بدون أي رسوم أو سنوات
            opt.textContent = sp.specialization_name;

            // إذا كان التخصص محدداً مسبقاً بشكل صريح ومتطابق
            if (preferredSpecName && (sp.specialization_name.trim() === preferredSpecName.trim())) {
                opt.selected = true;
                foundPreferred = true;
                currentDesiredSpec = sp.specialization_name;
            }

            optgroup.appendChild(opt);
        });

        specSelect.appendChild(optgroup);
    });

    if (!foundPreferred) {
        placeholderOpt.selected = true;
        currentDesiredSpec = '';
        const sideSpecEl = document.getElementById('sideSpecName');
        if (sideSpecEl) sideSpecEl.textContent = 'حدد التخصص في الخطوة 3';
    } else {
        const sideSpecEl = document.getElementById('sideSpecName');
        if (sideSpecEl) sideSpecEl.textContent = currentDesiredSpec;
    }

    if (hintEl) {
        hintEl.textContent = `متاح (${totalSpecCount}) تخصص في كليات الجامعة. اختر التخصص المطلوب.`;
    }

    updateFeeDetails();
    updateApplicationSummary();
}

// دالة بديلة للتوافق الكامل
function renderUniversityColleges(univId, preferredSpecName) {
    renderUniversitySpecialties(univId, preferredSpecName);
}

// عند اختيار التخصص من القائمة المنسدلة
function onSpecialtySelectChange(selectEl) {
    const val = selectEl ? selectEl.value : '';
    setChosenSpecialization(val);
}

// ضبط التخصص المختار وتحديث الواجهات التابعة
function setChosenSpecialization(name) {
    currentDesiredSpec = name || '';
    const selectEl = document.getElementById('desired_spec_select');
    if (selectEl && name && selectEl.value !== name) {
        selectEl.value = name;
    }
    const sideSpecEl = document.getElementById('sideSpecName');
    if (sideSpecEl) sideSpecEl.textContent = name || 'حدد التخصص في الخطوة 3';
    updateFeeDetails();
    updateApplicationSummary();
}

// معالجة اختيار ملف للوثائق
function onFilePicked(input, cardId, statusId) {
    const card = document.getElementById(cardId);
    const status = document.getElementById(statusId);

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);

        if (file.size > 5 * 1024 * 1024) {
            alert('حجم الملف كبير جداً! الحد الأقصى المسموح به هو 5 ميجابايت.');
            input.value = '';
            if (card) card.classList.remove('has-file');
            if (status) status.innerHTML = '<span class="text-danger">تجاوز 5MB</span>';
            return;
        }

        if (card) card.classList.add('has-file');
        if (status) status.innerHTML = `<span class="text-success fw-bold">✓ ${file.name}</span> <small class="text-muted">(${sizeMb}MB)</small>`;
    } else {
        if (card) card.classList.remove('has-file');
        if (status) status.textContent = 'لم يتم إرفاق ملف';
    }
}

// حفظ كمسودة
function submitAsDraft() {
    const draftInput = document.getElementById('applyIsDraft');
    if (draftInput) draftInput.value = '1';

    const univ = document.getElementById('university_id');
    if (!univ || !univ.value) {
        alert('يرجى اختيار الجامعة أولاً في الخطوة 3 لحفظ المسودة.');
        jumpToStep(3);
        return;
    }

    const form = document.getElementById('registrationForm');
    if (form) {
        const event = new Event('submit', { cancelable: true });
        form.dispatchEvent(event);
    }
}

// إرسال النموذج وتوثيقه في قاعدة البيانات
function handleApplySubmit(e) {
    e.preventDefault();

    const isDraft = document.getElementById('applyIsDraft').value === '1';

    // التحقق عند الإرسال الفعلي
    if (!isDraft) {
        if (!validateStep(5)) {
            jumpToStep(5);
            return;
        }

        const specSelect = document.getElementById('desired_spec_select');
        if (specSelect && specSelect.value) {
            currentDesiredSpec = specSelect.value;
        }
        if (!currentDesiredSpec) {
            alert('يرجى اختيار التخصص المرغوب به من الخطوة الثالثة قبل إرسال الطلب.');
            jumpToStep(3);
            return;
        }

        const doc1 = document.getElementById('file_doc_id_card');
        const doc2 = document.getElementById('file_doc_cert');
        const doc3 = document.getElementById('file_doc_transcript');
        if (!doc1.files.length || !doc2.files.length || !doc3.files.length) {
            const continueWithoutDocs = confirm('تنبيه: لم تقم بإرفاق جميع الوثائق الإلزامية الثلاث (صورة البطاقة، شهادة الثانوية الأصل، صورة الاستمارة). هل تود المتابعة بالإرسال كطلب يتطلب استكمال وثائق؟');
            if (!continueWithoutDocs) {
                jumpToStep(4);
                return;
            }
        }
    }

    const submitBtn = document.getElementById('btnSubmitApp');
    const draftBtn = document.getElementById('btnSaveDraft');
    const originalSubmitText = submitBtn ? submitBtn.innerHTML : '';
    const originalDraftText = draftBtn ? draftBtn.innerHTML : '';

    if (isDraft && draftBtn) {
        draftBtn.disabled = true;
        draftBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الحفظ...';
    } else if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ إرسال وتوثيق الطلب...';
    }

    const form = document.getElementById('registrationForm');
    const formData = new FormData(form);

    fetch('{{ route("student.apply.submit") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || 'تعذر إرسال الطلب، يرجى مراجعة البيانات.');
        }
        return data;
    })
    .then(data => {
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: isDraft ? 'تم حفظ المسودة في حسابك!' : 'تم تقديم طلبك بنجاح!',
                html: isDraft ? `
                    <p class="mb-2 text-dark font-medium">${data.message || 'تم حفظ بيانات الطلب كمسودة في حسابك الشخصي بنجاح.'}</p>
                    <div class="p-2 border rounded bg-light font-monospace text-primary fw-bold mb-2">رقم المسودة: #${data.req_id}</div>
                    <small class="text-muted d-block"><i class="bi bi-shield-check text-success me-1"></i><strong>ملاحظة:</strong> هذه مسودة خاصة بك فقط ولم يتم إرسالها إلى الجامعة بعد. يمكنك مراجعتها واستكمالها وإرسالها رسمياً متى أردت من لوحة تحكمك.</small>
                ` : `
                    <p class="mb-2 text-dark font-medium">${data.message || 'تم تقديم طلب الالتحاق بالجامعة بنجاح.'}</p>
                    <div class="p-2 border rounded bg-light font-monospace text-primary fw-bold mb-2">رقم الطلب: #${data.req_id}</div>
                    <small class="text-muted d-block"><i class="bi bi-send-check text-primary me-1"></i>تم توجيه طلبك مباشرة إلى إدارة القبول والتسجيل بالجامعة، ويمكنك متابعة حالته في أي وقت من لوحة تحكمك.</small>
                `,
                confirmButtonText: 'الانتقال إلى طلباتي',
                confirmButtonColor: '#003366'
            }).then(() => {
                window.location.href = "{{ route('student.dashboard', ['tab' => 'requests']) }}";
            });
        } else {
            alert(isDraft ? 'تم حفظ المسودة في حسابك فقط ولم تُرسل للجامعة بعد.' : (data.message || 'تم تقديم طلبك بنجاح!'));
            window.location.href = "{{ route('student.dashboard', ['tab' => 'requests']) }}";
        }
    })
    .catch(error => {
        alert(error.message || 'حدث خطأ أثناء معالجة الطلب. يرجى التحقق من الحقول الإلزامية.');
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalSubmitText;
        }
        if (draftBtn) {
            draftBtn.disabled = false;
            draftBtn.innerHTML = originalDraftText;
        }
        document.getElementById('applyIsDraft').value = '0';
    });
}

// توليد وسائل الدفع المعتمدة لدى الجامعة
let currentUnivMethods = [];
let currentActiveAccountNum = '';

function renderUniversityPaymentMethods(univId) {
    const container = document.getElementById('univPayMethodsContainer');
    const selectEl = document.getElementById('payment_method_id');
    const detailsBox = document.getElementById('selectedMethodDetailsBox');

    if (!container) return;
    container.innerHTML = '';
    if (detailsBox) detailsBox.classList.add('d-none');
    currentUnivMethods = [];
    currentActiveAccountNum = '';

    if (selectEl) {
        selectEl.innerHTML = '<option value="">-- اختر طريقة الدفع --</option>';
    }

    // تحديد الجامعة المختارة
    const uId = univId || currentUnivId || document.getElementById('university_id')?.value;
    let methods = [];

    if (uId && window.universitiesDatabase) {
        const univ = window.universitiesDatabase.find(u => String(u.university_id) === String(uId));
        if (univ && univ.payment_methods && univ.payment_methods.length > 0) {
            methods = univ.payment_methods.filter(m => m.account_number && m.account_number.trim() !== '');
        }
    }

    // في حال كانت الجامعة ليس لديها حسابات مدخلة بعد، توفير الحسابات الافتراضية المعتمدة
    if (methods.length === 0) {
        const univName = document.getElementById('university_id')?.options[document.getElementById('university_id')?.selectedIndex]?.text?.split('(')[0]?.trim() || 'الجامعة';
        methods = [
            { method_id: 'default_1', method_name: 'بنك الكريمي', account_number: '300124589', method_type: 'حساب بنكي', account_name: 'حساب تحصيل - ' + univName },
            { method_id: 'default_2', method_name: 'محفظة ون كاش', account_number: '777000111', method_type: 'محفظة إلكترونية', account_name: 'حساب رسوم - ' + univName },
            { method_id: 'default_3', method_name: 'بنك التضامن', account_number: '102938475', method_type: 'حساب بنكي', account_name: 'إدارة القبول والتسجيل - ' + univName },
            { method_id: 'default_4', method_name: 'محفظة جيب', account_number: '711223344', method_type: 'محفظة إلكترونية', account_name: 'رسوم التنسيق - ' + univName }
        ];
    }

    currentUnivMethods = methods;

    // بناء الكبسولات البيضاوية لجميع الطرق
    currentUnivMethods.forEach((pm, idx) => {
        const displayName = (pm.method_name && pm.method_name.trim()) ? pm.method_name : (pm.method_type || 'حساب بنكي');
        
        if (selectEl) {
            const opt = document.createElement('option');
            opt.value = pm.method_id;
            opt.textContent = displayName + ' (' + pm.account_number + ')';
            selectEl.appendChild(opt);
        }

        let iconClass = 'bi-bank';
        const nameLower = displayName.toLowerCase();
        if (nameLower.includes('محفظ') || nameLower.includes('كاش') || nameLower.includes('جوالي') || nameLower.includes('جيب') || nameLower.includes('موبايل')) {
            iconClass = 'bi-wallet2';
        }

        const pill = document.createElement('button');
        pill.type = 'button';
        pill.className = 'pay-method-pill';
        pill.id = 'payMethodPill_' + pm.method_id;
        pill.innerHTML = '<i class="bi ' + iconClass + ' pay-method-pill-icon"></i><span>' + escapeHtml(displayName) + '</span>';
        pill.onclick = function() {
            selectPaymentMethod(pm.method_id);
        };
        container.appendChild(pill);
    });

    // اختيار أول طريقة تلقائياً لإبراز البيانات والنسخ مباشرة
    if (currentUnivMethods.length > 0) {
        selectPaymentMethod(currentUnivMethods[0].method_id);
    }
}

// عند الضغط على طريقة الدفع
function selectPaymentMethod(methodId) {
    const pm = currentUnivMethods.find(m => String(m.method_id) === String(methodId));
    if (!pm) return;

    document.querySelectorAll('.pay-method-pill').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.getElementById('payMethodPill_' + methodId);
    if (activeBtn) activeBtn.classList.add('active');

    const selectEl = document.getElementById('payment_method_id');
    if (selectEl) selectEl.value = pm.method_id;

    const detailsBox = document.getElementById('selectedMethodDetailsBox');
    const nameEl = document.getElementById('detailMethodName');
    const numEl = document.getElementById('detailAccountNum');
    const accNameEl = document.getElementById('detailAccountName');

    currentActiveAccountNum = pm.account_number || '';

    const displayName = (pm.method_name && pm.method_name.trim()) ? pm.method_name : 'حساب بنكي معتمد';
    if (nameEl) nameEl.textContent = displayName;
    if (numEl) numEl.textContent = pm.account_number;
    if (accNameEl) accNameEl.textContent = 'باسم: ' + (pm.account_name || 'الجامعة');

    if (detailsBox) detailsBox.classList.remove('d-none');
}

// مزامنة عند التغيير من القائمة المنسدلة
function syncSelectedMethodFromDropdown(methodId) {
    if (methodId) {
        selectPaymentMethod(methodId);
    }
}

// نسخ رقم الحساب
function copyActiveAccount() {
    if (!currentActiveAccountNum) return;
    const btn = document.getElementById('btnCopyAccount');
    const origHtml = btn ? btn.innerHTML : '';

    const notifySuccess = () => {
        if (!btn) return;
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>تم النسخ!';
        btn.classList.add('btn-success', 'text-white');
        btn.classList.remove('btn-outline-primary');
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.remove('btn-success', 'text-white');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(currentActiveAccountNum).then(notifySuccess).catch(() => fallbackCopy(currentActiveAccountNum, notifySuccess));
    } else {
        fallbackCopy(currentActiveAccountNum, notifySuccess);
    }
}

function fallbackCopy(text, cb) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        if (cb) cb();
    } catch (e) {
        alert('رقم الحساب: ' + text);
    }
    document.body.removeChild(textarea);
}

// التبديل بين السداد الفوري والسداد لاحقاً
function togglePaymentTiming(mode) {
    const payNowContainer = document.getElementById('payNowContainer');
    const payLaterNotice = document.getElementById('payLaterNotice');
    const cardNow = document.getElementById('payChoiceNowLabel');
    const cardLater = document.getElementById('payChoiceLaterLabel');
    const radioNow = document.getElementById('payTimingNow');
    const radioLater = document.getElementById('payTimingLater');
    const pledgeCard = document.getElementById('applyPledgeCard');

    if (mode === 'now') {
        if (radioNow) radioNow.checked = true;
        if (cardNow) cardNow.classList.add('active');
        if (cardLater) cardLater.classList.remove('active');
        if (payNowContainer) payNowContainer.classList.remove('d-none');
        if (payLaterNotice) payLaterNotice.classList.add('d-none');

        // توليد الحسابات البنكية فوراً عند فتح السداد الآن
        renderUniversityPaymentMethods(currentUnivId);
    } else if (mode === 'later') {
        if (radioLater) radioLater.checked = true;
        if (cardNow) cardNow.classList.remove('active');
        if (cardLater) cardLater.classList.add('active');
        if (payNowContainer) payNowContainer.classList.add('d-none');
        if (payLaterNotice) payLaterNotice.classList.remove('d-none');

        setTimeout(() => {
            if (pledgeCard) {
                pledgeCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
    }
}

// تحديث الرسوم المحتسبة حسب التخصص المختار
function updateFeeDetails() {
    const feeSpecName = document.getElementById('feeSpecNameDisplay');
    const feeBadge = document.getElementById('feeAmountBadge');
    const feeAmountInput = document.getElementById('payment_amount');

    let fee = 5000;
    let specName = currentDesiredSpec || 'تخصص عام';

    if (currentUnivId && currentDesiredSpec) {
        const univ = window.universitiesDatabase.find(u => String(u.university_id) === String(currentUnivId));
        if (univ && univ.colleges) {
            for (const col of univ.colleges) {
                if (col.specializations) {
                    const sp = col.specializations.find(s => s.specialization_name === currentDesiredSpec);
                    if (sp && sp.admission_data && sp.admission_data.registration_fee) {
                        fee = sp.admission_data.registration_fee;
                        break;
                    }
                }
            }
        }
    }

    if (feeSpecName) feeSpecName.textContent = specName;
    if (feeBadge) feeBadge.textContent = Number(fee).toLocaleString() + ' ر.ي';
    const guideFee = document.getElementById('guideFeeAmount');
    if (guideFee) guideFee.textContent = Number(fee).toLocaleString() + ' ر.ي';
    if (feeAmountInput) feeAmountInput.value = fee;
}

// التبديل بين السداد الفوري والسداد لاحقاً
function togglePaymentTiming(mode) {
    const payNowContainer = document.getElementById('payNowContainer');
    const payLaterNotice = document.getElementById('payLaterNotice');
    const cardNow = document.getElementById('payChoiceNowLabel');
    const cardLater = document.getElementById('payChoiceLaterLabel');
    const radioNow = document.getElementById('payTimingNow');
    const radioLater = document.getElementById('payTimingLater');
    const pledgeCard = document.getElementById('applyPledgeCard');

    if (mode === 'now') {
        if (radioNow) radioNow.checked = true;
        if (cardNow) cardNow.classList.add('active');
        if (cardLater) cardLater.classList.remove('active');
        if (payNowContainer) payNowContainer.classList.remove('d-none');
        if (payLaterNotice) payLaterNotice.classList.add('d-none');

        // التمرير السلس إلى بداية خطوات السداد
        setTimeout(() => {
            if (payNowContainer) {
                payNowContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 100);
    } else if (mode === 'later') {
        if (radioLater) radioLater.checked = true;
        if (cardNow) cardNow.classList.remove('active');
        if (cardLater) cardLater.classList.add('active');
        if (payNowContainer) payNowContainer.classList.add('d-none');
        if (payLaterNotice) payLaterNotice.classList.remove('d-none');

        // نقل الطالب بسلاسة إلى زر الموافقة والإقرار على البيانات وأزرار الإجراءات الأخيرة
        setTimeout(() => {
            if (pledgeCard) {
                pledgeCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                pledgeCard.classList.add('pulse-focus');
                setTimeout(() => pledgeCard.classList.remove('pulse-focus'), 1500);
            }
        }, 150);
    }
}

</script>
@endsection
