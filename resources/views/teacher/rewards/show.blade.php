@extends('layouts.app')

@section('title', $reward->name)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $reward->name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('teacher.rewards.index') }}">{{ t('Rewards') }}</a></li>
        <li class="breadcrumb-item active">{{ $reward->name }}</li>
    </ol>

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

                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <p class="mb-0">
                                        <strong>{{ t('Total Stock') }}:</strong>
                                        {{ number_format($reward->stock_quantity) }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-0">
                                        <strong>{{ t('Total Redemptions') }}:</strong>
                                        {{ number_format($reward->redemptions->count()) }}
                                    </p>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>{{ t('Status') }}:</strong> 
                                <span class="badge {{ $reward->isAvailable() ? 'bg-success' : 'bg-secondary' }} ms-1">
                                    {{ $reward->isAvailable() ? t('Available') : t('Out of Stock') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ t('Reward Information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        {{ t('This reward is available for students to redeem using their earned points.') }}
                    </p>
                    
                    <hr>
                    
                    <h6>{{ t('Requirements') }}:</h6>
                    <ul class="mb-3">
                        <li>{{ t('Student must have at least :points points', ['points' => number_format($reward->points_cost)]) }}</li>
                        <li>{{ t('Reward must be in stock') }}</li>
                        <li>{{ t('Student account must be active') }}</li>
                    </ul>
                    
                    <a href="{{ route('teacher.rewards.index') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-arrow-left me-1"></i>{{ t('Back to Rewards') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Redemptions -->
    @if($reward->redemptions->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-clock-history me-1"></i>
                {{ t('Recent Redemptions') }} ({{ $reward->redemptions->count() }})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Student') }}</th>
                                <th>{{ t('Points Spent') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Redeemed At') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reward->redemptions->sortByDesc('created_at')->take(10) as $redemption)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $redemption->student->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $redemption->student->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ number_format($redemption->points_spent) }}</span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>{{ $redemption->redeemed_at->format('M j, Y g:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 