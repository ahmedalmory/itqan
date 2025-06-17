@extends('layouts.app')

@section('title', t('Available Rewards'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Available Rewards') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('supervisor.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ t('Rewards') }}</li>
    </ol>

    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        {{ t('These are the rewards your students can redeem with their points.') }}
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" 
                           placeholder="{{ t('Search rewards...') }}" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="sort" class="form-select">
                        <option value="">{{ t('Sort by') }}</option>
                        <option value="points_asc" {{ request('sort') === 'points_asc' ? 'selected' : '' }}>{{ t('Points: Low to High') }}</option>
                        <option value="points_desc" {{ request('sort') === 'points_desc' ? 'selected' : '' }}>{{ t('Points: High to Low') }}</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ t('Name A-Z') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>{{ t('Search') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Rewards Grid -->
    @if($rewards->count() > 0)
        <div class="row">
            @foreach($rewards as $reward)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($reward->image)
                            <img src="{{ asset('storage/' . $reward->image) }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-gift fs-1 text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $reward->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($reward->description, 80) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary fs-6">{{ number_format($reward->points_cost) }} {{ t('points') }}</span>
                                    <small class="text-muted">
                                        {{ t('Stock') }}: {{ number_format($reward->remaining_stock) }}
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge {{ $reward->isAvailable() ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $reward->isAvailable() ? t('Available') : t('Out of Stock') }}
                                    </span>
                                    <a href="{{ route('supervisor.rewards.show', $reward) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>{{ t('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $rewards->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-gift fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">{{ t('No rewards available') }}</h5>
            <p class="text-muted">{{ t('No rewards are currently available for students.') }}</p>
        </div>
    @endif
</div>
@endsection 