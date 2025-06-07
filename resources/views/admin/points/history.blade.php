@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('points_history_for') }} {{ $student->name }}</h1>
        <a href="{{ route('admin.points.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('back_to_points') }}
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ t('points_history') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('admin.points.history', $student->id) }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="circle_id" class="form-label">{{ t('circle') }}</label>
                                <select name="circle_id" id="circle_id" class="form-select">
                                    <option value="">{{ t('all_circles') }}</option>
                                    @foreach($circles as $circle)
                                        <option value="{{ $circle->id }}" @if($selectedCircleId == $circle->id) selected @endif>
                                            {{ $circle->name }}
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
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">{{ t('filter') }}</button>
                                @if(!empty(array_filter($filters)))
                                    <a href="{{ route('admin.points.history', $student->id) }}" class="btn btn-secondary ms-2">
                                        {{ t('clear') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Points Summary -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title">{{ t('points_summary') }}</h6>
                                    <div class="row">
                                        @foreach($studentPoints as $circleId => $points)
                                            <div class="col-md-3 mb-2">
                                                <div class="fw-bold">{{ $points->circle->name ?? 'Unknown' }}</div>
                                                <div>
                                                    <span class="badge bg-{{ $points->total_points > 0 ? 'success' : ($points->total_points < 0 ? 'danger' : 'secondary') }}">
                                                        {{ $points->total_points }} {{ t('points') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- History Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ t('date') }}</th>
                                    <th>{{ t('circle') }}</th>
                                    <th>{{ t('points') }}</th>
                                    <th>{{ t('reason') }}</th>
                                    <th>{{ t('added_by') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $record)
                                    <tr>
                                        <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $record->circle->name ?? 'Unknown' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $record->points > 0 ? 'success' : 'danger' }}">
                                                {{ $record->points > 0 ? '+' : '' }}{{ $record->points }}
                                            </span>
                                        </td>
                                        <td>{{ $record->notes }}</td>
                                        <td>{{ $record->creator->name ?? 'Unknown' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ t('no_points_history') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $history->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 