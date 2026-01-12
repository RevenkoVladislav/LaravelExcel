<?php

namespace App\Console\Commands;

use App\Imports\ExcelImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class TestingCommand extends Command
{
    protected $signature = 'testing';

    protected $description = 'Command for testing excel import';

    public function handle()
    {
        Excel::import(new ExcelImport(), 'files/projects.xlsx', 'public');
        return Command::SUCCESS;
    }
}
