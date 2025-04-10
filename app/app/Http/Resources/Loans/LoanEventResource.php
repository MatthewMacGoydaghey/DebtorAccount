<?php

namespace App\Http\Resources\Loans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanEventResource extends JsonResource
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
            'description' => $this->description,
            'loan_event_action' => $this->action,
            'loan_event_type' => [
                "id" => $this->type->id,
                "content" => $this->type->content
            ],
            'created_at' => $this->created_at
       ];

    }
}
