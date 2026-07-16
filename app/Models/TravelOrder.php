<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use EdeesonOpina\PsgcApi\Models\Barangay;

class TravelOrder extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'travel_order_no',
        'travel_date',
        'name',
        'position',
        'station',
        'transportation_means',
        'vehicle_type',
        'travel_type',
        'destination',
        'departure_date',
        'return_date',
        'report_to',
        'purpose_of_trip',
        'accommodation_type',
        'approved_by_name',
        'approved_by_position',
        'fund_custodian',
        'recommending_approval',
        'recommending_position',
        'status',
        'approved_at',
        'esignature_hash',


    ];
    protected $casts = [
        'purpose_of_trip' => 'array',
        'accommodation_type' => 'array',
        'vehicle_type' => 'array',
        'travel_date' => 'date',
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the user that owns the travel order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format destination as "Barangay, Municipality, Davao de Oro"
     */
    public function formattedDestination(): string
    {
        if (empty($this->destination)) {
            return '';
        }

        // Look up the barangay in PSGC data
        $barangay = Barangay::query()
            ->join('city_municipalities', 'barangays.city_municipality_id', '=', 'city_municipalities.id')
            ->where('barangays.province_id', 58) // Davao de Oro
            ->whereRaw('LOWER(barangays.name) = ?', [strtolower($this->destination)])
            ->first(['barangays.name', 'city_municipalities.name as municipality_name']);

        if ($barangay) {
            return "{$barangay->name}, {$barangay->municipality_name}, Davao de Oro";
        }

        // Fallback for free-text entries (regional/national destinations)
        return $this->destination;
    }
}


