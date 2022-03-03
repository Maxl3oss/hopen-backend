<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_qty',
        'product_price',
    ];
    public function orders_cart()
    {
        return $this->belongsTo('App\Models\Product', 'product_id')->select(['id', 'name', 'image', 'slug']);
    }
}
