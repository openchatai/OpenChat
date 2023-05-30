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
        Schema::create('chatbots', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('token');
            $table->string('website')->nullable();
            $table->string('status')->default('draft');
            $table->text('prompt_message');
            $table->boolean('enhanced_privacy')->default(false);
            $table->boolean('smart_sync')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};
