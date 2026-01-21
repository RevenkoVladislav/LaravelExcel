<?php

namespace App\Factory;

use App\Models\Payment;

class PaymentFactory
{
    public function sync(array $dtos): void
    {
        foreach ($dtos as $dto) {
            Payment::updateOrCreate(
                [
                    'project_id' => $dto->projectId,
                    'title' => $dto->title,
                ],
                [
                    'value' => $dto->value,
                ]
            );
        }
    }
}
