<?php

namespace App\Builder;

use App\DTO\ProjectDTO;
use App\Parsers\BoolParser;
use App\Parsers\DateParser;
use App\Resolvers\TypeResolver;
use Illuminate\Support\Collection;

class ProjectRowBuilder
{
    /**
     * Прокидываем резолверы и парсеры для преобразования данных
     *
     */
    public function __construct(
        private TypeResolver $typeResolver,
        private DateParser   $dateParser,
        private BoolParser   $boolParser,
    ) {}

    public function build(Collection $row): ProjectDTO
    {
        return new ProjectDTO(
            typeId: $this->typeResolver->resolve($row['tip']),
            title: $row['naimenovanie'],
            creationDate: $this->dateParser->parse($row['data_sozdaniia']),
            contractedDate: $this->dateParser->parse($row['podpisanie_dogovora']),
            deadline: $this->dateParser->parse($row['dedlain']) ?? null,
            isChain: $this->boolParser->parse($row['setevik']) ?? null,
            isOnTime: $this->boolParser->parse($row['sdaca_v_srok']) ?? null,
            hasOutsource: $this->boolParser->parse($row['nalicie_autsorsinga']) ?? null,
            hasInvestors: $this->boolParser->parse($row['nalicie_investorov']) ?? null,
            workersCount: $row['kolicestvo_ucastnikov'] ?? null,
            servicesCount: $row['kolicestvo_uslug'] ?? null,
            paymentFirstStep: $row['vlozenie_v_pervyi_etap'] ?? null,
            paymentSecondStep: $row['vlozenie_vo_vtoroi_etap'] ?? null,
            paymentThirdStep: $row['vlozenie_v_tretii_etap'] ?? null,
            paymentFourthStep: $row['vlozenie_v_cetvertyi_etap'] ?? null,
            comment: $row['kommentarii'] ?? null,
            efficiencyValue: $row['znacenie_effektivnosti'] ?? null,
        );
    }
}

