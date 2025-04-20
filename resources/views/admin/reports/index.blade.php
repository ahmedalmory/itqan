@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Monthly Reports Summary') }}</h1>
        <div>
            <a href="{{ route('admin.reports.daily') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-journal-text"></i> {{ t('View Daily Reports') }}
            </a>
            <a href="{{ route('admin.reports.export') }}" class="btn btn-success">
                <i class="bi bi-download"></i> {{ t('Export to CSV') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.reports') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">{{ t('All Departments') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">{{ t('From') }}</span>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">{{ t('To') }}</span>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">{{ t('Clear') }}</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($reports->isEmpty())
                <div class="alert alert-info">
                    {{ t('No reports found matching your criteria.') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Month') }}</th>
                                <th>{{ t('Department') }}</th>
                                <th>{{ t('Students') }}</th>
                                <th>{{ t('Reports') }}</th>
                                <th>{{ t('Avg. Grade') }}</th>
                                <th>{{ t('Total Memorization') }}</th>
                                <th>{{ t('Total Revision') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $report->month)->format('F Y') }}</td>
                                    <td>{{ $report->department_name ?? t('N/A') }}</td>
                                    <td>{{ $report->students_count }}</td>
                                    <td>{{ $report->reports_count }}</td>
                                    <td>
                                        @if($report->average_grade >= 90)
                                            <span class="badge bg-success">{{ number_format($report->average_grade, 1) }}</span>
                                        @elseif($report->average_grade >= 75)
                                            <span class="badge bg-info text-dark">{{ number_format($report->average_grade, 1) }}</span>
                                        @elseif($report->average_grade >= 60)
                                            <span class="badge bg-warning text-dark">{{ number_format($report->average_grade, 1) }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ number_format($report->average_grade, 1) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($report->total_memorization, 2) }} {{ t('parts') }}</td>
                                    <td>{{ number_format($report->total_revision, 2) }} {{ t('parts') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $reports->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ t('Monthly Performance') }}
                </div>
                <div class="card-body">
                    <canvas id="monthlyPerformanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ t('Department Comparison') }}
                </div>
                <div class="card-body">
                    <canvas id="departmentComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Extract data for charts
        const months = @json($reports->pluck('month')->map(function($month) {
            return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
        }));
        
        const averageGrades = @json($reports->pluck('average_grade'));
        const totalMemorization = @json($reports->pluck('total_memorization'));
        const totalRevision = @json($reports->pluck('total_revision'));
        const departments = @json($reports->pluck('department_name'));
        
        // Monthly Performance Chart
        const performanceCtx = document.getElementById('monthlyPerformanceChart').getContext('2d');
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: '{{ t("Average Grade") }}',
                        data: averageGrades,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: Math.max(0, Math.min(...averageGrades) - 10),
                        max: 100
                    }
                }
            }
        });
        
        // Department Comparison Chart
        const departmentCtx = document.getElementById('departmentComparisonChart').getContext('2d');
        new Chart(departmentCtx, {
            type: 'bar',
            data: {
                labels: departments,
                datasets: [
                    {
                        label: '{{ t("Memorization") }}',
                        data: totalMemorization,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ t("Revision") }}',
                        data: totalRevision,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection 