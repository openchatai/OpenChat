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
        Schema::create('crawled_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chatbot_id');
            $table->uuid('website_data_source_id');
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('status_code')->nullable();
            $table->longText('content')->nullable();
            $table->longText('normalized_content')->nullable();
            $table->text('aws_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawled_pages');
    }
};
