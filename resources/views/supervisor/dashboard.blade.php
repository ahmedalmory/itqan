@extends('layouts.dashboard')

@section('dashboard-content')
<div class="row">
    <div class="col-md-4 mb-4">
        <x-stat-card 
            icon="people"
            title="{{ t('supervised_circles') }}"
            value="{{ Auth::user()->supervisedCircles()->count() }}"
            description="{{ t('assigned_circles') }}"
        />
    </div>
    <div class="col-md-4 mb-4">
        <x-stat-card 
            icon="person"
            title="{{ t('teachers') }}"
            value="8"
            description="{{ t('total_teachers') }}"
        />
    </div>
    <div class="col-md-4 mb-4">
        <x-stat-card 
            icon="journal-text"
            title="{{ t('reports') }}"
            value="25" 
            description="{{ t('pending_verification') }}"
        />
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <x-card>
            <x-slot name="header">
                <h5 class="mb-0">{{ t('supervised_circles') }}</h5>
            </x-slot>
            
            <div class="list-group list-group-flush">
                @forelse (Auth::user()->supervisedCircles as $circle)
                    <a href="{{ route('supervisor.circles.show', $circle) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $circle->name }}</h5>
                            <span class="badge bg-primary rounded-pill">{{ $circle->students()->count() }} {{ t('students') }}</span>
                        </div>
                        <p class="mb-1">{{ t('teacher') }}: {{ $circle->teacher ? $circle->teacher->name : t('not_assigned') }}</p>
                        <small>{{ $circle->department->name }}</small>
                    </a>
                @empty
                    <div class="alert alert-info">
                        {{ t('no_assigned_circles') }}
                    </div>
                @endforelse
            </div>
            
            <x-slot name="footer">
                <a href="{{ route('supervisor.circles.index') }}" class="btn btn-primary">
                    <i class="bi bi-grid"></i> {{ t('view_all_circles') }}
                </a>
            </x-slot>
        </x-card>
    </div>
    
    <div class="col-md-6 mb-4">
        <x-card>
            <x-slot name="header">
                <h5 class="mb-0">{{ t('teacher_performance') }}</h5>
            </x-slot>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ t('teacher') }}</th>
                            <th>{{ t('circle') }}</th>
                            <th>{{ t('students') }}</th>
                            <th>{{ t('rating') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 5; $i++)
                            <tr>
                                <td>{{ t('teacher_name') }} {{ $i + 1 }}</td>
                                <td>{{ t('circle_name') }} {{ $i + 1 }}</td>
                                <td>{{ rand(10, 30) }}</td>
                                <td>
                                    <div class="rating">
                                        @for ($j = 0; $j < 5; $j++)
                                            @if ($j < rand(3, 5))
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>
@endsection 