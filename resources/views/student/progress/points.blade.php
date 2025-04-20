@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('my_points') }}</h5>
            <div>
                <a href="{{ route('student.progress.index') }}" class="btn btn-sm btn-secondary">
                    {{ t('back_to_progress') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="row mb-4">
                @forelse($totalPointsByCircle as $circlePoints)
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light h-100">
                            <div class="card-body text-center">
                                <h6>{{ $circlePoints->circle_name }}</h6>
                                <h2 class="text-primary">{{ $circlePoints->points }}</h2>
                                <p class="text-muted mb-0">{{ t('points') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            {{ t('no_points_earned_yet') }}
                        </div>
                    </div>
                @endforelse
            </div>
            
            <h5 class="mb-3">{{ t('points_history') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ t('date') }}</th>
                            <th>{{ t('circle') }}</th>
                            <th>{{ t('points') }}</th>
                            <th>{{ t('reason') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pointsHistory as $point)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($point->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $point->circle_name }}</td>
                                <td class="{{ $point->points >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $point->points >= 0 ? '+' : '' }}{{ $point->points }}
                                </td>
                                <td>{{ $point->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ t('no_points_history_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $pointsHistory->links() }}
            </div>
        </div>
    </div>
@endsection 