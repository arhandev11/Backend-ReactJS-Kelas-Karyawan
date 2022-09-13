<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
        'harga_diskon',
        'is_diskon',
        'image_url',
        'stock',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];

    public function getHargaAttribute($value)
    {
        return number_format($value, 0, ",", ".");
    }

    public function getHargaDiskonAttribute($value)
    {
        return number_format($value, 0, ",", ".");
    }
}
