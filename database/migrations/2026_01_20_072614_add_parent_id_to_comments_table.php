<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Thêm cột parent_id, cho phép null (vì comment gốc không có cha)
            // Sau khi xóa comment cha, các comment con cũng sẽ bị xóa (cascade)
            $table->unsignedBigInteger('parent_id')->nullable()->after('user_id');

            // Thiết lập khóa ngoại trỏ về chính bảng comments
            $table->foreign('parent_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};