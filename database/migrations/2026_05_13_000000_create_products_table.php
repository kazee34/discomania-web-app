<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('artist');
            $table->string('album_title');
            $table->string('slug', 280)->unique();
            $table->string('genre', 100)->nullable();
            $table->integer('release_year')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('label')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->text('description')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
