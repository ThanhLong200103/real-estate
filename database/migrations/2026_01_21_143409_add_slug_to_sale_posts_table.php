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
        Schema::table('sale_posts', function (Blueprint $table) {
            // Thêm cột slug vào sau cột title, cho phép nullable để tránh lỗi dữ liệu cũ
            $table->string('slug')->unique()->after('title')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sale_posts', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};