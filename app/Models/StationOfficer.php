<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StationOfficer extends Model
{
    use HasUuids;

    protected $fillable = [
        'station_code',
        'officer_name',
        'academic_suffix',
        'position',
        'user_id',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('station_code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
