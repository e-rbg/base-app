<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_information', function (Blueprint $table) {
            $table->string('salary_grade')->nullable()->after('employment_status');
            $table->string('step_increment')->nullable()->after('salary_grade');
            $table->decimal('monthly_salary', 10, 2)->nullable()->after('step_increment');
        });
    }

    public function down(): void
    {
        Schema::table('employee_information', function (Blueprint $table) {
            $table->dropColumn(['salary_grade', 'step_increment', 'monthly_salary']);
        });
    }
};
