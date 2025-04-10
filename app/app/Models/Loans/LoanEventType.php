<?php

namespace App\Models\Loans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'active'
    ];


    public function event()
    {
        return $this->hasOne(LoanEvent::class);
    }

}