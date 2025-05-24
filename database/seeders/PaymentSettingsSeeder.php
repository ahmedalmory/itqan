<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;

class PaymentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Enable payments by default
        PaymentSetting::updateOrCreateSetting('payment_enabled', '1', true);
        
        // Enable payment methods
        PaymentSetting::updateOrCreateSetting('enable_card', '1', true);
        PaymentSetting::updateOrCreateSetting('enable_wallet', '1', true);
        PaymentSetting::updateOrCreateSetting('enable_installments', '1', true);
    }
} 