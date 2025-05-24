@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('subscription_payment') }}</h5>
            <a href="{{ route('student.subscriptions.show', $subscription) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ t('back_to_subscription') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('subscription_details') }}</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
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
                                    <th>{{ t('transaction_id') }}</th>
                                    <td>{{ $transaction->transaction_id }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('payment_methods') }}</h6>
                        </div>
                        <div class="card-body">
                            <form id="paymentForm" action="{{ route('student.subscriptions.process-payment', $subscription) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="tap_card" value="card" checked>
                                        <label class="form-check-label" for="tap_card">
                                            <i class="fas fa-credit-card me-2"></i> {{ t('credit_debit_card') }}
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                        <label class="form-check-label" for="bank_transfer">
                                            <i class="fas fa-university me-2"></i> {{ t('bank_transfer') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ t('payment_gateway_redirect_notice') }}
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-lock me-1"></i> {{ t('secure_payment') }} ({{ $subscription->total_amount }} {{ config('payment.currency') }})
                                    </button>
                                </div>
                            </form>
                            
                            <!-- For demonstration purposes only -->
                            {{-- <div class="mt-4 pt-4 border-top">
                                <h6 class="text-muted">{{ t('demo_only') }}</h6>
                                <form action="{{ route('student.subscriptions.process-payment', $subscription) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="simulation">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        {{ t('simulate_successful_payment') }}
                                    </button>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 