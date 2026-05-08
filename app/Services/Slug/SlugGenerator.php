<?php

namespace App\Services\Slug;

use App\Models\Profile;
use Illuminate\Support\Str;

class SlugGenerator
{
    /**
     * 公開URL `/u/{slug}` で利用できない予約語。
     * ルート名・静的ページ・将来追加予定ルートも含む。
     */
    public const RESERVED = [
        'admin', 'api', 'auth', 'about',
        'contact', 'confirm-password',
        'dashboard',
        'email', 'explore',
        'forgot-password',
        'help', 'home',
        'index',
        'login', 'logout',
        'mypage',
        'password', 'privacy', 'profile',
        'register', 'report', 'reset-password',
        'search', 'settings', 'static',
        'terms',
        'u',
        'verification',
    ];

    public function isReserved(string $slug): bool
    {
        return in_array(strtolower(trim($slug)), self::RESERVED, true);
    }

    /**
     * 任意のベース文字列から、衝突しないユニークな slug を生成して返す。
     * 日本語など非 ASCII 入力は Str::slug で空になることがあるため、
     * 空・予約語・短すぎる場合はランダム slug にフォールバックする。
     */
    public function generateUnique(?string $base = null): string
    {
        $candidate = $this->normalize($base);

        if ($candidate === '' || $this->isReserved($candidate) || strlen($candidate) < 3) {
            $candidate = 'u' . strtolower(Str::random(8));
        }

        $slug = $candidate;
        $i = 0;
        while (Profile::where('slug', $slug)->exists()) {
            $i++;
            $suffix = (string) $i;
            $maxBase = 50 - strlen($suffix);
            $slug = substr($candidate, 0, $maxBase) . $suffix;

            // 想定外のループ防止：100 回当たったらランダム slug にフォールバック
            if ($i > 100) {
                return 'u' . strtolower(Str::random(10));
            }
        }
        return $slug;
    }

    private function normalize(?string $base): string
    {
        if ($base === null || trim($base) === '') {
            return '';
        }
        $slug = Str::slug($base, '-');
        return strtolower(substr($slug, 0, 50));
    }
}
