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
        Schema::create('verifikasi_qc', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hasil_uji_id')->constrained('hasil_uji');

            $table->foreignUuid('qc_id')->constrained('users');

            $table->string('status', 50);

            $table->text('catatan')->nullable();

            $table->timestamp('diverifikasi_pada')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_qc');
    }
};
