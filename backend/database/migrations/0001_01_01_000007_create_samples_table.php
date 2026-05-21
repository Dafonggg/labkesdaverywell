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
        Schema::create('samples', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('registrasi_sample_id')->constrained('registrasi_sample');

            $table->foreignUuid('jadwal_sampling_id')->nullable()->constrained('jadwal_sampling');

            $table->string('jenis_sample', 100);

            $table->text('kondisi_sample')->nullable();

            $table->text('metode_pengambilan')->nullable();

            $table->string('suhu', 50)->nullable();

            $table->string('cuaca', 100)->nullable();

            $table->text('lokasi_pengambilan')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamp('waktu_pengambilan')->nullable();

            $table->string('status', 50)->default('sampled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
