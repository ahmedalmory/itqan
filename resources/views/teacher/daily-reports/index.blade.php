@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('daily_reports') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('manage_daily_reports') }}</h5>
            <a href="{{ route('teacher.daily-reports.history') }}" class="btn btn-info btn-sm">
                <i class="bi bi-clock-history"></i> {{ t('view_history') }}
            </a>
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
    @foreach($students as $student)
        <!-- Report Modal for each student -->
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
@endif
@endsection