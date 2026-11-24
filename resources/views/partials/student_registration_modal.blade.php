{{-- =========================================================================
     استمارة طلب التسجيل للعام الجامعي 2026/2027 — EduYemen
     مطابقة بالكامل للمخطط والتصميم المعتمد
     ========================================================================= --}}

<div class="reg-modal-overlay" id="studentRegistrationModal" style="display:none;" onclick="if(event.target === this) closeRegistrationModal();">
    <div class="reg-modal-wrapper">

        {{-- الشريط العلوي داخل الإطار الكحلي --}}
        <div class="reg-top-bar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="reg-btn-close" onclick="closeRegistrationModal()" title="إغلاق">✕</button>
                <span class="reg-privacy-pill">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>بياناتك تُستخدم لأغراض التسجيل فقط</span>
                </span>
            </div>

            <div class="reg-top-guide text-end">
                <span class="text-white-50 small">أدخل بياناتك بدقة، اختر تخصصك المناسب، وأرفق الوثائق المطلوبة إلى الجامعة المختارة.</span>
            </div>
        </div>

        {{-- مؤشر جاهزية الطلب --}}
        <div class="reg-progress-container">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="reg-progress-percent" id="regProgressPercentText">65% — أكمل البيانات والوثائق المطلوبة</span>
                <span class="reg-progress-label">جاهزية الطلب</span>
            </div>
            <div class="reg-progress-track">
                <div class="reg-progress-bar" id="regProgressBar" style="width: 65%;"></div>
            </div>
        </div>

        {{-- ورقة الاستمارة البيضاء الرسمية --}}
        <div class="reg-paper-card">

            {{-- الترويسة الرسمية (Header) --}}
            <div class="reg-formal-header">
                <div class="row align-items-center">
                    {{-- الجانب الأيمن: الترويسة العربية --}}
                    <div class="col-4 text-end">
                        <div class="fw-bold text-dark" style="font-size:0.92rem;">الجمهورية اليمنية</div>
                        <div class="text-muted" style="font-size:0.75rem;">وزارة التعليم العالي والبحث العلمي</div>
                        <div class="fw-bold text-primary mt-1" style="font-size:0.88rem;" id="regHeaderUnivName">اسم الجامعة</div>
                        <div class="text-muted" style="font-size:0.7rem;" id="regHeaderUnivSub">يحدد تلقائياً بعد اختيار الجامعة</div>
                    </div>

                    {{-- المنتصف: الشعار الرسمي --}}
                    <div class="col-4 text-center">
                        <div class="reg-header-emblem">
                            <img src="{{ asset('img/logo.png') }}" alt="EduYemen" onerror="this.onerror=null; this.src='{{ asset('logo.png') }}';" style="height: 52px; object-fit: contain;">
                        </div>
                    </div>

                    {{-- الجانب الأيسر: الترويسة الإنجليزية والتاريخ --}}
                    <div class="col-4 text-start" dir="ltr">
                        <div class="fw-bold text-dark" style="font-size:0.85rem;">Republic Of Yemen</div>
                        <div class="text-muted" style="font-size:0.7rem;">Ministry of Higher Education & Scientific Research</div>
                        <div class="text-muted mt-1" style="font-size:0.75rem;">Date: <strong>{{ date('d/m/Y') }}</strong></div>
                    </div>
                </div>

                {{-- الشريط العريض لعنوان الاستمارة --}}
                <div class="reg-title-banner mt-3 text-center">
                    <h5 class="fw-bold mb-0 text-dark">استمارة طلب التسجيل للعام الجامعي 2026/2027</h5>
                </div>
            </div>

            {{-- تنبيه وضع المعاينة إن وجد --}}
            @if(isset($isPreview) && $isPreview)
            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 my-3 rounded-3">
                <i class="bi bi-shield-lock-fill text-warning fs-5"></i>
                <div><strong>وضع المعاينة:</strong> أنت تتصفح الاستمارة في وضع المعاينة المخصص لإدارة الجامعة. إرسال الطلب الفعلي متاح لحسابات الطلبة فقط.</div>
            </div>
            @endif

            <form id="studentRegistrationForm" onsubmit="handleRegistrationSubmit(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="is_draft" id="regIsDraftInput" value="0">

                {{-- =========================================================
                     القسم 1: بيانات استمارة الثانوية العامة
                     ========================================================= --}}
                <div class="reg-section-card">
                    <div class="reg-section-header">
                        <div class="reg-badge-num">1</div>
                        <div>
                            <h6 class="reg-section-title">بيانات استمارة الثانوية العامة</h6>
                            <span class="reg-section-sub">أدخل البيانات كما تظهر في وثيقة الثانوية الأصلية</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- السطر 1: الاسم والجنسية --}}
                        <div class="col-md-9">
                            <label class="reg-label required">الاسم الكامل</label>
                            <input type="text" class="form-control reg-input" name="full_name" id="reg_full_name"
                                   value="{{ auth()->check() && auth()->user()->student ? auth()->user()->student->full_name : '' }}"
                                   placeholder="أدخل اسمك الرباعي كما في استمارة الثانوية" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-3">
                            <label class="reg-label required">الجنسية</label>
                            <div class="d-flex align-items-center gap-3 pt-2">
                                <label class="reg-radio-label">
                                    <input type="radio" name="nationality" value="يمني" checked onchange="calculateRegProgress()">
                                    <span>يمني</span>
                                </label>
                                <label class="reg-radio-label">
                                    <input type="radio" name="nationality" value="أخرى" onchange="calculateRegProgress()">
                                    <span>أخرى</span>
                                </label>
                            </div>
                        </div>

                        {{-- السطر 2: تاريخ الميلاد ومكان الميلاد والجنس --}}
                        <div class="col-md-4">
                            <label class="reg-label required">تاريخ الميلاد</label>
                            <input type="date" class="form-control reg-input" name="birth_date" id="reg_birth_date"
                                   value="{{ auth()->check() && auth()->user()->student && auth()->user()->student->birth_date ? auth()->user()->student->birth_date->format('Y-m-d') : '2005-01-01' }}"
                                   required onchange="calculateRegProgress()">
                        </div>
                        <div class="col-md-5">
                            <label class="reg-label required">مكان الميلاد</label>
                            <input type="text" class="form-control reg-input" name="birth_place" id="reg_birth_place"
                                   placeholder="المحافظة / المديرية (مثال: إب / القفر)" value="صنعاء" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-3">
                            <label class="reg-label required">الجنس</label>
                            <div class="d-flex align-items-center gap-3 pt-2">
                                <label class="reg-radio-label">
                                    <input type="radio" name="gender" value="ذكر" {{ (!auth()->check() || !auth()->user()->student || auth()->user()->student->gender !== 'أنثى') ? 'checked' : '' }} onchange="calculateRegProgress()">
                                    <span>ذكر</span>
                                </label>
                                <label class="reg-radio-label">
                                    <input type="radio" name="gender" value="أنثى" {{ (auth()->check() && auth()->user()->student && auth()->user()->student->gender === 'أنثى') ? 'checked' : '' }} onchange="calculateRegProgress()">
                                    <span>أنثى</span>
                                </label>
                            </div>
                        </div>

                        {{-- السطر 3: سنة التخرج والمدرسة والقسم --}}
                        <div class="col-md-3">
                            <label class="reg-label required">سنة التخرج</label>
                            <select class="form-select reg-input" name="grad_year" id="reg_grad_year" required onchange="calculateRegProgress()">
                                @for($y = 2026; $y >= 2015; $y--)
                                <option value="{{ $y }}" {{ $y == 2024 ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="reg-label required">المدرسة الثانوية</label>
                            <input type="text" class="form-control reg-input" name="secondary_school" id="reg_secondary_school"
                                   placeholder="اسم المدرسة الثانوية (مثال: ثانوية جمال عبد الناصر)" value="ثانوية عثمان بن عفان" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">القسم الثانوي</label>
                            <div class="d-flex align-items-center gap-2 pt-2">
                                <label class="reg-radio-label">
                                    <input type="radio" name="sec_section" value="علمي" checked onchange="calculateRegProgress()">
                                    <span>علمي</span>
                                </label>
                                <label class="reg-radio-label">
                                    <input type="radio" name="sec_section" value="أدبي" onchange="calculateRegProgress()">
                                    <span>أدبي</span>
                                </label>
                                <label class="reg-radio-label">
                                    <input type="radio" name="sec_section" value="أخرى" onchange="calculateRegProgress()">
                                    <span>أخرى</span>
                                </label>
                            </div>
                        </div>

                        {{-- السطر 4: رقم الجلوس والمعدل والمجموع العام --}}
                        <div class="col-md-4">
                            <label class="reg-label required">رقم الجلوس</label>
                            <input type="text" class="form-control reg-input font-monospace" name="seat_number" id="reg_seat_number"
                                   placeholder="مثال: 423682" value="423682" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">المعدل (%)</label>
                            <input type="number" step="0.01" min="50" max="100" class="form-control reg-input" name="sec_avg" id="reg_sec_avg"
                                   placeholder="مثال: 85.50" value="81.00" required oninput="autoCalculateScore(); calculateRegProgress();">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">المجموع العام</label>
                            <input type="number" step="1" min="100" max="1000" class="form-control reg-input" name="total_score" id="reg_total_score"
                                   placeholder="مثال: 709" value="709" required oninput="calculateRegProgress()">
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     القسم 2: بيانات الطالب
                     ========================================================= --}}
                <div class="reg-section-card">
                    <div class="reg-section-header">
                        <div class="reg-badge-num">2</div>
                        <div>
                            <h6 class="reg-section-title">بيانات الطالب</h6>
                            <span class="reg-section-sub">بيانات التواصل وعنوان الطالب وولي الأمر</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- السطر 1: المحافظة والمديرية وعنوان السكن --}}
                        <div class="col-md-4">
                            <label class="reg-label required">مكان سكن الطالب/المحافظة</label>
                            <select class="form-select reg-input" name="province" id="reg_province" required onchange="calculateRegProgress()">
                                <option value="أمانة العاصمة" selected>أمانة العاصمة</option>
                                <option value="صنعاء">محافظة صنعاء</option>
                                <option value="عدن">عدن</option>
                                <option value="تعز">تعز</option>
                                <option value="إب">إب</option>
                                <option value="الحديدة">الحديدة</option>
                                <option value="ذمار">ذمار</option>
                                <option value="حضرموت">حضرموت</option>
                                <option value="لحج">لحج</option>
                                <option value="أبين">أبين</option>
                                <option value="شبوة">شبوة</option>
                                <option value="حجة">حجة</option>
                                <option value="صعدة">صعدة</option>
                                <option value="عمران">عمران</option>
                                <option value="البيضاء">البيضاء</option>
                                <option value="المحويت">المحويت</option>
                                <option value="المهرة">المهرة</option>
                                <option value="مأرب">مأرب</option>
                                <option value="الجوف">الجوف</option>
                                <option value="الضالع">الضالع</option>
                                <option value="ريمة">ريمة</option>
                                <option value="سقطرى">سقطرى</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">المديرية</label>
                            <input type="text" class="form-control reg-input" name="residence" id="reg_residence"
                                   placeholder="مثال: السبعين" value="السبعين" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">عنوان السكن</label>
                            <input type="text" class="form-control reg-input" name="home_address" id="reg_home_address"
                                   placeholder="الشارع / الحي (مثال: شارع تعز)" value="شارع تعز" required oninput="calculateRegProgress()">
                        </div>

                        {{-- السطر 2: الهاتف والبريد وولي الأمر --}}
                        <div class="col-md-4">
                            <label class="reg-label required">رقم الهاتف</label>
                            <input type="tel" class="form-control reg-input font-monospace" name="phone" id="reg_phone"
                                   value="{{ auth()->check() ? auth()->user()->phone : '771234567' }}"
                                   placeholder="مثال: 771234567" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">البريد الإلكتروني</label>
                            <input type="email" class="form-control reg-input" name="email" id="reg_email"
                                   value="{{ auth()->check() ? auth()->user()->email : 'student@gmail.com' }}"
                                   placeholder="student@example.com" required style="background-color: #f0f7ff;" oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">اسم ولي الأمر</label>
                            <input type="text" class="form-control reg-input" name="guardian_name" id="reg_guardian_name"
                                   placeholder="اسم ولي الأمر كاملاً" value="سعيد العودي" required oninput="calculateRegProgress()">
                        </div>

                        {{-- السطر 3: صلة القرابة والمهنة ورقم هاتف ولي الأمر --}}
                        <div class="col-md-4">
                            <label class="reg-label required">صلة القرابة</label>
                            <select class="form-select reg-input" name="guardian_rel" id="reg_guardian_rel" required onchange="calculateRegProgress()">
                                <option value="أب" selected>الأب</option>
                                <option value="أم">الأم</option>
                                <option value="أخ">الأخ</option>
                                <option value="عم">العم</option>
                                <option value="خال">الخال</option>
                                <option value="ولي أمر">ولي أمر شرعي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">مهنة ولي الأمر</label>
                            <input type="text" class="form-control reg-input" name="guardian_occup" id="reg_guardian_occup"
                                   placeholder="مثال: مدير مدرسة / موظف حكومي" value="مدير مدرسة" required oninput="calculateRegProgress()">
                        </div>
                        <div class="col-md-4">
                            <label class="reg-label required">هاتف ولي الأمر</label>
                            <input type="tel" class="form-control reg-input font-monospace" name="gu_phone" id="reg_gu_phone"
                                   placeholder="مثال: 773355581" value="773355581" required oninput="calculateRegProgress()">
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     القسم 3: التخصص المرغوب به
                     ========================================================= --}}
                <div class="reg-section-card">
                    <div class="reg-section-header">
                        <div class="reg-badge-num">3</div>
                        <div>
                            <h6 class="reg-section-title">التخصص المرغوب به</h6>
                            <span class="reg-section-sub">استعرض الكليات واختر تخصصاً واحداً فقط</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="reg-label required">الجامعة</label>
                            <select class="form-select reg-input" name="university_id" id="reg_university_select" required onchange="onRegistrationUnivChange(); calculateRegProgress();">
                                <option value="" disabled selected>-- اختر الجامعة --</option>
                                @if(isset($allUniversities) && $allUniversities->count() > 0)
                                    @foreach($allUniversities as $u)
                                    <option value="{{ $u->university_id }}" {{ (isset($university) && $university->university_id == $u->university_id) ? 'selected' : '' }}>
                                        {{ $u->university_name }} ({{ $u->university_type }})
                                    </option>
                                    @endforeach
                                @elseif(isset($universities) && $universities->count() > 0)
                                    @foreach($universities as $u)
                                    <option value="{{ $u->university_id }}" {{ (isset($university) && $university->university_id == $u->university_id) ? 'selected' : '' }}>
                                        {{ $u->university_name }} ({{ $u->university_type }})
                                    </option>
                                    @endforeach
                                @elseif(isset($university))
                                    <option value="{{ $university->university_id }}" selected>{{ $university->university_name }}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="reg-label required">التخصص المرغوب به</label>
                            <select class="form-select reg-input" name="desired_spec" id="reg_desired_spec_select" required onchange="calculateRegProgress()">
                                <option value="" disabled selected>-- اختر التخصص --</option>
                                @if(isset($university) && $university->colleges)
                                    @foreach($university->colleges as $c)
                                    <optgroup label="{{ $c->college_name }}">
                                        @foreach($c->specializations as $s)
                                        <option value="{{ $s->specialization_name }}">
                                            {{ $s->specialization_name }} ({{ number_format($s->tuition_fees) }} دولار/ر.ي - {{ $s->duration_years ?? 4 }} سنوات)
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-12">
                            <small class="text-danger" style="font-size:0.75rem;">
                                <i class="bi bi-info-circle me-1"></i>تنبيه: سيتم تحميل قائمة التخصصات بعد اختيار الجامعة من القائمة أعلاه ثم اختر التخصص.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     القسم 4: الوثائق المطلوبة
                     ========================================================= --}}
                <div class="reg-section-card">
                    <div class="reg-section-header">
                        <div class="reg-badge-num">4</div>
                        <div>
                            <h6 class="reg-section-title">الوثائق المطلوبة</h6>
                            <span class="reg-section-sub">ارفع ملفات واضحة بصيغة PDF أو JPG أو PNG</span>
                        </div>
                    </div>

                    {{-- شريط التنبيه الإرشادي --}}
                    <div class="reg-alert-box mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                        <span>الوثائق المحددة إجبارية. الرجاء التأكد من إرفاق الملف بدقة، الحد الأقصى للملف الواحد 5 ميجابايت.</span>
                    </div>

                    {{-- شبكة بطاقات الرفع (2x2) --}}
                    <div class="row g-3">
                        {{-- وثيقة 1: البطاقة الشخصية --}}
                        <div class="col-md-6">
                            <div class="reg-doc-card" id="docCard_id">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-badge text-primary fs-5"></i>
                                        <strong class="text-dark small">صورة البطاقة الشخصية</strong>
                                    </div>
                                    <span class="reg-tag-required">إلزامية</span>
                                </div>
                                <div class="text-muted" style="font-size:0.75rem; margin-bottom:8px;">الهوية الأساسية، واضحة</div>
                                
                                <div class="reg-upload-row">
                                    <input type="file" name="doc_id_card" id="file_doc_id_card" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="handleFileSelected(this, 'docCard_id', 'status_doc_id_card')">
                                    <button type="button" class="btn btn-outline-primary btn-sm reg-btn-pick" onclick="document.getElementById('file_doc_id_card').click();">
                                        <i class="bi bi-folder2-open me-1"></i>اختيار ملف
                                    </button>
                                    <div class="reg-file-status" id="status_doc_id_card">لم يتم اختيار ملف</div>
                                </div>
                            </div>
                        </div>

                        {{-- وثيقة 2: شهادة الثانوية الأصل --}}
                        <div class="col-md-6">
                            <div class="reg-doc-card" id="docCard_cert">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-award text-success fs-5"></i>
                                        <strong class="text-dark small">شهادة الثانوية الأصل</strong>
                                    </div>
                                    <span class="reg-tag-required">إلزامية</span>
                                </div>
                                <div class="text-muted" style="font-size:0.75rem; margin-bottom:8px;">صورة أو ملف PDF مكمل</div>
                                
                                <div class="reg-upload-row">
                                    <input type="file" name="doc_cert" id="file_doc_cert" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="handleFileSelected(this, 'docCard_cert', 'status_doc_cert')">
                                    <button type="button" class="btn btn-outline-primary btn-sm reg-btn-pick" onclick="document.getElementById('file_doc_cert').click();">
                                        <i class="bi bi-folder2-open me-1"></i>اختيار ملف
                                    </button>
                                    <div class="reg-file-status" id="status_doc_cert">لم يتم اختيار ملف</div>
                                </div>
                            </div>
                        </div>

                        {{-- وثيقة 3: استمارة الثانوية --}}
                        <div class="col-md-6">
                            <div class="reg-doc-card" id="docCard_transcript">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-check text-info fs-5"></i>
                                        <strong class="text-dark small">صورة استمارة الثانوية</strong>
                                    </div>
                                    <span class="reg-tag-required">إلزامية</span>
                                </div>
                                <div class="text-muted" style="font-size:0.75rem; margin-bottom:8px;">الصورة كاملة وغير مقصوصة</div>
                                
                                <div class="reg-upload-row">
                                    <input type="file" name="doc_transcript" id="file_doc_transcript" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="handleFileSelected(this, 'docCard_transcript', 'status_doc_transcript')">
                                    <button type="button" class="btn btn-outline-primary btn-sm reg-btn-pick" onclick="document.getElementById('file_doc_transcript').click();">
                                        <i class="bi bi-folder2-open me-1"></i>اختيار ملف
                                    </button>
                                    <div class="reg-file-status" id="status_doc_transcript">لم يتم اختيار ملف</div>
                                </div>
                            </div>
                        </div>

                        {{-- وثيقة 4: أخرى (اختيارية) --}}
                        <div class="col-md-6">
                            <div class="reg-doc-card" id="docCard_other">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-paperclip text-secondary fs-5"></i>
                                        <strong class="text-dark small">وثيقة أخرى</strong>
                                    </div>
                                    <span class="reg-tag-optional">اختيارية</span>
                                </div>
                                <div class="text-muted" style="font-size:0.75rem; margin-bottom:8px;">شهادات تميز، فحص طبي، أو وثائق إضافية</div>
                                
                                <div class="reg-upload-row">
                                    <input type="file" name="doc_other" id="file_doc_other" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="handleFileSelected(this, 'docCard_other', 'status_doc_other')">
                                    <button type="button" class="btn btn-outline-secondary btn-sm reg-btn-pick" onclick="document.getElementById('file_doc_other').click();">
                                        <i class="bi bi-folder2-open me-1"></i>اختيار ملف
                                    </button>
                                    <div class="reg-file-status" id="status_doc_other">لم يتم اختيار ملف</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- الإقرار والتعهد --}}
                    <div class="reg-pledge-box mt-3">
                        <label class="d-flex align-items-start gap-2 cursor-pointer mb-0">
                            <input type="checkbox" id="reg_pledge_check" class="form-check-input mt-1" required onchange="calculateRegProgress()">
                            <span class="text-dark small">أقر أنا الطالب بأن البيانات المدونة صحيحة، وأتعهد بالالتزام بأنظمة ولوائح الجامعة، وأتحمل مسؤولية صحة الوثائق المرفقة.</span>
                        </label>
                    </div>
                </div>

                {{-- أزرار الإرسال والحفظ في الأسفل --}}
                <div class="reg-bottom-actions mt-4 pt-2 d-flex justify-content-center gap-3">
                    @if(isset($isPreview) && $isPreview)
                    <button type="button" class="btn btn-secondary px-4 py-2" disabled style="cursor:not-allowed;">
                        <i class="bi bi-eye me-1"></i>وضع المعاينة (الإرسال متاح للطلبة فقط)
                    </button>
                    @else
                    <button type="submit" class="btn reg-btn-submit" id="regBtnSubmitApp" onclick="document.getElementById('regIsDraftInput').value = '0';">
                        <i class="bi bi-send-check me-1"></i>
                        <span>إرسال طلب التسجيل</span>
                    </button>
                    <button type="button" class="btn reg-btn-draft" id="regBtnSaveDraft" onclick="submitApplicationAsDraft()">
                        <i class="bi bi-bookmark me-1"></i>
                        <span>حفظ كمسودة</span>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- تنسيقات CSS الخاصة بالاستمارة --}}
