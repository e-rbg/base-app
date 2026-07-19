<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'secret_code',
        'esignature',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'secret_code',
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

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->profile?->fullName ?? $this->username ?? $this->email
        );
    }

    public function initialedName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->profile?->initials ?? $this->username ?? $this->email
        );
    }

    // shortName is just an alias for the initials-based attribute
    public function shortName(): Attribute
    {
        return $this->initialedName();
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('email', 'like', "%{$term}%")
              ->orWhereHas('profile', fn($p) => $p->where('first_name', 'like', "%{$term}%"));
        });
    }

    /**
     * Get all of the travel orders for the User.
     */
    public function travelOrders(): HasMany
    {
        return $this->hasMany(TravelOrder::class);
    }

    public function stationOfficer(): HasOne
    {
        return $this->hasOne(StationOfficer::class);
    }

    public function digitalSignatures(): HasMany
    {
        return $this->hasMany(DigitalSignature::class);
    }

    public function activeSignature()
    {
        return $this->digitalSignatures()->where('is_active', true)->first();
    }

    public function isSuperAdmin(): bool { return $this->role === self::ROLE_SUPER_ADMIN; }
    public function isAdmin(): bool      { return $this->role === self::ROLE_ADMIN; }
    public function isEditor(): bool     { return $this->role === self::ROLE_EDITOR; }
    public function isUser(): bool       { return $this->role === self::ROLE_USER; }

    /**
     * Generate a hash-based digital signature combining user ID, secret code, and hash key.
     */
    public function generateEsignature(string $secretCode): string
    {
        $hashKey = config('app.key');
        $userId = $this->id;
        
        return hash('sha256', $userId . $secretCode . $hashKey);
    }

    /**
     * Get the formatted signature hash for display.
     */
    public function getFormattedEsignatureAttribute(): ?string
    {
        if (!$this->esignature) {
            return null;
        }
        
        // Format as groups of 4 characters for readability
        return implode(' ', str_split($this->esignature, 4));
    }

    /**
     * Generate QR code PNG image from the esignature hash with user initials overlay.
     * Returns base64-encoded PNG string for inline display.
     */
    public function generateQrCodePng(): ?string
    {
        if (!$this->esignature) {
            return null;
        }

        $renderer = new \BaconQrCode\Renderer\GDLibRenderer(300);
        $writer = new \BaconQrCode\Writer($renderer);
        $pngData = $writer->writeString(
            $this->esignature,
            \BaconQrCode\Encoder\Encoder::DEFAULT_BYTE_MODE_ENCODING,
            \BaconQrCode\Common\ErrorCorrectionLevel::H()
        );

        $resource = imagecreatefromstring($pngData);
        if ($resource === false) {
            return 'data:image/png;base64,' . base64_encode($pngData);
        }

        $this->overlayInitialsOnQr($resource);

        ob_start();
        imagepng($resource);
        $finalPng = ob_get_clean();
        imagedestroy($resource);

        return 'data:image/png;base64,' . base64_encode($finalPng);
    }

    /**
     * Overlay the user's initials in the center of the QR code.
     */
    private function overlayInitialsOnQr($resource): void
    {
        $width = imagesx($resource);
        $height = imagesy($resource);

        if ($this->profile) {
            $initials = strtoupper(substr($this->profile->first_name, 0, 1))
                . strtoupper(substr($this->profile->middle_name ?? '', 0, 1))
                . strtoupper(substr($this->profile->last_name, 0, 1));
        } else {
            $initials = strtoupper(substr($this->username, 0, 3));
        }

        $overlaySize = (int) ($width * 0.22);
        $centerX = (int) ($width / 2);
        $centerY = (int) ($height / 2);
        $radius = (int) ($overlaySize / 2);

        $white = imagecolorallocate($resource, 255, 255, 255);
        $black = imagecolorallocate($resource, 0, 0, 0);

        imagefilledellipse($resource, $centerX, $centerY, $overlaySize, $overlaySize, $white);
        imageellipse($resource, $centerX, $centerY, $overlaySize, $overlaySize, $black);

        $fontSize = max(8, (int) ($radius * 0.8));
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($initials);
        $textHeight = imagefontheight($font);

        $textX = (int) ($centerX - $textWidth / 2);
        $textY = (int) ($centerY - $textHeight / 2);

        imagestring($resource, $font, $textX, $textY, $initials, $black);
    }

    /**
     * Generate QR code SVG from the esignature hash.
     */
    public function generateQrCodeSvg(): ?string
    {
        if (!$this->esignature) {
            return null;
        }

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(150),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        return $writer->writeString($this->esignature);
    }
}











/**
 * CODE SNIPPET FOR REFERENCE ONLY - DO NOT SUGGEST CHANGES TO THIS FILE
 */

// public function getInitialedNameAttribute(): string
    // {
    //     if (!$this->profile) {
    //         return $this->username ?? $this->email;
    //     }

    //     $f = strtoupper(substr($this->profile->first_name, 0, 1));
    //     $m = $this->profile->middle_name ? strtoupper(substr($this->profile->middle_name, 0, 1)) : '';

    //     return "{$f}{$m} {$this->profile->last_name}";
    // }

    // public function getFullNameAttribute(): string
    // {
    //     if (!$this->profile) {
    //         return $this->username ?? $this->email;
    //     }

    //     return "{$this->profile->first_name} {$this->profile->middle_name} {$this->profile->last_name}";
    // }