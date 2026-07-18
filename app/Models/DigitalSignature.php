<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Common\ErrorCorrectionLevel;

class DigitalSignature extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'label',
        'esignature_hash',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this signature is in use by any approved travel order.
     */
    public function isInUse(): bool
    {
        return TravelOrder::where('esignature_hash', $this->esignature_hash)
            ->orWhere('esignature_recommender_hash', $this->esignature_hash)
            ->exists();
    }

    /**
     * Get formatted signature hash for display.
     */
    public function getFormattedHashAttribute(): string
    {
        return implode(' ', str_split($this->esignature_hash, 4));
    }

    /**
     * Generate QR code PNG from the esignature hash.
     */
    public function generateQrCodePng(): ?string
    {
        if (empty($this->esignature_hash)) {
            return null;
        }

        $renderer = new GDLibRenderer(300);
        $writer = new Writer($renderer);
        $pngData = $writer->writeString(
            $this->esignature_hash,
            Encoder::DEFAULT_BYTE_MODE_ENCODING,
            ErrorCorrectionLevel::H()
        );

        $resource = imagecreatefromstring($pngData);
        if ($resource === false) {
            return 'data:image/png;base64,' . base64_encode($pngData);
        }

        // Overlay user initials
        $user = $this->user;
        if ($user && $user->profile) {
            $initials = strtoupper(substr($user->profile->first_name, 0, 1))
                . strtoupper(substr($user->profile->middle_name ?? '', 0, 1))
                . strtoupper(substr($user->profile->last_name, 0, 1));
        } else {
            $initials = strtoupper(substr($user->username ?? 'U', 0, 3));
        }

        $width = imagesx($resource);
        $height = imagesy($resource);
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

        ob_start();
        imagepng($resource);
        $finalPng = ob_get_clean();
        imagedestroy($resource);

        return 'data:image/png;base64,' . base64_encode($finalPng);
    }
}
