<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ticket_id',
        'quantity',
        'checkout_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facilities_id', 'id');
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'cart_id');
    }
}
