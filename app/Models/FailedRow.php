<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedRow extends Model
{
    protected $table = 'failed_rows';
    protected $fillable = [
        'key',
        'row',
        'message',
        'task_id',
    ];

    public static function insertFailedRows(array $rows, Task $task): void
    {
        FailedRow::insert($rows);
        $task->update(['status' => Task::STATUS_ERROR]);
    }
}
