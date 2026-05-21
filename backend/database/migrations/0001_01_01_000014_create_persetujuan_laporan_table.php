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
        Schema::create('persetujuan_laporan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('draft_laporan_id')->constrained('draft_laporan');

            $table->foreignUuid('kepala_uptd_id')->constrained('users');

            $table->string('status', 50)->default('pending');

            $table->text('catatan')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_laporan');
    }
};
