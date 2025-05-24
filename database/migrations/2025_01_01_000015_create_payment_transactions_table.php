<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('student_subscriptions');
            $table->string('transaction_id')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'tap', 'paymob', 'other'])->default('paymob');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('SAR');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('tap_charge_id')->nullable();
            $table->text('tap_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
}; 