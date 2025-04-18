<?php

namespace App\Models\Loans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_event_action_id',
        'description'
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function action(): BelongsTo
    {
        return $this->belongsTo(LoanEventAction::class, 'loan_event_action_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LoanEventType::class, 'loan_event_type_id');
    }
}