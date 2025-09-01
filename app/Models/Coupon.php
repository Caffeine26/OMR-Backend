<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'name_english',
        'min_order',
        'discount_type',
        'discount',
        'start_date',
        'end_date',
        'active',
    ];

    // Optional: if you want to link coupons to orders
    public function orders()
    {   
        return $this->hasMany(Order::class);
    }
}
