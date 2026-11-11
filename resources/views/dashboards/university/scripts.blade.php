<script>
/**
 * EduYemen University Dashboard - Unified Reactive Scripts
 */

// 1. البيانات الأولية للجامعة والكليات
window.universityData = {
    id: {{ $university->university_id }},
    name: @json($university->university_name),
    status: @json($university->status),
    colleges: @json($university->colleges)
};

// 2. سجل التنقل الذكي (History Stack)
window.tabHistory = ['overview'];
window.currentActiveTab = 'overview';
window.currentSelectedCollegeId = null;
window.currentSelectedSpecId = null;

// 3. مستودع الوثائق والخطط للتخصص الحالي في الذاكرة
window.currentSpecialtyDocs = [];
window.currentSpecialtyPlans = [];

// ==========================================
// التهيأة عند اكتمال تحميل الصفحة
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    initLiveClock();
    initSidebar();
    initHashTab();
    initLiveSearch();
    initCollegesData();
});

// ==========================================
// ساعة التوقيت والتاريخ الحية
// ==========================================
function initLiveClock() {
    const dateEl = document.getElementById('universityDashboardDate');
    const timeEl = document.getElementById('universityDashboardTime');
    const timeDigits = document.getElementById('liveClockDigits');
    const dateElem = document.getElementById('liveClockDate');

    function update() {
        const now = new Date();
        const dateStr = new Intl.DateTimeFormat('ar-YE', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(now);

        const timeStr = new Intl.DateTimeFormat('ar-YE', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        }).format(now);

        if (dateEl) {
            dateEl.innerHTML = '<i class="fa-regular fa-calendar-days"></i> ' + dateStr;
        }
        if (timeEl) {
            timeEl.innerHTML = '<i class="fa-regular fa-clock"></i> ' + timeStr;
        }
        if (timeDigits) {
            timeDigits.textContent = timeStr;
        }
        if (dateElem) {
            dateElem.textContent = dateStr;
        }
    }
    update();
    setInterval(update, 1000);
}

// ==========================================
// إدارة القائمة الجانبية (Sidebar)
// ==========================================
function isSidebarOpen() {
    const sidebar = document.getElementById('sidebar') || document.getElementById('mainSidebar');
    if (!sidebar) return true;
    const isMobile = window.innerWidth <= 992;
    if (isMobile) {
        return sidebar.classList.contains('mobile-open');
    } else {
        return !sidebar.classList.contains('collapsed') && !document.body.classList.contains('sidebar-collapsed');
    }
}

function updateToggleIcon(isOpen) {
    const toggleBtn = document.getElementById('sidebarToggle') || document.getElementById('toggleSidebarBtn');
    if (!toggleBtn) return;

    toggleBtn.setAttribute('aria-expanded', String(isOpen));
    if (isOpen) {
        // عندما تكون القائمة مفتوحة: علامة الإغلاق
        toggleBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        toggleBtn.setAttribute('title', 'إغلاق القائمة الجانبية');
        toggleBtn.setAttribute('aria-label', 'إغلاق القائمة الجانبية');
    } else {
        // عندما تكون القائمة مغلقة: علامة عرض القائمة (الثلاثة الخطوط الأفقية)
        toggleBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
        toggleBtn.setAttribute('title', 'عرض القائمة الجانبية');
        toggleBtn.setAttribute('aria-label', 'عرض القائمة الجانبية');
    }
}

function initSidebar() {
    const toggleBtn = document.getElementById('sidebarToggle') || document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar') || document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    // الحالة التلقائية: مفتوحة في الشاشات الكبيرة ولا تغلق إلا بالضغط، ومغلقة تلقائياً في الشاشات الصغيرة/الهاتف
    const isMobile = window.innerWidth <= 992;
    setSidebarState(!isMobile);

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const currentlyOpen = isSidebarOpen();
            setSidebarState(!currentlyOpen);
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            setSidebarState(false);
        });
    }

    window.addEventListener('resize', function() {
        const currentlyOpen = isSidebarOpen();
        updateToggleIcon(currentlyOpen);
    });
}

function setSidebarState(open) {
    const sidebar = document.getElementById('sidebar') || document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const isMobile = window.innerWidth <= 992;

    if (sidebar) {
        if (isMobile) {
            sidebar.classList.toggle('mobile-open', open);
            sidebar.classList.remove('collapsed');
        } else {
            sidebar.classList.toggle('collapsed', !open);
            sidebar.classList.remove('mobile-open');
        }
    }

    if (isMobile) {
        document.body.classList.remove('sidebar-collapsed');
    } else {
        document.body.classList.toggle('sidebar-collapsed', !open);
    }

    if (overlay) {
        overlay.classList.toggle('active', open && isMobile);
        overlay.style.display = (open && isMobile) ? 'block' : 'none';
    }

    updateToggleIcon(open);
}

function toggleSidebar(forceState) {
    const currentlyOpen = isSidebarOpen();
    const newState = (forceState !== undefined) ? forceState : !currentlyOpen;
    setSidebarState(newState);
}

// ==========================================
// البحث الحي في خدمات المنصة
// ==========================================
function initLiveSearch() {
    const searchInput = document.getElementById('pageSearch') || document.getElementById('globalLiveSearch');
    const clearBtn = document.getElementById('clearSearch') || document.getElementById('searchClearBtn');
    const serviceCards = [...document.querySelectorAll('.navigation-actions-list .nav-action-item')];
    const noResults = document.getElementById('noResults');

    if (!searchInput) return;

    function filterServices() {
        const query = searchInput.value.trim().toLocaleLowerCase('ar');
        let visible = 0;
        serviceCards.forEach(function (card) {
            const searchable = (card.innerText + ' ' + (card.dataset.search || '')).toLocaleLowerCase('ar');
            const matches = !query || searchable.includes(query);
            card.style.display = matches ? 'flex' : 'none';
            if (matches) visible++;
        });
        if (noResults) noResults.style.display = visible ? 'none' : 'block';
        if (clearBtn) clearBtn.style.display = query ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterServices);
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            filterServices();
            searchInput.focus();
        });
    }
}

// ==========================================
// التنقل الذكي بين التبويبات (Tab Switching & History)
// ==========================================
function switchTab(tabId, pushHistory = true) {
    if (!tabId) tabId = 'overview';
    
    const validTabs = ['overview', 'profile', 'colleges', 'admission', 'applications', 'referrals', 'payments', 'announcements', 'reports', 'notifications', 'settings'];
    if (!validTabs.includes(tabId)) tabId = 'overview';

    // تحديث سجل التنقل
    if (pushHistory && window.currentActiveTab !== tabId) {
        window.tabHistory.push(tabId);
    }
    window.currentActiveTab = tabId;

    // تحديث عناصر القائمة الجانبية
    document.querySelectorAll('.sidebar-nav li, .sidebar-nav .nav-link').forEach(el => {
        if (el.getAttribute('data-tab') === tabId) {
            el.classList.add('active');
        } else {
            el.classList.remove('active');
        }
    });

    // تبديل إظهار المحتوى وإخفاء غيره بالكامل
    const overviewPane = document.getElementById('overviewTabContent') || document.getElementById('summaryAndIntroSection');
    const tabPanes = {
        'overview': overviewPane,
        'profile': document.getElementById('profileTabContent'),
        'colleges': document.getElementById('collegesTabContent'),
        'admission': document.getElementById('admissionTabContent'),
        'applications': document.getElementById('applicationsTabContent'),
        'referrals': document.getElementById('referralsTabContent'),
        'payments': document.getElementById('paymentsTabContent'),
        'announcements': document.getElementById('announcementsTabContent'),
        'reports': document.getElementById('reportsTabContent'),
        'notifications': document.getElementById('notificationsTabContent'),
        'settings': document.getElementById('settingsTabContent')
    };

    Object.keys(tabPanes).forEach(key => {
        const pane = tabPanes[key];
        if (pane) {
            if (key === tabId) {
                pane.style.display = 'block';
                pane.classList.add('active', 'fade-in-tab');
            } else {
                pane.style.display = 'none';
                pane.classList.remove('active', 'fade-in-tab');
            }
        }
    });

    // تحديث الرابط في شريط المتصفح لدعم الانتقال المباشر وحفظ الصفحة
    try {
        const url = new URL(window.location);
        if (tabId === 'overview') {
            url.searchParams.delete('tab');
            url.hash = '';
        } else {
            url.searchParams.set('tab', tabId);
            url.hash = '';
        }
        if (pushHistory) {
            window.history.pushState({ tab: tabId }, '', url);
        } else {
            window.history.replaceState({ tab: tabId }, '', url);
        }
    } catch(e) {}

    // إغلاق القائمة الجانبية على الهواتف عند النقر على أي صفحة
    if (window.innerWidth <= 992) {
        setSidebarState(false);
    }

    // الانتقال لأعلى الصفحة المطلوبة مباشرة
    window.scrollTo(0, 0);
}

function initHashTab() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabFromUrl = urlParams.get('tab') || window.location.hash.replace('#', '') || 'overview';
    switchTab(tabFromUrl, false);

    // تفعيل أزرار الرجوع والتقدم في المتصفح للتنقل السلس بين الصفحات
    window.addEventListener('popstate', function(e) {
        const currentParams = new URLSearchParams(window.location.search);
        const currentTab = currentParams.get('tab') || window.location.hash.replace('#', '') || 'overview';
        switchTab(currentTab, false);
    });
}

// ==========================================
// إدارة بيانات الكليات والتخصصات
// ==========================================
function initCollegesData() {
    const collegeSelect = document.getElementById('collegeFilterSelect');
    if (!collegeSelect) return;

    // التحقق مما إذا كان هناك تخصص محدد مطلوب عبر الرابط (query parameters)
    const urlParams = new URLSearchParams(window.location.search);
    const specFromUrl = urlParams.get('spec') || urlParams.get('spec_id');
    const collegeFromUrl = urlParams.get('college') || urlParams.get('college_id');

    if (specFromUrl) {
        let targetCollegeId = collegeFromUrl;
        if (!targetCollegeId && window.universityData && window.universityData.colleges) {
            for (const c of window.universityData.colleges) {
                if (c.specializations && c.specializations.some(s => s.specialization_id == specFromUrl)) {
                    targetCollegeId = c.college_id;
                    break;
                }
            }
        }
        if (targetCollegeId) {
            collegeSelect.value = targetCollegeId;
            onCollegeSelectChange(targetCollegeId, specFromUrl);
            return;
        }
    }

    // الحالة الأساسية: عدم اختيار أي كلية أو تخصص تلقائياً
    collegeSelect.value = '';
    resetCollegesAndSpecialtiesView();
}

