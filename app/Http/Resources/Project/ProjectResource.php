<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Type\TypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => new TypeResource($this->type),
            'title' => $this->title,
            'creation_date' => $this->creation_date?->format('Y-m-d'),
            'contracted_date' => $this->contracted_date?->format('Y-m-d'),
            'deadline' => $this->deadline?->format('Y-m-d'),
            'is_chain' => $this->is_chain,
            'is_on_time' => $this->is_on_time,
            'has_outsource' => $this->has_outsource,
            'has_investors' => $this->has_investors,
            'workers_count' => $this->workers_count,
            'services_count' => $this->services_count,
            'total_payments' => $this->total_payments,
            'payments' => $this->paymentsForView(),
            'comment' => $this->comment,
            'efficiency_value' => $this->efficiency_value,
        ];
    }
}
