<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelImport implements ToCollection
{

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            dd($row);
        }
    }
}
