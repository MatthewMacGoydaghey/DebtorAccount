<?php

namespace App\Models\Payments;

use App\Models\Loans\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'date',
        'player_name',
        'purpose_of_payment',
        'amount',
        'payment_status_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date'
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateBetween($query, string $from, string $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $statusId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, int $statusId)
    {
        return $query->where('payment_status_id', $statusId);
    }
}