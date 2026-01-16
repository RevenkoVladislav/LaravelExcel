<?php

namespace App\Factory;

use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectFactory
{
    public function __construct(
        private int                   $typeId,
        private string                $title,
        private Carbon|\DateTime      $creationDate,
        private Carbon|\DateTime      $contractedDate,
        private null|Carbon|\DateTime $deadline,
        private ?bool                 $isChain,
        private ?bool                 $isOnTime,
        private ?bool                 $hasOutsource,
        private ?bool                 $hasInvestors,
        private ?int                  $workersCount,
        private ?int                  $servicesCount,
        private ?float                $paymentFirstStep,
        private ?float                $paymentSecondStep,
        private ?float                $paymentThirdStep,
        private ?float                $paymentFourthStep,
        private ?string               $comment,
        private ?float                $efficiencyValue,
    ) {}

    /**
     * Прокидываем массив наименований типов из таблицы Types, и row из Excel импорт
     *
     * Получаем id для Type
     * Преобразовываем дату в нужный формат
     * Преобразовываем да/нет в true/false
     */
    public static function make(array $map, Collection $row): ProjectFactory
    {
        return new self(
            self::getTypeId($map, $row['tip']),
            $row['naimenovanie'],
            self::getDate($row['data_sozdaniia']),
            self::getDate($row['podpisanie_dogovora']),
            isset($row['dedlain']) ? self::getDate($row['dedlain']) : null,
            isset($row['setevik']) ? self::getBool($row['setevik']) : null,
            isset($row['sdaca_v_srok']) ? self::getBool($row['sdaca_v_srok']) : null,
            isset($row['nalicie_autsorsinga']) ? self::getBool($row['nalicie_autsorsinga']) : null,
            isset($row['nalicie_investorov']) ? self::getBool($row['nalicie_investorov']) : null,
            $row['kolicestvo_ucastnikov'] ?? null,
            $row['kolicestvo_uslug'] ?? null,
            $row['vlozenie_v_pervyi_etap'] ?? null,
            $row['vlozenie_vo_vtoroi_etap'] ?? null,
            $row['vlozenie_v_tretii_etap'] ?? null,
            $row['vlozenie_v_cetvertyi_etap'] ?? null,
            $row['kommentarii'] ?? null,
            $row['znacenie_effektivnosti'] ?? null,
        );
    }

    /**
     * Проверяем, есть ли тип в таблице Types
     * Если есть - вернем его
     * Если нет создадим и вернем его id
     */
    private static function getTypeId(array &$map, string $title): int
    {
        if (isset($map[$title])) {
            return $map[$title];
        };

        $type = Type::firstOrCreate(['title' => $title]);
        $map[$title] = $type->id;

        return $type->id;
    }

    /**
     * Метод для формирования даты при импорте excel
     * Если нет значения value то вернем null
     * Если это число то преобразуем через excelToDate
     * Если строка - то преобразуем через Carbon
     */
    private static function getDate($value): null|Carbon|\DateTime
    {
        if (!$value) return null;

        return is_numeric($value)
            ? Date::excelToDateTimeObject($value)
            : Carbon::parse($value);
    }

    /**
     * Вспомогательный метод, для преобразования из excel файла 'да/нет' в 'true/false'
     */
    private static function getBool($item): bool
    {
        return $item === "Да";
    }

    /**
     * Получаем все свойства у созданного объекта в виде массива
     * Проходимся в цикле по всем элементам массива
     * Меняем ключи из camelCase в snake_case стиль
     */
    public function getValues(): array
    {
        $props = get_object_vars($this);
        $result = [];

        foreach ($props as $key => $prop) {
            $result[Str::snake($key)] = $prop;
        }

        return $result;
    }

    /**
     * Возвращаем уникальные ключи для защиты от дублирования
     */
    public function getUniqueKeys(): array
    {
        return [
            'type_id' => $this->typeId,
            'title' => $this->title,
            'creation_date' => $this->creationDate,
            'contracted_date' => $this->contractedDate,
        ];
    }
}
