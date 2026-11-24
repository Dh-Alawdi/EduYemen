@extends('layouts.app')

@section('title', 'منصة EduYemen - البوابة الموحدة للجامعات اليمنية')

@section('styles')
<style>

    /* ====== HERO + AD SPLIT (تنسيقات القسم الرئيسي والإعلانات) ====== */
    .hero-section {
        background: linear-gradient(150deg, #062238 0%, #0b3d6b 55%, #1565a8 100%);
        position: relative;
        overflow: hidden;
        padding: 28px 0 80px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .hero-split-layout {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        position: relative;
        z-index: 2;
    }

    .hero-info-col {
        flex: 0 0 55%;
        max-width: 55%;
        text-align: right;
    }

    .hero-ad-col {
        flex: 0 0 42%;
        max-width: 42%;
    }

    .hero-badge {
        background: rgba(197,155,39,0.2);
        border: 1px solid rgba(197,155,39,0.5);
        color: #f0c842;
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 1.2rem;
    }

    .hero-title {
        font-size: clamp(1.6rem, 3.2vw, 2.4rem);
        font-weight: 800;
        color: white;
        line-height: 1.35;
        margin-bottom: 1rem;
    }

    .hero-title span {
        color: #f0c842;
    }

    .hero-desc {
        color: rgba(255,255,255,0.78);
        font-size: 1rem;
        margin: 0 0 1.8rem;
        line-height: 1.8;
    }

    .hero-wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
    }

    /* ====== AD BANNER CAROUSEL ====== */
    /* تطبيق الخط الطبيعي لجميع عناصر ونصوص بطاقة الإعلان في الرئيسية */
    .hero-ad-col,
    .hero-ad-col *,
    .ad-carousel-card,
    .ad-carousel-card *,
    .ad-headline,
    .ad-body-text,
    .ad-uni-tag,
    .btn-uni-profile {
        font-family: 'Almarai', 'IBM Plex Sans Arabic', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif !important;
    }

    .ad-carousel-card {
        background: rgba(10, 42, 74, 0.88);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 18px;
        border: 1px solid rgba(197,155,39,0.45);
        color: #fff;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 36px rgba(0,0,0,0.32);
        height: 335px;
        min-height: 335px;
        max-height: 335px;
        box-sizing: border-box;
    }

    /* أسهم التنقل الرئيسية على طرفي حاوية الإعلان (< >) */
    .ad-side-arrow {
        position: absolute;
        top: 86px;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(8, 24, 42, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1.5px solid rgba(240, 200, 66, 0.65);
        color: #f0c842;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        z-index: 10;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 14px rgba(0,0,0,0.45);
        padding: 0;
        outline: none;
    }

    .ad-side-arrow:hover {
        background: #f0c842;
        color: #081726;
        border-color: #ffffff;
        transform: translateY(-50%) scale(1.14);
        box-shadow: 0 6px 20px rgba(240, 200, 66, 0.5);
    }

    .ad-side-arrow.ad-side-prev {
        right: 8px;
    }

    .ad-side-arrow.ad-side-next {
        left: 8px;
    }

    .ad-media-col {
        width: 100%;
        height: 172px;
        min-height: 172px;
        max-height: 172px;
        position: relative;
        overflow: hidden;
        background: #061829;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* خلفية ضبابية تعكس ألوان وسائط الإعلان لملء أي فراغات جانبية بدون اقتصاص */
    .ad-media-backdrop {
        position: absolute;
        inset: -15px;
        background-size: cover;
        background-position: center;
        filter: blur(16px) brightness(0.35);
        opacity: 0.82;
        transform: scale(1.12);
        z-index: 1;
        transition: background-image 0.4s ease, opacity 0.4s ease;
        pointer-events: none;
    }

    .ad-media-col img {
        position: relative;
        z-index: 2;
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
        max-height: 100%;
        object-fit: cover !important;
        object-position: center;
        display: block;
        transition: opacity 0.3s ease;
    }

    .ad-media-col video,
    #adMediaVideo {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        object-position: center;
        display: block;
    }

    /* شارة الفيديو الإعلاني في الزاوية العلوية وزر التحكم بالصوت */
    .ad-video-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 5;
        background: rgba(8, 24, 42, 0.88);
        color: #f0c842;
        border: 1px solid rgba(197, 155, 39, 0.55);
        font-size: 11px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        pointer-events: none;
    }

    .ad-sound-toggle-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 5;
        background: rgba(8, 24, 42, 0.85);
        color: #f0c842;
        border: 1px solid rgba(197, 155, 39, 0.5);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.2s ease;
        padding: 0;
    }

    .ad-sound-toggle-btn:hover {
        background: #f0c842;
        color: #081726;
        transform: scale(1.1);
    }

    /* شارة معرض الصور للباقة المتوسطة/المميزة */
    .ad-gallery-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 4;
        background: rgba(10,27,42,0.88);
        color: #f0c842;
        border: 1px solid rgba(197,155,39,0.55);
        font-size: 11px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        pointer-events: none;
    }

    /* أزرار تنقل لمعرض الصور داخل مساحة الصورة تظهر عند التحويم */
    .ad-gallery-hover-nav {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 46px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .ad-media-col:hover .ad-gallery-hover-nav {
        opacity: 1;
    }

    .ad-gal-arrow {
        pointer-events: auto;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(8, 22, 36, 0.78);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ad-gal-arrow:hover {
        background: rgba(240, 200, 66, 0.95);
        color: #081726;
        border-color: #f0c842;
        transform: scale(1.1);
    }

    /* مؤشر نقاط صور المعرض للباقة المتوسطة/المميزة */
    .ad-gallery-dots {
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 5;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        background: rgba(8, 20, 32, 0.75);
        border-radius: 20px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .ad-gallery-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .ad-gallery-dot.active {
        width: 16px;
        border-radius: 4px;
        background: #f0c842;
        box-shadow: 0 0 6px rgba(240, 200, 66, 0.6);
    }

    .ad-video-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 55%);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .ad-play-btn {
        width: 48px;
        height: 48px;
        background: var(--accent-gold);
        color: #101828;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(0,0,0,0.35);
        transition: transform 0.2s, background 0.2s;
    }

    .ad-play-btn:hover {
        transform: scale(1.13);
        background: var(--light-gold);
    }

    .ad-media-tag {
        position: absolute;
        bottom: 8px;
        left: 10px;
        background: rgba(0,0,0,0.72);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .ad-content-info {
        flex: 1;
        padding: 0.55rem 1.15rem 0.55rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        text-align: right;
        min-height: 0;
        overflow: hidden;
        box-sizing: border-box;
    }

    .ad-uni-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(197,155,39,0.18);
        border: 1px solid rgba(197,155,39,0.45);
        color: #f0c842;
        padding: 1px 8px;
        border-radius: 50px;
        font-size: 0.71rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
        width: fit-content;
        height: 20px;
        line-height: 1.3;
    }

    .ad-headline {
        font-size: 0.94rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.3;
        margin-bottom: 0.15rem;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        height: 1.3em;
        min-height: 1.3em;
        max-height: 1.3em;
    }

    .ad-body-text {
        font-size: 0.77rem;
        color: rgba(255,255,255,0.78);
        line-height: 1.4;
        margin-bottom: 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.7em;
        min-height: 2.7em;
        max-height: 2.7em;
    }

    .ad-footer-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap;
        gap: 0.6rem;
        margin-top: auto;
        min-height: 36px;
    }

    /* تكبير وتطوير زر الانتقال إلى الجامعة ليصبح الإجراء الأساسي والأبرز */
    .btn-uni-profile {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 36px;
        padding: 0 16px;
        background: linear-gradient(135deg, #f0c842 0%, #d49f1a 100%);
        color: #081726 !important;
        font-size: 0.86rem;
        font-weight: 800;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: 0 3px 12px rgba(240, 200, 66, 0.32);
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-uni-profile:hover {
        background: linear-gradient(135deg, #fce074 0%, #e5b026 100%);
        color: #05101a !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(240, 200, 66, 0.48);
    }

    .btn-uni-profile i {
        font-size: 0.95rem;
    }

    /* بطاقة شعار المنصة في حالة عدم وجود إعلانات */
    .platform-brand-showcase {
        background: rgba(10, 42, 74, 0.85);
        backdrop-filter: blur(8px);
        border-radius: 18px;
        border: 1px solid rgba(197, 155, 39, 0.4);
        height: 335px;
        min-height: 335px;
        max-height: 335px;
        padding: 1.4rem 1.2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 36px rgba(0,0,0,0.32);
        box-sizing: border-box;
    }

    .platform-brand-glow {
        position: absolute;
        width: 170px;
        height: 170px;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.2) 0%, transparent 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        filter: blur(15px);
    }

    .platform-showcase-logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        filter: drop-shadow(0 4px 14px rgba(0,0,0,0.35));
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 2;
    }

    .platform-showcase-badge {
        display: inline-block;
        background: rgba(197, 155, 39, 0.18);
        border: 1px solid rgba(197, 155, 39, 0.45);
        color: #f0c842;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 50px;
        margin-bottom: 0.5rem;
    }

    .platform-showcase-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.35rem;
    }

    .platform-showcase-title .brand-edu {
        color: #fff;
    }

    .platform-showcase-title .brand-yemen {
        color: #f0c842;
    }

    .platform-showcase-tagline {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.5;
        max-width: 320px;
        margin: 0 auto 0.75rem;
    }

    .platform-showcase-features {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 600;
    }

    .platform-showcase-features .text-gold {
        color: #f0c842;
    }

    .ad-dots {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .ad-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.25s;
    }

    .ad-dot.active {
        width: 22px;
        border-radius: 4px;
        background: var(--accent-gold);
    }

    .ad-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: var(--accent-gold);
        border-radius: 0 2px 2px 0;
        animation: adProgress 7s linear infinite;
        transform-origin: left;
    }

    @keyframes adProgress {
        from { width: 0%; }
        to   { width: 100%; }
    }

    @media (max-width: 991.98px) {
        .hero-split-layout {
            flex-direction: column;
            gap: 2rem;
        }
        .hero-info-col,
        .hero-ad-col {
            flex: 0 0 100%;
            max-width: 100%;
            width: 100%;
        }
        .hero-info-col {
            text-align: center;
        }
    }

    /* ====== STATS BAND (شريط الإحصائيات) ====== */
    .stats-band {
        margin-top: -55px;
        position: relative;
        z-index: 10;
    }

    .stat-card {
        background: white;
        border-radius: 14px;
        padding: clamp(0.75rem, 2vw, 1.4rem) clamp(0.5rem, 2vw, 1.2rem);
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        border-bottom: 3px solid transparent;
        transition: all 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 35px rgba(0,0,0,0.18);
    }

    .stat-card.blue { border-bottom-color: #0b3d6b; }
    .stat-card.green { border-bottom-color: #1a8754; }
    .stat-card.gold { border-bottom-color: #c59b27; }

    .stat-number {
        font-size: clamp(1.4rem, 3.5vw, 2.2rem);
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    /* ====== SECTION & UNIVERSITY CARDS ====== */
    .section-label {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--accent-gold);
        margin-bottom: 0.5rem;
    }

    .section-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--text-dark, #101828);
    }

    .univ-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.25s ease;
        border: 1px solid #E2E8F0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .univ-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        border-color: #CBD5E1;
    }

    .univ-logo-area {
        position: relative;
        background: linear-gradient(135deg, #eef2f8 0%, #dde6f2 100%);
        border-bottom: 1px solid #d9e4f0;
    }

    .univ-logo-img {
        width: 100%;
        height: 130px;
        object-fit: contain;
        padding: 1rem 1.5rem 0.75rem;
        display: block;
    }

    .univ-type-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        font-size: 0.73rem;
        font-weight: 700;
        padding: 0.22rem 0.7rem;
        border-radius: 50px;
    }

    .univ-card-body {
        padding: 1.25rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .univ-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #101828;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .univ-desc {
        font-size: 0.85rem;
        color: #475467;
        line-height: 1.7;
        flex: 1;
        margin-bottom: 1rem;
    }

    .univ-meta {
        font-size: 0.8rem;
        color: #64748B;
    }

    .univ-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 5px;
    }

    .univ-meta .meta-item i {
        width: 16px;
        flex-shrink: 0;
    }

    .univ-card-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #E2E8F0;
        background: #fafbfd;
    }

    /* ====== FEATURES ====== */
    .feature-card {
        background: white;
        border-radius: 14px;
        padding: 2rem 1.5rem;
        border: 1px solid #E2E8F0;
        text-align: center;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1.2rem;
    }

    /* ====== HOW IT WORKS (كيف تستخدم المنصة) ====== */
    .how-it-works-section {
        padding: 2.5rem 0 0.5rem;
        position: relative;
    }

    .steps-wrapper {
        position: relative;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .steps-container-row {
        position: relative;
        margin: 0 !important;
        width: 100% !important;
    }

    /* الخط الرابط الدقيق بين مراكز الخطوات: يبدأ عند 12.5% وينتهي عند 87.5% بعرض 75% دون خروج عن الصفحة */
    .steps-connecting-line {
        position: absolute;
        top: 24px;
        right: 12.5% !important;
        width: 75% !important;
        left: auto !important;
        height: 2.5px;
        background: linear-gradient(90deg, var(--accent-gold, #c59b27) 0%, #1565a8 50%, var(--primary-action, #0b3d6b) 100%);
        z-index: 1;
        border-radius: 2px;
        pointer-events: none;
        box-sizing: border-box;
    }

    .how-step-card {
        text-align: center;
        padding: 0.5rem 0.25rem;
        position: relative;
        z-index: 2;
        text-decoration: none !important;
        display: block;
        color: inherit;
        transition: transform 0.25s ease;
    }

    .how-step-card.clickable-step {
        cursor: pointer;
    }

    .how-step-card.clickable-step:hover {
        transform: translateY(-4px);
    }

    .how-step-card.clickable-step:hover .step-num {
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(197, 155, 39, 0.45);
    }

    .how-step-card.clickable-step:hover .how-step-title {
        color: #1565a8 !important;
    }

    .step-num {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #0b3d6b 0%, #1565a8 100%);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        font-weight: 800;
        margin: 0 auto 0.75rem;
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.28);
        border: 3px solid #f4f6fb;
        transition: all 0.25s ease;
        position: relative;
        z-index: 2;
    }

    .how-step-card.clickable-step .step-num {
        background: linear-gradient(135deg, #c59b27 0%, #ab8219 100%);
        box-shadow: 0 4px 14px rgba(197, 155, 39, 0.35);
    }

    .how-step-icon {
        width: 46px;
        height: 46px;
        background: #eef2f8;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #0b3d6b;
        margin: 0 auto 0.6rem;
        transition: all 0.2s ease;
    }

    .how-step-card.clickable-step:hover .how-step-icon {
        background: rgba(197, 155, 39, 0.15);
        color: #c59b27;
    }

    .how-step-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #101828;
        margin-bottom: 0.35rem;
        line-height: 1.35;
        transition: color 0.2s ease;
    }

    .how-step-desc {
        font-size: 0.78rem;
        color: #64748B;
        line-height: 1.55;
        margin-bottom: 0;
    }

    .step-action-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #c59b27;
        margin-top: 0.35rem;
    }

    /* تجاوب الشاشات الصغيرة والمتوسطة */
    @media (max-width: 768px) {
        .how-it-works-section {
            padding: 2rem 0 1rem;
        }

        .steps-connecting-line {
            top: 18px !important;
            height: 2px !important;
            right: 12.5% !important;
            width: 75% !important;
            left: auto !important;
        }

        .step-num {
            width: 36px;
            height: 36px;
            font-size: 0.88rem;
            border-width: 2px;
            margin-bottom: 0.4rem;
        }

        .how-step-icon {
            width: 34px;
            height: 34px;
            font-size: 1rem;
            border-radius: 8px;
            margin-bottom: 0.35rem;
        }

        .how-step-title {
            font-size: 0.74rem;
            margin-bottom: 0.15rem;
        }

        .how-step-desc {
            font-size: 0.64rem;
            line-height: 1.35;
        }

        .step-action-badge {
            font-size: 0.6rem;
        }
    }

    /* ====== CTA SECTION ====== */
    .cta-section {
        background: linear-gradient(135deg, #0b3d6b 0%, #1565a8 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .cta-section::after {
        content: '\F38E';
        font-family: 'bootstrap-icons';
        position: absolute;
        left: -20px;
        top: -20px;
        font-size: 12rem;
        color: rgba(255,255,255,0.04);
        pointer-events: none;
    }

    /* =========================================================
       تحسينات شاملة متجاوبة مع شاشات الهواتف والأجهزة الذكية
       تمنع التزاحم، تضبط أحجام الخطوط، وتجعل كل عنصر متناسق
    ========================================================= */
    @media (max-width: 767.98px) {
        .hero-section {
            padding: 1.8rem 0 3.2rem !important;
        }
        .hero-split-layout {
            gap: 1.5rem !important;
        }
        .hero-badge {
            font-size: 0.74rem !important;
            padding: 0.35rem 0.85rem !important;
            margin-bottom: 0.85rem !important;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-title {
            font-size: 1.45rem !important;
            line-height: 1.35 !important;
            margin-bottom: 0.75rem !important;
        }
        .hero-desc {
            font-size: 0.86rem !important;
            line-height: 1.65 !important;
            margin-bottom: 1.25rem !important;
        }
        .hero-cta-btns {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .hero-cta-btns .btn {
            flex: 1 1 50% !important;
            min-width: 0 !important;
            white-space: nowrap !important;
            font-size: 0.82rem !important;
            padding: 0.52rem 0.45rem !important;
            border-radius: 9px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            box-sizing: border-box !important;
        }
        .hero-cta-btns .btn i {
            font-size: 0.85rem !important;
            margin: 0 !important;
        }

        /* كرت الإعلان المتحرك */
        .ad-carousel-card {
            border-radius: 14px !important;
            height: 310px !important;
            min-height: 310px !important;
            max-height: 310px !important;
        }
        .ad-media-col {
            height: 140px !important;
            min-height: 140px !important;
            max-height: 140px !important;
        }
        .ad-side-arrow {
            top: 70px !important;
            width: 30px !important;
            height: 30px !important;
            font-size: 13px !important;
        }
        .ad-side-arrow.ad-side-prev {
            right: 4px !important;
        }
        .ad-side-arrow.ad-side-next {
            left: 4px !important;
        }
        .ad-gallery-hover-nav {
            padding: 0 36px !important;
        }
        .ad-content-info {
            padding: 0.6rem 0.85rem 0.5rem !important;
        }
        .ad-headline {
            font-size: 0.9rem !important;
            line-height: 1.35 !important;
            height: 1.35em !important;
            min-height: 1.35em !important;
            max-height: 1.35em !important;
            margin-bottom: 0.2rem !important;
        }
        .ad-body-text {
            font-size: 0.74rem !important;
            line-height: 1.4 !important;
            height: 2.8em !important;
            min-height: 2.8em !important;
            max-height: 2.8em !important;
            margin-bottom: 0.35rem !important;
        }
        .btn-uni-profile {
            height: 33px !important;
            font-size: 0.8rem !important;
            padding: 0 12px !important;
            gap: 5px !important;
        }

        /* شريط الإحصائيات (Stats Band) */
        .stats-band {
            margin-top: -30px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .stat-card {
            padding: 0.55rem 0.35rem !important;
            border-radius: 10px !important;
        }
        .stat-number {
            font-size: 1.35rem !important;
            margin-bottom: 0.2rem !important;
        }
        .stat-card .small {
            font-size: 0.68rem !important;
            line-height: 1.2 !important;
        }
        .stat-card .smaller {
            font-size: 0.58rem !important;
            line-height: 1.15 !important;
        }

        /* قسم كيف تستخدم المنصة */
        .how-it-works-section {
            padding: 2rem 0 1rem !important;
        }
        .section-title {
            font-size: 1.3rem !important;
        }
        .section-label {
            font-size: 0.72rem !important;
        }
        .steps-connecting-line {
            top: 16px !important;
            height: 2px !important;
            right: 12.5% !important;
            width: 75% !important;
            left: auto !important;
        }
        .step-num {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.82rem !important;
            border-width: 2px !important;
            margin-bottom: 0.35rem !important;
        }
        .how-step-icon {
            width: 30px !important;
            height: 30px !important;
            font-size: 0.9rem !important;
            border-radius: 8px !important;
            margin-bottom: 0.3rem !important;
        }
        .how-step-title {
            font-size: 0.68rem !important;
            line-height: 1.25 !important;
            margin-bottom: 0.15rem !important;
            min-height: 2.4em !important;
        }
        .step-action-badge {
            font-size: 0.58rem !important;
            gap: 2px !important;
        }

        /* بطاقات الجامعات في الموبايل */
        .univ-card {
            border-radius: 14px !important;
        }
        .univ-card-header-inner {
            padding: 0.75rem 0.85rem !important;
            gap: 10px !important;
        }
        .univ-card-logo-box {
            width: 52px !important;
            height: 52px !important;
            min-width: 52px !important;
            padding: 3px !important;
            border-radius: 10px !important;
        }
        .univ-card-title-text {
            font-size: 0.94rem !important;
        }
        .univ-card-body {
            padding: 0.85rem !important;
        }

        /* بطاقات الميزات */
        .feature-card {
            padding: 1.1rem 0.75rem !important;
            border-radius: 12px !important;
        }
        .feature-icon {
            width: 44px !important;
            height: 44px !important;
            font-size: 1.3rem !important;
            border-radius: 12px !important;
            margin-bottom: 0.65rem !important;
        }
        .feature-card h6 {
            font-size: 0.88rem !important;
            margin-bottom: 0.25rem !important;
        }
        .feature-card p {
            font-size: 0.74rem !important;
            line-height: 1.45 !important;
        }

        /* قسم الحث على التسجيل (CTA) */
        .cta-section {
            padding: 1.8rem 1.1rem !important;
            border-radius: 14px !important;
        }
        .cta-section h3 {
            font-size: 1.25rem !important;
        }
        .cta-section p {
            font-size: 0.82rem !important;
            margin-bottom: 1.2rem !important;
        }
        .cta-btns {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .cta-btns .btn {
            flex: 1 1 50% !important;
            min-width: 0 !important;
            white-space: nowrap !important;
            font-size: 0.82rem !important;
            padding: 0.52rem 0.45rem !important;
            border-radius: 9px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            box-sizing: border-box !important;
        }
        .cta-btns .btn i {
            font-size: 0.85rem !important;
            margin: 0 !important;
        }
    }

    @media (max-width: 420px) {
        .hero-title {
            font-size: 1.32rem !important;
        }
        .hero-desc {
            font-size: 0.82rem !important;
        }
        .stat-number {
            font-size: 1.2rem !important;
        }
        .stat-card .small {
            font-size: 0.64rem !important;
        }
        .stat-card .smaller {
            display: none !important;
        }
        .how-step-title {
            font-size: 0.64rem !important;
        }
        .hero-cta-btns,
        .cta-btns {
            gap: 5px !important;
        }
        .hero-cta-btns .btn,
        .cta-btns .btn {
            font-size: 0.72rem !important;
            padding: 0.46rem 0.35rem !important;
        }
        .hero-cta-btns .btn i,
        .cta-btns .btn i {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')

{{-- ====== HERO + AD SPLIT (معلومات الموقع والإعلانات) ====== --}}
<section class="hero-section">
    <div class="container position-relative">
        <div class="hero-split-layout">

            {{-- القسم الأول: معلومات الموقع --}}
            <div class="hero-info-col">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i>
                    دليلك الأكاديمي المعتمد — أمانة العاصمة صنعاء {{ date('Y') }}
                </div>
                <h1 class="hero-title">
                    المنصة الموحدة لاختيار<br>
                    <span>الجامعة والتخصص المناسب</span>
                </h1>
                <p class="hero-desc">
                    تعرَّف على الجامعات الحكومية والأهلية، واستكشف كلياتها وتخصصاتها وشروط القبول والرسوم الدراسية والوثائق المطلوبة، ثم قدِّم طلب التحاقك إلكترونياً ومباشرة.
                </p>
                <div class="d-flex justify-content-lg-start justify-content-center gap-3 flex-wrap hero-cta-btns">
                    <a href="{{ route('universities.index') }}" class="btn btn-accent btn-lg px-4 fw-bold">
                        <i class="bi bi-search me-2"></i><span>استكشف الجامعات</span><span class="d-none d-sm-inline">&nbsp;الآن</span>
                    </a>
                    @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 fw-bold">
                        <i class="bi bi-box-arrow-in-left me-2"></i><span>تسجيل الدخول</span>
                    </a>
                    @endguest
                </div>
            </div>

            {{-- القسم الثاني: المساحة الإعلانية بجانب معلومات الموقع أو شعار المنصة --}}
            <div class="hero-ad-col">
                @if(!empty($activeAdsData) && (is_object($activeAdsData) ? $activeAdsData->isNotEmpty() : count($activeAdsData) > 0))
                    {{-- البنر الإعلاني المتحرك (في حالة وجود إعلانات نشطة) --}}
                    <div class="ad-carousel-card" id="adCarouselCard">
                        {{-- أزرار التنقل الرئيسية بين الإعلانات على طرفي الحاوية (< >) --}}
                        <button type="button" class="ad-side-arrow ad-side-prev" id="btnAdPrev" onclick="navigateAd(-1, event)" aria-label="الإعلان السابق" title="الإعلان السابق">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <button type="button" class="ad-side-arrow ad-side-next" id="btnAdNext" onclick="navigateAd(1, event)" aria-label="الإعلان التالي" title="الإعلان التالي">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <div class="ad-media-col">
                            {{-- خلفية ضبابية تعكس ألوان وسائط الإعلان لملء أي أطراف فارغة بجمالية بدون اقتصاص --}}
                            <div class="ad-media-backdrop" id="adMediaBackdrop"></div>

                            <img src="/images/ads/ad-1.jpg"
                                 id="adMediaImg"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'224\'%3E%3Crect width=\'300\' height=\'224\' fill=\'%230a2a4a\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'rgba(255,255,255,0.15)\' font-size=\'60\'%3E🎓%3C/text%3E%3C/svg%3E'"
                                 alt="إعلان الجامعة">

                            {{-- مشغل الفيديو التلقائي للإعلانات بدون الحاجة للضغط --}}
                            <video id="adMediaVideo"
                                   playsinline
                                   muted
                                   autoplay
                                   loop
                                   preload="auto"
                                   style="display:none;width:100%;height:100%;object-fit:cover;position:relative;z-index:2;background:transparent;">
                            </video>

                            {{-- شارة الفيديو الإعلاني وزر كتم/تشغيل الصوت --}}
                            <div class="ad-video-badge" id="adVideoBadge" style="display:none;">
                                <i class="bi bi-play-circle-fill"></i>
                                <span>فيديو إعلاني</span>
                            </div>
                            <button type="button" class="ad-sound-toggle-btn" id="adSoundToggleBtn" onclick="toggleAdSound(event)" style="display:none;" title="كتم / تشغيل الصوت">
                                <i class="bi bi-volume-mute-fill" id="adSoundIcon" style="font-size:14px;"></i>
                            </button>

                            {{-- شارة معرض الصور للباقة المتوسطة/المميزة --}}
                            <div class="ad-gallery-badge" id="adGalleryBadge" style="display:none;">
                                <i class="bi bi-images"></i>
                                <span id="galleryCounter">صورة 1 / 1</span>
                            </div>

                            {{-- أزرار التنقل المصغرة لصور المعرض (تظهر بسلاسة عند التحويم) --}}
                            <div class="ad-gallery-hover-nav" id="adGalleryHoverNav" style="display:none;">
                                <button type="button" class="ad-gal-arrow ad-gal-prev" onclick="navigateGallery(-1, event)" title="الصورة السابقة">
                                    <i class="bi bi-chevron-compact-right"></i>
                                </button>
                                <button type="button" class="ad-gal-arrow ad-gal-next" onclick="navigateGallery(1, event)" title="الصورة التالية">
                                    <i class="bi bi-chevron-compact-left"></i>
                                </button>
                            </div>

                            {{-- مؤشر النقاط التفاعلي لصور المعرض في أسفل الصورة --}}
                            <div class="ad-gallery-dots" id="adGalleryDots" style="display:none;"></div>
                        </div>

                        <div class="ad-content-info" id="adContent">
                            <span class="ad-uni-tag" id="adUniTag">
                                <i class="bi bi-building"></i>
                                <span id="adUniName">جامعة صنعاء</span>
                            </span>
                            <h2 class="ad-headline" id="adTitle">فتح باب القبول والتسجيل للعام الجامعي 2026/2027</h2>
                            <p class="ad-body-text" id="adDesc">
                                يسر جامعة صنعاء الإعلان عن فتح باب القبول في مختلف كلياتها الأكاديمية الحكومية، مع توفر مقاعد محدودة في الكليات العلمية والأدبية.
                            </p>
                            <div class="ad-footer-actions">
                                <a href="#" class="btn-uni-profile" id="adUniLink" title="الانتقال إلى صفحة الجامعة التابع لها الإعلان">
                                    <i class="bi bi-building-check"></i>
                                    <span>الانتقال إلى الجامعة</span>
                                    <i class="bi bi-arrow-left"></i>
                                </a>

                                <div class="ad-dots" id="adDots"></div>
                            </div>
                        </div>

                        <div class="ad-progress-bar" id="adProgressBar"></div>
                    </div>
                @else
                    {{-- بطاقة شعار المنصة في حالة عدم وجود إعلانات نشطة --}}
                    <div class="platform-brand-showcase">
                        <div class="platform-brand-glow"></div>
                        <img src="/images/logo.png"
                             onerror="this.onerror=null;this.src='/images/eduyemen-logo.svg';"
                             class="platform-showcase-logo"
                             alt="شعار EduYemen">
                        <span class="platform-showcase-badge">
                            <i class="bi bi-stars me-1"></i>البوابة الموحدة للجامعات اليمنية
                        </span>
                        <h3 class="platform-showcase-title">
                            <span class="brand-edu">Edu</span><span class="brand-yemen">Yemen</span>
                        </h3>
                        <p class="platform-showcase-tagline">
                            دليلك المعتمد والموثوق لاختيار الجامعة والكلية والتخصص الأكاديمي المناسب والتقديم الإلكتروني المباشر
                        </p>
                        <div class="platform-showcase-features">
                            <span><i class="bi bi-check2-circle text-gold me-1"></i>دليل رسمي</span>
                            <span><i class="bi bi-check2-circle text-gold me-1"></i>تنسيق إلكتروني</span>
                            <span><i class="bi bi-check2-circle text-gold me-1"></i>معتمد</span>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <div class="hero-wave">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 30C360 60 1080 0 1440 30V60H0V30Z" fill="#f4f6fb"/>
        </svg>
    </div>
</section>

{{-- ====== STATS BAND ====== --}}
<section class="container stats-band">
    <div class="row g-2 g-md-3 justify-content-center">
        <div class="col-4 col-md-4 col-lg-3">
            <a href="{{ route('universities.index') }}" class="text-decoration-none d-block" title="استعراض دليل الجامعات المعتمدة">
                <div class="stat-card blue">
                    <div class="stat-number text-primary">{{ $stats['universities_count'] }}</div>
                    <div class="small fw-bold text-muted">جامعة معتمدة</div>
                    <div class="smaller text-muted" style="font-size:0.72rem">حكومية وأهلية</div>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-4 col-lg-3">
            <a href="{{ route('universities.index') }}" class="text-decoration-none d-block" title="استعراض الكليات الأكاديمية المتخصصة">
                <div class="stat-card green">
                    <div class="stat-number text-success">{{ $stats['colleges_count'] }}</div>
                    <div class="small fw-bold text-muted">كلية أكاديمية</div>
                    <div class="smaller text-muted" style="font-size:0.72rem">متخصصة ومعتمدة</div>
                </div>
            </a>
        </div>
        <div class="col-4 col-md-4 col-lg-3">
            <a href="{{ route('universities.index') }}" class="text-decoration-none d-block" title="استعراض التخصصات الدراسية المتاحة">
                <div class="stat-card gold">
                    <div class="stat-number text-accent">{{ $stats['specializations_count'] }}</div>
                    <div class="small fw-bold text-muted">تخصص دراسي</div>
                    <div class="smaller text-muted" style="font-size:0.72rem">في مختلف المجالات</div>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ====== HOW IT WORKS (كيف تستخدم منصة EduYemen؟) ====== --}}
<section class="container how-it-works-section">
    <div class="text-center mb-4">
        <div class="section-label">خطوات بسيطة</div>
        <h2 class="section-title">كيف تستخدم منصة EduYemen؟</h2>
    </div>

    <div class="steps-wrapper position-relative w-100">
        <div class="steps-connecting-line"></div>
        <div class="steps-container-row row g-1 g-md-3">

        {{-- الخطوة 1: استعرض الجامعات (رابط) --}}
        <div class="col-3">
            <a href="{{ route('universities.index') }}" class="how-step-card clickable-step" title="انقر لاستعراض الجامعات">
                <div class="step-num">1</div>
                <div class="how-step-icon">
                    <i class="bi bi-buildings"></i>
                </div>
                <h6 class="how-step-title">استعرض الجامعات</h6>
                <p class="how-step-desc d-none d-sm-block">تصفح قائمة الجامعات المتاحة وراجع تفاصيل كلياتها ومرافقها</p>
                <span class="step-action-badge"><i class="bi bi-arrow-left"></i> تصفح الآن</span>
            </a>
        </div>

        {{-- الخطوة 2: اختر تخصصك --}}
        <div class="col-3">
            <div class="how-step-card">
                <div class="step-num">2</div>
                <div class="how-step-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h6 class="how-step-title">اختر تخصصك</h6>
                <p class="how-step-desc d-none d-sm-block">اطلع على التخصصات الأكاديمية وشروط القبول ومعدلات التنسيق والرسوم</p>
            </div>
        </div>

        {{-- الخطوة 3: قدّم طلبك للتسجيل والالتحاق (رابط) --}}
        <div class="col-3">
            <a href="{{ route('student.apply') }}" class="how-step-card clickable-step" title="انقر لتقديم طلب التسجيل والالتحاق">
                <div class="step-num">3</div>
                <div class="how-step-icon">
                    <i class="bi bi-send-check"></i>
                </div>
                <h6 class="how-step-title">قدّم طلبك للتسجيل والالتحاق</h6>
                <p class="how-step-desc d-none d-sm-block">املأ استمارة التقديم وأرفق وثائقك ومستنداتك المطلوبة بسهولة</p>
                <span class="step-action-badge"><i class="bi bi-arrow-left"></i> قدّم الآن</span>
            </a>
        </div>

        {{-- الخطوة 4: تابع حالة طلبك --}}
        <div class="col-3">
            <div class="how-step-card">
                <div class="step-num">4</div>
                <div class="how-step-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h6 class="how-step-title">تابع حالة طلبك</h6>
                <p class="how-step-desc d-none d-sm-block">تابع مراحل مراجعة طلبك وقرار القبول من الجامعة مباشرة لحظة بلحظة</p>
            </div>
        </div>
    </div>
    </div>
</section>

{{-- ====== UNIVERSITIES LIST ====== --}}
<section class="pt-2 pb-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4">
            <div>
                <div class="section-label">الجامعات المتاحة</div>
                <h2 class="section-title section-header mb-0">استكشف كآفة الجامعات في العاصمة صنعاء</h2>
            </div>
            <a href="{{ route('universities.index') }}" class="btn btn-outline-primary mt-3 mt-md-0">
                عرض جميع الجامعات <i class="bi bi-arrow-left ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($universities->take(6) as $univ)
            <div class="col-md-6 col-lg-4">
                <div class="univ-card card-hover" style="border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 4px 16px rgba(11, 61, 107, 0.05); height: 100%; display: flex; flex-direction: column;">
                    
                    {{-- رأس البطاقة: الشعار في اليمين واسم الجامعة ونوعها إلى جانبه --}}
                    <div class="p-3 border-bottom d-flex align-items-center gap-3 univ-card-header-inner" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);">
                        {{-- إطار شعار الجامعة في جهة اليمين --}}
                        <div class="univ-card-logo-box" style="width: 65px; height: 65px; min-width: 65px; background: #ffffff; border-radius: 12px; padding: 4px; box-shadow: 0 3px 10px rgba(11, 61, 107, 0.08); display: flex; align-items: center; justify-content: center; border: 1.5px solid rgba(197, 155, 39, 0.25);">
                            @if($univ->isItemQuarantined('logo'))
                                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; height:100%; background:#fef2f2; border-radius:8px; color:#dc2626;" title="شعار الجامعة محجوب رقابياً بقرار إشرافي">
                                    <i class="fa-solid fa-shield-virus" style="font-size:20px;"></i>
                                    <span style="font-size:8px; font-weight:800; margin-top:2px;">محجوب</span>
                                </div>
                            @else
                                <img src="{{ $univ->logo_url }}"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'65\' height=\'65\'%3E%3Crect width=\'65\' height=\'65\' fill=\'%23ffffff\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%230b3d6b\' font-size=\'26\'%3E🏛%3C/text%3E%3C/svg%3E'"
                                     style="width: 100%; height: 100%; object-fit: contain;"
                                     alt="شعار {{ $univ->university_name }}">
                            @endif
                        </div>

                        {{-- اسم الجامعة ونوعها إلى جانبه --}}
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="mb-1 d-flex align-items-center gap-1 flex-wrap">
                                @if($univ->status === 'نشطة')
                                <span class="badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:0.68rem; font-weight:700; padding:2px 7px; border-radius:50px;">القبول مفتوح</span>
                                @else
                                <span class="badge" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-size:0.68rem; font-weight:700; padding:2px 7px; border-radius:50px;">القبول مغلق</span>
                                @endif
                                <span class="badge {{ $univ->university_type == 'حكومية' ? 'badge-gov' : 'badge-private' }}" style="font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 50px;">
                                    {{ $univ->university_type }}
                                </span>
                            </div>
                            <h3 class="univ-name univ-card-title-text mb-0 text-truncate" style="font-size: 1.02rem; font-weight: 800; color: #0b3d6b;" title="{{ $univ->university_name }}">
                                {{ $univ->university_name }}
                            </h3>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="univ-card-body p-3 d-flex flex-column flex-grow-1">
                        <p class="univ-desc mb-2" style="font-size: 0.81rem; color: #64748b; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            @if($univ->isItemQuarantined('description'))
                                <span style="color:#dc2626; font-weight:700;"><i class="fa-solid fa-shield-halved me-1"></i> [النبذة التعريفية محجوبة رقابياً للمراجعة]</span>
                            @else
                                {{ Str::limit($univ->description, 110) }}
                            @endif
                        </p>

                        {{-- إحصائيات الكليات والتخصصات --}}
                        <div class="d-flex gap-2 mb-2">
                            <div class="flex-fill text-center p-2 rounded-3" style="background: rgba(11, 61, 107, 0.06); border: 1px solid rgba(11, 61, 107, 0.1);">
                                <div style="font-size: 0.95rem; font-weight: 800; color: #0b3d6b;">{{ $univ->colleges_count }}</div>
                                <div style="font-size: 0.68rem; font-weight: 700; color: #475569;">كليات أكاديمية</div>
                            </div>
                            <div class="flex-fill text-center p-2 rounded-3" style="background: rgba(197, 155, 39, 0.08); border: 1px solid rgba(197, 155, 39, 0.2);">
                                <div style="font-size: 0.95rem; font-weight: 800; color: #ab8219;">{{ $univ->specializations_count }}</div>
                                <div style="font-size: 0.68rem; font-weight: 700; color: #475569;">تخصصاً دراسياً</div>
                            </div>
                        </div>

                        {{-- تفاصيل الموقع والتأسيس --}}
                        <div class="univ-meta mt-auto mb-1" style="font-size: 0.76rem;">
                            <div class="d-flex align-items-center gap-2 mb-1 text-muted">
                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                <span class="text-truncate">{{ $univ->district }} — {{ Str::limit($univ->address, 35) }}</span>
                            </div>
                            @if($univ->year_establ)
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="bi bi-calendar-check text-primary"></i>
                                <span>تأسست عام {{ $univ->year_establ }}</span>
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

        @if($universities->count() > 6)
        <div class="text-center mt-4">
            <a href="{{ route('universities.index') }}" class="btn btn-outline-primary px-5">
                <i class="bi bi-grid me-2"></i>عرض كل {{ $universities->count() }} جامعة
            </a>
        </div>
        @endif
    </div>
</section>

{{-- ====== FEATURES ====== --}}
<section class="py-5" style="background: white; border-top: 1px solid #E2E8F0;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">لماذا EduYemen؟</div>
            <h2 class="section-title">منصة شاملة وموثوقة للطلاب</h2>
        </div>
        <div class="row g-2 g-md-4">
            <div class="col-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#eef2ff;">
                        <i class="bi bi-search" style="color:#4f46e5;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">استكشاف سهل</h6>
                    <p class="small text-muted mb-0">ابحث وصفِّ الجامعات والكليات والتخصصات بسهولة وسرعة</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#f0fdf4;">
                        <i class="bi bi-file-earmark-text" style="color:#16a34a;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">تقديم إلكتروني</h6>
                    <p class="small text-muted mb-0">قدّم طلب التسجيل وأرفق مستنداتك مباشرة من أي مكان</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fff7ed;">
                        <i class="bi bi-bell-fill" style="color:#ea580c;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">متابعة لحظية</h6>
                    <p class="small text-muted mb-0">تابع حالة طلبك في كل مرحلة من مراحل المراجعة والقبول</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fdf4ff;">
                        <i class="bi bi-arrow-left-right" style="color:#9333ea;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">مقارنة ذكية</h6>
                    <p class="small text-muted mb-0">قارن بين الجامعات والتخصصات لاختيار الأنسب لك</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ====== CTA ====== --}}
<section class="container py-3 mb-5">
    <div class="cta-section text-center text-white">
        <h3 class="fw-bold mb-2">مستعد للبدء؟ سجّل الآن مجاناً</h3>
        <p class="text-white-50 mb-4">انضم إلى آلاف الطلاب الذين يستخدمون EduYemen للتقديم الجامعي الإلكتروني</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap cta-btns">
            <a href="{{ route('student.apply') }}" class="btn btn-accent btn-lg px-4 px-md-5 fw-bold">
                <i class="bi bi-send-check me-2"></i>التسجيل الآن
            </a>
            <a href="{{ route('universities.index') }}" class="btn btn-outline-light btn-lg px-4 px-md-5">
                <i class="bi bi-buildings me-2"></i>تصفح الجامعات
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
{{-- نظام الإعلانات التفاعلي — يعمل فقط في حالة وجود إعلانات نشطة --}}
@if(!empty($activeAdsData) && (is_object($activeAdsData) ? $activeAdsData->isNotEmpty() : count($activeAdsData) > 0))
<script>
const adList = @json($activeAdsData);

let currentAdIndex = 0;
let adTimer = null;
let currentGallery = [];
let galleryIndex = 0;
let galleryInterval = null;

// تحديد مدة الإعلان ديناميكياً بدقة لمنح العدالة والمساواة بين الباقات:
// - الباقة الاقتصادية (صورة واحدة): 5 ثوانٍ
// - الباقة المميزة / المتوسطة (معرض صور): 7 ثوانٍ
// - الباقة الشاملة (فيديو ترويجي): 10 ثوانٍ
function getAdDuration(ad) {
    if (!ad) return 5000;
    if (ad.package === 'شاملة' || ad.mediaType === 'video' || Boolean(ad.video)) {
        return 10000;
    }
    if (ad.package === 'مميزة' || (ad.mediaType === 'gallery' && Array.isArray(ad.gallery) && ad.gallery.length > 1)) {
        return 7000;
    }
    return 5000;
}

function updateMediaDisplay(ad) {
    const mediaImg    = document.getElementById('adMediaImg');
    const mediaVideo  = document.getElementById('adMediaVideo');
    const backdrop    = document.getElementById('adMediaBackdrop');
    const videoBadge  = document.getElementById('adVideoBadge');
    const soundBtn    = document.getElementById('adSoundToggleBtn');
    const galBadge    = document.getElementById('adGalleryBadge');
    const galHoverNav = document.getElementById('adGalleryHoverNav');
    const galDots     = document.getElementById('adGalleryDots');
    
    if (galleryInterval) {
        clearInterval(galleryInterval);
        galleryInterval = null;
    }

    // إزالة أي عناصر تحكم قديمة إذا وجدت
    const oldLegacyControls = document.getElementById('adGalleryControls');
    if (oldLegacyControls) oldLegacyControls.remove();

    if (!ad) return;

    const isVideo = (ad.mediaType === 'video' || (ad.package === 'شاملة' && Boolean(ad.video)) || Boolean(ad.video));

    if (isVideo && ad.video) {
        // حالة الفيديو
        if (mediaImg) mediaImg.style.display = 'none';
        if (backdrop) backdrop.style.backgroundImage = 'none';
        if (galBadge) galBadge.style.display = 'none';
        if (galHoverNav) galHoverNav.style.display = 'none';
        if (galDots) galDots.style.display = 'none';

        if (mediaVideo) {
            mediaVideo.style.display = 'block';
            if (mediaVideo.src !== ad.video) {
                mediaVideo.src = ad.video;
            }
            mediaVideo.currentTime = 0;
            mediaVideo.muted = true; // مطلوب لضمان التشغيل التلقائي فوراً
            const playPromise = mediaVideo.play();
            if (playPromise !== undefined) {
                playPromise.catch(err => {
                    console.log('Video autoplay prevented by browser policy:', err);
                });
            }
        }

        if (videoBadge) videoBadge.style.display = 'inline-flex';
        if (soundBtn) {
            soundBtn.style.display = 'inline-flex';
            const soundIcon = document.getElementById('adSoundIcon');
            if (soundIcon && mediaVideo) {
                soundIcon.className = mediaVideo.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
            }
        }
    } else {
        // حالة الصور
        if (mediaVideo) {
            mediaVideo.pause();
            mediaVideo.currentTime = 0;
            mediaVideo.style.display = 'none';
        }
        if (videoBadge) videoBadge.style.display = 'none';
        if (soundBtn) soundBtn.style.display = 'none';

        if (mediaImg) mediaImg.style.display = 'block';

        const isGallery = (ad.package === 'مميزة' || ad.mediaType === 'gallery') && Array.isArray(ad.gallery) && ad.gallery.length > 1;

        if (isGallery) {
            currentGallery = ad.gallery;
            galleryIndex = 0;

            // عرض أول صورة مع تدرج الخلفية الضبابية
            setGalleryImage(0, false);

            // تفعيل شارة معرض الصور
            if (galBadge) {
                galBadge.style.display = 'inline-flex';
                const counter = document.getElementById('galleryCounter');
                if (counter) counter.textContent = `صورة 1 / ${currentGallery.length}`;
            }

            // تفعيل أسهم التحويم
            if (galHoverNav) galHoverNav.style.display = 'flex';

            // بناء مؤشر النقاط التفاعلي
            if (galDots) {
                galDots.style.display = 'flex';
                galDots.innerHTML = currentGallery.map((_, idx) => 
                    `<button type="button" class="ad-gallery-dot ${idx === 0 ? 'active' : ''}" onclick="setGalleryImage(${idx}, true)" aria-label="صورة ${idx + 1}"></button>`
                ).join('');
            }

            // تدوير الصور تلقائياً خلال مدة الإعلان
            const slideDuration = Math.max(2200, Math.floor(7000 / currentGallery.length));
            galleryInterval = setInterval(() => {
                const nextIdx = (galleryIndex + 1) % currentGallery.length;
                setGalleryImage(nextIdx, false);
            }, slideDuration);

        } else {
            // صورة مفردة
            currentGallery = [];
            galleryIndex = 0;
            const singleImg = ad.img || (ad.gallery && ad.gallery[0]) || '';
            if (mediaImg) {
                mediaImg.src = singleImg;
                mediaImg.style.opacity = '1';
            }
            if (backdrop) {
                backdrop.style.backgroundImage = singleImg ? `url("${singleImg}")` : 'none';
            }
            if (galBadge) galBadge.style.display = 'none';
            if (galHoverNav) galHoverNav.style.display = 'none';
            if (galDots) galDots.style.display = 'none';
        }
    }
}

function setGalleryImage(index, userInitiated) {
    if (!currentGallery || currentGallery.length === 0) return;
    galleryIndex = (index + currentGallery.length) % currentGallery.length;
    const mediaImg = document.getElementById('adMediaImg');
    const backdrop = document.getElementById('adMediaBackdrop');
    const counter  = document.getElementById('galleryCounter');
    const dots     = document.querySelectorAll('.ad-gallery-dot');

    const nextSrc = currentGallery[galleryIndex];

    if (mediaImg) {
        mediaImg.style.opacity = '0.35';
        setTimeout(() => {
            mediaImg.src = nextSrc;
            if (backdrop) backdrop.style.backgroundImage = `url("${nextSrc}")`;
            mediaImg.style.opacity = '1';
        }, 120);
    }

    if (counter) {
        counter.textContent = `صورة ${galleryIndex + 1} / ${currentGallery.length}`;
    }

    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === galleryIndex);
    });

    if (userInitiated) {
        if (galleryInterval) clearInterval(galleryInterval);
        const slideDuration = Math.max(2200, Math.floor(7000 / currentGallery.length));
        galleryInterval = setInterval(() => {
            const nextIdx = (galleryIndex + 1) % currentGallery.length;
            setGalleryImage(nextIdx, false);
        }, slideDuration);
    }
}

