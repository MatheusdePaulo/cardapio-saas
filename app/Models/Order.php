<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id','table_id','order_type','status',
        'customer_name','customer_phone','delivery_address',
        'subtotal_cents','total_cents','public_token'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->public_token)) {
                $model->public_token = Str::random(32);
            }
        });
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
