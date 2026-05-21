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
        // Performance indexes
        Schema::table('permohonan_pengujian', function (Blueprint $table) {
            $table->index('status', 'idx_permohonan_status');
        });

        Schema::table('jadwal_sampling', function (Blueprint $table) {
            $table->index('tanggal_sampling', 'idx_jadwal_tanggal');
        });

        Schema::table('registrasi_sample', function (Blueprint $table) {
            $table->index('kode_sample', 'idx_sample_kode');
        });

        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->index('status_qc', 'idx_hasil_status_qc');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['entity_type', 'entity_id'], 'idx_logs_entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_pengujian', function (Blueprint $table) {
            $table->dropIndex('idx_permohonan_status');
        });

        Schema::table('jadwal_sampling', function (Blueprint $table) {
            $table->dropIndex('idx_jadwal_tanggal');
        });

        Schema::table('registrasi_sample', function (Blueprint $table) {
            $table->dropIndex('idx_sample_kode');
        });

        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropIndex('idx_hasil_status_qc');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_logs_entity');
        });
    }
};
