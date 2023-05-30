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
        Schema::create('website_data_sources', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('chatbot_id');
            $table->string('root_url');
            $table->string('icon')->nullable();
            $table->datetime('vector_databased_last_ingested_at')->nullable();
            $table->string('crawling_status')->default('pending');
            $table->float('crawling_progress')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_data_sources');
    }
};
