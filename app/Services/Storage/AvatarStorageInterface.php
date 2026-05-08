<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;

/**
 * アバター画像の保存・削除・URL 取得を抽象化する。
 * MVP は LocalAvatarStorage（storage/app/public/avatars/）。
 * 本番運用拡大時に Cloudflare R2 / S3 への実装に差し替え予定。
 */
interface AvatarStorageInterface
{
    /**
     * アップロードされた画像を保存し、DB の avatar_path カラムに入れる相対パスを返す。
     */
    public function store(UploadedFile $file, int $profileId): string;

    /**
     * 既存のアバター画像を削除する。存在しないパスは何もしない。
     */
    public function delete(string $path): void;

    /**
     * 公開 URL を返す。
     */
    public function url(string $path): string;
}
