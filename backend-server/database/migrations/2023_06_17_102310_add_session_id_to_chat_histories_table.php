<?php

use App\Http\Enums\IngestStatusType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
           $table->string('session_id')->after('chatbot_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_histories', function (Blueprint $table) {
            //
        });
    }
};
