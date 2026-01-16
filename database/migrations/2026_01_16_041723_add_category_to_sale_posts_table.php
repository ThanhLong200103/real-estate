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
            $table->string('category')->nullable()->after('title'); // Thêm cột category sau cột title
        });
    }

    public function down(): void
    {
        Schema::table('sale_posts', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};