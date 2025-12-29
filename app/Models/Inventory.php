<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'spare_part_id', 
        'stock', 
        'min_stock', 
        'location_rack'
    ];

    // Relasi kembali ke Master Data
    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    // Scope untuk mempermudah pengecekan stok menipis (UC-07)
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }
}
