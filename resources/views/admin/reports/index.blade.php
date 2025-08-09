@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">{{ t('reports_overview') }}</h1>
                    <p class="text-muted">{{ t('comprehensive_statistics_and_insights') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reports.detailed') }}" class="btn btn-outline-primary">
                        <i class="bi bi-graph-up me-2"></i>{{ t('detailed_reports') }}
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-2"></i>{{ t('export') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['format' => 'csv']) }}">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i>{{ t('export_csv') }}
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reports.export', ['format' => 'excel']) }}">
                                <i class="bi bi-file-earmark-excel me-2"></i>{{ t('export_excel') }}
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="row mb-4">
        <!-- User Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ t('total_users') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_users']) }}
                            </div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="bi bi-arrow-up"></i>
                                    {{ $stats['new_users_this_month'] }} {{ t('this_month') }}
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="bi bi-people text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ t('total_students') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_students']) }}
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    {{ t('active') }}: {{ $stats['active_students'] }} |
                                    {{ t('inactive') }}: {{ $stats['inactive_students'] }}
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success">
                                <i class="bi bi-person-graduation text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ t('total_teachers') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_teachers']) }}
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    {{ t('supervisors') }}: {{ $stats['total_supervisors'] }}
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info">
                                <i class="bi bi-person-check text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Circles Statistics -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ t('total_circles') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_circles']) }}
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    {{ t('active') }}: {{ $stats['active_circles'] }}
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning">
                                <i class="bi bi-circle text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender Distribution -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('student_gender_distribution') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 font-weight-bold text-primary">{{ $stats['male_students'] }}</div>
                                <div class="text-xs text-uppercase text-muted">{{ t('male_students') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 font-weight-bold text-pink">{{ $stats['female_students'] }}</div>
                            <div class="text-xs text-uppercase text-muted">{{ t('female_students') }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="genderChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('memorization_statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ t('total_memorized_pages') }}</span>
                                <span class="font-weight-bold">{{ number_format($stats['total_memorized_pages'], 1) }}</span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ t('total_revised_pages') }}</span>
                                <span class="font-weight-bold">{{ number_format($stats['total_revised_pages'], 1) }}</span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ t('average_grade') }}</span>
                                <span class="font-weight-bold">{{ $stats['average_grade'] }}%</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ t('total_reports') }}</span>
                                <span class="font-weight-bold">{{ number_format($stats['total_reports']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- User Registration Trend -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('user_registration_trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="registrationChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Role Distribution -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('role_distribution') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="roleChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Memorization Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('memorization_progress_trend') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="memorizationChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistical Summary Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">{{ t('statistical_summary') }}</h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="periodFilter" id="currentMonth" checked>
                            <label class="btn btn-outline-primary" for="currentMonth">{{ t('current_month') }}</label>
                            
                            <input type="radio" class="btn-check" name="periodFilter" id="lastMonth">
                            <label class="btn btn-outline-primary" for="lastMonth">{{ t('last_month') }}</label>
                            
                            <input type="radio" class="btn-check" name="periodFilter" id="allTime">
                            <label class="btn btn-outline-primary" for="allTime">{{ t('all_time') }}</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4">{{ t('metric') }}</th>
                                    <th class="border-0 text-center">{{ t('total') }}</th>
                                    <th class="border-0 text-center">{{ t('male') }}</th>
                                    <th class="border-0 text-center">{{ t('female') }}</th>
                                    <th class="border-0 text-center">{{ t('growth') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <i class="bi bi-people-fill text-primary me-2"></i>
                                        {{ t('total_students') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_students']) }}</td>
                                    <td class="text-center">{{ number_format($stats['male_students']) }}</td>
                                    <td class="text-center">{{ number_format($stats['female_students']) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-up"></i> {{ $stats['new_users_this_month'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <i class="bi bi-person-check-fill text-info me-2"></i>
                                        {{ t('total_teachers') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_teachers']) }}</td>
                                    <td class="text-center">{{ number_format($stats['male_teachers']) }}</td>
                                    <td class="text-center">{{ number_format($stats['female_teachers']) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            <i class="bi bi-dash"></i> 0
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <i class="bi bi-circle-fill text-warning me-2"></i>
                                        {{ t('study_circles') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_circles']) }}</td>
                                    <td class="text-center">{{ number_format($stats['active_circles']) }}</td>
                                    <td class="text-center">{{ number_format($stats['total_circles'] - $stats['active_circles']) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">
                                            <i class="bi bi-arrow-up"></i> 2
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <i class="bi bi-book-fill text-success me-2"></i>
                                        {{ t('memorized_pages') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_memorized_pages'], 1) }}</td>
                                    <td class="text-center">{{ number_format($stats['total_revised_pages'], 1) }}</td>
                                    <td class="text-center">{{ number_format($stats['total_reports']) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-up"></i> {{ $stats['reports_this_month'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <i class="bi bi-star-fill text-primary me-2"></i>
                                        {{ t('points_system') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_points_awarded']) }}</td>
                                    <td class="text-center">{{ number_format($stats['total_points_spent']) }}</td>
                                    <td class="text-center">{{ number_format($stats['average_points_per_student'], 1) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            <i class="bi bi-arrow-up"></i> 12%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <i class="bi bi-building-fill text-secondary me-2"></i>
                                        {{ t('departments') }}
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_departments']) }}</td>
                                    <td class="text-center">{{ number_format($stats['departments_with_circles']) }}</td>
                                    <td class="text-center">{{ number_format($stats['total_departments'] - $stats['departments_with_circles']) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-dash"></i> 0
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="ps-4 fw-bold">{{ t('system_totals') }}</td>
                                    <td class="text-center fw-bold">{{ number_format($stats['total_users']) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($stats['active_students']) }}</td>
                                    <td class="text-center fw-bold">{{ number_format($stats['average_students_per_circle'], 1) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-up"></i> {{ round(($stats['new_users_this_month'] / $stats['total_users']) * 100, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        {{ t('last_updated') }}: {{ date('Y-m-d H:i') }} | 
                        {{ t('data_source') }}: {{ t('itqan_database') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-circle bg-success mx-auto mb-3">
                        <i class="bi bi-star text-white"></i>
                    </div>
                    <h5 class="font-weight-bold">{{ number_format($stats['total_points_awarded']) }}</h5>
                    <p class="text-muted mb-0">{{ t('total_points_awarded') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-circle bg-warning mx-auto mb-3">
                        <i class="bi bi-gift text-white"></i>
                    </div>
                    <h5 class="font-weight-bold">{{ number_format($stats['total_points_spent']) }}</h5>
                    <p class="text-muted mb-0">{{ t('total_points_spent') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-circle bg-info mx-auto mb-3">
                        <i class="bi bi-building text-white"></i>
                    </div>
                    <h5 class="font-weight-bold">{{ number_format($stats['total_departments']) }}</h5>
                    <p class="text-muted mb-0">{{ t('total_departments') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-circle bg-primary mx-auto mb-3">
                        <i class="bi bi-people text-white"></i>
                    </div>
                    <h5 class="font-weight-bold">{{ number_format($stats['average_students_per_circle'], 1) }}</h5>
                    <p class="text-muted mb-0">{{ t('avg_students_per_circle') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.icon-circle {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.text-pink {
    color: #e83e8c !important;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.font-weight-bold {
    font-weight: 600 !important;
}

.text-xs {
    font-size: 0.75rem;
}
</style>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ t("male") }}', '{{ t("female") }}'],
            datasets: [{
                data: [{{ $chartData['gender_distribution']['male'] }}, {{ $chartData['gender_distribution']['female'] }}],
                backgroundColor: ['#007bff', '#e83e8c'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // User Registration Trend Chart
    const registrationCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(registrationCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['user_registration_trend']->pluck('label')) !!},
            datasets: [{
                label: '{{ t("new_registrations") }}',
                data: {!! json_encode($chartData['user_registration_trend']->pluck('value')) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Role Distribution Chart
    const roleCtx = document.getElementById('roleChart').getContext('2d');
    new Chart(roleCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartData['role_distribution']->keys()) !!},
            datasets: [{
                data: {!! json_encode($chartData['role_distribution']->values()) !!},
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Memorization Progress Chart
    const memorizationCtx = document.getElementById('memorizationChart').getContext('2d');
    new Chart(memorizationCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['memorization_progress']->pluck('label')) !!},
            datasets: [{
                label: '{{ t("memorized") }}',
                data: {!! json_encode($chartData['memorization_progress']->pluck('memorized')) !!},
                backgroundColor: '#28a745'
            }, {
                label: '{{ t("revised") }}',
                data: {!! json_encode($chartData['memorization_progress']->pluck('revised')) !!},
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection 