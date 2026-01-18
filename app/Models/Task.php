<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Массив для понятного названия статуса
     */
    public const STATUS_LABELS = [
        self::STATUS_PROCESS => 'Import in progress',
        self::STATUS_SUCCESS => 'Data imported successfully',
        self::STATUS_ERROR => 'Error during import',
    ];

    /**
     * Метод для получения статуса
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'Unknown status';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function failedRows(): HasMany
    {
        return $this->hasMany(FailedRow::class, 'task_id', 'id');
    }
}
