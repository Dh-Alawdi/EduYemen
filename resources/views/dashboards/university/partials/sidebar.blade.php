<aside class="sidebar" id="sidebar" aria-label="القائمة الرئيسية">
    <!-- هوية الجامعة أعلى القائمة: شعار الجامعة واسم الجامعة فقط -->
    <div class="sidebar-user-card">
        <div class="user-avatar-wrap">
            <img src="{{ $university->logo_url }}" alt="{{ $university->university_name }}" class="user-avatar-img" onerror="this.src='{{ asset('logo.png') }}';">
        </div>
        <h4 class="user-name" title="{{ $university->university_name }}">{{ $university->university_name }}</h4>
    </div>

    <div class="sidebar-caption">القائمة الرئيسية</div>

    <ul class="sidebar-nav">
        <li class="{{ (!isset($activeTab) || $activeTab === 'overview') ? 'active' : '' }}" data-tab="overview">
            <a href="{{ route('university.dashboard') }}" onclick="event.preventDefault(); switchTab('overview');">
                <i class="fa-solid fa-house"></i>
                <span>الرئيسية</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'profile') ? 'active' : '' }}" data-tab="profile">
            <a href="{{ route('university.dashboard', ['tab' => 'profile']) }}" onclick="event.preventDefault(); switchTab('profile');">
                <i class="fa-solid fa-building-columns"></i>
                <span>بيانات الجامعة</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'colleges') ? 'active' : '' }}" data-tab="colleges">
            <a href="{{ route('university.dashboard', ['tab' => 'colleges']) }}" onclick="event.preventDefault(); switchTab('colleges');">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>الكليات والتخصصات</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'admission') ? 'active' : '' }}" data-tab="admission">
            <a href="{{ route('university.dashboard', ['tab' => 'admission']) }}" onclick="event.preventDefault(); switchTab('admission');">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>القبول والتسجيل</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'applications') ? 'active' : '' }}" data-tab="applications">
            <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" onclick="event.preventDefault(); switchTab('applications');">
                <i class="fa-solid fa-file-signature"></i>
                <span>طلبات التسجيل</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'referrals') ? 'active' : '' }}" data-tab="referrals">
            <a href="{{ route('university.dashboard', ['tab' => 'referrals']) }}" onclick="event.preventDefault(); switchTab('referrals');">
                <i class="fa-solid fa-share-nodes"></i>
                <span>الإحالات</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'payments') ? 'active' : '' }}" data-tab="payments">
            <a href="{{ route('university.dashboard', ['tab' => 'payments']) }}" onclick="event.preventDefault(); switchTab('payments');">
                <i class="fa-solid fa-credit-card"></i>
                <span>طرق الدفع</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'announcements') ? 'active' : '' }}" data-tab="announcements">
            <a href="{{ route('university.dashboard', ['tab' => 'announcements']) }}" onclick="event.preventDefault(); switchTab('announcements');">
                <i class="fa-solid fa-bullhorn"></i>
                <span>الإعلانات</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'reports') ? 'active' : '' }}" data-tab="reports">
            <a href="{{ route('university.dashboard', ['tab' => 'reports']) }}" onclick="event.preventDefault(); switchTab('reports');">
                <i class="fa-solid fa-chart-pie"></i>
                <span>التقارير</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'notifications') ? 'active' : '' }}" data-tab="notifications">
            <a href="{{ route('university.dashboard', ['tab' => 'notifications']) }}" onclick="event.preventDefault(); switchTab('notifications');">
                <i class="fa-solid fa-bell"></i>
                <span>الإشعارات</span>
            </a>
        </li>

        <li class="{{ (isset($activeTab) && $activeTab === 'settings') ? 'active' : '' }}" data-tab="settings">
            <a href="{{ route('university.dashboard', ['tab' => 'settings']) }}" onclick="event.preventDefault(); switchTab('settings');">
                <i class="fa-solid fa-gear"></i>
                <span>الإعدادات</span>
            </a>
        </li>
    </ul>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="setSidebarState(false)"></div>
