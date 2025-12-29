<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $fillable = [
        'part_number', 
        'name', 
        'category', 
        'brand', 
        'unit_price'
    ];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
