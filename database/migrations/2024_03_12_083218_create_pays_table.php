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
        Schema::create('pays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('product_id');
            $table->uuid('status_id');
            $table->timestamps();

            $table->index('user_id', 'pays_user_id_idx');
            $table->index('product_id', 'pays_product_id_idx');
            $table->index('status_id', 'pays_status_id_idx');

            $table->foreign('user_id', 'pays_user_id_fk')->on('users')->references('id');
            $table->foreign('product_id', 'pays_product_id_fk')->on('products')->references('id');
            $table->foreign('status_id', 'pays_status_id_fk')->on('pay_statuses')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
