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
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)->unique();
            $table->string('code', 100)->unique();
            $table->timestamps();
        });

        // Permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->string('code', 255)->unique();
            $table->timestamps();
        });

        // Role-Permission pivot table
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignUuid('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();

            $table->unique(['role_id', 'permission_id']);
        });

        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('role_id')->constrained('roles');

            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('phone', 30)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamp('last_login_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
