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
            $table->enum('status', ['taken', 'not_taken', 'no_show'])
                ->default('not_taken')
                ->after('level_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_times', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
