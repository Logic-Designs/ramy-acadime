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
        Schema::table('booking_times', function (Blueprint $table) {
            $table->foreignId('level_session_id')->nullable()->constrained()->onDelete('set null')->after('session_time_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_times', function (Blueprint $table) {
            $table->dropForeign(['level_session_id']);
            $table->dropColumn('level_session_id');
        });
    }
};
