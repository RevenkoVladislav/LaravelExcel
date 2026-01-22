<?php

namespace App\Jobs;

use App\Models\Task;
use App\Resolvers\ImportStrategyResolver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
     * Запуск импорта excel файла в систему
     * Через resolver определяем какой тип импорта будет загружен (статичный или динамичный)
     *
     * Сюда прокидываем успешный статус
     * В случае ошибки мы его поменяем в ExcelImport и запишем Task с ошибкой
     * т.к ошибки не останавливают импорт, то успешный статус нужно прокинуть именно здесь
     * а в случае ошибки полностью поменять статус для всего Task
     *
     * Запускаем импорт
     */
    public function handle(ImportStrategyResolver $resolver): void
    {
        $strategy = $resolver->resolve($this->task->import_type);

        $this->task->update(['status' => Task::STATUS_SUCCESS]);

        $strategy->import($this->task, $this->path);
    }
}
