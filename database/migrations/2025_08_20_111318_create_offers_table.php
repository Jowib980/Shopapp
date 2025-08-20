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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Buy 2 Get 1 Free
            $table->enum('type', ['free', 'discount']); // free or discount
            $table->json('parameters'); // e.g., {"buy":2,"free":1} or {"buy":2,"discount":20}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
