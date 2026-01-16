<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action_type'); // Ví dụ: CREATE, UPDATE, DELETE, APPROVE, REJECT
            $table->string('target_type'); // Ví dụ: News, SalePost, Report
            $table->unsignedBigInteger('target_id'); // ID của bản ghi bị tác động
            $table->text('description'); // Mô tả chi tiết (VD: "Admin A đã duyệt tin đăng ID #12")
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_actions');
    }
};