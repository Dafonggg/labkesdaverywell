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
        Schema::create('draft_laporan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('permohonan_id')->constrained('permohonan_pengujian');

            $table->foreignUuid('analis_id')->constrained('users');

            $table->string('nomor_laporan', 100)->unique()->nullable();

            $table->string('status', 50)->default('draft');

            $table->text('file_path')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamp('dibuat_pada')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_laporan');
    }
};
