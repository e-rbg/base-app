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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('travel_order_no')->unique();
            $table->string('travel_type');
            $table->date('travel_date');
            $table->string('name');
            $table->string('position');
            $table->string('station');
            $table->enum('transportation_means', ['Air', 'Land', 'Sea']);
            $table->string('vehicle_type')->nullable();
            $table->string('destination');
            $table->date('departure_date');
            $table->date('return_date');
            $table->string('report_to');
            $table->json('purpose_of_trip');
            $table->string('accommodation_type');
            $table->string('recommending_approval')->nullable();
            $table->string('approved_by');
            $table->string('fund_custodian');
            $table->string('status')->default('pending');
            $table->dateTime('approved_at')->nullable();
            $table->string('esignature_hash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_orders');
    }
};
