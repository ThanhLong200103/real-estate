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
        Schema::create('sale_post_images', function (Blueprint $table) {
               $table->id();
            $table->foreignId('sale_post_id')
                ->constrained('sale_posts')
                ->cascadeOnDelete();
            $table->string('image_url', 2048);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_post_images');
    }
};
