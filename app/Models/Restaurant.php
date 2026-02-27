<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    //
    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
