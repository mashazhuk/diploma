<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lesson_name' => $this->lesson_name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'conf_id' => $this->conference_id,
            'conf_pass' => $this->conference_password,
        ];
    }
}
