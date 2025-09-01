<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_khmer',
        'name_english',
        'image',
    ];
    protected $appends = ['image_url'];
     public function getImageUrlAttribute()
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }

    // One category can have many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Optional: if promotions are linked to categories
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }
}