function resetCollegesAndSpecialtiesView() {
    window.currentSelectedCollegeId = null;
    window.currentSelectedSpecId = null;

    const btnEditCol = document.getElementById('btnEditCurrentCollege');
    const btnDelCol = document.getElementById('btnDeleteCurrentCollege');
    if (btnEditCol) {
        btnEditCol.disabled = true;
        btnEditCol.style.opacity = '0.55';
        btnEditCol.style.pointerEvents = 'none';
    }
    if (btnDelCol) {
        btnDelCol.disabled = true;
        btnDelCol.style.opacity = '0.55';
        btnDelCol.style.pointerEvents = 'none';
    }

    const specSelect = document.getElementById('specialtyFilterSelect');
    if (specSelect) {
        specSelect.innerHTML = '<option value="" selected disabled>-- يرجى اختيار الكلية أولاً --</option>';
        specSelect.disabled = true;
        specSelect.value = '';
    }

    const emptyPrompt = document.getElementById('specialtyEmptyPrompt');
    const detailsSection = document.getElementById('specialtyDetailsSection');
    const promptTitle = document.getElementById('specialtyPromptTitle');
    const promptDesc = document.getElementById('specialtyPromptDesc');

    if (emptyPrompt) emptyPrompt.style.display = 'block';
    if (detailsSection) detailsSection.style.display = 'none';
    if (promptTitle) promptTitle.textContent = 'يرجى تحديد الكلية والتخصص الأكاديمي';
    if (promptDesc) promptDesc.textContent = 'قم باختيار الكلية أولاً من القائمة أعلاه، ثم حدد التخصص الأكاديمي لاستعراض بياناته، شروط القبول والتسجيل، والوثائق المطلوبة.';
}

function onCollegeSelectChange(collegeId, preselectedSpecId = null) {
    if (!collegeId) {
        resetCollegesAndSpecialtiesView();
        return;
    }

    window.currentSelectedCollegeId = collegeId;

    // تفعيل أزرار تعديل وحذف الكلية المحددة
    const btnEditCol = document.getElementById('btnEditCurrentCollege');
    const btnDelCol = document.getElementById('btnDeleteCurrentCollege');
    if (btnEditCol) {
        btnEditCol.disabled = false;
        btnEditCol.style.opacity = '1';
        btnEditCol.style.pointerEvents = 'auto';
    }
    if (btnDelCol) {
        btnDelCol.disabled = false;
        btnDelCol.style.opacity = '1';
        btnDelCol.style.pointerEvents = 'auto';
    }

    const college = (window.universityData.colleges || []).find(c => c.college_id == collegeId);
    const specSelect = document.getElementById('specialtyFilterSelect');
    const emptyPrompt = document.getElementById('specialtyEmptyPrompt');
    const detailsSection = document.getElementById('specialtyDetailsSection');
    const promptTitle = document.getElementById('specialtyPromptTitle');
    const promptDesc = document.getElementById('specialtyPromptDesc');

    if (!specSelect) return;

    specSelect.innerHTML = '';

    if (college && college.specializations && college.specializations.length > 0) {
        specSelect.disabled = false;

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- اختر التخصص الأكاديمي --';
        defaultOpt.disabled = true;
        defaultOpt.selected = !preselectedSpecId;
        specSelect.appendChild(defaultOpt);

        college.specializations.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.specialization_id;
            opt.textContent = `${s.specialization_name} (${s.status || 'نشط'})`;
            if (preselectedSpecId && s.specialization_id == preselectedSpecId) {
                opt.selected = true;
            }
            specSelect.appendChild(opt);
        });

        if (preselectedSpecId) {
            onSpecialtySelectChange(preselectedSpecId);
        } else {
            // لا يتم اختيار أي تخصص تلقائياً، بل ينتظر قيام المستخدم باختياره
            window.currentSelectedSpecId = null;
            specSelect.value = '';
            if (detailsSection) detailsSection.style.display = 'none';
            if (emptyPrompt) emptyPrompt.style.display = 'block';
            if (promptTitle) promptTitle.textContent = `تم اختيار كلية: ${college.college_name}`;
            if (promptDesc) promptDesc.textContent = 'يرجى الآن اختيار التخصص الأكاديمي التابع لهذه الكلية من القائمة أعلاه لعرض تفاصيله وبقية الصفحة وإدارة القبول والتسجيل.';
        }
    } else {
        specSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'لا توجد تخصصات مضافة في هذه الكلية';
        opt.disabled = true;
        opt.selected = true;
        specSelect.appendChild(opt);

        window.currentSelectedSpecId = null;
        if (detailsSection) detailsSection.style.display = 'none';
        if (emptyPrompt) emptyPrompt.style.display = 'block';
        if (promptTitle) promptTitle.textContent = `كلية: ${college ? college.college_name : ''} (لا توجد تخصصات)`;
        if (promptDesc) promptDesc.textContent = 'لم يتم إضافة تخصصات في هذه الكلية حتى الآن. يمكنك إضافة تخصص جديد بالنقر على زر «إضافة تخصص أكاديمي» بالأعلى.';
    }
}

function onSpecialtySelectChange(specId) {
    const emptyPrompt = document.getElementById('specialtyEmptyPrompt');
    const detailsSection = document.getElementById('specialtyDetailsSection');

    if (!specId) {
        window.currentSelectedSpecId = null;
        if (detailsSection) detailsSection.style.display = 'none';
        if (emptyPrompt) emptyPrompt.style.display = 'block';
        return;
    }
    window.currentSelectedSpecId = specId;

    let foundSpec = null;
    let foundCollege = null;

    for (const c of window.universityData.colleges) {
        if (c.specializations) {
            const s = c.specializations.find(sp => sp.specialization_id == specId);
            if (s) {
                foundSpec = s;
                foundCollege = c;
                break;
            }
        }
    }

    if (!foundSpec) {
        if (detailsSection) detailsSection.style.display = 'none';
        if (emptyPrompt) emptyPrompt.style.display = 'block';
        return;
    }

    // إظهار قسم تفاصيل التخصص وبقية الصفحة وإخفاء الرسالة التوجيهية
    if (emptyPrompt) emptyPrompt.style.display = 'none';
    if (detailsSection) detailsSection.style.display = 'block';

    // 1. تحديث كارت الهيرو
    document.getElementById('heroCollegeTag').textContent = foundCollege ? foundCollege.college_name : 'كلية';
    document.getElementById('heroStatusTag').textContent = foundSpec.status || 'نشط';
    document.getElementById('heroLanguageTag').textContent = foundSpec.language || 'العربية';
    document.getElementById('heroSpecialtyTitle').textContent = foundSpec.specialization_name;
    document.getElementById('heroSpecialtyDesc').textContent = foundSpec.description || 'لا يوجد وصف مسجل لهذا التخصص.';

    document.getElementById('heroDurationVal').textContent = (foundSpec.duration_years || 4) + ' سنوات';
    document.getElementById('heroFeesVal').textContent = '$' + Number(foundSpec.tuition_fees || 0).toLocaleString();

    const admData = foundSpec.admission_data || {};
    document.getElementById('heroMinAvgVal').textContent = (admData.minimum_average || 70) + '%';
    const heroRegFee = document.getElementById('heroRegFeeVal');
    if (heroRegFee) {
        heroRegFee.textContent = Number(admData.registration_fee || 0).toLocaleString() + ' ر.ي';
    }
    const heroAdmStatus = document.getElementById('heroAdmStatusVal');
    if (heroAdmStatus) {
        heroAdmStatus.textContent = admData.admission_status || 'مفتوح';
    }

    // الوثائق
    let docs = [];
    if (admData.docs && Array.isArray(admData.docs) && admData.docs.length > 0) {
        docs = admData.docs.map(d => ({
            doc_id: d.doc_id,
            name: d.document_name || d.name,
            document_name: d.document_name || d.name,
            description: d.description || '',
            is_required: Boolean(d.is_required)
        }));
    } else {
        let rawDocs = admData.required_documents || [];
        if (typeof rawDocs === 'string') {
            try { rawDocs = JSON.parse(rawDocs); } catch(e) { rawDocs = []; }
        }
        if (Array.isArray(rawDocs)) {
            docs = rawDocs.map(d => ({
                name: d.name || d.document_name || String(d),
                document_name: d.name || d.document_name || String(d),
                description: d.description || '',
                is_required: Boolean(d.is_required)
            }));
        }
    }
    window.currentSpecialtyDocs = docs;
    document.getElementById('heroDocsCountVal').textContent = window.currentSpecialtyDocs.length + ' وثائق';
    document.getElementById('badgeDocsCount').textContent = window.currentSpecialtyDocs.length;

    // الخطط
    let plans = foundSpec.study_plans || [];
    if (typeof plans === 'string') {
        try { plans = JSON.parse(plans); } catch(e) { plans = []; }
    }
    window.currentSpecialtyPlans = Array.isArray(plans) ? plans : [];
    const heroPlans = document.getElementById('heroPlansCountVal');
    if (heroPlans) {
        heroPlans.textContent = window.currentSpecialtyPlans.length + ' خطط';
    }
    const badgePlans = document.getElementById('badgePlansCount');
    if (badgePlans) {
        badgePlans.textContent = window.currentSpecialtyPlans.length;
    }

    // 2. تعبئة التبويب الفرعي 1: البيانات الأساسية
    document.getElementById('basic_spec_name').value = foundSpec.specialization_name || '';
    document.getElementById('basic_spec_college').value = foundCollege ? foundCollege.college_id : '';
    document.getElementById('basic_spec_duration').value = foundSpec.duration_years || 4;
    document.getElementById('basic_spec_language').value = foundSpec.language || 'العربية';
    document.getElementById('basic_spec_fees').value = foundSpec.tuition_fees || 0;
    document.getElementById('basic_spec_status').value = foundSpec.status || 'نشط';
    document.getElementById('basic_spec_desc').value = foundSpec.description || '';

    // 3. تعبئة التبويب الفرعي 2: القبول والتسجيل
    document.getElementById('adm_min_avg').value = admData.minimum_average || 70.0;
    document.getElementById('adm_last_year').value = admData.secondary_last_year || 2021;
    const admTuition = document.getElementById('adm_tuition_fees');
    if (admTuition) {
        admTuition.value = admData.tuition_fees !== undefined ? admData.tuition_fees : (foundSpec.tuition_fees || 0);
    }
    const admRegFee = document.getElementById('adm_registration_fee');
    if (admRegFee) {
        admRegFee.value = admData.registration_fee !== undefined ? admData.registration_fee : 5000;
    }
    const admStart = document.getElementById('adm_start_date');
    if (admStart) {
        admStart.value = admData.admission_start_date ? String(admData.admission_start_date).split('T')[0] : '2026-06-01';
    }
    const admEnd = document.getElementById('adm_end_date');
    if (admEnd) {
        admEnd.value = admData.admission_end_date ? String(admData.admission_end_date).split('T')[0] : '2026-09-30';
    }
    const admStatus = document.getElementById('adm_status');
    if (admStatus) {
        admStatus.value = admData.admission_status || 'مفتوح';
    }
    document.getElementById('adm_criteria_text').value = admData.admission_data || '';
    const admDesc = document.getElementById('adm_desc_text');
    if (admDesc) {
        admDesc.value = admData.description || '';
    }

    // 4. تعبئة التبويب الفرعي 3: الوثائق المطلوبة
    renderSpecialtyDocuments();

    // 5. تعبئة التبويب الفرعي 4: الخطة الدراسية
    fetchSpecialtyPlans(specId);
}

