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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->string('event_type', 100)->nullable()->after('event_name');
            }
            if (!Schema::hasColumn('events', 'room_name')) {
                $table->string('room_name', 100)->nullable()->after('room_layout_id');
            }
            $table->foreignId('institution_id')->nullable()->change();
            $table->time('end_time')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('siswa')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'event_type')) {
                $table->dropColumn('event_type');
            }
            if (Schema::hasColumn('events', 'room_name')) {
                $table->dropColumn('room_name');
            }
        });
    }
};
