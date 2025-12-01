<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceJamaah extends Model
{
    use HasFactory;

    protected $table = 'attendance_jamaah';

    protected $fillable = [
        'jamaah_id',
        'tanggal',
        'sesi',
        'status',
        'catatan',
        'created_by',
    ];

    // Relasi ke jamaah
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }

    // Relasi ke user (TL / petugas)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
