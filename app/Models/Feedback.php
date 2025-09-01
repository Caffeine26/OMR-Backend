<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'table_id',
        'customer_id',
        'comment',
        'rating',
    ];

    // Feedback belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Feedback belongs to a table
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Feedback may belong to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
