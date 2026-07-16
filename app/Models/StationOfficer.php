<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class StationOfficer extends Model
{
    use HasUuids;

    protected $fillable = [
        'station_code',
        'officer_name',
        'position',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('station_code');
    }
}
