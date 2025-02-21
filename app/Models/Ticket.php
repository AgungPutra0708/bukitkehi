<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo',
        'name',
        'price',
        'description',
        'status',
        'type',
    ];
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Menghitung rating rata-rata per tiket
    public function averageRating()
    {
        return $this->hasMany(Rating::class)->avg('rating') ?? 0;
    }

    // Menghitung jumlah klik tiket
    public function totalClicks()
    {
        return $this->hasMany(Clicked::class)->count();
    }

    // Menghitung jumlah checkout tiket
    public function totalCheckout()
    {
        return $this->hasMany(Cart::class)->sum('quantity');
    }
}
