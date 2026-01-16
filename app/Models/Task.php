<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'user_id',
        'file_id',
        'status',
    ];

    const STATUS_PROCESS = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_ERROR = 3;

    public static function getStatus()
    {
        return [
            self::STATUS_PROCESS => 'Импорт в процессе обработки',
            self::STATUS_SUCCESS => 'Импорт данных успешно прошел',
            self::STATUS_ERROR => 'Ошибка во время импорта',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
