{{-- جميع النوافذ المنبثقة (Modals) للوحة تحكم الجامعة --}}

<!-- 1. نافذة تفاصيل طلب التسجيل ومراجعة الوثائق -->
<div class="modal-overlay" id="requestDetailsModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-xl">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-blue-subtle text-blue">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                </div>
                <div>
                    <h3 class="modal-title" id="reqModalTitle">تفاصيل طلب التنسيق رقم #...</h3>
                    <p class="modal-subtitle">مراجعة بيانات الطالب، المؤهل الثانوي، والمستندات المرفوعة</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('requestDetailsModal')">&times;</button>
        </div>

        <div class="modal-body" id="reqModalBody">
            <div class="text-center py-4 text-muted">
                <div class="spinner-border text-navy" role="status"></div>
                <p class="mt-2">جاري تحميل بيانات الطلب...</p>
            </div>
        </div>

        <div class="modal-footer" id="reqModalFooter">
            <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('requestDetailsModal')">إغلاق</button>
        </div>
    </div>
</div>

<!-- 2. نافذة سبب رفض طلب التنسيق -->
<div class="modal-overlay" id="rejectReasonModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-danger-subtle text-danger">
                    <svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">تحديد سبب رفض الطلب</h3>
                    <p class="modal-subtitle" id="rejectModalStudentName">توضيح سبب الرفض للطالب</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('rejectReasonModal')">&times;</button>
        </div>

        <form id="rejectReasonForm" onsubmit="submitRejectRequest(event)">
            @csrf
            <input type="hidden" id="rejectReqId" name="req_id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="rejection_reason" class="form-label required">سبب الرفض (يظهر للطالب في لوحة تحكمه)</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" class="form-control" required placeholder="مثال: عدم استيفاء الحد الأدنى لمعدل الثانوية العامة للتخصص، أو نقص بالوثائق المرفوعة..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('rejectReasonModal')">إلغاء</button>
                <button type="submit" class="btn btn-danger btn-sm" id="btnConfirmReject">تأكيد رفض الطلب</button>
            </div>
        </form>
    </div>
</div>

<!-- 2.5 نافذة طلب استكمال بيانات/وثائق من الطالب -->
<div class="modal-overlay" id="needsCompletionModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-warning-subtle text-warning">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">طلب استكمال وثائق أو بيانات</h3>
                    <p class="modal-subtitle" id="completionModalStudentName">إرسال إشعار فوري للطالب بالمتطلبات الناقصة</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('needsCompletionModal')">&times;</button>
        </div>

        <form id="needsCompletionForm" onsubmit="submitNeedsCompletionRequest(event)">
            @csrf
            <input type="hidden" id="completionReqId" name="req_id">
            <div class="modal-body">
                <div class="form-group">
                    <label for="completion_notes" class="form-label required">الملاحظات والمتطلبات الناقصة (تصل للطالب في إشعار رسمي)</label>
                    <textarea id="completion_notes" name="completion_notes" rows="4" class="form-control" required placeholder="مثال: يرجى إعادة رفع صورة واضحة لشهادة الثانوية العامة وتصحيح رقم الجلوس..."></textarea>
                    <small class="text-muted d-block mt-1">سيتم إشعار الطالب فورياً ليقوم بفتح الاستمارة واستكمال النواقص وإعادة الإرسال.</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('needsCompletionModal')">إلغاء</button>
                <button type="submit" class="btn btn-warning btn-sm fw-bold" id="btnConfirmCompletion">إرسال طلب الاستكمال للطالب</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. نافذة إضافة / تعديل كلية -->
<div class="modal-overlay" id="collegeModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-blue-subtle text-blue">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title" id="collegeModalTitle">إضافة كلية جديدة</h3>
                    <p class="modal-subtitle">أدخل بيانات الكلية التابعة للجامعة</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('collegeModal')">&times;</button>
        </div>

        <form id="collegeForm" onsubmit="submitCollegeForm(event)">
            @csrf
            <input type="hidden" id="collegeModalId" name="college_id">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="modal_college_name" class="form-label required">اسم الكلية</label>
                    <input type="text" id="modal_college_name" name="college_name" class="form-control" required placeholder="مثال: كلية الهندسة وتكنولوجيا المعلومات">
                </div>

                <div class="form-group mb-3">
                    <label for="modal_college_desc" class="form-label">وصف الكلية</label>
                    <textarea id="modal_college_desc" name="description" rows="3" class="form-control" placeholder="نبذة عن الكلية وأقسامها..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('collegeModal')">إلغاء</button>
                <button type="submit" class="btn btn-navy btn-sm" id="btnSaveCollegeModal">حفظ الكلية</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. نافذة إضافة تخصص أكاديمي جديد -->
