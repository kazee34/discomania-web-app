<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->timestampTz('order_date')->useCurrent();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('order_status', 50)->default('pending');
            $table->string('tracking_number', 100)->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestampsTz();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->restrictOnDelete();

            $table->index('customer_id', 'idx_orders_customer_id');
            $table->index('order_date', 'idx_orders_order_date');
            $table->index('order_number', 'idx_orders_order_number');
            $table->index('order_status', 'idx_orders_order_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};