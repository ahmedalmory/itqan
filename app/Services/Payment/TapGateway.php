<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TapGateway implements PaymentGatewayInterface
{
    protected $baseUrl;
    protected $secretKey;
    protected $publishableKey;
    protected $currency;

    public function __construct()
    {
        $this->baseUrl = 'https://api.tap.company/v2/';
        $this->secretKey = config('payment.gateways.tap.secret_key');
        $this->publishableKey = config('payment.gateways.tap.publishable_key');
        $this->currency = config('payment.gateways.tap.currency', 'SAR');
    }

    public function initialize(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'charges', [
                'amount' => $data['amount'],
                'currency' => $this->currency,
                'threeDSecure' => true,
                'save_card' => false,
                'description' => $data['description'] ?? 'Subscription Payment',
                'statement_descriptor' => 'Itqan Quran',
                'customer' => [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => [
                        'country_code' => '966',
                        'number' => $data['phone']
                    ]
                ],
                'source' => [
                    'id' => 'src_all'
                ],
                'post' => [
                    'url' => route('payment.callback')
                ],
                'redirect' => [
                    'url' => route('student.subscriptions.show', $data['metadata']['subscription_id'])
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'payment_url' => $responseData['transaction']['url'],
                    'transaction_id' => $responseData['id']
                ];
            }

            Log::error('Tap payment initialization failed', [
                'response' => $response->json()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to initialize payment'
            ];
        } catch (\Exception $e) {
            Log::error('Tap payment initialization error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function handleCallback(array $data): array
    {
        try {
            $success = ($data['status'] === 'CAPTURED');
            
            return [
                'success' => $success,
                'transaction_id' => $data['id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'payment_status' => $success ? 'paid' : 'failed',
                'raw_data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Tap callback processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . 'charges/' . $transactionId);

            if ($response->successful()) {
                $data = $response->json();
                $success = ($data['status'] === 'CAPTURED');

                return [
                    'success' => true,
                    'status' => $success ? 'paid' : 'failed',
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'raw_data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to verify payment'
            ];
        } catch (\Exception $e) {
            Log::error('Tap payment verification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getSupportedCurrencies(): array
    {
        return ['SAR', 'USD', 'AED', 'KWD', 'BHD', 'QAR', 'OMR', 'EGP'];
    }

    public function getName(): string
    {
        return 'tap';
    }
} 