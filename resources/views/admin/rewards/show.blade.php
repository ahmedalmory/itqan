@extends('layouts.app')

@section('title', t('Reward Details'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Reward Details') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.rewards.index') }}">{{ t('Rewards') }}</a></li>
        <li class="breadcrumb-item active">{{ $reward->name }}</li>
    </ol>

    <div class="row">
        <!-- Reward Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-gift me-1"></i>
                    {{ t('Reward Information') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($reward->image)
                                <img src="{{ asset('storage/' . $reward->image) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $reward->name }}"
                                     style="width: 100%; max-height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 200px;">
                                    <i class="bi bi-gift fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $reward->name }}</h3>
                            <p class="text-muted mb-3">{{ $reward->description }}</p>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>{{ t('Points Cost') }}:</strong><br>
                                    <span class="badge bg-primary fs-6">{{ number_format($reward->points_cost) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>{{ t('Status') }}:</strong><br>
                                    <span class="badge {{ $reward->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $reward->is_active ? t('Active') : t('Inactive') }}
                                    </span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>{{ t('Stock Quantity') }}:</strong><br>
                                    <span class="fs-5">{{ number_format($reward->stock_quantity) }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <strong>{{ t('Remaining Stock') }}:</strong><br>
                                    <span class="fs-5 {{ $reward->remaining_stock > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($reward->remaining_stock) }}
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <strong>{{ t('Total Redemptions') }}:</strong><br>
                                    <span class="fs-5 text-info">{{ number_format($reward->redemptions->count()) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-tools me-1"></i>
                    {{ t('Actions') }}
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.rewards.edit', $reward) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>{{ t('Edit Reward') }}
                        </a>
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>{{ t('Back to List') }}
                        </a>
                        @if($reward->redemptions->count() == 0)
                            <form method="POST" action="{{ route('admin.rewards.destroy', $reward) }}" 
                                  onsubmit="return confirm('{{ t('Are you sure you want to delete this reward?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>{{ t('Delete Reward') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Redemption History -->
    @if($reward->redemptions->count() > 0)
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-1"></i>
                {{ t('Redemption History') }} ({{ $reward->redemptions->count() }})
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
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reward->redemptions->sortByDesc('created_at') as $redemption)
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
                                    <td>
                                        <a href="{{ route('admin.reward-redemptions.show', $redemption) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
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