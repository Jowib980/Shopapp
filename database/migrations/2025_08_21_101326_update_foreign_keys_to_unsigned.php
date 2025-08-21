<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update product_id in product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });

        // Update product_id in product_options
        Schema::table('product_options', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });

        // Update product_id in product_images
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->change();
        });
    }

    public function down(): void
    {
        // Rollback to normal bigInteger if needed
        Schema::table('product_variants', function (Blueprint $table) {
            $table->bigInteger('product_id')->change();
        });

        Schema::table('product_options', function (Blueprint $table) {
            $table->bigInteger('product_id')->change();
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->bigInteger('product_id')->change();
        });
    }
};
