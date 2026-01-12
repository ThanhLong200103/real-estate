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
        Schema::create('sale_post_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_post_id')
                ->constrained('sale_posts')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('message');
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('sale_post_chats')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_post_chats');
    }
};