function clearSpecialtyHero() {
    document.getElementById('heroSpecialtyTitle').textContent = 'لا يوجد تخصص محدد';
    document.getElementById('heroSpecialtyDesc').textContent = 'يرجى اختيار تخصص من القائمة أعلاه أو إضافة تخصص جديد.';
}

// ==========================================
// التنقل بين التبويبات الفرعية للتخصص الأكاديمي
// ==========================================
function switchSpecialtySubtab(subtabId) {
    document.querySelectorAll('.subtab-nav-btn').forEach(btn => {
        if (btn.getAttribute('data-subtab') === subtabId) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    const panes = {
        'basic': document.getElementById('pane-basic'),
        'admission': document.getElementById('pane-admission'),
        'documents': document.getElementById('pane-documents'),
        'plans': document.getElementById('pane-plans')
    };

    Object.keys(panes).forEach(k => {
        if (panes[k]) {
            panes[k].style.display = (k === subtabId) ? 'block' : 'none';
        }
    });

    if (subtabId === 'plans' && window.currentSelectedSpecId && (!window.currentSpecialtyPlansData || window.currentSpecialtyPlansData.length === 0)) {
        fetchSpecialtyPlans(window.currentSelectedSpecId);
    }
}

// اختيار التخصص من جدول شروط القبول والتنقل إليه
function selectAndEditSpec(collegeId, specId) {
    switchTab('colleges');
    const colSelect = document.getElementById('collegeFilterSelect');
    if (colSelect) {
        colSelect.value = collegeId;
        onCollegeSelectChange(collegeId, specId);
    }
    switchSpecialtySubtab('admission');
}

// ==========================================
// إدارة الوثائق المطلوبة ديناميكياً
// ==========================================
function renderSpecialtyDocuments() {
    const container = document.getElementById('documentsListContainer');
    if (!container) return;

    if (!window.currentSpecialtyDocs || window.currentSpecialtyDocs.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted border rounded p-3 bg-light">
                <p class="mb-2">لا توجد وثائق مخصصة لهذا التخصص حتى الآن.</p>
                <button type="button" class="btn btn-outline-navy btn-xs" onclick="openAddDocModal()">+ إضافة أول وثيقة مطلوبة</button>
            </div>
        `;
        return;
    }

    let html = '';
    window.currentSpecialtyDocs.forEach((doc, idx) => {
        const isReq = doc.is_required !== undefined ? doc.is_required : true;
        const docName = doc.document_name || doc.name || '';
        const docDesc = doc.description || '';
        html += `
            <div class="doc-item-row" id="docRow-${idx}">
                <div class="doc-icon-badge">
                    <svg class="ico text-navy" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                </div>
                <div class="doc-info-body">
                    <strong class="doc-name">${escapeHtml(docName)}</strong>
                    <span class="doc-description">${escapeHtml(docDesc || 'لا يوجد وصف إضافي')}</span>
                </div>
                <div class="doc-meta-badge">
                    <span class="status-pill ${isReq ? 'pill-danger' : 'pill-secondary'}">
                        ${isReq ? 'إلزامية' : 'اختيارية'}
                    </span>
                </div>
                <div class="doc-actions d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-link text-primary btn-xs" onclick="openEditDocModal(${idx})" title="تعديل بيانات هذه الوثيقة">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button type="button" class="btn btn-link text-danger btn-xs" onclick="removeDocItem(${idx})" title="حذف هذه الوثيقة">
                        <svg class="ico" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    document.getElementById('badgeDocsCount').textContent = window.currentSpecialtyDocs.length;
    document.getElementById('heroDocsCountVal').textContent = window.currentSpecialtyDocs.length + ' وثائق';
}

function openAddDocModal() {
    document.getElementById('modal_doc_name').value = '';
    document.getElementById('modal_doc_desc').value = '';
    document.getElementById('modal_doc_required').checked = true;
    openModal('addDocModal');
}

function submitAddDocItem(e) {
    e.preventDefault();
    const name = document.getElementById('modal_doc_name').value.trim();
    const desc = document.getElementById('modal_doc_desc').value.trim();
    const isRequired = document.getElementById('modal_doc_required').checked;

    if (!name) return;

    window.currentSpecialtyDocs.push({
        name: name,
        document_name: name,
        description: desc,
        is_required: isRequired
    });

    renderSpecialtyDocuments();
    closeModal('addDocModal');
    showToast('تمت إضافة الوثيقة إلى القائمة مؤقتاً (اضغط اعتماد وحفظ لتثبيتها بقاعدة البيانات)', 'info');
}

function openEditDocModal(idx) {
    const doc = window.currentSpecialtyDocs[idx];
    if (!doc) return;

    document.getElementById('modal_edit_doc_index').value = idx;
    document.getElementById('modal_edit_doc_name').value = doc.document_name || doc.name || '';
    document.getElementById('modal_edit_doc_desc').value = doc.description || '';
    document.getElementById('modal_edit_doc_required').checked = doc.is_required !== undefined ? doc.is_required : true;

    openModal('editDocModal');
}

function submitEditDocItem(e) {
    e.preventDefault();
    const idx = parseInt(document.getElementById('modal_edit_doc_index').value, 10);
    const name = document.getElementById('modal_edit_doc_name').value.trim();
    const desc = document.getElementById('modal_edit_doc_desc').value.trim();
    const isRequired = document.getElementById('modal_edit_doc_required').checked;

    if (!name || isNaN(idx) || !window.currentSpecialtyDocs[idx]) return;

    window.currentSpecialtyDocs[idx].name = name;
    window.currentSpecialtyDocs[idx].document_name = name;
    window.currentSpecialtyDocs[idx].description = desc;
    window.currentSpecialtyDocs[idx].is_required = isRequired;

    renderSpecialtyDocuments();
    closeModal('editDocModal');
    showToast('تم تحديث بيانات الوثيقة (اضغط اعتماد وحفظ لتثبيتها بقاعدة البيانات)', 'info');
}

function removeDocItem(idx) {
    window.currentSpecialtyDocs.splice(idx, 1);
    renderSpecialtyDocuments();
    showToast('تم حذف الوثيقة من القائمة (اضغط اعتماد وحفظ لحفظ التغييرات)', 'warning');
}

function saveCurrentDocumentsList() {
    if (!window.currentSelectedSpecId) {
        showToast('يرجى اختيار تخصص أولاً', 'error');
        return;
    }

    const payload = {
        _token: '{{ csrf_token() }}',
        documents: window.currentSpecialtyDocs
    };

    fetch(`/university/specializations/${window.currentSelectedSpecId}/documents`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'تم اعتماد وحفظ قائمة الوثائق المطلوبة بنجاح!', 'success');
            // تحديث الكائن في الذاكرة
            for (const c of window.universityData.colleges) {
                if (c.specializations) {
                    const s = c.specializations.find(sp => sp.specialization_id == window.currentSelectedSpecId);
                    if (s) {
                        if (!s.admission_data) s.admission_data = {};
                        s.admission_data.docs = data.documents;
                        s.admission_data.required_documents = window.currentSpecialtyDocs;
                        break;
                    }
                }
            }
        } else {
            showToast(data.message || 'حدث خطأ أثناء الحفظ', 'error');
        }
    })
    .catch(() => showToast('تعذر الاتصال بالخادم لحفظ الوثائق', 'error'));
}

// ==========================================
// إدارة الخطط الدراسية والمقررات ديناميكياً
// ==========================================
window.currentSpecialtyPlansData = [];
window.activePlanIndex = 0;
window.activeSemesterNumber = 1;

function fetchSpecialtyPlans(specId) {
    const container = document.getElementById('plansListContainer');
    if (!container) return;

    container.innerHTML = `
        <div class="text-center py-4 text-muted">
            <div class="spinner-border spinner-border-sm text-purple me-2" role="status"></div>
            <span>جاري تحميل الخطط الدراسية للتخصص...</span>
        </div>
    `;

    fetch(`/university/specializations/${specId}/plans`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.currentSpecialtyPlansData = data.plans || [];
            if (window.activePlanIndex >= window.currentSpecialtyPlansData.length) {
                window.activePlanIndex = 0;
            }
            renderSpecialtyPlans();
        } else {
            container.innerHTML = `<div class="text-danger p-3 text-center">تعذر تحميل الخطط الدراسية.</div>`;
        }
    })
    .catch(() => {
        container.innerHTML = `<div class="text-danger p-3 text-center">خطأ في الاتصال أثناء جلب الخطط الدراسية.</div>`;
    });
}

function renderSpecialtyPlans() {
    const container = document.getElementById('plansListContainer');
    if (!container) return;

    const plans = window.currentSpecialtyPlansData || [];

    // تحديث العداد في الشارة وكارت الهيرو
    const count = plans.length;
    const badge = document.getElementById('badgePlansCount');
    if (badge) badge.textContent = count;
    const heroPlans = document.getElementById('heroPlansCountVal');
    if (heroPlans) heroPlans.textContent = count + ' خطط';

    if (count === 0) {
        container.innerHTML = `
            <div class="p-4 text-center rounded-3 bg-light border">
                <div class="mb-3">
                    <svg class="ico text-purple" style="width:48px;height:48px;" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h5 class="fw-bold text-dark mb-1">لا توجد خطة دراسية مسجلة لهذا التخصص حتى الآن</h5>
                <p class="text-muted small mb-3">يمكنك إضافة الخطة المعتمدة وتحديد سنوات وفصول الدراسة وإدراج المقررات الدراسية لها بسهولة.</p>
                <button type="button" class="btn btn-purple btn-sm px-3" onclick="openAddPlanModal()">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    <span>+ إضافة أول خطة دراسية للتخصص</span>
                </button>
            </div>
        `;
        return;
    }

    const currentPlan = plans[window.activePlanIndex] || plans[0];
    const semesters = currentPlan.semesters || [];

    let html = `
        <!-- شريط اختيار الخطة الدراسية عند وجود أكثر من خطة -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-bold small text-muted"><i class="bi bi-journal-bookmark text-purple me-1"></i>الخطط الدراسية المتاحة (${count}):</span>
                <div class="btn-group btn-group-sm flex-wrap" role="group">
                    ${plans.map((p, idx) => `
                        <button type="button" class="btn ${idx === window.activePlanIndex ? 'btn-purple active' : 'btn-outline-purple'}" onclick="selectActivePlan(${idx})">
                            <span class="fw-bold">${escapeHtml(p.plan_name)}</span>
                            <span class="badge ${p.status === 'معتمدة' ? 'bg-success' : 'bg-secondary'} ms-1">${escapeHtml(p.status || 'معتمدة')}</span>
                        </button>
                    `).join('')}
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-purple btn-sm" onclick="openAddPlanModal()" title="إضافة خطة دراسية أخرى للتخصص">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    <span>+ إضافة خطة دراسية أخرى</span>
                </button>
            </div>
        </div>

        <!-- كارت ملخص الخطة المحددة والإجراءات -->
        <div class="card border rounded-3 p-3 mb-3 bg-white shadow-sm">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-2 bg-purple-subtle text-purple d-flex align-items-center justify-content-center" style="width:44px; height:44px;">
                        <svg class="ico" style="width:24px;height:24px;" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0 text-dark">${escapeHtml(currentPlan.plan_name)}</h5>
                            <span class="badge ${currentPlan.status === 'معتمدة' ? 'bg-success' : 'bg-warning text-dark'}">${escapeHtml(currentPlan.status || 'معتمدة')}</span>
                        </div>
                        <div class="small text-muted mt-1">
                            <span>المدة: <strong>${currentPlan.duration_years || 4} سنوات</strong> (${semesters.length} فصول دراسية)</span>
                            <span class="mx-2">•</span>
                            <span>إجمالي الساعات: <strong class="text-primary">${currentPlan.total_hours || 0} ساعة معتمدة</strong></span>
                            <span class="mx-2">•</span>
                            <span>إجمالي المقررات: <strong class="text-purple">${currentPlan.courses_count || 0} مادة</strong></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm px-3" onclick="openEditPlanModal(${currentPlan.study_plan_id})">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>تعديل الخطة</span>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm px-2" onclick="deleteStudyPlanItem(${currentPlan.study_plan_id})" title="حذف الخطة">
                        <svg class="ico" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- فصول الخطة الدراسية والمواد -->
        <div class="semesters-section mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold small text-secondary"><i class="bi bi-calendar3 me-1 text-primary"></i>مقررات ومساقات الفصول الدراسية:</span>
                <span class="small text-muted">اختر الفصل لعرض وإدارة مواده</span>
            </div>

            <!-- أزرار اختيار الفصول -->
            <div class="d-flex gap-1 flex-wrap mb-3 p-1 rounded-3 bg-light border">
                ${semesters.map(s => `
                    <button type="button" class="btn btn-sm ${s.semester_number === window.activeSemesterNumber ? 'btn-navy active shadow-sm' : 'btn-light border-0'} py-1 px-3" onclick="selectActiveSemester(${s.semester_number})">
                        <span>الفصل ${s.semester_number}</span>
                        <span class="badge ${s.semester_number === window.activeSemesterNumber ? 'bg-light text-dark' : 'bg-secondary text-white'} ms-1" style="font-size:0.7rem;">${s.courses ? s.courses.length : 0}</span>
                    </button>
                `).join('')}
            </div>

            <!-- محتوى الفصل النشط -->
            ${renderActiveSemesterContent(currentPlan, semesters)}
        </div>
    `;

    container.innerHTML = html;
}

