<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_key',
        'order_qty',
        'order_total',
        'order_paytype',
        'order_image',
        'order_status',
        'user_id',
    ];
    public function user_orders()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->select(['id', 'fullname', 'email', 'avatar']);
    }
}
