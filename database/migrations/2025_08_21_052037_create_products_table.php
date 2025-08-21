<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id')->unique();
            $table->string('title');
            $table->text('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->string('handle')->nullable();
            $table->string('template_suffix')->nullable();
            $table->string('published_scope')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // Product variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id')->unique();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('inventory_policy')->nullable();
            $table->integer('position')->nullable();
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('sku')->nullable();
            $table->integer('inventory_quantity')->nullable();
            $table->boolean('taxable')->default(true);
            $table->timestamps();
        });

        // Product options table
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name');
            $table->integer('position')->nullable();
            $table->json('values');
            $table->timestamps();
        });

        // Product images table
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id')->unique();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('src');
            $table->integer('position')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('alt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};
