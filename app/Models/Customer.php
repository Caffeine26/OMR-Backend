<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'telegram_url',
        'status',
    ];

    // One customer can have many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Optional: if you want to link coupons to a customer
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
