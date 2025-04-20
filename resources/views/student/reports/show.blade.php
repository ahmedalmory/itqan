@extends('layouts.dashboard')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">{{ t('view_daily_report') }}</h1>
    <div>
        <a href="{{ route('student.reports.edit', $report) }}" class="btn btn-primary me-2">
            <i class="bi bi-pencil me-1"></i> {{ t('edit') }}
        </a>
        <a href="{{ route('student.reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> {{ t('back_to_reports') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ t('report_details') }}</span>
        <span class="badge bg-{{ $report->grade >= 90 ? 'success' : ($report->grade >= 75 ? 'info' : ($report->grade >= 60 ? 'primary' : 'danger')) }}">{{ t('grade') }}: {{ number_format($report->grade, 1) }}</span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="card-title text-muted mb-3">{{ t('general_information') }}</h5>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" width="40%">{{ t('report_date') }}</th>
                        <td>{{ $report->report_date->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">{{ t('created_at') }}</th>
                        <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">{{ t('updated_at') }}</th>
                        <td>{{ $report->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="card-title text-muted mb-3">{{ t('performance') }}</h5>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" width="40%">{{ t('memorization_parts') }}</th>
                        <td>{{ $report->memorization_parts }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">{{ t('revision_parts') }}</th>
                        <td>{{ $report->revision_parts }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">{{ t('total_parts') }}</th>
                        <td>{{ $report->memorization_parts + $report->revision_parts }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="card-title text-muted mb-3">{{ t('memorization_details') }}</h5>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" width="20%">{{ t('memorization_from') }}</th>
                        <td>
                            {{ optional($report->memorizationFromSurah)->name ?? '-' }}
                            ({{ t('verse') }} {{ $report->memorization_from_verse ?? '-' }})
                        </td>
                        <th class="bg-light" width="20%">{{ t('memorization_to') }}</th>
                        <td>
                            {{ optional($report->memorizationToSurah)->name ?? '-' }}
                            ({{ t('verse') }} {{ $report->memorization_to_verse ?? '-' }})
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($report->notes)
        <div class="row">
            <div class="col-12">
                <h5 class="card-title text-muted mb-3">{{ t('notes') }}</h5>
                <div class="card">
                    <div class="card-body bg-light">
                        {{ $report->notes }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 