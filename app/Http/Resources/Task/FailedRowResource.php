<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FailedRowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'row' => $this->row,
            'message' => $this->message,
            'task_id' => $this->task->id,
            'date' => $this->created_at->format('Y-m-d'),
        ];
    }
}
