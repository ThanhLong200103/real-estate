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
        Schema::create('sale_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // THÊM DÒNG NÀY VÀO ĐÂY
            $table->foreignId('category_id')
                ->nullable() // Cho phép null nếu chưa xác định danh mục
                ->constrained('categories')
                ->onDelete('set null'); // Nếu xóa danh mục, tin đăng vẫn giữ lại nhưng category_id = null

            $table->string('title', 200);
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->float('area');
            $table->string('address');
            $table->integer('bedrooms')->default(0); // Nên để default 0 để tránh lỗi
            $table->integer('bathrooms')->default(0);
            $table->boolean('is_furnished')->default(false);
            $table->boolean('status')->default(0); // 0: chờ duyệt, 1: đã đăng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_posts');
    }
};