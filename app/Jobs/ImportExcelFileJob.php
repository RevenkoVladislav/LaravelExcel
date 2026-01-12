<?php

namespace App\Jobs;

use App\Imports\ExcelImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelFileJob implements ShouldQueue
{
    use Queueable;

    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        Excel::import(new ExcelImport(), $this->path, 'public');
    }
}