function navigateGallery(direction, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    setGalleryImage(galleryIndex + direction, true);
}

function navigateAd(direction, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    if (!adList || adList.length <= 1) return;
    const nextIdx = (currentAdIndex + direction + adList.length) % adList.length;
    switchAd(nextIdx);
}

function toggleAdSound(e) {
    if (e) e.stopPropagation();
    const mediaVideo = document.getElementById('adMediaVideo');
    const soundIcon = document.getElementById('adSoundIcon');
    if (!mediaVideo) return;
    mediaVideo.muted = !mediaVideo.muted;
    if (soundIcon) {
        soundIcon.className = mediaVideo.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
    }
}

function switchAd(index, skipAnim) {
    if (!adList || adList.length === 0) return;
    const content   = document.getElementById('adContent');
    const mediaImg  = document.getElementById('adMediaImg');
    const uniName   = document.getElementById('adUniName');
    const title     = document.getElementById('adTitle');
    const desc      = document.getElementById('adDesc');
    const link      = document.getElementById('adLink');
    const uniLink   = document.getElementById('adUniLink');
    const bar       = document.getElementById('adProgressBar');
    const dots      = document.querySelectorAll('.ad-dot');

    const oldVideo = document.getElementById('adMediaVideo');
    if (oldVideo) {
        oldVideo.pause();
    }

    if (!skipAnim) {
        if (content) {
            content.style.opacity  = '0';
            content.style.transform = 'translateX(12px)';
        }
        if (mediaImg) mediaImg.style.opacity = '0';
    }

    setTimeout(() => {
        currentAdIndex = index;
        const ad = adList[index];
        if (!ad) return;

        if (uniName) uniName.textContent = ad.uni;
        if (title)   title.textContent   = ad.title;
        if (desc)    desc.textContent    = ad.desc;
        if (link)    link.href           = ad.link;
        if (uniLink) uniLink.href        = ad.link;

        updateMediaDisplay(ad);

        dots.forEach((d, i) => d.classList.toggle('active', i === index));

        if (!skipAnim) {
            if (content) {
                content.style.transition  = 'opacity 0.35s ease, transform 0.35s ease';
                content.style.opacity  = '1';
                content.style.transform = 'translateX(0)';
            }
            if (mediaImg) {
                mediaImg.style.transition = 'opacity 0.35s ease';
                mediaImg.style.opacity = '1';
            }
        }

        const duration = getAdDuration(ad);

        if (bar) {
            bar.style.animation = 'none';
            bar.offsetHeight;
            bar.style.animation = `adProgress ${duration / 1000}s linear`;
        }

        startAdTimer(duration);
    }, skipAnim ? 0 : 300);
}

