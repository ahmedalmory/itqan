@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points Management') }}</h1>
        <div>
            @if($selectedCircle && $students->isNotEmpty())
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#bulkPointsModal">
                    <i class="bi bi-people"></i> {{ t('Bulk Assign Points') }}
                </button>
            @endif
            <a href="{{ route('admin.points.leaderboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-trophy"></i> {{ t('View Leaderboard') }}
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

    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            <form method="GET" action="{{ route('admin.points.index') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select name="circle_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ t('Select Circle') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ request('circle_id') == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }} ({{ $circle->students_count }} {{ t('students') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if(!$selectedCircle)
                <div class="alert alert-info">
                    {{ t('Please select a circle to manage points') }}
                </div>
            @elseif($students->isEmpty())
                <div class="alert alert-info">
                    {{ t('No students found in this circle') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Student') }}</th>
                                <th>{{ t('Total Points') }}</th>
                                <th>{{ t('Actions') }}</th>
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
                                        <button type="button" class="btn btn-primary btn-sm me-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#pointsModal-{{ $student->id }}">
                                            <i class="bi bi-plus-circle"></i> {{ t('Add Points') }}
                                        </button>
                                        <a href="{{ route('admin.points.history', $student->id) }}" 
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-clock-history"></i> {{ t('History') }}
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
                <form action="{{ route('admin.points.bulk-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkPointsModalLabel">
                            {{ t('Bulk Assign Points for Circle') }} {{ $selectedCircle->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulk-reason" class="form-label">{{ t('Reason') }}</label>
                            <input type="text" class="form-control" id="bulk-reason" name="reason" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">{{ t('Quick Assign') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="bulk-points-all" placeholder="{{ t('Points for All') }}">
                                <button type="button" class="btn btn-secondary" onclick="applyPointsToAll()">{{ t('Apply to All') }}</button>
                            </div>
                            <small class="form-text text-muted">{{ t('Use negative values to subtract points') }}</small>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ t('Student Name') }}</th>
                                        <th>{{ t('Total Points') }}</th>
                                        <th>{{ t('Points to Add') }}</th>
                                        <th>{{ t('Reason') }}</th>
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
                                            <td>
                                                <input type="text" class="form-control reason-input" name="reasons[{{ $student->id }}]" required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ t('Save Changes') }}</button>
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
                    <form action="{{ route('admin.points.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="pointsModalLabel-{{ $student->id }}">
                                {{ t('Manage Points for') }} {{ $student->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="points_{{ $student->id }}" class="form-label">{{ t('Points') }}</label>
                                <input type="number" class="form-control" id="points_{{ $student->id }}" 
                                       name="points" required>
                                <div class="form-text">{{ t('Use negative values to subtract points') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reason_{{ $student->id }}" class="form-label">{{ t('Reason') }}</label>
                                <input type="text" class="form-control" id="reason_{{ $student->id }}" name="reason" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ t('Save') }}</button>
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
        const reason = document.getElementById('bulk-reason').value;
        document.querySelectorAll('.points-input').forEach(input => {
            input.value = points;
        });
        document.querySelectorAll('.reason-input').forEach(input => {
            input.value = reason;
        });
    }
</script>
@endpush

@endsection 