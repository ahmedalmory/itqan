@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">{{ t('detailed_reports') }}</h1>
                    <p class="text-muted">{{ t('advanced_analytics_and_insights') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>{{ t('back_to_overview') }}
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

    <!-- Task Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('task_management_statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="icon-circle bg-primary mx-auto mb-2">
                                <i class="bi bi-list-task text-white"></i>
                            </div>
                            <h5 class="font-weight-bold">{{ number_format($stats['task_stats']['total_tasks']) }}</h5>
                            <p class="text-muted mb-0">{{ t('total_tasks') }}</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="icon-circle bg-success mx-auto mb-2">
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                            <h5 class="font-weight-bold">{{ number_format($stats['task_stats']['completed_tasks']) }}</h5>
                            <p class="text-muted mb-0">{{ t('completed_tasks') }}</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="icon-circle bg-info mx-auto mb-2">
                                <i class="bi bi-clock text-white"></i>
                            </div>
                            <h5 class="font-weight-bold">{{ number_format($stats['task_stats']['active_tasks']) }}</h5>
                            <p class="text-muted mb-0">{{ t('active_tasks') }}</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="icon-circle bg-warning mx-auto mb-2">
                                <i class="bi bi-percent text-white"></i>
                            </div>
                            <h5 class="font-weight-bold">{{ $stats['task_stats']['completion_rate'] }}%</h5>
                            <p class="text-muted mb-0">{{ t('completion_rate') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div class="row mb-4">
        <!-- Points Distribution -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('points_distribution') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="pointsDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Weekly Activity Pattern -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('weekly_activity_pattern') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="weeklyActivityChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Circle Performance -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('top_performing_circles') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="circlePerformanceChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Department Performance -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('department_performance') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Students Table -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('top_performing_students') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ t('rank') }}</th>
                                    <th>{{ t('student_name') }}</th>
                                    <th>{{ t('total_points') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['top_students'] as $index => $student)
                                <tr>
                                    <td>
                                        @if($index === 0)
                                            <i class="bi bi-trophy-fill text-warning"></i>
                                        @elseif($index === 1)
                                            <i class="bi bi-award-fill text-secondary"></i>
                                        @elseif($index === 2)
                                            <i class="bi bi-award-fill text-orange"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($student->student_points_sum_total_points ?? 0) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Progress -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('monthly_progress_summary') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ t('month') }}</th>
                                    <th>{{ t('memorized') }}</th>
                                    <th>{{ t('reports') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['monthly_progress'] as $progress)
                                <tr>
                                    <td>{{ \Carbon\Carbon::create($progress->year, $progress->month)->format('M Y') }}</td>
                                    <td>{{ number_format($progress->total_memorized, 1) }}</td>
                                    <td>{{ number_format($progress->total_reports) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Distribution -->
    @if($stats['age_distribution']->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('student_age_distribution') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="ageDistributionChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Country Distribution -->
    @if($stats['country_distribution']->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('student_country_distribution') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($stats['country_distribution'] as $country)
                        <div class="col-md-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $country->country->name ?? 'Unknown' }}</span>
                                <span class="badge bg-primary">{{ $country->count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Circle Performance Details -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">{{ t('circle_performance_details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ t('circle_name') }}</th>
                                    <th>{{ t('teacher') }}</th>
                                    <th>{{ t('supervisor') }}</th>
                                    <th>{{ t('students_count') }}</th>
                                    <th>{{ t('status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['circle_performance'] as $circle)
                                <tr>
                                    <td>{{ $circle->name }}</td>
                                    <td>{{ $circle->teacher->name ?? 'N/A' }}</td>
                                    <td>{{ $circle->supervisor->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $circle->students_count }}</span>
                                    </td>
                                    <td>
                                        @if($circle->is_active)
                                            <span class="badge bg-success">{{ t('active') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ t('inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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

.text-orange {
    color: #fd7e14 !important;
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

.table th {
    border-top: none;
    font-weight: 600;
}
</style>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Points Distribution Chart
    const pointsCtx = document.getElementById('pointsDistributionChart').getContext('2d');
    new Chart(pointsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['points_distribution']->keys()) !!},
            datasets: [{
                label: '{{ t("students") }}',
                data: {!! json_encode($chartData['points_distribution']->values()) !!},
                backgroundColor: [
                    '#dc3545',
                    '#ffc107',
                    '#28a745',
                    '#007bff',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Weekly Activity Chart
    const weeklyCtx = document.getElementById('weeklyActivityChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['weekly_activity']->pluck('day')) !!},
            datasets: [{
                label: '{{ t("reports") }}',
                data: {!! json_encode($chartData['weekly_activity']->pluck('count')) !!},
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

    // Circle Performance Chart
    const circleCtx = document.getElementById('circlePerformanceChart').getContext('2d');
    new Chart(circleCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['circle_activity']->pluck('name')) !!},
            datasets: [{
                label: '{{ t("students") }}',
                data: {!! json_encode($chartData['circle_activity']->pluck('students')) !!},
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Department Performance Chart
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartData['department_performance']->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($chartData['department_performance']->pluck('circles')) !!},
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d',
                    '#17a2b8'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    @if($stats['age_distribution']->isNotEmpty())
    // Age Distribution Chart
    const ageCtx = document.getElementById('ageDistributionChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['age_distribution']->pluck('age')) !!},
            datasets: [{
                label: '{{ t("students") }}',
                data: {!! json_encode($stats['age_distribution']->pluck('count')) !!},
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
});
</script>
@endsection 