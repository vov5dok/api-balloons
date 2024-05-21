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
        Schema::create('statistics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('statistic_status_id');
            $table->uuid('user_id')->nullable();
            $table->string('device_id');
            $table->timestamps();

            $table->index('statistic_status_id', 'statistics_statistic_status_id_idx');
            $table->index('user_id', 'statistics_user_id_idx');

            $table->foreign('statistic_status_id', 'statistics_statistic_status_id_fk')
                ->references('id')
                ->on('statistic_statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id', 'statistics_user_id_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
