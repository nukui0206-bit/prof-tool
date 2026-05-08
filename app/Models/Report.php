<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    public const REASON_SPAM = 'spam';
    public const REASON_OFFENSIVE = 'offensive';
    public const REASON_IMPERSONATION = 'impersonation';
    public const REASON_OTHER = 'other';

    public const REASONS = [
        self::REASON_SPAM => 'スパム・迷惑行為',
        self::REASON_OFFENSIVE => '攻撃的・誹謗中傷',
        self::REASON_IMPERSONATION => 'なりすまし',
        self::REASON_OTHER => 'その他',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [
        self::STATUS_OPEN => '未対応',
        self::STATUS_REVIEWING => '対応中',
        self::STATUS_CLOSED => '対応済',
    ];

    protected $fillable = [
        'reporter_user_id',
        'target_type',
        'target_id',
        'reason',
        'body',
        'status',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    /**
     * 通報対象（profile / 将来の comment 等）。
     * Polymorphic だが target_type は短い文字列で保存する想定（'profile' 等）。
     * 解決は ReportController 側で明示的に行う。
     */
    public function reasonLabel(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
