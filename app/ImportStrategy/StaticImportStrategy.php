<?php

namespace App\ImportStrategy;

use App\Imports\ExcelImport;
use App\Models\Task;
use Maatwebsite\Excel\Facades\Excel;

class StaticImportStrategy implements ImportStrategy
{
    public function import(Task $task, string $path): void
    {
        Excel::import(new ExcelImport($task), $path, 'public');
    }
}

