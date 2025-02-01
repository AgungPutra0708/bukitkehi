<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'ticket_id',
        'facilities_id',
        'type',
        'metode',
        'harga_satuan',
        'jumlah',
        'amount',
    ];

    // Relasi ke Income
    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    // Relasi ke Fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Facility::class, 'facilities_id');
    }

    // Relasi ke Tiket
    public function tiket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
