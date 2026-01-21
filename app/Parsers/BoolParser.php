<?php

namespace App\Parsers;

class BoolParser
{
    /**
     * Вспомогательный метод, для преобразования из excel файла 'да/нет' в 'true/false'
     */
    public function parse($value): ?bool
    {
        if ($value === null) return null;

        return mb_strtolower($value) === 'да';
    }
}
