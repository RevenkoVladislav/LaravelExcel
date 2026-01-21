<?php

namespace App\Resolvers;

use App\Models\Type;

class TypeResolver
{
    private array $cache = [];

    /**
     * Проверяем, есть ли `тип` в таблице Types
     * Если есть - вернем его
     * Если нет создадим и вернем его id
     */
    public function resolve(string $title): int
    {
        if (isset($this->cache[$title])) {
            return $this->cache[$title];
        }

        return $this->cache[$title] =
            Type::firstOrCreate(['title' => $title])->id;
    }
}
