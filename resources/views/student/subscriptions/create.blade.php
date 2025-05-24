@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('new_subscription') }}</h5>
            <a href="{{ route('student.subscriptions.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ t('back_to_subscriptions') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            @if(count($circles) === 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ t('no_circles_available') }}
                </div>
            @else
                <form action="{{ route('student.subscriptions.store') }}" method="POST" id="subscriptionForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="circle_id" class="form-label">{{ t('select_circle') }}</label>
                        <select class="form-select @error('circle_id') is-invalid @enderror" 
                                id="circle_id" name="circle_id" required>
                            <option value="">{{ t('select_circle') }}</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->id }}" {{ old('circle_id') == $circle->id ? 'selected' : '' }}>
                                    {{ $circle->name }} ({{ $circle->department->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('circle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="planSelectionContainer" style="display: none;">
                        <label class="form-label">{{ t('select_subscription_plan') }}</label>
                        <div id="planOptions" class="row">
                            <!-- Plans will be loaded here via JavaScript -->
                        </div>
                        @error('plan_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="selectedPlanDetails" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">{{ t('subscription_details') }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>{{ t('circle') }}:</strong> <span id="selectedCircle"></span></p>
                                        <p class="mb-1"><strong>{{ t('plan') }}:</strong> <span id="selectedPlan"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>{{ t('price') }}:</strong> <span id="selectedPrice"></span></p>
                                        <p class="mb-1"><strong>{{ t('payment_status') }}:</strong> 
                                            @if($paymentEnabled)
                                                <span class="badge bg-warning">{{ t('payment_required') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ t('no_payment_required') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <input type="hidden" name="plan_type" id="plan_type" value="{{ old('plan_type') }}">
                        <button type="submit" class="btn btn-primary" id="subscribeBtn" disabled>
                            {{ t('subscribe') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<!-- Make sure jQuery is loaded first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        console.log('Document ready');
        
        $('#circle_id').change(function() {
            console.log('Circle changed');
            const circleId = $(this).val();
            console.log('Circle ID:', circleId);
            
            if (!circleId) {
                $('#planSelectionContainer').hide();
                $('#selectedPlanDetails').hide();
                $('#subscribeBtn').prop('disabled', true);
                return;
            }
            
            // Get circle name
            const circleName = $('#circle_id option:selected').text();
            $('#selectedCircle').text(circleName);
            
            // Reset plan selection
            $('#plan_type').val('');
            $('#subscribeBtn').prop('disabled', true);
            
            // Fetch plans for the selected circle
            $.ajax({
                url: "{{ route('student.subscriptions.plans') }}",
                type: 'GET',
                data: { circle_id: circleId },
                dataType: 'json',
                success: function(response) {
                    console.log('Plans response:', response);
                    const plans = response.plans || [];
                    
                    if (plans.length === 0) {
                        $('#planSelectionContainer').hide();
                        $('#selectedPlanDetails').hide();
                        alert("{{ t('no_plans_available_for_circle') }}");
                        return;
                    }
                    
                    // Generate plan options
                    let planOptions = '';
                    plans.forEach(function(plan) {
                        planOptions += `
                            <div class="col-md-3 mb-3">
                                <div class="card h-100 plan-card" data-plan="${plan.id}" data-name="${plan.name}" data-price="${plan.price}">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">${plan.name}</h6>
                                        <h5 class="text-primary">${plan.price} {{ t('currency') }}</h5>
                                        <p class="text-muted small mb-2">${plan.lessons_per_month} {{ t('lessons') }}/{{ t('month') }}</p>
                                        <button type="button" class="btn btn-sm btn-outline-primary select-plan-btn">
                                            {{ t('select') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    $('#planOptions').html(planOptions);
                    $('#planSelectionContainer').show();
                    
                    // Handle plan selection
                    $('.select-plan-btn').click(function() {
                        console.log('Plan selected');
                        const planCard = $(this).closest('.plan-card');
                        $('.plan-card').removeClass('border-primary');
                        planCard.addClass('border-primary');
                        
                        const planId = planCard.data('plan');
                        const planName = planCard.data('name');
                        const planPrice = planCard.data('price');
                        
                        console.log('Selected plan:', { planId, planName, planPrice });
                        
                        $('#plan_type').val(planId);
                        $('#selectedPlan').text(planName);
                        $('#selectedPrice').text(planPrice + ' {{ t('currency') }}');
                        
                        $('#selectedPlanDetails').show();
                        $('#subscribeBtn').prop('disabled', false);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching plans:', { status, error, response: xhr.responseText });
                    alert("{{ t('error_fetching_plans') }}");
                }
            });
        });
        
        // Trigger change event if circle was already selected (e.g. on form validation failure)
        if ($('#circle_id').val()) {
            $('#circle_id').trigger('change');
        }
    });
</script>
@endpush 