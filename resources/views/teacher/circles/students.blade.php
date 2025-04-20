@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $circle->name }} - {{ t('students') }}</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ t('students_list') }}</h5>
            <div>
                <a href="{{ route('teacher.circles.show', $circle->id) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> {{ t('back_to_circle') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="alert alert-info">
                    {{ t('no_students_in_this_circle') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('student') }}</th>
                                <th>{{ t('email') }}</th>
                                <th>{{ t('joined_date') }}</th>
                                <th>{{ t('points') }}</th>
                                <th>{{ t('recent_reports') }}</th>
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
                                            <div>{{ $student->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if(isset($student->pivot->created_at))
                                            {{ $student->pivot->created_at->format('Y-m-d') }}
                                        @else
                                            {{ t('unknown') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($student->studentPoints))
                                            <span class="badge bg-{{ $student->studentPoints->first() && $student->studentPoints->first()->points > 0 ? 'success' : 'secondary' }}">
                                                {{ $student->studentPoints->first() ? $student->studentPoints->first()->points : 0 }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($student->dailyReports) && $student->dailyReports->count() > 0)
                                            <span class="badge bg-success">{{ $student->dailyReports->count() }}</span>
                                            <small class="text-muted">{{ t('last') }}: {{ $student->dailyReports->sortByDesc('report_date')->first()->report_date }}</small>
                                        @else
                                            <span class="badge bg-warning">{{ t('no_reports') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher.daily-reports.index', ['circle_id' => $circle->id, 'student_id' => $student->id]) }}" 
                                               class="btn btn-success btn-sm">
                                                <i class="bi bi-journal-check"></i> {{ t('reports') }}
                                            </a>
                                            <a href="{{ route('teacher.points.history', $student->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="bi bi-trophy"></i> {{ t('points') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection