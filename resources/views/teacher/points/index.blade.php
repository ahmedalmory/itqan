@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('points_management') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('manage_points') }}</h5>
            <div>
                @if($selectedCircle && $students->isNotEmpty())
                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#bulkPointsModal">
                        <i class="bi bi-people"></i> {{ t('bulk_assign_points') }}
                    </button>
                @endif
                <a href="{{ route('teacher.points.leaderboard') }}" class="btn btn-info btn-sm">
                    <i class="bi bi-trophy"></i> {{ t('leaderboard') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="{{ route('teacher.points.index') }}" method="GET" class="d-flex">
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
            </div>

            @if(!$selectedCircle)
                <div class="alert alert-info">
                    {{ t('select_circle_to_manage_points') }}
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
                                <th>{{ t('total_points') }}</th>
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
                                        <span class="badge bg-{{ $student->total_points > 0 ? 'success' : ($student->total_points < 0 ? 'danger' : 'secondary') }}">
                                            {{ $student->total_points }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#pointsModal-{{ $student->id }}">
                                            <i class="bi bi-plus-circle"></i> {{ t('add_points') }}
                                        </button>
                                        
                                        <a href="{{ route('teacher.points.history', $student->id) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="bi bi-clock-history"></i> {{ t('history') }}
                                        </a>
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
    <!-- Bulk Points Modal -->
    <div class="modal fade" id="bulkPointsModal" tabindex="-1" aria-labelledby="bulkPointsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('teacher.points.bulk-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkPointsModalLabel">
                            {{ t('bulk_assign_points_for_circle') }} {{ $selectedCircle->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulk-reason" class="form-label">{{ t('reason') }}</label>
                            <select class="form-select" id="bulk-reason" name="reason" required>
                                <option value="">{{ t('select_reason') }}</option>
                                <option value="{{ t('daily_memorization') }}">{{ t('daily_memorization') }}</option>
                                <option value="{{ t('participation') }}">{{ t('participation') }}</option>
                                <option value="{{ t('good_behavior') }}">{{ t('good_behavior') }}</option>
                                <option value="{{ t('extra_activities') }}">{{ t('extra_activities') }}</option>
                                <option value="{{ t('absence') }}">{{ t('absence') }}</option>
                                <option value="{{ t('misconduct') }}">{{ t('misconduct') }}</option>
                                <option value="{{ t('other') }}">{{ t('other') }}</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">{{ t('quick_assign') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="bulk-points-all" placeholder="{{ t('points_for_all') }}">
                                <button type="button" class="btn btn-secondary" onclick="applyPointsToAll()">{{ t('apply_to_all') }}</button>
                            </div>
                            <small class="form-text text-muted">{{ t('points_help_text') }}</small>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ t('student_name') }}</th>
                                        <th>{{ t('total_points') }}</th>
                                        <th>{{ t('points_to_add') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->total_points }}</td>
                                            <td>
                                                <input type="number" class="form-control points-input" name="points[{{ $student->id }}]" required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ t('save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Individual Points Modals -->
    @foreach($students as $student)
        <div class="modal fade" id="pointsModal-{{ $student->id }}" tabindex="-1" aria-labelledby="pointsModalLabel-{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('teacher.points.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="pointsModalLabel-{{ $student->id }}">
                                {{ t('manage_points_for') }} {{ $student->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="points_{{ $student->id }}" class="form-label">{{ t('points') }}</label>
                                <input type="number" class="form-control" id="points_{{ $student->id }}" 
                                       name="points" required>
                                <div class="form-text">{{ t('points_help_text') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reason_{{ $student->id }}" class="form-label">{{ t('reason') }}</label>
                                <select class="form-select" id="reason_{{ $student->id }}" name="reason" required>
                                    <option value="">{{ t('select_reason') }}</option>
                                    <option value="{{ t('daily_memorization') }}">{{ t('daily_memorization') }}</option>
                                    <option value="{{ t('participation') }}">{{ t('participation') }}</option>
                                    <option value="{{ t('good_behavior') }}">{{ t('good_behavior') }}</option>
                                    <option value="{{ t('extra_activities') }}">{{ t('extra_activities') }}</option>
                                    <option value="{{ t('absence') }}">{{ t('absence') }}</option>
                                    <option value="{{ t('misconduct') }}">{{ t('misconduct') }}</option>
                                    <option value="{{ t('other') }}">{{ t('other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ t('save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script>
    function applyPointsToAll() {
        const points = document.getElementById('bulk-points-all').value;
        document.querySelectorAll('.points-input').forEach(input => {
            input.value = points;
        });
    }
</script>
@endpush

@endsection 