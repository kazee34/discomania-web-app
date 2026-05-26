<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->string('card_holder_name', 100);
            $table->string('card_brand', 20); // 'visa' | 'mastercard'
            $table->char('card_last_four', 4);
            $table->tinyInteger('card_expiry_month');
            $table->smallInteger('card_expiry_year');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};
