@extends('layouts.app')

@section('title', t('Reward Redemptions'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Reward Redemptions') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ t('Reward Redemptions') }}</li>
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
            <i class="bi bi-clock-history me-1"></i>
            {{ t('Redemption Management') }}
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           placeholder="{{ t('Search by student or reward...') }}" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">{{ t('All Statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ t('Pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ t('Approved') }}</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>{{ t('Delivered') }}</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ t('Cancelled') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i>{{ t('Filter') }}
                    </button>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4>{{ $redemptions->where('status', 'pending')->count() }}</h4>
                            <small>{{ t('Pending') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4>{{ $redemptions->where('status', 'approved')->count() }}</h4>
                            <small>{{ t('Approved') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4>{{ $redemptions->where('status', 'delivered')->count() }}</h4>
                            <small>{{ t('Delivered') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4>{{ $redemptions->where('status', 'cancelled')->count() }}</h4>
                            <small>{{ t('Cancelled') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redemptions Table -->
            @if($redemptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Student') }}</th>
                                <th>{{ t('Reward') }}</th>
                                <th>{{ t('Points Spent') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Redeemed At') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redemptions as $redemption)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $redemption->student->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $redemption->student->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($redemption->reward->image)
                                                <img src="{{ asset('storage/' . $redemption->reward->image) }}" 
                                                     class="me-2 rounded" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="me-2 bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-gift text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $redemption->reward->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($redemption->reward->description, 30) }}</small>
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
                                            @if($redemption->status === 'pending'){{ t('Pending') }}
                                            @elseif($redemption->status === 'approved'){{ t('Approved') }}
                                            @elseif($redemption->status === 'delivered'){{ t('Delivered') }}
                                            @else{{ t('Cancelled') }}@endif
                                        </span>
                                    </td>
                                    <td>{{ $redemption->redeemed_at->format('M j, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.reward-redemptions.show', $redemption) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               title="{{ t('View Details') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($redemption->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="updateStatus({{ $redemption->id }}, 'approved')"
                                                        title="{{ t('Approve') }}">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="updateStatus({{ $redemption->id }}, 'cancelled')"
                                                        title="{{ t('Cancel') }}">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            @elseif($redemption->status === 'approved')
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="updateStatus({{ $redemption->id }}, 'delivered')"
                                                        title="{{ t('Mark as Delivered') }}">
                                                    <i class="bi bi-truck"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $redemptions->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-clock-history fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ t('No redemptions found.') }}</h5>
                    <p class="text-muted">{{ t('Students haven\'t redeemed any rewards yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Form (Hidden) -->
<form id="statusUpdateForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="statusInput">
    <input type="hidden" name="notes" id="notesInput">
</form>

<script>
function updateStatus(redemptionId, status) {
    const statusNames = {
        'approved': '{{ t("Approved") }}',
        'delivered': '{{ t("Delivered") }}',
        'cancelled': '{{ t("Cancelled") }}'
    };
    
    const statusName = statusNames[status];
    
    Swal.fire({
        title: '{{ t("Update Status") }}',
        text: `{{ t("Are you sure you want to mark this redemption as") }} ${statusName}?`,
        input: 'textarea',
        inputPlaceholder: '{{ t("Add notes (optional)") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: status === 'cancelled' ? '#dc3545' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `{{ t("Yes, mark as") }} ${statusName}`,
        cancelButtonText: '{{ t("Cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('statusUpdateForm');
            form.action = `/admin/reward-redemptions/${redemptionId}/status`;
            document.getElementById('statusInput').value = status;
            document.getElementById('notesInput').value = result.value || '';
            form.submit();
        }
    });
}
</script>
@endsection 