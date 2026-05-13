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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Basic customer information
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('dni_nif', 20)->nullable();

            // Shipping address
            $table->string('shipping_street', 255)->nullable();
            $table->string('shipping_street_number', 20)->nullable();
            $table->string('shipping_apartment', 50)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_postal_code', 20)->nullable();
            $table->string('shipping_state_province', 100)->nullable();
            $table->string('shipping_country', 100)->default('España');
            $table->char('shipping_iso_country_code', 2)->default('ES');

            // Tax information
            $table->string('tax_name', 255)->nullable();
            $table->string('tax_vat_number', 30)->nullable();

            // Customer metrics and preferences
            $table->integer('total_orders')->default(0);
            $table->jsonb('wishlist')->default('[]');
            $table->string('preferred_language', 10)->default('es');
            $table->string('preferred_currency', 3)->default('EUR');

            // Timestamps
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
