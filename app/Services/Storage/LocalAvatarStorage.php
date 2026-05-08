<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalAvatarStorage implements AvatarStorageInterface
{
    private const DISK = 'public';
    private const DIR = 'avatars';

    public function store(UploadedFile $file, int $profileId): string
    {
        $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'jpg');
        $name = sprintf('%d_%s.%s', $profileId, Str::random(10), $ext);
        return $file->storeAs(self::DIR, $name, self::DISK);
    }

    public function delete(string $path): void
    {
        if ($path === '') {
            return;
        }
        Storage::disk(self::DISK)->delete($path);
    }

    public function url(string $path): string
    {
        return Storage::disk(self::DISK)->url($path);
    }
}
