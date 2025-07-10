@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('reports_history') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('daily_reports_history') }}</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-info btn-sm" onclick="generateStudentsSummary()">
                    <i class="bi bi-image"></i> {{ t('generate_students_summary') }}
                </button>
                <a href="{{ route('teacher.daily-reports.index') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-calendar-month"></i> {{ t('back_to_calendar') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('teacher.daily-reports.history') }}" method="GET" class="mb-4" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="circle_id" class="form-label">{{ t('circle') }}</label>
                        <select name="circle_id" id="circle_id" class="form-select">
                            <option value="">{{ t('all_circles') }}</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" @if(isset($filters['circle_id']) && $filters['circle_id'] == $circle->id) selected @endif>
                                    {{ $circle->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="student_id" class="form-label">{{ t('student') }}</label>
                        <select name="student_id" id="student_id" class="form-select">
                            <option value="">{{ t('all_students') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" @if(isset($filters['student_id']) && $filters['student_id'] == $student->id) selected @endif>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">{{ t('from_date') }}</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                               value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">{{ t('to_date') }}</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                               value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> {{ t('filter') }}
                        </button>
                        <a href="{{ route('teacher.daily-reports.history') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> {{ t('clear_filters') }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Reports Table -->
            @if($reports->isEmpty())
                <div class="alert alert-info">
                    {{ t('no_reports_found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('date') }}</th>
                                <th>{{ t('student') }}</th>
                                <th>{{ t('memorization') }}</th>
                                <th>{{ t('revision') }}</th>
                                <th>{{ t('grade') }}</th>
                                <th>{{ t('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ $report->report_date }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($report->student->profile_photo)
                                                <img src="{{ asset('storage/' . $report->student->profile_photo) }}" 
                                                     class="rounded-circle me-2" style="width: 30px; height: 30px;" 
                                                     alt="{{ $report->student->name }}">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px; color: white; font-size: 12px;">
                                                    {{ substr($report->student->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>{{ $report->student->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($report->memorization_parts > 0)
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ $report->memorization_parts }} {{ t('pages') }}</span>
                                                @if($report->fromSurah || $report->toSurah)
                                                    <i class="bi bi-info-circle text-info cursor-pointer" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       data-bs-html="true"
                                                       title="
                                                       <div class='text-start'>
                                                           <strong>{{ t('memorization_details') }}</strong><br>
                                                           {{ t('from') }}: {{ optional($report->fromSurah)->name ?? 'N/A' }} 
                                                           @if($report->memorization_from_verse)
                                                               - {{ t('verse') }} {{ $report->memorization_from_verse }}
                                                           @endif
                                                           <br>
                                                           {{ t('to') }}: {{ optional($report->toSurah)->name ?? 'N/A' }}
                                                           @if($report->memorization_to_verse)
                                                               - {{ t('verse') }} {{ $report->memorization_to_verse }}
                                                           @endif
                                                           @if($report->fromSurah && is_object($report->fromSurah) && $report->fromSurah->total_verses)
                                                               <br><small>{{ $report->fromSurah->name }}: {{ $report->fromSurah->total_verses }} {{ t('verses') }}</small>
                                                           @endif
                                                           @if($report->toSurah && is_object($report->toSurah) && $report->toSurah->total_verses && $report->toSurah->id != optional($report->fromSurah)->id)
                                                               <br><small>{{ $report->toSurah->name }}: {{ $report->toSurah->total_verses }} {{ t('verses') }}</small>
                                                           @endif
                                                       </div>
                                                       "></i>
                                                @endif
                                            </div>
                                            @if($report->fromSurah || $report->toSurah)
                                                <small class="text-muted d-block">
                                                    ({{ optional($report->fromSurah)->name ?? 'N/A' }} - {{ optional($report->toSurah)->name ?? 'N/A' }})
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ t('no_memorization') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->revision_parts > 0)
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ $report->revision_parts }} {{ t('pages') }}</span>
                                                @if($report->revision_from_surah || $report->revision_to_surah)
                                                    <i class="bi bi-info-circle text-info cursor-pointer" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       data-bs-html="true"
                                                       title="
                                                       <div class='text-start'>
                                                           <strong>{{ t('revision_details') }}</strong><br>
                                                           {{ t('from') }}: {{ optional($report->revisionFromSurah)->name ?? 'N/A' }} 
                                                           @if($report->revision_from_verse)
                                                               - {{ t('verse') }} {{ $report->revision_from_verse }}
                                                           @endif
                                                           <br>
                                                           {{ t('to') }}: {{ optional($report->revisionToSurah)->name ?? 'N/A' }}
                                                           @if($report->revision_to_verse)
                                                               - {{ t('verse') }} {{ $report->revision_to_verse }}
                                                           @endif
                                                           @if($report->revisionFromSurah && is_object($report->revisionFromSurah) && $report->revisionFromSurah->total_verses)
                                                               <br><small>{{ $report->revisionFromSurah->name }}: {{ $report->revisionFromSurah->total_verses }} {{ t('verses') }}</small>
                                                           @endif
                                                           @if($report->revisionToSurah && is_object($report->revisionToSurah) && $report->revisionToSurah->total_verses && $report->revisionToSurah->id != optional($report->revisionFromSurah)->id)
                                                               <br><small>{{ $report->revisionToSurah->name }}: {{ $report->revisionToSurah->total_verses }} {{ t('verses') }}</small>
                                                           @endif
                                                       </div>
                                                       "></i>
                                                @endif
                                            </div>
                                            @if($report->revisionFromSurah || $report->revisionToSurah)
                                                <small class="text-muted d-block">
                                                    ({{ optional($report->revisionFromSurah)->name ?? 'N/A' }} - {{ optional($report->revisionToSurah)->name ?? 'N/A' }})
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ t('no_revision') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $report->grade >= 80 ? 'success' : ($report->grade >= 60 ? 'warning' : 'danger') }}">
                                            {{ $report->grade }}%
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('teacher.daily-reports.destroy', $report->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('{{ t('confirm_delete_report') }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Students Summary Modal -->
<div class="modal fade" id="studentsSummaryModal" tabindex="-1" aria-labelledby="studentsSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentsSummaryModalLabel">{{ t('students_summary') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="studentsSummaryContent" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">{{ t('loading') }}</span>
                    </div>
                    <p class="mt-2">{{ t('generating_summary') }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('close') }}</button>
                <button type="button" class="btn btn-primary" id="downloadSummaryBtn" style="display: none;">
                    <i class="bi bi-download"></i> {{ t('download') }}
                </button>
                <button type="button" class="btn btn-success" id="shareSummaryBtn" style="display: none;">
                    <i class="bi bi-share"></i> {{ t('share') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function generateStudentsSummary() {
    const modal = new bootstrap.Modal(document.getElementById('studentsSummaryModal'));
    modal.show();
    
    // Get current filters
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams(formData);
    
    // Add export parameter
    params.append('export', 'summary');
    
    fetch(`{{ route('teacher.daily-reports.history') }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const content = document.getElementById('studentsSummaryContent');
        const downloadBtn = document.getElementById('downloadSummaryBtn');
        const shareBtn = document.getElementById('shareSummaryBtn');
        
        if (data.success) {
            content.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ t('summary_generated_successfully') }}
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">${data.stats.total_students}</h5>
                                <p class="card-text">{{ t('total_students') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">${data.stats.total_reports}</h5>
                                <p class="card-text">{{ t('total_reports') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">${data.stats.average_grade}%</h5>
                                <p class="card-text">{{ t('average_grade') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6>{{ t('top_students') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ t('student') }}</th>
                                    <th>{{ t('reports_count') }}</th>
                                    <th>{{ t('average_grade') }}</th>
                                    <th>{{ t('total_memorization') }}</th>
                                    <th>{{ t('total_revision') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.top_students.map(student => `
                                    <tr>
                                        <td>${student.name}</td>
                                        <td>${student.reports_count}</td>
                                        <td><span class="badge bg-${student.average_grade >= 80 ? 'success' : (student.average_grade >= 60 ? 'warning' : 'danger')}">${student.average_grade}%</span></td>
                                        <td>${student.total_memorization}</td>
                                        <td>${student.total_revision}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            downloadBtn.style.display = 'inline-block';
            shareBtn.style.display = 'inline-block';
            
            // Store data for download/share
            window.summaryData = data;
        } else {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> {{ t('error_generating_summary') }}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('studentsSummaryContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> {{ t('error_generating_summary') }}
            </div>
        `;
    });
}

// Download functionality
document.getElementById('downloadSummaryBtn').addEventListener('click', function() {
    if (window.summaryData) {
        const dataStr = JSON.stringify(window.summaryData, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        
        const exportFileDefaultName = `students_summary_${new Date().toISOString().split('T')[0]}.json`;
        
        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
    }
});

// Share functionality
document.getElementById('shareSummaryBtn').addEventListener('click', function() {
    if (window.summaryData && navigator.share) {
        navigator.share({
            title: '{{ t('students_summary') }}',
            text: `{{ t('students_summary') }}: ${window.summaryData.stats.total_students} {{ t('students') }}, ${window.summaryData.stats.total_reports} {{ t('reports') }}, {{ t('average_grade') }}: ${window.summaryData.stats.average_grade}%`,
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        const summaryText = `{{ t('students_summary') }}\n{{ t('total_students') }}: ${window.summaryData.stats.total_students}\n{{ t('total_reports') }}: ${window.summaryData.stats.total_reports}\n{{ t('average_grade') }}: ${window.summaryData.stats.average_grade}%`;
        
        navigator.clipboard.writeText(summaryText).then(() => {
            alert('{{ t('summary_copied_to_clipboard') }}');
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.cursor-pointer {
    cursor: pointer;
}
</style>
@endpush
@endsection