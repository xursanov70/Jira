<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NowContinueTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'task_id' => $this->task_id,
            'user' => $this->username,
            'task_name' => $this->task_name,
            'description' => $this->description,
            'category_name' => $this->category_name,
            'start_task' => $this->start_task,
            'original_task' => $this->original_task,
            'high' => $this->high,
        ];
    }
}
