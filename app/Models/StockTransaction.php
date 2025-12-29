<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'spare_part_id',
        'user_id', 
        'supplier_id', 
        'type', 
        'quantity', 
        'reference', 
        'status',
        'notes'
    ];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
