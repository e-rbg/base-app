<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension',
        'academic_suffix',
        'academic_titles',
        'position',
        'area_of_assignment',
        'gender',
        'marital_status',
        'spouse',
        'blood_type',
        'address',
        'avatar',
        'timezone',
        'preferences',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'preferences' => 'array',
        'academic_titles' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim("{$this->first_name} {$this->last_name}")
        );
    }

    public function employeeInformation(): HasOne
    {
        return $this->hasOne(EmployeeInformation::class);
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn() => strtoupper(substr($this->first_name,0,1))
                        . ($this->middle_name? strtoupper(substr($this->middle_name,0,1)) : '')
                        . " {$this->last_name}"
        );
    }
}