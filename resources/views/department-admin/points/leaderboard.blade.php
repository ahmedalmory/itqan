@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points Leaderboard') }}</h1>
        <a href="{{ route('department-admin.points.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('Back to Points') }}
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

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('department-admin.points.leaderboard') }}" class="row g-3">
                <div class="col-md-4">
                    <select name="circle_id" class="form-select">
                        <option value="">{{ t('All Circles') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ request('circle_id') == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                    <a href="{{ route('department-admin.points.leaderboard') }}" class="btn btn-outline-secondary">{{ t('Clear') }}</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($leaderboard->isEmpty())
                <div class="alert alert-info">
                    {{ t('No students found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ t('Student') }}</th>
                                <th>{{ t('Circle') }}</th>
                                <th>{{ t('Total Points') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard as $index => $entry)
                                <tr>
                                    <td>{{ $leaderboard->firstItem() + $index }}</td>
                                    <td>{{ $entry->student_name }}</td>
                                    <td>{{ $entry->circle_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $entry->total_points >= 0 ? 'success' : 'danger' }}">
                                            {{ $entry->total_points }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('department-admin.points.history', $entry->student_id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-clock-history"></i> {{ t('History') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $leaderboard->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 