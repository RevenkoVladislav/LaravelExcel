<?php

namespace App\ImportStrategy;

use App\Models\Task;

interface ImportStrategy
{
    public function import(Task $task, string $path): void;
}
