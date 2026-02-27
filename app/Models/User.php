<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Important for UUIDs
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'last_login_at',
        'last_login_ip',
        'verification_code',
        'verification_sent_at',
        'unverified_email',
        'email_verified_at',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'verification_sent_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'string',
        ];
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Accessor for the "BJDB Makatigbas" format.
     * Usage: $user->initialed_name
     */
    public function getInitialedNameAttribute(): string
    {
        if (!$this->profile) {
            return $this->username ?? $this->email;
        }

        $f = strtoupper(substr($this->profile->first_name, 0, 1));
        $m = $this->profile->middle_name ? strtoupper(substr($this->profile->middle_name, 0, 1)) : '';
        
        return "{$f}{$m} {$this->profile->last_name}";
    }

    public function getFullNameAttribute(): string
    {
        if (!$this->profile) {
            return $this->username ?? $this->email;
        }

        return "{$this->profile->first_name} {$this->profile->middle_name} {$this->profile->last_name}";
    }


    public function shortName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $profile = $this->profile; // Assuming a 'profile' relationship exists
                
                if (!$profile) return $this->username ?? $this->email;

                $fInitial = strtoupper(substr($profile->first_name, 0, 1));
                $mInitial = $profile->middle_name ? strtoupper(substr($profile->middle_name, 0, 1)) : '';
                
                // This handles multiple first names if you keep them in one column,
                // or just the clean initials if you have a middle_name column.
                return "{$fInitial}{$mInitial} {$profile->last_name}";
            },
        );
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('email', 'like', "%{$term}%")
            ->orWhereHas('profile', fn($p) => $p->where('first_name', 'like', "%{$term}%"));
        });
    }
}
