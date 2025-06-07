@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points History for') }} {{ $student->name }}</h1>
        <a href="{{ route('admin.points.index') }}" class="btn btn-outline-secondary">
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
            <form method="GET" action="{{ route('admin.points.history', $student->id) }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select name="circle_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ t('All Circles') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ request('circle_id') == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }}
                            </option>
                        @endforeach
                    </select>
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
                                <th>{{ t('Action') }}</th>
                                <th>{{ t('Notes') }}</th>
                                <th>{{ t('Added By') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $record)
                                <tr>
                                    <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $record->circle->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->points >= 0 ? 'success' : 'danger' }}">
                                            {{ $record->points }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $record->action_type === 'add' ? 'success' : 'danger' }}">
                                            {{ t($record->action_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $record->notes }}</td>
                                    <td>{{ $record->creator->name ?? t('N/A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 