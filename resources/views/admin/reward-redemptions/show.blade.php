@extends('layouts.app')

@section('title', t('Redemption Details'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Redemption Details') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reward-redemptions.index') }}">{{ t('Redemptions') }}</a></li>
        <li class="breadcrumb-item active">#{{ $redemption->id }}</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Redemption Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-receipt me-1"></i>
                    {{ t('Redemption Information') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ t('Student Information') }}</h5>
                            <p><strong>{{ t('Name') }}:</strong> {{ $redemption->student->name }}</p>
                            <p><strong>{{ t('Email') }}:</strong> {{ $redemption->student->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ t('Redemption Details') }}</h5>
                            <p><strong>{{ t('Points Spent') }}:</strong> {{ number_format($redemption->points_spent) }}</p>
                            <p><strong>{{ t('Status') }}:</strong> 
                                <span class="badge {{ 
                                    $redemption->status === 'pending' ? 'bg-warning' : 
                                    ($redemption->status === 'approved' ? 'bg-info' : 
                                    ($redemption->status === 'delivered' ? 'bg-success' : 'bg-danger')) 
                                }}">
                                    @if($redemption->status === 'pending'){{ t('Pending') }}
                                    @elseif($redemption->status === 'approved'){{ t('Approved') }}
                                    @elseif($redemption->status === 'delivered'){{ t('Delivered') }}
                                    @else{{ t('Cancelled') }}@endif
                                </span>
                            </p>
                            <p><strong>{{ t('Redeemed At') }}:</strong> {{ $redemption->redeemed_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reward Information -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-gift me-1"></i>
                    {{ t('Reward Information') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($redemption->reward->image)
                                <img src="{{ asset('storage/' . $redemption->reward->image) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $redemption->reward->name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 200px;">
                                    <i class="bi bi-gift fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $redemption->reward->name }}</h4>
                            <p class="text-muted">{{ $redemption->reward->description }}</p>
                            <p><strong>{{ t('Points Cost') }}:</strong> {{ number_format($redemption->reward->points_cost) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($redemption->notes)
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-sticky-note me-1"></i>
                        {{ t('Notes') }}
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $redemption->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-tools me-1"></i>
                    {{ t('Actions') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reward-redemptions.update-status', $redemption) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ t('Update Status') }}</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $redemption->status === 'pending' ? 'selected' : '' }}>{{ t('Pending') }}</option>
                                <option value="approved" {{ $redemption->status === 'approved' ? 'selected' : '' }}>{{ t('Approved') }}</option>
                                <option value="delivered" {{ $redemption->status === 'delivered' ? 'selected' : '' }}>{{ t('Delivered') }}</option>
                                <option value="cancelled" {{ $redemption->status === 'cancelled' ? 'selected' : '' }}>{{ t('Cancelled') }}</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ t('Notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="{{ t('Add any notes...') }}">{{ $redemption->notes }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-check me-1"></i>{{ t('Update Status') }}
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.reward-redemptions.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-1"></i>{{ t('Back to List') }}
                    </a>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-history me-1"></i>
                    {{ t('Status Timeline') }}
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ t('Redemption Created') }}</h6>
                                <small class="text-muted">{{ $redemption->created_at->format('M j, Y g:i A') }}</small>
                            </div>
                        </div>
                        
                        @if($redemption->status !== 'pending')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ 
                                    $redemption->status === 'approved' ? 'info' : 
                                    ($redemption->status === 'delivered' ? 'success' : 'danger') 
                                }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">
                                        @if($redemption->status === 'approved'){{ t('Approved') }}
                                        @elseif($redemption->status === 'delivered'){{ t('Delivered') }}
                                        @else{{ t('Cancelled') }}@endif
                                    </h6>
                                    <small class="text-muted">{{ $redemption->updated_at->format('M j, Y g:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}
</style>
@endsection 