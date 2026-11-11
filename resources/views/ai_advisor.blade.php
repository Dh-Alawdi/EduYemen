@extends('layouts.app')

@section('title', 'المساعد الذكي للتوجيه الأكاديمي — EduYemen')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المساعد الذكي</li>
                </ol>
            </nav>

            <div class="card border-0 shadow rounded-4 text-center p-5">
                <div class="mb-3" style="width:90px; height:90px; background:linear-gradient(135deg,#eef2ff,#dde6ff); border-radius:22px; display:flex; align-items:center; justify-content:center; font-size:2.8rem; margin:0 auto 1.2rem;">
                    <i class="bi bi-robot" style="color:#4f46e5;"></i>
                </div>
                <h3 class="fw-bold mb-2">المساعد الذكي للتوجيه الأكاديمي</h3>
                <p class="text-muted mx-auto mb-4" style="max-width:480px; line-height:1.8;">
                    أداة ذكية مصممة لتحليل معدّل الطالب واهتماماته وموقعه، وترشيح أفضل الجامعات والتخصصات الأنسب له بناءً على بياناته الأكاديمية.
                </p>

                <div class="alert alert-info d-inline-flex align-items-center gap-3 text-start mx-auto rounded-3 mb-4" style="max-width:480px;">
                    <i class="bi bi-info-circle-fill fs-4 text-info flex-shrink-0"></i>
                    <div class="small">
                        <strong>قيد التطوير:</strong><br>
                        هذه الخاصية مُدرجة في خطة تطوير المنصة وسيتم إطلاقها في الإصدار القادم.
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('universities.index') }}" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-buildings me-2"></i>تصفح الجامعات يدوياً
                    </a>
                    <a href="{{ route('compare') }}" class="btn btn-outline-primary px-4">
                        <i class="bi bi-arrow-left-right me-2"></i>مقارنة الجامعات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
