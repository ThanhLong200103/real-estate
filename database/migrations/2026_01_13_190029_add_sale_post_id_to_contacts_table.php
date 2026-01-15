<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // 1. Xóa các khóa ngoại trước (phải xóa cái này mới xóa được index)
            $table->dropForeign(['user_one_id']);
            $table->dropForeign(['user_two_id']);

            // 2. Bây giờ mới xóa Unique Index cũ
            $table->dropUnique(['user_one_id', 'user_two_id']);

            // 3. Thêm cột sale_post_id
            $table->foreignId('sale_post_id')->nullable()->after('id')
                ->constrained('sale_posts')->cascadeOnDelete();

            // 4. Tạo lại các khóa ngoại đã xóa
            $table->foreign('user_one_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('user_two_id')->references('id')->on('users')->cascadeOnDelete();

            // 5. Tạo Unique Index mới bao gồm cả bài đăng
            $table->unique(['user_one_id', 'user_two_id', 'sale_post_id']);
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Tắt kiểm tra khóa ngoại để tránh lỗi 1553
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $table->dropUnique(['user_one_id', 'user_two_id', 'sale_post_id']);
            $table->dropForeign(['sale_post_id']);
            $table->dropColumn('sale_post_id');

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }
};