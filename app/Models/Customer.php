<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'fullname',
        'tel',
        'address',
        'user_id',
    ];
    public function user_customers()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->select(['id', 'fullname', 'email', 'avatar']);
    }
}
