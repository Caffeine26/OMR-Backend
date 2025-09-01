<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'coupon_id',
        'started_date',
        'ended_date',
        'status',
    ];

    // CustomerCoupon belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // CustomerCoupon belongs to a coupon
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
