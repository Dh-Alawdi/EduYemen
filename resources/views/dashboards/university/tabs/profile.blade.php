{{-- تبويب بيانات وهوية الجامعة --}}
<div class="tab-pane-content" id="profileTabContent">

    <!-- شريط الرأس للتبويب مع زر الرجوع ومعاينة الطالب -->
    <div class="tab-header-banner">
        <div class="banner-title-group">
            <button type="button" class="btn-back-crumb" onclick="switchTab('overview')">
                <svg class="ico" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                <span>العودة للرئيسية</span>
            </button>
            <h2 class="tab-main-title">الملف التعريفي وهوية الجامعة</h2>
            <p class="tab-subtitle">إدارة وتحديث البيانات الأساسية، شعار الجامعة، صورة الحرم، وبيانات التواصل المعتمدة.</p>
        </div>

        <div class="banner-actions">
            <!-- زر معاينة كيف تظهر الصفحة للطالب -->
            <a href="{{ route('universities.show', $university->university_id) }}" class="btn btn-preview-live" title="معاينة الصفحة العامة للجامعة كما تظهر للطلبة والزوار">
                <svg class="ico" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span>معاينة كيف تظهر الصفحة للطالب</span>
            </a>
        </div>
    </div>

    <!-- نموذج تحديث بيانات الجامعة مع دعم رفع الشعار وصورة الحرم -->
    <form id="universityProfileForm" action="{{ route('university.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-editor-form">
        @csrf

        <!-- قسم صور وهوية الجامعة (الشعار + صورة الحرم الجامعي) -->
        <div class="card-box mb-4">
            <div class="card-box-header">
                <div class="header-icon-badge bg-gold-subtle text-gold">
                    <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div>
                    <h3 class="card-box-title">صور وهوية الحرم الجامعي</h3>
                    <p class="card-box-desc">قم برفع أو تحديث الشعار الرسمي وصورة الحرم لتعزيز ظهور الجامعة أمام الطلاب</p>
                </div>
            </div>

            <div class="branding-media-grid">
                <!-- 1. شعار الجامعة الرسمي -->
                <div class="media-upload-card">
                    <div class="media-preview-box logo-box">
                        <img id="logoPreviewImg" src="{{ $university->logo_url }}" alt="شعار الجامعة" class="logo-image-element" onerror="this.src='{{ asset('logo.png') }}';">
                    </div>
                    <div class="media-info-box">
                        <h4 class="media-label">شعار الجامعة الرسمي</h4>
                        <p class="media-hint">يفضل صيغة PNG بخلفية شفافة أو JPG بدقة لا تقل عن 300x300 بكسل.</p>
                        <div class="media-btn-wrapper">
                            <label for="logoFileInput" class="btn btn-outline-navy btn-sm upload-btn-label">
                                <svg class="ico" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span id="logoBtnText">{{ $university->logo ? 'تعديل شعار الجامعة' : 'إضافة شعار الجامعة' }}</span>
                            </label>
                            <input type="file" id="logoFileInput" name="logo" accept="image/*" class="d-none" onchange="previewSelectedImage(this, 'logoPreviewImg', 'logoBtnText', 'تم اختيار الشعار')">
                        </div>
                    </div>
                </div>

                <!-- 2. صورة الحرم الجامعي -->
                <div class="media-upload-card campus-card">
                    <div class="media-preview-box campus-box">
                        @if($university->campus_image_url)
                            <img id="campusPreviewImg" src="{{ $university->campus_image_url }}" alt="صورة الحرم الجامعي" class="campus-image-element">
                        @else
                            <img id="campusPreviewImg" src="{{ asset('img/صنعاء.png') }}" alt="حرم الجامعة" class="campus-image-element campus-placeholder" style="opacity: 0.4;">
                            <div class="campus-empty-tag" id="campusEmptyTag">لم يتم رفع صورة حرم جامعي</div>
                        @endif
                    </div>
                    <div class="media-info-box">
                        <h4 class="media-label">صورة الحرم الجامعي (الغلاف التعريفي)</h4>
                        <p class="media-hint">صورة أفقية واضحة للمبنى الرئيسي أو القاعات الجامعية (الحد الأقصى 5MB).</p>
                        <div class="media-btn-wrapper">
                            <label for="campusFileInput" class="btn btn-outline-navy btn-sm upload-btn-label">
                                <svg class="ico" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span id="campusBtnText">{{ $university->campus_image ? 'تعديل صورة الحرم' : 'إضافة صورة الحرم' }}</span>
                            </label>
                            <input type="file" id="campusFileInput" name="campus_image" accept="image/*" class="d-none" onchange="previewSelectedImage(this, 'campusPreviewImg', 'campusBtnText', 'تم اختيار صورة الحرم', 'campusEmptyTag')">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم البيانات الأكاديمية والرسمية (شبكة حقلين بسطر واحد على الهاتف والكمبيوتر) -->
        <div class="card-box mb-4">
            <div class="card-box-header">
                <div class="header-icon-badge bg-blue-subtle text-blue">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h3 class="card-box-title">البيانات الأساسية والتواصل</h3>
                    <p class="card-box-desc">البيانات المطابقة لخصائص جداول قاعدة البيانات والمعتمدة رسمياً</p>
                </div>
            </div>

            <!-- شبكة الحقول المتجاوبة: حقلين بسطر واحد حتى على الهواتف الذكية -->
            <div class="mobile-two-col-grid">
                <!-- اسم الجامعة -->
                <div class="form-group">
                    <label for="prof_university_name" class="form-label required">اسم الجامعة الرسمي</label>
                    <input type="text" id="prof_university_name" name="university_name" class="form-control" value="{{ old('university_name', $university->university_name) }}" required placeholder="مثال: جامعة الملكة أروى">
                </div>

                <!-- تصنيف / نوع الجامعة -->
                <div class="form-group">
                    <label for="prof_university_type" class="form-label required">نوع الجامعة</label>
                    <select id="prof_university_type" name="university_type" class="form-control" required>
                        <option value="أهلية" {{ $university->university_type == 'أهلية' ? 'selected' : '' }}>جامعة أهلية</option>
                        <option value="حكومية" {{ $university->university_type == 'حكومية' ? 'selected' : '' }}>جامعة حكومية</option>
                        <option value="خاصة" {{ $university->university_type == 'خاصة' ? 'selected' : '' }}>جامعة خاصة</option>
                    </select>
                </div>

                <!-- سنة التأسيس -->
                <div class="form-group">
                    <label for="prof_year_establ" class="form-label">سنة التأسيس</label>
                    <input type="number" id="prof_year_establ" name="year_establ" class="form-control" value="{{ old('year_establ', $university->year_establ) }}" min="1900" max="2035" placeholder="مثال: 1996">
                </div>

                <!-- البريد الإلكتروني الرسمي -->
                <div class="form-group">
                    <label for="prof_email" class="form-label">البريد الإلكتروني الرسمي</label>
                    <input type="email" id="prof_email" name="email" class="form-control" value="{{ old('email', $university->email ?? Auth::user()->email) }}" placeholder="info@university.edu.ye">
                </div>

                <!-- الهاتف الرئيسي -->
                <div class="form-group">
                    <label for="prof_phone" class="form-label">رقم الهاتف الرئيسي</label>
                    <input type="text" id="prof_phone" name="phone" class="form-control" value="{{ old('phone', $university->phone ?? Auth::user()->phone) }}" placeholder="مثال: 01450000 أو 770000000">
                </div>

                <!-- هاتف الاستفسارات والتواصل -->
                <div class="form-group">
                    <label for="prof_contact_phone" class="form-label">هاتف الاستفسارات والتنسيق</label>
                    <input type="text" id="prof_contact_phone" name="contact_phone" class="form-control" value="{{ old('contact_phone', $university->contact_phone) }}" placeholder="مثال: 771234567">
                </div>

                <!-- الموقع الإلكتروني -->
                <div class="form-group">
                    <label for="prof_website" class="form-label">الموقع الإلكتروني</label>
                    <input type="url" id="prof_website" name="website" class="form-control" value="{{ old('website', $university->website) }}" placeholder="https://www.university.edu.ye">
                </div>

                <!-- المديرية / المنطقة -->
                <div class="form-group">
                    <label for="prof_district" class="form-label">المديرية / المنطقة</label>
                    <input type="text" id="prof_district" name="district" class="form-control" value="{{ old('district', $university->district) }}" placeholder="مثال: حدة، معين، التحرير">
                </div>

                <!-- العنوان التفصيلي (يمتد على عمودين في حالة الرغبة، أو حقل متجاوب) -->
                <div class="form-group col-span-full-mobile">
                    <label for="prof_address" class="form-label required">العنوان التفصيلي وموقع الحرم الرئيسي</label>
                    <input type="text" id="prof_address" name="address" class="form-control" value="{{ old('address', $university->address) }}" required placeholder="المدينة، الشارع، المعلم المجاور">
                </div>

                <!-- الوصف والنبذة التعريفية (يمتد على العرض الكامل) -->
                <div class="form-group col-span-full-mobile">
                    <label for="prof_description" class="form-label">نبذة تعريفية شاملة عن الجامعة ورسالتها الأكاديمية</label>
                    <textarea id="prof_description" name="description" rows="4" class="form-control" placeholder="اكتب نبذة تعريفية بالجامعة، الرؤية، والرسالة وقيم التميز الأكاديمي...">{{ old('description', $university->description) }}</textarea>
                </div>
            </div>

            <!-- أزرار الحفظ والمعاينة في أسفل النموذج -->
            <div class="form-actions-bar">
                <button type="submit" class="btn btn-navy btn-md" id="saveProfileBtn">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span>حفظ وتحديث بيانات الجامعة</span>
                </button>

                <a href="{{ route('universities.show', $university->university_id) }}" class="btn btn-secondary-light btn-md" title="معاينة الصفحة العامة">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>معاينة صفحة الطالب</span>
                </a>
            </div>
        </div>
    </form>
</div>
