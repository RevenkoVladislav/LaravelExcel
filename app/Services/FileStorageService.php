<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    /**
     *  Сохраняем файл в Storage
     *  Сохраняем данные о файле в бд
     *  Возвращаем путь к файлу
     */
    public function storeUploadedFile(UploadedFile $file): File
    {
        $path = Storage::disk('public')->putFile('files/', $file);

        return File::create([
            'path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'filename' => $file->getClientOriginalName(),
        ]);
    }
}
