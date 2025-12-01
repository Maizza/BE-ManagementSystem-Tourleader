<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'koper_code',
        'owner_name',   // ✅ baru
        'owner_phone',  // ✅ baru
        'kloter',       // ✅ baru
        'scanned_at',
        'tourleader_id' // tambahkan ini
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

     public function tourleader()
    {
        return $this->belongsTo(Tourleader::class, 'tourleader_id');
    }
}
