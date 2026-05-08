<?php

namespace App\Services\Like;

use App\Models\Like;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LikeToggler
{
    /**
     * いいねの ON / OFF をトグルする。
     * profile.like_count を同じトランザクション内で increment / decrement する。
     *
     * @return array{liked: bool, count: int}
     */
    public function toggle(User $user, Profile $profile): array
    {
        return DB::transaction(function () use ($user, $profile) {
            $existing = Like::where('profile_id', $profile->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $existing->delete();
                $profile->decrement('like_count');
                $liked = false;
            } else {
                Like::create([
                    'profile_id' => $profile->id,
                    'user_id' => $user->id,
                ]);
                $profile->increment('like_count');
                $liked = true;
            }

            $profile->refresh();
            return ['liked' => $liked, 'count' => (int) $profile->like_count];
        });
    }

    public function isLiked(?User $user, Profile $profile): bool
    {
        if (! $user) {
            return false;
        }
        return Like::where('profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->exists();
    }
}
