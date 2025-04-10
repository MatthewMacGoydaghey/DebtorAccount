<?php

namespace App\Models\Loans;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_contract',
        'lender',
        'contract_number',
        'loan_amount',
        'total_outstanding_amount',
        'remaining_amount',
        'loan_status_id'
    ];

    protected $casts = [
        'date_of_contract' => 'date',
        'loan_amount' => 'decimal:2',
        'total_outstanding_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];


    public function events()
    {
        return $this->hasMany(LoanEvent::class, 'loan_id');
    }

    public function status()
    {
        return $this->belongsTo(LoanStatuse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
