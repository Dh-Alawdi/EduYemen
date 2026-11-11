<footer class="dashboard-footer">
    <div class="footer-inner">
        <div class="footer-right">
            <span>&copy; {{ date('Y') }} <strong>EduYemen</strong> — المنظومة الجامعية الموحدة للجمهورية اليمنية. جميع الحقوق محفوظة.</span>
        </div>
        <div class="footer-left">
            <span class="system-status-indicator">
                <span class="status-pulse-dot"></span>
                <span>النظام يعمل بكفاءة 100%</span>
            </span>
            <span class="footer-divider">|</span>
            <a href="{{ route('universities.show', $university->university_id) }}" class="footer-link">
                معاينة صفحة الجامعة للطلبة
            </a>
        </div>
    </div>
</footer>
