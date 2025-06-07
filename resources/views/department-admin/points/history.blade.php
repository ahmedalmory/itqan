@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points History') }} - {{ $student->name }}</h1>
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

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ t('Student Information') }}
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Name') }}:</div>
                        <div class="col-sm-8">{{ $student->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">{{ t('Email') }}:</div>
                        <div class="col-sm-8">{{ $student->email }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-bold">{{ t('Total Points') }}:</div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $student->total_points >= 0 ? 'success' : 'danger' }}">
                                {{ $student->total_points }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ t('Points by Circle') }}
                </div>
                <div class="card-body">
                    @if($studentPoints->isEmpty())
                        <div class="alert alert-info mb-0">
                            {{ t('No points recorded yet') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ t('Circle') }}</th>
                                        <th>{{ t('Points') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentPoints as $points)
                                        <tr>
                                            <td>{{ $points->circle->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $points->points >= 0 ? 'success' : 'danger' }}">
                                                    {{ $points->points }}
                                                </span>
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

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('department-admin.points.history', $student->id) }}" class="row g-3">
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
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">{{ t('From') }}</span>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">{{ t('To') }}</span>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">{{ t('Filter') }}</button>
                    <a href="{{ route('department-admin.points.history', $student->id) }}" class="btn btn-outline-secondary">{{ t('Clear') }}</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($history->isEmpty())
                <div class="alert alert-info">
                    {{ t('No points history found') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Date') }}</th>
                                <th>{{ t('Circle') }}</th>
                                <th>{{ t('Points') }}</th>
                                <th>{{ t('Reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $record)
                                <tr>
                                    <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $record->circle->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->points >= 0 ? 'success' : 'danger' }}">
                                            {{ $record->points >= 0 ? '+' : '' }}{{ $record->points }}
                                        </span>
                                    </td>
                                    <td>{{ $record->reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $history->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 