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
        Schema::create('hints', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('count')->default(0);
            $table->uuid('user_id')->nullable();
            $table->uuid('figure_id')->nullable();
            $table->timestamps();

            $table->index('user_id', 'hints_user_id_idx');
            $table->index('figure_id', 'hints_figure_id_idx');

            $table->foreign('user_id', 'hints_user_id_fk')->on('users')->references('id');
            $table->foreign('figure_id', 'hints_figure_id_fk')->on('figures')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hints');
    }
};
