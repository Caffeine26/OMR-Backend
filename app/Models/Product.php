<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'image_url', 'price', 'rate', 'type', 'parent_id'
    ];

    // Category relation
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Order items relation
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Promotion items relation
    public function promotionItems()
    {
        return $this->hasMany(PromotionItem::class);
    }

    // Order item options
    public function orderItemOptions()
    {
        return $this->hasMany(OrderItemOption::class);
    }

    // -------------------------
    // Customization relations
    // -------------------------

    // For a menu item: get all modify/add_on items linked to it
    public function options()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    // For a modify/add_on item: get the parent menu item
    public function menu()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }
}
