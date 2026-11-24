{{-- ملخص لوحة التحكم: الترحيب والوقت وقسم حول والبطاقات الظاهرة (صفحة الرئيسية) --}}
<div class="tab-pane-content active" id="overviewTabContent">

    @if(isset($activeModerations) && $activeModerations->isNotEmpty())
    <div class="alert alert-danger mb-4" style="background:#fef2f2; border:1.5px solid #fecaca; border-radius:14px; padding:18px 22px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; box-shadow:0 4px 12px rgba(220,38,38,0.08);">
        <div style="display:flex; align-items:center; gap:14px;">
            <div style="width:46px; height:46px; border-radius:12px; background:#fee2e2; color:#dc2626; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">
                <i class="fa-solid fa-shield-virus"></i>
            </div>
            <div>
                <strong style="color:#991b1b; font-size:15px; display:block; margin-bottom:3px;">
                    تنبيه رقابي عاجل: تم إيقاف ظهور بعض عناصر الجامعة في المنصة
                </strong>
                <span style="color:#b91c1c; font-size:13px; line-height:1.5;">
                    أصدرت إدارة الرقابة بالمنظومة قرارات بحجب ({{ $activeModerations->pluck('item_title')->join('، ') }}). يرجى مراجعة التوجيهات وتصحيح البيانات لإعادة الاعتماد.
                </span>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-sm" style="background:#dc2626; color:#ffffff; font-weight:700; border-radius:8px; padding:9px 18px; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:8px; box-shadow:0 3px 10px rgba(220,38,38,0.25); transition:all 0.2s;" onclick="openModerationCorrectionModal()">
                <i class="fa-solid fa-file-shield"></i>
                <span>استعراض التوجيهات وتصحيح البيانات</span>
            </button>
        </div>
    </div>
    @endif

    <div class="overview-grid-layout">
        
        <!-- الجانب الأول: حاوية الترحيب والوقت، يليه مباشرة قسم "حول" -->
        <div class="overview-intro-column">
            <!-- حاوية الترحيب بالجامعة مع الساعة والتاريخ الحي -->
            <div class="summary-welcome">
                <div class="summary-welcome-content">
                    <h2 id="dashboardSummaryTitle">
                        مرحبًا بك في لوحة تحكم الجامعة
                    </h2>
                    <p>
                        وصول سريع إلى بيانات الجامعة وإعداداتها الأكاديمية والإدارية.
                    </p>

                    <div id="universityDashboardDateTime">
                        <span id="universityDashboardDate">
                            <i class="fa-regular fa-calendar-days"></i>
                            جاري تحميل التاريخ...
                        </span>

                        <span id="universityDashboardTime">
                            <i class="fa-regular fa-clock"></i>
                            --:--
                        </span>
                    </div>
                </div>

                <div class="summary-welcome-icon" aria-hidden="true">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
            </div>

            <!-- حاوية قسم "حول" (معروضة بعد الحاوية التي فيها الترحيب والوقت مباشرة) -->
            <section class="platform-intro-block" id="aboutSection" aria-labelledby="introTitle">
                <div class="intro-header-wrapper">
                    <div class="intro-copy">
                        <h2 class="intro-title" id="introTitle" style="color: #e4b75b;">خدمات الجامعة عبر EduYemen</h2>
                        <p class="intro-description">
                            توفر منصة EduYemen للجامعات بيئة موحدة لإدارة بياناتها الأكاديمية، وتحديث الكليات والتخصصات والخطط الدراسية وشروط القبول والرسوم، لعرضها للطلاب الباحثين عن جامعات وتخصصات مناسبة.
                            <hr style="margin: 10px;" class="c1">
                            كما يوفر للجامعة متابعة طلبات التسجيل والإحالات الطلابية، إلى جانب نشر الإعلانات الجامعية والوصول إلى الطلاب المهتمين ببرامجها الأكاديمية.
                        </p>
                    </div>
                    
                    <a href="javascript:void(0)" class="intro-mark-link" title="شعار النظام" aria-label="شعار النظام">
                        <img src="{{ asset('logo.png') }}" alt="شعار النظام">
                    </a>
                </div>

                <div class="intro-meta">
                    <span><i class="fa-solid fa-shield-halved"></i> خدمات جامعية موثوقة</span>
                    <span><i class="fa-solid fa-bolt"></i> وصول سهل وسريع</span>
                </div>
            </section>
        </div>

        <!-- الجانب المقابل: البطاقات الظاهرة في الصفحة الرئيسية -->
        <div class="overview-cards-column">
            <!-- شبكة بطاقات التحكم السريعة الأربع (بيانات الجامعة، الكليات والتخصصات، طرق الدفع، التقارير) -->
            <div class="summary-stats">
                <!-- 1. بيانات الجامعة -->
                <a href="{{ route('university.dashboard', ['tab' => 'profile']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('profile'); }" class="summary-stat" title="بيانات الجامعة">
                    <div class="summary-stat-icon stat-blue">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">بيانات الجامعة</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>

                <!-- 2. الكليات والتخصصات -->
                <a href="{{ route('university.dashboard', ['tab' => 'colleges']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('colleges'); }" class="summary-stat" title="الكليات والتخصصات">
                    <div class="summary-stat-icon stat-teal">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">الكليات والتخصصات</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>

                <!-- 3. طرق الدفع -->
                <a href="{{ route('university.dashboard', ['tab' => 'payments']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('payments'); }" class="summary-stat" title="طرق الدفع">
                    <div class="summary-stat-icon stat-gold">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">طرق الدفع</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>

                <!-- 4. التقارير -->
                <a href="{{ route('university.dashboard', ['tab' => 'reports']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('reports'); }" class="summary-stat" title="التقارير">
                    <div class="summary-stat-icon stat-purple">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">التقارير</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>
            </div>

            <!-- حاوية البطاقات الظاهرة لخدمات النظام -->
            <section class="services-container" aria-labelledby="servicesTitle">
                <div class="navigation-actions-list" id="serviceCards">

                    <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('applications'); }" class="nav-action-item" data-search="عرض طلبات التسجيل طلبات التسجيل الالتحاق الطلاب">
                        <div class="nav-action-icon"><i class="fa-solid fa-file-signature"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">عرض طلبات التسجيل</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('applications'); }" class="nav-action-item" data-search="الطلبات الجديدة القبول التسجيل طلبات حديثة">
                        <div class="nav-action-icon"><i class="fa-solid fa-folder-plus"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">الطلبات الجديدة</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'referrals']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('referrals'); }" class="nav-action-item" data-search="الإحالات أوراق الطلاب الكليات الأقسام">
                        <div class="nav-action-icon"><i class="fa-solid fa-share-nodes"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">الإحالات</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.advertisements.index') }}" class="nav-action-item" data-search="الإعلانات الممولة الحملات الترويجية الباقات">
                        <div class="nav-action-icon"><i class="fa-solid fa-rectangle-ad"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">الإعلانات الممولة</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'notifications']) }}" onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('notifications'); }" class="nav-action-item" data-search="تنبيهات مهمة الإشعارات الملاحظات العاجلة">
                        <div class="nav-action-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">تنبيهات مهمة</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <!-- بطاقة معاينة صفحة الجامعة للطالب (بعد تنبيهات مهمة مباشرة) -->
                    <a href="{{ route('universities.show', $university->university_id) }}" class="nav-action-item nav-action-preview" data-search="معاينة صفحة الجامعة للطالب عرض ملف الجامعة استعراض الصفحة العامة" title="معاينة صفحة الجامعة كما تظهر للطلبة">
                        <div class="nav-action-icon"><i class="fa-solid fa-eye"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">معاينة صفحة الجامعة للطالب</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <div class="no-results" id="noResults">لا توجد خدمات تطابق عبارة البحث.</div>
                </div>
            </section>
        </div>

    </div>

</div>
