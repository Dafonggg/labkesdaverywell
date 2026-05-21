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
        Schema::create('jadwal_sampling', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('permohonan_id')->constrained('permohonan_pengujian');

            $table->foreignUuid('petugas_lapangan_id')->constrained('users');

            $table->date('tanggal_sampling');

            $table->time('jam_sampling')->nullable();

            $table->text('lokasi');

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('status', 50)->default('scheduled');

            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_sampling');
    }
};
