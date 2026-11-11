{{-- ====== القائمة الجانبية الخاصة بمستخدم الجامعة (تظهر في وضع المعاينة وتصفح النظام) ====== --}}
@php
    $loggedUniversity = Auth::check() && Auth::user()->university ? Auth::user()->university : null;
@endphp
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar-drawer" id="sidebarDrawer" aria-label="قائمة لوحة تحكم الجامعة">
    <!-- رأس القائمة: شعار الجامعة واسمها فقط -->
    <div class="sidebar-header" style="background: linear-gradient(135deg, #072644 0%, #0b3d6b 100%); padding: 16px;">
        <div class="d-flex align-items-center gap-2" style="min-width: 0;">
            @if($loggedUniversity)
                <img src="{{ $loggedUniversity->logo_url }}"
                     alt="{{ $loggedUniversity->university_name }}"
                     style="width: 36px; height: 36px; object-fit: contain; background: #fff; border-radius: 8px; padding: 2px; border: 1.5px solid var(--accent);"
                     onerror="this.src='/images/logo.png';">
                <span class="text-white fw-bold text-truncate" style="font-size: 0.95rem;">
                    {{ $loggedUniversity->university_name }}
                </span>
            @else
                <span class="text-white fw-bold">لوحة تحكم الجامعة</span>
            @endif
        </div>
        <button type="button" class="btn-close-sidebar" onclick="closeSidebar()" title="إغلاق القائمة" aria-label="إغلاق القائمة">✕</button>
    </div>

    <!-- بنر تنبيه وضع المعاينة في القائمة -->
    <div class="px-3 py-2 text-center" style="background: rgba(197, 155, 39, 0.15); border-bottom: 1px solid rgba(197, 155, 39, 0.3);">
        <small class="text-warning fw-bold d-block" style="font-size: 0.78rem;">
            <i class="bi bi-shield-lock-fill me-1"></i> وضع المعاينة نشط (للمعاينة فقط)
        </small>
    </div>

    <!-- روابط أقسام لوحة تحكم الجامعة -->
    <ul class="sidebar-nav-list">
        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard') }}" onclick="closeSidebar()">
                <i class="bi bi-house-door-fill text-accent fs-5"></i>
                <span>الرئيسية</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'profile']) }}" onclick="closeSidebar()">
                <i class="bi bi-building-fill text-accent fs-5"></i>
                <span>بيانات الجامعة</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'colleges']) }}" onclick="closeSidebar()">
                <i class="bi bi-mortarboard-fill text-accent fs-5"></i>
                <span>الكليات والتخصصات</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'admission']) }}" onclick="closeSidebar()">
                <i class="bi bi-clipboard-check-fill text-accent fs-5"></i>
                <span>القبول والتسجيل</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" onclick="closeSidebar()">
                <i class="bi bi-file-earmark-text-fill text-accent fs-5"></i>
                <span>طلبات التسجيل</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'referrals']) }}" onclick="closeSidebar()">
                <i class="bi bi-share-fill text-accent fs-5"></i>
                <span>الإحالات</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'payments']) }}" onclick="closeSidebar()">
                <i class="bi bi-credit-card-2-front-fill text-accent fs-5"></i>
                <span>طرق الدفع</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'announcements']) }}" onclick="closeSidebar()">
                <i class="bi bi-megaphone-fill text-accent fs-5"></i>
                <span>الإعلانات</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'reports']) }}" onclick="closeSidebar()">
                <i class="bi bi-pie-chart-fill text-accent fs-5"></i>
                <span>التقارير</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'notifications']) }}" onclick="closeSidebar()">
                <i class="bi bi-bell-fill text-accent fs-5"></i>
                <span>الإشعارات</span>
            </a>
        </li>

        <li class="sidebar-nav-item">
            <a href="{{ route('university.dashboard', ['tab' => 'settings']) }}" onclick="closeSidebar()">
                <i class="bi bi-gear-fill text-accent fs-5"></i>
                <span>الإعدادات</span>
            </a>
        </li>
    </ul>

    <!-- زر العودة الصريح للوحة التحكم في أسفل القائمة -->
    <div class="p-3 mt-auto border-top" style="border-color: rgba(255,255,255,0.1) !important;">
        <a href="{{ route('university.dashboard') }}" class="btn btn-warning w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px; padding: 10px;">
            <i class="bi bi-arrow-right"></i>
            <span>العودة إلى لوحة التحكم</span>
        </a>
    </div>
</aside>
