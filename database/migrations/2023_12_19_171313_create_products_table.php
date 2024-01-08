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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('count')->default(0);
            $table->uuid('money_type_id')->nullable();
            $table->uuid('figure_id')->nullable();
            $table->timestamps();

            $table->index('money_type_id', 'products_money_type_id_idx');
            $table->index('figure_id', 'products_figure_id_idx');

            $table->foreign('money_type_id', 'products_money_type_id_fk')->on('money_types')->references('id');
            $table->foreign('figure_id', 'products_figure_id_fk')->on('figures')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
