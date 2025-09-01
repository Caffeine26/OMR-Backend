<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table_id','status', 'total_price', 'special_note', 
        'order_time', 'estimate_time', 'coupon_id', 'customer_id',
    ];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }
    public function customers(){
        return $this->belongsTo(customers::class);
    }
    public function table(){
        return $this->belongsTo(tables::class);
    }
}
