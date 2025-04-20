@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('my_attendance') }}</h5>
            <div>
                <a href="{{ route('student.progress.index') }}" class="btn btn-sm btn-secondary">
                    {{ t('back_to_progress') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                {{ t('attendance_is_calculated_based_on_daily_reports') }}
            </div>
            
            <h5 class="mb-3">{{ t('monthly_attendance') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ t('month') }}</th>
                            <th>{{ t('days_attended') }}</th>
                            @if($workingDaysCount)
                                <th>{{ t('working_days') }}</th>
                                <th>{{ t('attendance_rate') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceByMonth as $attendance)
                            <tr>
                                <td>{{ date('F Y', mktime(0, 0, 0, $attendance->month, 1, $attendance->year)) }}</td>
                                <td>{{ $attendance->days_attended }}</td>
                                @if($workingDaysCount)
                                    @php
                                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $attendance->month, $attendance->year);
                                        $totalWorkingDays = 0;
                                        
                                        for ($day = 1; $day <= $daysInMonth; $day++) {
                                            $date = \Carbon\Carbon::createFromDate($attendance->year, $attendance->month, $day);
                                            $dayOfWeek = $date->dayOfWeek;
                                            
                                            // Count working days based on department settings
                                            // Sunday is 0, Monday is 1, etc.
                                            if (
                                                ($dayOfWeek == 0 && $department->work_sunday) ||
                                                ($dayOfWeek == 1 && $department->work_monday) ||
                                                ($dayOfWeek == 2 && $department->work_tuesday) ||
                                                ($dayOfWeek == 3 && $department->work_wednesday) ||
                                                ($dayOfWeek == 4 && $department->work_thursday) ||
                                                ($dayOfWeek == 5 && $department->work_friday) ||
                                                ($dayOfWeek == 6 && $department->work_saturday)
                                            ) {
                                                $totalWorkingDays++;
                                            }
                                        }
                                        
                                        $attendanceRate = $totalWorkingDays > 0 
                                            ? round(($attendance->days_attended / $totalWorkingDays) * 100, 1) 
                                            : 0;
                                    @endphp
                                    
                                    <td>{{ $totalWorkingDays }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $attendanceRate >= 75 ? 'success' : ($attendanceRate >= 50 ? 'warning' : 'danger') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ min($attendanceRate, 100) }}%;" 
                                                    aria-valuenow="{{ $attendanceRate }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <span>{{ $attendanceRate }}%</span>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $workingDaysCount ? 4 : 2 }}" class="text-center">{{ t('no_attendance_data_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 