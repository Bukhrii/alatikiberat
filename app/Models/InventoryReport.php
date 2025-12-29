<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryReport extends Model
{
    protected $fillable = [
        'manager_id', 
        'report_name', 
        'report_type', 
        'start_period', 
        'end_period', 
        'report_data',  
        'file_path'
    ];

    // Mengubah JSON di database menjadi array secara otomatis
    protected $casts = [
        'report_data' => 'array',
        'start_period' => 'date',
        'end_period' => 'date',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id'); // Kunci asing khusus
    }
}
