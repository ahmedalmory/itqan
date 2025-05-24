@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('my_subscriptions') }}</h5>
            <a href="{{ route('student.subscriptions.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> {{ t('new_subscription') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ t('id') }}</th>
                            <th>{{ t('circle') }}</th>
                            <th>{{ t('plan') }}</th>
                            <th>{{ t('amount') }}</th>
                            <th>{{ t('start_date') }}</th>
                            <th>{{ t('end_date') }}</th>
                            <th>{{ t('status') }}</th>
                            <th>{{ t('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>{{ $subscription->circle->name }} ({{ $subscription->circle->department->name }})</td>
                                <td>
                                    @if($subscription->plan_type == 'monthly')
                                        {{ t('monthly_plan') }}
                                    @elseif($subscription->plan_type == 'quarterly')
                                        {{ t('quarterly_plan') }}
                                    @elseif($subscription->plan_type == 'biannual')
                                        {{ t('biannual_plan') }}
                                    @elseif($subscription->plan_type == 'annual')
                                        {{ t('annual_plan') }}
                                    @endif
                                </td>
                                <td>{{ $subscription->total_amount }} {{ config('payment.currency') }}</td>
                                <td>{{ $subscription->start_date->format('Y-m-d') }}</td>
                                <td>{{ $subscription->end_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($subscription->is_active)
                                        <span class="badge bg-success">{{ t('active') }}</span>
                                    @elseif($subscription->payment_status == 'pending')
                                        <span class="badge bg-warning">{{ t('pending') }}</span>
                                    @elseif($subscription->payment_status == 'failed')
                                        <span class="badge bg-danger">{{ t('failed') }}</span>
                                    @elseif($subscription->payment_status == 'refunded')
                                        <span class="badge bg-secondary">{{ t('refunded') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('student.subscriptions.show', $subscription) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($subscription->status == 'pending')
                                            <a href="{{ route('student.subscriptions.payment', $subscription) }}" class="btn btn-primary">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                        @endif
                                        
                                        @if($subscription->status == 'active')
                                            <form action="{{ route('student.subscriptions.cancel', $subscription) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ t('are_you_sure_cancel_subscription') }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ t('no_subscriptions_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
@endsection 