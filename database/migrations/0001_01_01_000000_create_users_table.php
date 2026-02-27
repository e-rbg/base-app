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
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            
            // Audit & Security
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->timestamp('email_verified_at')->nullable();
            // The secondary "Verification" metadata
            $table->string('verification_code')->nullable(); // For manual 6-digit code entry
            $table->timestamp('verification_sent_at')->nullable(); // To prevent "Resend" spam (throttling)
            $table->string('unverified_email')->nullable()->index(); // To store a "pending" new email change
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('timezone')->default('UTC');
            $table->json('preferences')->nullable(); // For Theme/UI settings
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
