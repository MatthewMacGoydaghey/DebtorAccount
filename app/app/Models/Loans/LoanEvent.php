php
Copy
<?php

namespace App\Models;

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

    /**
     * Get the action associated with the loan event.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(LoanEventAction::class, 'loan_event_action_id');
    }
}