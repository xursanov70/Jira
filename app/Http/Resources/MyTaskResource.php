<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'task_name' => $this->task_name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'start_task' => $this->start_task,
            'end_task' => $this->end_task,
            'original_task' => $this->original_task,
            'high' => $this->high,
        ];
    }
}
