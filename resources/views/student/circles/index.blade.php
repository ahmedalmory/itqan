@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('my_circles') }}</h5>
            <a href="{{ route('student.circles.browse') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-search me-1"></i> {{ t('browse_circles') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            @if(isset($circles) && $circles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ t('name') }}</th>
                                <th>{{ t('department') }}</th>
                                <th>{{ t('teacher') }}</th>
                                <th>{{ t('schedule') }}</th>
                                <th>{{ t('status') }}</th>
                                <th>{{ t('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($circles as $circle)
                                <tr>
                                    <td>{{ $circle->name }}</td>
                                    <td>{{ $circle->department->name ?? t('not_available') }}</td>
                                    <td>{{ $circle->teacher->name ?? t('not_assigned') }}</td>
                                    <td>
                                        @if($circle->schedule)
                                            <small>{{ $circle->schedule }}</small>
                                        @else
                                            <span class="text-muted">{{ t('not_scheduled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($circle->is_active)
                                            <span class="badge bg-success">{{ t('active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t('inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('student.circles.show', $circle) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(isset($circles) && method_exists($circles, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $circles->links() }}
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ t('no_circles_enrolled') }}
                    <a href="{{ route('student.circles.browse') }}" class="alert-link">{{ t('browse_available_circles') }}</a>
                </div>
            @endif
        </div>
    </div>
@endsection 