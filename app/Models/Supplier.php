<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 
        'contact_person', 
        'phone_number',
        'address', 
        'rating'
    ];

    // Relasi ke transaksi barang masuk (UC-02)
    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
