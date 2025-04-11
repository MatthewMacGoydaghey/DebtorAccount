<?php

namespace App\Http\Resources\Loans;

use App\Http\Resources\Resource;
use Illuminate\Http\Request;

class LoanEventResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->full)
        return [
            'id' => $this->id,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'loan_event_action' => $this->action,
            'loan_event_type' => [
                "id" => $this->type->id,
                "content" => $this->type->content
            ],
       ];

       return [
        'id' => $this->id,
        'description' => $this->description,
        'loan_event_action_id' => $this->loan_event_action_id,
        'loan_event_type_id' => $this->loan_event_type_id,
        'created_at' => $this->created_at
    ];
    }
}
