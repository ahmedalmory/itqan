@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points Management') }}</h1>
        <a href="{{ route('department-admin.points.leaderboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-trophy"></i> {{ t('View Leaderboard') }}
        </a>
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

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    {{ t('Bulk Points Update') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('department-admin.points.bulk-update') }}" method="POST">
                        
                        <div class="mb-3">
                            <label for="bulk_circle_id" class="form-label">{{ t('Select Circle') }}</label>
                            <select class="form-select @error('circle_id') is-invalid @enderror" id="bulk_circle_id" name="circle_id" required>
                                <option value="">{{ t('Choose a circle') }}</option>
                                @foreach($circles as $circle)
                                    <option value="{{ $circle->id }}" {{ old('circle_id') == $circle->id ? 'selected' : '' }}>
                                        {{ $circle->name }} ({{ $circle->students_count }} {{ t('students') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('circle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bulk_points" class="form-label">{{ t('Points') }}</label>
                            <input type="number" class="form-control @error('points') is-invalid @enderror" id="bulk_points" name="points" value="{{ old('points') }}" required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ t('Use negative values to subtract points') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="bulk_reason" class="form-label">{{ t('Reason') }}</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="bulk_reason" name="reason" rows="2">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-lightning-charge"></i> {{ t('Update All Students') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('department-admin.points.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <select name="circle_id" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ t('Select Circle') }}</option>
                                @foreach($circles as $circle)
                                    <option value="{{ $circle->id }}" {{ $selectedCircle && $selectedCircle->id == $circle->id ? 'selected' : '' }}>
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
                                            <td>{{ $student->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $student->total_points >= 0 ? 'success' : 'danger' }}">
                                                    {{ $student->total_points }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updatePointsModal{{ $student->id }}">
                                                        <i class="bi bi-plus-slash-minus"></i> {{ t('Update Points') }}
                                                    </button>
                                                    <a href="{{ route('department-admin.points.history', $student->id) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-clock-history"></i> {{ t('History') }}
                                                    </a>
                                                </div>

                                                <!-- Update Points Modal -->
                                                <div class="modal fade" id="updatePointsModal{{ $student->id }}" tabindex="-1" aria-labelledby="updatePointsModalLabel{{ $student->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('department-admin.points.update') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="circle_id" value="{{ $selectedCircle->id }}">
                                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                                
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updatePointsModalLabel{{ $student->id }}">
                                                                        {{ t('Update Points for') }} {{ $student->name }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="points{{ $student->id }}" class="form-label">{{ t('Points') }}</label>
                                                                        <input type="number" class="form-control" id="points{{ $student->id }}" name="points" required>
                                                                        <div class="form-text">{{ t('Use negative values to subtract points') }}</div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="reason{{ $student->id }}" class="form-label">{{ t('Reason') }}</label>
                                                                        <textarea class="form-control" id="reason{{ $student->id }}" name="reason" rows="2"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Close') }}</button>
                                                                    <button type="submit" class="btn btn-primary">{{ t('Update Points') }}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
    </div>
</div>
@endsection 