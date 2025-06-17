@extends('layouts.app')

@section('title', t('Rewards Catalog'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Rewards Catalog') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ t('Rewards') }}</li>
    </ol>

    <!-- Points Balance Card -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{{ t('Your Points Balance') }}</h5>
                            <h2 class="mb-0">{{ number_format(auth()->user()->total_points_balance) }}</h2>
                            <small class="opacity-75">{{ t('Available Points') }}</small>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-star fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                
                                @if(!$reward->isAvailable())
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="bi bi-x-circle me-1"></i>{{ t('Out of Stock') }}
                                    </button>
                                @elseif(auth()->user()->total_points_balance < $reward->points_cost)
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ t('Insufficient Points') }}
                                    </button>
                                @else
                                    <a href="{{ route('student.rewards.show', $reward) }}" class="btn btn-primary w-100">
                                        <i class="bi bi-eye me-1"></i>{{ t('View Details') }}
                                    </a>
                                @endif
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
            <p class="text-muted">{{ t('Check back later for new rewards!') }}</p>
        </div>
    @endif
</div>
@endsection 