<?php

namespace App\Services;

use App\Models\FailedRow;
use App\Models\Task;

class ImportFailureService
{
    /**
     * Массова вставка ошибок в таблицу FailedRows
     * Обновление статуса для Task - ошибка
     */
    public static function insertFailedRows(Task $task, array $errors): void
    {
        FailedRow::insert($errors);
        $task->update(['status' => Task::STATUS_ERROR]);
    }
}
