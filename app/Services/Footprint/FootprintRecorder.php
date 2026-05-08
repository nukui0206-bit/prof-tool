<?php

namespace App\Services\Footprint;

use App\Models\Footprint;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Carbon;

class FootprintRecorder
{
    /**
     * 訪問を記録する。
     * 自分自身のプロフィールへの訪問は記録しない。
     * 24h 以内に同じ訪問者の足あとがあれば visited_at を UPDATE で集約。
     * それ以外は新規 INSERT。
     */
    public function record(User $visitor, Profile $profile): void
    {
        if ($visitor->id === $profile->user_id) {
            return;
        }

        $threshold = Carbon::now()->subDay();
        $now = Carbon::now();

        $latest = Footprint::where('profile_id', $profile->id)
            ->where('visitor_user_id', $visitor->id)
            ->where('visited_at', '>=', $threshold)
            ->latest('visited_at')
            ->first();

        if ($latest) {
            $latest->update(['visited_at' => $now]);
        } else {
            Footprint::create([
                'profile_id' => $profile->id,
                'visitor_user_id' => $visitor->id,
                'visited_at' => $now,
            ]);
        }
    }
}
