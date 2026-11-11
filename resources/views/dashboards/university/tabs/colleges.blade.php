{{-- تبويب إدارة الكليات والتخصصات المطور والمبتكر --}}
<div class="tab-pane-content" id="collegesTabContent">

    <!-- شريط الرأس والتحكم للكليات والتخصصات -->
    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">إدارة الكليات والتخصصات الأكاديمية</h2>
            <p class="tab-subtitle">إدارة البرامج والخطط الدراسية، شروط ومعدلات القبول، والوثائق المطلوبة لكل تخصص بكل مرونة.</p>
        </div>

        <div class="banner-actions">
            <button type="button" class="btn btn-navy btn-sm" onclick="openCollegeModal()">
                <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                <span>إضافة كلية جديدة</span>
            </button>
            <button type="button" class="btn btn-gold btn-sm" onclick="openSpecializationModal()">
                <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                <span>إضافة تخصص أكاديمي</span>
            </button>
        </div>
    </div>

    <!-- شريط اختيار الكلية والتخصص النشط -->
    <div class="card-box mb-4 filter-selector-bar">
        <div class="filter-controls-grid">
            <div class="filter-select-group">
                <label for="collegeFilterSelect" class="filter-label">
                    <svg class="ico text-blue" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <span>اختر الكلية:</span>
                </label>
                <select id="collegeFilterSelect" class="form-control" onchange="onCollegeSelectChange(this.value)">
                    <option value="" selected disabled>-- اختر الكلية أولاً --</option>
                    @forelse($university->colleges as $college)
                        <option value="{{ $college->college_id }}">{{ $college->college_name }} ({{ $college->specializations->count() }} تخصص)</option>
                    @empty
                        <option value="" disabled>لا توجد كليات مضافة حتى الآن</option>
                    @endforelse
                </select>
            </div>

            <div class="filter-select-group">
                <label for="specialtyFilterSelect" class="filter-label">
                    <svg class="ico text-teal" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    <span>اختر التخصص الأكاديمي:</span>
                </label>
                <select id="specialtyFilterSelect" class="form-control" onchange="onSpecialtySelectChange(this.value)" disabled>
                    <option value="" selected disabled>-- يرجى اختيار الكلية أولاً --</option>
                </select>
            </div>

            <div class="filter-actions-group">
                <button type="button" class="btn btn-outline-navy btn-sm" id="btnEditCurrentCollege" onclick="editSelectedCollege()" title="تعديل اسم أو بيانات الكلية الحالية" disabled style="opacity: 0.55; pointer-events: none;">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>تعديل الكلية</span>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" id="btnDeleteCurrentCollege" onclick="deleteSelectedCollege()" title="حذف الكلية الحالية" disabled style="opacity: 0.55; pointer-events: none;">
                    <svg class="ico" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    <span>حذف الكلية</span>
                </button>
            </div>
        </div>
    </div>

    <!-- رسالة توجيهية تظهر للمستخدم لتحديد الكلية والتخصص قبل عرض التفاصيل -->
    <div class="card-box text-center py-5" id="specialtyEmptyPrompt" style="border: 2px dashed #cbd5e1; background: #fafbfc; border-radius: 14px; padding: 3.5rem 1.5rem; margin-bottom: 2rem;">
        <div style="width: 76px; height: 76px; margin: 0 auto 1.25rem; background: rgba(30, 58, 138, 0.08); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg class="ico text-navy" style="width: 38px; height: 38px;" viewBox="0 0 24 24">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </svg>
        </div>
        <h3 class="fw-bold text-navy mb-2" id="specialtyPromptTitle" style="font-size: 1.25rem;">يرجى تحديد الكلية والتخصص الأكاديمي</h3>
        <p class="text-muted mb-0" id="specialtyPromptDesc" style="max-width: 540px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
            قم باختيار الكلية أولاً من القائمة أعلاه، ثم حدد التخصص الأكاديمي لاستعراض بياناته، شروط القبول والتسجيل، والوثائق المطلوبة.
        </p>
    </div>

    <!-- قسم تفاصيل التخصص وبقية الصفحة (يظهر فقط بعد تحديد الكلية والتخصص) -->
    <div id="specialtyDetailsSection" style="display: none;">

    <!-- كارت الهيرو الأكاديمي للتخصص المحدد حالياً (تصميم عصري مبتكر يجمع بيانات البرنامج) -->
    <div class="spec-hero-card mb-4" id="specHeroCard">
        <div class="spec-hero-main">
            <div class="spec-hero-title-box">
                <div class="spec-hero-badges">
                    <span class="badge-college-tag" id="heroCollegeTag">اسم الكلية</span>
                    <span class="badge-status-tag" id="heroStatusTag">نشط</span>
                    <span class="badge-lang-tag" id="heroLanguageTag">العربية</span>
                </div>
                <h3 class="spec-hero-title" id="heroSpecialtyTitle">اختر تخصصاً لاستعراض بياناته</h3>
                <p class="spec-hero-desc" id="heroSpecialtyDesc">وصف التخصص ومجالات عمل الخريجين والمهارات المكتسبة.</p>
            </div>

            <div class="spec-hero-actions">
                <button type="button" class="btn btn-white btn-sm" id="btnEditSpecHero" onclick="editSelectedSpecialty()">
                    <svg class="ico text-navy" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>تعديل التخصص</span>
                </button>
                <button type="button" class="btn btn-danger-soft btn-sm" id="btnDeleteSpecHero" onclick="deleteSelectedSpecialty()">
                    <svg class="ico" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    <span>حذف</span>
                </button>
            </div>
        </div>

        <!-- شريط المقاييس الأكاديمية السريعة للتخصص -->
        <div class="spec-hero-metrics">
            <div class="hero-metric-item">
                <span class="metric-label">مدة الدراسة</span>
                <span class="metric-val" id="heroDurationVal">4 سنوات</span>
            </div>
            <div class="hero-metric-item">
                <span class="metric-label">الرسوم السنوية</span>
                <span class="metric-val text-gold" id="heroFeesVal">$0</span>
            </div>
            <div class="hero-metric-item">
                <span class="metric-label">رسوم التسجيل</span>
                <span class="metric-val text-navy" id="heroRegFeeVal">0 ر.ي</span>
            </div>
            <div class="hero-metric-item">
                <span class="metric-label">الحد الأدنى للقبول</span>
                <span class="metric-val text-teal" id="heroMinAvgVal">70.0%</span>
            </div>
            <div class="hero-metric-item">
                <span class="metric-label">حالة القبول</span>
                <span class="metric-val text-success" id="heroAdmStatusVal">مفتوح</span>
            </div>
            <div class="hero-metric-item">
                <span class="metric-label">الوثائق المطلوبة</span>
                <span class="metric-val" id="heroDocsCountVal">3 وثائق</span>
            </div>
        </div>
    </div>

    <!-- شريط التبويبات الفرعية المبتكر للتنقل بين أبعاد التخصص الأربعة -->
    <div class="subtabs-navigation-bar" role="tablist">
        <!-- 1. البيانات الأساسية -->
        <button type="button" class="subtab-nav-btn active" data-subtab="basic" onclick="switchSpecialtySubtab('basic')" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            <span>البيانات الأساسية</span>
        </button>

        <!-- 2. القبول والتسجيل -->
        <button type="button" class="subtab-nav-btn" data-subtab="admission" onclick="switchSpecialtySubtab('admission')" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <span>القبول والتسجيل</span>
        </button>

        <!-- 3. الوثائق المطلوبة للتسجيل -->
        <button type="button" class="subtab-nav-btn" data-subtab="documents" onclick="switchSpecialtySubtab('documents')" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M9 12h6M9 16h6M4 4h16v16H4z"/></svg>
            <span>الوثائق المطلوبة للتسجيل</span>
            <span class="subtab-pill-badge" id="badgeDocsCount">0</span>
        </button>

        <!-- 4. الخطة الدراسية -->
        <button type="button" class="subtab-nav-btn" data-subtab="plans" onclick="switchSpecialtySubtab('plans')" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <span>الخطة الدراسية</span>
            <span class="subtab-pill-badge" id="badgePlansCount">0</span>
        </button>
    </div>

    <!-- محتويات التبويبات الفرعية الأربعة -->
    <div class="subtabs-container">

        <!-- 1. محتوى التبويب الفرعي: البيانات الأساسية -->
        <div class="subtab-pane active" id="pane-basic">
            <form id="formSpecialtyBasic" onsubmit="saveSpecialtyBasic(event)">
                <div class="card-box">
                    <div class="card-box-header">
                        <div class="header-icon-badge bg-blue-subtle text-blue">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                        </div>
                        <div>
                            <h4 class="card-box-title">تعديل البيانات الأساسية للبرنامج</h4>
                            <p class="card-box-desc">اسم التخصص، لغة التدريس، مدة الدراسة، الرسوم، وحالة التفعيل</p>
                        </div>
                    </div>

                    <div class="mobile-two-col-grid">
                        <div class="form-group">
                            <label for="basic_spec_name" class="form-label required">اسم التخصص الأكاديمي</label>
                            <input type="text" id="basic_spec_name" name="specialization_name" class="form-control" required placeholder="مثال: هندسة البرمجيات والذكاء الاصطناعي">
                        </div>

                        <div class="form-group">
                            <label for="basic_spec_college" class="form-label required">الكلية التابع لها</label>
                            <select id="basic_spec_college" name="college_id" class="form-control" disabled>
                                @foreach($university->colleges as $c)
                                    <option value="{{ $c->college_id }}">{{ $c->college_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="basic_spec_duration" class="form-label required">مدة الدراسة (بالسنوات)</label>
                            <select id="basic_spec_duration" name="duration_years" class="form-control" required>
                                <option value="2">سنتان (دبلوم)</option>
                                <option value="3">3 سنوات</option>
                                <option value="4" selected>4 سنوات (بكالوريوس)</option>
                                <option value="5">5 سنوات (هندسة / طب أسنان / صيدلة)</option>
                                <option value="6">6 سنوات (طب بشري)</option>
                                <option value="7">7 سنوات (سنة امتياز)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="basic_spec_language" class="form-label required">لغة التدريس</label>
                            <select id="basic_spec_language" name="language" class="form-control" required>
                                <option value="العربية">اللغة العربية</option>
                                <option value="الإنجليزية">اللغة الإنجليزية</option>
                                <option value="العربية والإنجليزية">العربية والإنجليزية (مزدوج)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="basic_spec_fees" class="form-label required">الرسوم الدراسية السنوية ($ أو ر.ي)</label>
                            <input type="number" id="basic_spec_fees" name="tuition_fees" class="form-control" required min="0" step="any" placeholder="مثال: 1200">
                        </div>

                        <div class="form-group">
                            <label for="basic_spec_status" class="form-label required">حالة التخصص</label>
                            <select id="basic_spec_status" name="status" class="form-control" required>
                                <option value="نشط">نشط (متاح للتسجيل)</option>
                                <option value="مغلق">مغلق مؤقتاً</option>
                                <option value="معلق">معلق للمراجعة</option>
                            </select>
                        </div>

                        <div class="form-group col-span-full-mobile">
                            <label for="basic_spec_desc" class="form-label">الوصف الأكاديمي والمهني للتخصص</label>
                            <textarea id="basic_spec_desc" name="description" rows="3" class="form-control" placeholder="اكتب نبذة عن التخصص ومستقبل الخريجين وسوق العمل..."></textarea>
                        </div>
                    </div>

                    <div class="form-actions-bar">
                        <button type="submit" class="btn btn-navy btn-md" id="btnSaveBasicSpec">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                            <span>حفظ التعديلات الأساسية</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. محتوى التبويب الفرعي: القبول والتسجيل -->
        <div class="subtab-pane" id="pane-admission">
            <form id="formSpecialtyAdmission" onsubmit="saveSpecialtyAdmission(event)">
                <div class="card-box">
                    <div class="card-box-header">
                        <div class="header-icon-badge bg-teal-subtle text-teal">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        </div>
                        <div>
                            <h4 class="card-box-title">شروط وضوابط القبول والتسجيل والرسوم</h4>
                            <p class="card-box-desc">الحد الأدنى لمعدل الثانوية، سنة التخرج، الرسوم الدراسية ورسوم التسجيل، وفترة القبول وفق ملف التوثيق</p>
                        </div>
                    </div>

                    <div class="mobile-two-col-grid">
                        <div class="form-group">
                            <label for="adm_min_avg" class="form-label required">الحد الأدنى لمعدل الثانوية العامة (%)</label>
                            <input type="number" id="adm_min_avg" name="minimum_average" class="form-control" required min="50" max="100" step="0.1" placeholder="مثال: 75.0">
                        </div>

                        <div class="form-group">
                            <label for="adm_last_year" class="form-label required">آخر سنة تخرج ثانوية مقبولة</label>
                            <input type="number" id="adm_last_year" name="secondary_last_year" class="form-control" required min="2000" max="2035" placeholder="مثال: 2021">
                        </div>

                        <div class="form-group">
                            <label for="adm_tuition_fees" class="form-label required">الرسوم الدراسية السنوية ($ أو ر.ي)</label>
                            <input type="number" id="adm_tuition_fees" name="tuition_fees" class="form-control" required min="0" step="any" placeholder="مثال: 1200">
                        </div>

                        <div class="form-group">
                            <label for="adm_registration_fee" class="form-label required">رسوم التسجيل / فتح الملف (ر.ي أو $)</label>
                            <input type="number" id="adm_registration_fee" name="registration_fee" class="form-control" required min="0" step="any" placeholder="مثال: 5000">
                        </div>

                        <div class="form-group">
                            <label for="adm_start_date" class="form-label">تاريخ بدء فترة القبول والتسجيل</label>
                            <input type="date" id="adm_start_date" name="admission_start_date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="adm_end_date" class="form-label">تاريخ انتهاء فترة القبول والتسجيل</label>
                            <input type="date" id="adm_end_date" name="admission_end_date" class="form-control">
                        </div>

                        <div class="form-group col-span-full-mobile">
                            <label for="adm_status" class="form-label required">حالة فترة القبول والتسجيل</label>
                            <select id="adm_status" name="admission_status" class="form-control" required>
                                <option value="مفتوح">مفتوح للتقديم والتسجيل</option>
                                <option value="مغلق">مغلق حالياً</option>
                                <option value="قريباً">يفتح قريباً</option>
                            </select>
                        </div>

                        <div class="form-group col-span-full-mobile">
                            <label for="adm_criteria_text" class="form-label required">تفاصيل وشروط القبول ومعايير المفاضلة (admission_data)</label>
                            <textarea id="adm_criteria_text" name="admission_data" rows="4" class="form-control" required placeholder="اذكر شروط القبول كاختبارات المفاضلة، المقابلة الشخصية، والفحص الطبي إن وجد..."></textarea>
                        </div>

                        <div class="form-group col-span-full-mobile">
                            <label for="adm_desc_text" class="form-label">ملاحظات وإرشادات إضافية للطالب (description)</label>
                            <textarea id="adm_desc_text" name="description" rows="2" class="form-control" placeholder="تعليمات إضافية حول التنسيق وتأكيد المقعد وتوريد الرسوم..."></textarea>
                        </div>
                    </div>

                    <div class="form-actions-bar">
                        <button type="submit" class="btn btn-teal btn-md" id="btnSaveAdmissionSpec">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                            <span>حفظ بيانات القبول والرسوم للتخصص</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 3. محتوى التبويب الفرعي: الوثائق المطلوبة للتسجيل -->
        <div class="subtab-pane" id="pane-documents">
            <div class="card-box">
                <div class="card-box-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon-badge bg-gold-subtle text-gold">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M9 12h6M9 16h6M4 4h16v16H4z"/></svg>
                        </div>
                        <div>
                            <h4 class="card-box-title">قائمة الوثائق المطلوبة للتسجيل</h4>
                            <p class="card-box-desc">المستندات التي يطلب من الطالب إرفاقها عند التقديم على هذا التخصص</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-gold btn-sm" onclick="openAddDocModal()">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        <span>إضافة وثيقة مطلوبة</span>
                    </button>
                </div>

                <div class="documents-dynamic-list" id="documentsListContainer">
                    <!-- يتم ملؤها ديناميكياً بواسطة جافاسكريبت بحسب التخصص -->
                </div>

                <div class="form-actions-bar mt-3">
                    <button type="button" class="btn btn-navy btn-md" onclick="saveCurrentDocumentsList()">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                        <span>اعتماد وحفظ قائمة الوثائق</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. محتوى التبويب الفرعي: الخطة الدراسية -->
        <div class="subtab-pane" id="pane-plans">
            <div class="card-box">
                <div class="card-box-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon-badge bg-purple-subtle text-purple">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="card-box-title">الخطط والمسارات الدراسية للتخصص</h4>
                            <p class="card-box-desc">إدارة الخطط الدراسية ومقررات الفصول، وإضافة مسارات أو خطط بديلة للبرنامج الأكاديمي</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-purple btn-sm" onclick="openAddPlanModal()">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        <span>+ إضافة خطة دراسية أخرى</span>
                    </button>
                </div>

                <!-- حاوية الخطط الديناميكية -->
                <div class="plans-dynamic-list" id="plansListContainer">
                    <div class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-purple me-2" role="status"></div>
                        <span>جاري تحميل الخطط الدراسية...</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </div>{{-- نهاية #specialtyDetailsSection --}}

</div>
