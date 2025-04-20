@extends('layouts.dashboard')

@section('dashboard-content')
<div class="row">
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="people-fill"
            title="{{ t('total_users') }}"
            value="{{ App\Models\User::count() }}"
            description="{{ t('registered_users') }}"
        />
    </div>
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="person-circle"
            title="{{ t('active_students') }}"
            value="{{ App\Models\User::where('role', 'student')->where('is_active', true)->count() }}"
            description="{{ t('enrolled_students') }}"
        />
    </div>
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="people-fill"
            title="{{ t('study_circles') }}"
            value="{{ App\Models\StudyCircle::count() }}"
            description="{{ t('active_circles') }}"
        />
    </div>
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="building"
            title="{{ t('departments') }}"
            value="{{ App\Models\Department::count() }}"
            description="{{ t('total_departments') }}"
        />
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <x-card>
            <x-slot name="header">
                <h5 class="mb-0">{{ t('recent_activities') }}</h5>
            </x-slot>
            
            <ul class="list-group list-group-flush">
                @for ($i = 0; $i < 5; $i++)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-plus-fill text-primary me-2"></i>
                                <span>{{ t('new_student_registered') }}</span>
                            </div>
                            <small class="text-muted">{{ now()->subHours(rand(1, 24))->diffForHumans() }}</small>
                        </div>
                    </li>
                @endfor
            </ul>
        </x-card>
    </div>
    
    <div class="col-md-6 mb-4">
        <x-card>
            <x-slot name="header">
                <h5 class="mb-0">{{ t('upcoming_schedule') }}</h5>
            </x-slot>
            
            <ul class="list-group list-group-flush">
                @for ($i = 0; $i < 5; $i++)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                <span>{{ t('circle_session') }} #{{ $i + 1 }}</span>
                            </div>
                            <small class="text-muted">{{ now()->addDays(rand(1, 7))->format('d M Y, h:i A') }}</small>
                        </div>
                    </li>
                @endfor
            </ul>
        </x-card>
    </div>
</div>
@endsection 