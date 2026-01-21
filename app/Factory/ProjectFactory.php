<?php

namespace App\Factory;

use App\DTO\ProjectDTO;
use App\Models\Project;
class ProjectFactory
{
    public function create(ProjectDTO $dto): Project
    {
        return Project::updateOrCreate(
            $dto->unique(),
            [
                'deadline' => $dto->getDeadline(),
                'is_chain' => $dto->isChain(),
                'is_on_time' => $dto->isOnTime(),
                'has_outsource' => $dto->hasOutsource(),
                'has_investors' => $dto->hasInvestors(),

                'workers_count' => $dto->getWorkersCount(),
                'services_count' => $dto->getServicesCount(),

                'payment_first_step' => $dto->getPaymentFirstStep(),
                'payment_second_step' => $dto->getPaymentSecondStep(),
                'payment_third_step' => $dto->getPaymentThirdStep(),
                'payment_fourth_step' => $dto->getPaymentFourthStep(),

                'comment' => $dto->getComment(),
                'efficiency_value' => $dto->getEfficiencyValue(),
            ]
        );
    }
}
