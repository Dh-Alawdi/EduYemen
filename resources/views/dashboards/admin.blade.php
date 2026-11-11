@extends('layouts.app')

@section('title', 'لوحة تحكم مدير النظام - منصة EduYemen')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1"><i class="bi bi-shield-check me-2"></i>لوحة تحكم مدير النظام</h3>
            <p class="text-muted small mb-0">مرحباً بك، يمكنك إدارة الجامعات والمستخدمين ومتابعة مؤشرات المنصة العامة.</p>
        </div>
        <span class="badge bg-danger px-3 py-2 fs-6">حساب المشرف العام</span>
    </div>

    <!-- الإحصائيات السريعة -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-primary border-4">
                <div class="text-muted small">إجمالي الجامعات</div>
                <h3 class="fw-bold mb-0 text-primary">{{ $stats['total_universities'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-success border-4">
                <div class="text-muted small">الجامعات النشطة</div>
                <h3 class="fw-bold mb-0 text-success">{{ $stats['active_universities'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-info border-4">
                <div class="text-muted small">الطلاب المسجلين</div>
                <h3 class="fw-bold mb-0 text-info">{{ $stats['total_students'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-warning border-4">
                <div class="text-muted small">طلبات التسجيل قيد المراجعة</div>
                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_requests'] }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- قائمة الجامعات المسجلة -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-buildings me-2"></i>الجامعات المسجلة في المنصة</h5>
                    <span class="badge bg-primary">{{ $universities->count() }} جامعة</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم الجامعة</th>
                                <th>النوع</th>
                                <th>المديرية</th>
                                <th>عدد الكليات</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($universities as $univ)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold text-primary">{{ $univ->university_name }}</td>
                                <td><span class="badge {{ $univ->university_type == 'حكومية' ? 'bg-secondary' : 'bg-info text-dark' }}">{{ $univ->university_type }}</span></td>
                                <td>{{ $univ->district }}</td>
                                <td>{{ $univ->colleges_count }}</td>
                                <td>
                                    <span class="badge {{ $univ->status == 'نشطة' ? 'bg-success' : 'bg-secondary' }}">{{ $univ->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- آخر الطلبات المسجلة -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2"></i>أحدث الطلبات</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentRequests as $req)
                        <li class="list-group-item p-3">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">{{ $req->full_name }}</span>
                                <span class="badge bg-warning text-dark">{{ $req->status }}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-building me-1"></i> {{ $req->university->university_name }}
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-mortarboard me-1"></i> {{ $req->desired_spec }}
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-4">لا توجد طلبات بعد</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
