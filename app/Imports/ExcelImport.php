<?php

namespace App\Imports;

use App\Factory\ProjectFactory;
use App\Models\FailedRow;
use App\Models\Project;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    /**
     * Получаем названия из таблицы Types
     *
     * Проходимся по строкам в загруженном файле
     * Если поле - наименование - пустое, то продолжить (нужно чтобы в бд не попали пустые строки)
     *
     * Проходимся в цикле и вызываем метод make у фабрики для создания экземпляра класса по каждой строке
     * Передаем массив уникальных ключей и массив всех значений в метод updateOrCreate
     */

    public function collection(Collection $collection)
    {
        $typesMap = $this->getTypesMap(Type::all());

        foreach ($collection as $row) {
            if (!isset($row['naimenovanie'])) continue;

            $projectFactory = ProjectFactory::make($typesMap, $row);

            Project::updateOrCreate(
                $projectFactory->getUniqueKeys(),
                $projectFactory->getValues()
            );
        }
    }

    /**
     * Получаем массив данных из таблицы Types
     * Где ключ - title из таблицы Types, а значение id из таблицы Types
     */
    private function getTypesMap($types): array
    {
        $map = [];
        foreach ($types as $type) {
            $map[$type->title] = $type->id;
        }

        return $map;
    }

    /**
     * Собираем все ошибки в массив $map
     * Проходимся циклом по каждому объекту Failure
     * Проходимся по всем ошибкам и формируем массив, который попадет в map
     * В массив попадают аттрибут ошибки, строка где была ошибка, сообщение об ошибке
     */
    public function onFailure(Failure ...$failures): void
    {
        $map = [];
        foreach ($failures as $failure){
            foreach ($failure->errors() as $error) {
                $map[] = [
                    'key' => $failure->attribute(),
                    'row' => $failure->row(),
                    'message' => "Row - {$failure->row()}: $error",
                    'task_id' => 1, //временное решение, пока не реализована сущность task
                ];
            }
        }

        if (!empty($map)) {
            FailedRow::insertFailedRows($map);
        }
    }

    /**
     * правила валидации для excel import
     */
    public function rules(): array
    {
        return [
            'tip' => 'required|string',
            'naimenovanie' => 'required|string',
            'data_sozdaniia' => 'required|numeric',
            'podpisanie_dogovora' => 'required|numeric',
            'dedlain' => 'nullable|numeric',
            'setevik' => 'nullable|string',
            'nalicie_autsorsinga' => 'nullable|string',
            'nalicie_investorov' => 'nullable|string',
            'sdaca_v_srok' => 'nullable|string',
            'vlozenie_v_pervyi_etap' => 'nullable|integer',
            'vlozenie_vo_vtoroi_etap' => 'nullable|integer',
            'vlozenie_v_tretii_etap' => 'nullable|integer',
            'vlozenie_v_cetvertyi_etap' => 'nullable|integer',
            'kolicestvo_ucastnikov' => 'nullable|integer',
            'kolicestvo_uslug' => 'nullable|integer',
            'kommentarii' => 'nullable|string',
            'znacenie_effektivnosti' => 'nullable|numeric',
        ];
    }
}
