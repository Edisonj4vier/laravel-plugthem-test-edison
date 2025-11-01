<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Define la forma del JSON para una Pregunta
        return [
            'id' => $this->id,
            'text' => $this->text,
            'type' => $this->type,
            'survey_id' => $this->survey_id,
        ];
    }
}
