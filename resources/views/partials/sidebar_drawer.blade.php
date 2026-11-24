{{-- ====== القائمة الجانبية (Sidebar Drawer) والقائمة الفرعية للجامعات ====== --}}
@php
    $sidebarUniversities = \App\Models\University::where('status', 'نشطة')->orderBy('university_name')->get();
@endphp
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- 1. القائمة الجانبية الرئيسية --}}
<aside class="sidebar-drawer" id="sidebarDrawer" aria-label="القائمة الجانبية">
    <div class="sidebar-header">
        <div class="d-flex align-items-center gap-2">
            <img src="/images/logo.png"
                 onerror="this.onerror=null; this.src='/images/eduyemen-logo.svg';"
                 style="width: 34px; height: 34px; object-fit: contain; background: transparent; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));"
                 alt="EduYemen">
            <div class="sidebar-title">
                Edu<span>Yemen</span>
            </div>
        </div>
        <button type="button" class="btn-close-sidebar" onclick="closeSidebar()" title="إغلاق القائمة" aria-label="إغلاق القائمة">✕</button>
    </div>

    <ul class="sidebar-nav-list">
        
        {{-- الرئيسية: انتقال مباشر --}}
        <li class="sidebar-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" onclick="closeSidebar()">
                <i class="bi bi-house-door-fill text-accent fs-5"></i>
                <span>الرئيسية</span>
            </a>
        </li>

        {{-- دليل الجامعات: رابط أساسي للانتقال وبجانبه سهم القائمة الفرعية بمساحة حجمه فقط --}}
        <li class="sidebar-nav-item {{ request()->routeIs('universities.*') ? 'active' : '' }}">
            <div class="sidebar-univ-row">
                <a href="{{ route('universities.index') }}" class="sidebar-univ-main-link" onclick="closeSidebar()">
                    <i class="bi bi-mortarboard-fill text-accent fs-5"></i>
                    <span>دليل الجامعات</span>
                </a>
                <button type="button" 
                        class="btn-toggle-univ-subdrawer" 
                        onclick="toggleUnivSubDrawer(event)" 
                        title="عرض قائمة الجامعات المعتمدة" 
                        aria-label="عرض قائمة الجامعات المعتمدة">
                    <i class="bi bi-chevron-left" id="univSubChevron"></i>
                </button>
            </div>
        </li>

        {{-- مقارنة الجامعات: انتقال مباشر --}}
        <li class="sidebar-nav-item {{ request()->routeIs('compare') ? 'active' : '' }}">
            <a href="{{ route('compare') }}" onclick="closeSidebar()">
                <i class="bi bi-arrow-left-right text-accent fs-5"></i>
                <span>مقارنة الجامعات</span>
            </a>
        </li>

        {{-- المساعد الذكي: انتقال مباشر --}}
        <li class="sidebar-nav-item {{ request()->routeIs('ai.advisor') ? 'active' : '' }}">
            <a href="{{ route('ai.advisor') }}" onclick="closeSidebar()">
                <i class="bi bi-robot text-accent fs-5"></i>
                <span>المساعد الذكي</span>
                <span class="badge" style="background: rgba(197, 155, 39, 0.2); color: #c59b27; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; margin-right: auto;">قريباً</span>
            </a>
        </li>

        {{-- التسجيل الآن: انتقال مباشر لاستمارة التقديم --}}
        <li class="sidebar-nav-item {{ request()->routeIs('student.apply') ? 'active' : '' }}">
            <a href="{{ route('student.apply') }}" onclick="closeSidebar()">
                <i class="bi bi-pencil-square text-accent fs-5"></i>
                <span>التسجيل الآن</span>
                <span class="badge" style="background: rgba(18, 183, 106, 0.2); color: #86efac; font-size: 0.65rem; padding: 2px 7px; border-radius: 4px; margin-right: auto;">متاح</span>
            </a>
        </li>

        {{-- الإشعارات: متابعة تنبيهات القبول والطلب --}}
        <li class="sidebar-nav-item {{ (request()->routeIs('student.dashboard') && request('tab') === 'notifications') ? 'active' : '' }}">
            @auth
                <a href="{{ route('student.dashboard', ['tab' => 'notifications']) }}" onclick="closeSidebar()">
                    <i class="bi bi-bell-fill text-accent fs-5"></i>
                    <span>الإشعارات</span>
                    @php
                        $userUnreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', 0)->count();
                    @endphp
                    @if($userUnreadCount > 0)
                        <span class="badge bg-danger rounded-pill font-monospace" style="font-size:0.68rem; margin-right: auto;">{{ $userUnreadCount }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}" onclick="closeSidebar()">
                    <i class="bi bi-bell-fill text-accent fs-5"></i>
                    <span>الإشعارات</span>
                </a>
            @endauth
        </li>

        {{-- خط فاصل --}}
        <div class="sidebar-divider"></div>

        {{-- تواصل معنا: انتقال مباشر --}}
        <li class="sidebar-nav-item">
            <a href="#footerContactArea" onclick="closeSidebar()">
                <i class="bi bi-headset text-accent fs-5"></i>
                <span>تواصل معنا</span>
            </a>
        </li>

    </ul>

    {{-- خط ذهبي فاصل قبل نهاية القائمة --}}
    <div style="height: 1.5px; background: linear-gradient(90deg, transparent, var(--accent, #c59b27), transparent); margin: 0.8rem 1rem;"></div>

    {{-- منطقة تسجيل الدخول والحساب في أسفل القائمة --}}
    <div class="sidebar-footer-auth">
        @guest
        <div id="sidebarGuestBlock" style="display: block;">
            <a href="{{ route('login', request()->routeIs('login', 'register') ? [] : ['return' => url()->full()]) }}" class="sidebar-btn-login" style="width: 100%; text-align: center; display: block;" onclick="closeSidebar()">
                <i class="bi bi-box-arrow-in-left me-1"></i>تسجيل الدخول / البدء
            </a>
        </div>
        @else
        <div id="sidebarUserBlock">
            <div class="sidebar-user-card">
                <div class="sidebar-user-name">
                    <span>👤</span>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" style="color: inherit; text-decoration: none;">لوحة تحكم مدير النظام</a>
                    @elseif(Auth::user()->isUniversity())
                        <a href="{{ route('university.dashboard') }}" style="color: inherit; text-decoration: none;">بوابة الجامعة ({{ Auth::user()->username }})</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" style="color: inherit; text-decoration: none;">الملف الشخصي للطالب</a>
                    @endif
                </div>
                <span style="font-size: 11px; background: rgba(18, 183, 106, 0.2); color: #86EFAC; padding: 2px 7px; border-radius: 4px; font-weight: 600;">نشط</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="sidebar-btn-logout">
                    <span>🚪 تسجيل الخروج</span>
                </button>
            </form>
        </div>
        @endguest
    </div>
</aside>

{{-- 2. القائمة الجانبية الفرعية لعرض الجامعات فقط بجانب القائمة الرئيسية --}}
<div class="sidebar-sub-drawer" id="univSubDrawer" aria-label="قائمة الجامعات المعتمدة">
    <div class="sub-drawer-header">
        <button type="button" class="btn-back-subdrawer d-md-none me-1" onclick="closeUnivSubsidebar(event)" title="رجوع">
            <i class="bi bi-arrow-right"></i>
        </button>
        <span class="sub-drawer-title">
            <i class="bi bi-buildings-fill"></i>
            <span>الجامعات المعتمدة</span>
        </span>
        <button type="button" class="btn-close-subdrawer ms-auto" onclick="closeUnivSubsidebar(event)" title="إغلاق">✕</button>
    </div>
    <div class="sub-drawer-body">
        @foreach($sidebarUniversities as $u)
        <a href="{{ route('universities.show', $u->university_id) }}" class="sub-drawer-univ-link" onclick="closeSidebar()">
            <i class="bi bi-buildings"></i>
            <span>{{ $u->university_name }}</span>
        </a>
        @endforeach
    </div>
</div>
