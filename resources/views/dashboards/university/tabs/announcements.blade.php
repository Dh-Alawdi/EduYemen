{{-- تبويب الأخبار والإعلانات الجامعية --}}
<div class="tab-pane-content" id="announcementsTabContent">

    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">الأخبار والإعلانات والتعاميم الجامعية</h2>
            <p class="tab-subtitle">نشر التعاميم الأكاديمية، مواعيد التنسيق، إعلانات المفاضلة، وبدء العام الدراسي.</p>
        </div>

        <div class="banner-actions">
            <button type="button" class="btn btn-navy btn-sm" onclick="openAnnouncementModal()">
                <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                <span>نشر إعلان جديد</span>
            </button>
        </div>
    </div>

    <!-- شبكة الإعلانات المنشورة -->
    <div class="card-box mb-4">
        <div class="card-box-header">
            <div class="header-icon-badge bg-teal-subtle text-teal">
                <svg class="ico" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 8a3 3 0 00-3-3M21 8a6 6 0 00-6-6M3 11a1 1 0 011-1h2.586l4.707-4.707A1 1 0 0113 6v12a1 1 0 01-1.707.707L6.586 14H4a1 1 0 01-1-1v-2z"/></svg>
            </div>
            <div>
                <h3 class="card-box-title">سجل الإعلانات الموجهة للطلبة</h3>
                <p class="card-box-desc">تظهر هذه الإعلانات في صفحة الجامعة العامة وفي إشعارات الطلبة المهتمين</p>
            </div>
        </div>

        <div class="announcements-list-grid">
            @forelse($announcements as $ann)
                <div class="announcement-item-card" id="announcementCard-{{ $ann->announcement_id }}">
                    <div class="ann-item-header">
                        <span class="ann-category-badge">{{ $ann->category ?? 'إعلان عام' }}</span>
                        <div class="ann-date-and-action">
                            <span class="ann-date">{{ $ann->created_at ? $ann->created_at->format('Y-m-d') : '—' }}</span>
                            <button type="button" class="btn btn-link text-danger btn-xs" onclick="deleteAnnouncement({{ $ann->announcement_id }})" title="حذف الإعلان">
                                <svg class="ico" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                    <h4 class="ann-title">{{ $ann->title }}</h4>
                    <p class="ann-content">{{ $ann->content }}</p>
                </div>
            @empty
                <div class="col-span-full text-center py-5 text-muted">
                    <p>لا توجد إعلانات أو تعاميم منشورة حالياً.</p>
                    <button type="button" class="btn btn-navy btn-sm mt-2" onclick="openAnnouncementModal()">
                        + نشر أول إعلان للجامعة
                    </button>
                </div>
            @endforelse
        </div>
    </div>

</div>
