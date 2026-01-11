<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = [
        'path',
        'extension',
        'filename',
    ];

    /**
     * Сохраняем файл в Storage
     * Сохраняем данные о файле в бд
     * Возвращаем путь к файлу
     */
    public static function putAndCreate($dataFile)
    {
        $file = Storage::disk('public')->putFile('files/', $dataFile);

        File::create([
            'path' => $file,
            'extension' => $dataFile->getClientOriginalExtension(),
            'filename' => $dataFile->getClientOriginalName(),
        ]);

        return $file;
    }
}
