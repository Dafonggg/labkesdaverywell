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
        Schema::create('permohonan_pengujian', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('nomor_permohonan', 100)->unique();

            $table->string('jenis_pemohon', 50);

            $table->string('nama_instansi', 255)->nullable();
            $table->string('nama_pemohon', 255);

            $table->string('email', 255)->nullable();
            $table->string('phone', 30)->nullable();

            $table->text('alamat')->nullable();

            $table->string('jenis_sample', 100);

            $table->decimal('total_biaya', 15, 2)->default(0);

            $table->string('status', 50)->default('pending');

            $table->text('catatan')->nullable();

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
        Schema::dropIfExists('permohonan_pengujian');
    }
};
