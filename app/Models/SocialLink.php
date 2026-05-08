<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    public const PLATFORM_X = 'x';
    public const PLATFORM_INSTAGRAM = 'instagram';
    public const PLATFORM_TIKTOK = 'tiktok';
    public const PLATFORM_YOUTUBE = 'youtube';
    public const PLATFORM_OTHER = 'other';

    public const PLATFORMS = [
        self::PLATFORM_X => 'X (Twitter)',
        self::PLATFORM_INSTAGRAM => 'Instagram',
        self::PLATFORM_TIKTOK => 'TikTok',
        self::PLATFORM_YOUTUBE => 'YouTube',
        self::PLATFORM_OTHER => 'その他',
    ];

    public const ICONS = [
        self::PLATFORM_X => 'bi-twitter-x',
        self::PLATFORM_INSTAGRAM => 'bi-instagram',
        self::PLATFORM_TIKTOK => 'bi-tiktok',
        self::PLATFORM_YOUTUBE => 'bi-youtube',
        self::PLATFORM_OTHER => 'bi-link-45deg',
    ];

    protected $fillable = ['profile_id', 'platform', 'url', 'label', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function platformLabel(): string
    {
        return self::PLATFORMS[$this->platform] ?? $this->platform;
    }

    public function iconClass(): string
    {
        return self::ICONS[$this->platform] ?? 'bi-link-45deg';
    }

    public function displayLabel(): string
    {
        return $this->label !== null && $this->label !== ''
            ? $this->label
            : $this->platformLabel();
    }
}
