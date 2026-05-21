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
        Schema::create('registrasi_sample', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('permohonan_id')->constrained('permohonan_pengujian');

            $table->string('nomor_registrasi', 100)->unique();

            $table->string('kode_sample', 100)->unique();

            $table->timestamp('tanggal_registrasi');

            $table->string('status', 50)->default('registered');

            $table->foreignUuid('dibuat_oleh')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi_sample');
    }
};
