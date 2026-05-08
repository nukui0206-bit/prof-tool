<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\Mypage\AnswerController as MypageAnswerController;
use App\Http\Controllers\Mypage\FavoriteController as MypageFavoriteController;
use App\Http\Controllers\Mypage\FootprintController as MypageFootprintController;
use App\Http\Controllers\Mypage\ProfileController as MypageProfileController;
use App\Http\Controllers\Mypage\SocialLinkController as MypageSocialLinkController;
use App\Http\Controllers\Mypage\ThemeController as MypageThemeController;
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

// いいねトグル（ログイン必須、Ajax JSON）
Route::post('/u/{slug}/like', [LikeController::class, 'toggle'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->middleware('auth')
    ->name('public.profile.like');

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

Route::middleware(['auth', 'verified'])->prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/profile', [MypageProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [MypageProfileController::class, 'update'])->name('profile.update');

    Route::get('/answers', [MypageAnswerController::class, 'edit'])->name('answers.edit');
    Route::patch('/answers', [MypageAnswerController::class, 'update'])->name('answers.update');

    Route::get('/favorites', [MypageFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [MypageFavoriteController::class, 'store'])->name('favorites.store');
    Route::patch('/favorites/reorder', [MypageFavoriteController::class, 'reorder'])->name('favorites.reorder');
    Route::patch('/favorites/{favorite}', [MypageFavoriteController::class, 'update'])->name('favorites.update');
    Route::delete('/favorites/{favorite}', [MypageFavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/links', [MypageSocialLinkController::class, 'index'])->name('links.index');
    Route::post('/links', [MypageSocialLinkController::class, 'store'])->name('links.store');
    Route::patch('/links/reorder', [MypageSocialLinkController::class, 'reorder'])->name('links.reorder');
    Route::patch('/links/{link}', [MypageSocialLinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [MypageSocialLinkController::class, 'destroy'])->name('links.destroy');

    Route::get('/theme', [MypageThemeController::class, 'edit'])->name('theme.edit');
    Route::patch('/theme', [MypageThemeController::class, 'update'])->name('theme.update');

    Route::get('/likes', [LikeController::class, 'index'])->name('likes.index');

    Route::get('/footprints', [MypageFootprintController::class, 'index'])->name('footprints.index');
});

require __DIR__.'/auth.php';
