<?php

namespace App\Factory;

use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectDynamicFactory
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
    public static function make(array $map, array $row): ProjectDynamicFactory
    {
        return new self(
            self::getTypeId($map, $row[0]),
            $row[1],
            self::getDate($row[2]),
            self::getDate($row[9]),
            isset($row[7]) ? self::getDate($row[7]) : null,
            isset($row[3]) ? self::getBool($row[3]) : null,
            isset($row[8]) ? self::getBool($row[8]) : null,
            isset($row[5]) ? self::getBool($row[5]) : null,
            isset($row[6]) ? self::getBool($row[6]) : null,
            $row[4] ?? null,
            $row[10] ?? null,
            $row[11] ?? null,
            $row[12] ?? null,
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