<div class="modal-overlay" id="specializationModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-lg">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-gold-subtle text-gold">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">إضافة تخصص أكاديمي جديد</h3>
                    <p class="modal-subtitle">ربط البرنامج الأكاديمي بالكلية مع تحديد الرسوم وشروط القبول الأولية</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('specializationModal')">&times;</button>
        </div>

        <form id="specializationForm" onsubmit="submitNewSpecialization(event)">
            @csrf
            <div class="modal-body">
                <div class="mobile-two-col-grid">
                    <div class="form-group col-span-full-mobile">
                        <label for="new_spec_college_id" class="form-label required">الكلية التابع لها</label>
                        <select id="new_spec_college_id" name="college_id" class="form-control" required>
                            @foreach($university->colleges as $c)
                                <option value="{{ $c->college_id }}">{{ $c->college_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-span-full-mobile">
                        <label for="new_spec_name" class="form-label required">اسم التخصص الأكاديمي</label>
                        <input type="text" id="new_spec_name" name="specialization_name" class="form-control" required placeholder="مثال: الأمن السيبراني والتحري الرقمي">
                    </div>

                    <div class="form-group">
                        <label for="new_spec_fees" class="form-label required">الرسوم الدراسية السنوية ($ أو ر.ي)</label>
                        <input type="number" id="new_spec_fees" name="tuition_fees" class="form-control" required min="0" step="any" placeholder="مثال: 1500">
                    </div>

                    <div class="form-group">
                        <label for="new_spec_min_avg" class="form-label required">الحد الأدنى للقبول (%)</label>
                        <input type="number" id="new_spec_min_avg" name="minimum_average" class="form-control" required min="50" max="100" step="0.1" value="70.0" placeholder="مثال: 75.0">
                    </div>

                    <div class="form-group">
                        <label for="new_spec_duration" class="form-label required">مدة الدراسة (بالسنوات)</label>
                        <select id="new_spec_duration" name="duration_years" class="form-control" required>
                            <option value="2">سنتان</option>
                            <option value="3">3 سنوات</option>
                            <option value="4" selected>4 سنوات</option>
                            <option value="5">5 سنوات</option>
                            <option value="6">6 سنوات</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="new_spec_language" class="form-label required">لغة التدريس</label>
                        <select id="new_spec_language" name="language" class="form-control" required>
                            <option value="العربية">العربية</option>
                            <option value="الإنجليزية">الإنجليزية</option>
                            <option value="العربية والإنجليزية">العربية والإنجليزية</option>
                        </select>
                    </div>

                    <div class="form-group col-span-full-mobile">
                        <label for="new_spec_desc" class="form-label">الوصف الأكاديمي والمهني</label>
                        <textarea id="new_spec_desc" name="description" rows="3" class="form-control" placeholder="وصف البرنامج ومخرجات التعليم..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('specializationModal')">إلغاء</button>
                <button type="submit" class="btn btn-gold btn-sm" id="btnSaveNewSpec">حفظ وإضافة التخصص</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. نافذة إضافة وثيقة مطلوبة للتسجيل -->
<div class="modal-overlay" id="addDocModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-gold-subtle text-gold">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M9 12h6M9 16h6M4 4h16v16H4z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">إضافة وثيقة مطلوبة للتسجيل</h3>
                    <p class="modal-subtitle">تحديد اسم الوثيقة ومواصفاتها للتخصص الحالي</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('addDocModal')">&times;</button>
        </div>

        <form id="addDocForm" onsubmit="submitAddDocItem(event)">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="modal_doc_name" class="form-label required">اسم الوثيقة المطلوبة</label>
                    <input type="text" id="modal_doc_name" class="form-control" required placeholder="مثال: أصل استمارة الثانوية العامة">
                </div>

                <div class="form-group mb-3">
                    <label for="modal_doc_desc" class="form-label">الوصف والشروط الخاصة بالوثيقة</label>
                    <input type="text" id="modal_doc_desc" class="form-control" placeholder="مثال: مصدقة ومعمدة من وزارة التربية والتعليم">
                </div>

                <div class="form-group mb-3">
                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="modal_doc_required" checked>
                        <span>هذه الوثيقة إجبارية لإتمام التنسيق</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('addDocModal')">إلغاء</button>
                <button type="submit" class="btn btn-navy btn-sm">إدراج الوثيقة للقائمة</button>
            </div>
        </form>
    </div>
</div>

<!-- 5-ب. نافذة تعديل وثيقة مطلوبة -->
<div class="modal-overlay" id="editDocModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-gold-subtle text-gold">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">تعديل الوثيقة المطلوبة</h3>
                    <p class="modal-subtitle">تعديل مسمى الوثيقة، الوصف، أو درجة إلزاميتها</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('editDocModal')">&times;</button>
        </div>

        <form id="editDocForm" onsubmit="submitEditDocItem(event)">
            <input type="hidden" id="modal_edit_doc_index" value="">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="modal_edit_doc_name" class="form-label required">اسم الوثيقة المطلوبة</label>
                    <input type="text" id="modal_edit_doc_name" class="form-control" required placeholder="مثال: أصل استمارة الثانوية العامة">
                </div>

                <div class="form-group mb-3">
                    <label for="modal_edit_doc_desc" class="form-label">الوصف والشروط الخاصة بالوثيقة</label>
                    <input type="text" id="modal_edit_doc_desc" class="form-control" placeholder="مثال: صورة طبق الأصل أو الأصل للمطابقة">
                </div>

                <div class="form-group mb-3">
                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="modal_edit_doc_required">
                        <span>هذه الوثيقة إجبارية (إلزامية) لإتمام التنسيق والقبول</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('editDocModal')">إلغاء</button>
                <button type="submit" class="btn btn-navy btn-sm">حفظ تعديل الوثيقة</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. نافذة إضافة أو تعديل خطة دراسية -->
<div class="modal-overlay" id="addPlanModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-purple-subtle text-purple">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title" id="planModalTitle">إضافة خطة دراسية للتخصص</h3>
                    <p class="modal-subtitle" id="planModalSubtitle">إدراج خطة أو مسار دراسي معتمد للتخصص الأكاديمي</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('addPlanModal')">&times;</button>
        </div>

        <form id="addPlanForm" onsubmit="submitPlanForm(event)">
            <input type="hidden" id="modal_plan_id" value="">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="modal_plan_name" class="form-label required">اسم الخطة الدراسية / المسار</label>
                    <input type="text" id="modal_plan_name" class="form-control" required placeholder="مثال: الخطة الأكاديمية المطورة (2025/2026) أو مسار الذكاء الاصطناعي">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="modal_plan_duration" class="form-label required">مدة الدراسة (بالسنوات)</label>
                        <input type="number" id="modal_plan_duration" class="form-control" required min="1" max="8" value="4">
                    </div>
                    <div class="col-6">
                        <label for="modal_plan_hours" class="form-label">إجمالي الساعات المعتمدة</label>
                        <input type="number" id="modal_plan_hours" class="form-control" min="0" max="300" placeholder="مثال: 136">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="modal_plan_status" class="form-label required">حالة اعتماد الخطة</label>
                    <select id="modal_plan_status" class="form-control" required>
                        <option value="معتمدة">معتمدة رسمياً</option>
                        <option value="نشطة">نشطة ومتاحة</option>
                        <option value="قيد الإعداد">قيد المراجعة والإعداد</option>
                        <option value="أرشيف">أرشيف (خطة قديمة)</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('addPlanModal')">إلغاء</button>
                <button type="submit" class="btn btn-purple btn-sm" id="btnSavePlan">حفظ الخطة الدراسية</button>
            </div>
        </form>
    </div>
</div>

<!-- 6.1 نافذة إضافة أو تعديل مادة دراسية -->
<div class="modal-overlay" id="courseModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-blue-subtle text-blue">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="modal-title" id="courseModalTitle">إضافة مادة دراسية</h3>
                    <p class="modal-subtitle" id="courseModalSubtitle">إدراج مقرر دراسي ضمن الفصل الدراسي المختار</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('courseModal')">&times;</button>
        </div>

        <form id="courseForm" onsubmit="submitCourseForm(event)">
            <input type="hidden" id="modal_course_id" value="">
            <input type="hidden" id="modal_course_semester" value="1">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="modal_course_name" class="form-label required">اسم المادة / المقرر الدراسي</label>
                    <input type="text" id="modal_course_name" class="form-control" required placeholder="مثال: مقدمة في البرمجة وتراكيب البيانات">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="modal_course_code" class="form-label">رمز / كود المادة</label>
                        <input type="text" id="modal_course_code" class="form-control" placeholder="مثال: CS101" dir="ltr">
                    </div>
                    <div class="col-6">
                        <label for="modal_course_hours" class="form-label required">الساعات المعتمدة</label>
                        <input type="number" id="modal_course_hours" class="form-control" required min="1" max="12" value="3">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="modal_course_type" class="form-label required">نوع المادة</label>
                        <select id="modal_course_type" class="form-control" required>
                            <option value="متطلب تخصص إجباري">متطلب تخصص إجباري</option>
                            <option value="متطلب كلية إجباري">متطلب كلية إجباري</option>
                            <option value="متطلب جامعة إجباري">متطلب جامعة إجباري</option>
                            <option value="متطلب اختياري">متطلب اختياري</option>
                            <option value="مشروع تخرج / تدريب">مشروع تخرج / تدريب</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="modal_course_semester_select" class="form-label required">الفصل الدراسي</label>
                        <select id="modal_course_semester_select" class="form-control" required onchange="document.getElementById('modal_course_semester').value = this.value;">
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="modal_course_desc" class="form-label">وصف مختصر أو متطلب سابق</label>
                    <input type="text" id="modal_course_desc" class="form-control" placeholder="مثال: المتطلب السابق: CS100 (برمجة 1)">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('courseModal')">إلغاء</button>
                <button type="submit" class="btn btn-navy btn-sm" id="btnSaveCourse">حفظ المادة</button>
            </div>
        </form>
    </div>
</div>

<!-- 7. نافذة نشر إعلان أو تعميم -->
<div class="modal-overlay" id="announcementModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-teal-subtle text-teal">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 8a3 3 0 00-3-3M21 8a6 6 0 00-6-6M3 11a1 1 0 011-1h2.586l4.707-4.707A1 1 0 0113 6v12a1 1 0 01-1.707.707L6.586 14H4a1 1 0 01-1-1v-2z"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">نشر إعلان أو تعميم جامعي جديد</h3>
                    <p class="modal-subtitle">إشعار الطلبة والزوار بمواعيد التنسيق والفعاليات</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('announcementModal')">&times;</button>
        </div>

        <form id="announcementForm" action="{{ route('university.announcements.store') }}" method="POST" onsubmit="submitAnnouncementForm(event)">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="ann_title" class="form-label required">عنوان الإعلان</label>
                    <input type="text" id="ann_title" name="title" class="form-control" required placeholder="مثال: فتح باب التنسيق والقبول للعام الجامعي 2026/2027">
                </div>

                <div class="form-group mb-3">
                    <label for="ann_category" class="form-label required">تصنيف الإعلان</label>
                    <select id="ann_category" name="category" class="form-control" required>
                        <option value="قبول وتنسيق">قبول وتنسيق</option>
                        <option value="مفاضلة واختبارات">مفاضلة واختبارات</option>
                        <option value="تعميم أكاديمي">تعميم أكاديمي</option>
                        <option value="إعلان عام">إعلان عام</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="ann_content" class="form-label required">نص ومحتوى الإعلان</label>
                    <textarea id="ann_content" name="content" rows="4" class="form-control" required placeholder="اكتب تفاصيل الإعلان ومواعيد التسجيل والشروط..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('announcementModal')">إلغاء</button>
                <button type="submit" class="btn btn-teal btn-sm" id="btnSaveAnnouncement">نشر الإعلان الآن</button>
            </div>
        </form>
    </div>
</div>

<!-- 8. نافذة إضافة وسيلة دفع -->
<div class="modal-overlay" id="paymentMethodModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-container modal-md">
        <div class="modal-header">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon bg-purple-subtle text-purple">
                    <svg class="ico" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <div>
                    <h3 class="modal-title">إضافة وسيلة أو حساب دفع</h3>
                    <p class="modal-subtitle">إتاحة خيار دفع جديد للطلبة لسداد رسوم التنسيق</p>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('paymentMethodModal')">&times;</button>
        </div>

        <form id="paymentMethodForm" action="{{ route('university.payments.store') }}" method="POST" onsubmit="submitPaymentMethodForm(event)">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="pm_method_name" class="form-label required">اسم البنك / الجهة</label>
                    <input type="text" id="pm_method_name" name="method_name" class="form-control" required placeholder="مثال: بنك الكريمي، ون كاش، محفظة جايبي، بنك اليمن والكويت">
                </div>

                <div class="form-group mb-3">
                    <label for="pm_method_type" class="form-label required">نوع وسيلة الدفع</label>
                    <select id="pm_method_type" name="method_type" class="form-control" required>
                        <option value="بنك">حساب بنكي</option>
                        <option value="محفظة إلكترونية">محفظة إلكترونية</option>
                        <option value="حوالة صرافة">حوالة صرافة</option>
                        <option value="سداد نقدي">سداد نقدي في شؤون الطلاب</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="pm_account_number" class="form-label required">رقم الحساب أو المحفظة</label>
                    <input type="text" id="pm_account_number" name="account_number" class="form-control" required placeholder="مثال: 12345678 أو رقم الهاتف للمحفظة" dir="ltr">
                </div>

                <div class="form-group mb-3">
                    <label for="pm_account_name" class="form-label">اسم صاحب الحساب المعتمد</label>
                    <input type="text" id="pm_account_name" name="account_name" class="form-control" placeholder="مثال: حساب جامعة الملكة أروى الرسمي">
                </div>

                <div class="form-group mb-3">
                    <label for="pm_instructions" class="form-label">تعليمات وإرشادات السداد للطالب</label>
                    <input type="text" id="pm_instructions" name="instructions" class="form-control" placeholder="مثال: يرجى كتابة اسم الطالب ورقم الاستمارة في ملاحظة الحوالة">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('paymentMethodModal')">إلغاء</button>
                <button type="submit" class="btn btn-navy btn-sm" id="btnSavePaymentMethod">حفظ وسيلة الدفع</button>
            </div>
        </form>
    </div>
</div>

<!-- 11. نافذة منبثقة: تأمين وتغيير بيانات الدخول الأولية لحساب الجامعة -->
<div class="modal-overlay" id="firstLoginCredentialsModal" role="dialog" aria-modal="true" 
     @if(Auth::check() && Auth::user()->must_change_password) style="display: flex; z-index: 99999;" data-mandatory="true" @else style="display: none;" @endif>
    <div class="modal-container modal-md" style="max-width: 490px; width: 94%; border: 1px solid #e4b75b; border-radius: 14px; box-shadow: 0 20px 40px rgba(10,27,42,0.35); overflow: hidden;">
        <div class="modal-header" style="background: linear-gradient(135deg, #102a43, #163d5a); color: #fff; padding: 18px 22px; border-bottom: 2px solid #e4b75b; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: rgba(228, 183, 91, 0.2); color: #e4b75b; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3 class="modal-title" style="color: #fff; font-size: 16px; font-weight: 800; margin: 0;">تأمين بيانات الدخول للحساب</h3>
                    <p class="modal-subtitle" style="color: #cbd5e1; font-size: 11.5px; margin: 2px 0 0;">خطوة أمان مطلوبة لتحديث بيانات الحساب المؤقتة</p>
                </div>
            </div>
            @if(Auth::check() && !Auth::user()->must_change_password)
                <button type="button" class="btn-close-modal" style="color: #cbd5e1; background: none; border: none; font-size: 22px; cursor: pointer;" onclick="closeModal('firstLoginCredentialsModal')">&times;</button>
            @endif
        </div>

        <form id="firstLoginCredentialsForm" action="{{ route('university.first_login.save') }}" method="POST" onsubmit="handleFirstLoginSubmit(event)">
            @csrf
            <div class="modal-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                
                {{-- تنبيه أمني مباشر ومختصر بدون حشو --}}
                <div style="background: #fdf8eb; border: 1px solid #fde68a; border-right: 4px solid #d97706; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 12.5px; color: #92400e; display: flex; align-items: flex-start; gap: 10px; line-height: 1.5;">
                    <i class="fas fa-lock" style="font-size: 15px; margin-top: 2px; color: #d97706; flex-shrink: 0;"></i>
                    <span>حفاظاً على أمان وسرية الحساب، يرجى تعيين اسم مستخدم خاص وتغيير كلمة المرور المؤقتة لمتابعة استخدام المنصة.</span>
                </div>

                {{-- صندوق التنبيهات والأخطاء الديناميكي --}}
                <div id="firstLoginAlertBox" style="display: none; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 12.5px; line-height: 1.5;"></div>

                {{-- 1. اسم المستخدم --}}
                <div class="form-group mb-3">
                    <label for="fl_username" class="form-label" style="font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 5px; display: block;">
                        اسم المستخدم (Username) <span class="text-danger">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"><i class="fas fa-user"></i></span>
                        <input type="text" id="fl_username" name="username" class="form-control" value="{{ Auth::check() ? Auth::user()->username : '' }}" required placeholder="اسم المستخدم" style="padding-right: 36px; font-family: monospace; font-size: 13px;" dir="ltr">
                    </div>
                    <small style="font-size: 11px; color: #64748b; margin-top: 3px; display: block;">يمكنك الإبقاء عليه أو كتابة اسم مستخدم دائم يسهل تذكره.</small>
                </div>

                {{-- 2. كلمة المرور الحالية (المؤقتة) --}}
                <div class="form-group mb-3">
                    <label for="fl_current_password" class="form-label" style="font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 5px; display: block;">
                        كلمة المرور الحالية (المؤقتة) <span class="text-danger">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"><i class="fas fa-key"></i></span>
                        <input type="password" id="fl_current_password" name="current_password" class="form-control" required placeholder="أدخل كلمة المرور الحالية/المؤقتة" style="padding-right: 36px; padding-left: 38px; font-size: 13px;" dir="ltr">
                        <button type="button" onclick="toggleCredentialEye('fl_current_password', this)" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- 3. كلمة المرور الجديدة --}}
                <div class="form-group mb-3">
                    <label for="fl_new_password" class="form-label" style="font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 5px; display: block;">
                        كلمة المرور الجديدة <span class="text-danger">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"><i class="fas fa-lock"></i></span>
                        <input type="password" id="fl_new_password" name="password" class="form-control" required minlength="6" placeholder="اختر كلمة مرور جديدة (6 خانات على الأقل)" style="padding-right: 36px; padding-left: 38px; font-size: 13px;" dir="ltr" oninput="checkModalPwdStrength(this.value)">
                        <button type="button" onclick="toggleCredentialEye('fl_new_password', this)" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- شريط مقياس القوة المصغر -->
                    <div style="height: 4px; background: #e2e8f0; border-radius: 2px; overflow: hidden; margin-top: 6px;">
                        <div id="modalPwdStrengthBar" style="height: 100%; width: 0%; transition: width 0.3s, background-color 0.3s;"></div>
                    </div>
                    <div id="modalPwdStrengthText" style="font-size: 11px; margin-top: 3px; color: #64748b;"></div>
                </div>

                {{-- 4. تأكيد كلمة المرور الجديدة --}}
                <div class="form-group mb-3">
                    <label for="fl_password_confirmation" class="form-label" style="font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 5px; display: block;">
                        تأكيد كلمة المرور الجديدة <span class="text-danger">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;"><i class="fas fa-check-circle"></i></span>
                        <input type="password" id="fl_password_confirmation" name="password_confirmation" class="form-control" required minlength="6" placeholder="أعد إدخال كلمة المرور الجديدة" style="padding-right: 36px; padding-left: 38px; font-size: 13px;" dir="ltr" oninput="checkModalPwdMatch()">
                        <button type="button" onclick="toggleCredentialEye('fl_password_confirmation', this)" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="modalPwdMatchText" style="font-size: 11px; margin-top: 3px;"></div>
                </div>

                {{-- 5. خيار اختياري غير إجباري للانتقال إلى بيانات الجامعة --}}
                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px 14px; margin-top: 14px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; user-select: none;">
                        <input type="checkbox" name="redirect_to_profile" value="1" id="redirectToProfileCheck" style="accent-color: #d97706; width: 17px; height: 17px; cursor: pointer;">
                        <span style="font-size: 12.5px; font-weight: 700; color: #0f172a;">الانتقال مباشرة لمراجعة وتحديث بيانات ملف الجامعة بعد الحفظ</span>
                    </label>
                    <p style="font-size: 11px; color: #64748b; margin: 4px 25px 0 0; line-height: 1.4;">(اختياري) يمكنك تخطي ذلك والدخول فوراً للرئيسية، كما يمكنك مراجعة وتعديل بيانات الجامعة والكليات في أي وقت لاحقاً من القائمة الجانبية.</p>
                </div>

            </div>

            <div class="modal-footer" style="padding: 14px 22px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button type="submit" id="btnSubmitFirstLogin" class="btn" style="background: linear-gradient(135deg, #102a43, #163d5a); color: #fff; border: 1px solid #e4b75b; font-weight: 700; border-radius: 8px; padding: 9px 26px; display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; box-shadow: 0 4px 12px rgba(16,42,67,0.15); cursor: pointer;">
                    <i class="fas fa-lock"></i>
                    <span>حفظ وتأمين الحساب</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal استعراض التوجيهات وتصحيح البيانات الرقابية لمدير النظام -->
<div class="modal-overlay" id="moderationCorrectionModal" role="dialog" aria-modal="true" style="display:none; z-index:9999;">
    <div class="modal-container modal-lg" style="max-width: 680px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header" style="border-bottom: 1px solid #fee2e2; background: #fffdfd; padding: 16px 22px;">
            <div class="d-flex align-items-center gap-2">
                <div class="modal-header-icon" style="background: #fef2f2; color: #dc2626; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-file-shield"></i>
                </div>
                <div>
                    <h3 class="modal-title" style="font-size: 16.5px; font-weight: 800; color: #991b1b; margin: 0;">استعراض التوجيهات وتصحيح البيانات</h3>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('moderationCorrectionModal')">&times;</button>
        </div>

        <form id="moderationCorrectionForm" onsubmit="submitModerationCorrectionAjax(event)" enctype="multipart/form-data" style="display:flex; flex-direction:column; overflow:hidden; flex:1;">
            @csrf
            <div class="modal-body" style="overflow-y: auto; padding: 20px 24px; flex:1;">
                
                {{-- 1. اختيار العنصر المحجوب إن وجد أكثر من عنصر --}}
                <div class="form-group mb-3">
                    <label class="form-label fw-bold" style="font-size: 13px; color: #1e293b;">العنصر المحجوب والمطلوب تصحيحه:</label>
                    <select id="modCorrectionItemId" class="form-select" onchange="onModerationItemChange(this)" required style="font-size: 13.5px; border-color: #cbd5e1;">
                        @if(isset($activeModerations) && $activeModerations->isNotEmpty())
                            @foreach($activeModerations as $m)
                                <option value="{{ $m->moderation_id }}"
                                        data-type="{{ $m->item_type }}"
                                        data-item-id="{{ $m->item_id }}"
                                        data-title="{{ $m->item_title }}"
                                        data-reason="{{ $m->violation_reason }}"
                                        data-guidance="{{ $m->admin_guidance ?? 'يرجى مراجعة وتعديل بيانات هذا العنصر ليتطابق مع المعايير واللوائح الأكاديمية الرسمية المعتمدة بالمنصة.' }}"
                                        data-date="{{ $m->suspended_at ? $m->suspended_at->format('Y-m-d h:i A') : '' }}"
                                        data-status="{{ $m->status }}">
                                    [{{ $m->item_title }}] — ({{ $m->item_type }})
                                </option>
                            @endforeach
                        @else
                            <option value="">لا توجد عناصر محجوبة حالياً</option>
                        @endif
                    </select>
                </div>

                {{-- 2. بطاقة توجيهات الإدارة وسبب الحجب وتفاصيله --}}
                <div id="modDirectivesCard" style="background: #ffffff; border: 1.5px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(220,38,38,0.04);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <span id="modBadgeTitle" class="badge" style="background:#fee2e2; color:#dc2626; font-size:12px; font-weight:700; padding:4px 10px; border-radius:20px;">
                            <i class="fa-solid fa-ban me-1"></i> محجوب رقابياً
                        </span>
                        <span id="modDateText" style="font-size: 11.5px; color: #64748b;">
                            <i class="fa-regular fa-clock me-1"></i> --
                        </span>
                    </div>

                    {{-- سبب الحجب --}}
                    <div style="background: #fff5f5; border-right: 4px solid #dc2626; border-radius: 6px; padding: 10px 14px; margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: 800; color: #991b1b; margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>سبب الإيقاف والحجب الرقابي:</span>
                        </div>
                        <div id="modViolationReasonText" style="font-size: 13px; color: #7f1d1d; line-height: 1.55;">
                            --
                        </div>
                    </div>

                    {{-- توجيهات الإدارة والاشتراطات --}}
                    <div style="background: #f0f7ff; border-right: 4px solid #0284c7; border-radius: 6px; padding: 10px 14px;">
                        <div style="font-size: 12px; font-weight: 800; color: #0369a1; margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-compass"></i>
                            <span>توجيهات الإدارة والاشتراطات المطلوبة لإعادة الاعتماد:</span>
                        </div>
                        <div id="modAdminGuidanceText" style="font-size: 13px; color: #0c4a6e; line-height: 1.55;">
                            --
                        </div>
                    </div>
                </div>

                {{-- 3. مساحة التعديل التفاعلي المباشر بحسب نوع العنصر المحجوب --}}
                <div style="border-top: 1.5px dashed #cbd5e1; padding-top: 18px; margin-bottom: 18px;">
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; gap: 7px;">
                        <i class="fa-solid fa-pen-to-square" style="color: #0b3d6b;"></i>
                        <span>تعديل وتصحيح العنصر مباشرة من هنا:</span>
                    </div>

                    {{-- أ. محرر الشعار --}}
                    <div id="editorType_logo" class="mod-type-editor" style="display:none;">
                        <div style="display:flex; align-items:center; gap:16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px;">
                            <div style="width:70px; height:70px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0;">
                                <img id="modCurrentLogoImg" src="{{ $university->logo_url ?? asset('logo.png') }}" alt="الشعار الحالي" style="max-width:100%; max-height:100%; object-fit:contain;" />
                            </div>
                            <div style="flex:1;">
                                <label class="form-label fw-bold mb-1" style="font-size:12.5px;">رفع صورة الشعار البديلة (PNG, JPG, WEBP, SVG حتى 5MB):</label>
                                <input type="file" name="new_logo" id="modNewLogoInput" class="form-control form-control-sm" accept="image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewModUploadedImg(this, 'modCurrentLogoImg')">
                                <div class="form-text" style="font-size:11.5px; color:#64748b;">سيتم حفظ الشعار وتعيينه فوراً ورفعه للإدارة لفك الحجب.</div>
                            </div>
                        </div>
                    </div>

                    {{-- ب. محرر صورة الحرم الجامعي --}}
                    <div id="editorType_campus_image" class="mod-type-editor" style="display:none;">
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px;">
                            <div style="width:100%; height:110px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-bottom:10px;">
                                <img id="modCurrentCampusImg" src="{{ $university->campus_image_url ?? asset('images/campus-default.svg') }}" alt="صورة الحرم" style="width:100%; height:100%; object-fit:cover;" />
                            </div>
                            <label class="form-label fw-bold mb-1" style="font-size:12.5px;">رفع صورة حرم جامعي بديلة وعالية الجودة:</label>
                            <input type="file" name="new_campus_image" id="modNewCampusInput" class="form-control form-control-sm" accept="image/png,image/jpeg,image/webp" onchange="previewModUploadedImg(this, 'modCurrentCampusImg')">
                        </div>
                    </div>

                    {{-- ج. محرر النبذة والوصف التعريفي --}}
                    <div id="editorType_description" class="mod-type-editor" style="display:none;">
                        <label class="form-label fw-bold mb-1" style="font-size:12.5px;">تعديل الوصف والنبذة التعريفية للجامعة:</label>
                        <textarea name="new_description" id="modNewDescInput" rows="4" class="form-control" style="font-size:13.5px; line-height:1.6;" placeholder="اكتب النبذة المصححة وفق المعايير المطلوبة...">{{ $university->description ?? '' }}</textarea>
                    </div>

                    {{-- د. محرر بيانات التواصل --}}
                    <div id="editorType_contact_info" class="mod-type-editor" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">رقم الهاتف / الاتصال:</label>
                                <input type="text" name="new_phone" value="{{ $university->phone ?? '' }}" class="form-control form-control-sm" placeholder="مثال: 01234567">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">البريد الإلكتروني الرسمي:</label>
                                <input type="email" name="new_email" value="{{ $university->email ?? '' }}" class="form-control form-control-sm" placeholder="info@univ.edu.ye">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">الموقع الإلكتروني:</label>
                                <input type="url" name="new_website" value="{{ $university->website ?? '' }}" class="form-control form-control-sm" placeholder="https://univ.edu.ye">
                            </div>
                        </div>
                    </div>

                    {{-- هـ. محرر بيانات الكلية --}}
                    <div id="editorType_college" class="mod-type-editor" style="display:none;">
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:12.5px;">اسم الكلية الأكاديمية المصحح:</label>
                            <input type="text" name="new_college_name" id="modNewCollegeName" class="form-control form-control-sm" placeholder="اسم الكلية المعتمد...">
                        </div>
                        <div>
                            <label class="form-label fw-bold mb-1" style="font-size:12.5px;">وصف الكلية (اختياري):</label>
                            <textarea name="new_college_desc" id="modNewCollegeDesc" rows="2" class="form-control form-control-sm" placeholder="نبذة عن الكلية..."></textarea>
                        </div>
                    </div>

                    {{-- و. محرر بيانات التخصص --}}
                    <div id="editorType_specialization" class="mod-type-editor" style="display:none;">
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">اسم التخصص الأكاديمي:</label>
                                <input type="text" name="new_spec_name" id="modNewSpecName" class="form-control form-control-sm" placeholder="اسم التخصص...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">نسبة الثانوية الأدنى (%):</label>
                                <input type="number" step="0.01" min="50" max="100" name="new_min_percentage" id="modNewMinPercentage" class="form-control form-control-sm" placeholder="مثال: 75.00">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">الرسوم السنوية (ر.ي):</label>
                                <input type="number" step="1" name="new_annual_fees" id="modNewAnnualFees" class="form-control form-control-sm" placeholder="مثال: 450000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold mb-1" style="font-size:12px;">سنوات الدراسة:</label>
                                <input type="number" min="1" max="7" name="new_study_years" id="modNewStudyYears" class="form-control form-control-sm" placeholder="مثال: 4">
                            </div>
                        </div>
                    </div>

                    {{-- ز. محرر الإعلان --}}
                    <div id="editorType_announcement" class="mod-type-editor" style="display:none;">
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1" style="font-size:12.5px;">عنوان الإعلان:</label>
                            <input type="text" name="new_ann_title" id="modNewAnnTitle" class="form-control form-control-sm" placeholder="عنوان الإعلان المصحح...">
                        </div>
                        <div>
                            <label class="form-label fw-bold mb-1" style="font-size:12.5px;">محتوى الإعلان:</label>
                            <textarea name="new_ann_content" id="modNewAnnContent" rows="3" class="form-control form-control-sm" placeholder="نص ومحتوى الإعلان..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- 4. إيضاحات إضافية اختيارية لمدير النظام --}}
                <div class="form-group mb-1">
                    <label class="form-label fw-bold mb-1" style="font-size: 12.5px; color:#334155;">ملاحظات وإيضاحات إضافية لمدير النظام (اختياري):</label>
                    <textarea id="modCorrectionNotes" name="correction_notes" rows="2" class="form-control" style="font-size:13px;" placeholder="اكتب أي ملاحظة أو تعقيب تود إرفاقه مع التعديلات المرفوعة أعلاه..."></textarea>
                </div>

            </div>

            <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 14px 24px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-secondary-light btn-sm" onclick="closeModal('moderationCorrectionModal')">إلغاء</button>
                <button type="submit" id="btnSubmitModCorrection" class="btn btn-primary btn-sm px-4" style="background:#0b3d6b; border-color:#0b3d6b; font-weight:700;">
                    <i class="fa-solid fa-paper-plane me-1"></i> إرسال التعديلات لمدير النظام وفك الحجب
                </button>
            </div>
        </form>
    </div>
</div>
