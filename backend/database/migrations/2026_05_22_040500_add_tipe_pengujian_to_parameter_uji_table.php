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
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->string('tipe_pengujian', 50)->default('laboratorium')
                  ->after('kategori')
                  ->comment('on_site or laboratorium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn('tipe_pengujian');
        });
    }
};
