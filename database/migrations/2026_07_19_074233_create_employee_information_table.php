<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_profile_id')->constrained()->cascadeOnDelete();
            $table->string('employee_id')->nullable();
            $table->string('position')->nullable();
            $table->string('assignment')->nullable();
            $table->string('designation')->nullable();
            $table->string('employment_status')->nullable();
            $table->date('employed_in_dar_since')->nullable();
            $table->date('employed_in_government_since')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_information');
    }
};
