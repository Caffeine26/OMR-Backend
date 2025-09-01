<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'product_id',
        'name',
        'price',
    ];

    // Option belongs to an order item
    public function Item()
    {
        return $this->belongsTo(OrderItem::class);
    }

    // Option belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
