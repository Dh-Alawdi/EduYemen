<header class="top-header" id="topHeader">
    <div class="header-right-side">
        <!-- زر فتح/إغلاق القائمة الجانبية -->
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" aria-label="إغلاق القائمة الجانبية" title="إغلاق القائمة الجانبية">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- شعار المنصة واسمها فقط -->
        <div class="header-brand">
            <a href="{{ route('university.dashboard') }}" class="brand-container" title="منصة EduYemen">
                <img src="{{ asset('logo.png') }}" alt="EduYemen" class="brand-logo-img" onerror="this.src='/images/logo.png';">
                <span class="brand-copy">
                    <span class="brand-name">Edu<span style="color: var(--gold);">Yemen</span></span>
                </span>
            </a>
        </div>
    </div>

    <!-- شريط البحث -->
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
        <input type="search" id="pageSearch" placeholder="ابحث في خدمات ومحتوى المنصة..." autocomplete="off" aria-label="البحث">
        <button type="button" class="search-clear" id="clearSearch" aria-label="مسح البحث"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <!-- الإجراءات في الترويسة: الإشعارات، تسجيل الخروج -->
    <div class="header-actions">
        <!-- أيقونة الإشعارات -->
        <button type="button" class="btn-icon notif-btn" onclick="switchTab('notifications')" title="الإشعارات" aria-label="الإشعارات">
            <i class="fa-solid fa-bell"></i>
            @php $bellCount = (isset($unreadNotifsCount) && $unreadNotifsCount > 0) ? $unreadNotifsCount : ((isset($stats['pending_requests']) && $stats['pending_requests'] > 0) ? $stats['pending_requests'] : 0); @endphp
            @if($bellCount > 0)
                <span class="notif-badge" style="{{ isset($unreadNotifsCount) && $unreadNotifsCount > 0 ? 'background:#ef4444;' : '' }}">{{ $bellCount }}</span>
            @endif
        </button>

        <!-- أيقونة تسجيل الخروج -->
        <form method="POST" action="{{ route('logout') }}" class="d-inline logout-form">
            @csrf
            <button type="submit" class="btn-logout" title="تسجيل الخروج" aria-label="تسجيل الخروج">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="logout-label">تسجيل الخروج</span>
            </button>
        </form>
    </div>
</header>
