<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Initialize a payment transaction
     *
     * @param array $data Payment data including amount, currency, etc.
     * @return array Response containing payment URL, transaction ID, etc.
     */
    public function initialize(array $data): array;

    /**
     * Process payment callback/webhook
     *
     * @param array $data Callback data from payment gateway
     * @return array Response containing payment status, transaction ID, etc.
     */
    public function handleCallback(array $data): array;

    /**
     * Verify payment status
     *
     * @param string $transactionId
     * @return array Payment status details
     */
    public function verifyPayment(string $transactionId): array;

    /**
     * Get supported currencies
     *
     * @return array List of supported currencies
     */
    public function getSupportedCurrencies(): array;

    /**
     * Get payment gateway name
     *
     * @return string
     */
    public function getName(): string;
} 