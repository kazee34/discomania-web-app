<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->restrictOnDelete();
            $table->foreignId('payment_method_id')->nullable()->nullOnDelete()->constrained('payment_methods');
            $table->string('payment_type', 20);
            $table->string('payment_summary', 100);
            $table->string('status', 20)->default('completed');
            $table->string('mock_transaction_id', 60)->unique();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('EUR');
            $table->timestampTz('processed_at');
            $table->timestamps();

            $table->index('mock_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
