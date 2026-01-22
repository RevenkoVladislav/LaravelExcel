<?php

namespace App\Builder;

use App\DTO\ProjectDTO;
use App\Enums\ImportType;
use App\Parsers\BoolParser;
use App\Parsers\DateParser;
use App\Resolvers\TypeResolver;

class ProjectDynamicRowBuilder
{
    public function __construct(
        private TypeResolver $typeResolver,
        private DateParser   $dateParser,
        private BoolParser   $boolParser,
    ) {}

    public function build(array $row): ProjectDTO
    {
        return new ProjectDTO(
            typeId: $this->typeResolver->resolve($row[0]),
            title: $row[1],
            creationDate: $this->dateParser->parse($row[2]),
            contractedDate: $this->dateParser->parse($row[9]),
            deadline: $this->dateParser->parse($row[7] ?? null),
            isChain: $this->boolParser->parse($row[3] ?? null),
            isOnTime: $this->boolParser->parse($row[8] ?? null),
            hasOutsource: $this->boolParser->parse($row[5] ?? null),
            hasInvestors: $this->boolParser->parse($row[6] ?? null),
            workersCount: $row[4] ?? null,
            servicesCount: $row[10] ?? null,
            paymentFirstStep: null,
            paymentSecondStep: null,
            paymentThirdStep: null,
            paymentFourthStep: null,
            comment: $row[11] ?? null,
            efficiencyValue: $row[12] ?? null,
            import_type: ImportType::DYNAMIC->value,
        );
    }
}
