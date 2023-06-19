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
        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->dropColumn('content');
            $table->dropColumn('normalized_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crawled_pages', function (Blueprint $table) {
            //
        });
    }
};
