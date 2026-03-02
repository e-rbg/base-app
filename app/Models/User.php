<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN       = 'admin';
    const ROLE_EDITOR      = 'editor';
    const ROLE_USER        = 'user';

    use HasFactory, Notifiable, HasUuids, SoftDeletes;

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
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'last_login_at'       => 'datetime',
            'verification_sent_at'=> 'datetime',
            'password'            => 'hashed',
            'status'              => 'string',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

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
                $profile = $this->profile;

                if (!$profile) return $this->username ?? $this->email;

                $fInitial = strtoupper(substr($profile->first_name, 0, 1));
                $mInitial = $profile->middle_name ? strtoupper(substr($profile->middle_name, 0, 1)) : '';

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

    public function isSuperAdmin(): bool { return $this->role === self::ROLE_SUPER_ADMIN; }
    public function isAdmin(): bool      { return $this->role === self::ROLE_ADMIN; }
    public function isEditor(): bool     { return $this->role === self::ROLE_EDITOR; }
}