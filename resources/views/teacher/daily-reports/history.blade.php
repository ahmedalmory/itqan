@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('reports_history') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('daily_reports_history') }}</h5>
            <a href="{{ route('teacher.daily-reports.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> {{ t('manage_daily_reports') }}
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('teacher.daily-reports.history') }}" method="GET" class="mb-4">
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
                                <th>{{ t('circle') }}</th>
                                <th>{{ t('memorization') }}</th>
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
                                        @if($report->circle)
                                            {{ $report->circle->name }}
                                        @else
                                            <span class="text-muted">{{ t('unknown_circle') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $report->memorization_parts }} {{ t('pages') }}
                                        ({{ $report->fromSurah->name ?? 'N/A' }} - {{ $report->toSurah->name ?? 'N/A' }})
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
@endsection