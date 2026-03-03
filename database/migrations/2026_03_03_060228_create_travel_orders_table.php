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
            $table->date('date');
            $table->string('name');
            $table->string('position');
            $table->string('station');
            $table->enum('transportation_means', ['Air', 'Land', 'Sea']);
            $table->string('vehicle_type')->nullable();
            $table->string('destination');
            $table->date('departure_date');
            $table->date('return_date');
            $table->string('report_to');
            $table->json('purpose_of_trip'); // Stored as array
            $table->enum('accommodation_type', ['live-in', 'live-out']);
            $table->string('approved_by');
            $table->string('fund_custodian');
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
