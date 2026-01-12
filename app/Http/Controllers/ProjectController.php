<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ImportStoreRequest;
use App\Models\File;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return inertia('Project/Index');
    }

    public function import()
    {
        return inertia('Project/Import');
    }

    /**
     * Сохранение файла через сервис
     */
    public function importStore(ImportStoreRequest $request, FileStorageService $service)
    {
        $file = $service->storeUploadedFile($request->file('file'));
    }
}
