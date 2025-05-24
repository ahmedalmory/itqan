<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use App\Models\StudentSubscription;
use App\Models\SubscriptionPlan;
use App\Models\StudyCircle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the student's subscriptions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $subscriptions = StudentSubscription::where('student_id', $user->id)
            ->with(['circle.department', 'paymentTransactions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('student.subscriptions.index', compact('subscriptions'));
    }
    
    /**
     * Show the form for creating a new subscription.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get circles that match the student's gender and age
        $circles = StudyCircle::with('department')
            ->whereHas('department', function ($query) use ($user) {
                $query->where('student_gender', $user->gender)
                    ->where('registration_open', true);
            })
            ->where('age_from', '<=', $user->age)
            ->where('age_to', '>=', $user->age)
            ->get();
            
        // Check if payment is enabled
        $paymentEnabled = PaymentSetting::where('setting_key', 'payment_enabled')
            ->where('is_active', true)
            ->value('setting_value') == '1';
            
        return view('student.subscriptions.create', compact('circles', 'paymentEnabled'));
    }
    
    /**
     * Get subscription plans for a circle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(Request $request)
    {
        try {
            $circleId = $request->input('circle_id');
            
            // Validate circle exists
            $circle = StudyCircle::findOrFail($circleId);
            
            // Get active subscription plans
            $plans = SubscriptionPlan::where('is_active', true)
                ->get()
                ->map(function($plan) {
                    return [
                        'id' => $plan->type,
                        'name' => t($plan->type . '_plan'),
                        'price' => $plan->price,
                        'lessons_per_month' => $plan->lessons_per_month
                    ];
                });
            
            return response()->json(['plans' => $plans]);
        } catch (\Exception $e) {
            return response()->json(['plans' => []], 500);
        }
    }
    
    /**
     * Store a newly created subscription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'circle_id' => 'required|exists:study_circles,id',
            'plan_type' => 'required|in:monthly,quarterly,biannual,annual',
        ]);
        
        $user = Auth::user();
        $circle = StudyCircle::with('department')->findOrFail($validated['circle_id']);
        
        // Get the subscription plan based on the plan type
        $plan = SubscriptionPlan::where('type', $validated['plan_type'])
            ->where('is_active', true)
            ->first();
            
        if (!$plan) {
            return back()->with('error', t('invalid_subscription_plan'));
        }
        
        // Determine subscription duration based on plan type
        $duration = match($validated['plan_type']) {
            'monthly' => 1,
            'quarterly' => 3,
            'biannual' => 6,
            'annual' => 12,
            default => 0
        };
        
        // Calculate expiry date
        $startDate = now();
        $expiryDate = $startDate->copy()->addMonths($duration);
        
        try {
            DB::beginTransaction();
            
            // Create subscription
            $subscription = StudentSubscription::create([
                'student_id' => $user->id,
                'circle_id' => $circle->id,
                'plan_id' => $plan->id,
                'duration_months' => $duration,
                'start_date' => $startDate,
                'end_date' => $expiryDate,
                'total_amount' => $plan->price,
                'payment_status' => 'pending',
                'payment_method' => 'tap',
                'is_active' => false
            ]);
            
            // Check if payment is enabled
            $paymentEnabled = PaymentSetting::where('setting_key', 'payment_enabled')
                ->where('is_active', true)
                ->value('setting_value') == '1';
                
            if ($paymentEnabled) {
                // Create payment transaction
                $transaction = PaymentTransaction::create([
                    'subscription_id' => $subscription->id,
                    'transaction_id' => 'TRX' . strtoupper(Str::random(8)),
                    'payment_method' => 'tap',
                    'amount' => $plan->price,
                    'currency' => config('payment.currency'),
                    'status' => 'pending',
                ]);
                
                DB::commit();
                
                // Redirect to payment page
                return redirect()->route('student.subscriptions.payment', $subscription)
                    ->with('success', t('subscription_created_proceed_to_payment'));
            } else {
                // If payment is not enabled, mark subscription as active
                $subscription->update([
                    'payment_status' => 'paid',
                    'is_active' => true
                ]);
                
                DB::commit();
                
                return redirect()->route('student.subscriptions.index')
                    ->with('success', t('subscription_created_successfully'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', t('error_creating_subscription') . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified subscription.
     *
     * @param  \App\Models\StudentSubscription  $subscription
     * @return \Illuminate\View\View
     */
    public function show(StudentSubscription $subscription)
    {
        $this->authorize('view', $subscription);
        
        $subscription->load(['circle.department', 'paymentTransactions']);
        
        return view('student.subscriptions.show', compact('subscription'));
    }
    
    /**
     * Show the payment page for a subscription.
     *
     * @param  \App\Models\StudentSubscription  $subscription
     * @return \Illuminate\View\View
     */
    public function showPayment(StudentSubscription $subscription)
    {
        $this->authorize('view', $subscription);
        
        // Check if payment is enabled
        $paymentSettings = PaymentSetting::where('is_active', true)
            ->pluck('setting_value', 'setting_key')
            ->toArray();
            
        $paymentEnabled = $paymentSettings['payment_enabled'] ?? '0';
        
        if ($paymentEnabled != '1') {
            return redirect()->route('student.subscriptions.index')
                ->with('error', t('payment_system_disabled'));
        }
        
        // Get the latest pending transaction
        $transaction = $subscription->paymentTransactions()
            ->where('status', 'pending')
            ->latest()
            ->first();
            
        if (!$transaction) {
            return redirect()->route('student.subscriptions.index')
                ->with('error', t('no_pending_transactions'));
        }
        
        return view('student.subscriptions.payment', compact('subscription', 'transaction', 'paymentSettings'));
    }
    
    /**
     * Process the payment for a subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentSubscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, StudentSubscription $subscription)
    {
        $this->authorize('view', $subscription);
        
        // Implementation for payment processing would be here
        // This typically involves redirecting to a payment gateway
        
        // For simulation purposes, we'll just mark it as successful
        try {
            DB::beginTransaction();
            
            // Get the latest pending transaction
            $transaction = $subscription->paymentTransactions()
                ->where('status', 'pending')
                ->latest()
                ->first();
                
            if (!$transaction) {
                return redirect()->route('student.subscriptions.index')
                    ->with('error', t('no_pending_transactions'));
            }
            
            // Update transaction status
            $transaction->update([
                'status' => 'success',
                'updated_at' => now(),
            ]);
            
            // Update subscription status
            $subscription->update([
                'status' => 'active',
                'updated_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('student.subscriptions.index')
                ->with('success', t('payment_processed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', t('error_processing_payment') . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Cancel a subscription.
     *
     * @param  \App\Models\StudentSubscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(StudentSubscription $subscription)
    {
        $this->authorize('update', $subscription);
        
        if ($subscription->status !== 'pending') {
            return back()->with('error', t('only_pending_subscriptions_can_be_cancelled'));
        }
        
        try {
            DB::beginTransaction();
            
            // Cancel all pending transactions
            $subscription->paymentTransactions()
                ->where('status', 'pending')
                ->update(['status' => 'failed']);
            
            // Update subscription status
            $subscription->update(['status' => 'cancelled']);
            
            DB::commit();
            
            return redirect()->route('student.subscriptions.index')
                ->with('success', t('subscription_cancelled_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', t('error_cancelling_subscription') . ': ' . $e->getMessage());
        }
    }

    /**
     * Show the form for renewing a subscription.
     *
     * @return \Illuminate\View\View
     */
    public function renewForm()
    {
        $user = Auth::user();
        
        // Get the active subscription
        $subscription = StudentSubscription::where('student_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();
        
        if (!$subscription) {
            return redirect()->route('student.subscriptions.create')
                ->with('info', t('no_active_subscription_found_create_new'));
        }
        
        // Get circles that match the student's gender and age
        $circles = StudyCircle::with('department')
            ->whereHas('department', function ($query) use ($user) {
                $query->where('student_gender', $user->gender)
                    ->where('registration_open', true);
            })
            ->where('age_from', '<=', $user->age)
            ->where('age_to', '>=', $user->age)
            ->get();
            
        // Check if payment is enabled
        $paymentEnabled = PaymentSetting::where('setting_key', 'payment_enabled')
            ->where('is_active', true)
            ->value('setting_value') == '1';
            
        return view('student.subscriptions.create', compact('circles', 'paymentEnabled', 'subscription'));
    }
} 