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
        Schema::create('laporan_final', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('draft_laporan_id')->constrained('draft_laporan');

            $table->string('nomor_laporan', 100)->unique();

            $table->text('file_pdf');

            $table->text('hash_sha256');

            $table->boolean('is_final')->default(true);

            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_final');
    }
};
