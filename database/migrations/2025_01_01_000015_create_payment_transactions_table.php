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
            $table->enum('payment_method', ['cash', 'bank_transfer', 'paymob', 'other'])->default('cash');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('EGP');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('paymob_order_id')->nullable();
            $table->string('paymob_transaction_id')->nullable();
            $table->text('paymob_response')->nullable();
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