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
        Schema::create('figures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image');
            $table->uuid('figure_type_id')->nullable();
            $table->timestamps();

            $table->index('figure_type_id', 'figures_figure_type_id_idx');

            $table->foreign('figure_type_id', 'figures_figure_type_id_fk')->on('figure_types')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('figures');
    }
};
