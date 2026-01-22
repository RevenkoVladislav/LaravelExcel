<?php

namespace App\Resolvers;

use App\Enums\ImportType;
use App\ImportStrategy\DynamicImportStrategy;
use App\ImportStrategy\ImportStrategy;
use App\ImportStrategy\StaticImportStrategy;

class ImportStrategyResolver
{
    /**
     * Разрешающий метод, который определяет какой тип Import нужно запустить при загрузке файла
     */
    public function resolve(ImportType $importType): ImportStrategy
    {
        return match ($importType) {
            ImportType::STATIC => app(StaticImportStrategy::class),
            ImportType::DYNAMIC => app(DynamicImportStrategy::class),
        };
    }
}
