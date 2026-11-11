@extends('layouts.app')

@section('title', 'دليل الجامعات اليمنية — EduYemen')

@section('styles')
<style>
    /* ترويسة الصفحة الحديثة المتوافقة مع ألوان الشعار */
    .univ-page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 65%, var(--primary-light) 100%);
        padding: 2.5rem 0 4.5rem;
        position: relative;
        overflow: hidden;
    }

    .univ-page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-light) 50%, var(--accent) 100%);
    }

    /* كرت الفلترة والبحث */
    .filter-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(37, 45, 107, 0.08);
        border: 1px solid rgba(37, 45, 107, 0.1);
        position: relative;
        z-index: 10;
        margin-top: -2.8rem;
    }

    /* حقل وأيقونة البحث المحسنة */
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon-indicator {
        position: absolute;
        right: 14px;
        color: #64748b;
        font-size: 1.15rem;
        pointer-events: none;
        transition: all 0.25s ease;
        z-index: 5;
    }

    .modern-search-input {
        padding-right: 44px !important;
        padding-left: 38px !important;
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        color: #1e293b;
    }

    /* عند الضغط والتركيز على شريط البحث */
    .modern-search-input:focus {
        background-color: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(197, 155, 39, 0.2);
    }

    .search-input-wrapper:focus-within .search-icon-indicator {
        color: var(--accent);
        transform: scale(1.15) rotate(-8deg);
    }

    .btn-clear-search {
        position: absolute;
        left: 12px;
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: color 0.15s;
        z-index: 5;
    }

    .btn-clear-search:hover {
        color: #ef4444;
    }

    .search-spinner-indicator {
        position: absolute;
        left: 14px;
        z-index: 5;
    }

    .modern-select {
        height: 48px;
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        transition: all 0.2s ease;
    }

    .modern-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(21, 101, 168, 0.15);
    }

    /* بطاقة عرض الجامعة المطورة المتوافقة مع ألوان الشعار */
    .univ-modern-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 16px rgba(37, 45, 107, 0.05);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .univ-modern-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(37, 45, 107, 0.12);
        border-color: var(--accent);
    }

    .univ-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 0.35rem;
        line-height: 1.35;
    }

    /* =========================================================
       تحسينات متجاوبة مع شاشات الهواتف (< 768px)
       تصغير حاوية البحث والفلترة لمنع استهلاك مساحة الشاشة
    ========================================================= */
    @media (max-width: 767.98px) {
        .univ-page-header {
            padding: 1.4rem 0 2.6rem !important;
        }
        .filter-card {
            margin-top: -1.5rem !important;
            padding: 0.65rem 0.75rem !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 16px rgba(37, 45, 107, 0.08) !important;
            margin-bottom: 1rem !important;
        }
        .modern-search-input {
            height: 38px !important;
            font-size: 0.82rem !important;
            padding-right: 36px !important;
            padding-left: 30px !important;
            border-radius: 8px !important;
        }
        .search-icon-indicator {
            right: 11px !important;
            font-size: 0.95rem !important;
        }
        .modern-select {
            height: 38px !important;
            font-size: 0.8rem !important;
            padding: 0.35rem 0.5rem !important;
            border-radius: 8px !important;
        }
        .btn-reset-compact {
            height: 38px !important;
            padding: 0.35rem 0.45rem !important;
            font-size: 0.8rem !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .univ-modern-card {
            border-radius: 14px !important;
        }
        .univ-card-title {
            font-size: 0.95rem !important;
        }
        .univ-card-header-box {
            padding: 0.75rem 0.85rem !important;
            gap: 10px !important;
        }
        .univ-logo-box-inner {
            width: 52px !important;
            height: 52px !important;
            min-width: 52px !important;
            border-radius: 10px !important;
            padding: 3px !important;
        }
    }

    @media (max-width: 576px) {
        .univ-page-header h1 {
            font-size: 1.35rem !important;
        }
        .univ-page-header p {
            font-size: 0.8rem !important;
        }
        .univ-header-logo {
            width: 44px !important;
            height: 44px !important;
        }
    }
</style>
@endsection

@section('content')

{{-- ====== PAGE HEADER ====== --}}
<div class="univ-page-header">
    <div class="container text-white">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size:0.84rem;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">الرئيسية</a></li>
                    <li class="breadcrumb-item active text-white">دليل الجامعات</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-3">
            <img src="/images/logo.png"
                 class="univ-header-logo"
                 onerror="this.onerror=null; this.src='/images/eduyemen-logo.svg';"
                 style="width: 58px; height: 58px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));"
                 alt="شعار EduYemen">
            <div>
                <h1 class="fw-bold mb-1" style="font-size:1.85rem;">دليل الجامعات اليمنية</h1>
                <p class="text-white-50 mb-0" style="font-size: 0.92rem;">استعرض الجامعات الحكومية والأهلية المعتمدة في أمانة العاصمة صنعاء وتعرف على تفاصيلها</p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    {{-- ====== FILTER & REAL-TIME SEARCH CARD (ملمومة ومضغوطة لشاشة الهاتف) ====== --}}
    <div class="filter-card mb-4">
        <form method="GET" action="{{ route('universities.index') }}" id="searchFilterForm" class="row g-2 g-md-3 align-items-end" onsubmit="event.preventDefault();">
            
            {{-- حقل البحث الفوري أثناء الكتابة --}}
            <div class="col-12 col-md-5">
                <label for="liveSearchInput" class="form-label small fw-bold text-secondary mb-1 d-none d-md-block">
                    البحث التلقائي السريع
                </label>
                <div class="search-input-wrapper">
                    <span class="search-icon-indicator" id="searchIconIndicator">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           id="liveSearchInput"
                           value="{{ request('search') }}"
                           class="form-control modern-search-input"
                           placeholder="اكتب اسم الجامعة أو كلمة مفتاحية للبحث فوراً…"
                           autocomplete="off">
                    
                    {{-- زر مسح البحث السريع --}}
                    <button type="button" class="btn-clear-search d-none" id="btnClearSearch" onclick="clearLiveSearch()" title="مسح البحث">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>

                    {{-- مؤشر التحميل أثناء الجلب --}}
                    <div class="search-spinner-indicator d-none" id="searchSpinner">
                        <div class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1rem; height: 1rem;"></div>
                    </div>
                </div>
            </div>

            {{-- نوع الجامعة --}}
            <div class="col-5 col-md-3">
                <label for="filterType" class="form-label small fw-bold text-secondary mb-1 d-none d-md-block">نوع الجامعة</label>
                <select name="type" id="filterType" class="form-select modern-select" onchange="performLiveSearch()">
                    <option value="">كل الأنواع</option>
                    <option value="حكومية" {{ request('type')=='حكومية' ? 'selected' : '' }}>حكومية</option>
                    <option value="أهلية"  {{ request('type')=='أهلية'  ? 'selected' : '' }}>أهلية</option>
                </select>
            </div>

            {{-- المديرية --}}
            <div class="col-4 col-md-2">
                <label for="filterDistrict" class="form-label small fw-bold text-secondary mb-1 d-none d-md-block">المديرية</label>
                <select name="district" id="filterDistrict" class="form-select modern-select" onchange="performLiveSearch()">
                    <option value="">كل المديريات</option>
                    @foreach($districts as $d)
                        <option value="{{ $d }}" {{ request('district')==$d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            {{-- زر إعادة الضبط --}}
            <div class="col-3 col-md-2 d-flex">
                <button type="button" class="btn btn-outline-secondary w-100 btn-reset-compact" onclick="resetAllFilters()" title="إعادة ضبط الفلاتر">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span class="d-none d-sm-inline ms-1">ضبط</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ====== RESULTS HEADER ====== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="small text-muted" id="resultsCounter">
            <i class="bi bi-grid-3x3-gap me-1 text-primary"></i>
            عُثر على <strong class="text-dark" id="totalCountDisplay">{{ $universities->total() }}</strong> جامعة
        </div>
        <a href="{{ route('compare') }}" class="btn btn-sm btn-outline-primary fw-bold">
            <i class="bi bi-arrow-left-right me-1"></i>مقارنة الجامعات
        </a>
    </div>

    {{-- ====== UNIVERSITIES GRID CONTAINER ====== --}}
    <div id="universitiesGridContainer">
        @if($universities->isEmpty())
        <div class="text-center bg-white rounded-4 border p-5">
            <i class="bi bi-search text-muted opacity-50" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold text-dark mt-3">لم يُعثر على جامعات مطابقة</h5>
            <p class="small text-muted mb-3">جرّب كتابة اسم آخر أو تغيير فلاتر البحث</p>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="resetAllFilters()">عرض كل الجامعات</button>
        </div>
        @else
        <div class="row g-4" id="universitiesCardRow">
            @foreach($universities as $univ)
            <div class="col-md-6 col-lg-4 univ-grid-item"
                 data-name="{{ mb_strtolower($univ->university_name) }}"
                 data-type="{{ $univ->university_type }}"
                 data-district="{{ $univ->district }}">
                <div class="univ-modern-card" style="border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 4px 16px rgba(11, 61, 107, 0.05); height: 100%; display: flex; flex-direction: column;">
                    
                    {{-- رأس البطاقة المطور: الشعار في اليمين واسم الجامعة ونوعها إلى جانبه --}}
                    <div class="p-3 border-bottom d-flex align-items-center gap-3 univ-card-header-box" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);">
                        {{-- إطار شعار الجامعة في جهة اليمين --}}
                        <div class="univ-logo-box-inner" style="width: 65px; height: 65px; min-width: 65px; background: #ffffff; border-radius: 12px; padding: 4px; box-shadow: 0 3px 10px rgba(11, 61, 107, 0.08); display: flex; align-items: center; justify-content: center; border: 1.5px solid rgba(197, 155, 39, 0.25);">
                            <img src="{{ $univ->logo_url }}"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'65\' height=\'65\'%3E%3Crect width=\'65\' height=\'65\' fill=\'%23ffffff\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%230b3d6b\' font-size=\'26\'%3E🏛%3C/text%3E%3C/svg%3E'"
                                 style="width: 100%; height: 100%; object-fit: contain;"
                                 alt="شعار {{ $univ->university_name }}">
                        </div>

                        {{-- اسم الجامعة ونوعها إلى جانبه --}}
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="mb-1 d-flex align-items-center gap-1 flex-wrap">
                                <span class="badge {{ $univ->university_type == 'حكومية' ? 'badge-gov' : 'badge-private' }}" style="font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 50px;">
                                    {{ $univ->university_type }}
                                </span>
                                @if($univ->status === 'نشطة')
                                <span class="badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:0.68rem; font-weight:700; padding:2px 7px; border-radius:50px;">القبول مفتوح</span>
                                @else
                                <span class="badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-size:0.68rem; font-weight:700; padding:2px 7px; border-radius:50px;">القبول مغلق</span>
                                @endif
                            </div>
                            <h3 class="univ-card-title mb-0 text-truncate" style="font-size: 1.02rem; font-weight: 800; color: #0b3d6b;" title="{{ $univ->university_name }}">
                                {{ $univ->university_name }}
                            </h3>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="univ-card-body-area p-3 d-flex flex-column flex-grow-1">
                        <p class="univ-card-description mb-2" style="font-size: 0.81rem; color: #64748b; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ Str::limit($univ->description, 110) }}
                        </p>

                        {{-- كليات وتخصصات --}}
                        <div class="d-flex gap-2 mb-2">
                            <div class="flex-fill text-center p-2 rounded-3" style="background: rgba(11, 61, 107, 0.06); border: 1px solid rgba(11, 61, 107, 0.1);">
                                <div style="font-size: 0.95rem; font-weight: 800; color: #0b3d6b;">{{ $univ->colleges_count }}</div>
                                <div style="font-size: 0.68rem; font-weight: 700; color: #475569;">عدد الكليات</div>
                            </div>
                            <div class="flex-fill text-center p-2 rounded-3" style="background: rgba(197, 155, 39, 0.08); border: 1px solid rgba(197, 155, 39, 0.2);">
                                <div style="font-size: 0.95rem; font-weight: 800; color: #ab8219;">{{ $univ->specializations_count }}</div>
                                <div style="font-size: 0.68rem; font-weight: 700; color: #475569;">تخصص متاح</div>
                            </div>
                        </div>

                        {{-- تفاصيل الموقع والتأسيس --}}
                        <div class="mt-auto mb-1" style="font-size: 0.76rem;">
                            <div class="d-flex align-items-center gap-2 mb-1 text-muted">
                                <i class="bi bi-geo-alt-fill text-danger flex-shrink-0"></i>
                                <span class="text-truncate">{{ $univ->district }} — {{ Str::limit($univ->address, 35) }}</span>
                            </div>
                            @if($univ->year_establ)
                            <div class="d-flex align-items-center gap-2 mb-1 text-muted">
                                <i class="bi bi-calendar-check text-primary flex-shrink-0"></i>
                                <span>تأسست عام {{ $univ->year_establ }}</span>
                            </div>
                            @endif
                            @if($univ->contact_phone ?? $univ->phone)
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="bi bi-telephone-fill text-success flex-shrink-0"></i>
                                <span dir="ltr">{{ $univ->contact_phone ?? $univ->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Actions: الانتقال لصفحة الجامعة + مقارنة في سطر واحد لا ينكسر نهائياً --}}
                    <div class="univ-card-footer-actions">
                        <a href="{{ route('universities.show', $univ->university_id) }}" class="btn-univ-view" title="الانتقال إلى صفحة الجامعة">
                            <span class="btn-univ-view-text">الانتقال إلى صفحة الجامعة</span>
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <a href="{{ route('compare') }}?u1={{ $univ->university_id }}" class="btn-univ-compare" title="مقارنة هذه الجامعة" aria-label="مقارنة الجامعة">
                            <i class="bi bi-arrow-left-right"></i>
                            <span class="btn-univ-compare-text">مقارنة</span>
                        </a>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        {{-- الترقيم (Pagination) --}}
        @if($universities->hasPages())
        <div class="d-flex justify-content-center mt-5" id="paginationWrapper">
            {{ $universities->links('pagination::bootstrap-5') }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    let searchDebounceTimer = null;

    const liveInput = document.getElementById('liveSearchInput');
    const clearBtn  = document.getElementById('btnClearSearch');
    const spinner   = document.getElementById('searchSpinner');

    // إظهار زر المسح عند وجود قيمة
    if (liveInput) {
        liveInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                clearBtn.classList.remove('d-none');
            } else {
                clearBtn.classList.add('d-none');
            }

            // فلترة بصرية فورية لحظية (0ms) للبطاقات المعروضة
            filterCardsInstantly(this.value.trim().toLowerCase());

            // جلب نتائج الخادم بالـ debounce (250ms)
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                performLiveSearch();
            }, 250);
        });
    }

    // فلترة فورية للبطاقات الموجودة في الصفحة
    function filterCardsInstantly(query) {
        const cards = document.querySelectorAll('.univ-grid-item');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const district = card.getAttribute('data-district') || '';
            if (!query || name.includes(query) || district.includes(query)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const totalDisplay = document.getElementById('totalCountDisplay');
        if (totalDisplay && query) {
            totalDisplay.textContent = visibleCount;
        }
    }

    // تنفيذ طلب البحث المباشر وجلب المحتوى عبر AJAX
    function performLiveSearch() {
        const search   = liveInput ? liveInput.value.trim() : '';
        const type     = document.getElementById('filterType').value;
        const district = document.getElementById('filterDistrict').value;

        if (spinner) spinner.classList.remove('d-none');

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (type) params.append('type', type);
        if (district) params.append('district', district);

        const targetUrl = "{{ route('universities.index') }}?" + params.toString();

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            // استخراج محتوى الحاوية والترقيم
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('universitiesGridContainer');
            const newCounter = doc.getElementById('resultsCounter');

            if (newGrid) {
                document.getElementById('universitiesGridContainer').innerHTML = newGrid.innerHTML;
            }
            if (newCounter) {
                document.getElementById('resultsCounter').innerHTML = newCounter.innerHTML;
            }

            // تحديث رابط المتصفح دون إعادة تحميل الصفحة
            window.history.replaceState({}, '', targetUrl);
        })
        .catch(err => {
            console.error('Error in live search:', err);
        })
        .finally(() => {
            if (spinner) spinner.classList.add('d-none');
        });
    }

    // مسح البحث
    function clearLiveSearch() {
        if (liveInput) {
            liveInput.value = '';
            clearBtn.classList.add('d-none');
            performLiveSearch();
        }
    }

    // إعادة ضبط جميع الفلاتر
    function resetAllFilters() {
        if (liveInput) liveInput.value = '';
        if (clearBtn) clearBtn.classList.add('d-none');
        document.getElementById('filterType').value = '';
        document.getElementById('filterDistrict').value = '';
        performLiveSearch();
    }
</script>
@endsection
