@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('subscription_details') }}</h5>
            <a href="{{ route('student.subscriptions.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ t('back_to_subscriptions') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('subscription_information') }}</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ t('id') }}</th>
                                    <td>{{ $subscription->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('circle') }}</th>
                                    <td>{{ $subscription->circle->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('department') }}</th>
                                    <td>{{ $subscription->circle->department->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('plan') }}</th>
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
                                </tr>
                                <tr>
                                    <th>{{ t('amount') }}</th>
                                    <td>{{ $subscription->total_amount }} {{ config('payment.currency') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('start_date') }}</th>
                                    <td>{{ $subscription->start_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('end_date') }}</th>
                                    <td>{{ $subscription->end_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('status') }}</th>
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
                                </tr>
                                <tr>
                                    <th>{{ t('created_at') }}</th>
                                    <td>{{ $subscription->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('payment_information') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($subscription->paymentTransactions->isEmpty())
                                <div class="alert alert-info">
                                    {{ t('no_payment_transactions_found') }}
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ t('transaction_id') }}</th>
                                                <th>{{ t('method') }}</th>
                                                <th>{{ t('amount') }}</th>
                                                <th>{{ t('status') }}</th>
                                                <th>{{ t('date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subscription->paymentTransactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->transaction_id }}</td>
                                                    <td>{{ ucfirst($transaction->payment_method) }}</td>
                                                    <td>{{ $transaction->amount }} {{ $transaction->currency }}</td>
                                                    <td>
                                                        @if($transaction->status == 'successful')
                                                            <span class="badge bg-success">{{ t('successful') }}</span>
                                                        @elseif($transaction->status == 'pending')
                                                            <span class="badge bg-warning">{{ t('pending') }}</span>
                                                        @elseif($transaction->status == 'failed')
                                                            <span class="badge bg-danger">{{ t('failed') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            
                            @if($subscription->status == 'pending')
                                <div class="mt-3">
                                    <a href="{{ route('student.subscriptions.payment', $subscription) }}" class="btn btn-primary">
                                        <i class="fas fa-credit-card me-1"></i> {{ t('make_payment') }}
                                    </a>
                                </div>
                            @elseif($subscription->status == 'active')
                                <div class="mt-3">
                                    <form action="{{ route('student.subscriptions.cancel', $subscription) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ t('are_you_sure_cancel_subscription') }}')">
                                            <i class="fas fa-times me-1"></i> {{ t('cancel_subscription') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 