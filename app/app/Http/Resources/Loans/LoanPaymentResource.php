<?php

namespace App\Http\Resources\Loans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanPaymentResource extends JsonResource
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
            'date' => $this->date,
            'payer_name' => $this->payer_name,
            'purpose_of_payment' => $this->purpose_of_payment,
            'loan_id' => $this->loan_id,
            'payment_status' => $this->status
       ];

    }
}