<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dateTime('recommending_approved_at')->nullable()->after('esignature_hash');
            $table->string('esignature_recommender_hash')->nullable()->after('recommending_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropColumn(['recommending_approved_at', 'esignature_recommender_hash']);
        });
    }
};
