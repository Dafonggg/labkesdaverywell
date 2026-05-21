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
        Schema::create('parameter_uji', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('nama_parameter', 255);

            $table->string('satuan', 100)->nullable();

            $table->decimal('baku_mutu_min', 15, 4)->nullable();
            $table->decimal('baku_mutu_max', 15, 4)->nullable();

            $table->text('metode_uji')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_uji');
    }
};
