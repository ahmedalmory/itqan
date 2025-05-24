<?php

namespace App\Http\Controllers;

use App\Models\StudentSubscription;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function processPayment(StudentSubscription $subscription)
    {
        try {
            $response = $this->paymentService->processSubscriptionPayment($subscription);

            if ($response['success']) {
                return redirect($response['payment_url']);
            }

            return back()->with('error', $response['message']);
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());
            return back()->with('error', t('error_processing_payment'));
        }
    }

    public function handleCallback(Request $request)
    {
        try {
            $response = $this->paymentService->handlePaymentCallback($request->all());

            if ($response['success']) {
                if ($response['status'] === 'paid') {
                    return redirect()->route('student.subscriptions.show', $response['subscription_id'])
                        ->with('success', t('payment_processed_successfully'));
                }

                return redirect()->route('student.subscriptions.show', $response['subscription_id'])
                    ->with('error', t('payment_failed'));
            }

            return redirect()->route('student.subscriptions.index')
                ->with('error', $response['message']);
        } catch (\Exception $e) {
            Log::error('Payment callback processing failed: ' . $e->getMessage());
            return redirect()->route('student.subscriptions.index')
                ->with('error', t('error_processing_payment'));
        }
    }

    public function handleWebhook(Request $request)
    {
        try {
            $response = $this->paymentService->handlePaymentCallback($request->all());
            
            if (!$response['success']) {
                Log::error('Payment webhook processing failed: ' . $response['message']);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Payment webhook processing failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request, string $transactionId)
    {
        try {
            $response = $this->paymentService->verifyPayment($transactionId);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => t('error_verifying_payment')
            ], 500);
        }
    }
} 