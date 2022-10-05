<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";

    protected $fillable = [
        'total',
        'handphone',
        'alamat',
        'status',
        'nama_penerima',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];

    protected $appends = ['total_display'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_has_products', 'transaction_id', 'product_id', 'id', 'id')->withPivot('qty')->withTrashed();
    }

    public function getTotalDisplayAttribute()
    {
        return number_format($this->total, 0, ",", ".");
    }
}
