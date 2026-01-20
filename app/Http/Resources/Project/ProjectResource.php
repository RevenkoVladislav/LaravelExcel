<?php

namespace App\Http\Resources\Project;

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
            'creation_date' => $this->creation_date ? $this->creation_date->format('Y-m-d') : null,
            'contracted_date' => $this->contracted_date ? $this->contracted_date->format('Y-m-d') : null,
            'deadline' => $this->deadline ? $this->deadline->format('Y-m-d') : null,
            'is_chain' => $this->is_chain ? 'Yes' : 'No',
            'is_on_time' => $this->is_on_time ? 'Yes' : 'No',
            'has_outsource' => $this->has_outsource ? 'Yes' : 'No',
            'has_investors' => $this->has_investors ? 'Yes' : 'No',
            'workers_count' => $this->workers_count,
            'services_count' => $this->services_count,
            'payment_first_step' => $this->payment_first_step,
            'payment_second_step' => $this->payment_second_step,
            'payment_third_step' => $this->payment_third_step,
            'payment_fourth_step' => $this->payment_fourth_step,
            'comment' => $this->comment,
            'efficiency_value' => $this->efficiency_value,
        ];
    }
}
