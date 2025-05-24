<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobGateway implements PaymentGatewayInterface
{
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;
    protected $baseUrl = 'https://accept.paymob.com/api/';

    public function __construct()
    {
        $this->apiKey = config('payment.paymob.api_key');
        $this->integrationId = config('payment.paymob.integration_id');
        $this->iframeId = config('payment.paymob.iframe_id');
    }

    public function initialize(array $data): array
    {
        try {
            // Step 1: Authentication request
            $authResponse = $this->authenticate();
            if (!isset($authResponse['token'])) {
                throw new \Exception('Authentication failed');
            }

            // Step 2: Order registration
            $orderResponse = $this->registerOrder($authResponse['token'], $data);
            if (!isset($orderResponse['id'])) {
                throw new \Exception('Order registration failed');
            }

            // Step 3: Payment key request
            $paymentKeyResponse = $this->getPaymentKey($authResponse['token'], $orderResponse['id'], $data);
            if (!isset($paymentKeyResponse['token'])) {
                throw new \Exception('Payment key generation failed');
            }

            // Return iframe URL
            return [
                'success' => true,
                'payment_url' => "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKeyResponse['token']}",
                'transaction_id' => $orderResponse['id']
            ];
        } catch (\Exception $e) {
            Log::error('Paymob payment initialization failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function handleCallback(array $data): array
    {
        try {
            // Verify HMAC
            if (!$this->verifyHmac($data)) {
                throw new \Exception('Invalid HMAC signature');
            }

            $success = ($data['success'] === 'true');
            $transactionId = $data['order']['id'];
            
            return [
                'success' => $success,
                'transaction_id' => $transactionId,
                'amount' => $data['amount_cents'] / 100,
                'currency' => $data['currency'],
                'payment_status' => $success ? 'paid' : 'failed',
                'raw_data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Paymob callback processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment(string $transactionId): array
    {
        try {
            $response = Http::get($this->baseUrl . "acceptance/transactions/{$transactionId}");
            $data = $response->json();

            return [
                'success' => true,
                'status' => $data['success'] ? 'paid' : 'failed',
                'amount' => $data['amount_cents'] / 100,
                'currency' => $data['currency'],
                'raw_data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Paymob payment verification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getSupportedCurrencies(): array
    {
        return ['EGP', 'USD', 'EUR', 'GBP'];
    }

    public function getName(): string
    {
        return 'paymob';
    }

    protected function authenticate(): array
    {
        $response = Http::post($this->baseUrl . 'auth/tokens', [
            'api_key' => $this->apiKey
        ]);

        return $response->json();
    }

    protected function registerOrder(string $token, array $data): array
    {
        $response = Http::post($this->baseUrl . 'ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $data['amount'] * 100,
            'currency' => $data['currency'] ?? 'EGP',
            'items' => []
        ]);

        return $response->json();
    }

    protected function getPaymentKey(string $token, string $orderId, array $data): array
    {
        $response = Http::post($this->baseUrl . 'acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => $data['amount'] * 100,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => [
                'first_name' => $data['first_name'] ?? 'Not Provided',
                'last_name' => $data['last_name'] ?? 'Not Provided',
                'email' => $data['email'] ?? 'not.provided@example.com',
                'phone_number' => $data['phone'] ?? 'Not Provided',
                'apartment' => 'NA',
                'floor' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'NA',
                'state' => 'NA'
            ],
            'currency' => $data['currency'] ?? 'EGP',
            'integration_id' => $this->integrationId
        ]);

        return $response->json();
    }

    protected function verifyHmac(array $data): bool
    {
        // Implement HMAC verification logic here
        // This should match Paymob's HMAC calculation
        return true; // Placeholder
    }
} 