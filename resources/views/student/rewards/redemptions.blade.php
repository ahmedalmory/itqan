@extends('layouts.app')

@section('title', t('My Redemptions'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('My Redemptions') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('student.rewards.index') }}">{{ t('Rewards') }}</a></li>
        <li class="breadcrumb-item active">{{ t('My Redemptions') }}</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="h4 mb-0">{{ $redemptions->count() }}</div>
                            <div>{{ t('Total Redemptions') }}</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-gift fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="h4 mb-0">{{ $redemptions->where('status', 'delivered')->count() }}</div>
                            <div>{{ t('Delivered') }}</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="h4 mb-0">{{ $redemptions->where('status', 'pending')->count() }}</div>
                            <div>{{ t('Pending') }}</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-clock fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="h4 mb-0">{{ number_format($totalPointsSpent) }}</div>
                            <div>{{ t('Points Spent') }}</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-star fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Redemptions History -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-clock-history me-1"></i>
                    {{ t('Redemption History') }}
                </div>
                <div>
                    <a href="{{ route('student.rewards.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus me-1"></i>{{ t('Redeem More') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($redemptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Reward') }}</th>
                                <th>{{ t('Points Spent') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Redeemed At') }}</th>
                                <th>{{ t('Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redemptions as $redemption)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($redemption->reward->image)
                                                <img src="{{ asset('storage/' . $redemption->reward->image) }}" 
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="bi bi-gift text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $redemption->reward->name }}</div>
                                                <small class="text-muted">{{ Str::limit($redemption->reward->description, 50) }}</small>
                                            </div>
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
                                            @if($redemption->status === 'pending')
                                                <i class="bi bi-clock me-1"></i>{{ t('Pending') }}
                                            @elseif($redemption->status === 'approved')
                                                <i class="bi bi-check me-1"></i>{{ t('Approved') }}
                                            @elseif($redemption->status === 'delivered')
                                                <i class="bi bi-check-circle me-1"></i>{{ t('Delivered') }}
                                            @else
                                                <i class="bi bi-x-circle me-1"></i>{{ t('Cancelled') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $redemption->redeemed_at->format('M j, Y g:i A') }}</td>
                                    <td>
                                        @if($redemption->notes)
                                            <small class="text-muted">{{ Str::limit($redemption->notes, 30) }}</small>
                                        @else
                                            <small class="text-muted">{{ t('No notes') }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-gift fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ t('No redemptions yet') }}</h5>
                    <p class="text-muted">{{ t('Start redeeming rewards to see your history here.') }}</p>
                    <a href="{{ route('student.rewards.index') }}" class="btn btn-primary">
                        <i class="bi bi-gift me-1"></i>{{ t('Browse Rewards') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 