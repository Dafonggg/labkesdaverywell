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
        Schema::create('sample_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('sample_id')->constrained('samples')->cascadeOnDelete();

            $table->string('file_name', 255)->nullable();
            $table->text('file_path')->nullable();

            $table->string('file_type', 100)->nullable();

            $table->foreignUuid('uploaded_by')->nullable()->constrained('users');

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_files');
    }
};
