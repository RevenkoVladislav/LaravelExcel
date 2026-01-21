<?php

namespace App\DTO;

use Carbon\Carbon;

class ProjectDTO
{
    public function __construct(
        private int                   $typeId,
        private string                $title,
        private Carbon|\DateTime      $creationDate,
        private Carbon|\DateTime      $contractedDate,
        private null|Carbon|\DateTime $deadline,
        private ?bool                 $isChain,
        private ?bool                 $isOnTime,
        private ?bool                 $hasOutsource,
        private ?bool                 $hasInvestors,
        private ?int                  $workersCount,
        private ?int                  $servicesCount,
        private ?float                $paymentFirstStep,
        private ?float                $paymentSecondStep,
        private ?float                $paymentThirdStep,
        private ?float                $paymentFourthStep,
        private ?string               $comment,
        private ?float                $efficiencyValue,
    ) {}

    public function toArray(): array
    {
        return [
            'type_id' => $this->typeId,
            'title' => $this->title,
            'creation_date' => $this->creationDate,
            'contracted_date' => $this->contractedDate,
            'deadline' => $this->deadline,
            'isChain' => $this->isChain,
            'isOnTime' => $this->isOnTime,
            'hasOutsource' => $this->hasOutsource,
            'hasInvestors' => $this->hasInvestors,
            'workersCount' => $this->workersCount,
            'servicesCount' => $this->servicesCount,
            'paymentFirstStep' => $this->paymentFirstStep,
            'paymentSecondStep' => $this->paymentSecondStep,
            'paymentThirdStep' => $this->paymentThirdStep,
            'paymentFourthStep' => $this->paymentFourthStep,
            'comment' => $this->comment,
            'efficiencyValue' => $this->efficiencyValue,
        ];
    }

    /**
     * Получаем уникальные свойства
     */
    public function unique(): array
    {
        return [
            'type_id' => $this->typeId,
            'title' => $this->title,
            'creation_date' => $this->creationDate,
            'contracted_date' => $this->contractedDate,
        ];
    }

    /**
     * Создаем геттеры для всех НЕ уникальных свойств.
     */
    public function getDeadline(): null|Carbon|\DateTime
    {
        return $this->deadline;
    }

    public function isChain(): ?bool
    {
        return $this->isChain;
    }

    public function isOnTime(): ?bool
    {
        return $this->isOnTime;
    }

    public function hasOutsource(): ?bool
    {
        return $this->hasOutsource;
    }

    public function hasInvestors(): ?bool
    {
        return $this->hasInvestors;
    }

    public function getWorkersCount(): ?int
    {
        return $this->workersCount;
    }

    public function getServicesCount(): ?int
    {
        return $this->servicesCount;
    }

    public function getPaymentFirstStep(): ?float
    {
        return $this->paymentFirstStep;
    }

    public function getPaymentSecondStep(): ?float
    {
        return $this->paymentSecondStep;
    }

    public function getPaymentThirdStep(): ?float
    {
        return $this->paymentThirdStep;
    }

    public function getPaymentFourthStep(): ?float
    {
        return $this->paymentFourthStep;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getEfficiencyValue(): ?float
    {
        return $this->efficiencyValue;
    }
}
