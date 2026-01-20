<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ImportStoreRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Jobs\ImportExcelFileJob;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('type')->paginate(10);

        return inertia('Project/Index', [
            'projects' => ProjectResource::collection($projects),
        ]);
    }

    public function import()
    {
        return inertia('Project/Import');
    }

    /**
     * Сохранение файла через сервис
     * Создаем task
     * Выполняем Job для чтение excel файла
     */
    public function importStore(ImportStoreRequest $request, FileStorageService $service)
    {
        $file = $service->storeUploadedFile($request->file('file'));
        $task = Task::create([
            'user_id' => auth()->id(),
            'file_id' => $file->id,
        ]);
        ImportExcelFileJob::dispatchSync($file->path, $task);
    }
}
