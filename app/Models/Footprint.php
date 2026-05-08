<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Footprint extends Model
{
    public $timestamps = false;

    protected $fillable = ['profile_id', 'visitor_user_id', 'visited_at'];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visitor_user_id');
    }
}
