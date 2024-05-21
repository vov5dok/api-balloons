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
        Schema::table('completed_levels', function (Blueprint $table) {
            $table->time('time_completed')->nullable();
            $table->unsignedInteger('count_steps')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('completed_levels', function (Blueprint $table) {
            $table->dropColumn('time_completed');
            $table->dropColumn('count_steps');
        });
    }
};
