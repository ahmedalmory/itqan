@extends('layouts.app')

@section('title', $reward->name)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $reward->name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('student.rewards.index') }}">{{ t('Rewards') }}</a></li>
        <li class="breadcrumb-item active">{{ $reward->name }}</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Reward Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            @if($reward->image)
                                <img src="{{ asset('storage/' . $reward->image) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $reward->name }}"
                                     style="width: 100%; max-height: 400px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 300px;">
                                    <i class="bi bi-gift fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h2 class="mb-3">{{ $reward->name }}</h2>
                            <p class="text-muted mb-4">{{ $reward->description }}</p>
                            
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <h5 class="text-primary">
                                        <i class="bi bi-star me-1"></i>
                                        {{ number_format($reward->points_cost) }} {{ t('Points') }}
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-0">
                                        <strong>{{ t('Stock Available') }}:</strong>
                                        <span class="{{ $reward->remaining_stock > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($reward->remaining_stock) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>{{ t('Your Points Balance') }}:</strong> 
                                {{ number_format(auth()->user()->total_points_balance) }} {{ t('points') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Redemption Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gift me-1"></i>
                        {{ t('Redeem Reward') }}
                    </h5>
                </div>
                <div class="card-body">
                    @if(!$reward->isAvailable())
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle me-2"></i>
                            {{ t('This reward is currently out of stock.') }}
                        </div>
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="bi bi-x-circle me-1"></i>{{ t('Out of Stock') }}
                        </button>
                    @elseif(auth()->user()->total_points_balance < $reward->points_cost)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ t('You need :points more points to redeem this reward.', [
                                'points' => number_format($reward->points_cost - auth()->user()->total_points_balance)
                            ]) }}
                        </div>
                        <button class="btn btn-outline-secondary w-100" disabled>
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ t('Insufficient Points') }}
                        </button>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ t('You have enough points to redeem this reward!') }}
                        </div>
                        
                        <form method="POST" action="{{ route('student.rewards.redeem', $reward) }}" 
                              onsubmit="return confirm('{{ t('Are you sure you want to redeem this reward for :points points?', ['points' => number_format($reward->points_cost)]) }}')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-3">
                                <i class="bi bi-gift me-1"></i>{{ t('Redeem Now') }}
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('student.rewards.index') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-arrow-left me-1"></i>{{ t('Back to Catalog') }}
                    </a>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ t('Redemption Info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <ul class="mb-0">
                            <li>{{ t('Redemptions are processed within 1-2 business days') }}</li>
                            <li>{{ t('You will be notified when your reward is ready') }}</li>
                            <li>{{ t('Points will be deducted immediately upon redemption') }}</li>
                        </ul>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 