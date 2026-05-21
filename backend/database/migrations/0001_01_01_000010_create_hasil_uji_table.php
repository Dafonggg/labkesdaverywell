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
        Schema::create('hasil_uji', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('sample_id')->constrained('samples');

            $table->foreignUuid('parameter_uji_id')->constrained('parameter_uji');

            $table->foreignUuid('analis_id')->constrained('users');

            $table->decimal('nilai_hasil', 15, 4)->nullable();

            $table->string('status_hasil', 50)->nullable();

            $table->text('metode_pengujian')->nullable();

            $table->text('catatan')->nullable();

            $table->string('status_qc', 50)->default('pending_qc');

            $table->timestamp('diuji_pada')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_uji');
    }
};
