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
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="gift"
            title="{{ t('Active Rewards') }}"
            value="{{ App\Models\Reward::where('is_active', true)->count() }}"
            description="{{ t('Available rewards') }}"
        />
    </div>
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="award"
            title="{{ t('Total Redemptions') }}"
            value="{{ App\Models\RewardRedemption::count() }}"
            description="{{ t('Rewards redeemed') }}"
        />
    </div>
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="clock"
            title="{{ t('Pending Redemptions') }}"
            value="{{ App\Models\RewardRedemption::where('status', 'pending')->count() }}"
            description="{{ t('Awaiting approval') }}"
        />
    </div>
    <div class="col-md-3 mb-4">
        <x-stat-card 
            icon="star"
            title="{{ t('Total Points Spent') }}"
            value="{{ number_format(App\Models\RewardRedemption::sum('points_spent')) }}"
            description="{{ t('Points redeemed') }}"
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
                <h5 class="mb-0">{{ t('Rewards Management') }}</h5>
            </x-slot>
            
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.rewards.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-gift me-2"></i>{{ t('Manage Rewards') }}
                    </a>
                    <a href="{{ route('admin.rewards.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-2"></i>{{ t('Add New Reward') }}
                    </a>
                    <a href="{{ route('admin.reward-redemptions.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-clock-history me-2"></i>{{ t('Pending Redemptions') }}
                        @if(App\Models\RewardRedemption::where('status', 'pending')->count() > 0)
                            <span class="badge bg-danger ms-1">{{ App\Models\RewardRedemption::where('status', 'pending')->count() }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection 