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
        // 1. Table borrowings
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('borrowing_code', 30)->unique();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('restrict');
            $table->enum('borrowing_status', ['pending', 'approved', 'rejected', 'returned', 'canceled'])->default('pending');
            $table->text('purpose')->nullable();
            $table->date('use_date');
            $table->dateTime('requested_at')->useCurrent();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('returned_at')->nullable();
            $table->text('return_notes')->nullable();
            $table->timestamps();
        });

        // 2. Table borrowing_details
        Schema::create('borrowing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->integer('quantity_requested');
            $table->integer('quantity_approved')->default(0);
            $table->integer('quantity_returned')->default(0);
            $table->integer('quantity_damaged')->default(0);
            $table->integer('quantity_lost')->default(0);
            $table->text('notes')->nullable();
            $table->unique(['borrowing_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
        Schema::dropIfExists('borrowings');
    }
};
