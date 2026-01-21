<?php

namespace App\Parsers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DateParser
{
    /**
     * Метод для формирования даты при импорте excel
     * Если нет значения value то вернем null
     * Если это число то преобразуем через excelToDate
     * Если строка - то преобразуем через Carbon
     */
    public function parse($value): null|Carbon|\DateTime
    {
        if (!$value) return null;

        return is_numeric($value)
            ? Date::excelToDateTimeObject($value)
            : Carbon::parse($value);
    }
}
