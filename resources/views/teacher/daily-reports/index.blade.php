@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('daily_reports') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('manage_daily_reports') }}</h5>
            <div>
                <a href="{{ route('teacher.daily-reports.history') }}" class="btn btn-info btn-sm me-2">
                    <i class="bi bi-clock-history"></i> {{ t('view_history') }}
                </a>
                @if($selectedCircle)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkReportModal">
                        <i class="bi bi-plus-circle"></i> {{ t('bulk_add_reports') }}
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="{{ route('teacher.daily-reports.index') }}" method="GET" class="d-flex">
                        <select name="circle_id" class="form-select me-2">
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" @if($selectedCircle && $selectedCircle->id == $circle->id) selected @endif>
                                    {{ $circle->name }} ({{ $circle->students_count }} {{ t('students') }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">{{ t('select') }}</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('teacher.daily-reports.index') }}" method="GET" class="d-flex">
                        @if($selectedCircle)
                            <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                        @endif
                        <input type="date" name="date" class="form-control me-2" value="{{ $date }}">
                        <button type="submit" class="btn btn-primary">{{ t('filter') }}</button>
                    </form>
                </div>
            </div>

            @if(!$selectedCircle)
                <div class="alert alert-info">
                    {{ t('select_circle_to_manage_reports') }}
                </div>
            @elseif($students->isEmpty())
                <div class="alert alert-warning">
                    {{ t('no_students_in_selected_circle') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('student') }}</th>
                                <th>{{ t('status') }}</th>
                                <th>{{ t('report_details') }}</th>
                                <th>{{ t('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($student->profile_photo)
                                                <img src="{{ asset('storage/' . $student->profile_photo) }}" 
                                                     class="rounded-circle me-2" style="width: 40px; height: 40px;" 
                                                     alt="{{ $student->name }}">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; color: white;">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div>{{ $student->name }}</div>
                                                <small class="text-muted">{{ $student->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($student->dailyReports->count() > 0)
                                            <span class="badge bg-success">{{ t('report_submitted') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ t('no_report') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->dailyReports->count() > 0)
                                            @php $report = $student->dailyReports->first(); @endphp
                                            <div><strong>{{ t('memorization') }}:</strong> {{ $report->memorization_parts }} {{ t('pages') }}</div>
                                            <div><strong>{{ t('grade') }}:</strong> {{ $report->grade }}%</div>
                                        @else
                                            <span class="text-muted">{{ t('no_report_details') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reportModal-{{ $student->id }}">
                                            @if($student->dailyReports->count() > 0)
                                                <i class="bi bi-pencil"></i> {{ t('edit') }}
                                            @else
                                                <i class="bi bi-plus-circle"></i> {{ t('add') }}
                                            @endif
                                        </button>
                                        
                                        @if($student->dailyReports->count() > 0)
                                            <form action="{{ route('teacher.daily-reports.destroy', $student->dailyReports->first()->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('{{ t('confirm_delete_report') }}')">
                                                    <i class="bi bi-trash"></i> {{ t('delete') }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@if($selectedCircle && $students->isNotEmpty())
    <!-- Individual Report Modals -->
    @foreach($students as $student)
        <div class="modal fade" id="reportModal-{{ $student->id }}" tabindex="-1" aria-labelledby="reportModalLabel-{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('teacher.daily-reports.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="report_date" value="{{ $date }}">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="reportModalLabel-{{ $student->id }}">
                                {{ t('daily_report_for') }} {{ $student->name }} - {{ $date }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php 
                                $report = $student->dailyReports->first();
                            @endphp
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="memorization_from_surah_{{ $student->id }}" class="form-label">{{ t('from_surah') }}</label>
                                    <select name="memorization_from_surah" id="memorization_from_surah_{{ $student->id }}" class="form-select" required>
                                        @foreach($surahs as $surah)
                                            <option value="{{ $surah->id }}" @if($report && $report->memorization_from_surah == $surah->id) selected @endif>
                                                {{ $surah->id }}. {{ $surah->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="memorization_from_verse_{{ $student->id }}" class="form-label">{{ t('from_verse') }}</label>
                                    <input type="number" class="form-control" id="memorization_from_verse_{{ $student->id }}" 
                                           name="memorization_from_verse" min="1" required
                                           value="{{ $report ? $report->memorization_from_verse : 1 }}">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="memorization_to_surah_{{ $student->id }}" class="form-label">{{ t('to_surah') }}</label>
                                    <select name="memorization_to_surah" id="memorization_to_surah_{{ $student->id }}" class="form-select" required>
                                        @foreach($surahs as $surah)
                                            <option value="{{ $surah->id }}" @if($report && $report->memorization_to_surah == $surah->id) selected @endif>
                                                {{ $surah->id }}. {{ $surah->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="memorization_to_verse_{{ $student->id }}" class="form-label">{{ t('to_verse') }}</label>
                                    <input type="number" class="form-control" id="memorization_to_verse_{{ $student->id }}" 
                                           name="memorization_to_verse" min="1" required
                                           value="{{ $report ? $report->memorization_to_verse : 1 }}">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="memorization_parts_{{ $student->id }}" class="form-label">{{ t('memorization_parts') }} ({{ t('pages') }})</label>
                                    <input type="number" class="form-control" id="memorization_parts_{{ $student->id }}" 
                                           name="memorization_parts" min="0.25" step="0.25" required
                                           value="{{ $report ? $report->memorization_parts : 1 }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="grade_{{ $student->id }}" class="form-label">{{ t('grade') }} (%)</label>
                                    <input type="number" class="form-control" id="grade_{{ $student->id }}" 
                                           name="grade" min="0" max="100" required
                                           value="{{ $report ? $report->grade : 80 }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes_{{ $student->id }}" class="form-label">{{ t('notes') }}</label>
                                <textarea class="form-control" id="notes_{{ $student->id }}" name="notes" rows="3">{{ $report ? $report->notes : '' }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ t('save_report') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Bulk Report Modal -->
    <div class="modal fade" id="bulkReportModal" tabindex="-1" aria-labelledby="bulkReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkReportModalLabel">{{ t('bulk_add_reports_for_circle', ['circle' => $selectedCircle->name]) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('teacher.daily-reports.bulk-store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ t('report_date') }}</label>
                            <input type="date" class="form-control" id="bulk_report_date" value="{{ $date }}" required>
                        </div>

                        <!-- Common Values Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ t('common_values') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">{{ t('memorization_parts') }}</label>
                                        <input type="number" id="common_memorization_parts" class="form-control" min="0.25" max="30" step="0.25">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">{{ t('revision_parts') }}</label>
                                        <input type="number" id="common_revision_parts" class="form-control" min="0" max="30" step="0.25">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">{{ t('grade') }}</label>
                                        <input type="number" id="common_grade" class="form-control" min="0" max="100">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">{{ t('from_surah') }}</label>
                                        <select id="common_memorization_from_surah" class="form-select">
                                            <option value="">{{ t('select_surah') }}</option>
                                            @foreach($surahs as $surah)
                                                <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">{{ t('from_verse') }}</label>
                                        <input type="number" id="common_memorization_from_verse" class="form-control" min="1">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">{{ t('to_surah') }}</label>
                                        <select id="common_memorization_to_surah" class="form-select">
                                            <option value="">{{ t('select_surah') }}</option>
                                            @foreach($surahs as $surah)
                                                <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">{{ t('to_verse') }}</label>
                                        <input type="number" id="common_memorization_to_verse" class="form-control" min="1">
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-primary" id="applyToAll">
                                        <i class="bi bi-check2-all"></i> {{ t('apply_to_all_students') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="bulkReportTable">
                                <thead>
                                    <tr>
                                        <th>{{ t('student') }}</th>
                                        <th>{{ t('memorization_parts') }}</th>
                                        <th>{{ t('revision_parts') }}</th>
                                        <th>{{ t('grade') }}</th>
                                        <th>{{ t('from_surah') }}</th>
                                        <th>{{ t('from_verse') }}</th>
                                        <th>{{ t('to_surah') }}</th>
                                        <th>{{ t('to_verse') }}</th>
                                        <th>{{ t('notes') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="report-row">
                                        <td>
                                            <select name="reports[0][student_id]" class="form-select" required>
                                                <option value="">{{ t('select_student') }}</option>
                                                @foreach($students as $student)
                                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="reports[0][memorization_parts]" class="form-control" min="0.25" max="30" step="0.25">
                                        </td>
                                        <td>
                                            <input type="number" name="reports[0][revision_parts]" class="form-control" min="0" max="30" step="0.25">
                                        </td>
                                        <td>
                                            <input type="number" name="reports[0][grade]" class="form-control" min="0" max="100">
                                        </td>
                                        <td>
                                            <select name="reports[0][memorization_from_surah]" class="form-select">
                                                <option value="">{{ t('select_surah') }}</option>
                                                @foreach($surahs as $surah)
                                                    <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="reports[0][memorization_from_verse]" class="form-control" min="1">
                                        </td>
                                        <td>
                                            <select name="reports[0][memorization_to_surah]" class="form-select">
                                                <option value="">{{ t('select_surah') }}</option>
                                                @foreach($surahs as $surah)
                                                    <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="reports[0][memorization_to_verse]" class="form-control" min="1">
                                        </td>
                                        <td>
                                            <input type="text" name="reports[0][notes]" class="form-control">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-success me-2" id="addReportRow">
                                <i class="bi bi-plus-circle"></i> {{ t('add_another_student') }}
                            </button>
                            <button type="button" class="btn btn-primary" id="addAllStudents">
                                <i class="bi bi-people-fill"></i> {{ t('add_all_students') }}
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ t('save_reports') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bulkReportTable = document.getElementById('bulkReportTable');
            const addReportRowBtn = document.getElementById('addReportRow');
            const addAllStudentsBtn = document.getElementById('addAllStudents');
            const bulkReportDate = document.getElementById('bulk_report_date');
            const applyToAllBtn = document.getElementById('applyToAll');
            let rowCount = 1;

            // Function to create a new row
            function createNewRow(studentId = '', studentName = '') {
                const tbody = bulkReportTable.querySelector('tbody');
                const firstRow = tbody.querySelector('.report-row');
                const newRow = firstRow.cloneNode(true);
                
                // Update input names with new index
                newRow.querySelectorAll('[name]').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${rowCount}]`);
                    input.value = ''; // Clear values
                });

                // If student is provided, select them in the dropdown
                if (studentId && studentName) {
                    const studentSelect = newRow.querySelector('select[name$="[student_id]"]');
                    const option = Array.from(studentSelect.options).find(opt => opt.value === studentId);
                    if (option) {
                        option.selected = true;
                    }
                }
                
                // Add remove button functionality
                newRow.querySelector('.remove-row').addEventListener('click', function() {
                    if (bulkReportTable.querySelectorAll('.report-row').length > 1) {
                        this.closest('tr').remove();
                    }
                });
                
                tbody.appendChild(newRow);
                rowCount++;
            }

            // Add all students at once
            addAllStudentsBtn.addEventListener('click', function() {
                // Clear existing rows except the first one
                const tbody = bulkReportTable.querySelector('tbody');
                const rows = tbody.querySelectorAll('.report-row');
                Array.from(rows).slice(1).forEach(row => row.remove());

                // Get all students from the first row's select element
                const studentSelect = tbody.querySelector('select[name$="[student_id]"]');
                const students = Array.from(studentSelect.options)
                    .filter(opt => opt.value !== ''); // Skip the placeholder option

                // Select the first student in the first row
                if (students.length > 0) {
                    studentSelect.value = students[0].value;
                }

                // Add rows for remaining students
                students.slice(1).forEach(student => {
                    createNewRow(student.value, student.text);
                });

                // Apply common values if any are set
                if (document.getElementById('common_memorization_parts').value ||
                    document.getElementById('common_revision_parts').value ||
                    document.getElementById('common_grade').value ||
                    document.getElementById('common_memorization_from_surah').value ||
                    document.getElementById('common_memorization_to_surah').value) {
                    applyToAllBtn.click();
                }
            });

            // Apply common values to all rows
            applyToAllBtn.addEventListener('click', function() {
                const rows = bulkReportTable.querySelectorAll('.report-row');
                const commonValues = {
                    memorization_parts: document.getElementById('common_memorization_parts').value,
                    revision_parts: document.getElementById('common_revision_parts').value,
                    grade: document.getElementById('common_grade').value,
                    memorization_from_surah: document.getElementById('common_memorization_from_surah').value,
                    memorization_from_verse: document.getElementById('common_memorization_from_verse').value,
                    memorization_to_surah: document.getElementById('common_memorization_to_surah').value,
                    memorization_to_verse: document.getElementById('common_memorization_to_verse').value
                };

                rows.forEach(row => {
                    if (commonValues.memorization_parts) {
                        row.querySelector('[name$="[memorization_parts]"]').value = commonValues.memorization_parts;
                    }
                    if (commonValues.revision_parts) {
                        row.querySelector('[name$="[revision_parts]"]').value = commonValues.revision_parts;
                    }
                    if (commonValues.grade) {
                        row.querySelector('[name$="[grade]"]').value = commonValues.grade;
                    }
                    if (commonValues.memorization_from_surah) {
                        row.querySelector('[name$="[memorization_from_surah]"]').value = commonValues.memorization_from_surah;
                    }
                    if (commonValues.memorization_from_verse) {
                        row.querySelector('[name$="[memorization_from_verse]"]').value = commonValues.memorization_from_verse;
                    }
                    if (commonValues.memorization_to_surah) {
                        row.querySelector('[name$="[memorization_to_surah]"]').value = commonValues.memorization_to_surah;
                    }
                    if (commonValues.memorization_to_verse) {
                        row.querySelector('[name$="[memorization_to_verse]"]').value = commonValues.memorization_to_verse;
                    }
                });
            });

            addReportRowBtn.addEventListener('click', function() {
                createNewRow();
            });

            // Add remove functionality to first row
            document.querySelector('.remove-row').addEventListener('click', function() {
                if (bulkReportTable.querySelectorAll('.report-row').length > 1) {
                    this.closest('tr').remove();
                }
            });

            // Update all report dates before submit
            document.querySelector('#bulkReportModal form').addEventListener('submit', function(e) {
                const reportDate = bulkReportDate.value;
                const rows = bulkReportTable.querySelectorAll('.report-row');
                
                rows.forEach((row, index) => {
                    const dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = `reports[${index}][report_date]`;
                    dateInput.value = reportDate;
                    row.appendChild(dateInput);
                });
            });
        });
    </script>
    @endpush
@endif
@endsection