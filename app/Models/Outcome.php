<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    use HasFactory;
    protected $fillable = [
        'tahun',
        'bulan',
        'total_amount',
    ];

    // Relasi ke OutcomeDetail
    public function outcomeDetail()
    {
        return $this->hasMany(OutcomeDetail::class);
    }
}
