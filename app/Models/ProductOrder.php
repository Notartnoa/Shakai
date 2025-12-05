<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'creator_id',
        'total_price',
        'is_paid',
        'proof',
        'snap_token', // Tambahkan ini
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