function startAdTimer(explicitDuration) {
    clearTimeout(adTimer);
    if (!adList || adList.length <= 1) return;
    const currentAd = adList[currentAdIndex];
    const duration = explicitDuration || getAdDuration(currentAd);
    adTimer = setTimeout(() => {
        const next = (currentAdIndex + 1) % adList.length;
        switchAd(next);
    }, duration);
}

function playAdVideo() {
    toggleAdSound();
}

function renderAdDots() {
    const dotsContainer = document.getElementById('adDots');
    if (!dotsContainer || !adList || adList.length <= 1) {
        if (dotsContainer && adList && adList.length <= 1) dotsContainer.innerHTML = '';
        return;
    }
    dotsContainer.innerHTML = adList.map((_, i) => 
        `<button class="ad-dot ${i === 0 ? 'active' : ''}" onclick="switchAd(${i})" aria-label="إعلان ${i + 1}"></button>`
    ).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    if (!adList || adList.length === 0) return;
    renderAdDots();
    switchAd(0, true);

    // إخفاء أسهم التنقل الرئيسية إذا كان هناك إعلان واحد فقط
    const prevBtn = document.getElementById('btnAdPrev');
    const nextBtn = document.getElementById('btnAdNext');
    if (adList.length <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    }

    const card = document.getElementById('adCarouselCard');
    if (card) {
        card.addEventListener('mouseenter', () => {
            clearTimeout(adTimer);
            if (galleryInterval) clearInterval(galleryInterval);
        });
        card.addEventListener('mouseleave', () => {
            startAdTimer();
            const ad = adList[currentAdIndex];
            if (ad && (ad.package === 'مميزة' || ad.mediaType === 'gallery') && currentGallery && currentGallery.length > 1) {
                const slideDur = Math.max(2200, Math.floor(7000 / currentGallery.length));
                galleryInterval = setInterval(() => {
                    const nextIdx = (galleryIndex + 1) % currentGallery.length;
                    setGalleryImage(nextIdx, false);
                }, slideDur);
            }
        });
    }
});
</script>
@endif
@endsection
