<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->uuid('cart_token')->unique();
            $table->timestampTz('expires_at')->nullable();
            $table->timestampsTz();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->nullOnDelete();

            $table->index('customer_id', 'idx_carts_customer_id');
            $table->index('session_id', 'idx_carts_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
