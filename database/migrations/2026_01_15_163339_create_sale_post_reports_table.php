<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_post_reports', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_post_id')->constrained()->onDelete('cascade');
            $table->string('reason');
            $table->text('content')->nullable();
            $table->integer('status')->default(0); // 0: Chờ, 1: Gỡ bài, 2: Bác bỏ
            $table->text('admin_feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_post_reports');
    }
};