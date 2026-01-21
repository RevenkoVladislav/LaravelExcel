<?php

namespace App\DTO;

class PaymentDTO
{
    public function __construct(
        public int $projectId,
        public string $title,
        public float $value,
    ) {}
}
