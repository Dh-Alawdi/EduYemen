<aside class="sidebar" id="sidebar" aria-label="القائمة الرئيسية">
    <!-- هوية الجامعة أعلى القائمة: شعار الجامعة واسم الجامعة فقط -->
    <div class="sidebar-user-card">
        <div class="user-avatar-wrap">
            <img src="{{ $university->logo_url }}" alt="{{ $university->university_name }}" class="user-avatar-img" onerror="this.src='{{ asset('logo.png') }}';">
        </div>
        <h4 class="user-name" title="{{ $university->university_name }}">{{ $university->university_name }}</h4>
    </div>

    <div class="sidebar-caption">القائمة الرئيسية</div>

    @php
        $isDashboardRoute = request()->routeIs('university.dashboard');
    @endphp

    <ul class="sidebar-nav">
        <li class="{{ ($isDashboardRoute && (!isset($activeTab) || $activeTab === 'overview')) ? 'active' : '' }}" data-tab="overview">
            <a href="{{ route('university.dashboard') }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('overview'); }" @endif>
                <i class="fa-solid fa-house"></i>
                <span>الرئيسية</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'profile') ? 'active' : '' }}" data-tab="profile">
            <a href="{{ route('university.dashboard', ['tab' => 'profile']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('profile'); }" @endif>
                <i class="fa-solid fa-building-columns"></i>
                <span>بيانات الجامعة</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'colleges') ? 'active' : '' }}" data-tab="colleges">
            <a href="{{ route('university.dashboard', ['tab' => 'colleges']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('colleges'); }" @endif>
                <i class="fa-solid fa-graduation-cap"></i>
                <span>الكليات والتخصصات</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'admission') ? 'active' : '' }}" data-tab="admission">
            <a href="{{ route('university.dashboard', ['tab' => 'admission']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('admission'); }" @endif>
                <i class="fa-solid fa-clipboard-check"></i>
                <span>القبول والتسجيل</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'applications') ? 'active' : '' }}" data-tab="applications">
            <a href="{{ route('university.dashboard', ['tab' => 'applications']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('applications'); }" @endif>
                <i class="fa-solid fa-file-signature"></i>
                <span>طلبات التسجيل</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'referrals') ? 'active' : '' }}" data-tab="referrals">
            <a href="{{ route('university.dashboard', ['tab' => 'referrals']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('referrals'); }" @endif>
                <i class="fa-solid fa-share-nodes"></i>
                <span>الإحالات</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'payments') ? 'active' : '' }}" data-tab="payments">
            <a href="{{ route('university.dashboard', ['tab' => 'payments']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('payments'); }" @endif>
                <i class="fa-solid fa-credit-card"></i>
                <span>طرق الدفع</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('university.advertisements.*') ? 'active' : '' }}" data-tab="advertisements">
            <a href="{{ route('university.advertisements.index') }}">
                <i class="fa-solid fa-rectangle-ad"></i>
                <span>الإعلانات الممولة</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'reports') ? 'active' : '' }}" data-tab="reports">
            <a href="{{ route('university.dashboard', ['tab' => 'reports']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('reports'); }" @endif>
                <i class="fa-solid fa-chart-pie"></i>
                <span>التقارير</span>
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'notifications') ? 'active' : '' }}" data-tab="notifications">
            <a href="{{ route('university.dashboard', ['tab' => 'notifications']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('notifications'); }" @endif>
                <i class="fa-solid fa-bell"></i>
                <span>الإشعارات</span>
                @if(isset($unreadNotifsCount) && $unreadNotifsCount > 0)
                    <span class="badge" style="background:#ef4444; color:#fff; font-size:10px; padding:2px 6px; border-radius:10px; margin-right:auto;">{{ $unreadNotifsCount }}</span>
                @endif
            </a>
        </li>

        <li class="{{ ($isDashboardRoute && isset($activeTab) && $activeTab === 'settings') ? 'active' : '' }}" data-tab="settings">
            <a href="{{ route('university.dashboard', ['tab' => 'settings']) }}" @if($isDashboardRoute) onclick="if(typeof switchTab==='function'){ event.preventDefault(); switchTab('settings'); }" @endif>
                <i class="fa-solid fa-gear"></i>
                <span>الإعدادات</span>
            </a>
        </li>
    </ul>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="setSidebarState(false)"></div>
