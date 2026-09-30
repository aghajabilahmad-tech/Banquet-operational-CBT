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
        // 1. Table events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_code', 30)->unique();
            $table->string('event_name', 150);
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('restrict');
            $table->foreignId('room_layout_id')->nullable()->constrained('room_layouts')->onDelete('set null');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('estimated_guest_count')->default(0);
            $table->integer('actual_guest_count')->nullable();
            $table->enum('event_status', ['upcoming', 'ongoing', 'completed', 'canceled'])->default('upcoming');
            $table->text('cancel_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('started_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('canceled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('canceled_at')->nullable();
            $table->timestamps();
        });

        // 2. Table event_rundowns
        Schema::create('event_rundowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->integer('order_no');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('activity', 150);
            $table->string('person_in_charge', 100)->nullable();
            $table->text('notes')->nullable();
        });

        // 3. Table event_guest_records
        Schema::create('event_guest_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('restrict');
            $table->integer('actual_guest_count');
            $table->dateTime('arrival_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_guest_records');
        Schema::dropIfExists('event_rundowns');
        Schema::dropIfExists('events');
    }
};
