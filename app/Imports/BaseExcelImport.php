<?php

namespace App\Imports;

use App\Models\Task;
use App\Services\ImportFailureService;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

abstract class BaseExcelImport implements ToCollection, WithValidation, SkipsOnFailure
{
    public function __construct(
        protected Task $task,
    ) {}

    /**
     * Получаем массив данных из таблицы Types
     * Где ключ - title из таблицы Types, а значение id из таблицы Types
     */
    protected function getTypesMap($types): array
    {
        $map = [];
        foreach ($types as $type) {
            $map[$type->title] = $type->id;
        }

        return $map;
    }

    /**
     * Собираем все ошибки в массив $errorsMap
     *
     * Получаем все аттрибуты в $attributes для корректной записи в БД
     * В первом цикле получает значение аттрибута в котором произошла ошибка
     * Если он есть в кастомном массиве значений то берем его иначе берем системное название
     *
     * Проходимся циклом по каждому объекту Failure
     * Проходимся по всем ошибкам и формируем массив, который попадет в errorsMap
     * В массив попадают аттрибут ошибки, строка где была ошибка, сообщение об ошибке
     *
     * Если массив errorsMap не пустой то проводим массовую вставку в бд через сервис
     */
    public function onFailure(Failure ...$failures): void
    {
        $errorsMap = [];
        $attributes = $this->attributeMap();

        foreach ($failures as $failure){
            $attributeKey = $failure->attribute();
            $readableAttribute = $attributes[$attributeKey] ?? $attributeKey;

            foreach ($failure->errors() as $error) {
                $errorsMap[] = [
                    'key' => $readableAttribute,
                    'row' => $failure->row(),
                    'message' => "Row - {$failure->row()}: поле «{$readableAttribute}»: $error",
                    'task_id' => $this->task->id,
                ];
            }
        }

        if (!empty($errorsMap)) {
            ImportFailureService::insertFailedRows($this->task, $errorsMap);
        }
    }

    abstract protected function attributeMap(): array;
}
