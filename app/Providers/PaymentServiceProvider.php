<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymobGateway;
use App\Services\Payment\TapGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $gateway = config('payment.default');
            
            return match($gateway) {
                'paymob' => new PaymobGateway(),
                'tap' => new TapGateway(),
                default => throw new \Exception("Unsupported payment gateway: {$gateway}")
            };
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService($app->make(PaymentGatewayInterface::class));
        });
    }

    public function boot()
    {
        //
    }
} 