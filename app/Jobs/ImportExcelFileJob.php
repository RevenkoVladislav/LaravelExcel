<?php

namespace App\Jobs;

use App\Imports\ExcelImport;
use App\Models\Task;
use App\Services\ImportFailureService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelFileJob implements ShouldQueue
{
    use Queueable;

    private string $path;
    private Task $task;

    public function __construct(string $path, Task $task)
    {
        $this->path = $path;
        $this->task = $task;
    }

    /**
     * Сюда прокидываем успешный статус
     * В случае ошибки мы его поменяем в ExcelImport и запишем Task с ошибкой
     * т.к ошибки не останавливают импорт, то успешный статус нужно прокинуть именно здесь
     * а в случае ошибки полностью поменять статус для всего Task
     */
    public function handle(): void
    {
        $this->task->update(['status' => Task::STATUS_SUCCESS]);
        Excel::import(new ExcelImport($this->task), $this->path, 'public');
    }
}
