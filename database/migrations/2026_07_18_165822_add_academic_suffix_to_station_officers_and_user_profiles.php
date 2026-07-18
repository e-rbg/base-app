<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('station_officers', function (Blueprint $table) {
            $table->string('academic_suffix')->nullable()->after('officer_name');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('academic_suffix')->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('station_officers', function (Blueprint $table) {
            $table->dropColumn('academic_suffix');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('academic_suffix');
        });
    }
};
