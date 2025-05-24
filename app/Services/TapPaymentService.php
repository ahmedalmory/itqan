<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\StudentSubscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TapPaymentService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publishableKey;
    protected $currency;
    protected $redirectUrl;
    protected $siteBaseUrl;

    public function __construct()
    {
        $this->baseUrl = config('tap.base_url');
        $this->secretKey = config('tap.secret_key');
        $this->publishableKey = config('tap.publishable_key');
        $this->currency = config('tap.currency');
        $this->redirectUrl = config('tap.redirect_url');
        $this->siteBaseUrl = config('tap.site_base_url');
    }

    /**
     * Create a payment session for the subscription
     *
     * @param StudentSubscription $subscription
     * @param PaymentTransaction $transaction
     * @return array|null
     */
    public function createPaymentSession(StudentSubscription $subscription, PaymentTransaction $transaction)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'charges', [
                'amount' => $subscription->total_amount,
                'currency' => $this->currency,
                'threeDSecure' => true,
                'save_card' => false,
                'description' => 'Circle Subscription Payment - ' . $subscription->circle->name,
                'statement_descriptor' => 'Itqan Quran',
                'reference' => [
                    'transaction' => $transaction->transaction_id,
                    'order' => 'sub_' . $subscription->id
                ],
                'receipt' => [
                    'email' => true,
                    'sms' => true
                ],
                'customer' => [
                    'first_name' => $subscription->student->first_name,
                    'last_name' => $subscription->student->last_name,
                    'email' => $subscription->student->email,
                    'phone' => [
                        'country_code' => '966',
                        'number' => $subscription->student->phone
                    ]
                ],
                'source' => [
                    'id' => 'src_all'
                ],
                'post' => [
                    'url' => $this->siteBaseUrl . 'api/payment/callback'
                ],
                'redirect' => [
                    'url' => $this->siteBaseUrl . $this->redirectUrl . $transaction->transaction_id
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update transaction with Tap payment details
                $transaction->update([
                    'tap_charge_id' => $data['id'],
                    'tap_response' => json_encode($data),
                ]);

                return [
                    'success' => true,
                    'redirect_url' => $data['transaction']['url'],
                    'charge_id' => $data['id']
                ];
            }

            Log::error('Tap payment session creation failed', [
                'response' => $response->json(),
                'subscription_id' => $subscription->id,
                'transaction_id' => $transaction->id
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create payment session'
            ];

        } catch (\Exception $e) {
            Log::error('Tap payment session creation error', [
                'message' => $e->getMessage(),
                'subscription_id' => $subscription->id,
                'transaction_id' => $transaction->id
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while processing the payment'
            ];
        }
    }

    /**
     * Verify the payment callback
     *
     * @param string $chargeId
     * @return array
     */
    public function verifyPayment(string $chargeId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . 'charges/' . $chargeId);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['status'],
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'reference' => $data['reference'] ?? null,
                    'response' => $data
                ];
            }

            Log::error('Tap payment verification failed', [
                'charge_id' => $chargeId,
                'response' => $response->json()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to verify payment'
            ];

        } catch (\Exception $e) {
            Log::error('Tap payment verification error', [
                'message' => $e->getMessage(),
                'charge_id' => $chargeId
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while verifying the payment'
            ];
        }
    }
} 