@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('my_progress') }}</h5>
            <div>
                <a href="{{ route('student.progress.points') }}" class="btn btn-sm btn-primary me-2">
                    {{ t('view_points') }}
                </a>
                <a href="{{ route('student.progress.attendance') }}" class="btn btn-sm btn-secondary">
                    {{ t('view_attendance') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6>{{ t('total_memorization') }}</h6>
                            <h2 class="text-primary">{{ $totalMemorization }}</h2>
                            <p class="text-muted mb-0">{{ t('parts') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6>{{ t('total_revision') }}</h6>
                            <h2 class="text-success">{{ $totalRevision }}</h2>
                            <p class="text-muted mb-0">{{ t('parts') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6>{{ t('average_grade') }}</h6>
                            <h2 class="text-info">{{ number_format($averageGrade, 1) }}</h2>
                            <p class="text-muted mb-0">{{ t('out_of_10') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-3">{{ t('monthly_progress') }}</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ t('month') }}</th>
                            <th>{{ t('memorization') }}</th>
                            <th>{{ t('revision') }}</th>
                            <th>{{ t('average_grade') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyProgress as $progress)
                            <tr>
                                <td>{{ date('F Y', mktime(0, 0, 0, $progress->month, 1, $progress->year)) }}</td>
                                <td>{{ $progress->total_memorization }}</td>
                                <td>{{ $progress->total_revision }}</td>
                                <td>{{ number_format($progress->average_grade, 1) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ t('no_monthly_progress_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <h5 class="mb-3">{{ t('points_by_circle') }}</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ t('circle') }}</th>
                            <th>{{ t('points') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pointsByCircle as $circleId => $points)
                            <tr>
                                <td>{{ $points->first()->circle->name }}</td>
                                <td>{{ $points->sum('points') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">{{ t('no_points_earned_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <h5 class="mb-3">{{ t('recent_reports') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ t('date') }}</th>
                            <th>{{ t('memorization') }}</th>
                            <th>{{ t('revision') }}</th>
                            <th>{{ t('grade') }}</th>
                            <th>{{ t('notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->report_date->format('Y-m-d') }}</td>
                                <td>{{ $report->memorization_parts }}</td>
                                <td>{{ $report->revision_parts }}</td>
                                <td>{{ $report->grade }}</td>
                                <td>{{ Str::limit($report->notes, 50) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ t('no_reports_submitted_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 