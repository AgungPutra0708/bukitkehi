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
}
