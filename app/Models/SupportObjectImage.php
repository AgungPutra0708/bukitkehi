<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportObjectImage extends Model
{
    use HasFactory;
    protected $fillable = ['object_id', 'image'];

    public function object()
    {
        return $this->belongsTo(SupportObject::class, 'object_id');
    }
}
