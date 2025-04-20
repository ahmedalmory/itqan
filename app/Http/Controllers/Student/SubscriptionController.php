<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use App\Models\StudentSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the student's subscriptions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $subscriptions = StudentSubscription::where('student_id', $user->id)
            ->with(['department', 'paymentTransactions'])
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
        
        // Get departments that match the student's gender
        $departments = Department::where('student_gender', $user->gender)
            ->where('registration_open', true)
            ->get();
            
        // Check if payment is enabled
        $paymentEnabled = PaymentSetting::where('setting_key', 'payment_enabled')
            ->where('is_active', true)
            ->value('setting_value') == '1';
            
        return view('student.subscriptions.create', compact('departments', 'paymentEnabled'));
    }
    
    /**
     * Get subscription plans for a department.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(Request $request)
    {
        $departmentId = $request->input('department_id');
        
        $department = Department::findOrFail($departmentId);
        
        $plans = [
            ['id' => 'monthly', 'name' => t('monthly_plan'), 'price' => $department->monthly_fees],
            ['id' => 'quarterly', 'name' => t('quarterly_plan'), 'price' => $department->quarterly_fees],
            ['id' => 'biannual', 'name' => t('biannual_plan'), 'price' => $department->biannual_fees],
            ['id' => 'annual', 'name' => t('annual_plan'), 'price' => $department->annual_fees],
        ];
        
        return response()->json([
            'plans' => array_filter($plans, function($plan) {
                return !is_null($plan['price']);
            })
        ]);
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
            'department_id' => 'required|exists:departments,id',
            'plan_type' => 'required|in:monthly,quarterly,biannual,annual',
        ]);
        
        $user = Auth::user();
        $department = Department::findOrFail($validated['department_id']);
        
        // Determine subscription amount based on plan type
        $amount = match($validated['plan_type']) {
            'monthly' => $department->monthly_fees,
            'quarterly' => $department->quarterly_fees,
            'biannual' => $department->biannual_fees,
            'annual' => $department->annual_fees,
            default => 0
        };
        
        if (!$amount) {
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
                'department_id' => $department->id,
                'plan_type' => $validated['plan_type'],
                'amount' => $amount,
                'currency' => 'EGP', // Default currency
                'start_date' => $startDate,
                'expiry_date' => $expiryDate,
                'status' => 'pending', // Initial status is pending
            ]);
            
            // Is payment is enabled, create a payment transaction
            $paymentEnabled = PaymentSetting::where('setting_key', 'payment_enabled')
                ->where('is_active', true)
                ->value('setting_value') == '1';
                
            if ($paymentEnabled && $amount > 0) {
                // Create payment transaction
                $transaction = PaymentTransaction::create([
                    'subscription_id' => $subscription->id,
                    'transaction_id' => 'TRX' . strtoupper(Str::random(8)),
                    'payment_method' => 'paymob', // Default to Paymob
                    'amount' => $amount,
                    'currency' => 'EGP',
                    'status' => 'pending',
                ]);
                
                DB::commit();
                
                // Redirect to payment page
                return redirect()->route('student.subscriptions.payment', $subscription)
                    ->with('success', t('subscription_created_proceed_to_payment'));
            } else {
                // If payment is not enabled, mark subscription as active
                $subscription->update(['status' => 'active']);
                
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
        
        $subscription->load(['department', 'paymentTransactions']);
        
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
        
        // Get departments that match the student's gender
        $departments = Department::where('student_gender', $user->gender)
            ->where('registration_open', true)
            ->get();
            
        // Check if payment is enabled
        $paymentEnabled = PaymentSetting::where('setting_key', 'payment_enabled')
            ->where('is_active', true)
            ->value('setting_value') == '1';
            
        return view('student.subscriptions.create', compact('departments', 'paymentEnabled', 'subscription'));
    }
} 