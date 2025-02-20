<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportObject extends Model
{
    use HasFactory;
    protected $table = 'objek_pendukung';
    protected $fillable = ['name', 'tipe', 'longitude', 'latitude', 'address', 'description', 'image', 'user_id'];
    public function images()
    {
        return $this->hasMany(SupportObjectImage::class, 'object_id');
    }
}
