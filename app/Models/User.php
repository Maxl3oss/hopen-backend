<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'username',
        'email',
        'email_verified_at',
        'password',
        'tel',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship
     */
    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('id', 'desc');
    }
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('id', 'desc');
    }
    public function customers()
    {
        return $this->hasMany(Customer::class)->orderBy('id', 'desc');
    }
}
