<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FailedRow extends Model
{
    protected $table = 'failed_rows';
    protected $fillable = [
        'key',
        'row',
        'message',
        'task_id',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
