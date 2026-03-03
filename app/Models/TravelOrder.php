<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelOrder extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'travel_order_no',
        'travel_date',
        'name',
        'position',
        'station',
        'transportation_means',
        'vehicle_type',
        'destination',
        'departure_date',
        'return_date',
        'report_to',
        'purpose_of_trip',
        'accommodation_type',
        'approved_by',
        'funds_available',
    
        
    ];
    protected $casts = [
        'purpose_of_trip' => 'array',
        'destination' => 'array',
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
}


