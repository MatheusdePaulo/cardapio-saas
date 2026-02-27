<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Restaurant;

class Product extends Model
{
    protected $fillable = [
        'restaurant_id','category_id','name','slug','description','price_cents','image_path','is_active','sort_order'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
