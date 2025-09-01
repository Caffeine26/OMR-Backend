<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_english',
        'name_khmer',
        'detail_des',
        'date',
        'discount',
        'type',
        'start_date',
        'end_date',
        'image',
    ];

    // Optional: a promotion may have many products or promotion items
    public function promotionItems()
    {
        return $this->hasMany(PromotionItem::class);
    }

    // Optional: if linked to categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_category', 'promotion_id', 'category_id');
    }
}
