<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Update shopify_id in product_options
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('shopify_id')->change();
        });

        // Update shopify_id in product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('shopify_id')->change();
        });

        // Update shopify_id in product_images
        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('shopify_id')->change();
        });
    }

    public function down(): void
    {

        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('shopify_id')->change();
        });

        // Rollback to normal bigInteger if needed
        Schema::table('product_variants', function (Blueprint $table) {
            $table->bigInteger('shopify_id')->change();
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->bigInteger('shopify_id')->change();
        });
    }
};
