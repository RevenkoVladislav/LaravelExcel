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
        $tasks = Task::with(['user', 'file'])->withCount('failedRows')->paginate(5);

        return inertia('Task/Index', [
            'tasks' => TaskResource::collection($tasks)->resolve(),
        ]);
    }

    /**
     * Получаем ошибки по конкретному таску
     * Передаем все на фронт
     */
    public function failedList(Task $task)
    {
        $failedList = $task->failedRows()->paginate(10);

        return inertia('Task/FailedList', [
            'failedList' => FailedRowResource::collection($failedList)->resolve(),
        ]);
    }
}
