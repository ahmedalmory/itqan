@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('teacher_dashboard') }}</h1>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">{{ t('my_circles') }}</h5>
                    <h2 class="card-text">{{ $circles->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">{{ t('total_students') }}</h5>
                    <h2 class="card-text">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">{{ t('todays_reports') }}</h5>
                    <h2 class="card-text">{{ $totalAttendance }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">{{ t('attendance_rate') }}</h5>
                    <h2 class="card-text">
                        {{ $totalStudents ? round(($totalAttendance / $totalStudents) * 100) : 0 }}%
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ t('todays_attendance') }}</h5>
                    <a href="{{ route('teacher.daily-reports.index', ['circle_id' => $circles->first()->id ?? null]) }}" class="btn btn-sm btn-primary">{{ t('add_reports') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ t('circle') }}</th>
                                    <th>{{ t('total_students') }}</th>
                                    <th>{{ t('reports_submitted') }}</th>
                                    <th>{{ t('progress') }}</th>
                                    <th>{{ t('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance as $circle)
                                    <tr>
                                        <td>{{ $circle->circle_name }}</td>
                                        <td>{{ $circle->total_students }}</td>
                                        <td>{{ $circle->reports_submitted }}</td>
                                        <td>
                                            <div class="progress">
                                                @php
                                                    $percentage = $circle->total_students ? 
                                                        round(($circle->reports_submitted / $circle->total_students) * 100) : 0;
                                                @endphp
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $percentage }}%"
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ $percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.daily-reports.index', ['circle_id' => $circle->circle_id]) }}" 
                                               class="btn btn-sm btn-primary">
                                                {{ t('add_reports') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('recent_reports') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ t('date') }}</th>
                                    <th>{{ t('student') }}</th>
                                    <th>{{ t('circle') }}</th>
                                    <th>{{ t('memorization') }}</th>
                                    <th>{{ t('grade') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentReports as $report)
                                    <tr>
                                        <td>{{ $report->report_date }}</td>
                                        <td>{{ $report->student_name }}</td>
                                        <td>{{ $report->circle_name }}</td>
                                        <td>
                                            {{ $report->memorization_parts }} {{ t('pages') }}
                                            ({{ $report->from_surah }} - {{ $report->to_surah }})
                                        </td>
                                        <td>{{ $report->grade }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('my_circles') }}</h5>
                </div>
                <div class="card-body">
                    @foreach ($circles as $circle)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $circle->name }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        {{ t('department') }}: {{ $circle->department->name }}
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <span class="badge bg-primary">
                                        {{ str_replace('_', ' ', ucfirst($circle->circle_time)) }}
                                    </span>
                                    <span class="badge bg-info">
                                        {{ $circle->students_count }}/{{ $circle->max_students }} {{ t('students') }}
                                    </span>
                                </p>
                                <div class="mt-2">
                                    @if ($circle->whatsapp_group)
                                        <a href="{{ $circle->whatsapp_group }}" 
                                           class="btn btn-sm btn-success" target="_blank">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    @endif
                                    @if ($circle->telegram_group)
                                        <a href="{{ $circle->telegram_group }}" 
                                           class="btn btn-sm btn-primary" target="_blank">
                                            <i class="bi bi-telegram"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('teacher.circles.students', $circle->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-people"></i> {{ t('students') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.daily-reports.index', ['circle_id' => $circles->first()->id ?? null]) }}" class="btn btn-primary">
                            <i class="bi bi-journal-check"></i> {{ t('add_daily_reports') }}
                        </a>
                        <a href="{{ route('teacher.circles.index') }}" class="btn btn-info">
                            <i class="bi bi-people"></i> {{ t('manage_circles') }}
                        </a>
                        <a href="{{ route('teacher.points.index') }}" class="btn btn-success">
                            <i class="bi bi-star"></i> {{ t('manage_points') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 