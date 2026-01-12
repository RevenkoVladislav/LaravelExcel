<?php

namespace App\Imports;

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
     * Создаем запись в бд прокинув туда следующие данные:
     * Получаем id для Type
     * Преобразовываем дату в нужный формат
     * Преобразовываем да/нет в true/false
     */

    public function collection(Collection $collection)
    {
        $typesMap = $this->getTypesMap(Type::all());

        foreach ($collection as $row) {
            if (!isset($row['naimenovanie'])) continue;

            Project::create([
                'type_id' => $this->getTypeId($typesMap, $row['tip']),
                'title' => $row['naimenovanie'],
                'creation_date' => $this->getDate($row['data_sozdaniia']),
                'contracted_date' => $this->getDate($row['podpisanie_dogovora']),
                'deadline' => isset($row['dedlain']) ? $this->getDate($row['dedlain']) : null,
                'is_chain' => isset($row['setevik']) ? $this->getBool($row['setevik']) : null,
                'is_on_time' => isset($row['sdaca_v_srok']) ? $this->getBool($row['sdaca_v_srok']) : null,
                'has_outsource' => isset($row['nalicie_autsorsinga']) ? $this->getBool($row['nalicie_autsorsinga']) : null,
                'has_investors' => isset($row['nalicie_investorov']) ? $this->getBool($row['nalicie_investorov']) : null,
                'workers_count' => $row['kolicestvo_ucastnikov'] ?? null,
                'services_count' => $row['kolicestvo_uslug'] ?? null,
                'payment_first_step' => $row['vlozenie_v_pervyi_etap'] ?? null,
                'payment_second_step' => $row['vlozenie_vo_vtoroi_etap'] ?? null,
                'payment_third_step' => $row['vlozenie_v_tretii_etap'] ?? null,
                'payment_fourth_step' => $row['vlozenie_v_cetvertyi_etap'] ?? null,
                'comment' => $row['kommentarii'] ?? null,
                'efficiency_value' => $row['znacenie_effektivnosti'] ?? null,
            ]);
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
     * Проверяем, есть ли тип в таблице Types
     * Если есть - вернем его
     * Если нет создадим и вернем его id
     */
    private function getTypeId(array &$map, string $title): int
    {
        if (isset($map[$title])){
            return $map[$title];
        };

        $type = Type::create(['title' => $title]);
        $map[$title] = $type->id;

        return $type->id;
    }

    /**
     * Вспомогательный метод, для преобразования из excel файла 'да/нет' в 'true/false'
     */
    private function getBool($item): bool
    {
        return $item === "Да" ? true : false;
    }

    /**
     * Метод для формирования даты при импорте excel
     * Если нет значения value то вернем null
     * Если это число то преобразуем через excelToDate
     * Если строка - то преобразуем через Carbon
     */
    private function getDate($value)
    {
        if (!$value) return null;

        return is_numeric($value)
            ? Date::excelToDateTimeObject($value)
            : Carbon::parse($value);
    }
}
