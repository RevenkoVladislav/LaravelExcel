<?php

namespace App\Http\Controllers;

use App\Http\Resources\Task\FailedRowResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\FailedRow;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Получаем Таски вместе с пользователями и файлами
     * Формируем ресурс
     * Отдаем на фронт
     */
    public function index()
    {
        $tasks = Task::with(['user', 'file'])->get();
        $tasks = TaskResource::collection($tasks)->resolve();

        return inertia('Task/Index', [
            'tasks' => $tasks,
        ]);
    }

    /**
     *
     */
    public function failedList(Task $task)
    {
        $failedList = FailedRow::with('task')->get();
        $failedList = FailedRowResource::collection($failedList)->resolve();

        return inertia('Task/FailedList', [
            'failedList' => $failedList,
        ]);
    }
}
