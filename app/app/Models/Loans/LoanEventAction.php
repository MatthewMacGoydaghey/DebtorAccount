<?php

namespace App\Models;

use App\Models\Loans\LoanEventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanEventAction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    
    public function events(): HasMany
    {
        return $this->hasMany(LoanEvent::class);
    }

    public function type()
    {
        return $this->belongsTo(LoanEventType::class, 'id');
    }
}