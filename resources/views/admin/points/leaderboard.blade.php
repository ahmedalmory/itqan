@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('points_leaderboard') }}</h1>
        <a href="{{ route('admin.points.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('back_to_points') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ t('leaderboard') }}</h5>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('admin.points.leaderboard') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">{{ t('filter') }}</button>
                        @if($selectedCircleId)
                            <a href="{{ route('admin.points.leaderboard') }}" class="btn btn-secondary ms-2">
                                {{ t('clear') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Leaderboard Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ t('student') }}</th>
                            <th>{{ t('circle') }}</th>
                            <th>{{ t('points') }}</th>
                            <th>{{ t('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaderboard as $index => $entry)
                            <tr>
                                <td>{{ $leaderboard->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($entry->student->avatar)
                                            <img src="{{ $entry->student->avatar }}" alt="{{ $entry->student->name }}" 
                                                 class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($entry->student->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div>{{ $entry->student->name }}</div>
                                            <small class="text-muted">{{ $entry->student->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $entry->circle->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $entry->total_points > 0 ? 'success' : ($entry->total_points < 0 ? 'danger' : 'secondary') }}">
                                        {{ $entry->total_points }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.points.history', $entry->student->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="bi bi-clock-history"></i> {{ t('history') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ t('no_points_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $leaderboard->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 