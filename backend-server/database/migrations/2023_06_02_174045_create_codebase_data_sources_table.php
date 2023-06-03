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
        Schema::create('codebase_data_sources', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('chatbot_id');
            $table->string('repository');
            $table->dateTime('ingested_at')->nullable();
            $table->string('ingestion_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codebase_data_sources');
    }
};
