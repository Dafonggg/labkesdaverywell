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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->nullable()->constrained('users');

            $table->string('sync_type', 100)->nullable();

            $table->string('status', 50)->nullable();

            $table->integer('total_data')->default(0);

            $table->integer('success_data')->default(0);

            $table->integer('failed_data')->default(0);

            $table->text('response_message')->nullable();

            $table->timestamp('synced_at')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
