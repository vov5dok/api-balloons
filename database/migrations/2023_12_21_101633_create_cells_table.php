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
        Schema::create('cells', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('x')->default(0);
            $table->unsignedInteger('y')->default(0);
            $table->boolean('is_exist')->default(false);
            $table->integer('count_shoot')->default(0);
            $table->uuid('level_id')->nullable();
            $table->uuid('figure_id')->nullable();
            $table->timestamps();

            $table->index('level_id', 'cells_level_id_idx');
            $table->index('figure_id', 'cells_figure_id_idx');

            $table->foreign('level_id', 'cells_level_id_fk')->on('levels')->references('id');
            $table->foreign('figure_id', 'cells_figure_id_fk')->on('figures')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cells');
    }
};
