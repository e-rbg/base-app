<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('extension')->nullable()->after('last_name');
            $table->json('academic_titles')->nullable()->after('academic_suffix');
            $table->string('gender')->nullable()->after('academic_titles');
            $table->string('marital_status')->nullable()->after('gender');
            $table->string('spouse')->nullable()->after('marital_status');
            $table->string('blood_type')->nullable()->after('spouse');
            $table->text('address')->nullable()->after('blood_type');
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['extension', 'academic_titles', 'gender', 'marital_status', 'spouse', 'blood_type', 'address']);
        });
    }
};
