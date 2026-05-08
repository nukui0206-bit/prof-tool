<?php

namespace App\Providers;

use App\Services\Storage\AvatarStorageInterface;
use App\Services\Storage\LocalAvatarStorage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AvatarStorageInterface::class,
            LocalAvatarStorage::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
