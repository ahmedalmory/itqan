@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('points_history') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('points_history_for') }} {{ $student->name }}</h5>
            <div>
                <a href="{{ route('teacher.points.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> {{ t('back_to_points') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Student Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        @if($student->profile_photo)
                            <img src="{{ asset('storage/' . $student->profile_photo) }}" 
                                 class="rounded-circle me-3" style="width: 60px; height: 60px;" 
                                 alt="{{ $student->name }}">
                        @else
                            <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; color: white;">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $student->name }}</h5>
                            <div class="text-muted">{{ $student->email }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body p-3">
                            <h6 class="card-title">{{ t('points_summary') }}</h6>
                            <div class="row text-center">
                                @foreach($studentPoints as $circleId => $points)
                                    <div class="col-md-6 mb-2">
                                        <div class="fw-bold">{{ $points->circle->name ?? 'Unknown' }}</div>
                                        <div>
                                            <span class="badge bg-{{ $points->points > 0 ? 'success' : ($points->points < 0 ? 'danger' : 'secondary') }} me-1">
                                                {{ $points->points }} {{ t('current') }}
                                            </span>
                                            <span class="badge bg-info">
                                                {{ $points->total_points }} {{ t('total') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('teacher.points.history', $student->id) }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">{{ t('from_date') }}</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                               value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">{{ t('to_date') }}</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                               value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> {{ t('filter') }}
                        </button>
                        <a href="{{ route('teacher.points.history', $student->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> {{ t('clear_filters') }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Points History Table -->
            @if($history->isEmpty())
                <div class="alert alert-info">
                    {{ t('no_points_history_found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('date') }}</th>
                                <th>{{ t('circle') }}</th>
                                <th>{{ t('points') }}</th>
                                <th>{{ t('reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $record)
                                <tr>
                                    <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $record->circle->name ?? t('unknown_circle') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->points > 0 ? 'success' : ($record->points < 0 ? 'danger' : 'secondary') }}">
                                            {{ $record->points > 0 ? '+' : '' }}{{ $record->points }}
                                        </span>
                                    </td>
                                    <td>{{ $record->notes }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $history->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 