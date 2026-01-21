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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // Địa lý (Quan trọng cho AI Prophet theo khu vực)
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->onDelete('set null');

            $table->string('type'); // 'sale' hoặc 'rent'
            $table->string('title', 200);
            $table->text('description');
            $table->decimal('price', 15, 2); // Tổng giá
            $table->float('area');           // Diện tích
            $table->string('address');
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->boolean('is_furnished')->default(false);
            $table->boolean('status')->default(0);
            $table->timestamps(); // Cột 'created_at' chính là cột 'ds' cho Prophet
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