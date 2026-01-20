<?php

namespace App\Console\Commands;

use App\Imports\ExcelDynamicImport;
use App\Imports\ExcelImport;
use App\Models\Task;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class TestingCommand extends Command
{
    protected $signature = 'testing';

    protected $description = 'Command for testing excel import';

    public function handle()
    {
        Excel::import(new ExcelDynamicImport(Task::find(8)), 'files/projects2.xlsx', 'public');
        return Command::SUCCESS;
    }
}
