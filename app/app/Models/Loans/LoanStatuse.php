<?php

namespace App\Models\Loans;

use Illuminate\Database\Eloquent\Model;

class LoanStatuse extends Model
{
    protected $fillable = [
        'id',
        'name'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
