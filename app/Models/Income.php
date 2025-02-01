<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'bulan',
        'amount',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['tahun'] ?? false, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        });
    }

    // Relasi ke IncomeDetail
    public function incomeDetail()
    {
        return $this->hasMany(IncomeDetail::class);
    }
}
