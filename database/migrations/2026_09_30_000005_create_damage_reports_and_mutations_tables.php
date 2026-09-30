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
        // 1. Table damage_reports
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code', 30)->unique();
            $table->foreignId('borrowing_detail_id')->constrained('borrowing_details')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('responsible_name', 100)->nullable();
            $table->enum('damage_type', ['rusak', 'pecah', 'hilang']);
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->dateTime('reported_at')->useCurrent();
        });

        // 2. Table stock_mutations
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->foreignId('damage_report_id')->nullable()->constrained('damage_reports')->onDelete('set null');
            $table->enum('mutation_type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reference_note', 255)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
        Schema::dropIfExists('damage_reports');
    }
};
