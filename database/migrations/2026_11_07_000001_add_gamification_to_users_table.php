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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('xp')->default(0)->after('password');
            $table->unsignedSmallInteger('current_streak')->default(0)->after('xp');
            $table->unsignedSmallInteger('longest_streak')->default(0)->after('current_streak');
            $table->date('last_activity_date')->nullable()->after('longest_streak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['xp', 'current_streak', 'longest_streak', 'last_activity_date']);
        });
    }
};

