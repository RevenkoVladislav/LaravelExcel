<?php

namespace App\ImportStrategy;

use App\Imports\ExcelDynamicImport;
use App\Models\Task;
use Maatwebsite\Excel\Facades\Excel;

class DynamicImportStrategy implements ImportStrategy
{
    public function import(Task $task, string $path): void
    {
        Excel::import(new ExcelDynamicImport($task), $path, 'public');
    }
}