function renderActiveSemesterContent(plan, semesters) {
    const sem = semesters.find(s => s.semester_number === window.activeSemesterNumber) || semesters[0];
    if (!sem) {
        return `<div class="p-3 text-muted text-center border rounded">لا توجد فصول مسجلة في هذه الخطة.</div>`;
    }

    const courses = sem.courses || [];
    const hoursSum = courses.reduce((sum, c) => sum + Number(c.hours || 0), 0);

    return `
        <div class="card border rounded-3 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 pb-2 border-bottom">
                <div>
                    <h6 class="fw-bold text-navy mb-0">
                        <i class="bi bi-mortarboard me-1 text-purple"></i>مقررات الفصل الدراسي رقم ${sem.semester_number}
                    </h6>
                    <span class="small text-muted">${courses.length} مواد مسجلة (${hoursSum} ساعة معتمدة في هذا الفصل)</span>
                </div>
                <button type="button" class="btn btn-navy btn-sm" onclick="openAddCourseModal(${sem.semester_number})">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    <span>+ إضافة مادة للفصل ${sem.semester_number}</span>
                </button>
            </div>

            ${courses.length === 0 ? `
                <div class="text-center py-4 text-muted bg-light rounded-3 p-3">
                    <p class="mb-2 small">لا توجد مواد مسجلة في الفصل الدراسي ${sem.semester_number} حتى الآن.</p>
                    <button type="button" class="btn btn-outline-navy btn-xs" onclick="openAddCourseModal(${sem.semester_number})">+ إضافة أول مادة لهذا الفصل</button>
                </div>
            ` : `
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 14%;">رمز المادة</th>
                                <th style="width: 32%;">اسم المادة / المقرر</th>
                                <th style="width: 12%; text-align: center;">الساعات</th>
                                <th style="width: 18%;">نوع المساق</th>
                                <th style="width: 14%;">المتطلب / ملاحظة</th>
                                <th style="width: 10%; text-align: center;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${courses.map(c => `
                                <tr>
                                    <td><code class="text-primary fw-bold">${escapeHtml(c.course_code || '---')}</code></td>
                                    <td>
                                        <div class="fw-bold text-dark">${escapeHtml(c.course_name)}</div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark border font-monospace">${c.hours || 3} س</span></td>
                                    <td>
                                        <span class="badge ${c.course_type && c.course_type.includes('تخصص') ? 'bg-purple-subtle text-purple' : (c.course_type && c.course_type.includes('كلية') ? 'bg-blue-subtle text-blue' : 'bg-light text-secondary border')}">
                                            ${escapeHtml(c.course_type || 'متطلب تخصص')}
                                        </span>
                                    </td>
                                    <td><span class="small text-muted">${escapeHtml(c.description || '---')}</span></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link text-primary p-0 me-2" onclick="openEditCourseModal(${c.course_id}, ${sem.semester_number})" title="تعديل المادة">
                                            <svg class="ico" style="width:16px;height:16px;" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </button>
                                        <button type="button" class="btn btn-link text-danger p-0" onclick="deleteCourseItem(${c.course_id})" title="حذف المادة">
                                            <svg class="ico" style="width:16px;height:16px;" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `}
        </div>
    `;
}

function selectActivePlan(idx) {
    window.activePlanIndex = idx;
    window.activeSemesterNumber = 1;
    renderSpecialtyPlans();
}

function selectActiveSemester(semNum) {
    window.activeSemesterNumber = semNum;
    renderSpecialtyPlans();
}

function openAddPlanModal() {
    if (!window.currentSelectedSpecId) {
        showToast('يرجى تحديد تخصص أولاً', 'error');
        return;
    }
    document.getElementById('planModalTitle').textContent = 'إضافة خطة دراسية جديدة للتخصص';
    document.getElementById('planModalSubtitle').textContent = 'إدراج خطة أو مسار دراسي إضافي للتخصص الأكاديمي الحالي';
    document.getElementById('modal_plan_id').value = '';
    document.getElementById('modal_plan_name').value = '';
    document.getElementById('modal_plan_duration').value = document.getElementById('basic_spec_duration').value || 4;
    document.getElementById('modal_plan_hours').value = (Number(document.getElementById('basic_spec_duration').value || 4) * 34);
    document.getElementById('modal_plan_status').value = 'معتمدة';
    document.getElementById('btnSavePlan').textContent = 'إضافة الخطة الدراسية';
    openModal('addPlanModal');
}

function openEditPlanModal(planId) {
    const plan = window.currentSpecialtyPlansData.find(p => p.study_plan_id == planId);
    if (!plan) return;

    document.getElementById('planModalTitle').textContent = 'تعديل الخطة الدراسية';
    document.getElementById('planModalSubtitle').textContent = 'تحديث بيانات وسنوات الخطة الدراسية المختارة';
    document.getElementById('modal_plan_id').value = plan.study_plan_id;
    document.getElementById('modal_plan_name').value = plan.plan_name;
    document.getElementById('modal_plan_duration').value = plan.duration_years || 4;
    document.getElementById('modal_plan_hours').value = plan.total_hours || '';
    document.getElementById('modal_plan_status').value = plan.status || 'معتمدة';
    document.getElementById('btnSavePlan').textContent = 'حفظ التعديلات';
    openModal('addPlanModal');
}

function submitPlanForm(e) {
    e.preventDefault();
    if (!window.currentSelectedSpecId) return;

    const planId = document.getElementById('modal_plan_id').value;
    const name = document.getElementById('modal_plan_name').value.trim();
    const duration = document.getElementById('modal_plan_duration').value;
    const hours = document.getElementById('modal_plan_hours').value;
    const status = document.getElementById('modal_plan_status').value;

    const isEdit = Boolean(planId);
    const url = isEdit ? `/university/plans/${planId}/update` : `/university/specializations/${window.currentSelectedSpecId}/plans`;

    const payload = {
        _token: '{{ csrf_token() }}',
        plan_name: name,
        duration_years: Number(duration),
        total_hours: hours ? Number(hours) : null,
        status: status
    };

    const btn = document.getElementById('btnSavePlan');
    btn.disabled = true;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            showToast(data.message || 'تم حفظ الخطة بنجاح', 'success');
            closeModal('addPlanModal');
            fetchSpecialtyPlans(window.currentSelectedSpecId);
        } else {
            showToast(data.message || 'فشل حفظ الخطة الدراسية', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        showToast('تعذر الاتصال بالخادم لحفظ الخطة', 'error');
    });
}

