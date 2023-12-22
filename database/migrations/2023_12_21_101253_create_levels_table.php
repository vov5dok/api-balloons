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
        Schema::create('levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('height')->default(0);
            $table->unsignedInteger('point_first_star')->default(0);
            $table->unsignedInteger('point_second_star')->default(0);
            $table->unsignedInteger('point_third_star')->default(0);
            $table->unsignedInteger('number')->default(0);
            $table->uuid('category_id')->nullable();
            $table->timestamps();

            $table->index('category_id', 'levels_category_id_idx');

            $table->foreign('category_id', 'levels_category_id_fk')->on('categories')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
