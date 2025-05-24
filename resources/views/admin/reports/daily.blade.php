@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Daily Reports') }}</h1>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload"></i> {{ t('Import CSV') }}
            </button>
            <a href="{{ route('admin.reports') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-bar-chart"></i> {{ t('Monthly Summary') }}
            </a>
            <a href="{{ route('admin.reports.export-daily') }}" class="btn btn-success">
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

    <div class="card mb-4">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.reports.daily') }}" class="row g-3">
                <div class="col-md-2">
                    <select name="department_id" class="form-select">
                        <option value="">{{ t('All Departments') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="circle_id" class="form-select">
                        <option value="">{{ t('All Circles') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ request('circle_id') == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">{{ t('From') }}</span>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">{{ t('To') }}</span>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="student_name" 
                           placeholder="{{ t('Student Name') }}" value="{{ request('student_name') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                    <a href="{{ route('admin.reports.daily') }}" class="btn btn-outline-secondary">{{ t('Clear') }}</a>
                </div>
            </form>
        </div>
    </div>

    @if($reports->isEmpty())
        <div class="alert alert-info">
            {{ t('No reports found matching your criteria.') }}
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ t('Date') }}</th>
                        <th>{{ t('Student') }}</th>
                        <th>{{ t('Circle') }}</th>
                        <th>{{ t('Department') }}</th>
                        <th>{{ t('Memorization') }}</th>
                        <th>{{ t('Revision') }}</th>
                        <th>{{ t('Grade') }}</th>
                        <th>{{ t('Notes') }}</th>
                        <th>{{ t('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $report->student_id) }}" class="text-decoration-none">
                                    {{ $report->student->name ?? t('N/A') }}
                                </a>
                            </td>
                            <td>
                                @if($report->student && $report->student->circle)
                                    <a href="{{ route('admin.circles.show', $report->student->circle_id) }}" class="text-decoration-none">
                                        {{ $report->student->circle->name ?? t('N/A') }}
                                    </a>
                                @else
                                    {{ t('N/A') }}
                                @endif
                            </td>
                            <td>
                                @if($report->student && $report->student->circle && $report->student->circle->department)
                                    {{ $report->student->circle->department->name ?? t('N/A') }}
                                @else
                                    {{ t('N/A') }}
                                @endif
                            </td>
                            <td>
                                {{ $report->memorization_parts }} {{ t('parts') }}
                                @if($report->memorization_from_surah)
                                    <small class="d-block text-muted">
                                        {{ $report->memorization_from_surah->name ?? '' }} 
                                        {{ $report->memorization_from_verse ? $report->memorization_from_verse : '' }}
                                        -
                                        {{ $report->memorization_to_surah->name ?? '' }}
                                        {{ $report->memorization_to_verse ? $report->memorization_to_verse : '' }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                {{ $report->revision_parts }} {{ t('parts') }}
                                @if($report->revision_from_surah)
                                    <small class="d-block text-muted">
                                        {{ $report->revision_from_surah->name ?? '' }}
                                        {{ $report->revision_from_verse ? $report->revision_from_verse : '' }}
                                        -
                                        {{ $report->revision_to_surah->name ?? '' }}
                                        {{ $report->revision_to_verse ? $report->revision_to_verse : '' }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($report->grade >= 90)
                                    <span class="badge bg-success">{{ $report->grade }}</span>
                                @elseif($report->grade >= 75)
                                    <span class="badge bg-info text-dark">{{ $report->grade }}</span>
                                @elseif($report->grade >= 60)
                                    <span class="badge bg-warning text-dark">{{ $report->grade }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $report->grade }}</span>
                                @endif
                            </td>
                            <td>
                                @if($report->notes)
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="popover" data-bs-trigger="focus" 
                                            data-bs-content="{{ $report->notes }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">{{ t('Import Reports from CSV') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.reports.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">{{ t('CSV File') }}</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            {{ t('Required columns: Student Name, Report Date, Memorization Parts, Revision Parts, Grade, Notes') }}
                        </div>
                    </div>
                    
                    @if($errors->has('csv_file'))
                        <div class="alert alert-danger">
                            {{ $errors->first('csv_file') }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ t('Import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true
            });
        });
    });
</script>
@endpush

@endsection 