function deleteStudyPlanItem(planId) {
    if (!confirm('هل أنت متأكد من حذف هذه الخطة الدراسية وجميع المقررات التابعة لها؟')) return;

    fetch(`/university/plans/${planId}/delete`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            window.activePlanIndex = 0;
            fetchSpecialtyPlans(window.currentSelectedSpecId);
        } else {
            showToast(data.message || 'فشل حذف الخطة', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء محاولة حذف الخطة', 'error'));
}

function openAddCourseModal(semesterNum) {
    const plan = window.currentSpecialtyPlansData[window.activePlanIndex];
    if (!plan) return;

    document.getElementById('courseModalTitle').textContent = `إضافة مادة للفصل الدراسي ${semesterNum}`;
    document.getElementById('courseModalSubtitle').textContent = `الخطة: ${plan.plan_name}`;
    document.getElementById('modal_course_id').value = '';
    document.getElementById('modal_course_name').value = '';
    document.getElementById('modal_course_code').value = '';
    document.getElementById('modal_course_hours').value = 3;
    document.getElementById('modal_course_type').value = 'متطلب تخصص إجباري';
    document.getElementById('modal_course_desc').value = '';
    document.getElementById('modal_course_semester').value = semesterNum;

    const sel = document.getElementById('modal_course_semester_select');
    sel.innerHTML = (plan.semesters || []).map(s => `
        <option value="${s.semester_number}" ${s.semester_number == semesterNum ? 'selected' : ''}>الفصل الدراسي ${s.semester_number}</option>
    `).join('');

    document.getElementById('btnSaveCourse').textContent = 'إضافة المادة';
    openModal('courseModal');
}

function openEditCourseModal(courseId, semesterNum) {
    const plan = window.currentSpecialtyPlansData[window.activePlanIndex];
    if (!plan) return;

    let foundCourse = null;
    for (const sem of plan.semesters) {
        if (sem.courses) {
            const c = sem.courses.find(x => x.course_id == courseId);
            if (c) {
                foundCourse = c;
                break;
            }
        }
    }
    if (!foundCourse) return;

    document.getElementById('courseModalTitle').textContent = `تعديل المادة: ${foundCourse.course_name}`;
    document.getElementById('courseModalSubtitle').textContent = `الخطة: ${plan.plan_name}`;
    document.getElementById('modal_course_id').value = foundCourse.course_id;
    document.getElementById('modal_course_name').value = foundCourse.course_name;
    document.getElementById('modal_course_code').value = foundCourse.course_code || '';
    document.getElementById('modal_course_hours').value = foundCourse.hours || 3;
    document.getElementById('modal_course_type').value = foundCourse.course_type || 'متطلب تخصص إجباري';
    document.getElementById('modal_course_desc').value = foundCourse.description || '';
    document.getElementById('modal_course_semester').value = semesterNum;

    const sel = document.getElementById('modal_course_semester_select');
    sel.innerHTML = (plan.semesters || []).map(s => `
        <option value="${s.semester_number}" ${s.semester_number == semesterNum ? 'selected' : ''}>الفصل الدراسي ${s.semester_number}</option>
    `).join('');

    document.getElementById('btnSaveCourse').textContent = 'حفظ التعديلات';
    openModal('courseModal');
}

function submitCourseForm(e) {
    e.preventDefault();
    const plan = window.currentSpecialtyPlansData[window.activePlanIndex];
    if (!plan) return;

    const courseId = document.getElementById('modal_course_id').value;
    const semNum = document.getElementById('modal_course_semester_select').value;
    const name = document.getElementById('modal_course_name').value.trim();
    const code = document.getElementById('modal_course_code').value.trim();
    const hours = document.getElementById('modal_course_hours').value;
    const type = document.getElementById('modal_course_type').value;
    const desc = document.getElementById('modal_course_desc').value.trim();

    const payload = {
        _token: '{{ csrf_token() }}',
        semester_number: Number(semNum),
        course_name: name,
        course_code: code,
        hours: Number(hours),
        course_type: type,
        description: desc,
        course_id: courseId ? Number(courseId) : null
    };

    const btn = document.getElementById('btnSaveCourse');
    btn.disabled = true;

    fetch(`/university/plans/${plan.study_plan_id}/courses`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('courseModal');
            window.activeSemesterNumber = Number(semNum);
            fetchSpecialtyPlans(window.currentSelectedSpecId);
        } else {
            showToast(data.message || 'فشل حفظ المادة', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        showToast('حدث خطأ في الاتصال أثناء حفظ المادة', 'error');
    });
}

function deleteCourseItem(courseId) {
    if (!confirm('هل أنت متأكد من حذف هذه المادة من الخطة الدراسية؟')) return;

    fetch(`/university/courses/${courseId}/delete`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            fetchSpecialtyPlans(window.currentSelectedSpecId);
        } else {
            showToast(data.message || 'فشل حذف المادة', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء محاولة حذف المادة', 'error'));
}

// ==========================================
// حفظ التعديلات الأساسية وشروط القبول عبر AJAX
// ==========================================
function saveSpecialtyBasic(e) {
    e.preventDefault();
    if (!window.currentSelectedSpecId) return;

    const btn = document.getElementById('btnSaveBasicSpec');
    btn.disabled = true;

    const payload = {
        _token: '{{ csrf_token() }}',
        specialization_name: document.getElementById('basic_spec_name').value,
        duration_years: document.getElementById('basic_spec_duration').value,
        language: document.getElementById('basic_spec_language').value,
        tuition_fees: document.getElementById('basic_spec_fees').value,
        status: document.getElementById('basic_spec_status').value,
        description: document.getElementById('basic_spec_desc').value
    };

    fetch(`/university/specializations/${window.currentSelectedSpecId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            showToast('تم تحديث البيانات الأساسية للتخصص بنجاح!', 'success');
            // تحديث الكارت
            document.getElementById('heroSpecialtyTitle').textContent = payload.specialization_name;
            document.getElementById('heroStatusTag').textContent = payload.status;
            document.getElementById('heroLanguageTag').textContent = payload.language;
            document.getElementById('heroDurationVal').textContent = payload.duration_years + ' سنوات';
            document.getElementById('heroFeesVal').textContent = '$' + Number(payload.tuition_fees).toLocaleString();
            document.getElementById('heroSpecialtyDesc').textContent = payload.description || '';
        } else {
            showToast(data.message || 'فشل تحديث البيانات', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        showToast('تعذر الاتصال بالخادم', 'error');
    });
}

function saveSpecialtyAdmission(e) {
    e.preventDefault();
    if (!window.currentSelectedSpecId) return;

    const btn = document.getElementById('btnSaveAdmissionSpec');
    btn.disabled = true;

    const payload = {
        _token: '{{ csrf_token() }}',
        minimum_average: document.getElementById('adm_min_avg').value,
        secondary_last_year: document.getElementById('adm_last_year').value,
        tuition_fees: document.getElementById('adm_tuition_fees') ? document.getElementById('adm_tuition_fees').value : document.getElementById('basic_spec_fees').value,
        registration_fee: document.getElementById('adm_registration_fee') ? document.getElementById('adm_registration_fee').value : 0,
        admission_start_date: document.getElementById('adm_start_date') ? document.getElementById('adm_start_date').value : null,
        admission_end_date: document.getElementById('adm_end_date') ? document.getElementById('adm_end_date').value : null,
        admission_status: document.getElementById('adm_status') ? document.getElementById('adm_status').value : 'مفتوح',
        admission_data: document.getElementById('adm_criteria_text').value,
        description: document.getElementById('adm_desc_text') ? document.getElementById('adm_desc_text').value : ''
    };

    fetch(`/university/specializations/${window.currentSelectedSpecId}/admission`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            showToast(data.message || 'تم حفظ بيانات وشروط القبول والرسوم بنجاح!', 'success');
            
            // تحديث كارت الهيرو
            document.getElementById('heroMinAvgVal').textContent = payload.minimum_average + '%';
            if (document.getElementById('heroFeesVal')) {
                document.getElementById('heroFeesVal').textContent = '$' + Number(payload.tuition_fees || 0).toLocaleString();
            }
            if (document.getElementById('heroRegFeeVal')) {
                document.getElementById('heroRegFeeVal').textContent = Number(payload.registration_fee || 0).toLocaleString() + ' ر.ي';
            }
            if (document.getElementById('heroAdmStatusVal')) {
                document.getElementById('heroAdmStatusVal').textContent = payload.admission_status || 'مفتوح';
            }

            // تحديث حقل الرسوم في التبويب الأساسي ليكون متزامناً
            if (document.getElementById('basic_spec_fees')) {
                document.getElementById('basic_spec_fees').value = payload.tuition_fees;
            }

            // تحديث الكائن في الذاكرة
            for (const c of window.universityData.colleges) {
                if (c.specializations) {
                    const s = c.specializations.find(sp => sp.specialization_id == window.currentSelectedSpecId);
                    if (s) {
                        s.tuition_fees = payload.tuition_fees;
                        if (!s.admission_data) s.admission_data = {};
                        s.admission_data.minimum_average = payload.minimum_average;
                        s.admission_data.secondary_last_year = payload.secondary_last_year;
                        s.admission_data.tuition_fees = payload.tuition_fees;
                        s.admission_data.registration_fee = payload.registration_fee;
                        s.admission_data.admission_start_date = payload.admission_start_date;
                        s.admission_data.admission_end_date = payload.admission_end_date;
                        s.admission_data.admission_status = payload.admission_status;
                        s.admission_data.admission_data = payload.admission_data;
                        s.admission_data.description = payload.description;
                        break;
                    }
                }
            }
        } else {
            showToast(data.message || 'فشل حفظ بيانات القبول', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        showToast('تعذر الاتصال بالخادم لحفظ بيانات القبول', 'error');
    });
}

// ==========================================
// إضافة وتعديل وحذف الكليات والتخصصات
// ==========================================
function openCollegeModal(collegeId = null) {
    const title = document.getElementById('collegeModalTitle');
    const idInput = document.getElementById('collegeModalId');
    const nameInput = document.getElementById('modal_college_name');
    const descInput = document.getElementById('modal_college_desc');

    if (collegeId) {
        title.textContent = 'تعديل بيانات الكلية';
        idInput.value = collegeId;
        const col = window.universityData.colleges.find(c => c.college_id == collegeId);
        if (col) {
            nameInput.value = col.college_name;
            descInput.value = col.description || '';
        }
    } else {
        title.textContent = 'إضافة كلية جديدة';
        idInput.value = '';
        nameInput.value = '';
        descInput.value = '';
    }
    openModal('collegeModal');
}

function submitCollegeForm(e) {
    e.preventDefault();
    const id = document.getElementById('collegeModalId').value;
    const name = document.getElementById('modal_college_name').value.trim();
    const desc = document.getElementById('modal_college_desc').value.trim();

    const url = id ? `/university/colleges/${id}/update` : '{{ route('university.colleges.store') }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ college_name: name, description: desc })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('collegeModal');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'فشلت العملية', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function editSelectedCollege() {
    if (window.currentSelectedCollegeId) {
        openCollegeModal(window.currentSelectedCollegeId);
    } else {
        showToast('يرجى تحديد كلية أولاً', 'info');
    }
}

function deleteSelectedCollege() {
    if (!window.currentSelectedCollegeId) return;
    if (!confirm('هل أنت متأكد من حذف هذه الكلية مع كافة تخصصاتها المرتبطة؟')) return;

    fetch(`/university/colleges/${window.currentSelectedCollegeId}/delete`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'فشل حذف الكلية', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء الحذف', 'error'));
}

function openSpecializationModal() {
    document.getElementById('specializationForm').reset();
    if (window.currentSelectedCollegeId) {
        document.getElementById('new_spec_college_id').value = window.currentSelectedCollegeId;
    }
    openModal('specializationModal');
}

function submitNewSpecialization(e) {
    e.preventDefault();
    const form = document.getElementById('specializationForm');
    const formData = new FormData(form);

    fetch('{{ route('university.specializations.store') }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('specializationModal');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'فشل إضافة التخصص', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function editSelectedSpecialty() {
    switchSpecialtySubtab('basic');
}

function deleteSelectedSpecialty() {
    if (!window.currentSelectedSpecId) return;
    if (!confirm('هل أنت متأكد من حذف هذا التخصص الأكاديمي نهائياً؟')) return;

    fetch(`/university/specializations/${window.currentSelectedSpecId}/delete`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'فشل حذف التخصص', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء الحذف', 'error'));
}

// ==========================================
// إدارة طلبات التنسيق والتسجيل (Applications)
// ==========================================
function filterApplications(status) {
    document.querySelectorAll('.filter-pill-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-filter') === status);
    });

    const rows = document.querySelectorAll('#applicationsTable tbody tr.app-row');
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        if (status === 'all' || rowStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function searchApplicationsTable(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('#applicationsTable tbody tr.app-row');
    rows.forEach(row => {
        const text = (row.getAttribute('data-student') + ' ' + row.getAttribute('data-spec') + ' ' + row.getAttribute('data-phone')).toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}

function viewRequestDetails(reqId) {
    openModal('requestDetailsModal');
    const body = document.getElementById('reqModalBody');
    const title = document.getElementById('reqModalTitle');
    const footer = document.getElementById('reqModalFooter');

    title.textContent = `مراجعة وتدقيق طلب التسجيل #${reqId}`;
        body.innerHTML = `
        <div class="text-center py-5 text-muted">
            <div class="spinner-border text-navy" role="status"></div>
            <p class="mt-2 fw-bold text-navy">جاري تحميل وتجهيز ملف الطالب والوثائق للمراجعة...</p>
        </div>
    `;

    fetch(`/university/requests/${reqId}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success) {
            body.innerHTML = '<div class="alert alert-danger">تعذر تحميل بيانات الطلب.</div>';
            return;
        }

        const r = res.request;
        const statusClass = r.status === 'مقبول' ? 'success' : (r.status === 'مرفوض' ? 'danger' : (r.status === 'يتطلب استكمال' ? 'info' : 'warning'));
        const secAvg = parseFloat(r.sec_avg || 0);
        let appreciation = 'مقبول';
        if (secAvg >= 90) appreciation = 'ممتاز';
        else if (secAvg >= 80) appreciation = 'جيد جداً';
        else if (secAvg >= 65) appreciation = 'جيد';

        // قائمة الوثائق والمستندات المصنفة والواضحة
        let docsListHtml = '';
        if (r.reg_docs && r.reg_docs.length > 0) {
            docsListHtml = '<div class="docs-review-list">';
            r.reg_docs.forEach((doc, idx) => {
                const displayName = doc.file_name || doc.document_name || `مستند رسمي #${idx + 1}`;
                const ext = (doc.file_path || '').split('.').pop().toLowerCase();
                const isPdf = ext === 'pdf';
                const iconSvg = isPdf
                    ? '<svg class="ico text-danger" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'
                    : '<svg class="ico text-primary" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';

                docsListHtml += `
                    <div class="doc-review-item">
                        <div class="d-flex align-items-center gap-2" style="min-width:0; flex:1;">
                            <div class="doc-icon-badge">${iconSvg}</div>
                            <div style="min-width:0; flex:1;">
                                <strong class="d-block text-truncate text-navy" style="font-size:13px;" title="${escapeHtml(displayName)}">${escapeHtml(displayName)}</strong>
                                <span class="text-muted" style="font-size:11px;">صيغة الملف: ${ext.toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="/storage/${doc.file_path}" target="_blank" class="btn btn-navy btn-xs py-1 px-2.5 font-bold" title="معاينة الملف">
                                <i class="bi bi-eye me-1"></i>معاينة
                            </a>
                            <a href="/storage/${doc.file_path}" download class="btn btn-outline-secondary btn-xs py-1 px-2" title="تنزيل">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                `;
            });
            docsListHtml += '</div>';
        } else {
            docsListHtml = `
                <div class="text-center py-4 px-3 bg-light rounded border border-dashed text-muted">
                    <svg class="ico text-muted mb-1" style="width:28px;height:28px;" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    <p class="mb-0 small fw-bold">لم يرفق الطالب أي مستندات رقمية مع الطلب.</p>
                </div>
            `;
        }

                // توليد بطاقة التدقيق المالي وسند سداد الرسوم
        let paymentSectionHtml = '';
        if (res.payments && res.payments.length > 0) {
            const pay = res.payments[0];
            const pStatus = pay.ver_status || 'قيد التحقق';
            const pStatusClass = pStatus === 'معتمد ومؤكد' ? 'success' : (pStatus === 'مرفوض' ? 'danger' : 'warning');
            
            paymentSectionHtml = `
                <div class="review-panel-card">
                    <div class="review-panel-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-wallet2 text-navy me-1.5"></i>بيانات وتدقيق سند سداد الرسوم</span>
                        <span class="badge bg-${pStatusClass}-subtle text-${pStatusClass} border border-${pStatusClass}-subtle font-monospace">${escapeHtml(pStatus)}</span>
                    </div>
                    <div class="review-panel-body">
                        <table class="clean-data-table mb-2">
                            <tbody>
                                <tr>
                                    <td class="label-col"><i class="bi bi-bank me-1 text-muted"></i>وسيلة السداد:</td>
                                    <td class="val-col"><strong>${escapeHtml(pay.method_name)}</strong> <small class="text-muted">(${escapeHtml(pay.method_type)})</small></td>
                                </tr>
                                <tr>
                                    <td class="label-col"><i class="bi bi-hash me-1 text-muted"></i>رقم الإشعار / الحوالة:</td>
                                    <td class="val-col font-monospace fw-bold text-navy">${escapeHtml(pay.reference || '—')}</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><i class="bi bi-cash-stack me-1 text-muted"></i>المبلغ المسدد:</td>
                                    <td class="val-col font-monospace text-success fw-bold">${Number(pay.amount || 5000).toLocaleString()} ر.ي</td>
                                </tr>
                                <tr>
                                    <td class="label-col"><i class="bi bi-calendar-event me-1 text-muted"></i>تاريخ السداد:</td>
                                    <td class="val-col font-monospace">${escapeHtml(pay.pay_date || '—')}</td>
                                </tr>
                                ${pay.notes ? `
                                    <tr>
                                        <td class="label-col"><i class="bi bi-chat-text me-1 text-muted"></i>ملاحظات الطالب:</td>
                                        <td class="val-col text-muted small">${escapeHtml(pay.notes)}</td>
                                    </tr>
                                ` : ''}
                            </tbody>
                        </table>

                        ${pay.proof_file ? `
                            <div class="p-2 bg-light rounded border mb-2 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-image fs-4 text-primary"></i>
                                    <div>
                                        <strong class="d-block text-dark small">مستند سند السداد المرفوع</strong>
                                        <small class="text-muted" style="font-size:10px;">انقر للمعاينة والتدقيق المالي</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <a href="${pay.proof_file_url}" target="_blank" class="btn btn-navy btn-xs py-1 px-2.5 font-bold" title="معاينة السند">
                                        <i class="bi bi-eye me-1"></i>معاينة السند
                                    </a>
                                    <a href="${pay.proof_file_url}" download class="btn btn-outline-secondary btn-xs py-1 px-2" title="تنزيل السند">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        ` : `
                            <div class="alert alert-warning py-1.5 px-2 small mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>لم يتم إرفاق ملف صورة السند
                            </div>
                        `}

                        ${pay.ver_result ? `
                            <div class="alert alert-${pay.ver_status === 'مرفوض' ? 'danger' : 'info'} py-1.5 px-2.5 small mb-2">
                                <strong>قرار التدقيق المالي:</strong> ${escapeHtml(pay.ver_result)}
                            </div>
                        ` : ''}

                        {{-- أزرار إجراءات التدقيق المالي للسند --}}
                        <div class="d-flex align-items-center gap-2 pt-2 border-top">
                            ${pStatus !== 'معتمد ومؤكد' ? `
                                <button type="button" class="btn btn-success btn-xs py-1.5 px-3 fw-bold flex-grow-1" onclick="verifyApplicationPayment(${r.req_id}, 'معتمد ومؤكد')">
                                    <i class="bi bi-check-circle-fill me-1"></i>اعتماد وتأكيد السداد المالي
                                </button>
                            ` : `
                                <span class="badge bg-success-subtle text-success border border-success-subtle py-1.5 px-2 flex-grow-1 text-center font-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i>السداد المالي معتمد ومؤكد
                                </span>
                            `}
                            ${pStatus !== 'مرفوض' ? `
                                <button type="button" class="btn btn-outline-danger btn-xs py-1.5 px-2.5 fw-bold" onclick="rejectApplicationPaymentPrompt(${r.req_id})">
                                    <i class="bi bi-x-circle me-1"></i>رفض السند
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        } else {
            paymentSectionHtml = `
                <div class="review-panel-card">
                    <div class="review-panel-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-wallet2 text-navy me-1.5"></i>بيانات وتدقيق سند سداد الرسوم</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle font-monospace">بانتظار السداد</span>
                    </div>
                    <div class="review-panel-body text-center py-3">
                        <i class="bi bi-clock-history text-muted fs-4 d-block mb-1"></i>
                        <p class="small text-muted mb-2">لم يقم الطالب برفع سند سداد الرسوم بعد (اختار السداد لاحقاً).</p>
                        <button type="button" class="btn btn-outline-success btn-xs py-1 px-2.5 fw-bold" onclick="verifyApplicationPayment(${r.req_id}, 'معتمد ومؤكد')">
                            <i class="bi bi-check2-circle me-1"></i>تأكيد السداد يدوياً (سداد نقدي بالإدارة)
                        </button>
                    </div>
                </div>
            `;
        }

        body.innerHTML = `
            <div class="review-station-wrap">
                
                {{-- 1. بطاقة الهوية والطلب العلوية --}}
                <div class="review-top-card">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px; height:48px; border-radius:12px; background:rgba(255,255,255,0.15); display:grid; place-items:center; font-size:22px; color:#f0c842;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="mb-0 text-white font-bold" style="font-size:1.15rem;">${escapeHtml(r.full_name)}</h3>
                                    <span class="badge bg-gold-subtle text-gold px-2 py-0.5 rounded font-monospace" style="font-size:0.75rem;">طلب #${r.req_id}</span>
                                </div>
                                <div class="text-white-50 small mt-1">
                                    <span><i class="bi bi-calendar3 me-1"></i>تاريخ التقديم: <strong>${res.req_date_formatted || '—'}</strong></span>
                                    <span class="mx-2">•</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>المحافظة: <strong>${escapeHtml(r.province || 'أمانة العاصمة')}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="status-pill status-pill-${statusClass} px-3 py-1 font-bold" style="font-size:0.85rem;">
                                ${escapeHtml(r.status)}
                            </span>
                            ${r.seat_number ? `
                                <a href="http://127.0.0.1:8080/ministry_exams_service/public/?seat_number=${encodeURIComponent(r.seat_number)}&grad_year=${encodeURIComponent(r.grad_year || 2024)}&section=${encodeURIComponent(r.sec_section || 'علمي')}" 
                                   target="_blank" 
                                   class="btn-ministry-badge" 
                                   title="فحص ومطابقة نتيجة ودرجات الطالب مباشرة عبر بوابة وزارة التربية والتعليم">
                                    <i class="bi bi-patch-check-fill text-white fs-5"></i>
                                    <span>فحص نتيجة الوزارة</span>
                                </a>
                            ` : ''}
                        </div>
                    </div>
                </div>

                {{-- تنبيه الملاحظات المسجلة سابقاً إن وجدت --}}
                ${r.rejection_reason ? `
                    <div class="alert alert-warning py-2.5 px-3 mb-0 d-flex align-items-start gap-2 border-warning">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-warning mt-0.5"></i>
                        <div style="flex:1;">
                            <strong class="d-block text-dark">ملاحظات وقرارات التدقيق المسجلة سابقاً:</strong>
                            <p class="mb-0 text-dark small mt-0.5">${escapeHtml(r.rejection_reason)}</p>
                        </div>
                    </div>
                ` : ''}

                {{-- 2. الهيكل المصنف للمراجعة الأكاديمية والوثائق --}}
                <div class="review-grid-layout">
                    
                    {{-- العمود الأيمن: المؤهل الأكاديمي والبيانات الشخصية وولي الأمر --}}
                    <div class="d-flex flex-column gap-3">
                        
                        {{-- كرت الأهلية الأكاديمية والتخصص المطلوب --}}
                        <div class="review-panel-card">
                            <div class="review-panel-header">
                                <span><i class="bi bi-mortarboard-fill text-navy me-1.5"></i>المؤهل الأكاديمي والتخصص المطلوب</span>
                                <span class="badge bg-blue-subtle text-blue font-monospace">بيانات الثانوية</span>
                            </div>
                            <div class="review-panel-body">
                                
                                <div class="academic-highlight-box">
                                    <div>
                                        <span class="text-muted small d-block mb-1">التخصص المرغوب به:</span>
                                        <h4 class="mb-0 text-navy fw-bold" style="font-size:1.05rem;">
                                            <i class="bi bi-bookmark-star-fill text-gold me-1"></i>${escapeHtml(r.desired_spec)}
                                        </h4>
                                    </div>
                                    <div class="academic-score-pill">
                                        <span class="text-muted d-block" style="font-size:10px;">النسبة الثانوية</span>
                                        <strong class="text-teal font-monospace" style="font-size:1.3rem;">${r.sec_avg || '—'}%</strong>
                                        <span class="badge bg-success-subtle text-success d-block mt-0.5" style="font-size:10px;">${appreciation}</span>
                                    </div>
                                </div>

                                <table class="clean-data-table">
                                    <tbody>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-123 me-1 text-muted"></i>رقم الجلوس:</td>
                                            <td class="val-col">
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="font-monospace text-primary fw-bold fs-6">#${escapeHtml(r.seat_number || '—')}</span>
                                                    ${r.seat_number ? `
                                                        <a href="http://127.0.0.1:8080/ministry_exams_service/public/?seat_number=${encodeURIComponent(r.seat_number)}&grad_year=${encodeURIComponent(r.grad_year || 2024)}&section=${encodeURIComponent(r.sec_section || 'علمي')}" 
                                                           target="_blank" 
                                                           class="badge bg-warning-subtle text-dark border border-warning px-2.5 py-1 text-decoration-none fw-bold d-inline-flex align-items-center gap-1" 
                                                           title="الاستعلام والتحقق من النتيجة في بوابة الوزارة">
                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                            <span>فحص النتيجة بالوزارة</span>
                                                        </a>
                                                    ` : ''}
                                                </div>
                                            </td>
                                            <td class="label-col"><i class="bi bi-diagram-2 me-1 text-muted"></i>القسم:</td>
                                            <td class="val-col"><span class="badge bg-light text-dark border">${escapeHtml(r.sec_section || 'علمي')}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-award me-1 text-muted"></i>المجموع العام:</td>
                                            <td class="val-col font-monospace">${r.total_score || '—'} درجة</td>
                                            <td class="label-col"><i class="bi bi-calendar-event me-1 text-muted"></i>سنة التخرج:</td>
                                            <td class="val-col font-monospace">${escapeHtml(r.grad_year || '—')}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-building me-1 text-muted"></i>المدرسة الثانوية:</td>
                                            <td class="val-col" colspan="3">${escapeHtml(r.secondary_school || '—')}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        {{-- كرت البيانات الشخصية ومكان الإقامة --}}
                        <div class="review-panel-card">
                            <div class="review-panel-header">
                                <span><i class="bi bi-person-lines-fill text-navy me-1.5"></i>البيانات الشخصية والإقامة</span>
                            </div>
                            <div class="review-panel-body">
                                <table class="clean-data-table">
                                    <tbody>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-gender-ambiguous me-1 text-primary"></i>الجنس:</td>
                                            <td class="val-col">${escapeHtml(r.gender || '—')}</td>
                                            <td class="label-col"><i class="bi bi-flag me-1 text-primary"></i>الجنسية:</td>
                                            <td class="val-col">${escapeHtml(r.nationality || 'يمني')}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-calendar-date me-1 text-primary"></i>تاريخ الميلاد:</td>
                                            <td class="val-col font-monospace">${res.birth_date_formatted || '—'}</td>
                                            <td class="label-col"><i class="bi bi-geo me-1 text-primary"></i>مكان الميلاد:</td>
                                            <td class="val-col">${escapeHtml(r.birth_place || '—')}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-house-door me-1 text-primary"></i>عنوان السكن:</td>
                                            <td class="val-col" colspan="3">
                                                ${escapeHtml(r.province || 'أمانة العاصمة')} — ${escapeHtml(r.residence || '—')} (${escapeHtml(r.home_address || '—')})
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- كرت بيانات التواصل وولي الأمر --}}
                        <div class="review-panel-card">
                            <div class="review-panel-header">
                                <span><i class="bi bi-telephone-fill text-navy me-1.5"></i>معلومات التواصل وولي الأمر</span>
                            </div>
                            <div class="review-panel-body">
                                <table class="clean-data-table">
                                    <tbody>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-telephone me-1 text-primary"></i>هاتف الطالب:</td>
                                            <td class="val-col">
                                                <span dir="ltr" class="font-monospace fw-bold text-navy">${escapeHtml(r.phone || '—')}</span>
                                                ${r.phone ? `
                                                    <a href="https://wa.me/${encodeURIComponent((r.phone.startsWith('7') ? '967' + r.phone : r.phone).replace(/[^0-9]/g, ''))}" target="_blank" class="btn btn-outline-success btn-xs py-0 px-1.5 ms-1" title="مراسلة عبر واتساب">
                                                        <i class="bi bi-whatsapp"></i>
                                                    </a>
                                                ` : ''}
                                            </td>
                                            <td class="label-col"><i class="bi bi-envelope me-1 text-primary"></i>البريد:</td>
                                            <td class="val-col font-monospace text-xs">${escapeHtml(r.email || '—')}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-person me-1 text-primary"></i>اسم ولي الأمر:</td>
                                            <td class="val-col">${escapeHtml(r.guardian_name || '—')}</td>
                                            <td class="label-col"><i class="bi bi-people me-1 text-primary"></i>صلة القرابة:</td>
                                            <td class="val-col">${escapeHtml(r.guardian_rel || 'أب')} (${escapeHtml(r.guardian_occup || '—')})</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col"><i class="bi bi-phone me-1 text-primary"></i>هاتف ولي الأمر:</td>
                                            <td class="val-col" colspan="3">
                                                <span dir="ltr" class="font-monospace">${escapeHtml(r.gu_phone || '—')}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- العمود الأيسر: تدقيق الوثائق وسند سداد الرسوم --}}
                    <div class="d-flex flex-column gap-3">
                        <div class="review-panel-card">
                            <div class="review-panel-header">
                                <span><i class="bi bi-file-earmark-check-fill text-navy me-1.5"></i>ملف الوثائق والمستندات المرفوعة</span>
                                <span class="badge bg-light text-navy border">${(r.reg_docs && r.reg_docs.length) || 0} مرفقات</span>
                            </div>
                            <div class="review-panel-body">
                                ${docsListHtml}
                            </div>
                        </div>

                        ${paymentSectionHtml}
                    </div>

                </div>

            </div>
        `;

        let decisionActionsHtml = '';
        if (r.status === 'مقبول') {
            decisionActionsHtml = `
                <button type="button" class="btn btn-success btn-sm fw-bold px-3" disabled>
                    <i class="bi bi-check-circle-fill me-1"></i>الطلب مقبول رسمياً
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm fw-bold px-3" onclick="openNeedsCompletionModal(${r.req_id}, '${escapeHtml(r.full_name)}')">
                    <i class="bi bi-pencil-square me-1"></i>طلب استكمال وثائق
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold px-3" onclick="openRejectModal(${r.req_id}, '${escapeHtml(r.full_name)}')">
                    <i class="bi bi-x-circle me-1"></i>تغيير إلى مرفوض
                </button>
            `;
        } else if (r.status === 'مرفوض') {
            decisionActionsHtml = `
                <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm" onclick="quickAcceptRequest(${r.req_id})">
                    <i class="bi bi-check-circle-fill me-1"></i>إعادة قبول واعتماد الطلب
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm fw-bold px-3" onclick="openNeedsCompletionModal(${r.req_id}, '${escapeHtml(r.full_name)}')">
                    <i class="bi bi-pencil-square me-1"></i>طلب استكمال وثائق
                </button>
                <button type="button" class="btn btn-danger btn-sm fw-bold px-3" disabled>
                    <i class="bi bi-x-circle me-1"></i>الطلب مرفوض
                </button>
            `;
        } else {
            decisionActionsHtml = `
                <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm" onclick="quickAcceptRequest(${r.req_id})">
                    <i class="bi bi-check-circle-fill me-1"></i>قبول واعتماد الطلب
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm fw-bold px-3" onclick="openNeedsCompletionModal(${r.req_id}, '${escapeHtml(r.full_name)}')">
                    <i class="bi bi-pencil-square me-1"></i>طلب استكمال وثائق
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold px-3" onclick="openRejectModal(${r.req_id}, '${escapeHtml(r.full_name)}')">
                    <i class="bi bi-x-circle me-1"></i>رفض الطلب
                </button>
            `;
        }

        footer.innerHTML = `
            <div class="review-modal-footer-wrap">
                <div class="review-footer-actions-group">
                    ${decisionActionsHtml}
                </div>
                <div class="review-footer-tools-group">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="window.print()" title="طباعة ملف الطلب">
                        <i class="bi bi-printer me-1"></i>طباعة
                    </button>
                    <button type="button" class="btn btn-secondary-light btn-sm px-3" onclick="closeModal('requestDetailsModal')">
                        <i class="bi bi-x-lg me-1"></i>إغلاق
                    </button>
                </div>
            </div>
        `;
    })
    .catch(() => {
        body.innerHTML = '<div class="alert alert-danger">حدث خطأ أثناء الاتصال بالخادم.</div>';
    });
}

function quickAcceptRequest(reqId) {
    if (!confirm('هل أنت متأكد من قبول هذا الطلب رسمياً؟')) return;

    fetch(`/university/requests/${reqId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: 'مقبول' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('requestDetailsModal');
            updateRowStatusUI(reqId, 'مقبول', 'success');
        } else {
            showToast(data.message || 'حدث خطأ أثناء تحديث الحالة', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function openRejectModal(reqId, studentName) {
    closeModal('requestDetailsModal');
    document.getElementById('rejectReqId').value = reqId;
    document.getElementById('rejectModalStudentName').textContent = `تحديد سبب رفض طلب الطالب: ${studentName}`;
    document.getElementById('rejection_reason').value = '';
    openModal('rejectReasonModal');
}

function submitRejectRequest(e) {
    e.preventDefault();
    const reqId = document.getElementById('rejectReqId').value;
    const reason = document.getElementById('rejection_reason').value.trim();

    fetch(`/university/requests/${reqId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: 'مرفوض', rejection_reason: reason })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('rejectReasonModal');
            updateRowStatusUI(reqId, 'مرفوض', 'danger');
        } else {
            showToast(data.message || 'فشل تحديث الطلب', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function openNeedsCompletionModal(reqId, studentName) {
    closeModal('requestDetailsModal');
    document.getElementById('completionReqId').value = reqId;
    document.getElementById('completionModalStudentName').textContent = `تحديد النواقص لطلب الطالب: ${studentName}`;
    document.getElementById('completion_notes').value = '';
    openModal('needsCompletionModal');
}

function submitNeedsCompletionRequest(e) {
    e.preventDefault();
    const reqId = document.getElementById('completionReqId').value;
    const notes = document.getElementById('completion_notes').value.trim();

    const btn = document.getElementById('btnConfirmCompletion');
    if (btn) btn.disabled = true;

    fetch(`/university/requests/${reqId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: 'يتطلب استكمال', rejection_reason: notes })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('needsCompletionModal');
            updateRowStatusUI(reqId, 'يتطلب استكمال', 'info');
        } else {
            showToast(data.message || 'فشل تحديث الطلب', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'))
    .finally(() => {
        if (btn) btn.disabled = false;
    });
}

function updateRowStatusUI(reqId, statusText, colorType) {
    const pill = document.getElementById(`statusPill-${reqId}`);
    if (pill) {
        pill.textContent = statusText;
        pill.className = `status-pill status-pill-${colorType}`;
    }
    const row = document.querySelector(`tr[data-status].app-row:has(#statusPill-${reqId})`);
    if (row) {
        row.setAttribute('data-status', statusText);
    }
}

// ==========================================
// التحقق من كود الإحالة (Referral Codes)
// ==========================================
function submitVerifyReferral(e) {
    e.preventDefault();
    const input = document.getElementById('verifyReferralInput');
    const resultBox = document.getElementById('referralVerifyResult');
    const code = input.value.trim();
    if (!code) return;

    resultBox.style.display = 'block';
    resultBox.className = 'verification-result-box loading';
    resultBox.innerHTML = '<div class="spinner-border spinner-border-sm text-navy" role="status"></div> جاري التحقق من كود الإحالة...';

    fetch('{{ route('university.referrals.verify') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ referral_code: code })
    })
    .then(r => r.json().then(data => ({ status: r.status, body: data })))
    .then(res => {
        const data = res.body;
        if (data.success) {
            resultBox.className = 'verification-result-box success';
            resultBox.innerHTML = `
                <div class="result-success-content">
                    <div class="result-icon">✅</div>
                    <div>
                        <strong class="text-success">${escapeHtml(data.message)}</strong>
                        <p class="text-sm mt-1 mb-0">تم اعتماد الكود وحفظ الزيارة رسمياً، الطالب مؤهل للحصول على خصم التنسيق الجامعي.</p>
                    </div>
                </div>
            `;
            showToast('تم التحقق من كود الإحالة بنجاح!', 'success');
        } else {
            resultBox.className = 'verification-result-box error';
            resultBox.innerHTML = `
                <div class="result-error-content">
                    <div class="result-icon">⚠️</div>
                    <div>
                        <strong class="text-danger">${escapeHtml(data.message || 'كود الإحالة غير صحيح')}</strong>
                    </div>
                </div>
            `;
        }
    })
    .catch(() => {
        resultBox.className = 'verification-result-box error';
        resultBox.innerHTML = '<div class="text-danger">تعذر الاتصال بالخادم للتحقق من الكود.</div>';
    });
}

// ==========================================
// وسائل الدفع والإعلانات (Payments & Announcements)
// ==========================================
function openAnnouncementModal() {
    document.getElementById('announcementForm').reset();
    openModal('announcementModal');
}

function submitAnnouncementForm(e) {
    e.preventDefault();
    const form = document.getElementById('announcementForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('announcementModal');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'فشل نشر الإعلان', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function deleteAnnouncement(id) {
    if (!confirm('هل تريد بالتأكيد حذف هذا الإعلان؟')) return;

    fetch(`/university/announcements/${id}/delete`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const card = document.getElementById(`announcementCard-${id}`);
            if (card) card.remove();
        } else {
            showToast(data.message || 'فشل حذف الإعلان', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء الحذف', 'error'));
}

function openPaymentMethodModal() {
    const form = document.getElementById('paymentMethodForm');
    if (form) {
        form.reset();
        form.action = "{{ route('university.payments.store') }}";
    }
    const title = document.querySelector('#paymentMethodModal .modal-title');
    if (title) title.textContent = 'إضافة وسيلة أو حساب دفع';
    openModal('paymentMethodModal');
}

function editPaymentMethod(pm) {
    const form = document.getElementById('paymentMethodForm');
    if (!form) return;
    form.reset();
    form.action = `/university/payments/${pm.method_id}/update`;

    const title = document.querySelector('#paymentMethodModal .modal-title');
    if (title) title.textContent = 'تعديل وسيلة الدفع والحساب';

    if (document.getElementById('pm_method_name')) document.getElementById('pm_method_name').value = pm.method_name || '';
    if (document.getElementById('pm_method_type')) document.getElementById('pm_method_type').value = pm.method_type || 'حساب بنكي';
    if (document.getElementById('pm_account_number')) document.getElementById('pm_account_number').value = pm.account_number || '';
    if (document.getElementById('pm_account_name')) document.getElementById('pm_account_name').value = pm.account_name || '';
    if (document.getElementById('pm_instructions')) document.getElementById('pm_instructions').value = pm.instructions || '';

    openModal('paymentMethodModal');
}

// اعتماد وتأكيد السداد المالي للطلب
function verifyApplicationPayment(reqId, status, notes = '') {
    if (status === 'معتمد ومؤكد') {
        if (!confirm('هل أنت متأكد من اعتماد وتأكيد استلام رسوم التنسيق لهذا الطلب؟')) return;
    }

    fetch(`/university/requests/${reqId}/payment-verify`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            ver_status: status,
            ver_result: notes || (status === 'معتمد ومؤكد' ? 'تمت مطابقة السند وتأكيد السداد المالي بنجاح.' : '')
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'تم تحديث حالة السداد بنجاح', 'success');
            // إعادة تحميل بيانات الطلب في النافذة
            viewRequestDetails(reqId);
        } else {
            showToast(data.message || 'فشل تحديث حالة السداد', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

// طلب إدخال سبب رفض السند
function rejectApplicationPaymentPrompt(reqId) {
    const reason = prompt('يرجى كتابة سبب رفض سند السداد (سيظهر للطالب لإعادة الرفع):', 'رقم الإشعار غير مطابق أو صورة السند غير واضحة');
    if (reason && reason.trim()) {
        verifyApplicationPayment(reqId, 'مرفوض', reason.trim());
    }
}

function submitPaymentMethodForm(e) {
    e.preventDefault();
    const form = document.getElementById('paymentMethodForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('paymentMethodModal');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'فشل حفظ وسيلة الدفع', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function togglePaymentActive(id) {
    fetch(`/university/payments/${id}/toggle`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const card = document.getElementById(`paymentCard-${id}`);
            if (card) {
                card.classList.toggle('pm-active', data.is_active);
                card.classList.toggle('pm-inactive', !data.is_active);
            }
        }
    })
    .catch(() => showToast('حدث خطأ أثناء تعديل الحالة', 'error'));
}

function deletePaymentMethod(id) {
    if (!confirm('هل تريد بالتأكيد حذف وسيلة الدفع هذه؟')) return;

    fetch(`/university/payments/${id}/delete`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const card = document.getElementById(`paymentCard-${id}`);
            if (card) card.remove();
        } else {
            showToast(data.message || 'فشل حذف وسيلة الدفع', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء الحذف', 'error'));
}

// ==========================================
// إعدادات الحساب وحالة القبول وكلمة المرور
// ==========================================
function onAdmissionSwitchChange(isChecked) {
    const desc = document.getElementById('admissionStatusDescription');
    if (desc) {
        desc.textContent = isChecked ? 
            'القبول والتسجيل مفتوح حالياً ويستقبل طلبات الطلبة.' : 
            'القبول مغلق حالياً، ولا يمكن للطلبة تقديم طلبات جديدة.';
    }
}

function submitAdmissionSettings(e) {
    e.preventDefault();
    const isChecked = document.getElementById('admissionStatusSwitch').checked;
    const statusVal = isChecked ? 'open' : 'closed';

    fetch('{{ route('university.settings.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ admission_status: statusVal })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const headerPill = document.getElementById('headerAdmissionStatusPill');
            if (headerPill) {
                headerPill.className = 'status-indicator-pill ' + (isChecked ? 'status-open' : 'status-closed');
                headerPill.querySelector('.status-text').textContent = isChecked ? 'القبول مفتوح' : 'القبول مغلق';
            }
        } else {
            showToast(data.message || 'فشل تحديث الحالة', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ في الاتصال بالخادم', 'error'));
}

function submitChangePassword(e) {
    e.preventDefault();
    const form = document.getElementById('universityPasswordForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json().then(data => ({ status: r.status, body: data })))
    .then(res => {
        if (res.body.success) {
            showToast('تم تغيير كلمة المرور بنجاح!', 'success');
            form.reset();
        } else {
            showToast(res.body.message || 'كلمة المرور الحالية غير صحيحة أو البيانات غير متطابقة', 'error');
        }
    })
    .catch(() => showToast('حدث خطأ أثناء تغيير كلمة المرور', 'error'));
}

// ==========================================
// معاينة الصور فورياً عند الاختيار (Logo & Campus Image)
// ==========================================
function previewSelectedImage(input, imgElementId, btnTextId, chosenText, emptyTagId = null) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(imgElementId);
            if (img) {
                img.src = e.target.result;
                img.style.opacity = '1';
                img.classList.remove('campus-placeholder');
            }
            if (btnTextId) {
                const btnText = document.getElementById(btnTextId);
                if (btnText) btnText.textContent = chosenText;
            }
            if (emptyTagId) {
                const tag = document.getElementById(emptyTagId);
                if (tag) tag.style.display = 'none';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ==========================================
// البحث السريع الموحد (Live Global Search)
// ==========================================
const globalLiveInput = document.getElementById('globalLiveSearch');
if (globalLiveInput) {
    globalLiveInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        const clearBtn = document.getElementById('searchClearBtn');
        if (clearBtn) clearBtn.style.display = query ? 'inline-block' : 'none';

        // إذا كان المستخدم في تبويب الكليات
        if (window.currentActiveTab === 'colleges') {
            const specSelect = document.getElementById('specialtyFilterSelect');
            if (specSelect) {
                for (let i = 0; i < specSelect.options.length; i++) {
                    const opt = specSelect.options[i];
                    if (opt.text.toLowerCase().includes(query)) {
                        specSelect.selectedIndex = i;
                        onSpecialtySelectChange(opt.value);
                        break;
                    }
                }
            }
        } else if (window.currentActiveTab === 'applications') {
            searchApplicationsTable(query);
        }
    });
}

function clearLiveSearch() {
    if (globalLiveInput) {
        globalLiveInput.value = '';
        const clearBtn = document.getElementById('searchClearBtn');
        if (clearBtn) clearBtn.style.display = 'none';
        if (window.currentActiveTab === 'applications') searchApplicationsTable('');
    }
}

// ==========================================
// وظائف النوافذ المنبثقة والإشعارات (Modals & Toast)
// ==========================================
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});

function showToast(message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container-fixed';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast-pill-alert toast-${type}`;
    
    let icon = '✅';
    if (type === 'error') icon = '❌';
    if (type === 'warning') icon = '⚠️';
    if (type === 'info') icon = 'ℹ️';

    toast.innerHTML = `<span class="toast-ico">${icon}</span> <span class="toast-msg">${escapeHtml(message)}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('toast-show');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('toast-show');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

function escapeHtml(text) {
    if (!text) return '';
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}
</script>
