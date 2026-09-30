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
        // 1. Table item_categories
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 100)->unique();
            $table->text('description')->nullable();
        });

        // 2. Table items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 30)->unique();
            $table->string('item_name', 150);
            $table->foreignId('category_id')->constrained('item_categories')->onDelete('restrict');
            $table->string('unit', 20)->default('pcs');
            $table->integer('total_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('storage_location', 100)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_categories');
    }
};
