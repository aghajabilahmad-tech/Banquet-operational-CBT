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
        // 1. Table institutions
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('institution_name', 150);
            $table->string('contact_person', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 2. Table room_layouts
        Schema::create('room_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('layout_name', 100);
            $table->text('description')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_layouts');
        Schema::dropIfExists('institutions');
    }
};
