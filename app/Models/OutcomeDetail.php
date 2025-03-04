<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutcomeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'outcome_id',
        'name',
        'amount',
    ];

    // Relasi ke Outcome
    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }
}
