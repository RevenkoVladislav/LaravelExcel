<?php

namespace App\Imports;

use App\Factory\ProjectFactory;
use App\Models\Project;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelImport implements ToCollection, WithHeadingRow
{
    /**
     * Получаем названия из таблицы Types
     *
     * Проходимся по строкам в загруженном файле
     * Если поле - наименование - пустое, то продолжить (нужно чтобы в бд не попали пустые строки)
     *
     * Проходимся в цикле и вызываем метод make у фабрики
     */

    public function collection(Collection $collection)
    {
        $typesMap = $this->getTypesMap(Type::all());

        foreach ($collection as $row) {
            if (!isset($row['naimenovanie'])) continue;

            $projectFactory = ProjectFactory::make($typesMap, $row);
            dd($projectFactory->getValues());
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
}