<style>
/* تطبيق الخط الحديث فائق الوضوح (IBM Plex Sans Arabic) لنافذة تسجيل الطالب */
.reg-modal-wrapper,
.reg-modal-wrapper input,
.reg-modal-wrapper select,
.reg-modal-wrapper button,
.reg-modal-wrapper textarea {
    font-family: 'IBM Plex Sans Arabic', 'Almarai', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
}

/* تراكب المودال */
.reg-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background-color: rgba(10, 20, 38, 0.88);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px 12px;
    overflow-y: auto;
}

/* بطاقة الإطار الكحلي الخارجي */
.reg-modal-wrapper {
    background: linear-gradient(180deg, #12213a 0%, #0d192d 100%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
    width: 100%;
    max-width: 900px;
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
    animation: regModalSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes regModalSlideIn {
    from { opacity: 0; transform: translateY(24px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* الشريط العلوي */
.reg-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.reg-btn-close {
    background: #ffffff;
    border: none;
    color: #0f172a;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
}
.reg-btn-close:hover {
    background: #f1f5f9;
    transform: scale(1.06);
}

.reg-privacy-pill {
    background: rgba(255, 255, 255, 0.95);
    color: #0f172a;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(0, 0, 0, 0.06);
}

/* مؤشر الجاهزية */
.reg-progress-container {
    padding: 8px 20px 12px;
}
.reg-progress-label {
    color: #cbd5e1;
    font-size: 0.78rem;
    font-weight: 600;
}
.reg-progress-percent {
    color: #facc15;
    font-size: 0.8rem;
    font-weight: 700;
}
.reg-progress-track {
    background: rgba(255, 255, 255, 0.12);
    height: 6px;
    border-radius: 10px;
    overflow: hidden;
}
.reg-progress-bar {
    background: linear-gradient(90deg, #d97706, #f59e0b, #fbbf24);
    height: 100%;
    border-radius: 10px;
    transition: width 0.4s ease;
}

/* ورقة الاستمارة البيضاء */
.reg-paper-card {
    background: #ffffff;
    border-radius: 16px 16px 0 0;
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

/* الترويسة الرسمية */
.reg-formal-header {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    background: #f8fafc;
    margin-bottom: 22px;
}
.reg-header-emblem {
    display: inline-block;
    padding: 6px 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.reg-title-banner {
    border-top: 1px solid #cbd5e1;
    padding-top: 12px;
}
.reg-title-banner h5 {
    color: #0f172a;
    letter-spacing: -0.3px;
}

/* بطاقات الأقسام */
.reg-section-card {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 20px;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}
.reg-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 1px dashed #e2e8f0;
}
.reg-badge-num {
    background: #1e3a8a;
    color: #ffffff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.88rem;
    flex-shrink: 0;
}
.reg-section-title {
    font-weight: 700;
    color: #0f2b5c;
    margin-bottom: 2px;
    font-size: 0.95rem;
}
.reg-section-sub {
    font-size: 0.74rem;
    color: #64748b;
}

/* التسميات والحقول */
.reg-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 5px;
    display: block;
}
.reg-label.required::after {
    content: " *";
    color: #dc2626;
    font-weight: bold;
}
.reg-input {
    font-size: 0.84rem;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    padding: 7px 12px;
    transition: all 0.2s ease;
}
.reg-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.reg-radio-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    color: #334155;
    cursor: pointer;
}
.reg-radio-label input {
    accent-color: #1e3a8a;
    cursor: pointer;
}

/* تنبيه الوثائق */
.reg-alert-box {
    background: #fefce8;
    border: 1px solid #fef08a;
    color: #854d0e;
    font-size: 0.76rem;
    padding: 8px 12px;
    border-radius: 8px;
    display: flex;
    align-items: center;
}

/* بطاقات رفع الوثائق */
.reg-doc-card {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.reg-doc-card.has-file {
    background: #f0fdf4;
    border-color: #86efac;
}
.reg-tag-required {
    background: #fee2e2;
    color: #b91c1c;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
}
.reg-tag-optional {
    background: #f1f5f9;
    color: #64748b;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 12px;
}
.reg-upload-row {
    display: flex;
    align-items: center;
    gap: 8px;
}
.reg-btn-pick {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
}
.reg-file-status {
    font-size: 0.72rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 220px;
}

/* مربع الإقرار */
.reg-pledge-box {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
}

/* أزرار الإرسال والحفظ */
.reg-btn-submit {
    background: linear-gradient(180deg, #facc15 0%, #eab308 100%);
    border: none;
    color: #0f172a;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 10px 32px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(234, 179, 8, 0.35);
    transition: all 0.2s ease;
}
.reg-btn-submit:hover {
    background: linear-gradient(180deg, #fde047 0%, #facc15 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(234, 179, 8, 0.45);
}

.reg-btn-draft {
    background: #ffffff;
    border: 1.5px solid #0f172a;
    color: #0f172a;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 10px 24px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.reg-btn-draft:hover {
    background: #0f172a;
    color: #ffffff;
}

/* استجابة الشاشات الصغيرة */
@media (max-width: 768px) {
    .reg-modal-wrapper {
        border-radius: 12px;
        max-height: 98vh;
    }
    .reg-paper-card {
        padding: 14px;
    }
    .reg-section-card {
        padding: 14px 12px;
    }
}
</style>

{{-- برمجة وتفاعل الاستمارة (JavaScript) --}}
<script>
// كائن بيانات الجامعات والتخصصات لتحديث القوائم ديناميكياً
window.allUniversitiesList = @json(isset($allUniversities) ? $allUniversities : (isset($universities) ? $universities : []));

// فتح الاستمارة
function openRegistrationModal(targetUnivId = null, targetSpecName = null) {
    const modal = document.getElementById('studentRegistrationModal');
    if (!modal) return;

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // تحديد الجامعة
    const univSelect = document.getElementById('reg_university_select');
    if (univSelect && targetUnivId) {
        univSelect.value = targetUnivId;
    }

    onRegistrationUnivChange();

    // تحديد التخصص إن وجد
    if (targetSpecName) {
        const specSelect = document.getElementById('reg_desired_spec_select');
        if (specSelect) {
            for (let i = 0; i < specSelect.options.length; i++) {
                if (specSelect.options[i].value.includes(targetSpecName) || targetSpecName.includes(specSelect.options[i].value)) {
                    specSelect.selectedIndex = i;
                    break;
                }
            }
        }
    }

    calculateRegProgress();
}

// إغلاق الاستمارة
function closeRegistrationModal() {
    const modal = document.getElementById('studentRegistrationModal');
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// تحديث اسم الجامعة في الترويسة وقائمة التخصصات عند تغيير الجامعة
function onRegistrationUnivChange() {
    const univSelect = document.getElementById('reg_university_select');
    if (!univSelect) return;

    const selectedUnivId = univSelect.value;
    const selectedOption = univSelect.options[univSelect.selectedIndex];
    const univNameText = selectedOption ? selectedOption.text.split('(')[0].trim() : 'اسم الجامعة';

    // تحديث الترويسة
    const headerName = document.getElementById('regHeaderUnivName');
    const headerSub = document.getElementById('regHeaderUnivSub');
    if (headerName) headerName.textContent = selectedUnivId ? univNameText : 'اسم الجامعة';
    if (headerSub) headerSub.textContent = selectedUnivId ? 'المعتمدة لدى وزارة التعليم العالي' : 'يحدد تلقائياً بعد اختيار الجامعة';

    // تحديث قائمة التخصصات
    const specSelect = document.getElementById('reg_desired_spec_select');
    if (!specSelect) return;

    // الاحتفاظ بالاختيار الحالي إن وجد
    const currentVal = specSelect.value;
    specSelect.innerHTML = '<option value="" disabled selected>-- اختر التخصص --</option>';

    if (!selectedUnivId) return;

    // البحث في كائن الجامعات المحلية
    let foundUniv = null;
    if (Array.isArray(window.allUniversitiesList)) {
        foundUniv = window.allUniversitiesList.find(u => u.university_id == selectedUnivId);
    }

    if (foundUniv && foundUniv.colleges) {
        foundUniv.colleges.forEach(college => {
            if (college.specializations && college.specializations.length > 0) {
                const optGroup = document.createElement('optgroup');
                optGroup.label = college.college_name;
                college.specializations.forEach(spec => {
                    const opt = document.createElement('option');
                    opt.value = spec.specialization_name;
                    const feesText = spec.tuition_fees ? ` (${Number(spec.tuition_fees).toLocaleString()} دولار/ر.ي)` : '';
                    opt.textContent = `${spec.specialization_name}${feesText}`;
                    if (spec.specialization_name === currentVal) {
                        opt.selected = true;
                    }
                    optGroup.appendChild(opt);
                });
                specSelect.appendChild(optGroup);
            }
        });
    }
}

// معالجة اختيار الملفات وتحديث واجهة البطاقة
function handleFileSelected(input, cardId, statusId) {
    const card = document.getElementById(cardId);
    const status = document.getElementById(statusId);

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

        if (file.size > 5 * 1024 * 1024) {
            alert('حجم الملف كبير جداً! الحد الأقصى المسموح به هو 5 ميجابايت.');
            input.value = '';
            if (card) card.classList.remove('has-file');
            if (status) status.innerHTML = '<span class="text-danger">تجاوز الحد الأقصى (5MB)</span>';
            return;
        }

        if (card) card.classList.add('has-file');
        if (status) {
            status.innerHTML = `<span class="text-success fw-bold">✓ ${file.name}</span> <span class="text-muted" style="font-size:0.68rem;">(${fileSizeMB}MB)</span>`;
        }
    } else {
        if (card) card.classList.remove('has-file');
        if (status) status.textContent = 'لم يتم اختيار ملف';
    }

    calculateRegProgress();
}

// حساب نسبة اكتمال البيانات وجاهزية الطلب
function calculateRegProgress() {
    const fields = [
        'reg_full_name', 'reg_birth_date', 'reg_birth_place',
        'reg_secondary_school', 'reg_seat_number', 'reg_sec_avg', 'reg_total_score',
        'reg_residence', 'reg_home_address', 'reg_phone', 'reg_email',
        'reg_guardian_name', 'reg_guardian_occup', 'reg_gu_phone',
        'reg_university_select', 'reg_desired_spec_select'
    ];

    let filledCount = 0;
    fields.forEach(id => {
        const el = document.getElementById(id);
        if (el && el.value && el.value.trim() !== '') {
            filledCount++;
        }
    });

    // احتساب الوثائق الإلزامية
    const doc1 = document.getElementById('file_doc_id_card');
    const doc2 = document.getElementById('file_doc_cert');
    const doc3 = document.getElementById('file_doc_transcript');

    let docsFilled = 0;
    if (doc1 && doc1.files && doc1.files.length > 0) docsFilled++;
    if (doc2 && doc2.files && doc2.files.length > 0) docsFilled++;
    if (doc3 && doc3.files && doc3.files.length > 0) docsFilled++;

    const pledge = document.getElementById('reg_pledge_check');
    const pledgeFilled = pledge && pledge.checked ? 1 : 0;

    const totalWeight = fields.length + 3 + 1; // حقول + 3 وثائق إلزامية + إقرار
    const currentScore = filledCount + docsFilled + pledgeFilled;
    const percent = Math.min(100, Math.round((currentScore / totalWeight) * 100));

    const bar = document.getElementById('regProgressBar');
    const text = document.getElementById('regProgressPercentText');

    if (bar) bar.style.width = percent + '%';
    if (text) {
        if (percent >= 100) {
            text.textContent = '100% — الطلب جاهز للإرسال والاعتماد';
        } else {
            text.textContent = `${percent}% — أكمل البيانات والوثائق المطلوبة`;
        }
    }
}

// حساب المجموع التقديري تلقائياً من المعدل إن لم يُدخل يدوياً
function autoCalculateScore() {
    const avgInput = document.getElementById('reg_sec_avg');
    const scoreInput = document.getElementById('reg_total_score');
    if (!avgInput || !scoreInput) return;

    const avg = parseFloat(avgInput.value);
    if (!isNaN(avg) && avg > 0 && (!scoreInput.value || scoreInput.value == '709')) {
        // باليمن مجموع الثانوية العامة للقسم العلمي هو 800 أو 700
        const total = Math.round((avg / 100) * 800);
        scoreInput.value = total;
    }
}

// حفظ الطلب كمسودة
function submitApplicationAsDraft() {
    const draftInput = document.getElementById('regIsDraftInput');
    if (draftInput) draftInput.value = '1';

    // فحص الجامعة والتخصص فقط عند الحفظ كمسودة
    const univ = document.getElementById('reg_university_select');
    const spec = document.getElementById('reg_desired_spec_select');

    if (!univ || !univ.value) {
        alert('يرجى تحديد الجامعة أولاً لحفظ المسودة.');
        return;
    }
    if (!spec || !spec.value) {
        alert('يرجى تحديد التخصص أولاً لحفظ المسودة.');
        return;
    }

    // إرسال النموذج كمسودة
    const form = document.getElementById('studentRegistrationForm');
    if (form) {
        const event = new Event('submit', { cancelable: true });
        form.dispatchEvent(event);
    }
}

// إرسال النموذج عبر AJAX
function handleRegistrationSubmit(e) {
    e.preventDefault();

    @if(isset($isPreview) && $isPreview)
        alert('أنت في وضع المعاينة المخصص لإدارة الجامعة. تقديم طلبات التسجيل متاح لحسابات الطلبة فقط.');
        return;
    @endif
    @if(!auth()->check())
        if (confirm('تقديم طلب التسجيل الرسمي يتطلب تسجيل الدخول أو إنشاء حساب طالب لحفظ ومتابعة طلبك، هل ترغب في تسجيل الدخول الآن؟')) {
            window.location.href = "{{ route('login') }}";
        }
        return;
    @endif

    const form = e.target;
    const isDraft = document.getElementById('regIsDraftInput').value === '1';

    // التحقق من الإقرار إذا لم يكن مسودة
    if (!isDraft) {
        const pledge = document.getElementById('reg_pledge_check');
        if (pledge && !pledge.checked) {
            alert('يجب الموافقة على إقرار صحة البيانات والتعهد قبل إرسال الطلب.');
            return;
        }
    }

    const btnSubmit = document.getElementById('regBtnSubmitApp');
    const btnDraft = document.getElementById('regBtnSaveDraft');

    const originalSubmitText = btnSubmit ? btnSubmit.innerHTML : '';
    const originalDraftText = btnDraft ? btnDraft.innerHTML : '';

    if (isDraft && btnDraft) {
        btnDraft.disabled = true;
        btnDraft.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحفظ كمسودة...';
    } else if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري معالجة وإرسال الطلب...';
    }

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
            throw new Error(data.message || 'حدث خطأ أثناء معالجة الطلب');
        }
        return data;
    })
    .then(data => {
        closeRegistrationModal();

        // إشعار نجاح فوري
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: isDraft ? 'تم حفظ المسودة!' : 'تم إرسال طلبك بنجاح!',
                html: `
                    <p class="mb-2">${data.message || 'تم تقديم طلب الالتحاق بالجامعة بنجاح.'}</p>
                    <div class="p-2 border rounded bg-light font-monospace text-primary fw-bold">رقم الطلب: #${data.req_id}</div>
                    <small class="text-muted mt-2 d-block">يمكنك الآن متابعة حالة طلبك وقرار لجنة القبول مباشرة من لوحة تحكم الطالب.</small>
                `,
                confirmButtonText: 'الانتقال إلى طلباتي',
                confirmButtonColor: '#1e3a8a'
            }).then(() => {
                window.location.href = "{{ route('student.dashboard', ['tab' => 'requests']) }}";
            });
        } else {
            alert(data.message || 'تم تقديم طلب الالتحاق بنجاح!');
            window.location.href = "{{ route('student.dashboard', ['tab' => 'requests']) }}";
        }
    })
    .catch(error => {
        if (window.Swal) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه بشأن ضوابط التقديم',
                text: error.message || 'تعذر إرسال الطلب. يرجى مراجعة الحقول والمحاولة مجدداً.',
                confirmButtonText: 'حسناً، فهمت',
                confirmButtonColor: '#1e3a8a'
            });
        } else {
            alert(error.message || 'تعذر إرسال الطلب. يرجى التحقق من كافة الحقول الإلزامية.');
        }
    })
    .finally(() => {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalSubmitText;
        }
        if (btnDraft) {
            btnDraft.disabled = false;
            btnDraft.innerHTML = originalDraftText;
        }
        document.getElementById('regIsDraftInput').value = '0';
    });
}
</script>
