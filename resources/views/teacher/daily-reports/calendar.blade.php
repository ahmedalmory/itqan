@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('daily_reports') }}</h1>
        <div class="d-flex gap-2 align-items-center">
            <!-- View Toggle Buttons -->
            <div class="btn-group me-3" role="group" aria-label="{{ t('view_options') }}">
                <input type="radio" class="btn-check" name="viewType" id="dayView" value="day">
                <label class="btn btn-outline-secondary btn-sm" for="dayView">
                    <i class="bi bi-calendar-day"></i> {{ t('day') }}
                </label>
                
                <input type="radio" class="btn-check" name="viewType" id="weekView" value="week">
                <label class="btn btn-outline-secondary btn-sm" for="weekView">
                    <i class="bi bi-calendar-week"></i> {{ t('week') }}
                </label>
                
                <input type="radio" class="btn-check" name="viewType" id="monthView" value="month" checked>
                <label class="btn btn-outline-secondary btn-sm" for="monthView">
                    <i class="bi bi-calendar-month"></i> {{ t('month') }}
                </label>
            </div>
            
            @if($selectedCircle)
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#bulkReportModal">
                    <i class="bi bi-plus-square"></i> {{ t('bulk_add_reports') }}
                </button>
            @endif
            <a href="{{ route('teacher.daily-reports.history') }}" class="btn btn-info">
                <i class="bi bi-clock-history"></i> {{ t('view_history') }}
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

    <!-- Circle Selection and Navigation -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <form method="GET" class="d-flex">
                        <select name="circle_id" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">{{ t('select_circle') }}</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" @if($selectedCircle && $selectedCircle->id == $circle->id) selected @endif>
                                    {{ $circle->name }} ({{ $circle->students_count }} {{ t('students') }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
                @if($selectedCircle)
                <div class="col-md-8">
                    <div class="d-flex justify-content-center align-items-center">
                        <form method="GET" class="d-flex align-items-center">
                            <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                            
                            <button type="submit" name="month" value="{{ $currentMonth == 1 ? 12 : $currentMonth - 1 }}" 
                                    class="btn btn-outline-secondary btn-sm me-2">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            
                            <h4 class="mb-0 mx-3">
                                {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}
                            </h4>
                            
                            <button type="submit" name="month" value="{{ $currentMonth == 12 ? 1 : $currentMonth + 1 }}" 
                                    class="btn btn-outline-secondary btn-sm ms-2">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if(!$selectedCircle)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> {{ t('select_circle_to_view_calendar') }}
        </div>
    @else
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-2"></i>
                        <h4 class="mt-2">{{ $stats['total_students'] }}</h4>
                        <p class="mb-0">{{ t('total_students') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle fs-2"></i>
                        <h4 class="mt-2">{{ $stats['attendance_percentage'] }}%</h4>
                        <p class="mb-0">{{ t('attendance_rate') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-book fs-2"></i>
                        <h4 class="mt-2">{{ $stats['total_memorization_parts'] }}</h4>
                        <p class="mb-0">{{ t('total_memorization') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-arrow-repeat fs-2"></i>
                        <h4 class="mt-2">{{ $stats['total_revision_parts'] }}</h4>
                        <p class="mb-0">{{ t('total_revision') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-trophy fs-2"></i>
                        <h4 class="mt-2">{{ $stats['average_grade'] }}%</h4>
                        <p class="mb-0">{{ t('average_grade') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-check fs-2"></i>
                        <h4 class="mt-2">{{ $stats['total_reports'] }}</h4>
                        <p class="mb-0">{{ t('total_reports') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <strong>{{ t('legend') }}:</strong>
                    </div>
                    <div class="col-md-10">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="calendar-color-sample bg-success me-2"></div>
                                <span>{{ t('memorization_and_revision') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="calendar-color-sample bg-primary me-2"></div>
                                <span>{{ t('memorization_only') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="calendar-color-sample bg-warning me-2"></div>
                                <span>{{ t('revision_only') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="calendar-color-sample bg-danger me-2"></div>
                                <span>{{ t('no_report') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="calendar-color-sample bg-secondary me-2"></div>
                                <span>{{ t('future_date') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="card">
            <div class="card-body">
                <!-- Daily View -->
                <div id="dailyCalendar" class="calendar-view d-none">
                    <div class="table-responsive">
                        <table class="table table-bordered calendar-table">
                        <thead>
                            <tr>
                                <th style="width: 200px;">{{ t('student') }}</th>
                                @php
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                                @endphp
                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $date = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day);
                                        $isWeekend = $date->isWeekend();
                                        $dayName = $date->format('D');
                                    @endphp
                                    <th class="text-center {{ $isWeekend ? 'weekend-header' : '' }}" style="min-width: 35px;">
                                        <div>{{ $day }}</div>
                                        <small class="text-muted">{{ $dayName }}</small>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendarData as $studentData)
                                <tr>
                                    <td class="student-info">
                                        <div class="d-flex align-items-center">
                                            @if($studentData['profile_photo'])
                                                <img src="{{ asset('storage/' . $studentData['profile_photo']) }}" 
                                                     class="rounded-circle me-2" style="width: 35px; height: 35px;" 
                                                     alt="{{ $studentData['name'] }}">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 35px; height: 35px; color: white; font-size: 14px;">
                                                    {{ substr($studentData['name'], 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $studentData['name'] }}</div>
                                                <small class="text-muted">
                                                    {{ t('joined') }}: {{ \Carbon\Carbon::parse($studentData['joining_date'])->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dayData = $studentData['days'][$day];
                                            $date = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day);
                                            $isFuture = $date->isFuture();
                                            $isToday = $date->isToday();
                                            $colorClass = $isFuture ? 'secondary' : $dayData['color_class'];
                                        @endphp
                                        <td class="calendar-day text-center position-relative" 
                                            data-student-id="{{ $studentData['id'] }}" 
                                            data-date="{{ $dayData['date'] }}"
                                            data-bs-toggle="tooltip" 
                                            title="{{ $studentData['name'] }} - {{ $dayData['date'] }}{{ $dayData['report'] ? ': ' . t('mem') . ': ' . $dayData['report']->memorization_parts . 'p, ' . t('rev') . ': ' . $dayData['report']->revision_parts . 'p, ' . t('grade') . ': ' . $dayData['report']->grade . '%' : ': ' . t('no_report_tooltip') }}">
                                            
                                            @if(!$isFuture)
                                                <div class="calendar-day-content bg-{{ $colorClass === 'green' ? 'success' : ($colorClass === 'blue' ? 'primary' : ($colorClass === 'yellow' ? 'warning' : 'danger')) }} {{ $dayData['report'] ? 'clickable' : '' }}"
                                                     style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin: 0 auto; cursor: {{ $dayData['report'] ? 'pointer' : 'default' }};">
                                                    {{ $day }}
                                                </div>
                                                @if($isToday)
                                                    <div class="today-indicator"></div>
                                                @endif
                                            @else
                                                <div class="calendar-day-content bg-secondary" 
                                                     style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; margin: 0 auto; opacity: 0.5;">
                                                    {{ $day }}
                                                </div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                
                <!-- Weekly View -->
                <div id="weeklyCalendar" class="calendar-view d-none">
                    <div class="table-responsive">
                        <table class="table table-bordered calendar-table">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">{{ t('student') }}</th>
                                    <th class="text-center">{{ t('monday') }}</th>
                                    <th class="text-center">{{ t('tuesday') }}</th>
                                    <th class="text-center">{{ t('wednesday') }}</th>
                                    <th class="text-center">{{ t('thursday') }}</th>
                                    <th class="text-center">{{ t('friday') }}</th>
                                    <th class="text-center weekend-header">{{ t('saturday') }}</th>
                                    <th class="text-center weekend-header">{{ t('sunday') }}</th>
                                </tr>
                            </thead>
                            <tbody id="weeklyTableBody">
                                <!-- Weekly data will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Monthly View (Default) -->
                <div id="monthlyCalendar" class="calendar-view">
                    <div class="row">
                        @foreach($calendarData as $studentData)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        @if($studentData['profile_photo'])
                                            <img src="{{ asset('storage/' . $studentData['profile_photo']) }}" 
                                                 class="rounded-circle me-2" style="width: 40px; height: 40px;" 
                                                 alt="{{ $studentData['name'] }}">
                                        @else
                                            <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; color: white; font-size: 16px;">
                                                {{ substr($studentData['name'], 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $studentData['name'] }}</h6>
                                            <small class="text-muted">{{ t('student_id') }}: {{ $studentData['id'] }}</small>
                                            <br>
                                            <small class="text-muted">{{ t('joined') }}: {{ \Carbon\Carbon::parse($studentData['joining_date'])->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-1">
                                        @php
                                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                                        @endphp
                                        @for($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $dayData = $studentData['days'][$day];
                                                $date = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day);
                                                $isFuture = $date->isFuture();
                                                $isToday = $date->isToday();
                                                $colorClass = $isFuture ? 'secondary' : $dayData['color_class'];
                                            @endphp
                                            <div class="col">
                                                <div class="calendar-day-mini text-center position-relative" 
                                                     data-student-id="{{ $studentData['id'] }}" 
                                                     data-date="{{ $dayData['date'] }}"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ $dayData['date'] }}{{ $dayData['report'] ? ': ' . t('mem') . ': ' . $dayData['report']->memorization_parts . 'p, ' . t('rev') . ': ' . $dayData['report']->revision_parts . 'p, ' . t('grade') . ': ' . $dayData['report']->grade . '%' : ': ' . t('no_report_tooltip') }}">
                                                    
                                                    @if(!$isFuture)
                                                        <div class="calendar-day-content bg-{{ $colorClass === 'green' ? 'success' : ($colorClass === 'blue' ? 'primary' : ($colorClass === 'yellow' ? 'warning' : 'danger')) }} {{ $dayData['report'] ? 'clickable' : '' }}"
                                                             style="width: 25px; height: 25px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin: 0 auto; cursor: {{ $dayData['report'] ? 'pointer' : 'default' }}; font-size: 0.75rem;">
                                                            {{ $day }}
                                                        </div>
                                                        @if($isToday)
                                                            <div class="today-indicator" style="bottom: 0px;"></div>
                                                        @endif
                                                    @else
                                                        <div class="calendar-day-content bg-secondary" 
                                                             style="width: 25px; height: 25px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; margin: 0 auto; opacity: 0.5; font-size: 0.75rem;">
                                                            {{ $day }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    
                                    <!-- Student Stats -->
                                    <div class="mt-3 pt-3 border-top">
                                        @php
                                            $totalMemorization = 0;
                                            $totalRevision = 0;
                                            $totalGrade = 0;
                                            $reportCount = 0;
                                            
                                            foreach($studentData['days'] as $dayData) {
                                                if ($dayData['report']) {
                                                    $totalMemorization += $dayData['report']->memorization_parts;
                                                    $totalRevision += $dayData['report']->revision_parts;
                                                    $totalGrade += $dayData['report']->grade;
                                                    $reportCount++;
                                                }
                                            }
                                            
                                            $avgGrade = $reportCount > 0 ? round($totalGrade / $reportCount, 1) : 0;
                                        @endphp
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="text-primary fw-bold">{{ $totalMemorization }}</div>
                                                <small class="text-muted">{{ t('memorization') }}</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-warning fw-bold">{{ $totalRevision }}</div>
                                                <small class="text-muted">{{ t('revision') }}</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-success fw-bold">{{ $avgGrade }}%</div>
                                                <small class="text-muted">{{ t('avg_grade') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Bulk Report Modal -->
@if($selectedCircle)
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
                        <input type="date" class="form-control" id="bulk_report_date" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <!-- Common Values Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('common_values') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
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
                            </div>
                            
                            <!-- Memorization Section -->
                            <h6>{{ t('memorization') }}</h6>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('from_surah') }}</label>
                                    <select id="common_memorization_from_surah" class="form-select">
                                        <option value="">{{ t('select_surah') }}</option>
                                        @foreach($surahs as $surah)
                                            <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('from_verse') }}</label>
                                    <input type="number" id="common_memorization_from_verse" class="form-control" min="1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('to_surah') }}</label>
                                    <select id="common_memorization_to_surah" class="form-select">
                                        <option value="">{{ t('select_surah') }}</option>
                                        @foreach($surahs as $surah)
                                            <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('to_verse') }}</label>
                                    <input type="number" id="common_memorization_to_verse" class="form-control" min="1">
                                </div>
                            </div>
                            
                            <!-- Revision Section -->
                            <h6>{{ t('revision') }}</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('from_surah') }}</label>
                                    <select id="common_revision_from_surah" class="form-select">
                                        <option value="">{{ t('select_surah') }}</option>
                                        @foreach($surahs as $surah)
                                            <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('from_verse') }}</label>
                                    <input type="number" id="common_revision_from_verse" class="form-control" min="1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('to_surah') }}</label>
                                    <select id="common_revision_to_surah" class="form-select">
                                        <option value="">{{ t('select_surah') }}</option>
                                        @foreach($surahs as $surah)
                                            <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ t('to_verse') }}</label>
                                    <input type="number" id="common_revision_to_verse" class="form-control" min="1">
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" id="applyToAll">
                                    <i class="bi bi-check2-all"></i> {{ t('apply_to_all_students') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive bulk-table-container">
                        <table class="table table-bordered bulk-report-table" id="bulkReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="student-col">{{ t('student') }}</th>
                                    <th class="parts-col">{{ t('memorization_parts') }}</th>
                                    <th class="parts-col">{{ t('revision_parts') }}</th>
                                    <th class="grade-col">{{ t('grade') }}</th>
                                    <th colspan="4" class="text-center bg-primary text-white">{{ t('memorization') }}</th>
                                    <th colspan="4" class="text-center bg-warning text-dark">{{ t('revision') }}</th>
                                    <th class="notes-col">{{ t('notes') }}</th>
                                    <th class="action-col"></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="surah-col bg-primary bg-opacity-10">{{ t('from_surah') }}</th>
                                    <th class="verse-col bg-primary bg-opacity-10">{{ t('from_verse') }}</th>
                                    <th class="surah-col bg-primary bg-opacity-10">{{ t('to_surah') }}</th>
                                    <th class="verse-col bg-primary bg-opacity-10">{{ t('to_verse') }}</th>
                                    <th class="surah-col bg-warning bg-opacity-25">{{ t('from_surah') }}</th>
                                    <th class="verse-col bg-warning bg-opacity-25">{{ t('from_verse') }}</th>
                                    <th class="surah-col bg-warning bg-opacity-25">{{ t('to_surah') }}</th>
                                    <th class="verse-col bg-warning bg-opacity-25">{{ t('to_verse') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="report-row">
                                    <td class="student-cell">
                                        <select name="reports[0][student_id]" class="form-select student-select" required>
                                            <option value="">{{ t('select_student') }}</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" title="{{ $student->name }}">{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="student-name-display d-none"></div>
                                    </td>
                                    <td>
                                        <input type="number" name="reports[0][memorization_parts]" class="form-control form-control-sm" min="0.25" max="30" step="0.25" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="number" name="reports[0][revision_parts]" class="form-control form-control-sm" min="0" max="30" step="0.25" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="number" name="reports[0][grade]" class="form-control form-control-sm" min="0" max="100" placeholder="0-100">
                                    </td>
                                    <td class="bg-primary bg-opacity-5">
                                        <select name="reports[0][memorization_from_surah]" class="form-select form-select-sm surah-select">
                                            <option value="">{{ t('select_surah') }}</option>
                                            @foreach($surahs as $surah)
                                                <option value="{{ $surah->id }}" title="{{ $surah->name }}">{{ $surah->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="bg-primary bg-opacity-5">
                                        <input type="number" name="reports[0][memorization_from_verse]" class="form-control form-control-sm" min="1" placeholder="1">
                                    </td>
                                    <td class="bg-primary bg-opacity-5">
                                        <select name="reports[0][memorization_to_surah]" class="form-select form-select-sm surah-select">
                                            <option value="">{{ t('select_surah') }}</option>
                                            @foreach($surahs as $surah)
                                                <option value="{{ $surah->id }}" title="{{ $surah->name }}">{{ $surah->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="bg-primary bg-opacity-5">
                                        <input type="number" name="reports[0][memorization_to_verse]" class="form-control form-control-sm" min="1" placeholder="1">
                                    </td>
                                    <td class="bg-warning bg-opacity-15">
                                        <select name="reports[0][revision_from_surah]" class="form-select form-select-sm surah-select">
                                            <option value="">{{ t('select_surah') }}</option>
                                            @foreach($surahs as $surah)
                                                <option value="{{ $surah->id }}" title="{{ $surah->name }}">{{ $surah->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="bg-warning bg-opacity-15">
                                        <input type="number" name="reports[0][revision_from_verse]" class="form-control form-control-sm" min="1" placeholder="1">
                                    </td>
                                    <td class="bg-warning bg-opacity-15">
                                        <select name="reports[0][revision_to_surah]" class="form-select form-select-sm surah-select">
                                            <option value="">{{ t('select_surah') }}</option>
                                            @foreach($surahs as $surah)
                                                <option value="{{ $surah->id }}" title="{{ $surah->name }}">{{ $surah->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="bg-warning bg-opacity-15">
                                        <input type="number" name="reports[0][revision_to_verse]" class="form-control form-control-sm" min="1" placeholder="1">
                                    </td>
                                    <td>
                                        <input type="text" name="reports[0][notes]" class="form-control form-control-sm" placeholder="{{ t('optional') }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row" title="{{ t('remove_row') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
@endif

<!-- Report Details Modal -->
<div class="modal fade" id="reportDetailsModal" tabindex="-1" aria-labelledby="reportDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportDetailsModalLabel">{{ t('report_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reportDetailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.calendar-table {
    font-size: 0.9rem;
}

.calendar-day {
    padding: 8px 4px;
    vertical-align: middle;
    height: 60px;
}

.calendar-day-content.clickable:hover {
    transform: scale(1.1);
    transition: transform 0.2s;
}

.student-info {
    background-color: #f8f9fa;
    font-weight: 500;
}

.weekend-header {
    background-color: #e9ecef;
}

.calendar-color-sample {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
}

.today-indicator {
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 6px;
    height: 6px;
    background-color: orange;
    border-radius: 50%;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-success { background-color: #28a745 !important; }
.bg-primary { background-color: #007bff !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-danger { background-color: #dc3545 !important; }
.bg-secondary { background-color: #6c757d !important; }

/* Bulk Report Table Enhancements */
.bulk-table-container {
    max-height: 70vh;
    overflow-y: auto;
}

.bulk-report-table {
    font-size: 0.875rem;
    margin-bottom: 0;
}

.bulk-report-table th {
    position: sticky;
    top: 0;
    z-index: 10;
    white-space: nowrap;
    font-weight: 600;
    border: 1px solid #dee2e6;
}

.bulk-report-table .student-col {
    min-width: 200px;
    width: 200px;
    max-width: 200px;
}

.bulk-report-table .parts-col {
    min-width: 80px;
    width: 80px;
}

.bulk-report-table .grade-col {
    min-width: 70px;
    width: 70px;
}

.bulk-report-table .surah-col {
    min-width: 140px;
    width: 140px;
}

.bulk-report-table .verse-col {
    min-width: 60px;
    width: 60px;
}

.bulk-report-table .notes-col {
    min-width: 120px;
    width: 120px;
}

.bulk-report-table .action-col {
    min-width: 50px;
    width: 50px;
}

.student-select {
    min-width: 180px;
}

.student-select option {
    padding: 8px 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.surah-select {
    min-width: 120px;
}

.surah-select option {
    padding: 6px 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.form-control-sm, .form-select-sm {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

.student-cell {
    position: relative;
}

.student-name-display {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 123, 255, 0.1);
    color: #0056b3;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
    z-index: 5;
    border: 1px solid rgba(0, 123, 255, 0.3);
}

.report-row:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.bulk-report-table tbody td {
    vertical-align: middle;
    padding: 0.5rem 0.25rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .bulk-report-table {
        font-size: 0.8rem;
    }
    
    .bulk-report-table .student-col {
        min-width: 160px;
        width: 160px;
    }
    
    .bulk-report-table .surah-col {
        min-width: 120px;
        width: 120px;
    }
}

@media (max-width: 992px) {
    .bulk-table-container {
        max-height: 60vh;
    }
    
    .bulk-report-table .student-col {
        min-width: 140px;
        width: 140px;
    }
    
    .bulk-report-table .surah-col {
        min-width: 100px;
        width: 100px;
    }
    
    .bulk-report-table .notes-col {
        min-width: 100px;
        width: 100px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Translation variables for JavaScript
const translations = {
    mem: '{{ t("mem") }}',
    rev: '{{ t("rev") }}',
    grade: '{{ t("grade") }}',
    no_report_tooltip: '{{ t("no_report_tooltip") }}'
};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Function to initialize click handlers for report details
    function initializeClickHandlers() {
        document.querySelectorAll('.calendar-day-content.clickable').forEach(function(element) {
            // Remove existing event listeners to avoid duplicates
            element.replaceWith(element.cloneNode(true));
        });
        
        // Re-select elements after cloning
        document.querySelectorAll('.calendar-day-content.clickable').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                
                const studentId = this.dataset.studentId || this.closest('.calendar-day').dataset.studentId;
                const date = this.dataset.date || this.closest('.calendar-day').dataset.date;
                
                if (studentId && date) {
                    // Load report details via AJAX
                    fetch(`{{ route('teacher.daily-reports.get-details') }}?student_id=${studentId}&date=${date}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const report = data.report;
                                const modalContent = `
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>{{ t('memorization_details') }}</h6>
                                            <p><strong>{{ t('parts') }}:</strong> ${report.memorization_parts || 0}</p>
                                            <p><strong>{{ t('from_surah') }}:</strong> ${report.memorization_from_surah || 'N/A'}</p>
                                            <p><strong>{{ t('to_surah') }}:</strong> ${report.memorization_to_surah || 'N/A'}</p>
                                            <p><strong>{{ t('verses') }}:</strong> ${report.memorization_from_verse || 'N/A'} - ${report.memorization_to_verse || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>{{ t('revision_details') }}</h6>
                                            <p><strong>{{ t('parts') }}:</strong> ${report.revision_parts || 0}</p>
                                            <p><strong>{{ t('from_surah') }}:</strong> ${report.revision_from_surah || 'N/A'}</p>
                                            <p><strong>{{ t('to_surah') }}:</strong> ${report.revision_to_surah || 'N/A'}</p>
                                            <p><strong>{{ t('verses') }}:</strong> ${report.revision_from_verse || 'N/A'} - ${report.revision_to_verse || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p><strong>{{ t('grade') }}:</strong> ${report.grade}%</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ t('date') }}:</strong> ${report.report_date}</p>
                                        </div>
                                    </div>
                                    ${report.notes ? `
                                        <div class="mt-3">
                                            <h6>{{ t('notes') }}</h6>
                                            <p class="text-muted">${report.notes}</p>
                                        </div>
                                    ` : ''}
                                `;
                                
                                document.getElementById('reportDetailsContent').innerHTML = modalContent;
                                new bootstrap.Modal(document.getElementById('reportDetailsModal')).show();
                            } else {
                                alert('{{ t('error_loading_report_details') }}');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('{{ t('error_loading_report_details') }}');
                        });
                }
            });
        });
    }
    
    // Initialize click handlers for daily view on page load
    initializeClickHandlers();
    
    // Bulk Report Modal JavaScript
    @if($selectedCircle)
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
        
        // Add hidden date input for this row
        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = `reports[${rowCount}][report_date]`;
        dateInput.value = bulkReportDate.value || '';
        newRow.appendChild(dateInput);
        
        // Setup enhanced student selection for new row
        const newStudentSelect = newRow.querySelector('.student-select');
        if (newStudentSelect) {
            setupStudentSelect(newStudentSelect);
        }
        
        // Add tooltips for new row
        addTooltips(newRow);
        
        // Add remove button functionality
        newRow.querySelector('.remove-row').addEventListener('click', function() {
            if (bulkReportTable.querySelectorAll('.report-row').length > 1) {
                this.closest('tr').remove();
            }
        });
        
        tbody.appendChild(newRow);
        rowCount++;
    }

    // Add report row button
    addReportRowBtn.addEventListener('click', function() {
        createNewRow();
    });

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
            document.getElementById('common_memorization_to_surah').value ||
            document.getElementById('common_revision_from_surah').value ||
            document.getElementById('common_revision_to_surah').value) {
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
            memorization_to_verse: document.getElementById('common_memorization_to_verse').value,
            revision_from_surah: document.getElementById('common_revision_from_surah').value,
            revision_from_verse: document.getElementById('common_revision_from_verse').value,
            revision_to_surah: document.getElementById('common_revision_to_surah').value,
            revision_to_verse: document.getElementById('common_revision_to_verse').value
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
            if (commonValues.revision_from_surah) {
                row.querySelector('[name$="[revision_from_surah]"]').value = commonValues.revision_from_surah;
            }
            if (commonValues.revision_from_verse) {
                row.querySelector('[name$="[revision_from_verse]"]').value = commonValues.revision_from_verse;
            }
            if (commonValues.revision_to_surah) {
                row.querySelector('[name$="[revision_to_surah]"]').value = commonValues.revision_to_surah;
            }
            if (commonValues.revision_to_verse) {
                row.querySelector('[name$="[revision_to_verse]"]').value = commonValues.revision_to_verse;
            }
        });
    });

    // Update report date inputs when bulk date changes
    bulkReportDate.addEventListener('change', function() {
        const rows = bulkReportTable.querySelectorAll('.report-row');
        rows.forEach(row => {
            // Add hidden input for report_date if it doesn't exist
            let dateInput = row.querySelector('input[name$="[report_date]"]');
            if (!dateInput) {
                dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = row.querySelector('select[name$="[student_id]"]').name.replace('[student_id]', '[report_date]');
                row.appendChild(dateInput);
            }
            dateInput.value = this.value;
        });
    });

    // Initial setup for remove buttons
    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function() {
            if (bulkReportTable.querySelectorAll('.report-row').length > 1) {
                this.closest('tr').remove();
            }
        });
    });

    // Enhanced student selection with better UX
    function setupStudentSelect(selectElement) {
        selectElement.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const studentCell = this.closest('.student-cell');
            const nameDisplay = studentCell.querySelector('.student-name-display');
            
            if (this.value && selectedOption.text !== '{{ t('select_student') }}') {
                // Show selected student name
                nameDisplay.textContent = selectedOption.text;
                nameDisplay.classList.remove('d-none');
                this.style.opacity = '0.7';
            } else {
                // Hide name display if no student selected
                nameDisplay.classList.add('d-none');
                this.style.opacity = '1';
            }
        });

        // Add hover effects for better visibility
        selectElement.addEventListener('focus', function() {
            this.style.opacity = '1';
            const nameDisplay = this.closest('.student-cell').querySelector('.student-name-display');
            nameDisplay.classList.add('d-none');
        });

        selectElement.addEventListener('blur', function() {
            if (this.value) {
                this.style.opacity = '0.7';
                const nameDisplay = this.closest('.student-cell').querySelector('.student-name-display');
                nameDisplay.classList.remove('d-none');
            }
        });
    }

    // Setup initial student select
    document.querySelectorAll('.student-select').forEach(setupStudentSelect);

    // Add tooltips for better UX
    function addTooltips(row) {
        const studentSelect = row.querySelector('.student-select');
        const surahSelects = row.querySelectorAll('.surah-select');
        
        if (studentSelect) {
            studentSelect.title = 'Click to select a student';
        }
        
        surahSelects.forEach(select => {
            select.title = 'Click to select a surah';
        });
    }

    // Set initial date for the first row
    const firstRow = bulkReportTable.querySelector('.report-row');
    if (firstRow) {
        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'reports[0][report_date]';
        dateInput.value = '';
        firstRow.appendChild(dateInput);
        
        // Setup tooltips for first row
        addTooltips(firstRow);
        
        // Setup student select for first row
        setupStudentSelect(firstRow.querySelector('.student-select'));
    }
    
    // Trigger change event to populate dates
    bulkReportDate.dispatchEvent(new Event('change'));
    @endif
    
    // View switching functionality
    const viewButtons = document.querySelectorAll('input[name="viewType"]');
    const calendarViews = document.querySelectorAll('.calendar-view');
    
    viewButtons.forEach(button => {
        button.addEventListener('change', function() {
            // Hide all views
            calendarViews.forEach(view => view.classList.add('d-none'));
            
            // Show selected view
            const selectedView = this.value;
            if (selectedView === 'day') {
                document.getElementById('dailyCalendar').classList.remove('d-none');
                // Reinitialize click handlers for daily view
                initializeClickHandlers();
            } else if (selectedView === 'week') {
                document.getElementById('weeklyCalendar').classList.remove('d-none');
                populateWeeklyView();
            } else if (selectedView === 'month') {
                document.getElementById('monthlyCalendar').classList.remove('d-none');
                // Reinitialize click handlers for monthly view
                initializeClickHandlers();
            }
        });
    });
    
    // Function to populate weekly view
    function populateWeeklyView() {
        const weeklyTableBody = document.getElementById('weeklyTableBody');
        if (!weeklyTableBody) return;
        
        // Get current week (assuming we want to show current week)
        const today = new Date();
        const currentWeekStart = new Date(today);
        currentWeekStart.setDate(today.getDate() - today.getDay() + 1); // Monday
        
        // Clear existing content
        weeklyTableBody.innerHTML = '';
        
        @if($selectedCircle && isset($calendarData))
        const calendarData = @json($calendarData);
        
        calendarData.forEach(studentData => {
            const row = document.createElement('tr');
            
            // Student info cell
            const studentCell = document.createElement('td');
            studentCell.className = 'student-info';
            studentCell.innerHTML = `
                <div class="d-flex align-items-center">
                    ${studentData.profile_photo ? 
                        `<img src="{{ asset('storage/') }}/${studentData.profile_photo}" class="rounded-circle me-2" style="width: 35px; height: 35px;" alt="${studentData.name}">` :
                        `<div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; color: white; font-size: 14px;">${studentData.name.charAt(0)}</div>`
                    }
                    <div>
                        <div class="fw-bold">${studentData.name}</div>
                        <small class="text-muted">{{ t('joined') }}: ${new Date(studentData.joining_date).toLocaleDateString()}</small>
                    </div>
                </div>
            `;
            row.appendChild(studentCell);
            
            // Add cells for each day of the week
            for (let dayOffset = 0; dayOffset < 7; dayOffset++) {
                const currentDate = new Date(currentWeekStart);
                currentDate.setDate(currentWeekStart.getDate() + dayOffset);
                
                const dayCell = document.createElement('td');
                dayCell.className = 'calendar-day text-center position-relative';
                
                const dateStr = currentDate.toISOString().split('T')[0];
                const dayData = Object.values(studentData.days).find(day => day.date === dateStr);
                
                if (dayData) {
                    const isFuture = currentDate > new Date();
                    const isToday = currentDate.toDateString() === new Date().toDateString();
                    const colorClass = isFuture ? 'secondary' : dayData.color_class;
                    
                    let bgColorClass = 'secondary';
                    if (!isFuture) {
                        switch(colorClass) {
                            case 'green': bgColorClass = 'success'; break;
                            case 'blue': bgColorClass = 'primary'; break;
                            case 'yellow': bgColorClass = 'warning'; break;
                            case 'red': bgColorClass = 'danger'; break;
                        }
                    }
                    
                    dayCell.innerHTML = `
                        <div class="calendar-day-content bg-${bgColorClass} ${dayData.report ? 'clickable' : ''}"
                             style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin: 0 auto; cursor: ${dayData.report ? 'pointer' : 'default'};"
                             data-student-id="${studentData.id}" 
                             data-date="${dateStr}">
                            ${currentDate.getDate()}
                        </div>
                        ${isToday ? '<div class="today-indicator"></div>' : ''}
                    `;
                    
                    if (dayData.report) {
                        dayCell.setAttribute('data-bs-toggle', 'tooltip');
                        dayCell.setAttribute('title', `${studentData.name} - ${dateStr}: ${translations.mem}: ${dayData.report.memorization_parts}p, ${translations.rev}: ${dayData.report.revision_parts}p, ${translations.grade}: ${dayData.report.grade}%`);
                    }
                } else {
                    dayCell.innerHTML = `
                        <div class="calendar-day-content bg-light text-dark"
                             style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; opacity: 0.5;">
                            ${currentDate.getDate()}
                        </div>
                    `;
                }
                
                row.appendChild(dayCell);
            }
            
            weeklyTableBody.appendChild(row);
        });
        
        // Re-initialize tooltips and click handlers for weekly view
        initializeTooltips();
        initializeClickHandlers();
        @endif
    }
    
    // Function to initialize tooltips
    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            // Dispose existing tooltip if any
            const existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
            if (existingTooltip) {
                existingTooltip.dispose();
            }
            // Create new tooltip
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    

});
</script>
@endpush 