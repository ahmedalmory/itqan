<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentTransaction;
use App\Models\StudentSubscription;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $gateway;

    public function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function processSubscriptionPayment(StudentSubscription $subscription): array
    {
        try {
            $user = $subscription->student;
            
            $paymentData = [
                'amount' => $subscription->total_amount,
                'currency' => 'EGP',
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'description' => 'Subscription Payment - ' . $subscription->circle->name,
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'student_id' => $user->id,
                    'circle_id' => $subscription->circle_id
                ]
            ];

            $response = $this->gateway->initialize($paymentData);

            if ($response['success']) {
                // Create payment transaction record
                PaymentTransaction::create([
                    'subscription_id' => $subscription->id,
                    'transaction_id' => $response['transaction_id'],
                    'payment_method' => $this->gateway->getName(),
                    'amount' => $subscription->total_amount,
                    'currency' => 'EGP',
                    'status' => 'pending'
                ]);

                return [
                    'success' => true,
                    'payment_url' => $response['payment_url'],
                    'transaction_id' => $response['transaction_id']
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Payment initialization failed'
            ];
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ];
        }
    }

    public function handlePaymentCallback(array $data): array
    {
        try {
            $response = $this->gateway->handleCallback($data);

            if ($response['success']) {
                $transaction = PaymentTransaction::where('transaction_id', $response['transaction_id'])->first();

                if (!$transaction) {
                    throw new \Exception('Transaction not found');
                }

                $transaction->update([
                    'status' => $response['payment_status'],
                    'raw_response' => $response['raw_data']
                ]);

                if ($response['payment_status'] === 'paid') {
                    $subscription = $transaction->subscription;
                    $subscription->update([
                        'payment_status' => 'paid',
                        'is_active' => true
                    ]);
                }

                return [
                    'success' => true,
                    'status' => $response['payment_status'],
                    'subscription_id' => $transaction->subscription_id
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Payment callback processing failed'
            ];
        } catch (\Exception $e) {
            Log::error('Payment callback processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment callback processing failed: ' . $e->getMessage()
            ];
        }
    }

    public function verifyPayment(string $transactionId): array
    {
        try {
            $response = $this->gateway->verifyPayment($transactionId);

            if ($response['success']) {
                $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();

                if (!$transaction) {
                    throw new \Exception('Transaction not found');
                }

                if ($response['status'] !== $transaction->status) {
                    $transaction->update([
                        'status' => $response['status'],
                        'raw_response' => $response['raw_data']
                    ]);

                    if ($response['status'] === 'paid') {
                        $subscription = $transaction->subscription;
                        $subscription->update([
                            'payment_status' => 'paid',
                            'is_active' => true
                        ]);
                    }
                }

                return [
                    'success' => true,
                    'status' => $response['status'],
                    'subscription_id' => $transaction->subscription_id
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Payment verification failed'
            ];
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ];
        }
    }
} 