<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_posts', function (Blueprint $table) {
            // Thêm cột type sau cột user_id
            // 'sale' là Bán, 'rent' là Cho thuê. Mặc định là 'sale'.
            $table->enum('type', ['sale', 'rent'])->default('sale')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('sale_posts', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};