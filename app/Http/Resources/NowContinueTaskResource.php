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
            'user_id' => $this->username,
            'category_id' => $this->category_name,
            'start_task' => $this->start_task,
            'original_task' => $this->original_task,
            'high' => $this->high,
        ];
    }
}
