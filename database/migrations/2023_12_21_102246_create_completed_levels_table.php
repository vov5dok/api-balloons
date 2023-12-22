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
        Schema::create('completed_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('count_star')->default(0);
            $table->uuid('level_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();

            $table->index('level_id', 'completed_levels_level_id_idx');
            $table->index('user_id', 'completed_levels_user_id_idx');

            $table->foreign('level_id', 'completed_levels_level_id_fk')->on('levels')->references('id');
            $table->foreign('user_id', 'completed_levels_user_id_fk')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_levels');
    }
};
