<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/terms', 'static.terms')->name('terms');
Route::view('/privacy', 'static.privacy')->name('privacy');

// 公開プロフィールページ（slug は SlugGenerator::RESERVED で除外済の語のみ許可される）
Route::get('/u/{slug}', [PublicProfileController::class, 'show'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->name('public.profile');

// ローカル開発用：メール認証スキップ。
// MAIL_MAILER=log で認証リンクが見えない状態を回避するため、APP_ENV=local の時だけ有効。
if (app()->environment('local')) {
    Route::post('/dev/verify', function () {
        $user = auth()->user();
        if ($user && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return redirect()->route('dashboard');
    })->middleware('auth')->name('dev.verify');
}

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
