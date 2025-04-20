@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ t('points_leaderboard') }}</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('leaderboard') }}</h5>
            <a href="{{ route('teacher.points.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-left"></i> {{ t('back_to_points') }}
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('teacher.points.leaderboard') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="circle_id" class="form-label">{{ t('filter_by_circle') }}</label>
                        <div class="d-flex">
                            <select name="circle_id" id="circle_id" class="form-select me-2">
                                <option value="">{{ t('all_circles') }}</option>
                                @foreach($circles as $circle)
                                    <option value="{{ $circle->id }}" @if($selectedCircleId == $circle->id) selected @endif>
                                        {{ $circle->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">{{ t('filter') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Leaderboard Table -->
            @if($leaderboard->isEmpty())
                <div class="alert alert-info">
                    {{ t('no_students_with_points') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ t('student') }}</th>
                                <th>{{ t('circle') }}</th>
                                <th>{{ t('total_points') }}</th>
                                <th>{{ t('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard as $index => $entry)
                                <tr>
                                    <td>{{ $leaderboard->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($entry->student->profile_photo)
                                                <img src="{{ asset('storage/' . $entry->student->profile_photo) }}" 
                                                     class="rounded-circle me-2" style="width: 40px; height: 40px;" 
                                                     alt="{{ $entry->student->name }}">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; color: white;">
                                                    {{ substr($entry->student->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>{{ $entry->student->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $entry->circle->name ?? t('unknown_circle') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $entry->total_points > 0 ? 'success' : ($entry->total_points < 0 ? 'danger' : 'secondary') }}">
                                            {{ $entry->total_points }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('teacher.points.history', $entry->student_id) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="bi bi-clock-history"></i> {{ t('history') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $leaderboard->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 