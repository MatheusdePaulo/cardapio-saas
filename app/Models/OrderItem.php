<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','product_id','name','unit_price_cents','quantity','total_cents'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->belongsToMany(\App\Models\Option::class, 'option_order_item')
            ->withPivot(['name', 'price_cents'])
            ->withTimestamps();
    }
}
