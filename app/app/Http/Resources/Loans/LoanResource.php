<?php

namespace App\Http\Resources\Loans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'user_id' => $this->user->id,
            'date_of_contract' => $this->date_of_contract,
            'lender' => $this->lender,
            'contract_number' => $this->contract_number,
            'loan_amount' => $this->loan_amount,
            'total_outstanding_amount' => $this->total_outstanding_amount,
            'remaining_amount' => $this->remaining_amount,
            'loan_status' => $this->status
       ];

    }
}
