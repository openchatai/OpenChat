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
        Schema::table('pdf_data_sources', function (Blueprint $table) {
            $table->string('ingest_status')->default(IngestStatusType::SUCCESS);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdf_data_sources', function (Blueprint $table) {
            //
        });
    }
};
