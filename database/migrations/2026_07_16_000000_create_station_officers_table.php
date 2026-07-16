<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('station_officers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('station_code')->unique();
            $table->string('officer_name');
            $table->string('position');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('station_officers');
    }
};
