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
        Schema::create('hasil_uji_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hasil_uji_id')->constrained('hasil_uji')->cascadeOnDelete();

            $table->string('file_name', 255)->nullable();
            $table->text('file_path')->nullable();

            $table->string('file_type', 100)->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_uji_files');
    }
};
