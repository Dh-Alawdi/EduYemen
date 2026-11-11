{{-- ملخص لوحة التحكم: الترحيب والوقت وقسم حول والبطاقات الظاهرة (صفحة الرئيسية) --}}
<div class="tab-pane-content active" id="overviewTabContent">

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
                <a href="{{ route('university.dashboard', ['tab' => 'profile']) }}" onclick="event.preventDefault(); switchTab('profile');" class="summary-stat" title="بيانات الجامعة">
                    <div class="summary-stat-icon stat-blue">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">بيانات الجامعة</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>

                <!-- 2. الكليات والتخصصات -->
                <a href="{{ route('university.dashboard', ['tab' => 'colleges']) }}" onclick="event.preventDefault(); switchTab('colleges');" class="summary-stat" title="الكليات والتخصصات">
                    <div class="summary-stat-icon stat-teal">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">الكليات والتخصصات</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>

                <!-- 3. طرق الدفع -->
                <a href="{{ route('university.dashboard', ['tab' => 'payments']) }}" onclick="event.preventDefault(); switchTab('payments');" class="summary-stat" title="طرق الدفع">
                    <div class="summary-stat-icon stat-gold">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div class="summary-stat-info">
                        <span class="summary-stat-title">طرق الدفع</span>
                    </div>
                    <i class="fa-solid fa-angle-left summary-stat-arrow" aria-hidden="true"></i>
                </a>

                <!-- 4. التقارير -->
                <a href="{{ route('university.dashboard', ['tab' => 'reports']) }}" onclick="event.preventDefault(); switchTab('reports');" class="summary-stat" title="التقارير">
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

                    <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" onclick="event.preventDefault(); switchTab('applications');" class="nav-action-item" data-search="عرض طلبات التسجيل طلبات التسجيل الالتحاق الطلاب">
                        <div class="nav-action-icon"><i class="fa-solid fa-file-signature"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">عرض طلبات التسجيل</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" onclick="event.preventDefault(); switchTab('applications');" class="nav-action-item" data-search="الطلبات الجديدة القبول التسجيل طلبات حديثة">
                        <div class="nav-action-icon"><i class="fa-solid fa-folder-plus"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">الطلبات الجديدة</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'referrals']) }}" onclick="event.preventDefault(); switchTab('referrals');" class="nav-action-item" data-search="الإحالات أوراق الطلاب الكليات الأقسام">
                        <div class="nav-action-icon"><i class="fa-solid fa-share-nodes"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">الإحالات</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'announcements']) }}" onclick="event.preventDefault(); switchTab('announcements');" class="nav-action-item" data-search="الإعلانات النشطة الأخبار التعميمات">
                        <div class="nav-action-icon"><i class="fa-solid fa-bullhorn"></i></div>
                        <div class="nav-action-content">
                            <div class="nav-action-title">الإعلانات النشطة</div>
                        </div>
                        <div class="nav-action-arrow"><i class="fa-solid fa-chevron-left"></i></div>
                    </a>

                    <a href="{{ route('university.dashboard', ['tab' => 'notifications']) }}" onclick="event.preventDefault(); switchTab('notifications');" class="nav-action-item" data-search="تنبيهات مهمة الإشعارات الملاحظات العاجلة">
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
