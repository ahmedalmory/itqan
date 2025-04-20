@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Report Details') }}</h1>
        <div>
            <a href="{{ route('admin.reports.daily') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Reports') }}
            </a>
            <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> {{ t('Edit Report') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">{{ t('Basic Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Report ID') }}:</div>
                        <div class="col-sm-8">{{ $report->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Student') }}:</div>
                        <div class="col-sm-8">
                            <a href="{{ route('admin.users.show', $report->student_id) }}">
                                {{ $report->student->name ?? t('N/A') }}
                            </a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Circle') }}:</div>
                        <div class="col-sm-8">
                            @if($report->student && $report->student->circles->first())
                                <a href="{{ route('admin.circles.show', $report->student->circles->first()->id) }}">
                                    {{ $report->student->circles->first()->name }}
                                </a>
                            @else
                                {{ t('Not assigned') }}
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Department') }}:</div>
                        <div class="col-sm-8">
                            @if($report->student && $report->student->circles->first() && $report->student->circles->first()->department)
                                {{ $report->student->circles->first()->department->name }}
                            @else
                                {{ t('Not assigned') }}
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Report Date') }}:</div>
                        <div class="col-sm-8">{{ $report->report_date->format('Y-m-d') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Grade') }}:</div>
                        <div class="col-sm-8">
                            @if($report->grade >= 90)
                                <span class="badge bg-success">{{ $report->grade }}</span>
                            @elseif($report->grade >= 75)
                                <span class="badge bg-info text-dark">{{ $report->grade }}</span>
                            @elseif($report->grade >= 60)
                                <span class="badge bg-warning text-dark">{{ $report->grade }}</span>
                            @else
                                <span class="badge bg-danger">{{ $report->grade }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-bold">{{ t('Created At') }}:</div>
                        <div class="col-sm-8">{{ $report->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">{{ t('Performance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5 fw-bold">{{ t('Memorization Parts') }}:</div>
                        <div class="col-sm-7">{{ $report->memorization_parts }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 fw-bold">{{ t('Revision Parts') }}:</div>
                        <div class="col-sm-7">{{ $report->revision_parts }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 fw-bold">{{ t('Memorization Range') }}:</div>
                        <div class="col-sm-7">
                            @if($report->memorization_from_surah)
                                {{ $report->memorization_from_surah->name ?? '' }}
                                {{ $report->memorization_from_verse ? '(' . $report->memorization_from_verse . ')' : '' }}
                                -
                                {{ $report->memorization_to_surah->name ?? '' }}
                                {{ $report->memorization_to_verse ? '(' . $report->memorization_to_verse . ')' : '' }}
                            @else
                                {{ t('Not specified') }}
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 fw-bold">{{ t('Revision Range') }}:</div>
                        <div class="col-sm-7">
                            @if($report->revision_from_surah)
                                {{ $report->revision_from_surah->name ?? '' }}
                                {{ $report->revision_from_verse ? '(' . $report->revision_from_verse . ')' : '' }}
                                -
                                {{ $report->revision_to_surah->name ?? '' }}
                                {{ $report->revision_to_verse ? '(' . $report->revision_to_verse . ')' : '' }}
                            @else
                                {{ t('Not specified') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">{{ t('Notes') }}</h5>
                </div>
                <div class="card-body">
                    @if($report->notes)
                        <p>{{ $report->notes }}</p>
                    @else
                        <p class="text-muted">{{ t('No notes provided.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Progress Comparison -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">{{ t('Recent Activity') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h6>{{ t('Recent Reports for') }} {{ $report->student->name ?? t('this student') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>{{ t('Date') }}</th>
                                    <th>{{ t('Memorization') }}</th>
                                    <th>{{ t('Revision') }}</th>
                                    <th>{{ t('Grade') }}</th>
                                    <th>{{ t('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentReports = \App\Models\DailyReport::where('student_id', $report->student_id)
                                                    ->where('id', '!=', $report->id)
                                                    ->orderBy('report_date', 'desc')
                                                    ->limit(5)
                                                    ->get();
                                @endphp
                                
                                @if($recentReports->count() > 0)
                                    @foreach($recentReports as $recentReport)
                                        <tr>
                                            <td>{{ $recentReport->report_date->format('Y-m-d') }}</td>
                                            <td>{{ $recentReport->memorization_parts }}</td>
                                            <td>{{ $recentReport->revision_parts }}</td>
                                            <td>
                                                @if($recentReport->grade >= 90)
                                                    <span class="badge bg-success">{{ $recentReport->grade }}</span>
                                                @elseif($recentReport->grade >= 75)
                                                    <span class="badge bg-info text-dark">{{ $recentReport->grade }}</span>
                                                @elseif($recentReport->grade >= 60)
                                                    <span class="badge bg-warning text-dark">{{ $recentReport->grade }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $recentReport->grade }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.reports.show', $recentReport->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">{{ t('No recent reports found.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 