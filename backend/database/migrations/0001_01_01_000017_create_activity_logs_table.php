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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->nullable()->constrained('users');

            $table->string('action', 255)->nullable();

            $table->string('entity_type', 255)->nullable();

            $table->uuid('entity_id')->nullable();

            $table->json('old_values')->nullable();

            $table->json('new_values')->nullable();

            $table->string('ip_address', 100)->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
