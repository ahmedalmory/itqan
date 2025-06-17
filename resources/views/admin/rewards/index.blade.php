@extends('layouts.app')

@section('title', t('Rewards Management'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Rewards Management') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ t('Rewards') }}</li>
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

    <div class="card mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <i class="bi bi-gift me-1"></i>
                    {{ t('Rewards & Prizes') }}
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-1"></i>{{ t('Add Reward') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" 
                           placeholder="{{ t('Search rewards...') }}" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">{{ t('All Statuses') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ t('Active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i>{{ t('Filter') }}
                    </button>
                </div>
            </form>

            <!-- Rewards Table -->
            @if($rewards->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Image') }}</th>
                                <th>{{ t('Name') }}</th>
                                <th>{{ t('Points Cost') }}</th>
                                <th>{{ t('Stock') }}</th>
                                <th>{{ t('Remaining Stock') }}</th>
                                <th>{{ t('Total Redemptions') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rewards as $reward)
                                <tr>
                                    <td>
                                        @if($reward->image)
                                            <img src="{{ asset('storage/' . $reward->image) }}" 
                                                 class="rounded" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-gift text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $reward->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($reward->description, 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ number_format($reward->points_cost) }} {{ t('points') }}</span>
                                    </td>
                                    <td>{{ number_format($reward->stock_quantity) }}</td>
                                    <td>
                                        <span class="{{ $reward->remaining_stock > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($reward->remaining_stock) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($reward->redemptions->count()) }}</td>
                                    <td>
                                        <span class="badge {{ $reward->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $reward->is_active ? t('Active') : t('Inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.rewards.show', $reward) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               title="{{ t('View') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.rewards.edit', $reward) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="{{ t('Edit') }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.rewards.destroy', $reward) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('{{ t('Are you sure you want to delete this reward?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="{{ t('Delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $rewards->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-gift fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ t('No rewards found.') }}</h5>
                    <p class="text-muted">{{ t('Start by creating your first reward.') }}</p>
                    <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-1"></i>{{ t('Add Reward') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 